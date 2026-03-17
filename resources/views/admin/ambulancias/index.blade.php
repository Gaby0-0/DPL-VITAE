@extends('layouts.admin')

@section('title', 'Ambulancias')
@section('header', 'AMBULANCIAS')

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
            <h3 class="text-2xl font-semibold text-gray-800">Listado de ambulancias</h3>
            <p class="text-sm text-gray-500 mt-1">Gestiona todas las ambulancias registradas en el sistema.</p>
        </div>
        <a href="{{ route('ambulancias.create') }}"
           class="inline-flex items-center gap-2 bg-[#d90000] hover:bg-red-800 text-white font-semibold px-6 py-3 rounded-2xl shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva ambulancia
        </a>
    </div>

    {{-- Buscador --}}
    <div class="w-full md:w-96">
        <input
            type="text"
            id="buscadorAmbulancia"
            placeholder="Buscar por placa, estado, tipo..."
            class="w-full rounded-2xl bg-[#e9e9e9] px-5 py-3 outline-none focus:ring-2 focus:ring-[#7aa6c2] text-sm"
        >
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full text-left" id="tablaAmbulancia">
            <thead class="bg-[#d90000] text-white">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold">#</th>
                    <th class="px-6 py-4 text-sm font-semibold">Placa</th>
                    <th class="px-6 py-4 text-sm font-semibold">Tipo</th>
                    <th class="px-6 py-4 text-sm font-semibold">Operador</th>
                    <th class="px-6 py-4 text-sm font-semibold">Estado</th>
                    <th class="px-6 py-4 text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($ambulancias as $a)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $a->id_ambulancia }}</td>

                    <td class="px-6 py-4 font-medium text-gray-800">{{ $a->placa }}</td>

                    <td class="px-6 py-4 text-gray-700">{{ $a->tipo->nombre_tipo ?? '—' }}</td>

                    <td class="px-6 py-4 text-gray-700">
                        @if($a->operador && $a->operador->usuario)
                            {{ $a->operador->usuario->nombre }} {{ $a->operador->usuario->ap_paterno }}
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        @php
                            $badges = [
                                'Disponible'       => 'bg-green-100 text-green-700',
                                'En servicio'      => 'bg-blue-100 text-blue-700',
                                'En mantenimiento' => 'bg-yellow-100 text-yellow-700',
                            ];
                            $clase = $badges[$a->estado] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $clase }}">
                            {{ $a->estado }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Editar --}}
                            <a href="{{ route('ambulancias.edit', $a->id_ambulancia) }}"
                               title="Editar"
                               class="text-gray-500 hover:text-gray-800 hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('ambulancias.destroy', $a->id_ambulancia) }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta ambulancia?')">
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
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                        No hay ambulancias registradas aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


   

    
    <div id="tipos" class="pt-4">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Tipos de ambulancia</h3>
                <p class="text-sm text-gray-500 mt-1">Administra las categorías disponibles para clasificar ambulancias.</p>
            </div>
        </div>

        {{-- Buscador --}}
        <div class="w-full md:w-96 mb-6">
            <input
                type="text"
                id="buscadorTipo"
                placeholder="Buscar por nombre o descripción..."
                class="w-full rounded-2xl bg-[#e9e9e9] px-5 py-3 outline-none focus:ring-2 focus:ring-[#7aa6c2] text-sm"
            >
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Tabla --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden">
                <table class="w-full text-left" id="tablaTipos">
                    <thead class="bg-[#d90000] text-white">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold">#</th>
                            <th class="px-6 py-4 text-sm font-semibold">Nombre</th>
                            <th class="px-6 py-4 text-sm font-semibold">Descripción</th>
                            <th class="px-6 py-4 text-sm font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tipos as $tipo)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 text-sm">{{ $tipo->id_tipo_ambulancia }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $tipo->nombre_tipo }}</td>
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ $tipo->descripcion ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Editar --}}
                                    <a href="{{ route('tipo_ambulancia.edit', $tipo->id_tipo_ambulancia) }}"
                                       title="Editar"
                                       class="text-gray-500 hover:text-gray-800 hover:scale-110 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('tipo_ambulancia.destroy', $tipo->id_tipo_ambulancia) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este tipo de ambulancia?')">
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
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                No hay tipos registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Formulario --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden self-start">
                <div class="bg-[#d90000] px-6 py-4">
                    <h4 class="text-white font-semibold text-base">Nuevo tipo</h4>
                    <p class="text-red-200 text-xs mt-0.5">Completa los campos para registrar.</p>
                </div>

                <form action="{{ route('tipo_ambulancia.store') }}" method="POST" class="px-6 py-6 space-y-4">
                    @csrf

                    {{-- Errores --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-xs space-y-1">
                            @foreach($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre <span class="text-[#d90000]">*</span>
                        </label>
                        <input type="text" name="nombre_tipo" value="{{ old('nombre_tipo') }}"
                               placeholder="Ej. Tipo I"
                               class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('nombre_tipo') ring-2 ring-red-400 @enderror">
                        @error('nombre_tipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="3"
                                  placeholder="Descripción del tipo..."
                                  class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('descripcion') ring-2 ring-red-400 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-[#d90000] hover:bg-red-800 text-white font-semibold py-3 rounded-2xl shadow-md transition text-sm">
                        Guardar tipo
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

{{-- Scripts --}}
<script>
    document.getElementById('buscadorAmbulancia').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#tablaAmbulancia tbody tr').forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
    });

    document.getElementById('buscadorTipo').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#tablaTipos tbody tr').forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
    });
</script>
@endsection
