@extends('layouts.admin')

@section('title', 'Insumos')
@section('header', 'INSUMOS')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-2xl font-semibold">Listado de insumos</h3>
        <a href="{{ route('insumos.create') }}"
           class="bg-[#d90000] text-white px-5 py-3 rounded-xl hover:bg-red-800 transition">
            Registrar insumo
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-100 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-[#d90000] text-white">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nombre del insumo</th>
                    <th class="px-6 py-4">Costo por unidad</th>
                    <th class="px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($insumos as $insumo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $insumo->id_insumo }}</td>
                        <td class="px-6 py-4">{{ $insumo->nombre_insumo }}</td>
                        <td class="px-6 py-4">${{ number_format($insumo->costo_unidad, 2) }}</td>
                        <td class="px-6 py-4 flex gap-3">
                            <a href="{{ route('insumos.edit', $insumo->id_insumo) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg">
                                Editar
                            </a>

                            <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar este insumo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-lg">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No hay insumos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection