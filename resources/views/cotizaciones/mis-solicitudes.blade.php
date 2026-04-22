<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Solicitudes — {{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; background: #f5f5f9; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        .status-badge { font-size: .78rem; }
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
            <span class="text-muted small">{{ auth()->user()->nombre }} {{ auth()->user()->ap_paterno }}</span>
            <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva solicitud
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-log-out me-1"></i> Salir
                </button>
            </form>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container" style="max-width:860px">

        <h2 class="fw-bold mb-1">Mis solicitudes de cotización</h2>
        <p class="text-muted mb-4">Aquí puedes ver el estado de todas tus solicitudes y responder a las propuestas.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cotizaciones->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <i class="bx bx-file-blank text-muted" style="font-size:4rem;"></i>
                <h5 class="mt-3 fw-semibold">No tienes solicitudes aún</h5>
                <p class="text-muted">Envía tu primera solicitud y te haremos llegar una propuesta.</p>
                <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary mx-auto" style="max-width:220px">
                    <i class="bx bx-plus me-1"></i> Solicitar cotización
                </a>
            </div>
        @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Guía</th>
                            <th>Servicio</th>
                            <th>Fecha solicitada</th>
                            <th>Estado</th>
                            <th>Decisión</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cotizaciones as $cot)
                        @php
                            $colorEstado = match($cot->estado) {
                                'Pendiente'   => 'warning',
                                'En revisión' => 'info',
                                'Aceptada'    => 'success',
                                'Cancelada'   => 'danger',
                                default       => 'secondary',
                            };
                            $colorDecision = match($cot->decision_cliente) {
                                'confirmada' => 'success',
                                'declinada'  => 'danger',
                                default      => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td><span class="fw-semibold text-primary">{{ $cot->numero_guia }}</span></td>
                            <td>{{ $cot->tipo_servicio }}</td>
                            <td>{{ $cot->fecha_requerida ? \Carbon\Carbon::parse($cot->fecha_requerida)->format('d/m/Y') : '—' }}</td>
                            <td><span class="badge bg-{{ $colorEstado }} status-badge">{{ $cot->estado }}</span></td>
                            <td>
                                @if($cot->decision_cliente)
                                    <span class="badge bg-{{ $colorDecision }} status-badge">
                                        {{ $cot->decision_cliente === 'confirmada' ? 'Confirmada' : 'Declinada' }}
                                    </span>
                                @elseif($cot->estado === 'Aceptada')
                                    <span class="badge bg-warning text-dark status-badge">Pendiente tu respuesta</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    @if(!empty($cot->datos_paciente['padecimientos']))
                                        <ul class="mb-0 ps-3">
                                            @foreach($cot->datos_paciente['padecimientos'] as $p)
                                                <li>{{ $p }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Sin registro</span>
                                    @endif

                                    @if(!empty($cot->datos_paciente['costo_extra_padecimientos']))
                                        <div class="mt-1">
                                            <strong>+$ {{ number_format($cot->datos_paciente['costo_extra_padecimientos'], 2) }}</strong>
                                        </div>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('cotizaciones.mi-estado', $cot) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show me-1"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($cotizaciones->hasPages())
            <div class="card-footer bg-transparent d-flex justify-content-center py-3">
                {{ $cotizaciones->links() }}
            </div>
            @endif
        </div>
        @endif

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
