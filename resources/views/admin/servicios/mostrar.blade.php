@extends('layouts.admin')

@section('title', 'Detalle del servicio')
@section('header', 'DETALLE DEL SERVICIO')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('servicios.index') }}" class="hover:text-[#d90000] transition">Servicios</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Detalle #{{ $servicio->id_servicio }}</span>
    </div>

    {{-- Tarjeta principal --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Encabezado con estado --}}
        <div class="bg-[#d90000] px-8 py-5 flex items-center justify-between">
            <div>
                <h3 class="text-white text-xl font-semibold">Servicio #{{ $servicio->id_servicio }}</h3>
                <p class="text-red-200 text-sm mt-1">
                    Registrado el {{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y') }}
                </p>
            </div>

            @php
                $badges = [
                    'Programado' => 'bg-yellow-100 text-yellow-700',
                    'En curso'   => 'bg-blue-100 text-blue-700',
                    'Finalizado' => 'bg-green-100 text-green-700',
                    'Cancelado'  => 'bg-red-200 text-red-900',
                ];
                $clase = $badges[$servicio->estado] ?? 'bg-gray-100 text-gray-700';
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $clase }}">
                {{ $servicio->estado }}
            </span>
        </div>

        {{-- Datos del servicio --}}
        <div class="px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ambulancia --}}
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Ambulancia</p>
                    <p class="text-gray-800 font-semibold text-base">{{ $servicio->placa }}</p>
                </div>

                {{-- Cliente --}}
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Cliente</p>
                    <p class="text-gray-800 font-semibold text-base">#{{ $servicio->id_cliente }}</p>
                </div>

                {{-- Fecha y hora --}}
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Fecha y hora</p>
                    <p class="text-gray-800 font-semibold text-base">
                        {{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}
                    </p>
                </div>

                {{-- Hora de salida --}}
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Hora de salida</p>
                    <p class="text-gray-800 font-semibold text-base">
                        {{ $servicio->hora_salida ?? '—' }}
                    </p>
                </div>

                {{-- Costo total --}}
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4 md:col-span-2">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Costo total</p>
                    <p class="text-[#d90000] font-bold text-2xl">
                        ${{ number_format($servicio->costo_total, 2) }}
                    </p>
                </div>

                {{-- Observaciones --}}
                @if($servicio->observaciones)
                <div class="bg-[#e9e9e9] rounded-2xl px-5 py-4 md:col-span-2">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Observaciones</p>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $servicio->observaciones }}</p>
                </div>
                @endif

            </div>
        </div>

        {{-- Pie con acciones --}}
        <div class="border-t border-gray-100 px-8 py-5 flex items-center justify-between">
            <a href="{{ route('servicios.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al listado
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('servicios.edit', $servicio->id_servicio) }}"
                   class="inline-flex items-center gap-2 bg-[#7aa6c2] hover:bg-[#6894b0] text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>

                <form action="{{ route('servicios.destroy', $servicio->id_servicio) }}" method="POST"
                      onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-[#d90000] hover:bg-red-800 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection