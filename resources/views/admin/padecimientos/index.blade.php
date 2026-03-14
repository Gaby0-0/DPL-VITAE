@extends('layouts.admin')

@section('title', 'Padecimientos')
@section('header', 'PADECIMIENTOS')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-2xl font-semibold">Listado de padecimientos</h3>
            <!-- aqui va el boton de crear padecimiento jsjs-->
           class="bg-[#d90000] text-white px-5 py-3 rounded-xl hover:bg-red-800 transition">
            Registrar padecimiento
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
                    <th class="px-6 py-4">Nombre del padecimiento</th>
                    <th class="px-6 py-4">Nivel de riesgo</th>
                    <th class="px-6 py-4">Costo extra</th>
                    <th class="px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($padecimientos as $padecimiento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $padecimiento->id_padecimiento }}</td>
                        <td class="px-6 py-4">{{ $padecimiento->nombre_padecimiento }}</td>
                        <td class="px-6 py-4">{{ $padecimiento->nivel_riesgo ?? 'Sin especificar' }}</td>
                        <td class="px-6 py-4">
                            {{ $padecimiento->costo_extra !== null ? '$' . number_format($padecimiento->costo_extra, 2) : 'Sin costo extra' }}
                        </td>
                        <td class="px-6 py-4 flex gap-3">
                            <a href="{{ route('padecimientos.edit', $padecimiento->id_padecimiento) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg">
                                Editar
                            </a>

                            <form action="{{ route('padecimientos.destroy', $padecimiento->id_padecimiento) }}" method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar este padecimiento?')">
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay padecimientos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection