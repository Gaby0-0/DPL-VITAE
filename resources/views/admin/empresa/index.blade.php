@extends('layouts.admin')

@section('title', 'Empresa')
@section('header', 'EMPRESA')

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

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Datos de la empresa</h3>
            <p class="text-sm text-gray-500 mt-1">Gestiona la información institucional que se muestra en el sitio público.</p>
        </div>
        @if($empresas->isEmpty())
        <a href="{{ route('empresa.create') }}"
           class="inline-flex items-center gap-2 bg-[#d90000] hover:bg-red-800 text-white font-semibold px-6 py-3 rounded-2xl shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Registrar empresa
        </a>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-[#d90000] text-white">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold">Logo</th>
                    <th class="px-6 py-4 text-sm font-semibold">Nombre</th>
                    <th class="px-6 py-4 text-sm font-semibold">Slogan</th>
                    <th class="px-6 py-4 text-sm font-semibold">Teléfono</th>
                    <th class="px-6 py-4 text-sm font-semibold">Correo</th>
                    <th class="px-6 py-4 text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($empresas as $e)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($e->logo)
                            <img src="{{ route('empresa.logo', $e->id_empresa) }}"
                                 alt="Logo" class="h-12 w-12 object-contain rounded-lg border border-gray-200">
                        @else
                            <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                Sin logo
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $e->nombre }}</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $e->slogan ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-sm">{{ $e->telefono ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-700 text-sm">{{ $e->correo ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Editar --}}
                            <a href="{{ route('empresa.edit', $e->id_empresa) }}"
                               title="Editar"
                               class="text-gray-500 hover:text-gray-800 hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            {{-- Eliminar --}}
                            <form action="{{ route('empresa.destroy', $e->id_empresa) }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar"
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
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                        No hay datos de empresa registrados aún.
                        <a href="{{ route('empresa.create') }}" class="text-[#d90000] hover:underline ml-1">Registrar ahora</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
