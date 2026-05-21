{{-- ══════════════════════════════════════════════════
     reporte_cotizaciones.blade.php
══════════════════════════════════════════════════ --}}
@extends('reportes.pdf.base')
@section('titulo_reporte', 'Reporte de Cotizaciones')
@section('contenido')

<div class="resumen-grid">
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Total cotizaciones</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->whereIn('estado',['Aprobado','Aceptada'])->count() }}</div>
        <div class="lbl">Aprobadas / Aceptadas</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('estado','Pendiente')->count() }}</div>
        <div class="lbl">Pendientes</div>
    </div>
    <div class="resumen-cell light">
        <div class="num money">${{ number_format($datos->sum('costo'), 2) }}</div>
        <div class="lbl">Monto total</div>
    </div>
</div>

<table class="data">
    <thead>
        <tr>
            <th>Guía</th>
            <th>Fecha solicitud</th>
            <th>Solicitante</th>
            <th>Teléfono</th>
            <th>Tipo servicio</th>
            <th>Fecha requerida</th>
            <th>Estado</th>
            <th class="text-right">Costo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $c)
        <tr>
            <td><strong>{{ $c->numero_guia }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y') }}</td>
            <td>{{ $c->nombre }}</td>
            <td>{{ $c->telefono }}</td>
            <td>{{ $c->tipo_servicio }}</td>
            <td>{{ $c->fecha_requerida ? \Carbon\Carbon::parse($c->fecha_requerida)->format('d/m/Y') : '—' }}</td>
            <td><span class="badge badge-{{ strtolower(str_replace(' ','',$c->estado)) }}">{{ $c->estado }}</span></td>
            <td class="text-right money">${{ $c->costo ? number_format($c->costo, 2) : '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center" style="padding:12px;color:#B0BEC5;">Sin registros.</td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr>
            <td colspan="7" class="text-right">Total cotizaciones: {{ $datos->count() }} &nbsp;|&nbsp; Monto total:</td>
            <td class="text-right money">${{ number_format($datos->sum('costo'), 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>
@endsection