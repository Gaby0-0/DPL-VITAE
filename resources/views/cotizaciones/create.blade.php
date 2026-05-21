<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar Cotización — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .form-card { border: none; border-radius: 16px; box-shadow: 0 4px 30px rgba(0,0,0,.10); }
        .step-badge {
            width: 32px; height: 32px; border-radius: 50%;
            background: #696cff; color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem; flex-shrink: 0;
        }
        .map-box { height: 300px; border-radius: 10px; border: 1px solid #dee2e6; overflow: hidden; }

        /* Tarjetas de tipo de ambulancia */
        .tipo-card {
            border: 2px solid #e0e0e0; border-radius: 12px; cursor: pointer;
            transition: all .2s; padding: 1rem;
        }
        .tipo-card:hover { border-color: #696cff; background: #f0f0ff; }
        .tipo-card.selected { border-color: #696cff; background: #ededff; }
        .tipo-card.premium { border: 2px solid #e0e0e0; border-radius: 12px; cursor: pointer;
            transition: all .2s; padding: 1rem; }
        .tipo-card.premium.selected { border: 2px solid #e0e0e0; border-radius: 12px; cursor: pointer;
            transition: all .2s; padding: 1rem; }
        .premium-badge {
            background: linear-gradient(135deg, #f59e0b, #ffd700);
            color: #000; font-size: .7rem; font-weight: 700;
            padding: 2px 8px; border-radius: 20px; letter-spacing:.5px;
        }
        .precio-estimado {
            background: linear-gradient(135deg, #696cff15, #696cff08);
            border: 1px solid #696cff30; border-radius: 12px;
        }

        footer { background: #1a1a2e; color: #b0b0c0; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        @if($empresa && $empresa->logo_nombre)
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="{{ $empresa->nombre }}">
            </a>
        @else
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                {{ $empresa->nombre ?? config('app.name') }}
            </a>
        @endif
        <div class="d-flex align-items-center gap-2">
            @auth
            <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-outline-primary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Mis solicitudes
            </a>
            @endauth
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Volver
            </a>
        </div>
    </div>
</nav>

@php
    $costoKm      = $empresa->costo_km ?? 25;
    $tipoMaxCosto = $tiposAmbulancia->first();
    $tipoMinCosto = $tiposAmbulancia->last();
    $tiposDataJs  = $tiposDisponibles->map(fn($t) => [
        'id'          => $t->id_tipo_ambulancia,
        'nombre'      => $t->nombre_tipo,
        'costo'       => (float) $t->costo_base,
        'descripcion' => $t->descripcion ?? '',
    ])->values();
@endphp

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="text-center mb-4">
                    <h1 class="fw-bold">Solicitar Cotización</h1>
                    <p class="text-muted">Completa el formulario y te enviaremos una propuesta a la brevedad.</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="card form-card p-4 p-md-5">
                    @php
                        $u = auth()->user();
                        $esAdmin = $u && !$u->operador && !$u->paramedico && !$u->cliente;
                        $formAction = $esAdmin ? route('admin.cotizaciones.store') : route('cotizaciones.store');
                    @endphp
                    <form action="{{ $formAction }}" method="POST" id="form-cotizacion">
                        @csrf
                        <input type="hidden" id="f_lat_origen"      name="lat_origen"              value="{{ old('lat_origen') }}">
                        <input type="hidden" id="f_lng_origen"      name="lng_origen"              value="{{ old('lng_origen') }}">
                        <input type="hidden" id="f_lat_destino"     name="lat_destino"             value="{{ old('lat_destino') }}">
                        <input type="hidden" id="f_lng_destino"     name="lng_destino"             value="{{ old('lng_destino') }}">
                        <input type="hidden" id="f_tipo_pref"       name="tipo_ambulancia_preferida" value="{{ old('tipo_ambulancia_preferida') }}">
                        <input type="hidden" id="f_id_tipo"         name="id_tipo_ambulancia"      value="{{ old('id_tipo_ambulancia') }}">

                        {{-- paso 1: contacto --}}
                        @php
                            $nombrePre = old('nombre', $user
                                ? trim($user->nombre . ' ' . $user->ap_paterno . ' ' . $user->ap_materno)
                                : '');
                            $correoPre = old('correo', $user?->email ?? '');
                        @endphp
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="step-badge">1</span>
                            <h5 class="mb-0 fw-semibold">Datos de Contacto</h5>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ $nombrePre }}" placeholder="Tu nombre" required>
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- poner validacion de solo 10 digitos --}}
                            <div class="col-md-6"> 
                                <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="text" 
                                name="telefono" 
                                class="form-control @error('telefono') is-invalid @enderror"
                                value="{{ old('telefono') }}"
                                placeholder="Ej. 9511234567"
                                pattern="[0-9]{10}"
                                maxlength="15"
                                required>
                                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                    value="{{ $correoPre }}" placeholder="correo@ejemplo.com">
                                @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- paso 2: tipo de servicio --}}
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="step-badge">2</span>
                            <h5 class="mb-0 fw-semibold">Tipo de Servicio</h5>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de servicio <span class="text-danger">*</span></label>
                                <select id="tipo_servicio" name="tipo_servicio"
                                    class="form-select @error('tipo_servicio') is-invalid @enderror" required>
                                    <option value="">— Selecciona —</option>
                                    <option value="Traslado"   {{ old('tipo_servicio') == 'Traslado'   ? 'selected' : '' }}>Traslado de paciente</option>
                                    <option value="Evento"     {{ old('tipo_servicio') == 'Evento'     ? 'selected' : '' }}>Cobertura de evento</option>
                                </select>
                                @error('tipo_servicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha requerida <span class="text-danger">*</span></label>
                                <input type="text" id="fecha" name="fecha_requerida" placeholder="Selecciona fecha"
                                    class="form-control @error('fecha_requerida') is-invalid @enderror"
                                    value="{{ old('fecha_requerida') }}" required>
                                @error('fecha_requerida')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora <span class="text-danger">*</span></label>
                                <input type="time" name="hora_requerida" id="inp-hora"
                                    class="form-control @error('hora_requerida') is-invalid @enderror"
                                    value="{{ old('hora_requerida') }}" required>
                                @error('hora_requerida')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4" id="wrap-personas">
                                <label class="form-label">Número de personas</label>
                                <input type="number" name="personas"
                                    class="form-control @error('personas') is-invalid @enderror"
                                    value="{{ old('personas') }}" min="1" placeholder="Ej. 50">
                                @error('personas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- horas: solo visible para Evento; para Traslado se calcula y se envía oculto --}}
                            <div class="col-md-6 d-none" id="wrap-horas">
                                <label class="form-label">Duración del evento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" id="inp-horas" name="horas_servicio" step="0.5" min="0.5"
                                        class="form-control @error('horas_servicio') is-invalid @enderror"
                                        value="{{ old('horas_servicio', 2) }}">
                                    <span class="input-group-text">hrs</span>
                                </div>
                                <small class="text-muted">Tiempo total de cobertura médica en el evento.</small>
                                @error('horas_servicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Descripción adicional</label>
                                <textarea name="descripcion" rows="3"
                                    class="form-control @error('descripcion') is-invalid @enderror"
                                    placeholder="Cuéntanos más detalles sobre el servicio...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- oxígeno --}}
                            <div class="col-12" id="wrap-oxigeno">
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" id="chk-oxigeno" name="requiere_oxigeno"
                                        value="1" {{ old('requiere_oxigeno') ? 'checked' : '' }}
                                        onchange="actualizarEstimado()">
                                    <label class="form-check-label fw-semibold" for="chk-oxigeno">
                                        <i class="bx bx-plus-medical me-1 text-danger"></i>¿Requiere oxígeno médico?
                                    </label>
                                </div>
                                <small class="text-muted ms-4">Si el paciente o evento necesita equipo de oxígeno, indícalo para preparar la unidad.</small>
                            </div>

                            {{-- padecimientos, solo traslado --}}
                            <div id="wrap-padecimientos" class="col-12 d-none">
                                <div class="alert alert-warning py-2 mb-2">
                                    <i class="bx bx-plus-medical me-1"></i>
                                    <strong>Información médica del paciente</strong> — Nos ayuda a preparar la ambulancia adecuada.
                                </div>
                                <label class="form-label">Padecimientos o condiciones del paciente</label>
                                <textarea name="padecimientos_paciente" rows="3"
                                    class="form-control @error('padecimientos_paciente') is-invalid @enderror"
                                    placeholder="Ej. Diabetes, hipertensión, fractura de cadera, requiere oxígeno...">{{ old('padecimientos_paciente') }}</textarea>
                                <small class="text-muted">El nombre del paciente se solicitará al confirmar el servicio.</small>
                                @error('padecimientos_paciente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- paso 3: tipo de ambulancia --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">3</span>
                            <h5 class="mb-0 fw-semibold">Tipo de Ambulancia</h5>
                        </div>

                        {{-- traslado: ambulancia avanzada forzada --}}
                        @php $tipoAvanzado = $tiposDisponibles->first(); @endphp
                        <div id="wrap-amb-fija" class="d-none mb-4">
                            @if($tipoAvanzado)
                            <div class="alert border d-flex align-items-center gap-3 py-3"
                                 style="background:#ededff;border-color:#696cff !important;">
                                <i class="bx bx-ambulance text-primary" style="font-size:2rem;flex-shrink:0;"></i>
                                <div>
                                    <div class="fw-semibold">{{ $tipoAvanzado->nombre_tipo }}
                                        <span class="badge bg-success ms-1">${{ number_format($tipoAvanzado->costo_base, 0) }}/hr</span>
                                    </div>
                                    @if($tipoAvanzado->descripcion)
                                    <div class="text-muted small">{{ $tipoAvanzado->descripcion }}</div>
                                    @endif
                                    <div class="text-muted small mt-1">
                                        <i class="bx bx-check-circle text-success me-1"></i>
                                        Los traslados incluyen nuestra ambulancia más completa de forma automática.
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info small">
                                <i class="bx bx-info-circle me-1"></i>
                                No hay unidades disponibles en este momento. Nuestro equipo asignará la unidad adecuada.
                            </div>
                            @endif
                        </div>

                        {{-- evento u otro --}}
                        <div id="wrap-amb-eleccion" class="d-none mb-4">
                            @php $idsDisponibles = $tiposDisponibles->pluck('id_tipo_ambulancia'); @endphp
                            @if($tiposAmbulancia->isNotEmpty())
                            <div class="row g-2" id="grid-tipos">
                                @foreach($tiposAmbulancia as $tipo)
                                @php $disponible = $idsDisponibles->contains($tipo->id_tipo_ambulancia); @endphp
                                <div class="col-md-6">
                                    @if($disponible)
                                    <div class="tipo-card {{ old('tipo_ambulancia_preferida') == $tipo->nombre_tipo ? 'selected' : '' }}"
                                         onclick="seleccionarTipo('{{ $tipo->nombre_tipo }}', {{ (float)$tipo->costo_base }}, this, {{ $tipo->id_tipo_ambulancia }})">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <strong class="fs-6">{{ $tipo->nombre_tipo }}</strong>
                                            <span class="badge bg-success ms-2">${{ number_format($tipo->costo_base, 0) }}/hr</span>
                                        </div>
                                        @if($tipo->descripcion)
                                        <p class="text-muted small mb-0 mt-1">{{ $tipo->descripcion }}</p>
                                        @endif
                                    </div>
                                    @else
                                    <div class="tipo-card" style="opacity:.5;cursor:not-allowed;background:#f8f9fa;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <strong class="fs-6 text-muted">{{ $tipo->nombre_tipo }}</strong>
                                            <span class="badge bg-secondary ms-2">${{ number_format($tipo->costo_base, 0) }}/hr</span>
                                        </div>
                                        @if($tipo->descripcion)
                                        <p class="text-muted small mb-0 mt-1">{{ $tipo->descripcion }}</p>
                                        @endif
                                        <p class="text-danger small mb-0 mt-1">
                                            <i class="bx bx-x-circle me-1"></i>Sin unidades disponibles
                                        </p>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-info small">
                                <i class="bx bx-info-circle me-1"></i>
                                No hay tipos de ambulancia registrados. Aun así puede enviar su solicitud y nuestro equipo la atenderá.
                            </div>
                            @endif
                        </div>

                        {{-- sin tipo seleccionado --}}
                        <div id="wrap-amb-vacio" class="alert alert-light border text-muted small">
                            <i class="bx bx-info-circle me-1"></i>Selecciona un tipo de servicio para ver las opciones de ambulancia disponibles.
                        </div>

                        <hr class="my-4">

                        {{-- paso 4: ubicación --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">4</span>
                            <h5 class="mb-0 fw-semibold">Ubicación</h5>
                        </div>

                        <div class="mb-4">
                            <label id="lbl-origen" class="form-label fw-semibold">
                                <i class="bx bx-current-location me-1 text-primary"></i>Ubicación de origen
                            </label>
                            <div id="map-origen" class="map-box mb-2"></div>
                            <small class="text-muted"><i class="bx bx-info-circle me-1"></i>Haz clic en el mapa o arrastra el marcador para ajustar.</small>
                            <input type="text" id="f_origen" name="origen" class="form-control mt-2"
                                placeholder="La dirección se detecta al marcar en el mapa" value="{{ old('origen') }}">
                        </div>

                        <div id="wrap-destino" class="mb-4 d-none">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-map-pin me-1 text-danger"></i>Destino — ¿a dónde llevamos al paciente?
                            </label>
                            <div id="map-destino" class="map-box mb-2"></div>
                            <small class="text-muted"><i class="bx bx-info-circle me-1"></i>Haz clic en el mapa o arrastra el marcador para ajustar.</small>
                            <input type="text" id="f_destino" name="destino" class="form-control mt-2"
                                placeholder="La dirección se detecta al marcar en el mapa" value="{{ old('destino') }}">
                        </div>

                        <hr class="my-4">

                        {{-- total estimado --}}
                        <div id="wrap-estimado" class="precio-estimado p-4 mb-4 d-none">
                            <div class="text-muted small fw-semibold mb-2">
                                <i class="bx bx-calculator me-1 text-primary"></i>Precio estimado del servicio
                            </div>
                            <div id="est-total" class="fw-bold mb-2" style="font-size:2rem;color:#696cff">—</div>
                            <ul id="est-incluye" class="list-unstyled text-muted small mb-2"></ul>
                            <small class="text-muted">
                                <i class="bx bx-info-circle me-1"></i>
                                El precio final puede ser ajustado por nuestro equipo antes de confirmar el servicio.
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-send me-2"></i>Enviar solicitud de cotización
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong> — Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var COSTO_KM       = {{ $costoKm }};
    var AVG_SALARIO_PM = {{ $avgSalarioParamedico }};
    var AVG_SALARIO_OP = {{ $avgSalarioOperador }};
    var BASE_LAT       = {{ $empresa->lat_base ?? 'null' }};
    var BASE_LNG       = {{ $empresa->lng_base ?? 'null' }};
    var TIPOS_DATA     = @json($tiposDataJs);
    var TIPO_PREMIUM   = TIPOS_DATA.length ? TIPOS_DATA[0] : null;

    var DEFAULT_LAT = 17.0669, DEFAULT_LNG = -96.7203, DEFAULT_ZOOM = 13;

    var fLatOrigen  = document.getElementById('f_lat_origen');
    var fLngOrigen  = document.getElementById('f_lng_origen');
    var fLatDestino = document.getElementById('f_lat_destino');
    var fLngDestino = document.getElementById('f_lng_destino');
    var fOrigen     = document.getElementById('f_origen');
    var fDestino    = document.getElementById('f_destino');
    var fTipoPref   = document.getElementById('f_tipo_pref');
    var fIdTipo     = document.getElementById('f_id_tipo');
    var lblOrigen   = document.getElementById('lbl-origen');
    var wDestino    = document.getElementById('wrap-destino');
    var wPadec      = document.getElementById('wrap-padecimientos');
    var wAmbFija    = document.getElementById('wrap-amb-fija');
    var wAmbElec    = document.getElementById('wrap-amb-eleccion');
    var wAmbVacio   = document.getElementById('wrap-amb-vacio');
    var wHoras      = document.getElementById('wrap-horas');
    var tipoSelect  = document.getElementById('tipo_servicio');
    var inpPersonas = document.querySelector('[name="personas"]');
    var inpHoras    = document.getElementById('inp-horas');

    var mapOrigen = null, mapDestino = null, destinoInit = false;
    var tipoAmbActual = null; // {id, nombre, costo, descripcion}

    // ── Geocoder ──
    function geocode(lat, lng, input) {
        fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng,
              { headers: { 'Accept-Language': 'es' } })
        .then(function(r){ return r.json(); })
        .then(function(d){ if (d && d.display_name) input.value = d.display_name; actualizarEstimado(); })
        .catch(function(){});
    }

    function buildMap(containerId, latInput, lngInput, addressInput) {
        var map = L.map(containerId).setView([DEFAULT_LAT, DEFAULT_LNG], DEFAULT_ZOOM);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([DEFAULT_LAT, DEFAULT_LNG], { draggable: true }).addTo(map);
        function update(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            geocode(lat, lng, addressInput);
        }
        marker.on('dragend', function(e){ var p = e.target.getLatLng(); update(p.lat, p.lng); });
        map.on('click', function(e){ marker.setLatLng(e.latlng); update(e.latlng.lat, e.latlng.lng); });
        return { map: map, marker: marker, update: update };
    }

    mapOrigen = buildMap('map-origen', fLatOrigen, fLngOrigen, fOrigen);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude, lng = pos.coords.longitude;
            mapOrigen.marker.setLatLng([lat, lng]);
            mapOrigen.map.setView([lat, lng], DEFAULT_ZOOM);
            mapOrigen.update(lat, lng);
        }, function(){});
    }

    // ── Haversine ──
    function haversine(lat1, lng1, lat2, lng2) {
        var R = 6371;
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLng = (lng2 - lng1) * Math.PI / 180;
        var a = Math.sin(dLat/2)*Math.sin(dLat/2) +
                Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*
                Math.sin(dLng/2)*Math.sin(dLng/2);
        return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 10) / 10;
    }

    function fmt(n) {
        return '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // ── Horas para traslado: 1h mínimo, +1h por cada 50km ──
    function calcHorasTraslado(km) {
        return Math.max(1, Math.ceil(km / 50));
    }

    // ── Paramédicos ──
    function calcParamedicos() {
        var tipo    = tipoSelect.value;
        var personas = parseInt(inpPersonas ? inpPersonas.value : 0) || 0;
        var num = 2;
        if (tipo === 'Evento' && personas > 40) {
            num += Math.ceil((personas - 40) / 20);
        }
        return num;
    }

    // ── Actualizar estimado ──
    function actualizarEstimado() {
        if (!tipoAmbActual) return;

        var wEst = document.getElementById('wrap-estimado');
        wEst.classList.remove('d-none');

        var tipo = tipoSelect.value;
        var pm   = calcParamedicos();

        // Kilómetros
        var lat1 = parseFloat(fLatOrigen.value), lng1 = parseFloat(fLngOrigen.value);
        var lat2 = parseFloat(fLatDestino.value), lng2 = parseFloat(fLngDestino.value);
        var km = 0, kmBase = 0, costoKmVal = 0;
        // base → origen
        if (BASE_LAT && BASE_LNG && lat1 && lng1) {
            kmBase = haversine(BASE_LAT, BASE_LNG, lat1, lng1);
        }
        // origen → destino (solo traslado)
        if (lat1 && lng1 && lat2 && lng2) {
            km = haversine(lat1, lng1, lat2, lng2);
        }
        costoKmVal = Math.round((km + kmBase) * COSTO_KM * 100) / 100;

        // Horas: traslado = calculado por km; evento = input del cliente
        var horas;
        if (tipo === 'Traslado') {
            horas = km > 0 ? calcHorasTraslado(km) : 1;
            inpHoras.value = horas;
        } else {
            horas = parseFloat(inpHoras.value) || 2;
        }

        var costoAmb      = Math.round(tipoAmbActual.costo * horas * 100) / 100;
        var costoPersonal = Math.round((pm * AVG_SALARIO_PM + AVG_SALARIO_OP) * horas * 100) / 100;
        var total         = costoAmb + costoPersonal + costoKmVal;

        var oxigeno = document.getElementById('chk-oxigeno') && document.getElementById('chk-oxigeno').checked;

        document.getElementById('est-total').textContent = fmt(total) + ' MXN';

        // Lista de lo que incluye
        var lista = document.getElementById('est-incluye');
        lista.innerHTML = '';
        var items = [];
        items.push('<i class="bx bx-ambulance me-1 text-primary"></i><strong>' + tipoAmbActual.nombre + '</strong>'
            + (tipoAmbActual.descripcion ? ' — ' + tipoAmbActual.descripcion : '')
            + ' × ' + horas + 'h'
            + ' <span class="text-success">(' + fmt(costoAmb) + ')</span>');
        items.push('<i class="bx bx-user-plus me-1 text-info"></i>Operador + ' + pm + ' paramédico' + (pm > 1 ? 's' : '')
            + ' × ' + horas + 'h'
            + ' <span class="text-success">(' + fmt(costoPersonal) + ')</span>');
        if (kmBase > 0) items.push('<i class="bx bx-flag me-1 text-secondary"></i>' + kmBase + ' km desde nuestra base');
        if (km > 0)     items.push('<i class="bx bx-map-alt me-1 text-warning"></i>' + km + ' km de traslado');
        if (oxigeno) items.push('<i class="bx bx-plus-medical me-1 text-danger"></i>Oxígeno médico requerido');
        items.forEach(function(html) {
            var li = document.createElement('li');
            li.className = 'mb-1';
            li.innerHTML = html;
            lista.appendChild(li);
        });
    }

    // ── Selección de tipo de ambulancia ──
    window.seleccionarTipo = function(nombre, costo, card, id) {
        document.querySelectorAll('#grid-tipos .tipo-card, #grid-amb-fija .tipo-card').forEach(function(c){
            c.classList.remove('selected');
        });
        card.classList.add('selected');
        fTipoPref.value = nombre;
        fIdTipo.value   = id;
        var found = TIPOS_DATA.find(function(t){ return t.id === id; });
        tipoAmbActual = found || { id: id, nombre: nombre, costo: costo, descripcion: '' };
        actualizarEstimado();
    };

    // ── Cambio de tipo de servicio ──
    function onTipoChange(tipo) {
        wPadec.classList.toggle('d-none', tipo !== 'Traslado');

        // Mostrar horas solo en Evento; para Traslado se calcula automáticamente
        wHoras.classList.toggle('d-none', tipo !== 'Evento');

        wAmbFija.classList.add('d-none');
        wAmbElec.classList.add('d-none');
        wAmbVacio.classList.add('d-none');

        if (tipo === 'Traslado') {
            wAmbFija.classList.remove('d-none');
            // Forzar ambulancia avanzada (primera = más cara)
            if (TIPO_PREMIUM) {
                fTipoPref.value = TIPO_PREMIUM.nombre;
                fIdTipo.value   = TIPO_PREMIUM.id;
                tipoAmbActual   = TIPO_PREMIUM;
            }
            lblOrigen.innerHTML = '<i class="bx bx-current-location me-1 text-primary"></i>Origen — ¿dónde recogemos al paciente?';
            wDestino.classList.remove('d-none');
            if (!destinoInit) {
                mapDestino = buildMap('map-destino', fLatDestino, fLngDestino, fDestino);
                destinoInit = true;
            }

        } else if (tipo === 'Evento') {
            wAmbElec.classList.remove('d-none');
            if (!fTipoPref.value) {
                var primera = document.querySelector('#grid-tipos .tipo-card');
                if (primera) primera.click();
            }
            lblOrigen.innerHTML = '<i class="bx bx-map me-1 text-primary"></i>Ubicación del evento';
            wDestino.classList.add('d-none');
            fLatDestino.value = ''; fLngDestino.value = '';
            if (fDestino) fDestino.value = '';

        } else {
            wAmbVacio.classList.remove('d-none');
            tipoAmbActual = null;
            document.getElementById('wrap-estimado').classList.add('d-none');
        }

        actualizarEstimado();
        setTimeout(function(){ mapOrigen.map.invalidateSize(); }, 150);
    }

    if (inpPersonas) inpPersonas.addEventListener('input', actualizarEstimado);
    if (inpHoras)    inpHoras.addEventListener('input', actualizarEstimado);

    tipoSelect.addEventListener('change', function(){ onTipoChange(this.value); });
    if (tipoSelect.value) {
        onTipoChange(tipoSelect.value);
    } else {
        wAmbVacio.classList.remove('d-none');
    }
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#fecha", {
    dateFormat: "Y-m-d",
    minDate: "today",
    disable: @json($fechasOcupadas)
});
</script>

</body>
</html>
