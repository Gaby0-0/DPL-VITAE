<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Insumo;
use App\Models\Empresa;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $empresa = Empresa::first();
        $tiposAmbulancia = \App\Models\TipoAmbulancia::orderByDesc('costo_base')->get();
        $tiposDisponibles = \App\Models\TipoAmbulancia::whereHas('ambulancias', function ($q) {
                $q->where('estado', 'Disponible');
            })->orderByDesc('costo_base')->get();
        return view('cotizaciones.create', compact('empresa', 'tiposAmbulancia', 'tiposDisponibles', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:150',
            'telefono'               => 'required|string|max:20',
            'correo'                 => 'nullable|email|max:150',
            'tipo_servicio'          => 'required|string|max:100',
            'descripcion'            => 'nullable|string',
            'fecha_requerida'        => 'nullable|date|after_or_equal:today',
            'origen'                 => 'nullable|string|max:500',
            'lat_origen'             => 'nullable|numeric|between:-90,90',
            'lng_origen'             => 'nullable|numeric|between:-180,180',
            'destino'                => 'nullable|string|max:500',
            'lat_destino'            => 'nullable|numeric|between:-90,90',
            'lng_destino'            => 'nullable|numeric|between:-180,180',
            'personas'                  => 'nullable|integer|min:1',
            'padecimientos_paciente'    => 'nullable|string',
            'tipo_ambulancia_preferida' => 'nullable|string|max:150',
        ]);

        $data                = $request->all();
        $data['user_id']     = auth()->id();
        $data['numero_guia'] = Cotizacion::generarGuia();
        $data['estado']      = 'Pendiente';

        if (!empty($data['lat_origen']) && !empty($data['lng_origen']) &&
            !empty($data['lat_destino']) && !empty($data['lng_destino'])) {
            $data['km_distancia'] = Cotizacion::haversineKm(
                $data['lat_origen'], $data['lng_origen'],
                $data['lat_destino'], $data['lng_destino']
            );
        }

        $cotizacion = Cotizacion::create($data);

        return redirect()->route('cotizaciones.gracias')
            ->with('numero_guia', $data['numero_guia'])
            ->with('cotizacion_id', $cotizacion->id_cotizacion);
    }

    public function gracias()
    {
        $empresa    = Empresa::first();
        $numeroGuia = session('numero_guia');
        return view('cotizaciones.gracias', compact('empresa', 'numeroGuia'));
    }

    public function rastrear(Request $request)
    {
        $empresa    = Empresa::first();
        $cotizacion = null;
        $buscado    = false;

        if ($request->filled('guia')) {
            $buscado    = true;
            $cotizacion = Cotizacion::where('numero_guia', strtoupper(trim($request->guia)))->first();
        }

        return view('cotizaciones.rastrear', compact('empresa', 'cotizacion', 'buscado'));
    }

    public function index()
    {
        $cotizaciones = Cotizacion::latest()->paginate(20);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function show(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado === 'Pendiente') {
            $cotizacion->update(['estado' => 'En revisión']);
        }

        $empresa     = Empresa::first();
        $kmCalculado = null;

        if ($cotizacion->lat_origen && $cotizacion->lng_origen &&
            $cotizacion->lat_destino && $cotizacion->lng_destino) {
            $kmCalculado = Cotizacion::haversineKm(
                $cotizacion->lat_origen, $cotizacion->lng_origen,
                $cotizacion->lat_destino, $cotizacion->lng_destino
            );
        }

        $fecha = $cotizacion->fecha_requerida ?? now()->toDateString();

        // Ambulancias disponibles (activas, sin servicio ese día)
        $ambulancias = Ambulancia::with('tipo', 'operador.usuario')
            ->where('estado', 'Disponible')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        // Paramédicos disponibles ese día
        $paramedicos = Paramedico::with('usuario')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $insumos = Insumo::orderBy('nombre_insumo')->get();

        return view('cotizaciones.show', compact(
            'cotizacion', 'empresa', 'kmCalculado',
            'ambulancias', 'paramedicos', 'insumos'
        ));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'estado'    => 'required|in:Pendiente,En revisión,Aceptada,Cancelada',
            'respuesta' => 'nullable|string',
        ]);

        $cotizacion->update($request->only('estado', 'respuesta'));
        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización actualizada.');
    }

    public function aceptar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'km_distancia'   => 'required|numeric|min:0',
            'costo_km_unitario' => 'required|numeric|min:0',
            'id_ambulancia'  => 'nullable|exists:ambulancia,id_ambulancia',
            'horas_servicio' => 'nullable|numeric|min:0',
            'paramedicos_ids' => 'nullable|array',
            'paramedicos_ids.*' => 'exists:paramedico,id_usuario',
            'insumos'        => 'nullable|array',
            'incluye'        => 'required|string',
            'respuesta'      => 'nullable|string',
            'nombre_paciente'=> 'nullable|string|max:200',
            'anticipo'       => 'nullable|numeric|min:0',
        ]);

        $km       = (float) $request->km_distancia;
        $tarifaKm = (float) $request->costo_km_unitario;
        $costoKm  = round($km * $tarifaKm, 2);
        $horas    = (float) ($request->horas_servicio ?? 1);

        // costo_base del tipo + salario_hora del operador * horas
        $costoAmbulancia = 0;
        if ($request->id_ambulancia) {
            $amb = Ambulancia::with('tipo', 'operador')->find($request->id_ambulancia);
            if ($amb) {
                $costoTipo = (float) ($amb->tipo->costo_base ?? 0);
                $salarioOp = (float) ($amb->operador->salario_hora ?? 0);
                $costoAmbulancia = round($costoTipo + $salarioOp * $horas, 2);
            }
        }

        $costoParamedicos = 0;
        if ($request->paramedicos_ids) {
            $paramedicos = Paramedico::whereIn('id_usuario', $request->paramedicos_ids)->get();
            foreach ($paramedicos as $p) {
                $costoParamedicos += (float) $p->salario_hora * $horas;
            }
            $costoParamedicos = round($costoParamedicos, 2);
        }

        $costoInsumos = 0;
        $insumosGuardados = [];
        if ($request->insumos) {
            foreach ($request->insumos as $idInsumo => $datos) {
                if (empty($datos['seleccionado'])) continue;
                $insumo   = Insumo::find($idInsumo);
                if (!$insumo) continue;
                $cantidad = max(1, (int) ($datos['cantidad'] ?? 1));
                $subtotal = round($insumo->costo_unidad * $cantidad, 2);
                $costoInsumos += $subtotal;
                $insumosGuardados[] = [
                    'id'        => $idInsumo,
                    'nombre'    => $insumo->nombre_insumo,
                    'cantidad'  => $cantidad,
                    'costo_u'   => $insumo->costo_unidad,
                    'subtotal'  => $subtotal,
                ];
            }
            $costoInsumos = round($costoInsumos, 2);
        }

        $costoTotal = $costoKm + $costoAmbulancia + $costoParamedicos + $costoInsumos;

        $cotizacion->update([
            'estado'               => 'Aceptada',
            'km_distancia'         => $km,
            'costo_km_unitario'    => $tarifaKm,
            'id_ambulancia'        => $request->id_ambulancia,
            'horas_servicio'       => $request->horas_servicio,
            'paramedicos_ids'      => $request->paramedicos_ids ?? [],
            'insumos_seleccionados'=> $insumosGuardados,
            'costo_ambulancia'     => $costoAmbulancia,
            'costo_paramedicos'    => $costoParamedicos,
            'costo_insumos'        => $costoInsumos,
            'costo'                => $costoTotal,
            'anticipo'             => $request->anticipo ?: null,
            'incluye'              => $request->incluye,
            'respuesta'            => $request->respuesta,
            'nombre_paciente'      => $request->nombre_paciente,
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización aceptada. Costo calculado: $' . number_format($costoTotal, 2) . ' MXN');
    }

    public function rechazar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'respuesta' => 'required|string|max:1000',
        ]);

        $cotizacion->update([
            'estado'    => 'Cancelada',
            'respuesta' => $request->respuesta,
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización rechazada.');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada.');
    }

    public function misSolicitudes()
    {
        $empresa = Empresa::first();
        $cotizaciones = Cotizacion::where('user_id', auth()->id())->latest()->paginate(10);
        return view('cotizaciones.mis-solicitudes', compact('empresa', 'cotizaciones'));
    }

    public function miEstado(Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        $empresa = Empresa::first();
        return view('cotizaciones.mi-estado', compact('cotizacion', 'empresa'));
    }

    public function descargar(Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        $empresa = Empresa::first();
        return view('cotizaciones.pdf-cliente', compact('cotizacion', 'empresa'));
    }

    public function confirmar(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        abort_if($cotizacion->estado !== 'Aceptada' || $cotizacion->decision_cliente !== null, 403);

        $rules = ['comentario_cliente' => 'nullable|string|max:1000'];

        if ($cotizacion->tipo_servicio === 'Traslado') {
            $rules = array_merge($rules, [
                'paciente_nombre'     => 'required|string|max:200',
                'paciente_nacimiento' => 'required|date',
                'paciente_curp'       => 'nullable|string|max:18',
                'paciente_tipo_sangre'=> 'nullable|string|max:10',
                'paciente_diagnostico'=> 'required|string|max:1000',
                'paciente_alergias'   => 'nullable|string|max:500',
                'paciente_medico'     => 'nullable|string|max:200',
            ]);
        }

        $validated = $request->validate($rules);

        $datosPaciente = null;
        if ($cotizacion->tipo_servicio === 'Traslado') {
            $datosPaciente = [
                'nombre'      => $validated['paciente_nombre'],
                'nacimiento'  => $validated['paciente_nacimiento'],
                'curp'        => $validated['paciente_curp'] ?? null,
                'tipo_sangre' => $validated['paciente_tipo_sangre'] ?? null,
                'diagnostico' => $validated['paciente_diagnostico'],
                'alergias'    => $validated['paciente_alergias'] ?? null,
                'medico'      => $validated['paciente_medico'] ?? null,
            ];
        }

        $cotizacion->update([
            'decision_cliente'   => 'confirmada',
            'comentario_cliente' => $validated['comentario_cliente'] ?? null,
            'datos_paciente'     => $datosPaciente,
        ]);

        return redirect()->route('cotizaciones.mi-estado', $cotizacion)
            ->with('success', '¡Servicio confirmado! Nuestro equipo se pondrá en contacto contigo.');
    }

    public function declinar(Request $request, Cotizacion $cotizacion)
    {
        abort_if($cotizacion->user_id !== auth()->id(), 403);
        abort_if($cotizacion->estado !== 'Aceptada' || $cotizacion->decision_cliente !== null, 403);

        $request->validate(['comentario_cliente' => 'nullable|string|max:1000']);

        $cotizacion->update([
            'decision_cliente'   => 'declinada',
            'comentario_cliente' => $request->comentario_cliente,
        ]);

        return redirect()->route('cotizaciones.mi-estado', $cotizacion)
            ->with('info', 'Has declinado la propuesta. Puedes contactarnos si deseas más información.');
    }
}
