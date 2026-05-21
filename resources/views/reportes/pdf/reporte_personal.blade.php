{{-- ════════════════════════════════
   reporte_personal.blade.php
════════════════════════════════ --}}
@extends('reportes.pdf.base')
@section('titulo_reporte','Reporte de Personal')
@section('contenido')

<div class="resumen-grid">
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Total personal</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('rol','Operador')->count() }}</div>
        <div class="lbl">Operadores</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('rol','Paramédico')->count() }}</div>
        <div class="lbl">Paramédicos</div>
    </div>
    <div class="resumen-cell light">
        <div class="num money">${{ number_format($datos->avg('salario_hora'), 2) }}</div>
        <div class="lbl">Salario/hora promedio</div>
    </div>
</div>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th class="text-right">Salario/hora</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $p)
        <tr>
            <td>{{ $p->id_usuario }}</td>
            <td>{{ $p->nombre }} {{ $p->ap_paterno }} {{ $p->ap_materno }}</td>
            <td>{{ $p->email }}</td>
            <td>
                <span class="badge badge-{{ $p->rol === 'Operador' ? 'operador' : 'paramedico' }}">
                    {{ $p->rol }}
                </span>
            </td>
            <td class="text-right money">${{ number_format($p->salario_hora, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center" style="padding:12px;color:#B0BEC5;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection