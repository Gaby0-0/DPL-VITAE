@extends('layouts.admin')

@section('title', 'Personal')
@section('header', 'PERSONAL')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-2xl font-semibold">Listado de personal</h3>
        <a href="{{ route('personal.create') }}"
           class="bg-[#7aa6c2] text-white px-5 py-3 rounded-xl hover:bg-[#6894b0] transition">
            Registrar personal
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
        <div class="max-h-[500px] overflow-y-auto">
            <table class="w-full text-left">
                <thead class="bg-[#d90000] text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Correo</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Salario/hora</th>
                        <th class="px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($personal as $persona)
                    <tr>
                        <td class="px-6 py-4">{{ $persona->id_usuario }}</td>
                        <td class="px-6 py-4">
                            {{ $persona->nombre }} {{ $persona->ap_paterno }} {{ $persona->ap_materno }}
                        </td>
                        <td class="px-6 py-4">{{ $persona->email }}</td>
                        <td class="px-6 py-4">{{ $persona->tipo }}</td>
                        <td class="px-6 py-4">${{ $persona->salario_hora }}</td>
                        <td class="px-6 py-4 flex gap-3">
                            <a href="{{ route('personal.edit', $persona->id_usuario) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg">Editar</a>

                            <form action="{{ route('personal.destroy', $persona->id_usuario) }}" method="POST"
                                  onsubmit="return confirm('Esta seguro de eliminar este registro?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-100 text-red-700 px-3 py-1 rounded-lg">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No hay personal registrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection