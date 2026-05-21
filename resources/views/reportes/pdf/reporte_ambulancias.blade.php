{{-- ════════════════════════════════
   reporte_ambulancias.blade.php
════════════════════════════════ --}}
@extends('reportes.pdf.base')
@section('titulo_reporte','Reporte de Ambulancias')
@section('contenido')

<div class="resumen-grid">
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Total unidades</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('estado','Disponible')->count() }}</div>
        <div class="lbl">Disponibles</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('nombre_tipo','Básica')->count() }}</div>
        <div class="lbl">Tipo Básica</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('nombre_tipo','Avanzada')->count() }}</div>
        <div class="lbl">Tipo Avanzada</div>
    </div>
</div>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Descripción tipo</th>
            <th>Estado</th>
            <th class="text-right">Costo base tipo</th>
            <th class="text-right">Costo unit.</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $a)
        <tr>
            <td>{{ $a->id_ambulancia }}</td>
            <td><strong>{{ $a->placa }}</strong></td>
            <td>{{ $a->nombre_tipo }}</td>
            <td>{{ $a->descripcion ?? '—' }}</td>
            <td><span class="badge badge-{{ strtolower($a->estado) }}">{{ $a->estado }}</span></td>
            <td class="text-right money">${{ number_format($a->costo_base, 2) }}</td>
            <td class="text-right money">${{ number_format($a->costo, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center" style="padding:12px;color:#B0BEC5;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection