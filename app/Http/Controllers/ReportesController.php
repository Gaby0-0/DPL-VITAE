<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportesController extends Controller
{
    // ─────────────────────────────────────────────
    //  Página principal de reportes
    // ─────────────────────────────────────────────
    public function index()
    {
        return view('reportes.index');
    }

    // ─────────────────────────────────────────────
    //  Vista previa AJAX (devuelve HTML de tabla)
    // ─────────────────────────────────────────────
    public function preview(Request $request)
    {
        $tipo  = $request->input('tipo');
        
        if (!$tipo) {
            return response()->json(['html' => '<div class="preview-empty">Tipo de reporte no especificado.</div>']);
        }

        $datos = $this->obtenerDatos($request);

        $html = view("reportes.partials.tabla_{$tipo}", [
            'datos'   => $datos['registros'],
            'resumen' => $datos['resumen'] ?? [],
        ])->render();

        return response()->json(['html' => $html]);
    }

    // ─────────────────────────────────────────────
    //  Descarga PDF
    // ─────────────────────────────────────────────
    public function descargarPDF(Request $request)
    {
        $tipo  = $request->input('tipo');
        $datos = $this->obtenerDatos($request);

        $empresa = DB::table('empresa')->first();

        $pdf = Pdf::loadView("reportes.pdf.reporte_{$tipo}", [
            'datos'        => $datos['registros'],
            'resumen'      => $datos['resumen'] ?? [],
            'empresa'      => $empresa,
            'filtros'      => $request->except(['tipo', '_token']),
            'generado_en'  => Carbon::now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'landscape');

        $nombre = 'reporte_' . $tipo . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($nombre);
    }

    // ─────────────────────────────────────────────
    //  Lógica centralizada de datos
    // ─────────────────────────────────────────────
    private function obtenerDatos(Request $request): array
    {
        $tipo = $request->input('tipo');

        return match ($tipo) {
            'servicios'    => $this->datosServicios($request),
            'cotizaciones' => $this->datosCotizaciones($request),
            'personal'     => $this->datosPersonal($request),
            'ambulancias'  => $this->datosAmbulancias($request),
            'pacientes'    => $this->datosPacientes($request),
            'ingresos'     => $this->datosIngresos($request),
            default        => ['registros' => collect(), 'resumen' => []],
        };
    }

    // ── Servicios ────────────────────────────────
    private function datosServicios(Request $request): array
    {
        $query = DB::table('servicio as s')
            ->join('ambulancia as a',        's.id_ambulancia', '=', 'a.id_ambulancia')
            ->join('tipo_ambulancia as ta',  'a.id_tipo_ambulancia', '=', 'ta.id_tipo_ambulancia')
            ->join('users as uc',            's.id_cliente', '=', 'uc.id_usuario')
            ->leftJoin('users as uo',        's.id_operador', '=', 'uo.id_usuario')
            ->whereNull('s.deleted_at')
            ->select(
                's.id_servicio',
                's.fecha_hora',
                's.estado',
                's.tipo',
                's.costo_total',
                's.observaciones',
                'a.placa',
                'ta.nombre_tipo as tipo_ambulancia',
                DB::raw("CONCAT(uc.nombre,' ',uc.ap_paterno) as cliente"),
                DB::raw("CONCAT(uo.nombre,' ',uo.ap_paterno) as operador"),
            );

        if ($fi = $request->fecha_inicio) $query->whereDate('s.fecha_hora', '>=', $fi);
        if ($ff = $request->fecha_fin)    $query->whereDate('s.fecha_hora', '<=', $ff);
        if ($e  = $request->estado)       $query->where('s.estado', $e);
        if ($t  = $request->tipo)         $query->where('s.tipo', $t);

        $registros = $query->orderByDesc('s.fecha_hora')->get();

        return [
            'registros' => $registros,
            'resumen'   => [
                'total'    => $registros->count(),
                'ingresos' => $registros->sum('costo_total'),
            ],
        ];
    }

    // ── Cotizaciones ─────────────────────────────
    private function datosCotizaciones(Request $request): array
    {
        $query = DB::table('cotizaciones as c')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id_usuario')
            ->select(
                'c.id_cotizacion',
                'c.numero_guia',
                'c.nombre',
                'c.telefono',
                'c.tipo_servicio',
                'c.estado',
                'c.costo',
                'c.fecha_requerida',
                'c.created_at',
                DB::raw("CONCAT(u.nombre,' ',u.ap_paterno) as usuario_reg"),
            );

        if ($fi = $request->fecha_inicio) $query->whereDate('c.created_at', '>=', $fi);
        if ($ff = $request->fecha_fin)    $query->whereDate('c.created_at', '<=', $ff);
        if ($e  = $request->estado)       $query->where('c.estado', $e);
        if ($t  = $request->tipo_servicio) $query->where('c.tipo_servicio', $t);

        $registros = $query->orderByDesc('c.created_at')->get();

        return [
            'registros' => $registros,
            'resumen'   => [
                'total'     => $registros->count(),
                'aprobadas' => $registros->whereIn('estado', ['Aprobado', 'Aceptada'])->count(),
                'pendientes'=> $registros->where('estado', 'Pendiente')->count(),
                'monto'     => $registros->sum('costo'),
            ],
        ];
    }

    // ── Personal ──────────────────────────────────
    private function datosPersonal(Request $request): array
    {
        $rol = $request->rol ?? '';

        $operadores  = collect();
        $paramedicos = collect();

        if ($rol === '' || $rol === 'Operadores') {
            $operadores = DB::table('operador as o')
                ->join('users as u', 'o.id_usuario', '=', 'u.id_usuario')
                ->whereNull('o.deleted_at')
                ->whereNull('u.deleted_at')
                ->select('u.id_usuario', 'u.nombre', 'u.ap_paterno', 'u.ap_materno',
                         'u.email', 'o.salario_hora',
                         DB::raw("'Operador' as rol"))
                ->get();
        }

        if ($rol === '' || $rol === 'Paramédicos') {
            $paramedicos = DB::table('paramedico as p')
                ->join('users as u', 'p.id_usuario', '=', 'u.id_usuario')
                ->whereNull('p.deleted_at')
                ->whereNull('u.deleted_at')
                ->select('u.id_usuario', 'u.nombre', 'u.ap_paterno', 'u.ap_materno',
                         'u.email', 'p.salario_hora',
                         DB::raw("'Paramédico' as rol"))
                ->get();
        }

        $registros = $operadores->merge($paramedicos)->sortBy('ap_paterno')->values();

        return [
            'registros' => $registros,
            'resumen'   => [
                'operadores'  => $operadores->count(),
                'paramedicos' => $paramedicos->count(),
            ],
        ];
    }

    // ── Ambulancias ───────────────────────────────
    private function datosAmbulancias(Request $request): array
    {
        $query = DB::table('ambulancia as a')
            ->join('tipo_ambulancia as ta', 'a.id_tipo_ambulancia', '=', 'ta.id_tipo_ambulancia')
            ->whereNull('a.deleted_at')
            ->select('a.id_ambulancia', 'a.placa', 'a.estado', 'a.costo',
                     'ta.nombre_tipo', 'ta.costo_base', 'ta.descripcion');

        if ($t = $request->tipo_amb) $query->where('ta.nombre_tipo', $t);
        if ($e = $request->estado)   $query->where('a.estado', $e);

        $registros = $query->orderBy('a.placa')->get();

        return [
            'registros' => $registros,
            'resumen'   => [
                'total'     => $registros->count(),
                'disponible'=> $registros->where('estado', 'Disponible')->count(),
            ],
        ];
    }

    // ── Pacientes ─────────────────────────────────
    private function datosPacientes(Request $request): array
    {
        $query = DB::table('paciente as p')
            ->join('servicio as s', 'p.id_servicio', '=', 's.id_servicio')
            ->leftJoin('paciente_padecimiento as pp', 'p.id_paciente', '=', 'pp.id_paciente')
            ->leftJoin('padecimiento as pad',         'pp.id_padecimiento', '=', 'pad.id_padecimiento')
            ->whereNull('p.deleted_at')
            ->select(
                'p.id_paciente', 'p.nombre', 'p.ap_paterno', 'p.ap_materno',
                'p.sexo', 'p.fecha_nacimiento', 'p.peso', 'p.oxigeno',
                's.id_servicio', 's.fecha_hora', 's.tipo as tipo_servicio',
                DB::raw("GROUP_CONCAT(pad.nombre_padecimiento SEPARATOR ', ') as padecimientos")
            )
            ->groupBy(
                'p.id_paciente','p.nombre','p.ap_paterno','p.ap_materno',
                'p.sexo','p.fecha_nacimiento','p.peso','p.oxigeno',
                's.id_servicio','s.fecha_hora','s.tipo'
            );

        if ($fi = $request->fecha_inicio) $query->whereDate('s.fecha_hora', '>=', $fi);
        if ($ff = $request->fecha_fin)    $query->whereDate('s.fecha_hora', '<=', $ff);

        $registros = $query->orderByDesc('s.fecha_hora')->get();

        return [
            'registros' => $registros,
            'resumen'   => ['total' => $registros->count()],
        ];
    }

    // ── Ingresos ──────────────────────────────────
    private function datosIngresos(Request $request): array
    {
        $agrupar = $request->input('agrupar') ?? 'Mes';

        $baseServicios = DB::table('servicio')
            ->whereNull('deleted_at')
            ->select('costo_total as monto', 'fecha_hora as fecha', 'tipo', 'id_ambulancia');

        $baseCotizaciones = DB::table('cotizaciones')
            ->whereIn('estado', ['Aprobado', 'Aceptada'])
            ->whereNotNull('costo')
            ->select('costo as monto', 'created_at as fecha', 'tipo_servicio as tipo',
                     'id_ambulancia');

        // Aplicar rango de fechas
        if ($fi = $request->fecha_inicio) {
            $baseServicios->whereDate('fecha_hora', '>=', $fi);
            $baseCotizaciones->whereDate('created_at', '>=', $fi);
        }
        if ($ff = $request->fecha_fin) {
            $baseServicios->whereDate('fecha_hora', '<=', $ff);
            $baseCotizaciones->whereDate('created_at', '<=', $ff);
        }

        $serviciosData    = $baseServicios->get();
        $cotizacionesData = $baseCotizaciones->get();
        $todos            = $serviciosData->merge($cotizacionesData);

        $registros = match ($agrupar) {
            'Mes' => $todos->groupBy(fn($r) => Carbon::parse($r->fecha)->format('Y-m'))
                           ->map(fn($g, $k) => (object)[
                               'agrupacion' => Carbon::createFromFormat('Y-m', $k)->isoFormat('MMMM YYYY'),
                               'cantidad'   => $g->count(),
                               'total'      => $g->sum('monto'),
                           ])->sortKeys()->values(),

            'Tipo de servicio' => $todos->groupBy('tipo')
                           ->map(fn($g, $k) => (object)[
                               'agrupacion' => $k ?: 'Sin tipo',
                               'cantidad'   => $g->count(),
                               'total'      => $g->sum('monto'),
                           ])->values(),

            default => $todos->groupBy('id_ambulancia')
                           ->map(fn($g, $k) => (object)[
                               'agrupacion' => 'Ambulancia #' . $k,
                               'cantidad'   => $g->count(),
                               'total'      => $g->sum('monto'),
                           ])->values(),
        };

        return [
            'registros' => $registros,
            'resumen'   => [
                'total_ingresos' => $todos->sum('monto'),
                'num_servicios'  => $serviciosData->count(),
                'num_cotizaciones' => $cotizacionesData->count(),
            ],
        ];
    }
}