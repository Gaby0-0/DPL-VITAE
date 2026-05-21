<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud {{ $cotizacion->numero_guia }} — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .timeline-step {
            display: flex; align-items: flex-start; gap: 1rem; padding-bottom: 1.5rem;
            position: relative;
        }
        .timeline-step:not(:last-child)::before {
            content: ''; position: absolute; left: 15px; top: 32px;
            width: 2px; height: calc(100% - 32px); background: #dee2e6;
        }
        .timeline-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: .9rem;
        }
        .timeline-icon.done  { background: #198754; color: #fff; }
        .timeline-icon.active { background: #696cff; color: #fff; }
        .timeline-icon.wait  { background: #dee2e6; color: #6c757d; }
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
        <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Mis solicitudes
        </a>
    </div>
</nav>

@php
    $estadoDisplay = match(true) {
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'Declinada por ti',
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'Confirmada',
        default => $cotizacion->estado,
    };

    $colorEstado = match(true) {
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'danger',
        $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'success',
        $cotizacion->estado === 'Pendiente'   => 'warning',
        $cotizacion->estado === 'En revisión' => 'info',
        $cotizacion->estado === 'Aceptada'    => 'success',
        $cotizacion->estado === 'Cancelada'   => 'danger',
        default => 'secondary',
    };

    $etapas = [
        ['label' => 'Solicitud enviada',    'estados' => ['Pendiente','En revisión','Aceptada','Cancelada']],
        ['label' => 'En revisión',          'estados' => ['En revisión','Aceptada','Cancelada']],
        ['label' => 'Propuesta recibida',   'estados' => ['Aceptada']],
    ];

    $anticipoPagado      = $cotizacion->anticipo <= 0 || $cotizacion->mp_pago_estado === 'approved';
    $expirada            = $cotizacion->confirmacion_expires_at && $cotizacion->confirmacion_expires_at->isPast();
    $puedeDecidirCliente = $cotizacion->estado === 'Aceptada'
                        && $cotizacion->decision_cliente === null
                        && $anticipoPagado
                        && !$expirada;
@endphp

<section class="py-5">
    <div class="container" style="max-width:760px">

        {{-- alertas de sesión --}}
        @foreach(['success' => 'alert-success', 'info' => 'alert-info'] as $key => $cls)
            @if(session($key))
            <div class="alert {{ $cls }} alert-dismissible mb-4">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1">{{ $cotizacion->numero_guia }}</h2>
                <span class="text-muted small">Enviada el {{ $cotizacion->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <span class="badge bg-{{ $colorEstado }} fs-6 px-3 py-2">{{ $estadoDisplay }}</span>
        </div>

        {{-- timeline --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-semibold mb-4">Progreso de tu solicitud</h6>

            <div class="timeline-step">
                <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                <div>
                    <div class="fw-semibold">Solicitud enviada</div>
                    <div class="text-muted small">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="timeline-step">
                @if(in_array($cotizacion->estado, ['En revisión','Aceptada','Cancelada']))
                    <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                @else
                    <div class="timeline-icon wait"><i class="bx bx-time"></i></div>
                @endif
                <div>
                    <div class="fw-semibold">En revisión</div>
                    <div class="text-muted small">Nuestro equipo está preparando tu propuesta</div>
                </div>
            </div>

            <div class="timeline-step">
                @if($cotizacion->estado === 'Cancelada')
                    <div class="timeline-icon" style="background:#dc3545;color:#fff;"><i class="bx bx-x"></i></div>
                @elseif($cotizacion->estado === 'Aceptada')
                    <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                @else
                    <div class="timeline-icon wait"><i class="bx bx-time"></i></div>
                @endif
                <div>
                    <div class="fw-semibold">
                        @if($cotizacion->estado === 'Cancelada') Solicitud cancelada
                        @elseif($cotizacion->estado === 'Aceptada') Propuesta lista
                        @else Propuesta pendiente
                        @endif
                    </div>
                    @if($cotizacion->estado === 'Aceptada')
                        <div class="text-muted small">Revisa los detalles y confirma o declina el servicio</div>
                    @endif
                </div>
            </div>
                  

            @if($cotizacion->estado === 'Aceptada')
            <div class="timeline-step" style="padding-bottom:0">
                @if($cotizacion->decision_cliente === 'confirmada')
                    <div class="timeline-icon done"><i class="bx bx-check"></i></div>
                @elseif($cotizacion->decision_cliente === 'declinada')
                    <div class="timeline-icon" style="background:#dc3545;color:#fff;"><i class="bx bx-x"></i></div>
                @else
                    <div class="timeline-icon active"><i class="bx bx-question-mark"></i></div>
                @endif
                <div>
                    <div class="fw-semibold">Tu decisión</div>
                    @if($cotizacion->decision_cliente === 'confirmada')
                        <div class="text-success small fw-semibold">Confirmaste el servicio</div>
                    @elseif($cotizacion->decision_cliente === 'declinada')
                        <div class="text-danger small fw-semibold">Declinaste la propuesta</div>
                    @else
                        <div class="text-warning small fw-semibold">Pendiente tu respuesta</div>
                    @endif
                    @if($cotizacion->comentario_cliente)
                        <div class="text-muted small mt-1">{{ $cotizacion->comentario_cliente }}</div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- datos de la solicitud --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-semibold mb-3">Datos de tu solicitud</h6>
            <h6 class="fw-semibold mb-3">   </h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Tipo de servicio</dt>
                <dd class="col-7">{{ $cotizacion->tipo_servicio }}</dd>

                @if($cotizacion->fecha_requerida)
                <dt class="col-5 text-muted">Fecha requerida</dt>
                <dd class="col-7">{{ \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') }}</dd>
                @endif

                @if($cotizacion->origen)
                <dt class="col-5 text-muted">Origen</dt>
                <dd class="col-7">{{ $cotizacion->origen }}</dd>
                @endif

                @if($cotizacion->destino)
                <dt class="col-5 text-muted">Destino</dt>
                <dd class="col-7">{{ $cotizacion->destino }}</dd>
                @endif

                @if($cotizacion->descripcion)
                <dt class="col-5 text-muted">Descripción</dt>
                <dd class="col-7">{{ $cotizacion->descripcion }}</dd>
                @endif
            </dl>
        </div>

        {{-- propuesta del equipo --}}
        @if($cotizacion->estado === 'Aceptada')
        <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success p-4 mb-4">
            <h6 class="fw-semibold text-success mb-3"><i class="bx bx-package me-1"></i>Propuesta de servicio</h6>

            <table class="table table-sm mb-3">
                <tbody>
                    @if($cotizacion->km_distancia)
                    <tr>
                        <td class="text-muted">Kilómetros</td>
                        <td class="text-end">{{ $cotizacion->km_distancia }} km × ${{ number_format($cotizacion->costo_km_unitario, 2) }}</td>
                        <td class="text-end fw-bold">${{ number_format($cotizacion->km_distancia * $cotizacion->costo_km_unitario, 2) }}</td>
                    </tr>
                    @endif
                    @if($cotizacion->costo_ambulancia)
                    <tr>
                        <td class="text-muted">Ambulancia</td>
                        <td class="text-end">{{ $cotizacion->ambulancia?->tipo?->nombre_tipo ?? '—' }}</td>
                        <td class="text-end fw-bold">${{ number_format($cotizacion->costo_ambulancia, 2) }}</td>
                    </tr>
                    @endif
                    @if($cotizacion->costo_paramedicos)
                    <tr>
                        <td class="text-muted">Paramédicos</td>
                        <td class="text-end">{{ count($cotizacion->paramedicos_ids ?? []) }} persona(s) × {{ $cotizacion->horas_servicio }}h</td>
                        <td class="text-end fw-bold">${{ number_format($cotizacion->costo_paramedicos, 2) }}</td>
                    </tr>
                    @endif
                    @if($cotizacion->costo_insumos)
                    <tr>
                        <td class="text-muted">Insumos</td>
                        <td class="text-end">{{ count($cotizacion->insumos_seleccionados ?? []) }} artículo(s)</td>
                        <td class="text-end fw-bold">${{ number_format($cotizacion->costo_insumos, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="table-success">
                        <td colspan="2" class="fw-bold fs-5">Total</td>
                        <td class="text-end fw-bold fs-5">${{ number_format($cotizacion->costo, 2) }} MXN</td>
                    </tr>
                </tbody>
            </table>

            @if($cotizacion->incluye)
            <div class="mb-3">
                <strong class="small">El servicio incluye:</strong>
                <div class="small text-muted mt-1" style="white-space:pre-line">{{ $cotizacion->incluye }}</div>
            </div>
            @endif

            @if($cotizacion->respuesta)
            <div class="alert alert-success py-2 mb-0 small">
                <strong>Mensaje del equipo:</strong> {{ $cotizacion->respuesta }}
            </div>
            @endif
        </div>

        {{-- tiempo restante para confirmar --}}
        @if($cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === null && $cotizacion->confirmacion_expires_at)
        @if($expirada)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 border-start border-4 border-danger">
            <h6 class="fw-semibold text-danger mb-1"><i class="bx bx-time-five me-1"></i>Tiempo de reserva expirado</h6>
            <p class="text-muted small mb-0">El plazo de 24 horas para confirmar esta propuesta ha vencido. Contáctanos si deseas reagendar el servicio.</p>
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 border-start border-4 border-warning">
            <h6 class="fw-semibold mb-1"><i class="bx bx-time-five me-1 text-warning"></i>Recursos apartados para ti</h6>
            <p class="text-muted small mb-2">La ambulancia, operador y paramédicos asignados están reservados mientras tomas tu decisión. Tiempo restante:</p>
            <div id="countdown" class="fw-bold fs-5 text-warning">—</div>
            <small class="text-muted">Vence el {{ $cotizacion->confirmacion_expires_at->format('d/m/Y \a \l\a\s H:i') }} hrs</small>
        </div>
        <script>
        (function() {
            var expires = new Date("{{ $cotizacion->confirmacion_expires_at->toIso8601String() }}");
            function tick() {
                var diff = Math.max(0, expires - Date.now());
                var h = Math.floor(diff / 3600000);
                var m = Math.floor((diff % 3600000) / 60000);
                var s = Math.floor((diff % 60000) / 1000);
                document.getElementById('countdown').textContent =
                    h + 'h ' + String(m).padStart(2,'0') + 'm ' + String(s).padStart(2,'0') + 's';
                if (diff > 0) setTimeout(tick, 1000);
                else location.reload();
            }
            tick();
        })();
        </script>
        @endif
        @endif

        {{-- anticipo --}}
        @if($cotizacion->estado === 'Aceptada' && $cotizacion->anticipo > 0)
        @php $pagoAprobado = $cotizacion->mp_pago_estado === 'approved'; @endphp
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 {{ $pagoAprobado ? 'border-start border-4 border-success' : 'border-start border-4 border-warning' }}">
            <h6 class="fw-semibold mb-1">
                <i class="bx bx-credit-card me-1"></i>Anticipo requerido
            </h6>

            @if($pagoAprobado)
                <div class="alert alert-success py-2 mb-0 mt-2 small">
                    <i class="bx bx-check-circle me-1"></i>
                    <strong>Anticipo pagado:</strong> ${{ number_format($cotizacion->anticipo, 2) }} MXN
                    @if($cotizacion->mp_payment_id)
                        &nbsp;· ID: {{ $cotizacion->mp_payment_id }}
                    @endif
                </div>
            @elseif($cotizacion->mp_pago_estado === 'pending')
                <div class="alert alert-warning py-2 mb-3 mt-2 small">
                    <i class="bx bx-time me-1"></i>
                    Pago en proceso de acreditación. Puedes intentar de nuevo si fue un error.
                </div>
                <a href="{{ route('cotizaciones.pago.iniciar', $cotizacion) }}"
                   class="btn btn-warning w-100">
                    <i class="bx bxl-mastercard me-1"></i>Reintentar pago — ${{ number_format($cotizacion->anticipo, 2) }} MXN
                </a>
            @else
                <p class="text-muted small mt-2 mb-3">
                    Para confirmar el servicio debes pagar un anticipo de
                    <strong>${{ number_format($cotizacion->anticipo, 2) }} MXN</strong> vía MercadoPago.
                </p>
                <a href="{{ route('cotizaciones.pago.iniciar', $cotizacion) }}"
                   class="btn btn-primary w-100">
                    <i class="bx bxl-mastercard me-1"></i>Pagar anticipo — ${{ number_format($cotizacion->anticipo, 2) }} MXN
                </a>
            @endif
        </div>
        @endif

        {{-- botones confirmar o declinar --}}
        @if($puedeDecidirCliente)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-semibold mb-1">¿Deseas contratar este servicio?</h6>
            <p class="text-muted small mb-4">Una vez que confirmes, nuestro equipo se pondrá en contacto para coordinar los detalles.</p>

            @if($errors->any())
            <div class="alert alert-danger small mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('cotizaciones.confirmar', $cotizacion) }}" method="POST">
                @csrf

                @if($cotizacion->tipo_servicio === 'Traslado')
                <div class="alert alert-warning small mb-4">
                    <i class="bx bx-lock-alt me-1"></i>
                    <strong>Datos confidenciales del paciente</strong> — esta información es necesaria para coordinar el traslado y será tratada con total privacidad.
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre completo del paciente <span class="text-danger">*</span></label>
                        <input type="text" name="paciente_nombre" class="form-control @error('paciente_nombre') is-invalid @enderror"
                            value="{{ old('paciente_nombre') }}" placeholder="Nombre completo">
                        @error('paciente_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha de nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="paciente_nacimiento" class="form-control @error('paciente_nacimiento') is-invalid @enderror"
                            value="{{ old('paciente_nacimiento') }}">
                        @error('paciente_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CURP</label>
                        <input type="text" name="paciente_curp" class="form-control @error('paciente_curp') is-invalid @enderror"
                            value="{{ old('paciente_curp') }}" placeholder="CURP (opcional)" maxlength="18" style="text-transform:uppercase">
                        @error('paciente_curp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de sangre</label>
                        <select name="paciente_tipo_sangre" class="form-select @error('paciente_tipo_sangre') is-invalid @enderror">
                            <option value="">— No sé / Prefiero no decir —</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('paciente_tipo_sangre') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                        @error('paciente_tipo_sangre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Diagnóstico / motivo del traslado <span class="text-danger">*</span></label>
                        <textarea name="paciente_diagnostico" rows="3" class="form-control @error('paciente_diagnostico') is-invalid @enderror"
                            placeholder="Describe el estado de salud o motivo del traslado">{{ old('paciente_diagnostico') }}</textarea>
                        @error('paciente_diagnostico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Alergias conocidas</label>
                        <input type="text" name="paciente_alergias" class="form-control"
                            value="{{ old('paciente_alergias') }}" placeholder="Medicamentos, materiales, etc. (opcional)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Médico tratante</label>
                        <input type="text" name="paciente_medico" class="form-control"
                            value="{{ old('paciente_medico') }}" placeholder="Nombre del médico responsable (opcional)">
                    </div>
                </div>
                <hr class="my-3">
                @elseif($cotizacion->tipo_servicio === 'Evento')
                <div class="alert alert-info small mb-4">
                    <i class="bx bx-calendar-event me-1"></i>
                    <strong>Datos del evento</strong> — necesitamos estos datos para coordinar la cobertura médica durante tu evento.
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre del evento <span class="text-danger">*</span></label>
                        <input type="text" name="evento_nombre" class="form-control @error('evento_nombre') is-invalid @enderror"
                            value="{{ old('evento_nombre') }}" placeholder="Ej. Boda de Juan y María">
                        @error('evento_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de evento <span class="text-danger">*</span></label>
                        <input type="text" name="evento_tipo" class="form-control @error('evento_tipo') is-invalid @enderror"
                            value="{{ old('evento_tipo') }}" placeholder="Ej. Boda, Concierto, Conferencia, Deportivo…">
                        @error('evento_tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Número de asistentes esperados <span class="text-danger">*</span></label>
                        <input type="number" name="evento_personas" min="1"
                            class="form-control @error('evento_personas') is-invalid @enderror"
                            value="{{ old('evento_personas', $cotizacion->personas) }}">
                        @error('evento_personas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre del responsable <span class="text-danger">*</span></label>
                        <input type="text" name="evento_responsable" class="form-control @error('evento_responsable') is-invalid @enderror"
                            value="{{ old('evento_responsable', $cotizacion->nombre) }}" placeholder="Persona de contacto en el evento">
                        @error('evento_responsable')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono del responsable</label>
                        <input type="text" name="evento_tel_responsable" class="form-control"
                            value="{{ old('evento_tel_responsable', $cotizacion->telefono) }}" placeholder="Número de contacto durante el evento">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Indicaciones especiales</label>
                        <textarea name="evento_indicaciones" rows="3" class="form-control"
                            placeholder="Acceso al lugar, restricciones, puntos de atención, etc. (opcional)">{{ old('evento_indicaciones') }}</textarea>
                    </div>
                </div>
                <hr class="my-3">
                @endif

                <div class="mb-3">
                    <label class="form-label">Comentario adicional (opcional)</label>
                    <textarea name="comentario_cliente" rows="2" class="form-control"
                        placeholder="Indicaciones especiales, horarios de disponibilidad, etc.">{{ old('comentario_cliente') }}</textarea>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <button type="submit" class="btn btn-success flex-grow-1">
                        <i class="bx bx-check-circle me-1"></i> Confirmar servicio
                    </button>

                    <button type="button" class="btn btn-outline-danger flex-grow-1"
                        data-bs-toggle="modal" data-bs-target="#modal-declinar">
                        <i class="bx bx-x-circle me-1"></i> Declinar propuesta
                    </button>
                </div>
            </form>
        </div>

        {{-- modal declinar --}}
        <div class="modal fade" id="modal-declinar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-semibold text-danger">Declinar propuesta</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('cotizaciones.declinar', $cotizacion) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted small">¿Estás seguro de que deseas declinar esta propuesta?</p>
                            <label class="form-label">Motivo (opcional)</label>
                            <textarea name="comentario_cliente" rows="3" class="form-control"
                                placeholder="Cuéntanos por qué..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger btn-sm">Declinar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @endif

        {{-- cancelada --}}
        @if($cotizacion->estado === 'Cancelada' && $cotizacion->respuesta)
        <div class="alert alert-danger rounded-4">
            <strong>Motivo de cancelación:</strong> {{ $cotizacion->respuesta }}
        </div>
        @endif

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Volver a mis solicitudes
            </a>
            @if($cotizacion->estado === 'Aceptada')
            <a href="{{ route('cotizaciones.descargar', $cotizacion) }}" target="_blank" class="btn btn-outline-primary">
                <i class="bx bx-download me-1"></i> Descargar comprobante
            </a>
            @endif
        </div>

    </div>
</section>

<footer class="py-4 mt-5" style="background:#1a1a2e; color:#b0b0c0;">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
