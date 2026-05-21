@extends('reportes.pdf.base')

@section('titulo_reporte', 'Reporte de Servicios')

@section('contenido')

{{-- Resumen ─────────────────────────────────── --}}
<div class="resumen-grid">
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Total servicios</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('estado','Activo')->count() }}</div>
        <div class="lbl">Activos</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('tipo','Traslado')->count() }}</div>
        <div class="lbl">Traslados</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('tipo','Evento')->count() }}</div>
        <div class="lbl">Eventos</div>
    </div>
    <div class="resumen-cell light">
        <div class="num money">${{ number_format($datos->sum('costo_total'), 2) }}</div>
        <div class="lbl">Ingresos totales</div>
    </div>
</div>

{{-- Tabla ─────────────────────────────────────── --}}
<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Ambulancia</th>
            <th>Tipo Amb.</th>
            <th>Operador</th>
            <th>Cliente</th>
            <th>Observaciones</th>
            <th class="text-right">Costo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $s)
        <tr>
            <td>{{ $s->id_servicio }}</td>
            <td>{{ \Carbon\Carbon::parse($s->fecha_hora)->format('d/m/Y H:i') }}</td>
            <td>{{ $s->tipo ?? '—' }}</td>
            <td>
                <span class="badge badge-{{ strtolower($s->estado) }}">{{ $s->estado }}</span>
            </td>
            <td>{{ $s->placa }}</td>
            <td>{{ $s->tipo_ambulancia }}</td>
            <td>{{ $s->operador ?? '—' }}</td>
            <td>{{ $s->cliente }}</td>
            <td>{{ Str::limit($s->observaciones, 30) }}</td>
            <td class="text-right money">${{ number_format($s->costo_total, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center" style="padding:12px;color:#B0BEC5;">
            Sin registros para los filtros seleccionados.
        </td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr>
            <td colspan="9" class="text-right">Total:</td>
            <td class="text-right money">${{ number_format($datos->sum('costo_total'), 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

@endsection