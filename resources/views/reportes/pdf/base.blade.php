<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    /* ── Reset & Base ── */
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 10px;
        color: #1C2833;
        background: #fff;
    }

    /* ── Header ── */
    .header {
        border-bottom: 3px solid #C0392B;
        padding-bottom: 10px;
        margin-bottom: 18px;
        display: table;
        width: 100%;
    }
    .header-left  { display:table-cell; vertical-align:middle; width:70%; }
    .header-right { display:table-cell; vertical-align:middle; text-align:right; width:30%; }

    .empresa-nombre { font-size:18px; font-weight:bold; color:#C0392B; }
    .empresa-sub    { font-size:9px;  color:#637074; margin-top:2px; }

    .reporte-titulo { font-size:14px; font-weight:bold; color:#1C2833; }
    .reporte-fecha  { font-size:8px;  color:#637074; margin-top:3px; }

    /* ── Datos empresa ── */
    .empresa-info { font-size:8px; color:#637074; margin-top:4px; }

    /* ── Filtros aplicados ── */
    .filtros-box {
        background: #F4F6F8;
        border-left: 3px solid #C0392B;
        padding: 6px 10px;
        margin-bottom: 14px;
        font-size: 8.5px;
        color: #455A64;
    }
    .filtros-box strong { color:#1C2833; }

    /* ── Resumen ── */
    .resumen-grid { display:table; width:100%; margin-bottom:14px; }
    .resumen-cell {
        display:table-cell;
        background:#C0392B;
        color:#fff;
        text-align:center;
        padding:8px 4px;
        border-right:2px solid #fff;
        border-radius:0;
    }
    .resumen-cell:last-child { border-right:none; }
    .resumen-cell .num { font-size:16px; font-weight:bold; }
    .resumen-cell .lbl { font-size:7.5px; margin-top:2px; opacity:.9; }
    .resumen-cell.light { background:#FADBD8; color:#922B21; }
    .resumen-cell.light .num { color:#922B21; }

    /* ── Tabla principal ── */
    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5px;
        margin-top: 4px;
    }
    table.data thead tr {
        background: #1C2833;
        color: #fff;
    }
    table.data thead th {
        padding: 6px 7px;
        text-align: left;
        font-weight: bold;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    table.data tbody tr:nth-child(even) { background: #F4F6F8; }
    table.data tbody tr:nth-child(odd)  { background: #fff; }
    table.data tbody td {
        padding: 5px 7px;
        border-bottom: 1px solid #E8ECF0;
        vertical-align: top;
    }
    table.data tfoot tr { background: #FADBD8; font-weight: bold; }
    table.data tfoot td { padding: 6px 7px; color:#922B21; font-size:9px; }

    /* ── Badges estado ── */
    .badge {
        display:inline-block;
        padding:2px 6px;
        border-radius:10px;
        font-size:7.5px;
        font-weight:bold;
    }
    .badge-activo     { background:#D5F5E3; color:#1E8449; }
    .badge-pendiente  { background:#FEF9E7; color:#B7950B; }
    .badge-cancelado  { background:#FADBD8; color:#922B21; }
    .badge-aceptada   { background:#D6EAF8; color:#1A5276; }
    .badge-aprobado   { background:#D5F5E3; color:#1E8449; }
    .badge-disponible { background:#D5F5E3; color:#1E8449; }
    .badge-operador   { background:#D6EAF8; color:#1A5276; }
    .badge-paramedico { background:#E8DAEF; color:#6C3483; }

    /* ── Footer ── */
    .footer {
        margin-top: 20px;
        border-top: 1px solid #E8ECF0;
        padding-top: 6px;
        font-size: 7.5px;
        color: #B0BEC5;
        display: table;
        width: 100%;
    }
    .footer-left  { display:table-cell; }
    .footer-right { display:table-cell; text-align:right; }

    /* ── Utilidades ── */
    .text-right  { text-align:right; }
    .text-center { text-align:center; }
    .money       { font-family: "Courier New", monospace; }
    .page-break  { page-break-after: always; }
    .section-title {
        font-size:11px; font-weight:bold; color:#C0392B;
        border-bottom:1px solid #FADBD8; padding-bottom:4px; margin:14px 0 8px;
    }
</style>
</head>
<body>

{{-- ── Header ── --}}
<div class="header">
    <div class="header-left">
        <div class="empresa-nombre">{{ $empresa->nombre ?? 'Vitae Ambulancias' }}</div>
        <div class="empresa-sub">{{ $empresa->slogan ?? '' }}</div>
        <div class="empresa-info">
            {{ $empresa->direccion ?? '' }}
            @if($empresa->telefono ?? null) &nbsp;|&nbsp; Tel: {{ $empresa->telefono }} @endif
            @if($empresa->correo ?? null)   &nbsp;|&nbsp; {{ $empresa->correo }} @endif
        </div>
    </div>
    <div class="header-right">
        <div class="reporte-titulo">@yield('titulo_reporte')</div>
        <div class="reporte-fecha">Generado: {{ $generado_en }}</div>
    </div>
</div>

{{-- ── Filtros aplicados ── --}}
@php
    $filtrosActivos = array_filter($filtros ?? [], fn($v) => $v !== '' && $v !== null);
@endphp
@if(count($filtrosActivos))
<div class="filtros-box">
    <strong>Filtros aplicados:</strong>
    @foreach($filtrosActivos as $k => $v)
        &nbsp; {{ ucfirst(str_replace('_', ' ', $k)) }}: <strong>{{ $v }}</strong>
        @if(!$loop->last) &nbsp;|&nbsp; @endif
    @endforeach
</div>
@endif

{{-- ── Contenido del reporte ── --}}
@yield('contenido')

{{-- ── Footer ── --}}
<div class="footer">
    <div class="footer-left">{{ $empresa->nombre ?? 'Vitae Ambulancias' }} — Reporte confidencial</div>
    <div class="footer-right">{{ $generado_en }}</div>
</div>

</body>
</html>