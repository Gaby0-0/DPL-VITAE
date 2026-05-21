{{-- ════════════════════════════════
   reporte_pacientes.blade.php
════════════════════════════════ --}}
@extends('reportes.pdf.base')
@section('titulo_reporte','Reporte de Pacientes')
@section('contenido')

<div class="resumen-grid">
    <div class="resumen-cell">
        <div class="num">{{ $datos->count() }}</div>
        <div class="lbl">Total pacientes</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('sexo','Masculino')->count() }}</div>
        <div class="lbl">Masculinos</div>
    </div>
    <div class="resumen-cell">
        <div class="num">{{ $datos->where('sexo','Femenino')->count() }}</div>
        <div class="lbl">Femeninos</div>
    </div>
    <div class="resumen-cell light">
        <div class="num">{{ number_format($datos->whereNotNull('peso')->avg('peso'),1) }} kg</div>
        <div class="lbl">Peso promedio</div>
    </div>
</div>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Paciente</th>
            <th>Sexo</th>
            <th>F. Nacimiento</th>
            <th>Peso</th>
            <th>Oxígeno</th>
            <th>Servicio</th>
            <th>Fecha servicio</th>
            <th>Padecimientos</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $p)
        <tr>
            <td>{{ $p->id_paciente }}</td>
            <td>{{ $p->nombre }} {{ $p->ap_paterno }} {{ $p->ap_materno }}</td>
            <td>{{ $p->sexo ?? '—' }}</td>
            <td>{{ $p->fecha_nacimiento ? \Carbon\Carbon::parse($p->fecha_nacimiento)->format('d/m/Y') : '—' }}</td>
            <td>{{ $p->peso ? $p->peso.' kg' : '—' }}</td>
            <td>{{ $p->oxigeno ?? '—' }}</td>
            <td>#{{ $p->id_servicio }} {{ $p->tipo_servicio }}</td>
            <td>{{ \Carbon\Carbon::parse($p->fecha_hora)->format('d/m/Y') }}</td>
            <td>{{ $p->padecimientos ?: '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center" style="padding:12px;color:#B0BEC5;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection


{{-- ════════════════════════════════
   reporte_ingresos.blade.php
════════════════════════════════ --}}
{{-- NOTE: save this block as resources/views/reportes/pdf/reporte_ingresos.blade.php --}}