<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Ambulancia;
use App\Models\Paramedico;
use App\Models\Insumo;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\Servicio;
use App\Models\TipoAmbulancia;
use App\Models\Padecimiento;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $empresa = Empresa::first();

        $tiposAmbulancia = TipoAmbulancia::orderByDesc('costo_base')->get();

        $tiposDisponibles = TipoAmbulancia::whereHas('ambulancias', function ($q) {
            $q->where('estado', 'Disponible');
        })->orderByDesc('costo_base')->get();

        $padecimientos = Padecimiento::orderBy('nombre_padecimiento')->get();

        return view('cotizaciones.create', compact(
            'empresa',
            'tiposAmbulancia',
            'tiposDisponibles',
            'padecimientos',
            'user'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'                    => 'required|string|max:150',
            'telefono'                  => 'required|string|max:20',
            'correo'                    => 'nullable|email|max:150',
            'tipo_servicio'             => 'required|string|max:100',
            'descripcion'               => 'nullable|string',
            'fecha_requerida'           => 'nullable|date|after_or_equal:today',
            'origen'                    => 'nullable|string|max:500',
            'lat_origen'                => 'nullable|numeric|between:-90,90',
            'lng_origen'                => 'nullable|numeric|between:-180,180',
            'destino'                   => 'nullable|string|max:500',
            'lat_destino'               => 'nullable|numeric|between:-90,90',
            'lng_destino'               => 'nullable|numeric|between:-180,180',
            'personas'                  => 'nullable|integer|min:1',
            'tipo_ambulancia_preferida' => 'required|string|max:150',

            //datos del paciente
            'nombre_paciente'           => 'nullable|string|max:150',
            'nacimiento'                => 'nullable|date',
            'curp'                      => 'nullable|string|max:18',
            'tipo_sangre'               => 'nullable|string|max:10',
            'diagnostico'               => 'nullable|string|max:1000',
            'alergias'                  => 'nullable|string|max:1000',
            'medico'                    => 'nullable|string|max:150',
            'observaciones_medicas'     => 'nullable|string|max:1000',

            //catalogo de padecimientos
            'padecimientos'             => 'nullable|array',
            'padecimientos.*'           => 'exists:padecimiento,id_padecimiento',
        ]);


        $empresa = Empresa::first();

        $tipoAmbulancia = TipoAmbulancia::where('nombre_tipo', $validated['tipo_ambulancia_preferida'])->first();

        $padecimientosSeleccionados = collect();
        if (!empty($validated['padecimientos'])) {
            $padecimientosSeleccionados = Padecimiento::whereIn(
                'id_padecimiento',
                $validated['padecimientos']
            )->get();
        }


        $costoExtraPadecimientos = (float) $padecimientosSeleccionados->sum('costo_extra');

        $kmDistancia = null;
        if (
            !empty($validated['lat_origen']) &&
            !empty($validated['lng_origen']) &&
            !empty($validated['lat_destino']) &&
            !empty($validated['lng_destino'])
        ) {
            $kmDistancia = Cotizacion::haversineKm(
                $validated['lat_origen'],
                $validated['lng_origen'],
                $validated['lat_destino'],
                $validated['lng_destino']
            );
        }

        $costoKmUnitario = (float) ($empresa->costo_km ?? 0);
        $costoKm = $kmDistancia ? ($kmDistancia * $costoKmUnitario) : 0;
        $costoAmbulancia = (float) ($tipoAmbulancia->costo_base ?? 0);
        $costoParamedicos = 0;
        $costoInsumos = 0;

        $costoTotal = $costoKm + $costoAmbulancia + $costoParamedicos + $costoInsumos + $costoExtraPadecimientos;

        $cotizacion = Cotizacion::create([
            'user_id'                   => auth()->id(),
            'numero_guia'               => Cotizacion::generarGuia(),
            'nombre'                    => $validated['nombre'],
            'telefono'                  => $validated['telefono'],
            'correo'                    => $validated['correo'] ?? null,
            'tipo_servicio'             => $validated['tipo_servicio'],
            'tipo_ambulancia_preferida' => $validated['tipo_ambulancia_preferida'],
            'descripcion'               => $validated['descripcion'] ?? null,
            'fecha_requerida'           => $validated['fecha_requerida'] ?? null,
            'origen'                    => $validated['origen'] ?? null,
            'lat_origen'                => $validated['lat_origen'] ?? null,
            'lng_origen'                => $validated['lng_origen'] ?? null,
            'destino'                   => $validated['destino'] ?? null,
            'lat_destino'               => $validated['lat_destino'] ?? null,
            'lng_destino'               => $validated['lng_destino'] ?? null,
            'personas'                  => $validated['personas'] ?? null,
            'padecimientos_paciente'    => $padecimientosSeleccionados->pluck('nombre_padecimiento')->implode(', '),
            'nombre_paciente'           => $validated['nombre_paciente'] ?? null,
            'estado'                    => 'Pendiente',
            'km_distancia'              => $kmDistancia,
            'costo_km_unitario'         => $costoKmUnitario,
            'costo_ambulancia'          => $costoAmbulancia,
            'costo_paramedicos'         => $costoParamedicos,
            'costo_insumos'             => $costoInsumos,
            'costo'                     => $costoTotal,
            'anticipo'                  => 0,

            'datos_paciente' => [
                'nombre' => $validated['nombre_paciente'] ?? null,
                'nacimiento' => $validated['nacimiento'] ?? null,
                'curp' => $validated['curp'] ?? null,
                'tipo_sangre' => $validated['tipo_sangre'] ?? null,
                'diagnostico' => $validated['diagnostico'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'medico' => $validated['medico'] ?? null,
                'observaciones_medicas' => $validated['observaciones_medicas'] ?? null,
                'padecimientos_ids' => $padecimientosSeleccionados->pluck('id_padecimiento')->toArray(),
                'padecimientos' => $padecimientosSeleccionados->pluck('nombre_padecimiento')->toArray(),
                'costo_extra_padecimientos' => $costoExtraPadecimientos,
            ],
        ]);

        return redirect()->route('cotizaciones.gracias')
            ->with('numero_guia', $cotizacion->numero_guia)
            ->with('cotizacion_id', $cotizacion->id_cotizacion);
    }


    public function gracias()
    {
        $empresa = Empresa::first();
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
        $cotizaciones = Cotizacion::latest()->paginate(8);
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
        $ambulancias = Ambulancia::with('tipo')
            ->where('estado', 'Disponible')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        // Operadores disponibles: sin servicio activo y sin servicio ese día
        $operadores = Operador::with('usuario')
            ->whereDoesntHave('servicios', function ($q) {
                $q->where('estado', 'Activo');
            })
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha)
                  ->whereNotIn('estado', ['Cancelado']);
            })
            ->get();

        // Sugerencia aleatoria (respeta selección previa si ya fue aceptada)
        $operadorSugerido = $cotizacion->id_operador
            ?? ($operadores->isNotEmpty() ? $operadores->random()->id_usuario : null);

        // Paramédicos disponibles ese día
        $paramedicos = Paramedico::with('usuario')
            ->whereDoesntHave('servicios', function ($q) use ($fecha) {
                $q->whereDate('fecha_hora', $fecha);
            })
            ->get();

        $insumos = Insumo::orderBy('nombre_insumo')->get();

        return view('cotizaciones.show', compact(
            'cotizacion', 'empresa', 'kmCalculado',
            'ambulancias', 'operadores', 'operadorSugerido',
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

        // costo_base del tipo + salario_hora del operador * horas
        $costoAmbulancia = 0;
        if ($request->id_ambulancia) {
            $amb = Ambulancia::with('tipo')->find($request->id_ambulancia);
            if ($amb) {
                $costoTipo = (float) ($amb->tipo->costo_base ?? 0);
                $costoAmbulancia = round($costoTipo, 2);
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
            'id_operador'          => $request->id_operador,
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
