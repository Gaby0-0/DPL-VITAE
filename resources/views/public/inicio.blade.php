@extends('layouts.public')

@section('content')

@php $e = $empresa; @endphp

<section class="bg-[#d90000] text-white">
    <div class="max-w-6xl mx-auto px-6 py-16 flex flex-col md:flex-row items-center gap-10">

        <div class="flex-shrink-0">
            @if($e && $e->logo)
                <img src="{{ route('empresa.logo', $e->id_empresa) }}"
                     alt="Logo" class="h-32 w-32 object-contain rounded-2xl bg-white p-2 shadow-lg">
            @else
                <div class="h-32 w-32 rounded-2xl bg-white flex items-center justify-center shadow-lg">
                    <span class="text-[#d90000] font-bold text-3xl">🚑</span>
                </div>
            @endif
        </div>

        <div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                {{ $e->nombre ?? 'Servicio Profesional de Ambulancias' }}
            </h1>
            @if($e && $e->slogan)
                <p class="text-red-200 text-xl mt-3 italic">{{ $e->slogan }}</p>
            @endif
            @if($e && $e->descripcion)
                <p class="text-red-100 mt-4 max-w-xl leading-relaxed">{{ $e->descripcion }}</p>
            @endif
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="/cotizacion"
                   class="inline-block bg-white text-[#d90000] font-bold px-8 py-3 rounded-2xl shadow hover:bg-red-50 transition">
                    Solicitar cotización
                </a>
                @if($e && $e->telefono)
                <a href="tel:{{ $e->telefono }}"
                   class="inline-block border-2 border-white text-white font-semibold px-8 py-3 rounded-2xl hover:bg-red-700 transition">
                    {{ $e->telefono }}
                </a>
                @endif
            </div>
        </div>

    </div>
</section>


@if($e && $e->imagen)
<section class="w-full max-h-80 overflow-hidden">
    <img src="{{ route('empresa.imagen', $e->id_empresa) }}"
         alt="Imagen principal" class="w-full object-cover max-h-80">
</section>
@endif

@if($e && ($e->mision || $e->vision || $e->valores))
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-gray-800 text-center mb-10">Nuestra empresa</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @if($e->mision)
            <div class="bg-[#f3f3f3] rounded-2xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-xl mb-4">
                    🎯
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Misión</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $e->mision }}</p>
            </div>
            @endif

            @if($e->vision)
            <div class="bg-[#f3f3f3] rounded-2xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-xl mb-4">
                    🔭
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Visión</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $e->vision }}</p>
            </div>
            @endif

            @if($e->valores)
            <div class="bg-[#f3f3f3] rounded-2xl p-6 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-xl mb-4">
                    ⭐
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Valores</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $e->valores }}</p>
            </div>
            @endif

        </div>
    </div>
</section>
@endif


@if($e && ($e->telefono || $e->correo || $e->sitio_web || $e->direccion))
<section class="bg-[#f3f3f3] py-16">
    <div class="max-w-4xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-gray-800 text-center mb-10">Contacto</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            @if($e->telefono)
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-lg flex-shrink-0">📞</div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Teléfono</p>
                    <p class="text-gray-800 font-semibold">{{ $e->telefono }}</p>
                </div>
            </div>
            @endif

            @if($e->correo)
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-lg flex-shrink-0">✉️</div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Correo</p>
                    <p class="text-gray-800 font-semibold">{{ $e->correo }}</p>
                </div>
            </div>
            @endif

            @if($e->sitio_web)
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-lg flex-shrink-0">🌐</div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Sitio web</p>
                    <p class="text-gray-800 font-semibold">{{ $e->sitio_web }}</p>
                </div>
            </div>
            @endif

            @if($e->direccion)
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-[#d90000] flex items-center justify-center text-white text-lg flex-shrink-0">📍</div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Dirección</p>
                    <p class="text-gray-800 font-semibold">{{ $e->direccion }}</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
@endif


<section class="bg-[#d90000] py-14 text-center">
    <h2 class="text-3xl font-bold text-white mb-3">¿Necesitas un servicio de ambulancia?</h2>
    <p class="text-red-200 mb-6">Solicita una cotización sin costo y con atención inmediata.</p>
    <a href="/cotizacion"
       class="inline-block bg-white text-[#d90000] font-bold px-10 py-4 rounded-2xl shadow-lg hover:bg-red-50 transition text-lg">
        Solicitar cotización
    </a>
</section>

@endsection
