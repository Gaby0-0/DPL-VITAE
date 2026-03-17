@extends('layouts.admin')

@section('title', 'Servicios')
@section('header', 'SERVICIOS')

@section('content')
<div class="space-y-8">

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 px-5 py-4 rounded-2xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-[#d90000] text-red-800 px-5 py-4 rounded-2xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Encabezado y botón nuevo --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Listado de servicios</h3>
            <p class="text-sm text-gray-500 mt-1">Gestiona todos los servicios registrados en el sistema.</p>
        </div>
        <a href="{{ route('servicios.create') }}"
           class="inline-flex items-center gap-2 bg-[#d90000] hover:bg-red-800 text-white font-semibold px-6 py-3 rounded-2xl shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo servicio
        </a>
    </div>

    {{-- Buscador --}}
    <div class="w-full md:w-96">
        <input
            type="text"
            id="buscador"
            placeholder="Buscar por ambulancia, estado..."
            class="w-full rounded-2xl bg-[#e9e9e9] px-5 py-3 outline-none focus:ring-2 focus:ring-[#7aa6c2] text-sm"
        >
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full text-left" id="tablaServicios">
            <thead class="bg-[#d90000] text-white">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold">#</th>
                    <th class="px-6 py-4 text-sm font-semibold">Ambulancia</th>
                    <th class="px-6 py-4 text-sm font-semibold">Cliente</th>
                    <th class="px-6 py-4 text-sm font-semibold">Fecha y hora</th>
                    <th class="px-6 py-4 text-sm font-semibold">Hora salida</th>
                    <th class="px-6 py-4 text-sm font-semibold">Costo total</th>
                    <th class="px-6 py-4 text-sm font-semibold">Estado</th>
                    <th class="px-6 py-4 text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($servicios as $servicio)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $servicio->id_servicio }}</td>

                    <td class="px-6 py-4 font-medium text-gray-800">{{ $servicio->placa }}</td>

                    <td class="px-6 py-4 text-gray-700">{{ $servicio->id_cliente }}</td>

                    <td class="px-6 py-4 text-gray-700 text-sm">
                        {{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-6 py-4 text-gray-700 text-sm">
                        {{ $servicio->hora_salida ?? '—' }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-800">
                        ${{ number_format($servicio->costo_total, 2) }}
                    </td>

                    <td class="px-6 py-4">
                        @php
                            $badges = [
                                'Programado'  => 'bg-yellow-100 text-yellow-700',
                                'En curso'    => 'bg-blue-100 text-blue-700',
                                'Finalizado'  => 'bg-green-100 text-green-700',
                                'Cancelado'   => 'bg-red-100 text-red-700',
                            ];
                            $clase = $badges[$servicio->estado] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $clase }}">
                            {{ $servicio->estado }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Ver --}}
                            <a href="{{ route('servicios.show', $servicio->id_servicio) }}"
                               title="Ver detalle"
                               class="text-[#7aa6c2] hover:text-[#6894b0] hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            {{-- Editar --}}
                            <a href="{{ route('servicios.edit', $servicio->id_servicio) }}"
                               title="Editar"
                               class="text-gray-500 hover:text-gray-800 hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('servicios.destroy', $servicio->id_servicio) }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Eliminar"
                                        class="text-[#d90000] hover:text-red-800 hover:scale-110 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 text-sm">
                        No hay servicios registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Búsqueda en tabla --}}
<script>
    document.getElementById('buscador').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaServicios tbody tr');
        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
    });
</script>
@endsection