@section('title', 'Cotización ' . $cotizacion->numero_guia)

@section('vendor-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
.map-admin-box { height: 300px; border-radius: 8px; border: 1px solid #dee2e6; overflow: hidden; }
@media print {
    /* Ocultar toda la interfaz */
    #layout-navbar,
    #layout-menu,
    .layout-menu-toggle,
    .content-backdrop,
    .no-print { display: none !important; }

    /* Sin márgenes ni sombras en impresión */
    body, .layout-wrapper, .layout-page, .content-wrapper,
    .container-xxl { margin: 0 !important; padding: 0 !important; background: #fff !important; }

    /* Solo mostrar la columna izquierda a ancho completo */
    .row.g-4 { display: block !important; }
    .col-lg-5 { width: 100% !important; max-width: 100% !important; }
    .col-lg-7 { display: none !important; }

    /* Cabecera de impresión */
    .print-header { display: flex !important; }

    /* Quitar iframe del mapa (no imprimible) */
    iframe { display: none !important; }

    /* Quitar badge de mapa */
    .px-3.py-2.d-flex.justify-content-between { display: none !important; }

    /* Tarjetas sin sombra */
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; break-inside: avoid; }

    /* Quitar botones dentro de la card de info */
    .card-body .btn, .card-footer { display: none !important; }
}
</style>
@endsection

<x-layouts.app :title="'Cotización ' . $cotizacion->numero_guia">

@php
    $tieneOrigen  = $cotizacion->lat_origen  && $cotizacion->lng_origen;
    $tieneDestino = $cotizacion->lat_destino && $cotizacion->lng_destino;
    $tieneMapa    = $tieneOrigen || $tieneDestino;

    if ($tieneOrigen && $tieneDestino) {
        $iframeSrc = 'https://maps.google.com/maps?saddr='.$cotizacion->lat_origen.','.$cotizacion->lng_origen
                   . '&daddr='.$cotizacion->lat_destino.','.$cotizacion->lng_destino.'&output=embed';
        $mapsLink  = 'https://www.google.com/maps/dir/'.$cotizacion->lat_origen.','.$cotizacion->lng_origen
                   . '/'.$cotizacion->lat_destino.','.$cotizacion->lng_destino;
    } elseif ($tieneOrigen) {
        $iframeSrc = 'https://maps.google.com/maps?q='.$cotizacion->lat_origen.','.$cotizacion->lng_origen.'&output=embed';
        $mapsLink  = 'https://www.google.com/maps?q='.$cotizacion->lat_origen.','.$cotizacion->lng_origen;
    } elseif ($tieneDestino) {
        $iframeSrc = 'https://maps.google.com/maps?q='.$cotizacion->lat_destino.','.$cotizacion->lng_destino.'&output=embed';
        $mapsLink  = 'https://www.google.com/maps?q='.$cotizacion->lat_destino.','.$cotizacion->lng_destino;
    }

    $colorEstado = match(true) {
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'danger',
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'success',
        $cotizacion->estado === 'Pendiente'   => 'warning',
        $cotizacion->estado === 'En revisión' => 'info',
        $cotizacion->estado === 'Aceptada'    => 'success',
        $cotizacion->estado === 'Cancelada'   => 'danger',
        default => 'secondary',
    };

    $labelEstado = match(true) {
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'Declinada',
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'Confirmada',
        default => $cotizacion->estado,
    };
@endphp

{{-- encabezado para imprimir --}}
<div class="print-header mb-4 pb-3 align-items-center justify-content-between border-bottom" style="display:none;">
    <div>
        <h4 class="fw-bold mb-0">Solicitud de Cotización</h4>
        <span class="text-muted">N° {{ $cotizacion->numero_guia }} &nbsp;·&nbsp; {{ $cotizacion->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="text-end">
        <strong>{{ $empresa->nombre ?? config('app.name') }}</strong><br>
        @if($empresa && $empresa->telefono)<small>{{ $empresa->telefono }}</small>@endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4 no-print">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

{{-- columna izquierda --}}
<div class="col-lg-5">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ $cotizacion->numero_guia }}</h5>
                <small class="text-muted">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <span class="badge bg-label-{{ $colorEstado }} fs-6">{{ $labelEstado }}</span>
        </div>
        <div class="card-body pb-2">
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Solicitante</dt>
                <dd class="col-7">{{ $cotizacion->nombre }}</dd>

                <dt class="col-5 text-muted">Teléfono</dt>
                <dd class="col-7">{{ $cotizacion->telefono }}</dd>

                <dt class="col-5 text-muted">Correo</dt>
                <dd class="col-7">{{ $cotizacion->correo ?? '—' }}</dd>

                <dt class="col-5 text-muted">Tipo servicio</dt>
                <dd class="col-7"><span class="badge bg-label-primary">{{ $cotizacion->tipo_servicio }}</span></dd>

                @if($cotizacion->tipo_ambulancia_preferida)
                <dt class="col-5 text-muted">Ambulancia preferida</dt>
                <dd class="col-7"><span class="badge bg-label-warning"><i class="bx bx-star me-1"></i>{{ $cotizacion->tipo_ambulancia_preferida }}</span></dd>
                @endif

                <dt class="col-5 text-muted">Fecha requerida</dt>
                <dd class="col-7">{{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</dd>

                <dt class="col-5 text-muted">Personas</dt>
                <dd class="col-7">{{ $cotizacion->personas ?? '—' }}</dd>

                @if($cotizacion->origen)
                <dt class="col-5 text-muted">Origen</dt>
                <dd class="col-7">{{ $cotizacion->origen }}</dd>
                @endif

                @if($cotizacion->destino)
                <dt class="col-5 text-muted">Destino</dt>
                <dd class="col-7">{{ $cotizacion->destino }}</dd>
                @endif

                @if($kmCalculado || $cotizacion->km_distancia)
                <dt class="col-5 text-muted">Distancia</dt>
                <dd class="col-7 fw-bold">{{ $cotizacion->km_distancia ?? $kmCalculado }} km</dd>
                @endif

                @if($cotizacion->requiere_oxigeno)
                <dt class="col-5 text-muted"><i class="bx bx-plus-medical text-danger me-1"></i>Oxígeno</dt>
                <dd class="col-7"><span class="badge bg-label-danger">Requiere oxígeno médico</span></dd>
                @endif

                @if($cotizacion->padecimientos_paciente)
                <dt class="col-5 text-muted"><i class="bx bx-plus-medical text-warning me-1"></i>Padecimientos</dt>
                <dd class="col-7">{{ $cotizacion->padecimientos_paciente }}</dd>
                @endif

                @if($cotizacion->nombre_paciente)
                <dt class="col-5 text-muted">Paciente</dt>
                <dd class="col-7">{{ $cotizacion->nombre_paciente }}</dd>
                @endif

                <dt class="col-5 text-muted">Descripción</dt>
                <dd class="col-7">{{ $cotizacion->descripcion ?? '—' }}</dd>
            </dl>
        </div>

        {{-- desglose de costos --}}
        @if($cotizacion->estado === 'Aceptada')
        @php
            $h      = (float)($cotizacion->horas_servicio ?? 1);
            $nPm    = count($cotizacion->paramedicos_ids ?? []);
            $tAmb   = $cotizacion->ambulancia?->tipo;
            $tarifaOp = (float)($empresa->tarifa_operador  ?? 100);
            $tarifaPm = (float)($empresa->tarifa_paramedico ?? 80);
            $costoOpCalc  = round($tarifaOp * $h, 2);
            $costoPmCalc  = round($tarifaPm * $nPm * $h, 2);
            $costoAmbCalc = $cotizacion->costo_ambulancia;
            $costoKmCalc  = round(($cotizacion->km_distancia ?? 0) * ($cotizacion->costo_km_unitario ?? 0), 2);
        @endphp
        <div class="card-body border-top pt-3">
            <h6 class="fw-bold text-success mb-3"><i class="bx bx-receipt me-1"></i>Desglose de costos</h6>
            <table class="table table-sm table-borderless mb-2 small">
                <thead class="table-light">
                    <tr>
                        <th>Concepto</th>
                        <th class="text-center">Fórmula</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ambulancia --}}
                    @if($costoAmbCalc)
                    <tr>
                        <td><i class="bx bx-ambulance text-primary me-1"></i>
                            Ambulancia<br>
                            <span class="text-muted">{{ $tAmb?->nombre_tipo ?? '—' }}</span>
                        </td>
                        <td class="text-center text-muted">
                            ${{ number_format($tAmb?->costo_base ?? 0, 2) }}/hr
                            × {{ $h }}h
                        </td>
                        <td class="text-end fw-bold">${{ number_format($costoAmbCalc, 2) }}</td>
                    </tr>
                    @endif

                    {{-- Operador --}}
                    @if($cotizacion->id_operador)
                    <tr>
                        <td><i class="bx bx-user-check text-info me-1"></i>
                            Operador<br>
                            <span class="text-muted">Tarifa global: ${{ number_format($tarifaOp, 2) }}/hr</span>
                        </td>
                        <td class="text-center text-muted">
                            ${{ number_format($tarifaOp, 2) }}/hr × {{ $h }}h
                        </td>
                        <td class="text-end fw-bold">${{ number_format($costoOpCalc, 2) }}</td>
                    </tr>
                    @endif

                    {{-- Paramédicos --}}
                    @if($nPm)
                    <tr>
                        <td><i class="bx bx-user-plus text-warning me-1"></i>
                            {{ $nPm }} Paramédico{{ $nPm > 1 ? 's' : '' }}<br>
                            <span class="text-muted">Tarifa global: ${{ number_format($tarifaPm, 2) }}/hr c/u</span>
                        </td>
                        <td class="text-center text-muted">
                            ${{ number_format($tarifaPm, 2) }}/hr
                            × {{ $nPm }} × {{ $h }}h
                        </td>
                        <td class="text-end fw-bold">${{ number_format($costoPmCalc, 2) }}</td>
                    </tr>
                    @endif

                    {{-- Kilómetros --}}
                    @if($cotizacion->km_distancia)
                    <tr>
                        <td><i class="bx bx-map-alt text-danger me-1"></i>
                            Traslado (km)<br>
                            <span class="text-muted">{{ $cotizacion->km_distancia }} km recorridos</span>
                        </td>
                        <td class="text-center text-muted">
                            {{ $cotizacion->km_distancia }} km
                            × ${{ number_format($cotizacion->costo_km_unitario, 2) }}/km
                        </td>
                        <td class="text-end fw-bold">${{ number_format($costoKmCalc, 2) }}</td>
                    </tr>
                    @endif

                    {{-- Insumos --}}
                    @if($cotizacion->costo_insumos)
                    <tr>
                        <td><i class="bx bx-injection text-secondary me-1"></i>
                            Insumos especiales<br>
                            <span class="text-muted">{{ count($cotizacion->insumos_seleccionados ?? []) }} artículo(s)</span>
                        </td>
                        <td class="text-center text-muted">precio × cantidad c/u</td>
                        <td class="text-end fw-bold">${{ number_format($cotizacion->costo_insumos, 2) }}</td>
                    </tr>
                    @endif

                    <tr class="table-success">
                        <td colspan="2" class="fw-bold fs-6">TOTAL</td>
                        <td class="text-end fw-bold fs-5">${{ number_format($cotizacion->costo, 2) }} <small>MXN</small></td>
                    </tr>
                </tbody>
            </table>

            @if($cotizacion->anticipo)
            <div class="d-flex justify-content-between align-items-center py-2 px-2 rounded"
                 style="background:#f0f0ff;border:1px solid #696cff40;">
                <span class="small fw-semibold"><i class="bx bx-credit-card me-1 text-primary"></i>Anticipo requerido</span>
                <strong class="text-primary">${{ number_format($cotizacion->anticipo, 2) }} MXN</strong>
            </div>
            @endif

            @if($cotizacion->respuesta)
            <div class="alert alert-success mt-2 mb-0 py-2 small">{{ $cotizacion->respuesta }}</div>
            @endif
        </div>
        @endif

        @if($cotizacion->estado === 'Cancelada' && $cotizacion->respuesta)
        <div class="card-body border-top pt-3">
            <div class="alert alert-danger mb-0 small">
                <strong>Motivo:</strong> {{ $cotizacion->respuesta }}
            </div>
        </div>
        @endif

        @if($cotizacion->datos_paciente)
        <div class="card-body border-top pt-3">
            <h6 class="fw-bold text-danger mb-3 small"><i class="bx bx-lock-alt me-1"></i>Datos confidenciales del paciente</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Nombre</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['nombre'] ?? '—' }}</dd>

                <dt class="col-5 text-muted">Fecha de nacimiento</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['nacimiento'] ? \Carbon\Carbon::parse($cotizacion->datos_paciente['nacimiento'])->format('d/m/Y') : '—' }}</dd>

                @if($cotizacion->datos_paciente['curp'] ?? null)
                <dt class="col-5 text-muted">CURP</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['curp'] }}</dd>
                @endif

                @if($cotizacion->datos_paciente['tipo_sangre'] ?? null)
                <dt class="col-5 text-muted">Tipo de sangre</dt>
                <dd class="col-7"><span class="badge bg-label-danger">{{ $cotizacion->datos_paciente['tipo_sangre'] }}</span></dd>
                @endif

                <dt class="col-5 text-muted">Diagnóstico</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['diagnostico'] ?? '—' }}</dd>

                @if($cotizacion->datos_paciente['alergias'] ?? null)
                <dt class="col-5 text-muted">Alergias</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['alergias'] }}</dd>
                @endif

                @if($cotizacion->datos_paciente['medico'] ?? null)
                <dt class="col-5 text-muted">Médico tratante</dt>
                <dd class="col-7">{{ $cotizacion->datos_paciente['medico'] }}</dd>
                @endif
            </dl>
        </div>
        @endif

        @if($cotizacion->datos_evento)
        <div class="card-body border-top pt-3">
            <h6 class="fw-bold text-info mb-3 small"><i class="bx bx-calendar-event me-1"></i>Datos del evento</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Nombre del evento</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['nombre'] ?? '—' }}</dd>

                <dt class="col-5 text-muted">Tipo de evento</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['tipo'] ?? '—' }}</dd>

                <dt class="col-5 text-muted">Asistentes</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['personas'] ?? '—' }}</dd>

                <dt class="col-5 text-muted">Responsable</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['responsable'] ?? '—' }}</dd>

                @if($cotizacion->datos_evento['tel_responsable'] ?? null)
                <dt class="col-5 text-muted">Tel. responsable</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['tel_responsable'] }}</dd>
                @endif

                @if($cotizacion->datos_evento['indicaciones'] ?? null)
                <dt class="col-5 text-muted">Indicaciones</dt>
                <dd class="col-7">{{ $cotizacion->datos_evento['indicaciones'] }}</dd>
                @endif
            </dl>
        </div>
        @endif

        @if($cotizacion->decision_cliente)
        <div class="card-body border-top pt-3">
            <h6 class="fw-semibold mb-2 small text-uppercase text-muted">Respuesta del cliente</h6>
            @if($cotizacion->decision_cliente === 'confirmada')
                <div class="alert alert-success py-2 mb-0 small">
                    <i class="bx bx-check-circle me-1"></i>
                    <strong>El cliente confirmó el servicio.</strong>
                    @if($cotizacion->comentario_cliente)
                        <br>{{ $cotizacion->comentario_cliente }}
                    @endif
                </div>
            @else
                <div class="alert alert-danger py-2 mb-0 small">
                    <i class="bx bx-x-circle me-1"></i>
                    <strong>El cliente declinó la propuesta.</strong>
                    @if($cotizacion->comentario_cliente)
                        <br>{{ $cotizacion->comentario_cliente }}
                    @endif
                </div>
            @endif
        </div>
        @endif

        @if($tieneMapa)
        <div class="px-3 py-2 d-flex justify-content-between align-items-center border-top">
            <span class="small fw-semibold text-muted"><i class="bx bx-map-alt me-1 text-primary"></i>Mapa</span>
            <a href="{{ $mapsLink }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-directions me-1"></i>Google Maps
            </a>
        </div>
        <iframe src="{{ $iframeSrc }}" width="100%" height="280"
            style="border:0;display:block;" allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
    </div>

    {{-- cambiar estado --}}
    @if(in_array($cotizacion->estado, ['Aceptada','Cancelada']))
    <div class="card no-print">
        <div class="card-body">
            <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST" class="d-flex gap-2">
                @csrf @method('PUT')
                <select name="estado" class="form-select form-select-sm">
                    @foreach(['Pendiente','En revisión','Aceptada','Cancelada'] as $est)
                        <option value="{{ $est }}" {{ $cotizacion->estado === $est ? 'selected' : '' }}>{{ $est }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-secondary text-nowrap">Cambiar</button>
            </form>
        </div>
    </div>
    @endif
</div>

{{-- columna derecha --}}
<div class="col-lg-7 no-print">

@if($cotizacion->estado === 'En revisión')
<form action="{{ route('cotizaciones.aceptar', $cotizacion) }}" method="POST" id="form-paquete">
@csrf

{{-- sección 1: km, tarifa y horas --}}
<div class="card mb-4">
    <div class="card-header bg-label-primary">
        <h6 class="mb-0"><i class="bx bx-map-alt me-1"></i>1. Distancia, tarifa y duración</h6>
    </div>
    <div class="card-body">

        {{-- mapa interactivo --}}
        @if($cotizacion->lat_origen || $cotizacion->lat_destino)
        <div id="map-admin" class="map-admin-box mb-2"></div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted">
                <i class="bx bx-info-circle me-1"></i>
                Arrastra los marcadores para ajustar y el km se recalcula automáticamente.
            </small>
            <div class="d-flex gap-2 align-items-center" style="font-size:.78rem">
                @if($cotizacion->tipo_servicio === 'Traslado')
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#696cff;"></span> Origen</span>
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#e74c3c;"></span> Destino</span>
                @else
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#696cff;"></span> Ubicación del evento</span>
                @endif
                @if($empresa->lat_base && $empresa->lng_base)
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#2ecc71;"></span> Base</span>
                @endif
            </div>
        </div>
        @endif

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kilómetros totales</label>
                <div class="input-group">
                    <input type="number" id="inp_km" name="km_distancia" step="0.01" min="0"
                        class="form-control" required
                        value="{{ old('km_distancia', $cotizacion->km_distancia ?? $kmCalculado) }}">
                    <span class="input-group-text">km</span>
                </div>
                <small class="text-muted" id="txt-km-detalle">
                    @if($kmCalculado)
                        Haversine calculado: <strong>{{ $kmCalculado }} km</strong>
                    @else
                        Se actualiza al mover los marcadores
                    @endif
                </small>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tarifa por km</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" id="inp_tarifa_km" name="costo_km_unitario" step="0.01" min="0"
                        class="form-control" required
                        value="{{ old('costo_km_unitario', $empresa->costo_km ?? 25) }}">
                </div>
                <small class="text-muted">Configurado en Empresa</small>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Horas del servicio</label>
                <div class="input-group">
                    <input type="number" id="inp_horas" name="horas_servicio" step="0.5" min="0.5"
                        class="form-control" value="{{ old('horas_servicio', $cotizacion->horas_servicio ?? 1) }}">
                    <span class="input-group-text">hrs</span>
                </div>
                <small class="text-muted">Aplica a operador y paramédicos</small>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Subtotal km</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="text" id="sub_km" class="form-control bg-light fw-bold" readonly value="0.00">
                    <span class="input-group-text">MXN</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- sección 2: ambulancia --}}
<div class="card mb-4">
    <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bx bx-ambulance me-1"></i>2. Ambulancia disponible</h6>
        @if($tipoSolicitado)
        <span class="badge bg-warning text-dark">
            <i class="bx bx-lock-alt me-1"></i>Solo tipo: {{ $tipoSolicitado->nombre_tipo }}
        </span>
        @elseif($cotizacion->tipo_ambulancia_preferida)
        <span class="badge bg-warning text-dark">
            <i class="bx bx-star me-1"></i>Cliente prefiere: {{ $cotizacion->tipo_ambulancia_preferida }}
        </span>
        @endif
    </div>
    <div class="card-body">
        @if($ambulancias->isEmpty())
            <div class="alert alert-warning mb-0">
                No hay ambulancias de tipo <strong>{{ $tipoSolicitado->nombre_tipo ?? $cotizacion->tipo_ambulancia_preferida }}</strong>
                disponibles para la fecha solicitada.
            </div>
        @else
        <div class="mb-2">
            <input type="text" id="search-amb" class="form-control form-control-sm"
                placeholder="Buscar ambulancia..." oninput="pagers.amb && pagers.amb.filter()">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40"></th>
                        <th>Placa</th>
                        <th>Tipo</th>
                        <th class="text-end">Base tipo</th>
                    </tr>
                </thead>
                <tbody id="tbody-amb">
                    @foreach($ambulancias as $amb)
                    @php $ambSelected = old('id_ambulancia', $cotizacion->id_ambulancia) == $amb->id_ambulancia; @endphp
                    <tr class="amb-row {{ $ambSelected ? 'table-success' : '' }}"
                        data-costo-tipo="{{ $amb->tipo->costo_base ?? 0 }}"
                        data-salario-op="0"
                        style="cursor:pointer" onclick="seleccionarAmb(this, {{ $amb->id_ambulancia }})">
                        <td>
                            <input type="radio" name="id_ambulancia" value="{{ $amb->id_ambulancia }}"
                                class="form-check-input" {{ $ambSelected ? 'checked' : '' }}>
                        </td>
                        <td class="fw-semibold">{{ $amb->placa }}</td>
                        <td>{{ $amb->tipo->nombre_tipo ?? '—' }}</td>
                        <td class="text-end fw-bold text-success">${{ number_format($amb->tipo->costo_base ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pager-amb"></div>
        @endif
        <div class="mt-2 text-end">
            <span class="text-muted small">Subtotal ambulancia: </span>
            <strong id="sub_ambulancia" class="text-success">$0.00</strong>
        </div>
    </div>
</div>

{{-- sección 3: operador --}}
<div class="card mb-4">
    <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bx bx-user-check me-1"></i>3. Operador asignado</h6>
        <span class="badge bg-info text-white">
            <i class="bx bx-check-circle me-1"></i>Auto-asignado — puedes modificarlo si es necesario
        </span>
    </div>
    <div class="card-body">
        @error('id_operador')
            <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
        @enderror
        @if($operadores->isEmpty())
            <div class="alert alert-warning mb-0">No hay operadores disponibles para la fecha solicitada.</div>
        @else
        <div class="mb-2">
            <input type="text" id="search-op" class="form-control form-control-sm"
                placeholder="Buscar operador..." oninput="pagers.op && pagers.op.filter()">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40"></th>
                        <th>Nombre</th>
                        <th class="text-end">$/hora</th>
                    </tr>
                </thead>
                <tbody id="tbody-op">
                    @foreach($operadores as $op)
                    @php $selected = old('id_operador', $operadorSugerido) == $op->id_usuario; @endphp
                    <tr class="op-row {{ $selected ? 'table-success' : '' }}"
                        style="cursor:pointer"
                        onclick="seleccionarOp(this, {{ $op->id_usuario }})">
                        <td>
                            <input type="radio" name="id_operador" value="{{ $op->id_usuario }}"
                                class="form-check-input" {{ $selected ? 'checked' : '' }}>
                        </td>
                        <td class="fw-semibold">
                            {{ $op->usuario->nombre ?? '—' }}
                            {{ $op->usuario->ap_paterno ?? '' }}
                        </td>
                        <td class="text-end">${{ number_format($empresa->tarifa_operador ?? 100, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pager-op"></div>
        @endif
    </div>
</div>

{{-- sección 4: paramédicos --}}
<div class="card mb-4">
    <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bx bx-user-plus me-1"></i>4. Paramédicos <span class="badge bg-warning text-dark ms-1">Mínimo 2</span></h6>
        <span class="badge bg-info text-white">
            <i class="bx bx-check-circle me-1"></i>Auto-asignados — puedes modificarlos si es necesario
        </span>
    </div>
    <div class="card-body">
        @if($paramedicos->isEmpty())
            <div class="alert alert-warning">No hay paramédicos disponibles para la fecha solicitada.</div>
        @else
        <div class="mb-2">
            <input type="text" id="search-pm" class="form-control form-control-sm"
                placeholder="Buscar paramédico..." oninput="pagers.pm && pagers.pm.filter()">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40"></th>
                        <th>Paramédico</th>
                        <th class="text-end">$/hora</th>
                        <th class="text-end">$/hora × horas = subtotal</th>
                    </tr>
                </thead>
                <tbody id="tabla-paramedicos">
                    @foreach($paramedicos as $pm)
                    @php
                        $pmAutoIds = $cotizacion->paramedicos_ids ?? [];
                        $checked = (is_array(old('paramedicos_ids')) && in_array($pm->id_usuario, old('paramedicos_ids')))
                                || (!is_array(old('paramedicos_ids')) && in_array($pm->id_usuario, $pmAutoIds));
                    @endphp
                    <tr class="pm-row {{ $checked ? 'table-success' : '' }}"
                        style="cursor:pointer" onclick="toggleParamedico(this)">
                        <td>
                            <input type="checkbox" name="paramedicos_ids[]" value="{{ $pm->id_usuario }}"
                                class="form-check-input pm-check" {{ $checked ? 'checked' : '' }}>
                        </td>
                        <td>
                            {{ $pm->usuario->nombre ?? '—' }}
                            {{ $pm->usuario->ap_paterno ?? '' }}
                        </td>
                        <td class="text-end">${{ number_format($empresa->tarifa_paramedico ?? 80, 2) }}</td>
                        <td class="text-end fw-bold pm-subtotal">$0.00</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pager-pm"></div>
        @endif

        <div class="mt-2 d-flex justify-content-between align-items-center">
            <span id="aviso-min-pm" class="text-warning small d-none">
                <i class="bx bx-error me-1"></i>Se requieren mínimo 2 paramédicos
            </span>
            <span class="ms-auto text-muted small">Subtotal paramédicos: </span>
            <span id="sub_paramedicos" class="text-success ms-2 fw-bold">$0.00</span>
        </div>
    </div>
</div>

{{-- sección 5: insumos --}}
<div class="card mb-4">
    <div class="card-header bg-label-primary">
        <h6 class="mb-0"><i class="bx bx-injection me-1"></i>5. Insumos especiales
            @if($cotizacion->padecimientos_paciente)
                <span class="badge bg-warning text-dark ms-1"><i class="bx bx-plus-medical me-1"></i>Revisar padecimientos</span>
            @endif
        </h6>
    </div>
    <div class="card-body">
        @if($cotizacion->padecimientos_paciente)
        <div class="alert alert-warning py-2 mb-3 small">
            <strong>Padecimientos del paciente:</strong> {{ $cotizacion->padecimientos_paciente }}
        </div>
        @endif

        @if($insumos->isEmpty())
            <div class="alert alert-info mb-0">No hay insumos registrados en el catálogo.</div>
        @else
        <div class="mb-2">
            <input type="text" id="search-ins" class="form-control form-control-sm"
                placeholder="Buscar insumo..." oninput="pagers.ins && pagers.ins.filter()">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40"></th>
                        <th>Insumo</th>
                        <th class="text-end">$/unidad</th>
                        <th style="width:100px">Cantidad</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tbody-ins">
                    @foreach($insumos as $ins)
                    <tr class="ins-row" data-costo="{{ $ins->costo_unidad }}" data-id="{{ $ins->id_insumo }}">
                        <td>
                            <input type="checkbox" name="insumos[{{ $ins->id_insumo }}][seleccionado]"
                                value="1" class="form-check-input ins-check"
                                onchange="recalcularInsumos()">
                        </td>
                        <td>{{ $ins->nombre_insumo }}</td>
                        <td class="text-end">${{ number_format($ins->costo_unidad, 2) }}</td>
                        <td>
                            <input type="number" name="insumos[{{ $ins->id_insumo }}][cantidad]"
                                class="form-control form-control-sm ins-cant"
                                value="1" min="1" onchange="recalcularInsumos()">
                        </td>
                        <td class="text-end fw-bold ins-subtotal">$0.00</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pager-ins"></div>
        @endif
        <div class="mt-2 text-end">
            <span class="text-muted small">Subtotal insumos: </span>
            <strong id="sub_insumos" class="text-success">$0.00</strong>
        </div>
    </div>
</div>

{{-- sección 6: resumen y envío --}}
<div class="card mb-4 border-success">
    <div class="card-header bg-label-success">
        <h6 class="mb-0 text-success"><i class="bx bx-receipt me-1"></i>6. Resumen del paquete</h6>
    </div>
    <div class="card-body">
        <table class="table table-sm mb-3 small">
            <thead class="table-light">
                <tr><th>Concepto</th><th class="text-center text-muted">Fórmula</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="bx bx-ambulance text-primary me-1"></i>Ambulancia</td>
                    <td class="text-center text-muted" id="formula_amb">—</td>
                    <td id="res_amb" class="text-end fw-bold">$0.00</td>
                </tr>
                <tr>
                    <td><i class="bx bx-user-check text-info me-1"></i>Operador
                        <small class="text-muted d-block">${{ number_format($empresa->tarifa_operador ?? 100, 2) }}/hr</small>
                    </td>
                    <td class="text-center text-muted" id="formula_op">—</td>
                    <td id="res_op" class="text-end fw-bold">$0.00</td>
                </tr>
                <tr>
                    <td><i class="bx bx-user-plus text-warning me-1"></i>Paramédicos
                        <small class="text-muted d-block">${{ number_format($empresa->tarifa_paramedico ?? 80, 2) }}/hr c/u</small>
                    </td>
                    <td class="text-center text-muted" id="formula_pm">—</td>
                    <td id="res_pm" class="text-end fw-bold">$0.00</td>
                </tr>
                <tr>
                    <td><i class="bx bx-map-alt text-danger me-1"></i>Km de traslado</td>
                    <td class="text-center text-muted" id="formula_km">—</td>
                    <td id="res_km" class="text-end fw-bold">$0.00</td>
                </tr>
                <tr>
                    <td><i class="bx bx-injection text-secondary me-1"></i>Insumos</td>
                    <td class="text-center text-muted">precio × cant</td>
                    <td id="res_ins" class="text-end fw-bold">$0.00</td>
                </tr>
                <tr class="table-success fs-5">
                    <td colspan="2" class="fw-bold">TOTAL</td>
                    <td id="res_total" class="text-end fw-bold">$0.00 MXN</td>
                </tr>
            </tbody>
        </table>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                <i class="bx bx-credit-card me-1 text-primary"></i>Anticipo requerido (MercadoPago)
            </label>
            <div class="input-group" style="max-width:220px">
                <span class="input-group-text">$</span>
                <input type="number" name="anticipo" step="0.01" min="0"
                    class="form-control"
                    value="{{ old('anticipo', $cotizacion->anticipo ?? '') }}"
                    placeholder="0.00">
                <span class="input-group-text">MXN</span>
            </div>
            <small class="text-muted">El cliente deberá pagar este monto antes de confirmar el servicio.</small>
        </div>

        @if($cotizacion->tipo_servicio === 'Traslado')
        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre del paciente</label>
            <input type="text" name="nombre_paciente" class="form-control"
                value="{{ old('nombre_paciente', $cotizacion->nombre_paciente) }}"
                placeholder="Nombre completo del paciente a trasladar">
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-semibold">¿Qué incluye el servicio? <span class="text-danger">*</span></label>
            <textarea name="incluye" id="inp_incluye" rows="5" required
                class="form-control @error('incluye') is-invalid @enderror"
                placeholder="El texto se genera automáticamente al seleccionar los recursos, puedes editarlo libremente.">{{ old('incluye', $cotizacion->incluye) }}</textarea>
            @error('incluye')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="generarIncluye()">
                <i class="bx bx-refresh me-1"></i>Regenerar texto automáticamente
            </button>
        </div>

        <div class="mb-3">
            <label class="form-label">Mensaje adicional al cliente</label>
            <textarea name="respuesta" rows="2" class="form-control"
                placeholder="Instrucciones, horario de confirmación, anticipos, etc.">{{ old('respuesta', $cotizacion->respuesta) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success w-100 btn-lg" id="btn-aceptar">
            <i class="bx bx-check me-1"></i>Aceptar cotización y enviar propuesta al cliente
        </button>
    </div>
</div>
</form>

{{-- rechazar --}}
<div class="card border-danger">
    <div class="card-header bg-label-danger">
        <h6 class="mb-0 text-danger"><i class="bx bx-x-circle me-1"></i>Rechazar solicitud</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('cotizaciones.rechazar', $cotizacion) }}" method="POST">
            @csrf
            <div class="mb-2">
                <textarea name="respuesta" rows="2" required class="form-control"
                    placeholder="Motivo del rechazo..."></textarea>
            </div>
            <button type="submit" class="btn btn-danger w-100"
                onclick="return confirm('¿Confirmas rechazar esta cotización?')">
                <i class="bx bx-x me-1"></i>Rechazar
            </button>
        </form>
    </div>
</div>
@endif

</div>
</div>

<div class="mt-3 d-flex gap-2 no-print">
    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Volver
    </a>
    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
        <i class="bx bx-printer me-1"></i> Imprimir
    </button>
    <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST"
        onsubmit="return confirm('¿Eliminar esta cotización?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">
            <i class="bx bx-trash me-1"></i> Eliminar
        </button>
    </form>
</div>

@php
$datosParamedicos = $paramedicos->map(function($p) {
    return [
        'id'     => $p->id_usuario,
        'nombre' => trim(($p->usuario->nombre ?? '') . ' ' . ($p->usuario->ap_paterno ?? '')),
    ];
})->values();

$datosInsumos = $insumos->map(function($i) {
    return [
        'id'     => $i->id_insumo,
        'nombre' => $i->nombre_insumo,
        'costo'  => (float) $i->costo_unidad,
    ];
})->values();
@endphp

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ── Mapa interactivo admin ──
(function () {
    var LAT_ORIGEN  = {{ $cotizacion->lat_origen  ? (float)$cotizacion->lat_origen  : 'null' }};
    var LNG_ORIGEN  = {{ $cotizacion->lng_origen  ? (float)$cotizacion->lng_origen  : 'null' }};
    var LAT_DESTINO = {{ $cotizacion->lat_destino ? (float)$cotizacion->lat_destino : 'null' }};
    var LNG_DESTINO = {{ $cotizacion->lng_destino ? (float)$cotizacion->lng_destino : 'null' }};
    var LAT_BASE    = {{ $empresa->lat_base ? (float)$empresa->lat_base : 'null' }};
    var LNG_BASE    = {{ $empresa->lng_base ? (float)$empresa->lng_base : 'null' }};
    var TIPO        = '{{ $cotizacion->tipo_servicio }}';
    var COSTO_KM_U  = {{ (float)($empresa->costo_km ?? 25) }};

    if (!document.getElementById('map-admin')) return;

    function haversine(lat1, lng1, lat2, lng2) {
        var R = 6371, dLat = (lat2-lat1)*Math.PI/180, dLng = (lng2-lng1)*Math.PI/180;
        var a = Math.sin(dLat/2)*Math.sin(dLat/2) +
                Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*
                Math.sin(dLng/2)*Math.sin(dLng/2);
        return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 10) / 10;
    }

    function iconColor(color) {
        return L.divIcon({
            className: '',
            html: '<div style="width:14px;height:14px;border-radius:50%;background:'+color+';border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
            iconSize: [14, 14], iconAnchor: [7, 7]
        });
    }

    var defaultLat = LAT_ORIGEN || LAT_DESTINO || 17.0669;
    var defaultLng = LNG_ORIGEN || LNG_DESTINO || -96.7203;

    var map = L.map('map-admin').setView([defaultLat, defaultLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var markerOrigen  = null;
    var markerDestino = null;
    var polyline      = null;

    function updateKm() {
        var latO = markerOrigen  ? markerOrigen.getLatLng().lat  : null;
        var lngO = markerOrigen  ? markerOrigen.getLatLng().lng  : null;
        var latD = markerDestino ? markerDestino.getLatLng().lat : null;
        var lngD = markerDestino ? markerDestino.getLatLng().lng : null;

        var kmBase  = (LAT_BASE && LNG_BASE && latO) ? haversine(LAT_BASE, LNG_BASE, latO, lngO) : 0;
        var kmRuta  = (latO && latD) ? haversine(latO, lngO, latD, lngD) : 0;
        var kmTotal = Math.round((kmBase + kmRuta) * 10) / 10;

        var inpKm = document.getElementById('inp_km');
        if (inpKm) {
            inpKm.value = kmTotal;
            inpKm.dispatchEvent(new Event('input'));
        }

        var txtDetalle = document.getElementById('txt-km-detalle');
        if (txtDetalle) {
            var partes = [];
            if (kmBase > 0) partes.push(kmBase + ' km base→origen');
            if (kmRuta > 0) partes.push(kmRuta + ' km origen→destino');
            txtDetalle.innerHTML = partes.length
                ? partes.join(' + ') + ' = <strong>' + kmTotal + ' km</strong>'
                : 'Mueve los marcadores para calcular';
        }

        if (polyline) map.removeLayer(polyline);
        if (markerOrigen && markerDestino) {
            polyline = L.polyline([markerOrigen.getLatLng(), markerDestino.getLatLng()],
                { color: '#696cff', weight: 3, dashArray: '6 4' }).addTo(map);
        }
    }

    if (LAT_ORIGEN && LNG_ORIGEN) {
        markerOrigen = L.marker([LAT_ORIGEN, LNG_ORIGEN], {
            draggable: true,
            icon: iconColor('#696cff'),
            title: 'Origen'
        }).addTo(map);
        markerOrigen.on('dragend', updateKm);
    }

    if (LAT_DESTINO && LNG_DESTINO && TIPO === 'Traslado') {
        markerDestino = L.marker([LAT_DESTINO, LNG_DESTINO], {
            draggable: true,
            icon: iconColor('#e74c3c'),
            title: 'Destino'
        }).addTo(map);
        markerDestino.on('dragend', updateKm);
    }

    if (LAT_BASE && LNG_BASE) {
        L.marker([LAT_BASE, LNG_BASE], {
            draggable: false,
            icon: iconColor('#2ecc71'),
            title: 'Base'
        }).addTo(map);
    }

    // Ajustar vista para mostrar todos los puntos
    var bounds = [];
    if (markerOrigen)  bounds.push(markerOrigen.getLatLng());
    if (markerDestino) bounds.push(markerDestino.getLatLng());
    if (LAT_BASE && LNG_BASE) bounds.push([LAT_BASE, LNG_BASE]);
    if (bounds.length > 1) {
        map.fitBounds(L.latLngBounds(bounds).pad(0.2));
    } else if (bounds.length === 1) {
        map.setView(bounds[0], 14);
    }

    updateKm();
})();

// ── Paginador cliente genérico ──
var pagers = {};
function TablePager(cfg) {
    var tbody   = document.getElementById(cfg.tbody);
    var searchEl = cfg.search ? document.getElementById(cfg.search) : null;
    var pagerEl  = cfg.pager  ? document.getElementById(cfg.pager)  : null;
    var pageSize = cfg.pageSize || 8;
    var page = 1;

    function allRows() { return tbody ? Array.from(tbody.querySelectorAll('tr')) : []; }

    function filteredRows() {
        var q = searchEl ? searchEl.value.trim().toLowerCase() : '';
        return allRows().filter(function(r) {
            return !q || r.textContent.toLowerCase().includes(q);
        });
    }

    function render() {
        var rows = filteredRows();
        var pages = Math.ceil(rows.length / pageSize) || 1;
        page = Math.max(1, Math.min(page, pages));
        var start = (page - 1) * pageSize;

        allRows().forEach(function(r) { r.style.display = 'none'; });
        rows.slice(start, start + pageSize).forEach(function(r) { r.style.display = ''; });

        if (pagerEl) {
            if (rows.length <= pageSize) {
                pagerEl.innerHTML = '';
            } else {
                pagerEl.innerHTML =
                    '<div class="d-flex justify-content-between align-items-center mt-2 px-1">' +
                    '<small class="text-muted">' + rows.length + ' registros &nbsp;|&nbsp; página ' + page + ' de ' + pages + '</small>' +
                    '<div class="btn-group btn-group-sm">' +
                    '<button type="button" class="btn btn-outline-secondary" ' + (page <= 1 ? 'disabled' : '') + ' onclick="pagers[\'' + cfg.id + '\'].prev()">&#8249;</button>' +
                    '<button type="button" class="btn btn-outline-secondary" ' + (page >= pages ? 'disabled' : '') + ' onclick="pagers[\'' + cfg.id + '\'].next()">&#8250;</button>' +
                    '</div></div>';
            }
        }
    }

    if (searchEl) searchEl.addEventListener('input', function() { page = 1; render(); });
    render();

    return {
        filter: function() { page = 1; render(); },
        prev:   function() { page--; render(); },
        next:   function() { page++; render(); },
    };
}

// ── Tarifas globales ──
var TARIFA_OP = {{ (float)($empresa->tarifa_operador  ?? 100) }};
var TARIFA_PM = {{ (float)($empresa->tarifa_paramedico ?? 80)  }};

// ── Datos de paramédicos para generarIncluye ──
var datosParamedicos = @json($datosParamedicos);
var datosInsumos     = @json($datosInsumos);

// ── Helpers ──
function fmt(n) { return '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
function getKm()     { return parseFloat(document.getElementById('inp_km')?.value)       || 0; }
function getTarifa() { return parseFloat(document.getElementById('inp_tarifa_km')?.value) || 0; }
function getHoras()  { return parseFloat(document.getElementById('inp_horas')?.value)     || 1; }

// ── Ambulancia: costo_base × horas ──
function seleccionarAmb(row, id) {
    document.querySelectorAll('.amb-row').forEach(r => r.classList.remove('table-success'));
    row.classList.add('table-success');
    row.querySelector('input[type=radio]').checked = true;
    recalcularTotal();
}
function getAmbCosto() {
    var sel = document.querySelector('.amb-row.table-success');
    if (!sel) return 0;
    return (parseFloat(sel.dataset.costoTipo) || 0) * getHoras();
}
function actualizarSubtotalAmb() {
    var el = document.getElementById('sub_ambulancia');
    if (el) el.textContent = fmt(getAmbCosto());
}

// ── Operador: tarifa global × horas ──
function seleccionarOp(row, id) {
    document.querySelectorAll('.op-row').forEach(r => r.classList.remove('table-success'));
    row.classList.add('table-success');
    row.querySelector('input[type=radio]').checked = true;
    recalcularTotal();
}
function getOpCosto() {
    return document.querySelector('.op-row.table-success') ? TARIFA_OP * getHoras() : 0;
}

// ── Paramédicos: tarifa global × N × horas ──
function toggleParamedico(row) {
    var cb = row.querySelector('.pm-check');
    cb.checked = !cb.checked;
    row.classList.toggle('table-success', cb.checked);
    recalcularParamedicos();
}
function recalcularParamedicos() {
    var horas = getHoras();
    var selCount = 0;
    var avisoMin = document.getElementById('aviso-min-pm');

    document.querySelectorAll('.pm-row').forEach(function(row) {
        var cb  = row.querySelector('.pm-check');
        var cel = row.querySelector('.pm-subtotal');
        if (cb.checked) {
            selCount++;
            var sub = TARIFA_PM * horas;
            cel.innerHTML = '<span class="text-muted small d-block" style="font-size:.75rem">'
                + fmt(TARIFA_PM) + '/hr &times; ' + horas + 'h</span>'
                + fmt(sub);
        } else {
            cel.innerHTML = '<span class="text-muted">—</span>';
        }
    });

    var total = TARIFA_PM * selCount * horas;
    if (avisoMin) avisoMin.classList.toggle('d-none', selCount >= 2);
    var elSub = document.getElementById('sub_paramedicos');
    if (elSub) {
        elSub.innerHTML = selCount > 0
            ? fmt(TARIFA_PM) + '/hr &times; ' + selCount + ' &times; ' + horas + 'h = <strong>' + fmt(total) + '</strong>'
            : fmt(0);
    }
    recalcularTotal();
}

// ── Insumos ──
function recalcularInsumos() {
    var total = 0;
    document.querySelectorAll('.ins-row').forEach(function(row) {
        var cb   = row.querySelector('.ins-check');
        var cant = parseInt(row.querySelector('.ins-cant').value) || 1;
        var cost = parseFloat(row.dataset.costo) || 0;
        var sub  = cb.checked ? cost * cant : 0;
        row.querySelector('.ins-subtotal').textContent = fmt(sub);
        total += sub;
    });
    document.getElementById('sub_insumos').textContent = fmt(total);
    recalcularTotal();
}

// ── KM ──
function recalcularKm() {
    var sub = getKm() * getTarifa();
    var el = document.getElementById('sub_km');
    if (el) el.value = sub.toFixed(2);
    recalcularTotal();
}

// ── Total general con desglose y fórmulas ──
function recalcularTotal() {
    var horas = getHoras();
    var km    = getKm();
    var tar   = getTarifa();
    var amb   = getAmbCosto();
    var op    = getOpCosto();
    var pm    = TARIFA_PM * document.querySelectorAll('.pm-row .pm-check:checked').length * horas;
    var ins   = parseFloat(document.getElementById('sub_insumos')?.textContent.replace(/[$,]/g,'')) || 0;
    var tot   = km * tar + amb + op + pm + ins;

    // Subtotales
    if (document.getElementById('sub_ambulancia')) document.getElementById('sub_ambulancia').textContent = fmt(amb);

    // Resumen con fórmulas
    var selAmb = document.querySelector('.amb-row.table-success');
    var tipoAmb = selAmb ? selAmb.querySelectorAll('td')[2]?.textContent.trim() : null;
    var costoBase = selAmb ? (parseFloat(selAmb.dataset.costoTipo) || 0) : 0;

    var nPm = document.querySelectorAll('.pm-row .pm-check:checked').length;
    var hasOp = !!document.querySelector('.op-row.table-success');

    if (document.getElementById('res_amb')) {
        document.getElementById('res_amb').textContent = fmt(amb);
        document.getElementById('formula_amb').textContent =
            tipoAmb ? ('$' + costoBase.toFixed(2) + '/hr × ' + horas + 'h') : '—';
    }
    if (document.getElementById('res_op')) {
        document.getElementById('res_op').textContent = fmt(op);
        document.getElementById('formula_op').textContent =
            hasOp ? ('$' + TARIFA_OP.toFixed(2) + '/hr × ' + horas + 'h') : '—';
    }
    if (document.getElementById('res_pm')) {
        document.getElementById('res_pm').textContent = fmt(pm);
        document.getElementById('formula_pm').textContent =
            nPm ? ('$' + TARIFA_PM.toFixed(2) + '/hr × ' + nPm + ' × ' + horas + 'h') : '—';
    }
    if (document.getElementById('res_km')) {
        document.getElementById('res_km').textContent = fmt(km * tar);
        document.getElementById('formula_km').textContent =
            km ? (km + ' km × $' + tar.toFixed(2) + '/km') : '—';
    }
    if (document.getElementById('res_ins')) document.getElementById('res_ins').textContent = fmt(ins);
    if (document.getElementById('res_total')) document.getElementById('res_total').textContent = fmt(tot) + ' MXN';
}

// ── Generar texto "incluye" ──
function generarIncluye() {
    var horas = getHoras();
    var lines = [];
    var km = getKm(); var tar = getTarifa();

    var ambRow = document.querySelector('.amb-row.table-success');
    if (ambRow) {
        var tipoCell = ambRow.querySelectorAll('td')[2];
        var cb = parseFloat(ambRow.dataset.costoTipo) || 0;
        lines.push('• Ambulancia ' + (tipoCell ? tipoCell.textContent.trim() : '') + ' — $' + cb.toFixed(2) + '/hr × ' + horas + 'h');
    }

    var opRow = document.querySelector('.op-row.table-success');
    if (opRow) {
        var opNombre = opRow.querySelectorAll('td')[1].textContent.trim();
        lines.push('• Operador: ' + opNombre + ' — $' + TARIFA_OP.toFixed(2) + '/hr × ' + horas + 'h');
    }

    var pmNames = [];
    document.querySelectorAll('.pm-row').forEach(function(row) {
        if (row.querySelector('.pm-check').checked)
            pmNames.push(row.querySelectorAll('td')[1].textContent.trim());
    });
    if (pmNames.length)
        lines.push('• ' + pmNames.length + ' paramédico(s): ' + pmNames.join(', ') + ' — $' + TARIFA_PM.toFixed(2) + '/hr × ' + pmNames.length + ' × ' + horas + 'h');

    if (km > 0) lines.push('• Traslado de ' + km + ' km (tarifa $' + tar.toFixed(2) + '/km)');

    document.querySelectorAll('.ins-row').forEach(function(row) {
        if (row.querySelector('.ins-check').checked) {
            var nombre = row.querySelectorAll('td')[1].textContent.trim();
            var cant   = row.querySelector('.ins-cant').value;
            lines.push('• ' + nombre + ' (x' + cant + ')');
        }
    });

    document.getElementById('inp_incluye').value = lines.join('\n');
}

// ── Eventos ──
document.addEventListener('DOMContentLoaded', function () {
    var inpKm     = document.getElementById('inp_km');
    var inpTarifa = document.getElementById('inp_tarifa_km');
    var inpHoras  = document.getElementById('inp_horas');

    if (inpKm)     inpKm.addEventListener('input', recalcularKm);
    if (inpTarifa) inpTarifa.addEventListener('input', recalcularKm);
    if (inpHoras)  inpHoras.addEventListener('input', function() {
        recalcularParamedicos();
        recalcularTotal();
    });

    document.querySelectorAll('.pm-row').forEach(function(row) {
        row.querySelector('.pm-check').addEventListener('change', function() {
            row.classList.toggle('table-success', this.checked);
            recalcularParamedicos();
        });
    });

    recalcularKm();
    recalcularParamedicos();
    recalcularInsumos();
    recalcularTotal();

    if (document.getElementById('tbody-amb'))
        pagers.amb = TablePager({ id: 'amb', tbody: 'tbody-amb', search: 'search-amb', pager: 'pager-amb', pageSize: 8 });
    if (document.getElementById('tbody-op'))
        pagers.op  = TablePager({ id: 'op',  tbody: 'tbody-op',  search: 'search-op',  pager: 'pager-op',  pageSize: 8 });
    if (document.getElementById('tabla-paramedicos'))
        pagers.pm  = TablePager({ id: 'pm',  tbody: 'tabla-paramedicos', search: 'search-pm', pager: 'pager-pm', pageSize: 8 });
    if (document.getElementById('tbody-ins'))
        pagers.ins = TablePager({ id: 'ins', tbody: 'tbody-ins', search: 'search-ins', pager: 'pager-ins', pageSize: 8 });
});
</script>

</x-layouts.app>
