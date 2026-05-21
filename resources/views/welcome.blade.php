<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $empresa->nombre ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .navbar-brand img { height: 45px; object-fit: contain; }
        #hero-carousel .carousel-item {
            height: 90vh; min-height: 500px; background-color: #1a1a2e;
        }
        #hero-carousel .carousel-item img {
            width: 100%; height: 100%; object-fit: cover; opacity: .45;
        }
        #hero-carousel .carousel-caption {
            bottom: 50%; transform: translateY(50%);
        }
        #hero-carousel .carousel-caption h1 { font-size: 3rem; font-weight: 700; }
        #hero-carousel .carousel-caption p  { font-size: 1.25rem; }
        .section-title {
            font-weight: 700;
            display: block;
            text-align: center;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #696cff;
            margin: 8px auto 0;
        }
        .section-title-left {
            font-weight: 700;
            display: block;
        }
        .section-title-left::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #696cff;
            margin-top: 8px;
        }
        .card-info { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08); transition: transform .2s; }
        .card-info:hover { transform: translateY(-4px); }
        footer { background: #1a1a2e; color: #b0b0c0; }
        footer a { color: #696cff; text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
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

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="#mision">Misión y Visión</a></li>
                <li class="nav-item"><a class="nav-link" href="#valores">Valores</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>

                @auth
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.mis-solicitudes') }}" class="nav-link text-muted">
                            <i class="bx bx-list-ul me-1"></i> Mis solicitudes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bx bx-calculator me-1"></i> Cotizar
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bx bx-calculator me-1"></i> Cotizar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">Iniciar Sesión</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ── Hero Carousel ── --}}
<div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active"></button>
        @if($empresa && $empresa->mision)
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1"></button>
        @endif
        @if($empresa && $empresa->vision)
            <button type="button" data-bs-target="#hero-carousel"
                data-bs-slide-to="{{ ($empresa && $empresa->mision) ? 2 : 1 }}"></button>
        @endif
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active" style="background: linear-gradient(135deg,#1a1a2e,#16213e);">
            @if($empresa && $empresa->imagen_nombre)
                <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}" alt="">
            @endif
            <div class="carousel-caption text-center">
                <h1 class="display-4 fw-bold">{{ $empresa->nombre ?? 'Bienvenido' }}</h1>
                @if($empresa && $empresa->slogan)
                    <p class="lead">{{ $empresa->slogan }}</p>
                @endif
                <div class="d-flex gap-3 justify-content-center mt-3">
                    <a href="#nosotros" class="btn btn-primary btn-lg px-4">Conoce más</a>
                    <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bx bx-calculator me-2"></i>Solicitar Cotización
                    </a>
                </div>
            </div>
        </div>

        @if($empresa && $empresa->mision)
        <div class="carousel-item" style="background: linear-gradient(135deg,#16213e,#0f3460);">
            <div class="carousel-caption text-center">
                <h2 class="fw-bold mb-3"><i class="bx bx-target-lock me-2"></i>Nuestra Misión</h2>
                <p class="lead col-md-8 mx-auto">{{ $empresa->mision }}</p>
            </div>
        </div>
        @endif

        @if($empresa && $empresa->vision)
        <div class="carousel-item" style="background: linear-gradient(135deg,#0f3460,#533483);">
            <div class="carousel-caption text-center">
                <h2 class="fw-bold mb-3"><i class="bx bx-binoculars me-2"></i>Nuestra Visión</h2>
                <p class="lead col-md-8 mx-auto">{{ $empresa->vision }}</p>
            </div>
        </div>
        @endif
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#hero-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#hero-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

{{-- ── Nosotros ── --}}
@if($empresa)
<section id="nosotros" class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title-left mt-2 mb-4">{{ $empresa->nombre }}</h2>
                @if($empresa->descripcion)
                    <p class="text-muted fs-5">{{ $empresa->descripcion }}</p>
                @endif
                @if($empresa->slogan)
                    <blockquote class="blockquote border-start border-primary border-3 ps-3 mt-4">
                        <p class="fst-italic text-primary fs-5">"{{ $empresa->slogan }}"</p>
                    </blockquote>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                @if($empresa->imagen_nombre)
                    <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}"
                         alt="{{ $empresa->nombre }}" class="img-fluid rounded-3 shadow">
                @else
                    <div class="rounded-3 p-5 d-flex align-items-center justify-content-center"
                         style="min-height:300px; background: linear-gradient(135deg,#696cff,#5f61e6);">
                        <i class="bx bx-ambulance text-white" style="font-size:8rem;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── Misión y Visión ── --}}
@if($empresa && ($empresa->mision || $empresa->vision))
<section id="mision" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Misión y Visión</h2>
        </div>
        <div class="row g-4">
            @if($empresa->mision)
            <div class="col-md-6">
                <div class="card card-info h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bx bx-target-lock text-primary fs-3"></i>
                        </div>
                        <h4 class="mb-0">Misión</h4>
                    </div>
                    <p class="text-muted mb-0">{{ $empresa->mision }}</p>
                </div>
            </div>
            @endif
            @if($empresa->vision)
            <div class="col-md-6">
                <div class="card card-info h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bx bx-binoculars text-info fs-3"></i>
                        </div>
                        <h4 class="mb-0">Visión</h4>
                    </div>
                    <p class="text-muted mb-0">{{ $empresa->vision }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── Valores ── --}}
@if($empresa && $empresa->valores)
<section id="valores" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Valores</h2>
        </div>
        @php
            $valoresList = array_filter(
                array_map('trim', preg_split('/[\n,]+/', $empresa->valores))
            );
        @endphp
        <div class="row g-3 justify-content-center">
            @foreach($valoresList as $valor)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-info text-center p-3">
                    <i class="bx bx-check-shield text-primary fs-2 mb-2"></i>
                    <p class="fw-semibold mb-0">{{ $valor }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Contacto ── --}}
@if($empresa && ($empresa->telefono || $empresa->correo || $empresa->sitio_web || $empresa->direccion))
<section id="contacto" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Contacto</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @if($empresa->telefono)
            @php
                $tel = preg_replace('/\D/', '', $empresa->telefono);
                $telDisplay = strlen($tel) === 10
                    ? '(' . substr($tel,0,3) . ') ' . substr($tel,3,3) . '-' . substr($tel,6,4)
                    : $empresa->telefono;
            @endphp
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-phone text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Teléfono</h6>
                    <p class="text-muted mb-0">{{ $telDisplay }}</p>
                </div>
            </div>
            @endif
            @if($empresa->correo)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-envelope text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Correo</h6>
                    <p class="text-muted mb-0">{{ $empresa->correo }}</p>
                </div>
            </div>
            @endif
            @if($empresa->sitio_web)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-globe text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Sitio Web</h6>
                    <a href="{{ $empresa->sitio_web }}" target="_blank" class="text-primary">
                        {{ $empresa->sitio_web }}
                    </a>
                </div>
            </div>
            @endif
            @if($empresa->direccion)
            <div class="col-sm-6 col-md-3">
                <div class="card card-info text-center p-4">
                    <i class="bx bx-map text-primary fs-2 mb-2"></i>
                    <h6 class="fw-semibold">Dirección</h6>
                    <p class="text-muted mb-0">{{ $empresa->direccion }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Sin empresa registrada --}}
@if(!$empresa)
<section class="py-5 text-center">
    <div class="container">
        <i class="bx bx-info-circle text-muted" style="font-size:4rem;"></i>
        <h3 class="mt-3 text-muted">Aún no hay información de la empresa.</h3>
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">Configurar desde el panel</a>
    </div>
</section>
@endif

{{-- ── Footer ── --}}
<footer class="py-4">
    <div class="container text-center">
        <p class="mb-1">
            &copy; {{ date('Y') }}
            <strong class="text-white">{{ $empresa->nombre ?? config('app.name') }}</strong>
            — Todos los derechos reservados.
        </p>
        @if($empresa && $empresa->correo)
            <small><a href="mailto:{{ $empresa->correo }}">{{ $empresa->correo }}</a></small>
        @endif
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>