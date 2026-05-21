@section('title', 'Configuración de Empresa')
<x-layouts.app :title="'Configuración de Empresa'">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HERO --}}
    <div class="card mb-4 border-0 overflow-hidden" style="background: #667eea; position: relative;">
        <div class="card-body py-4 px-4" style="position: relative; z-index: 1;">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                @if($empresa->logo_nombre)
                    <div style="width:80px;height:80px;border-radius:16px;overflow:hidden;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid rgba(255,255,255,0.3);">
                        <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="Logo" style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;">
                    </div>
                @else
                    <div style="width:80px;height:80px;border-radius:16px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid rgba(255,255,255,0.3);">
                        <i class="bx bx-buildings" style="font-size:2.5rem;color:rgba(255,255,255,0.85);"></i>
                    </div>
                @endif
                <div>
                    <h3 class="mb-1 fw-bold" style="color:#fff;">{{ $empresa->nombre }}</h3>
                    @if($empresa->slogan)
                        <p class="mb-1" style="color:rgba(255,255,255,0.8);font-style:italic;">"{{ $empresa->slogan }}"</p>
                    @endif
                    <span class="badge" style="background:rgba(255,255,255,0.2);color:#fff;font-weight:400;">
                        <i class="bx bx-cog me-1"></i>Configuración de empresa
                    </span>
                </div>
            </div>
        </div>
        {{-- Círculos decorativos --}}
        <div style="position:absolute;right:-50px;top:-50px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.07);z-index:0;"></div>
        <div style="position:absolute;right:80px;bottom:-70px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.05);z-index:0;"></div>
        <div style="position:absolute;left:40%;top:-30px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.04);z-index:0;"></div>
    </div>

    <form action="{{ route('empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- IDENTIDAD --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#667eea;">
                            <i class="bx bx-buildings text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Identidad</h6>
                            <small class="text-muted">Nombre y presentación pública</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $empresa->nombre) }}" required>
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Slogan</label>
                                <input type="text" name="slogan"
                                       class="form-control @error('slogan') is-invalid @enderror"
                                       value="{{ old('slogan', $empresa->slogan) }}"
                                       placeholder="Frase que representa a la empresa">
                                @error('slogan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTACTO --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#11998e;">
                            <i class="bx bx-phone text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Contacto</h6>
                            <small class="text-muted">Canales de comunicación</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                    <input type="tel" id="telefono-display"
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           inputmode="numeric" maxlength="14"
                                           placeholder="(XXX) XXX-XXXX"
                                           autocomplete="tel">
                                    <input type="hidden" name="telefono" id="telefono-raw"
                                           value="{{ preg_replace('/\D/', '', old('telefono', $empresa->telefono ?? '')) }}">
                                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="invalid-feedback" id="telefono-hint" style="display:none;">
                                        Ingresa los 10 dígitos completos.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" name="correo"
                                           class="form-control @error('correo') is-invalid @enderror"
                                           value="{{ old('correo', $empresa->correo) }}">
                                    @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Sitio web</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-globe"></i></span>
                                    <input type="text" name="sitio_web"
                                           class="form-control @error('sitio_web') is-invalid @enderror"
                                           value="{{ old('sitio_web', $empresa->sitio_web) }}">
                                    @error('sitio_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-map"></i></span>
                                    <input type="text" name="direccion"
                                           class="form-control @error('direccion') is-invalid @enderror"
                                           value="{{ old('direccion', $empresa->direccion) }}">
                                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TARIFAS --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#f7971e;">
                            <i class="bx bx-dollar text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Tarifas</h6>
                            <small class="text-muted">Costos aplicados a las cotizaciones</small>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <label class="form-label fw-medium">
                            Costo por kilómetro <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg mb-2">
                            <span class="input-group-text fw-semibold">$</span>
                            <input type="number" name="costo_km" step="0.01" min="0"
                                   class="form-control @error('costo_km') is-invalid @enderror"
                                   value="{{ old('costo_km', $empresa->costo_km ?? 25.00) }}" required>
                            <span class="input-group-text">MXN / km</span>
                            @error('costo_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            Esta tarifa base se multiplica por la distancia al generar cotizaciones.
                        </p>
                    </div>
                </div>
            </div>

            {{-- UBICACIÓN BASE --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#11998e;">
                            <i class="bx bx-map-pin text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Ubicación base</h6>
                            <small class="text-muted">Punto de partida para calcular el costo de desplazamiento en cotizaciones</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="lat_base" id="lat_base" value="{{ old('lat_base', $empresa->lat_base) }}">
                        <input type="hidden" name="lng_base" id="lng_base" value="{{ old('lng_base', $empresa->lng_base) }}">
                        <div id="map-base" style="height:300px;border-radius:10px;border:1px solid #dee2e6;overflow:hidden;" class="mb-2"></div>
                        <input type="text" id="dir_base" class="form-control mt-2" readonly
                               placeholder="Marca en el mapa para guardar la ubicación"
                               value="{{ old('lat_base', $empresa->lat_base) ? ($empresa->direccion ?? '') : '' }}">
                        <small class="text-muted"><i class="bx bx-info-circle me-1"></i>Haz clic en el mapa o arrastra el marcador para ajustar la ubicación base.</small>
                        @if($empresa->lat_base && $empresa->lng_base)
                        <div class="mt-2">
                            <span class="badge bg-label-success"><i class="bx bx-check me-1"></i>Ubicación guardada: {{ $empresa->lat_base }}, {{ $empresa->lng_base }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- FILOSOFÍA INSTITUCIONAL --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#ee0979;">
                            <i class="bx bx-book-heart text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Filosofía institucional</h6>
                            <small class="text-muted">Descripción, misión, visión y valores</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Descripción general</label>
                                <textarea name="descripcion" rows="3"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Breve descripción de la empresa...">{{ old('descripcion', $empresa->descripcion) }}</textarea>
                                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="bx bx-target-lock me-1 text-primary"></i>Misión
                                </label>
                                <textarea name="mision" rows="5"
                                          class="form-control @error('mision') is-invalid @enderror"
                                          placeholder="¿Qué hace la empresa?">{{ old('mision', $empresa->mision) }}</textarea>
                                @error('mision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="bx bx-glasses me-1 text-success"></i>Visión
                                </label>
                                <textarea name="vision" rows="5"
                                          class="form-control @error('vision') is-invalid @enderror"
                                          placeholder="¿Hacia dónde va la empresa?">{{ old('vision', $empresa->vision) }}</textarea>
                                @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="bx bx-diamond me-1 text-warning"></i>Valores
                                </label>
                                <textarea name="valores" rows="5"
                                          class="form-control @error('valores') is-invalid @enderror"
                                          placeholder="Principios que guían a la empresa...">{{ old('valores', $empresa->valores) }}</textarea>
                                @error('valores')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IMÁGENES --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent d-flex align-items-center gap-3 py-3 border-bottom">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#4facfe;">
                            <i class="bx bx-image text-white" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Imágenes</h6>
                            <small class="text-muted">Logo e imagen principal</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                @if($empresa->logo_nombre)
                                    <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3"
                                         style="background:#f8f9fa;border:1px dashed #dee2e6;">
                                        <img src="{{ asset('storage/' . $empresa->logo_nombre) }}"
                                             alt="Logo actual" style="height:56px;object-fit:contain;">
                                        <div>
                                            <div class="fw-medium small">Logo actual</div>
                                            <div class="text-muted small">Sube uno nuevo para reemplazarlo</div>
                                        </div>
                                    </div>
                                @endif
                                <label class="form-label fw-medium">Logo</label>
                                <input type="file" name="logo"
                                       class="form-control @error('logo') is-invalid @enderror"
                                       accept="image/*">
                                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                @if($empresa->imagen_nombre)
                                    <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3"
                                         style="background:#f8f9fa;border:1px dashed #dee2e6;">
                                        <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}"
                                             alt="Imagen actual"
                                             style="height:56px;object-fit:cover;border-radius:8px;">
                                        <div>
                                            <div class="fw-medium small">Imagen principal actual</div>
                                            <div class="text-muted small">Sube una nueva para reemplazarla</div>
                                        </div>
                                    </div>
                                @endif
                                <label class="form-label fw-medium">Imagen principal</label>
                                <input type="file" name="imagen"
                                       class="form-control @error('imagen') is-invalid @enderror"
                                       accept="image/*">
                                @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ACCIÓN --}}
        <div class="d-flex justify-content-end mt-4 pb-2">
            <button type="submit" class="btn btn-primary btn-lg px-5 d-flex align-items-center gap-2">
                <i class="bx bx-save"></i> Guardar cambios
            </button>
        </div>

    </form>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        var latSaved = {{ $empresa->lat_base ?? 17.0669 }};
        var lngSaved = {{ $empresa->lng_base ?? -96.7203 }};
        var zoom     = {{ $empresa->lat_base ? 15 : 13 }};

        var map    = L.map('map-base').setView([latSaved, lngSaved], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([latSaved, lngSaved], { draggable: true }).addTo(map);

        function setBase(lat, lng) {
            document.getElementById('lat_base').value = lat.toFixed(7);
            document.getElementById('lng_base').value = lng.toFixed(7);
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng,
                  { headers: { 'Accept-Language': 'es' } })
            .then(r => r.json())
            .then(d => { if (d && d.display_name) document.getElementById('dir_base').value = d.display_name; })
            .catch(() => {});
        }

        marker.on('dragend', function(e) {
            var p = e.target.getLatLng();
            setBase(p.lat, p.lng);
        });
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            setBase(e.latlng.lat, e.latlng.lng);
        });

        // Si ya hay ubicación guardada, hacer geocode para mostrar dirección
        @if($empresa->lat_base && $empresa->lng_base)
        fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={{ $empresa->lat_base }}&lon={{ $empresa->lng_base }}',
              { headers: { 'Accept-Language': 'es' } })
        .then(r => r.json())
        .then(d => { if (d && d.display_name) document.getElementById('dir_base').value = d.display_name; })
        .catch(() => {});
        @endif
    })();
    </script>
    <script>
    (function () {
        var display = document.getElementById('telefono-display');
        var raw     = document.getElementById('telefono-raw');
        var hint    = document.getElementById('telefono-hint');
        if (!display || !raw) return;

        function formatTel(digits) {
            digits = digits.slice(0, 10);
            if (digits.length === 0) return '';
            if (digits.length <= 3) return '(' + digits;
            if (digits.length <= 6) return '(' + digits.slice(0,3) + ') ' + digits.slice(3);
            return '(' + digits.slice(0,3) + ') ' + digits.slice(3,6) + '-' + digits.slice(6);
        }

        // Mostrar valor existente al cargar
        if (raw.value) {
            display.value = formatTel(raw.value);
        }

        display.addEventListener('keydown', function (e) {
            var nav = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
            if (nav.includes(e.key)) return;
            if (!/^[0-9]$/.test(e.key)) e.preventDefault();
        });

        display.addEventListener('input', function () {
            var digits = this.value.replace(/\D/g, '').slice(0, 10);
            raw.value  = digits;
            var cursor = this.selectionStart;
            this.value = formatTel(digits);
            // mantener cursor en posición lógica
            if (cursor <= 4) this.setSelectionRange(cursor, cursor);

            if (digits.length > 0 && digits.length < 10) {
                this.classList.add('is-invalid');
                hint.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                hint.style.display = 'none';
            }
        });

        display.addEventListener('paste', function (e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text');
            var digits = pasted.replace(/\D/g, '').slice(0, 10);
            raw.value  = digits;
            this.value = formatTel(digits);
            this.dispatchEvent(new Event('input'));
        });
    })();
    </script>
</x-layouts.app>
