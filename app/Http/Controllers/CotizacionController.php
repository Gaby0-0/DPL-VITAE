<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Insumo;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CotizacionController extends Controller
{
    public function create()
    {
        $user             = auth()->user();
        $empresa          = Empresa::first();
        $tiposAmbulancia  = \App\Models\TipoAmbulancia::orderByDesc('costo_base')->get();
        $tiposDisponibles = \App\Models\TipoAmbulancia::whereHas('ambulancias', function ($q) {
                $q->where('estado', 'Disponible');
            })->orderByDesc('costo_base')->get();

        $fechasOcupadas = Cotizacion::pluck('fecha_requerida')
            ->map(fn($f) => \Carbon\Carbon::parse($f)->format('Y-m-d'))
            ->toArray();
        $avgSalarioParamedico = (float) ($empresa->tarifa_paramedico ?? 80);
        $avgSalarioOperador   = (float) ($empresa->tarifa_operador   ?? 100);

        return view('cotizaciones.create', compact(
            'empresa', 'tiposAmbulancia', 'tiposDisponibles', 'user',
            'fechasOcupadas', 'avgSalarioParamedico', 'avgSalarioOperador'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'                  => 'required|string|max:150',
            'telefono'                => 'required|string|max:20',
            'correo'                  => 'nullable|email|max:150',
            'tipo_servicio'           => 'required|string|max:100',
            'descripcion'             => 'nullable|string',
            'fecha_requerida'         => 'nullable|date|after_or_equal:today',
            'origen'                  => 'nullable|string|max:500',
            'lat_origen'              => 'nullable|numeric|between:-90,90',
            'lng_origen'              => 'nullable|numeric|between:-180,180',
            'destino'                 => 'nullable|string|max:500',
            'lat_destino'             => 'nullable|numeric|between:-90,90',
            'lng_destino'             => 'nullable|numeric|between:-180,180',
            'personas'                => 'nullable|integer|min:1',
            'padecimientos_paciente'  => 'nullable|string',
            'id_tipo_ambulancia'      => 'nullable|exists:tipo_ambulancia,id_tipo_ambulancia',
            'horas_servicio'          => 'nullable|numeric|min:0.5',
            'hora_requerida'          => 'nullable|date_format:H:i',
            'requiere_oxigeno'        => 'nullable|boolean',
        ]);


        $empresa  = Empresa::first();
        $costoKm  = (float) ($empresa->costo_km ?? 25);
        $fecha    = $request->fecha_requerida ?? Carbon::now()->addDay()->toDateString();
        $personas = (int) ($request->personas ?? 0);
        $horas    = (float) ($request->horas_servicio ?? 2);

        // Calcular distancia
        $km = 0;
        if ($request->lat_origen && $request->lng_origen &&
            $request->lat_destino && $request->lng_destino) {
            $km = Cotizacion::haversineKm(
                $request->lat_origen, $request->lng_origen,
                $request->lat_destino, $request->lng_destino
            );
        }
        // Km base → origen (desplazamiento desde nuestra base)
        $kmBase = 0;
        if ($empresa->lat_base && $empresa->lng_base && $request->lat_origen && $request->lng_origen) {
            $kmBase = Cotizacion::haversineKm(
                $empresa->lat_base, $empresa->lng_base,
                $request->lat_origen, $request->lng_origen
            );
        }
        $costoKmTotal = round(($km + $kmBase) * $costoKm, 2);

        // Bloquear recursos: Pendiente/En revisión (asignados auto) + Aceptada con reserva activa
        $reservadas = Cotizacion::whereDate('fecha_requerida', $fecha)
            ->where(function ($q) {
                $q->whereIn('estado', ['Pendiente', 'En revisión'])
                  ->orWhere(fn($q2) => $q2->where('estado', 'Aceptada')
                      ->where(fn($q3) => $q3->where('decision_cliente', 'confirmada')
                          ->orWhere(fn($q4) => $q4->whereNull('decision_cliente')
                              ->where('confirmacion_expires_at', '>', now()))));
            })
            ->get(['id_ambulancia', 'id_operador', 'paramedicos_ids']);

        $ambulanciasReservadas = $reservadas->pluck('id_ambulancia')->filter()->unique()->values();
        $operadoresReservados  = $reservadas->pluck('id_operador')->filter()->unique()->values();
        $paramedicosReservados = $reservadas->pluck('paramedicos_ids')
            ->filter()->flatten()->map(fn($v) => (int) $v)->unique()->values();

        // Auto-asignar ambulancia del tipo elegido
        $idAmbulancia = null;
        $costoAmb     = 0;
        $tipoNombre   = null;

        if ($request->id_tipo_ambulancia) {
            $tipoAmb = \App\Models\TipoAmbulancia::find($request->id_tipo_ambulancia);
            if ($tipoAmb) {
                $costoAmb   = (float) $tipoAmb->costo_base;
                $tipoNombre = $tipoAmb->nombre_tipo;
                $ambulancia = Ambulancia::where('estado', 'Disponible')
                    ->where('id_tipo_ambulancia', $request->id_tipo_ambulancia)
                    ->whereDoesntHave('servicios', fn($q) => $q->whereDate('fecha_hora', $fecha)
                        ->whereNotIn('estado', ['Cancelado', 'Finalizado']))
                    ->whereNotIn('id_ambulancia', $ambulanciasReservadas)
                    ->first();
                $idAmbulancia = $ambulancia?->id_ambulancia;
            }
        }

        // Auto-asignar operador disponible (solo cuentas activas)
        $operador = Operador::whereHas('usuario', fn($q) => $q->where('activo', true))
            ->whereDoesntHave('servicios', fn($q) => $q->where('estado', 'Activo'))
            ->whereDoesntHave('servicios', fn($q) => $q->whereDate('fecha_hora', $fecha)
                ->whereNotIn('estado', ['Cancelado', 'Finalizado']))
            ->whereNotIn('id_usuario', $operadoresReservados)
            ->inRandomOrder()
            ->first();

        // Número de paramédicos: 2 mínimo, +1 por cada 20 personas arriba de 40 (para eventos)
        $numParamedicos = 2;
        if ($request->tipo_servicio === 'Evento' && $personas > 40) {
            $numParamedicos += (int) ceil(($personas - 40) / 20);
        }

        // Auto-asignar paramédicos disponibles (solo cuentas activas)
        $paramedicos = Paramedico::whereHas('usuario', fn($q) => $q->where('activo', true))
            ->whereDoesntHave('servicios', fn($q) => $q->whereDate('fecha_hora', $fecha))
            ->whereNotIn('id_usuario', $paramedicosReservados)
            ->inRandomOrder()
            ->take($numParamedicos)
            ->get();

        $tarifaOp         = (float) ($empresa->tarifa_operador  ?? 100);
        $tarifaPm         = (float) ($empresa->tarifa_paramedico ?? 80);
        $costoPersonal    = round(($paramedicos->count() * $tarifaPm + ($operador ? $tarifaOp : 0)) * $horas, 2);
        $costoAmb         = round($costoAmb * $horas, 2);
        $paramedicosIds   = $paramedicos->pluck('id_usuario')->values()->toArray();
        $costoTotal       = round($costoKmTotal + $costoAmb + $costoPersonal, 2);

        // Texto de lo que incluye el servicio
        $incluye = [];
        if ($kmBase > 0) $incluye[] = "• Desplazamiento desde base: {$kmBase} km";
        if ($km > 0)     $incluye[] = "• Traslado de {$km} km (tarifa \${$costoKm}/km)";
        if ($tipoNombre) $incluye[] = "• Ambulancia {$tipoNombre} — {$horas} hrs";
        if ($operador) $incluye[] = "• Operador asignado — {$horas} hrs";
        if ($paramedicos->count()) $incluye[] = "• {$paramedicos->count()} paramédico(s) — {$horas} hrs";
        if ($request->boolean('requiere_oxigeno')) $incluye[] = "• Oxígeno médico requerido";

        $cotizacion = Cotizacion::create([
            'user_id'                   => auth()->id(),
            'numero_guia'               => Cotizacion::generarGuia(),
            'estado'                    => 'Pendiente',
            'nombre'                    => $request->nombre,
            'telefono'                  => $request->telefono,
            'correo'                    => $request->correo,
            'tipo_servicio'             => $request->tipo_servicio,
            'descripcion'               => $request->descripcion,
            'fecha_requerida'           => $request->fecha_requerida,
            'origen'                    => $request->origen,
            'lat_origen'                => $request->lat_origen,
            'lng_origen'                => $request->lng_origen,
            'destino'                   => $request->destino,
            'lat_destino'               => $request->lat_destino,
            'lng_destino'               => $request->lng_destino,
            'personas'                  => $request->personas,
            'padecimientos_paciente'    => $request->padecimientos_paciente,
            'tipo_ambulancia_preferida' => $tipoNombre,
            'id_tipo_ambulancia'        => $request->id_tipo_ambulancia ?: null,
            'horas_servicio'            => $horas,
            'requiere_oxigeno'          => $request->boolean('requiere_oxigeno'),
            'km_distancia'              => $km > 0 ? $km : null,
            'costo_km_unitario'         => $costoKm,
            'id_ambulancia'             => $idAmbulancia,
            'id_operador'               => $operador?->id_usuario,
            'paramedicos_ids'           => $paramedicosIds,
            'costo_ambulancia'          => $costoAmb,
            'costo_paramedicos'         => $costoPersonal,
            'costo'                     => $costoTotal,
            'incluye'                   => implode("\n", $incluye),
            'hora_requerida'            => $request->hora_requerida,
        ]);

        return redirect()->route('cotizaciones.gracias')
            ->with('numero_guia', $cotizacion->numero_guia)
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

    public function index(Request $request)
    {
        $cotizaciones = Cotizacion::latest()
        ->when($request->estado, function ($q, $estado){
            $q->where('estado', $estado);
        })
        ->paginate(8)->withQueryString();

        $estados = [
            'Pendiente' => 'Pendientes',
            'Aceptada' => 'Aceptadas',
            'En revisión' => 'En revisión',
            'Cancelada' => 'Canceladas' 
        ];

        return view('cotizaciones.index', compact('cotizaciones', 'estados'));
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

        // Bloquear recursos: Pendiente/En revisión (asignados auto) + Aceptada con reserva activa
        $reservadas = Cotizacion::whereDate('fecha_requerida', $fecha)
            ->where('id_cotizacion', '!=', $cotizacion->id_cotizacion)
            ->where(function ($q) {
                $q->whereIn('estado', ['Pendiente', 'En revisión'])
                  ->orWhere(fn($q2) => $q2->where('estado', 'Aceptada')
                      ->where(fn($q3) => $q3->where('decision_cliente', 'confirmada')
                          ->orWhere(fn($q4) => $q4->whereNull('decision_cliente')
                              ->where('confirmacion_expires_at', '>', now()))));
            })
            ->get(['id_ambulancia', 'id_operador', 'paramedicos_ids']);

        $ambulanciasReservadas = $reservadas->pluck('id_ambulancia')->filter()->unique()->values();
        $operadoresReservados  = $reservadas->pluck('id_operador')->filter()->unique()->values();
        $paramedicosReservados = $reservadas->pluck('paramedicos_ids')
            ->filter()
            ->flatten()
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();

        // Tipo solicitado por el cliente
        $tipoSolicitado = $cotizacion->id_tipo_ambulancia
            ? \App\Models\TipoAmbulancia::find($cotizacion->id_tipo_ambulancia)
            : null;

        // Ambulancias disponibles: solo del tipo solicitado por el cliente
        $ambulancias = Ambulancia::with('tipo')
            ->where('estado', 'Disponible')
            ->when($tipoSolicitado, fn($q) => $q->where('id_tipo_ambulancia', $tipoSolicitado->id_tipo_ambulancia))
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha)
                  ->whereNotIn('estado', ['Cancelado', 'Finalizado']);
            })
            ->whereNotIn('id_ambulancia', $ambulanciasReservadas)
            ->get();

        // Operadores disponibles: activos, sin servicio activo, sin servicio ese día, sin cotización que los aparte
        $operadores = Operador::with('usuario')
            ->whereHas('usuario', fn($q) => $q->where('activo', true))
            ->whereDoesntHave('servicios', function ($q) {
                $q->where('estado', 'Activo');
            })
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha)
                  ->whereNotIn('estado', ['Cancelado', 'Finalizado']);
            })
            ->whereNotIn('id_usuario', $operadoresReservados)
            ->get();

        // Sugerencia aleatoria (respeta selección previa si ya fue aceptada)
        $operadorSugerido = $cotizacion->id_operador
            ?? ($operadores->isNotEmpty() ? $operadores->random()->id_usuario : null);

        // Paramédicos disponibles ese día: activos, sin servicio y sin cotización que los aparte
        $paramedicos = Paramedico::with('usuario')
            ->whereHas('usuario', fn($q) => $q->where('activo', true))
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->whereNotIn('id_usuario', $paramedicosReservados)
            ->get();

        $insumos = Insumo::orderBy('nombre_insumo')->get();

        return view('cotizaciones.show', compact(
            'cotizacion', 'empresa', 'kmCalculado',
            'ambulancias', 'tipoSolicitado', 'operadores', 'operadorSugerido',
            'paramedicos', 'insumos'
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
            'km_distancia'      => 'required|numeric|min:0',
            'costo_km_unitario' => 'required|numeric|min:0',
            'id_ambulancia'     => 'nullable|exists:ambulancia,id_ambulancia',
            'id_operador'       => 'required|exists:operador,id_usuario',
            'horas_servicio'    => 'nullable|numeric|min:0',
            'paramedicos_ids'   => 'nullable|array',
            'paramedicos_ids.*' => 'exists:paramedico,id_usuario',
            'insumos'           => 'nullable|array',
            'incluye'           => 'required|string',
            'respuesta'         => 'nullable|string',
            'nombre_paciente'   => 'nullable|string|max:200',
            'anticipo'          => 'nullable|numeric|min:0',
        ]);

        // Validar disponibilidad del operador
        $fecha = $cotizacion->fecha_requerida ?? now()->toDateString();

        $operadorActivo = Servicio::where('id_operador', $request->id_operador)
            ->where('estado', 'Activo')
            ->exists();
        if ($operadorActivo) {
            return back()->withErrors(['id_operador' => 'El operador seleccionado ya tiene un servicio activo en curso.'])->withInput();
        }

        $operadorOcupado = Servicio::where('id_operador', $request->id_operador)
            ->whereDate('fecha_hora', $fecha)
            ->whereNotIn('estado', ['Cancelado'])
            ->exists();
        if ($operadorOcupado) {
            return back()->withErrors(['id_operador' => 'El operador ya está asignado a otro servicio en esa fecha.'])->withInput();
        }

        $km       = (float) $request->km_distancia;
        $tarifaKm = (float) $request->costo_km_unitario;
        $costoKm  = round($km * $tarifaKm, 2);
        $horas    = (float) ($request->horas_servicio ?? 1);

        // costo_base × horas
        $costoAmbulancia = 0;
        if ($request->id_ambulancia) {
            $amb = Ambulancia::with('tipo')->find($request->id_ambulancia);
            if ($amb) {
                $costoAmbulancia = round((float)($amb->tipo->costo_base ?? 0) * $horas, 2);
            }
        }

        // (paramédicos + operador) × horas usando tarifas globales
        $empresaRec       = \App\Models\Empresa::first();
        $tarifaOp         = (float) ($empresaRec->tarifa_operador  ?? 100);
        $tarifaPm         = (float) ($empresaRec->tarifa_paramedico ?? 80);
        $numPm            = $request->paramedicos_ids ? count($request->paramedicos_ids) : 0;
        $costoParamedicos = round(($numPm * $tarifaPm + ($request->id_operador ? $tarifaOp : 0)) * $horas, 2);

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
            'estado'                  => 'Aceptada',
            'km_distancia'            => $km,
            'costo_km_unitario'       => $tarifaKm,
            'id_ambulancia'           => $request->id_ambulancia,
            'id_operador'             => $request->id_operador,
            'horas_servicio'          => $request->horas_servicio,
            'paramedicos_ids'         => $request->paramedicos_ids ?? [],
            'insumos_seleccionados'   => $insumosGuardados,
            'costo_ambulancia'        => $costoAmbulancia,
            'costo_paramedicos'       => $costoParamedicos,
            'costo_insumos'           => $costoInsumos,
            'costo'                   => $costoTotal,
            'anticipo'                => $request->anticipo ?: null,
            'incluye'                 => $request->incluye,
            'respuesta'               => $request->respuesta,
            'nombre_paciente'         => $request->nombre_paciente,
            'confirmacion_expires_at' => Carbon::now()->addHours(24),
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
        $cotizaciones = Cotizacion::where('user_id', auth()->id())->latest()->paginate(8);
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

        if ($cotizacion->confirmacion_expires_at && $cotizacion->confirmacion_expires_at->isPast()) {
            $cotizacion->update([
                'estado'    => 'Cancelada',
                'respuesta' => 'El tiempo límite para confirmar el servicio ha expirado.',
            ]);
            return redirect()->route('cotizaciones.mi-estado', $cotizacion)
                ->with('error', 'El tiempo para confirmar tu servicio ha expirado. Contáctanos para reagendar.');
        }

        if ($cotizacion->anticipo > 0 && $cotizacion->mp_pago_estado !== 'approved') {
            return back()->withErrors(['anticipo' => 'Debes completar el pago del anticipo antes de confirmar el servicio.']);
        }

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
        } elseif ($cotizacion->tipo_servicio === 'Evento') {
            $rules = array_merge($rules, [
                'evento_nombre'          => 'required|string|max:200',
                'evento_tipo'            => 'required|string|max:100',
                'evento_personas'        => 'required|integer|min:1',
                'evento_responsable'     => 'required|string|max:200',
                'evento_tel_responsable' => 'nullable|string|max:20',
                'evento_indicaciones'    => 'nullable|string|max:1000',
            ]);
        }

        $validated = $request->validate($rules);

        $datosPaciente = null;
        $datosEvento   = null;

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
        } elseif ($cotizacion->tipo_servicio === 'Evento') {
            $datosEvento = [
                'nombre'          => $validated['evento_nombre'],
                'tipo'            => $validated['evento_tipo'],
                'personas'        => $validated['evento_personas'],
                'responsable'     => $validated['evento_responsable'],
                'tel_responsable' => $validated['evento_tel_responsable'] ?? null,
                'indicaciones'    => $validated['evento_indicaciones'] ?? null,
            ];
        }

        $cotizacion->update([
            'decision_cliente'   => 'confirmada',
            'comentario_cliente' => $validated['comentario_cliente'] ?? null,
            'datos_paciente'     => $datosPaciente,
            'datos_evento'       => $datosEvento,
        ]);

        // Crear el servicio si no existe ya uno para esta cotización
        if ($cotizacion->id_ambulancia && !\App\Models\Servicio::where('id_cotizacion', $cotizacion->id_cotizacion)->exists()) {
            $fechaHora = $cotizacion->fecha_requerida
                ? $cotizacion->fecha_requerida . ' ' . ($cotizacion->hora_requerida ?? '00:00:00')
                : now();

            \App\Models\Servicio::create([
                'costo_total'   => $cotizacion->costo ?? 0,
                'estado'        => 'Activo',
                'fecha_hora'    => $fechaHora,
                'tipo'          => $cotizacion->tipo_servicio,
                'id_cotizacion' => $cotizacion->id_cotizacion,
                'forma_pago'    => $cotizacion->mp_pago_estado === 'approved' ? 'online' : 'efectivo',
                'id_ambulancia' => $cotizacion->id_ambulancia,
                'id_cliente'    => auth()->id(),
                'id_operador'   => $cotizacion->id_operador,
            ]);
        }

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
