<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicio {{ $cotizacion->numero_guia }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Public Sans', sans-serif; font-size: 13px; color: #2d2d2d; background: #fff; }

        .page { max-width: 780px; margin: 0 auto; padding: 32px 40px; }

        /* Encabezado */
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #696cff; padding-bottom: 16px; margin-bottom: 20px; }
        .header-logo { font-size: 1.4rem; font-weight: 700; color: #696cff; }
        .header-info { text-align: right; color: #666; font-size: 12px; }
        .header-info strong { display: block; font-size: 14px; color: #2d2d2d; }

        /* Badge estado */
        .badge-estado { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-Aceptada  { background: #d4edda; color: #155724; }
        .badge-Pendiente { background: #fff3cd; color: #856404; }
        .badge-Cancelada { background: #f8d7da; color: #721c24; }

        /* Secciones */
        .section { margin-bottom: 20px; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #696cff; border-bottom: 1px solid #e0e0ff; padding-bottom: 4px; margin-bottom: 10px; }

        /* Grid de datos */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; }
        .field label { font-size: 11px; color: #888; display: block; }
        .field span  { font-weight: 500; }

        /* Tabla de costos */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        table th { background: #f5f5ff; padding: 6px 10px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; }
        table td { padding: 7px 10px; border-bottom: 1px solid #f0f0f0; }
        table tr.total td { background: #f0f0ff; font-weight: 700; font-size: 15px; border-top: 2px solid #696cff; }
        table td.right { text-align: right; }

        /* Anticipo */
        .anticipo-box { background: #fff8e1; border-left: 4px solid #ffc107; padding: 10px 14px; border-radius: 4px; margin-top: 8px; }
        .anticipo-box.pagado { background: #e8f5e9; border-color: #4caf50; }

        /* Datos paciente */
        .confidencial { background: #fff5f5; border-left: 4px solid #dc3545; padding: 10px 14px; border-radius: 4px; }

        /* Incluye */
        .incluye { background: #f8f8ff; border-radius: 6px; padding: 10px 14px; font-size: 12px; line-height: 1.7; white-space: pre-line; }

        /* Firma */
        .firma { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px; }
        .firma-box { border-top: 1px solid #ccc; padding-top: 8px; text-align: center; font-size: 11px; color: #888; }

        /* Footer */
        .footer { margin-top: 32px; border-top: 1px solid #e0e0e0; padding-top: 10px; text-align: center; font-size: 11px; color: #aaa; }

        /* Botón imprimir (se oculta al imprimir) */
        .btn-print { position: fixed; top: 16px; right: 16px; background: #696cff; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; font-family: inherit; box-shadow: 0 4px 12px rgba(105,108,255,.3); }
        .btn-print:hover { background: #5f63f2; }

        @media print {
            .btn-print { display: none !important; }
            body { font-size: 12px; }
            .page { padding: 16px 20px; }
        }
    </style>
</head>
<body>

<button class="btn-print" onclick="window.print()">⬇ Descargar PDF</button>

<div class="page">

    {{-- encabezado --}}
    <div class="header">
        <div>
            @if($empresa && $empresa->logo_nombre)
                <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="{{ $empresa->nombre }}" style="height:45px;object-fit:contain;">
            @else
                <div class="header-logo">{{ $empresa->nombre ?? config('app.name') }}</div>
            @endif
            @if($empresa && $empresa->telefono)
                <div style="font-size:12px;color:#666;margin-top:4px;">Tel: {{ $empresa->telefono }}</div>
            @endif
        </div>
        <div class="header-info">
            <strong>Comprobante de Servicio</strong>
            N° {{ $cotizacion->numero_guia }}<br>
            Fecha: {{ $cotizacion->created_at->format('d/m/Y H:i') }}<br>
            <span class="badge-estado badge-{{ $cotizacion->estado }}">{{ $cotizacion->estado }}</span>
        </div>
    </div>

    {{-- datos del solicitante --}}
    <div class="section">
        <div class="section-title">Datos del solicitante</div>
        <div class="grid">
            <div class="field"><label>Nombre</label><span>{{ $cotizacion->nombre }}</span></div>
            <div class="field"><label>Teléfono</label><span>{{ $cotizacion->telefono }}</span></div>
            @if($cotizacion->correo)
            <div class="field"><label>Correo</label><span>{{ $cotizacion->correo }}</span></div>
            @endif
            <div class="field"><label>Tipo de servicio</label><span>{{ $cotizacion->tipo_servicio }}</span></div>
            @if($cotizacion->fecha_requerida)
            <div class="field"><label>Fecha requerida</label><span>{{ \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') }}</span></div>
            @endif
            @if($cotizacion->personas)
            <div class="field"><label>Personas</label><span>{{ $cotizacion->personas }}</span></div>
            @endif
        </div>
    </div>

    {{-- origen y destino --}}
    @if($cotizacion->origen || $cotizacion->destino)
    <div class="section">
        <div class="section-title">Ruta</div>
        <div class="grid">
            @if($cotizacion->origen)
            <div class="field"><label>Origen</label><span>{{ $cotizacion->origen }}</span></div>
            @endif
            @if($cotizacion->destino)
            <div class="field"><label>Destino</label><span>{{ $cotizacion->destino }}</span></div>
            @endif
            @if($cotizacion->km_distancia)
            <div class="field"><label>Distancia</label><span>{{ $cotizacion->km_distancia }} km</span></div>
            @endif
        </div>
    </div>
    @endif

    {{-- costos del servicio --}}
    @if($cotizacion->estado === 'Aceptada' && $cotizacion->costo)
    <div class="section">
        <div class="section-title">Desglose del servicio</div>
        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Detalle</th>
                    <th class="right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @if($cotizacion->km_distancia)
                <tr>
                    <td>Kilómetros</td>
                    <td>{{ $cotizacion->km_distancia }} km × ${{ number_format($cotizacion->costo_km_unitario, 2) }}</td>
                    <td class="right">${{ number_format($cotizacion->km_distancia * $cotizacion->costo_km_unitario, 2) }}</td>
                </tr>
                @endif
                @if($cotizacion->costo_ambulancia)
                <tr>
                    <td>Ambulancia y operador</td>
                    <td>{{ $cotizacion->ambulancia?->tipo?->nombre_tipo ?? '—' }} · {{ $cotizacion->horas_servicio }}h</td>
                    <td class="right">${{ number_format($cotizacion->costo_ambulancia, 2) }}</td>
                </tr>
                @endif
                @if($cotizacion->costo_paramedicos)
                <tr>
                    <td>Paramédicos</td>
                    <td>{{ count($cotizacion->paramedicos_ids ?? []) }} persona(s) × {{ $cotizacion->horas_servicio }}h</td>
                    <td class="right">${{ number_format($cotizacion->costo_paramedicos, 2) }}</td>
                </tr>
                @endif
                @if($cotizacion->costo_insumos)
                <tr>
                    <td>Insumos especiales</td>
                    <td>{{ count($cotizacion->insumos_seleccionados ?? []) }} artículo(s)</td>
                    <td class="right">${{ number_format($cotizacion->costo_insumos, 2) }}</td>
                </tr>
                @endif
                <tr class="total">
                    <td colspan="2">TOTAL</td>
                    <td class="right">${{ number_format($cotizacion->costo, 2) }} MXN</td>
                </tr>
            </tbody>
        </table>

        @if($cotizacion->anticipo)
        <div class="anticipo-box {{ $cotizacion->mp_pago_estado === 'approved' ? 'pagado' : '' }} mt-2">
            <strong>Anticipo:</strong> ${{ number_format($cotizacion->anticipo, 2) }} MXN
            @if($cotizacion->mp_pago_estado === 'approved')
                — <strong style="color:#155724">✓ Pagado</strong>
                @if($cotizacion->mp_payment_id) (ID: {{ $cotizacion->mp_payment_id }}) @endif
            @else
                — Pendiente de pago
            @endif
        </div>
        @endif
    </div>

    @if($cotizacion->incluye)
    <div class="section">
        <div class="section-title">El servicio incluye</div>
        <div class="incluye">{{ $cotizacion->incluye }}</div>
    </div>
    @endif
    @endif

    {{-- datos del paciente --}}
    @if($cotizacion->datos_paciente)
    <div class="section">
        <div class="section-title">Datos del paciente</div>
        <div class="confidencial">
            <div class="grid">
                <div class="field"><label>Nombre</label><span>{{ $cotizacion->datos_paciente['nombre'] ?? '—' }}</span></div>
                <div class="field"><label>Fecha de nacimiento</label><span>{{ $cotizacion->datos_paciente['nacimiento'] ? \Carbon\Carbon::parse($cotizacion->datos_paciente['nacimiento'])->format('d/m/Y') : '—' }}</span></div>
                @if($cotizacion->datos_paciente['curp'] ?? null)
                <div class="field"><label>CURP</label><span>{{ $cotizacion->datos_paciente['curp'] }}</span></div>
                @endif
                @if($cotizacion->datos_paciente['tipo_sangre'] ?? null)
                <div class="field"><label>Tipo de sangre</label><span>{{ $cotizacion->datos_paciente['tipo_sangre'] }}</span></div>
                @endif
                <div class="field" style="grid-column:span 2"><label>Diagnóstico</label><span>{{ $cotizacion->datos_paciente['diagnostico'] ?? '—' }}</span></div>
                @if($cotizacion->datos_paciente['alergias'] ?? null)
                <div class="field"><label>Alergias</label><span>{{ $cotizacion->datos_paciente['alergias'] }}</span></div>
                @endif
                @if($cotizacion->datos_paciente['medico'] ?? null)
                <div class="field"><label>Médico tratante</label><span>{{ $cotizacion->datos_paciente['medico'] }}</span></div>
                @endif
                @if(!empty($cotizacion->datos_paciente['padecimientos']))
                <div class="field" style="grid-column:span 2">
                    <label>Padecimientos del paciente</label>
                    <span>
                        <ul style="margin-top: 6px; margin-left: 18px;">
                            @foreach($cotizacion->datos_paciente['padecimientos'] as $p)
                                <li>{{ $p }}</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
                @endif

                @if(!empty($cotizacion->datos_paciente['costo_extra_padecimientos']))
                <div class="field" style="grid-column:span 2">
                    <label>Costo extra por padecimientos</label>
                    <span>${{ number_format($cotizacion->datos_paciente['costo_extra_padecimientos'], 2) }}</span>
                </div>
                @endif


            </div>
        </div>
    </div>
    @endif

    {{-- firmas --}}
    <div class="firma">
        <div class="firma-box">Firma del cliente<br><br><br>{{ $cotizacion->nombre }}</div>
        <div class="firma-box">Sello / Firma de la empresa<br><br><br>{{ $empresa->nombre ?? config('app.name') }}</div>
    </div>

    {{-- footer --}}
    <div class="footer">
        {{ $empresa->nombre ?? config('app.name') }} · Documento generado el {{ now()->format('d/m/Y H:i') }}
    </div>

</div>

<script>
    // Abrir diálogo de impresión automáticamente
    window.addEventListener('load', function () { window.print(); });
</script>
</body>
</html>
