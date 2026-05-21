@section('title', 'Mi Panel · Operador')
<x-layouts.app title="Mi Panel">

@php
    $nombreCompleto = trim($user->nombre . ' ' . $user->ap_paterno . ' ' . ($user->ap_materno ?? ''));
    $hoy            = \Carbon\Carbon::now();
    $serviciosHoy   = $servicios->filter(fn($s) => \Carbon\Carbon::parse($s->fecha_hora)->isToday());
    $totalMes       = $esteMes->count();
    $reservasProx   = $reservas->filter(fn($r) => \Carbon\Carbon::parse($r->fecha_requerida)->isFuture())
                               ->sortBy('fecha_requerida');
@endphp

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .rol-chip      { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .65rem; border-radius:20px; font-size:.75rem; font-weight:600; }
    .perfil-initials {
        width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,#696cff,#5f63f2);
        color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1rem; flex-shrink:0;
    }
    .perfil-initials.lg { width:50px; height:50px; font-size:1.15rem; }
    .perfil-card-compact { cursor:pointer; transition:box-shadow .2s, transform .15s; }
    .perfil-card-compact:hover { box-shadow:0 6px 24px rgba(105,108,255,.18) !important; transform:translateY(-2px); }

    .servicio-card { transition:box-shadow .15s; }
    .servicio-card:hover { box-shadow:0 4px 18px rgba(0,0,0,.10) !important; }

    .estado-Activo     { background:#e8e8ff; color:#696cff; }
    .estado-Finalizado { background:#f0f0f0; color:#8592a3; }
    .estado-Cancelado  { background:#ffe0dc; color:#ff3e1d; }
    .estado-default    { background:#fff4de; color:#ffab00; }

    .fc .fc-toolbar-title { font-size:1.05rem; font-weight:700; }
    .fc .fc-button-primary { background:#696cff; border-color:#696cff; }
    .fc .fc-button-primary:hover,
    .fc .fc-button-primary:not(:disabled):active { background:#5f63f2; border-color:#5f63f2; }
    .fc-event { cursor:pointer; border-radius:6px !important; font-size:.78rem; }
    .fc-daygrid-event { padding:2px 5px !important; }

    .ruta-item { display:flex; align-items:center; gap:.75rem; padding:.55rem .75rem; border-radius:10px; transition:background .15s; }
    .ruta-item:hover { background:#f5f5ff; }
    .ruta-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

    .reserva-card { border-left:3px solid #ff9f43; }
    .amb-chip { display:inline-flex; align-items:center; gap:.4rem; background:#f0f0ff; border-radius:8px; padding:.3rem .65rem; font-size:.78rem; }
    .toggle-pass { cursor:pointer; }
</style>
@endsection

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4">
    <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Hoy</span>
                    <h3 class="card-title mb-0">{{ $serviciosHoy->count() }}</h3>
                    <small class="text-muted">{{ $serviciosHoy->count() === 1 ? 'ruta' : 'rutas' }}</small>
                </div>
                <span class="avatar-initial rounded bg-label-primary" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-map-pin"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Este mes</span>
                    <h3 class="card-title mb-0">{{ $totalMes }}</h3>
                    <small class="text-muted">rutas</small>
                </div>
                <span class="avatar-initial rounded bg-label-info" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-calendar"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Próximas</span>
                    <h3 class="card-title mb-0">{{ $proximos->count() }}</h3>
                    <small class="text-muted">pendientes</small>
                </div>
                <span class="avatar-initial rounded bg-label-warning" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-time-five"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between gap-2">
                <div>
                    <span class="fw-medium d-block mb-1 small">Completadas</span>
                    <h3 class="card-title mb-0">{{ $completados }}</h3>
                    <small class="text-muted">finalizadas</small>
                </div>
                <span class="avatar-initial rounded bg-label-success" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-check-circle"></i>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Rutas de hoy --}}
@if($serviciosHoy->isNotEmpty())
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="avatar-initial rounded bg-label-primary" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:.85rem;">
            <i class="bx bx-map-pin"></i>
        </span>
        <h6 class="mb-0 fw-bold text-uppercase" style="font-size:.78rem; letter-spacing:.08em; color:#696cff;">
            Rutas de hoy — {{ $hoy->translatedFormat('l d \d\e F') }}
        </h6>
    </div>
    <div class="row g-3">
        @foreach($serviciosHoy as $s)
        @php
            $badgeColor = match($s->estado) {
                'Activo'     => 'primary',
                'Finalizado' => 'secondary',
                'Cancelado'  => 'danger',
                default      => 'warning',
            };
            $borderColor = match($s->estado) {
                'Activo'     => '#696cff',
                'Finalizado' => '#8592a3',
                'Cancelado'  => '#ff3e1d',
                default      => '#ffab00',
            };
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="card servicio-card h-100" style="border-left:3px solid {{ $borderColor }};">
                <div class="card-body p-3">

                    {{-- Header: hora + tipo + estado --}}
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="badge bg-dark me-1" style="font-size:.68rem;">
                                <i class="bx bx-time me-1"></i>{{ \Carbon\Carbon::parse($s->fecha_hora)->format('H:i') }}
                                @if($s->hora_salida) – {{ \Carbon\Carbon::parse($s->hora_salida)->format('H:i') }}@endif
                            </span>
                            <span class="fw-semibold small">{{ $s->tipo ?? 'Servicio' }}</span>
                            @if($s->evento)<span class="badge bg-label-info ms-1" style="font-size:.62rem;">Evento</span>@endif
                        </div>
                        <span class="badge bg-label-{{ $badgeColor }} ms-2 flex-shrink-0">{{ $s->estado }}</span>
                    </div>

                    {{-- Ambulancia --}}
                    @if($s->ambulancia)
                    <div class="d-flex align-items-center gap-2 rounded px-2 py-2 mb-2" style="background:#f0f0ff;">
                        <i class="bx bx-ambulance text-primary" style="font-size:1.3rem;"></i>
                        <div>
                            <div class="fw-bold small" style="color:#696cff;">{{ $s->ambulancia->placa }}</div>
                            @if($s->ambulancia->tipo)
                            <div class="text-muted" style="font-size:.7rem;">{{ $s->ambulancia->tipo->nombre_tipo }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Cliente --}}
                    @if($s->cliente?->usuario)
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bx bx-user text-muted mt-1" style="font-size:.95rem; flex-shrink:0;"></i>
                        <div>
                            <div class="fw-semibold small">
                                {{ $s->cliente->usuario->nombre }} {{ $s->cliente->usuario->ap_paterno }}
                            </div>
                            @if($s->cliente->usuario->telefono)
                            <div class="text-muted" style="font-size:.7rem;">
                                <i class="bx bx-phone me-1"></i>{{ $s->cliente->usuario->telefono }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Paramédicos asignados --}}
                    @if($s->paramedicos->isNotEmpty())
                    <div class="d-flex align-items-center gap-1 small text-muted mb-1">
                        <i class="bx bx-plus-medical" style="font-size:.85rem; flex-shrink:0;"></i>
                        <span>{{ $s->paramedicos->map(fn($p) => $p->usuario?->nombre . ' ' . $p->usuario?->ap_paterno)->filter()->implode(' · ') }}</span>
                    </div>
                    @endif

                    {{-- Evento info --}}
                    @if($s->evento)
                    <div class="d-flex align-items-center gap-2 small text-muted mt-1">
                        <i class="bx bx-calendar-event" style="font-size:.85rem;"></i>
                        <span>{{ $s->evento->duracion ?? '—' }} hr · {{ $s->evento->personas ?? '—' }} personas</span>
                    </div>
                    @endif

                    {{-- Observaciones --}}
                    @if($s->observaciones)
                    <div class="text-muted small mt-2 pt-2 border-top">
                        <i class="bx bx-notepad me-1"></i>{{ $s->observaciones }}
                    </div>
                    @endif

                    {{-- Direcciones (cotización vinculada) --}}
                    @if($s->cotizacion && ($s->cotizacion->origen || $s->cotizacion->destino))
                    <div class="d-flex align-items-start gap-2 mt-2 pt-2 border-top">
                        <div class="d-flex flex-column align-items-center gap-0 pt-1" style="flex-shrink:0;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:block;"></span>
                            <span style="width:1.5px;height:18px;background:#ccc;display:block;margin:2px 0;"></span>
                            <span style="width:8px;height:8px;border-radius:2px;background:#dc2626;display:block;"></span>
                        </div>
                        <div style="font-size:.78rem;">
                            <div class="text-muted mb-1">{{ $s->cotizacion->origen ?? '—' }}</div>
                            <div class="fw-semibold">{{ $s->cotizacion->destino ?? '—' }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- Mini mapa (solo si hay coordenadas) --}}
                    @if($s->estado === 'Activo' && $s->cotizacion && $s->cotizacion->lat_origen && $s->cotizacion->lat_destino)
                    <div id="map-srv-{{ $s->id_servicio }}"
                         data-lat-origen="{{ $s->cotizacion->lat_origen }}"
                         data-lng-origen="{{ $s->cotizacion->lng_origen }}"
                         data-lat-destino="{{ $s->cotizacion->lat_destino }}"
                         data-lng-destino="{{ $s->cotizacion->lng_destino }}"
                         style="height:150px;border-radius:8px;margin-top:.6rem;"></div>
                    @endif

                    {{-- Botón finalizar (solo servicios Activos) --}}
                    @if($s->estado === 'Activo')
                    @php
                        $cot2 = $s->cotizacion;
                        $anticoOnline = ($cot2 && $cot2->mp_pago_estado === 'approved') ? (float)$cot2->anticipo : 0;
                        $finData = [
                            'id'          => $s->id_servicio,
                            'tipo'        => $s->tipo ?? 'Servicio',
                            'forma_pago'  => $s->forma_pago,
                            'costo_total' => (float)$s->costo_total,
                            'anticipo'    => $anticoOnline,
                            'mp_aprobado' => $cot2 ? $cot2->mp_pago_estado === 'approved' : false,
                            'lat_origen'  => $cot2 ? $cot2->lat_origen : null,
                            'lng_origen'  => $cot2 ? $cot2->lng_origen : null,
                            'lat_destino' => $cot2 ? $cot2->lat_destino : null,
                            'lng_destino' => $cot2 ? $cot2->lng_destino : null,
                            'origen'      => $cot2 ? $cot2->origen : null,
                            'destino'     => $cot2 ? $cot2->destino : null,
                            'cliente'     => $s->cliente?->usuario
                                ? trim($s->cliente->usuario->nombre . ' ' . $s->cliente->usuario->ap_paterno)
                                : '—',
                            'telefono'    => $s->cliente?->usuario?->telefono ?? null,
                            'route'       => route('empleado.servicio.finalizar', $s->id_servicio),
                        ];
                    @endphp
                    <div class="mt-2 pt-2 border-top">
                        @if(session('error_pago'))
                        <div class="alert alert-danger alert-dismissible py-2 mb-2" role="alert" style="font-size:.8rem;">
                            <i class="bx bx-x-circle me-1"></i>{{ session('error_pago') }}
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        <button type="button" class="btn btn-success btn-sm w-100"
                                onclick='abrirModalFinalizar(@json($finData))'>
                            <i class="bx bx-flag-checkered me-1"></i>Finalizar servicio
                        </button>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Contenido principal --}}
<div class="row g-4">

    {{-- Columna izquierda: calendario + reservas --}}
    <div class="col-12 col-xl-8 d-flex flex-column gap-4">

        {{-- Calendario --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title m-0"><i class="bx bx-calendar me-1 text-primary"></i>Mi calendario</h5>
                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#696cff;display:inline-block;"></span>Activo</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#ffab00;display:inline-block;"></span>Programado</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#8592a3;display:inline-block;"></span>Finalizado</span>
                    <span class="d-flex align-items-center gap-1 small"><span style="width:10px;height:10px;border-radius:50%;background:#ff9f43;display:inline-block;"></span>Reserva</span>
                </div>
            </div>
            <div class="card-body">
                <div id="mi-calendario"></div>
            </div>
        </div>

        {{-- Reservas asignadas --}}
        @if($reservasProx->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">
                    <i class="bx bx-bookmark-star me-1" style="color:#ff9f43;"></i>
                    Reservas confirmadas asignadas
                    <span class="badge bg-label-warning ms-1">{{ $reservasProx->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @foreach($reservasProx as $r)
                @php
                    $fechaR = \Carbon\Carbon::parse($r->fecha_requerida);
                    $esHoy  = $fechaR->isToday();
                    $label  = $esHoy ? 'Hoy' : ($fechaR->isTomorrow() ? 'Mañana' : $fechaR->translatedFormat('d M'));
                @endphp
                <div class="px-4 py-3 reserva-card {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div class="flex-grow-1 min-width-0">
                            {{-- Guía + tipo --}}
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="fw-bold small" style="color:#ff9f43;">{{ $r->numero_guia }}</span>
                                <span class="badge bg-label-secondary" style="font-size:.65rem;">{{ $r->tipo_servicio }}</span>
                                @if($r->horas_servicio)
                                <span class="text-muted" style="font-size:.7rem;"><i class="bx bx-time me-1"></i>{{ $r->horas_servicio }} hrs</span>
                                @endif
                            </div>

                            {{-- Fecha --}}
                            <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                                <i class="bx bx-calendar-event"></i>
                                <span class="{{ $esHoy ? 'text-primary fw-semibold' : '' }}">{{ $label }}, {{ $fechaR->format('H:i') }}</span>
                            </div>

                            {{-- Origen → Destino --}}
                            @if($r->origen || $r->destino)
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="d-flex flex-column align-items-center gap-0 pt-1" style="flex-shrink:0;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#696cff;display:block;"></span>
                                    <span style="width:1.5px;height:18px;background:#ccc;display:block;margin:2px 0;"></span>
                                    <span style="width:8px;height:8px;border-radius:2px;background:#ff3e1d;display:block;"></span>
                                </div>
                                <div style="font-size:.78rem;">
                                    <div class="text-muted mb-1">{{ $r->origen ?? '—' }}</div>
                                    <div class="fw-semibold text-dark">{{ $r->destino ?? '—' }}</div>
                                </div>
                            </div>
                            @endif

                            {{-- Ambulancia asignada --}}
                            @if($r->ambulancia)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2 rounded px-2 py-1" style="background:#f0f0ff;font-size:.78rem;">
                                    <i class="bx bx-ambulance text-primary"></i>
                                    <span class="fw-semibold" style="color:#696cff;">{{ $r->ambulancia->placa }}</span>
                                    @if($r->ambulancia->tipo)
                                    <span class="text-muted">· {{ $r->ambulancia->tipo->nombre_tipo }}</span>
                                    @endif
                                </div>
                                @if($r->requiere_oxigeno)
                                <span class="badge bg-label-danger" style="font-size:.65rem;"><i class="bx bx-plus-medical me-1"></i>Oxígeno</span>
                                @endif
                            </div>
                            @endif

                            {{-- Cliente --}}
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-1 text-muted small">
                                    <i class="bx bx-user" style="font-size:.85rem;"></i>
                                    <span>{{ $r->nombre }}</span>
                                </div>
                                @if($r->telefono)
                                <a href="tel:{{ $r->telefono }}" class="d-flex align-items-center gap-1 text-muted small text-decoration-none">
                                    <i class="bx bx-phone" style="font-size:.85rem;"></i>
                                    <span>{{ $r->telefono }}</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        {{-- Fecha label --}}
                        <div class="text-end flex-shrink-0">
                            <div class="small fw-bold {{ $esHoy ? 'text-primary' : 'text-muted' }}">{{ $label }}</div>
                            <div class="text-muted" style="font-size:.7rem;">{{ $fechaR->format('H:i') }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Columna derecha --}}
    <div class="col-12 col-xl-4 d-flex flex-column gap-4">

        {{-- Tarjeta de perfil --}}
        <div class="card perfil-card-compact" data-bs-toggle="modal" data-bs-target="#modal-perfil" title="Editar mi perfil">
            <div class="card-body py-3 d-flex align-items-center gap-3">
                <div class="perfil-initials lg flex-shrink-0">
                    {{ mb_strtoupper(mb_substr($user->nombre,0,1) . mb_substr($user->ap_paterno??'',0,1)) }}
                </div>
                <div class="flex-grow-1 min-width-0">
                    <div class="fw-bold text-truncate">{{ $nombreCompleto }}</div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                        <span class="rol-chip bg-label-primary"><i class="bx bx-id-card"></i>Operador</span>
                        @if($user->telefono)
                        <span class="text-muted small"><i class="bx bx-phone me-1"></i>{{ $user->telefono }}</span>
                        @endif
                        <span class="text-muted small"><i class="bx bx-envelope me-1"></i>{{ $user->email }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-muted"><i class="bx bx-edit-alt fs-5"></i></div>
            </div>
        </div>

        {{-- Ambulancias recientes --}}
        @if($ambulancias->isNotEmpty())
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title m-0 small text-uppercase fw-bold" style="font-size:.72rem; letter-spacing:.06em;">
                    <i class="bx bx-ambulance me-1 text-primary"></i>Ambulancias asignadas
                </h6>
            </div>
            <div class="card-body py-2 px-3 d-flex flex-wrap gap-2">
                @foreach($ambulancias as $amb)
                <div class="amb-chip">
                    <i class="bx bx-ambulance text-primary"></i>
                    <div>
                        <div class="fw-bold" style="font-size:.78rem;">{{ $amb->placa }}</div>
                        @if($amb->tipo)<div class="text-muted" style="font-size:.65rem;">{{ $amb->tipo->nombre_tipo }}</div>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Próximas rutas --}}
        <div class="card flex-grow-1">
            <div class="card-header py-3">
                <h6 class="card-title m-0"><i class="bx bx-list-ul me-1 text-warning"></i>Próximas rutas</h6>
            </div>
            <div class="card-body p-2">
                @if($proximos->isEmpty())
                <div class="text-center py-5">
                    <i class="bx bx-calendar-x text-muted" style="font-size:3rem;"></i>
                    <p class="text-muted small mt-2 mb-0">Sin rutas próximas</p>
                </div>
                @else
                @foreach($proximos as $s)
                @php
                    $fechaS   = \Carbon\Carbon::parse($s->fecha_hora);
                    $dotColor = match($s->estado){ 'Activo'=>'#696cff','Finalizado'=>'#8592a3','Cancelado'=>'#ff3e1d',default=>'#ffab00' };
                    $label    = $fechaS->isToday() ? 'Hoy' : ($fechaS->isTomorrow() ? 'Mañana' : $fechaS->format('d/m'));
                @endphp
                <div class="ruta-item">
                    <span class="ruta-dot" style="background:{{ $dotColor }}"></span>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate">
                            {{ $s->tipo ?? 'Servicio' }}
                            @if($s->evento)<span class="badge bg-label-info ms-1" style="font-size:.6rem;">Evento</span>@endif
                        </div>
                        <div class="d-flex gap-2 text-muted" style="font-size:.7rem;">
                            <span><i class="bx bx-time-five"></i> {{ $fechaS->format('H:i') }}</span>
                            <span><i class="bx bx-ambulance"></i> {{ $s->ambulancia?->placa ?? '—' }}</span>
                        </div>
                    </div>
                    <span class="small fw-semibold {{ $fechaS->isToday() ? 'text-primary' : 'text-muted' }}">{{ $label }}</span>
                </div>
                @if(!$loop->last)<hr class="my-0 mx-3">@endif
                @endforeach
                @endif
            </div>
            @if($servicios->isNotEmpty())
            <div class="card-footer bg-transparent border-top text-center">
                <small class="text-muted">Total asignados: <strong>{{ $servicios->count() }}</strong></small>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ===== MODAL PERFIL ===== --}}
<div class="modal fade" id="modal-perfil" tabindex="-1" aria-labelledby="modal-perfil-label">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="perfil-initials">
                        {{ mb_strtoupper(mb_substr($user->nombre,0,1) . mb_substr($user->ap_paterno??'',0,1)) }}
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-perfil-label">Mi perfil</h5>
                        <small class="text-muted">Actualiza tus datos de contacto y acceso</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empleado.perfil.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <p class="fw-semibold small text-uppercase text-muted mb-2">Datos personales</p>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small">Nombre(s) <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre', $user->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Apellido paterno <span class="text-danger">*</span></label>
                            <input type="text" name="ap_paterno" class="form-control form-control-sm @error('ap_paterno') is-invalid @enderror" value="{{ old('ap_paterno', $user->ap_paterno) }}" required>
                            @error('ap_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Apellido materno</label>
                            <input type="text" name="ap_materno" class="form-control form-control-sm @error('ap_materno') is-invalid @enderror" value="{{ old('ap_materno', $user->ap_materno) }}">
                            @error('ap_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="fw-semibold small text-uppercase text-muted mb-2">Contacto</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small">Teléfono</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user->telefono) }}" placeholder="951 123 4567">
                                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="fw-semibold small text-uppercase text-muted mb-2">Cambiar contraseña <span class="fw-normal text-lowercase">(dejar vacío para no cambiar)</span></p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Nueva contraseña</label>
                            <div class="input-group input-group-sm input-group-merge">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres">
                                <span class="input-group-text toggle-pass" onclick="togglePass(this)"><i class="bx bx-hide"></i></span>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Confirmar contraseña</label>
                            <div class="input-group input-group-sm input-group-merge">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña">
                                <span class="input-group-text toggle-pass" onclick="togglePass(this)"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save me-1"></i> Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL FINALIZAR SERVICIO ===== --}}
<div class="modal fade" id="modal-finalizar" tabindex="-1" aria-labelledby="modal-finalizar-label">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modal-finalizar-label">
                        <i class="bx bx-flag-checkered me-1 text-success"></i>
                        Finalizar servicio <span id="fin-id" class="text-primary">#—</span>
                    </h5>
                    <small class="text-muted" id="fin-tipo">—</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="fin-form" method="POST" action="">
                @csrf

                <div class="modal-body">

                    {{-- Resumen del servicio --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <div class="text-muted small mb-1">Cliente</div>
                            <div id="fin-cliente" class="fw-semibold">—</div>
                            <div id="fin-telefono-wrap" class="d-none">
                                <a id="fin-telefono" href="#" class="text-muted small text-decoration-none">
                                    <i class="bx bx-phone me-1"></i>—
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Costo total del servicio</div>
                            <div id="fin-costo" class="fw-bold text-success" style="font-size:1.2rem;">—</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Anticipo recibido</div>
                            <div id="fin-anticipo" class="fw-semibold">—</div>
                        </div>
                    </div>

                    {{-- Mapa de la ruta --}}
                    <div id="fin-mapa-container" class="mb-3 d-none">
                        <div class="text-muted small mb-1 fw-semibold text-uppercase" style="font-size:.75rem;letter-spacing:.06em;">
                            <i class="bx bx-map me-1"></i>Ruta del servicio
                        </div>
                        <div id="fin-mapa" style="height:200px;border-radius:8px;"></div>
                        <div class="d-flex gap-3 mt-1 small text-muted flex-wrap">
                            <span><span style="color:#2563eb;font-size:.9rem;">●</span> <span id="fin-origen-txt"></span></span>
                            <span><span style="color:#dc2626;font-size:.9rem;">●</span> <span id="fin-destino-txt"></span></span>
                        </div>
                    </div>

                    {{-- Pago en efectivo --}}
                    <div id="fin-efectivo-section" class="d-none">
                        <div class="alert alert-warning mb-3 p-3">
                            <div class="fw-semibold mb-1">
                                <i class="bx bx-money me-1"></i>Cobro en efectivo al cliente
                            </div>
                            <div style="font-size:1.1rem;">
                                Saldo a cobrar: <strong id="fin-saldo" class="text-dark">—</strong>
                            </div>
                            <div class="text-muted small mt-1">
                                Cobra este monto al cliente antes de finalizar el servicio.
                            </div>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="fin-check-pago"
                                   name="pago_confirmado" value="1">
                            <label class="form-check-label fw-semibold" for="fin-check-pago">
                                Confirmo que recibí el pago en efectivo del cliente
                            </label>
                        </div>
                    </div>

                    {{-- Pago en línea --}}
                    <div id="fin-online-section" class="d-none">
                        <div id="fin-online-ok" class="alert alert-success p-3 mb-2 d-none">
                            <i class="bx bx-check-circle me-1"></i>
                            Pago en línea verificado y aprobado. Puedes finalizar el servicio.
                        </div>
                        <div id="fin-online-pending" class="alert alert-danger p-3 mb-2 d-none">
                            <i class="bx bx-x-circle me-1"></i>
                            El cliente aún no ha completado el pago en línea. No se puede finalizar el servicio hasta que se confirme el pago.
                        </div>
                    </div>

                    {{-- Sin cotización (servicio manual) --}}
                    <div id="fin-generic-section" class="d-none">
                        <div class="alert alert-info p-3 mb-2">
                            <i class="bx bx-info-circle me-1"></i>
                            Al finalizar el servicio se liberarán la ambulancia, el operador y los paramédicos asignados.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="fin-btn-submit" class="btn btn-success">
                        <i class="bx bx-check-circle me-1"></i>Finalizar servicio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL DETALLE EVENTO CALENDARIO ===== --}}
<div class="modal fade" id="modal-evento" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="modal-titulo">—</h5>
                    <span id="modal-estado-badge" class="small fw-semibold px-2 py-1 rounded mt-1 d-inline-block">—</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row small mb-0" id="detalle-servicio">
                    <dt class="col-5 text-muted">Fecha inicio</dt><dd class="col-7" id="md-inicio">—</dd>
                    <dt class="col-5 text-muted">Fecha fin</dt><dd class="col-7" id="md-fin">—</dd>
                    <dt class="col-5 text-muted">Tipo</dt><dd class="col-7" id="md-tipo">—</dd>
                    <dt class="col-5 text-muted">Ambulancia</dt><dd class="col-7" id="md-amb">—</dd>
                    <dt class="col-5 text-muted">Tipo ambulancia</dt><dd class="col-7" id="md-tipo-amb">—</dd>
                    <dt id="lbl-dur" class="col-5 text-muted d-none">Duración</dt><dd id="md-dur" class="col-7 d-none">—</dd>
                    <dt id="lbl-per" class="col-5 text-muted d-none">Personas</dt><dd id="md-per" class="col-7 d-none">—</dd>
                    <dt class="col-5 text-muted">Observaciones</dt><dd class="col-7" id="md-obs">—</dd>
                </dl>
                <dl class="row small mb-0 d-none" id="detalle-reserva">
                    <dt class="col-5 text-muted">N° Guía</dt><dd class="col-7" id="md-guia">—</dd>
                    <dt class="col-5 text-muted">Fecha</dt><dd class="col-7" id="md-res-fecha">—</dd>
                    <dt class="col-5 text-muted">Tipo servicio</dt><dd class="col-7" id="md-res-tipo">—</dd>
                    <dt class="col-5 text-muted">Horas estimadas</dt><dd class="col-7" id="md-res-horas">—</dd>
                    <dt class="col-5 text-muted">Ambulancia</dt><dd class="col-7" id="md-res-amb">—</dd>
                    <dt class="col-5 text-muted">Tipo ambulancia</dt><dd class="col-7" id="md-res-tipo-amb">—</dd>
                    <dt id="lbl-res-oxig" class="col-5 text-muted d-none">Oxígeno</dt>
                    <dd id="md-res-oxig" class="col-7 d-none"><span class="badge bg-label-danger">Requerido</span></dd>
                    <dt class="col-5 text-muted">Cliente</dt><dd class="col-7" id="md-res-cliente">—</dd>
                    <dt class="col-5 text-muted">Teléfono</dt>
                    <dd class="col-7"><a id="md-res-tel" href="#" class="text-decoration-none">—</a></dd>
                    <dt class="col-5 text-muted">Origen</dt><dd class="col-7" id="md-res-origen">—</dd>
                    <dt class="col-5 text-muted">Destino</dt><dd class="col-7" id="md-res-destino">—</dd>
                    <dt id="lbl-res-km" class="col-5 text-muted d-none">Distancia</dt>
                    <dd id="md-res-km" class="col-7 d-none">—</dd>
                    <dt class="col-5 text-muted">Costo total</dt><dd class="col-7 fw-bold text-success" id="md-res-costo">—</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Mini mapas en tarjetas de servicio activo ──────────────────────────
    document.querySelectorAll('[id^="map-srv-"]').forEach(function (el) {
        var latO = parseFloat(el.dataset.latOrigen);
        var lngO = parseFloat(el.dataset.lngOrigen);
        var latD = parseFloat(el.dataset.latDestino);
        var lngD = parseFloat(el.dataset.lngDestino);
        if (isNaN(latO) || isNaN(latD)) return;

        var m = L.map(el.id, { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(m);

        var iconO = L.divIcon({ html: '<div style="background:#2563eb;width:11px;height:11px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 4px rgba(0,0,0,.4);"></div>', iconSize:[11,11], iconAnchor:[5,5], className:'' });
        var iconD = L.divIcon({ html: '<div style="background:#dc2626;width:11px;height:11px;border-radius:2px;border:2px solid #fff;box-shadow:0 0 4px rgba(0,0,0,.4);"></div>', iconSize:[11,11], iconAnchor:[5,5], className:'' });

        var origen  = L.latLng(latO, lngO);
        var destino = L.latLng(latD, lngD);
        L.marker(origen,  { icon: iconO }).addTo(m).bindPopup('Origen');
        L.marker(destino, { icon: iconD }).addTo(m).bindPopup('Destino');
        L.polyline([origen, destino], { color: '#696cff', weight: 3, dashArray: '6,4' }).addTo(m);
        m.fitBounds([origen, destino], { padding: [15, 15] });
    });

    // ── Modal finalizar servicio ───────────────────────────────────────────
    var _finMap = null;

    window.abrirModalFinalizar = function (srv) {
        // Rellenar encabezado
        document.getElementById('fin-id').textContent    = srv.id;
        document.getElementById('fin-tipo').textContent  = srv.tipo;
        document.getElementById('fin-form').action       = srv.route;

        // Cliente
        document.getElementById('fin-cliente').textContent = srv.cliente;
        var telWrap = document.getElementById('fin-telefono-wrap');
        var telLink = document.getElementById('fin-telefono');
        if (srv.telefono) {
            telWrap.classList.remove('d-none');
            telLink.textContent = srv.telefono;
            telLink.href        = 'tel:' + srv.telefono;
        } else {
            telWrap.classList.add('d-none');
        }

        // Costos
        document.getElementById('fin-costo').textContent    = '$' + parseFloat(srv.costo_total).toFixed(2) + ' MXN';
        document.getElementById('fin-anticipo').textContent = srv.anticipo > 0 ? '$' + parseFloat(srv.anticipo).toFixed(2) + ' MXN' : 'Sin anticipo';

        // Ocultar todas las secciones de pago
        ['fin-efectivo-section', 'fin-online-section', 'fin-generic-section',
         'fin-online-ok', 'fin-online-pending'].forEach(function (id) {
            document.getElementById(id).classList.add('d-none');
        });

        var btnSubmit = document.getElementById('fin-btn-submit');
        btnSubmit.disabled = false;

        if (srv.forma_pago === 'online') {
            document.getElementById('fin-online-section').classList.remove('d-none');
            var pagoCompleto = srv.mp_aprobado && parseFloat(srv.anticipo) >= parseFloat(srv.costo_total);
            if (pagoCompleto) {
                document.getElementById('fin-online-ok').classList.remove('d-none');
            } else {
                document.getElementById('fin-online-pending').classList.remove('d-none');
                btnSubmit.disabled = true;
            }
        } else if (srv.forma_pago === 'efectivo' || !srv.forma_pago) {
            document.getElementById('fin-efectivo-section').classList.remove('d-none');
            var saldo = Math.max(0, parseFloat(srv.costo_total) - parseFloat(srv.anticipo || 0));
            document.getElementById('fin-saldo').textContent = '$' + saldo.toFixed(2) + ' MXN';
            document.getElementById('fin-check-pago').checked = false;
        } else {
            document.getElementById('fin-generic-section').classList.remove('d-none');
        }

        // Mapa en modal
        window._finSrvData = srv;

        new bootstrap.Modal(document.getElementById('modal-finalizar')).show();
    };

    // Inicializar mapa modal cuando se muestre
    document.getElementById('modal-finalizar').addEventListener('shown.bs.modal', function () {
        var srv = window._finSrvData;
        if (!srv || !srv.lat_origen || !srv.lat_destino) {
            document.getElementById('fin-mapa-container').classList.add('d-none');
            return;
        }
        document.getElementById('fin-mapa-container').classList.remove('d-none');
        document.getElementById('fin-origen-txt').textContent  = srv.origen  || 'Origen';
        document.getElementById('fin-destino-txt').textContent = srv.destino || 'Destino';

        if (!_finMap) {
            _finMap = L.map('fin-mapa');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(_finMap);
        }

        // Limpiar capas previas (excepto tile layer)
        _finMap.eachLayer(function (layer) {
            if (!(layer instanceof L.TileLayer)) _finMap.removeLayer(layer);
        });

        var iconO = L.divIcon({ html: '<div style="background:#2563eb;width:13px;height:13px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 4px rgba(0,0,0,.4);"></div>', iconSize:[13,13], iconAnchor:[6,6], className:'' });
        var iconD = L.divIcon({ html: '<div style="background:#dc2626;width:13px;height:13px;border-radius:3px;border:2px solid #fff;box-shadow:0 0 4px rgba(0,0,0,.4);"></div>', iconSize:[13,13], iconAnchor:[6,6], className:'' });

        var origen  = L.latLng(srv.lat_origen, srv.lng_origen);
        var destino = L.latLng(srv.lat_destino, srv.lng_destino);
        L.marker(origen,  { icon: iconO }).addTo(_finMap).bindPopup(srv.origen || 'Origen');
        L.marker(destino, { icon: iconD }).addTo(_finMap).bindPopup(srv.destino || 'Destino');
        L.polyline([origen, destino], { color: '#696cff', weight: 3, dashArray: '6,4' }).addTo(_finMap);
        _finMap.fitBounds([origen, destino], { padding: [25, 25] });
        _finMap.invalidateSize();
    });

    // Validación antes de enviar (efectivo requiere checkbox)
    document.getElementById('fin-form').addEventListener('submit', function (e) {
        var sec = document.getElementById('fin-efectivo-section');
        if (!sec.classList.contains('d-none')) {
            var chk = document.getElementById('fin-check-pago');
            if (!chk.checked) {
                e.preventDefault();
                chk.closest('.form-check').classList.add('was-validated');
                chk.setCustomValidity('Debes confirmar el pago.');
                chk.reportValidity();
                return false;
            }
        }
    });

    var cal = new FullCalendar.Calendar(document.getElementById('mi-calendario'), {
        locale: 'es',
        initialView: 'dayGridMonth',
        height: 'auto',
        firstDay: 1,
        nowIndicator: true,
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: { today:'Hoy', month:'Mes', week:'Semana', list:'Lista' },
        events: @json($eventosCalendario),
        noEventsContent: 'No tienes rutas registradas',
        eventClick: function (info) {
            var e = info.event, p = e.extendedProps;
            var fmt = function (iso) {
                if (!iso) return '—';
                return new Date(iso).toLocaleString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
            };
            document.getElementById('modal-titulo').textContent = e.title;
            var badge = document.getElementById('modal-estado-badge');
            var detalleServ = document.getElementById('detalle-servicio');
            var detalleRes  = document.getElementById('detalle-reserva');

            if (p.tipo_evento === 'reserva') {
                detalleServ.classList.add('d-none');
                detalleRes.classList.remove('d-none');
                badge.textContent   = 'Reserva confirmada';
                badge.className     = 'small fw-semibold px-2 py-1 rounded mt-1 d-inline-block';
                badge.style.cssText = 'background:#fff3e0;color:#e65100;';
                document.getElementById('md-guia').textContent        = p.guia;
                document.getElementById('md-res-fecha').textContent   = fmt(e.startStr);
                document.getElementById('md-res-tipo').textContent    = p.tipo_servicio;
                document.getElementById('md-res-horas').textContent   = p.horas + ' hrs';
                document.getElementById('md-res-amb').textContent     = p.ambulancia;
                document.getElementById('md-res-tipo-amb').textContent= p.tipo_amb;
                var oxigEl   = document.getElementById('md-res-oxig');
                var oxigLbl  = document.getElementById('lbl-res-oxig');
                oxigEl.classList.toggle('d-none', !p.oxigeno);
                oxigLbl.classList.toggle('d-none', !p.oxigeno);
                document.getElementById('md-res-cliente').textContent = p.cliente;
                var telEl = document.getElementById('md-res-tel');
                telEl.textContent = p.telefono || '—';
                telEl.href = p.telefono ? 'tel:' + p.telefono : '#';
                document.getElementById('md-res-origen').textContent  = p.origen;
                document.getElementById('md-res-destino').textContent = p.destino;
                var kmEl  = document.getElementById('md-res-km');
                var kmLbl = document.getElementById('lbl-res-km');
                if (p.km) { kmEl.textContent = p.km + ' km'; kmEl.classList.remove('d-none'); kmLbl.classList.remove('d-none'); }
                else       { kmEl.classList.add('d-none'); kmLbl.classList.add('d-none'); }
                document.getElementById('md-res-costo').textContent   = p.costo;
            } else {
                detalleServ.classList.remove('d-none');
                detalleRes.classList.add('d-none');
                badge.textContent   = p.estado;
                badge.style.cssText = '';
                badge.className     = 'small fw-semibold px-2 py-1 rounded mt-1 d-inline-block estado-' + (p.estado || 'default');
                document.getElementById('md-inicio').textContent    = fmt(e.startStr);
                document.getElementById('md-fin').textContent       = fmt(e.endStr) || '—';
                document.getElementById('md-tipo').textContent      = p.tipo;
                document.getElementById('md-amb').textContent       = p.ambulancia;
                document.getElementById('md-tipo-amb').textContent  = p.tipo_amb;
                document.getElementById('md-obs').textContent       = p.observaciones;
                ['lbl-dur','md-dur','lbl-per','md-per'].forEach(function(id){
                    document.getElementById(id).classList.toggle('d-none', !p.es_evento);
                });
                if (p.es_evento) {
                    document.getElementById('md-dur').textContent = p.duracion;
                    document.getElementById('md-per').textContent = p.personas;
                }
            }
            new bootstrap.Modal(document.getElementById('modal-evento')).show();
        },
    });
    cal.render();

    window.togglePass = function(btn) {
        var input = btn.closest('.input-group').querySelector('input');
        var icon  = btn.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bx-hide', input.type === 'password');
        icon.classList.toggle('bx-show', input.type === 'text');
    };

    @if($errors->any())
        new bootstrap.Modal(document.getElementById('modal-perfil')).show();
    @endif
});
</script>
@endsection

</x-layouts.app>
