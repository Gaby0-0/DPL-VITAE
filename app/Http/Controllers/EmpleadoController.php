<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function miPanel()
    {
        $user = auth()->user();
        $user->load(['operador', 'paramedico']);

        $rol         = null;
        $servicios   = collect();
        $ambulancias = collect();
        $reservas    = collect();

        if ($user->operador) {
            $rol = 'operador';

            $servicios = Servicio::where('id_operador', $user->operador->id_usuario)
                ->with(['evento', 'paramedicos.usuario', 'cliente.usuario', 'ambulancia.tipo', 'cotizacion'])
                ->orderBy('fecha_hora')
                ->get();

            $ambulancias = $servicios->pluck('ambulancia')->filter()->unique('id_ambulancia')->values();

            $reservas = Cotizacion::where('id_operador', $user->operador->id_usuario)
                ->where('decision_cliente', 'confirmada')
                ->whereNotNull('fecha_requerida')
                ->with(['ambulancia.tipo'])
                ->orderBy('fecha_requerida')
                ->get();

        } elseif ($user->paramedico) {
            $rol = 'paramedico';

            $servicios = $user->paramedico->servicios()
                ->with(['evento', 'ambulancia.tipo', 'cliente.usuario', 'operador.usuario', 'pacientes.padecimientos', 'insumos'])
                ->orderBy('fecha_hora')
                ->get();

            $idStr = (string) $user->paramedico->id_usuario;
            $reservas = Cotizacion::where('decision_cliente', 'confirmada')
                ->whereNotNull('fecha_requerida')
                ->with(['ambulancia.tipo'])
                ->where(function ($q) use ($idStr) {
                    $q->whereJsonContains('paramedicos_ids', $idStr)
                      ->orWhereJsonContains('paramedicos_ids', (int) $idStr);
                })
                ->orderBy('fecha_requerida')
                ->get();
        }

        $hoy       = Carbon::now();
        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes    = $hoy->copy()->endOfMonth();

        $esteMes     = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->between($inicioMes, $finMes));
        $proximos    = $servicios->filter(fn($s) => Carbon::parse($s->fecha_hora)->isFuture() && $s->estado !== 'Cancelado')
                                 ->sortBy('fecha_hora')
                                 ->take(6);
        $completados = $servicios->where('estado', 'Finalizado')->count();

        $colorPorEstado = [
            'Activo'     => '#696cff',
            'Finalizado' => '#8592a3',
            'Cancelado'  => '#ff3e1d',
        ];

        $eventosServicios = $servicios->map(function ($s) use ($colorPorEstado) {
            $color  = $colorPorEstado[$s->estado] ?? '#ffab00';
            $titulo = $s->tipo ?? 'Servicio';
            if ($s->evento) {
                $titulo = 'Evento: ' . $titulo;
            }
            return [
                'id'              => 'srv-' . $s->id_servicio,
                'title'           => $titulo,
                'start'           => Carbon::parse($s->fecha_hora)->toIso8601String(),
                'end'             => $s->hora_salida
                    ? Carbon::parse($s->hora_salida)->toIso8601String()
                    : Carbon::parse($s->fecha_hora)->addHours(2)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'extendedProps'   => [
                    'tipo_evento'   => 'servicio',
                    'estado'        => $s->estado,
                    'tipo'          => $s->tipo ?? '—',
                    'ambulancia'    => $s->ambulancia?->placa ?? '—',
                    'tipo_amb'      => $s->ambulancia?->tipo?->nombre_tipo ?? '—',
                    'es_evento'     => $s->evento !== null,
                    'duracion'      => $s->evento?->duracion ?? '—',
                    'personas'      => $s->evento?->personas ?? '—',
                    'observaciones' => $s->observaciones ?? '—',
                ],
            ];
        });

        $eventosReservas = $reservas->map(function ($c) {
            $horas  = (float) ($c->horas_servicio ?? 2);
            $inicio = Carbon::parse($c->fecha_requerida . ' ' . ($c->hora_requerida ?? '00:00:00'));
            return [
                'id'              => 'cot-' . $c->id_cotizacion,
                'title'           => 'Reserva: ' . $c->tipo_servicio,
                'start'           => $inicio->toIso8601String(),
                'end'             => $inicio->copy()->addHours($horas)->toIso8601String(),
                'backgroundColor' => '#ff9f43',
                'borderColor'     => '#ff9f43',
                'extendedProps'   => [
                    'tipo_evento'    => 'reserva',
                    'guia'           => $c->numero_guia,
                    'tipo_servicio'  => $c->tipo_servicio,
                    'cliente'        => $c->nombre,
                    'telefono'       => $c->telefono,
                    'origen'         => $c->origen ?? '—',
                    'destino'        => $c->destino ?? '—',
                    'horas'          => $horas,
                    'costo'          => $c->costo ? '$' . number_format($c->costo, 2) . ' MXN' : '—',
                    'paciente'       => $c->datos_paciente['nombre'] ?? null,
                    'ambulancia'     => $c->ambulancia?->placa ?? '—',
                    'tipo_amb'       => $c->ambulancia?->tipo?->nombre_tipo ?? '—',
                    'oxigeno'        => (bool) $c->requiere_oxigeno,
                    'datos_evento'   => $c->datos_evento,
                    'km'             => $c->km_distancia ?? null,
                    'descripcion'    => $c->descripcion ?? null,
                ],
            ];
        });

        $eventosCalendario = $eventosServicios->concat($eventosReservas)->values();

        $view = $rol === 'operador' ? 'empleado.operador' : 'empleado.paramedico';

        return view($view, compact(
            'user', 'rol', 'ambulancias', 'servicios', 'reservas',
            'esteMes', 'proximos', 'completados', 'eventosCalendario'
        ));
    }

    public function finalizarServicio(Request $request, Servicio $servicio)
    {
        $operador = auth()->user()->operador;
        abort_if(!$operador || $servicio->id_operador !== $operador->id_usuario, 403);
        abort_if($servicio->estado !== 'Activo', 422, 'Este servicio ya no está activo.');

        if ($servicio->forma_pago === 'online') {
            $cotizacion   = $servicio->cotizacion;
            $totalCosto   = (float) $servicio->costo_total;
            $anticipoPagado = 0;

            if ($cotizacion && $cotizacion->mp_pago_estado === 'approved') {
                $anticipoPagado = (float) $cotizacion->anticipo;
            }

            if ($anticipoPagado < $totalCosto) {
                return back()->with('error_pago', 'El cliente aún no ha completado el pago en línea ($' . number_format($totalCosto - $anticipoPagado, 2) . ' pendiente). No se puede finalizar el servicio.');
            }
        } else {
            $request->validate(
                ['pago_confirmado' => 'required|accepted'],
                ['pago_confirmado.accepted' => 'Debes confirmar que recibiste el pago en efectivo para poder finalizar el servicio.']
            );
        }

        $servicio->update([
            'estado'      => 'Finalizado',
            'hora_salida' => now(),
        ]);

        return redirect()->route('empleado.mi-panel')
            ->with('success', 'Servicio #' . $servicio->id_servicio . ' finalizado correctamente.');
    }

    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nombre'     => 'required|string|max:100',
            'ap_paterno' => 'required|string|max:100',
            'ap_materno' => 'nullable|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'required|email|max:150|unique:users,email,' . $user->id_usuario . ',id_usuario',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('nombre', 'ap_paterno', 'ap_materno', 'telefono', 'email');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('empleado.mi-panel')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
