@extends('reportes.pdf.base')
@section('titulo_reporte','Reporte de Ingresos')
@section('contenido')

<div class="resumen-grid">
    <div class="resumen-cell light">
        <div class="num money">${{ number_format($resumen['total_ingresos'] ?? 0, 2) }}</div>
        <div class="lbl">Ingresos totales</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $resumen['num_servicios'] ?? 0 }}</div>
        <div class="lbl">Servicios</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $resumen['num_cotizaciones'] ?? 0 }}</div>
        <div class="lbl">Cotizaciones</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Grupos</div>
    </div>
</div>

<table class="data">
    <thead>
        <tr>
            <th>Agrupación</th>
            <th class="text-right">Cantidad</th>
            <th class="text-right">Total</th>
            <th class="text-right">Promedio</th>
            <th class="text-right">% del total</th>
        </tr>
    </thead>
    <tbody>
        @php $totalGlobal = $datos->sum('total') ?: 1; @endphp
        @forelse($datos as $row)
        <tr>
            <td><strong>{{ $row->agrupacion }}</strong></td>
            <td class="text-right">{{ $row->cantidad }}</td>
            <td class="text-right money">${{ number_format($row->total, 2) }}</td>
            <td class="text-right money">${{ $row->cantidad ? number_format($row->total / $row->cantidad, 2) : '—' }}</td>
            <td class="text-right">{{ number_format(($row->total / $totalGlobal) * 100, 1) }}%</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center" style="padding:12px;color:#B0BEC5;">Sin registros.</td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="text-right">{{ $datos->sum('cantidad') }}</td>
            <td class="text-right money">${{ number_format($datos->sum('total'), 2) }}</td>
            <td class="text-right">—</td>
            <td class="text-right">100%</td>
        </tr>
    </tfoot>
    @endif
</table>
@endsection