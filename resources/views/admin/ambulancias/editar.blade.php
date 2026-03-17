@extends('layouts.admin')

@section('title', 'Editar ambulancia')
@section('header', 'EDITAR AMBULANCIA')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('ambulancias.index') }}" class="hover:text-[#d90000] transition">Ambulancias</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Editar #{{ $ambulancia->id_ambulancia }}</span>
    </div>

    {{-- Tarjeta del formulario --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Encabezado azul (diferencia visual respecto al create) --}}
        <div class="bg-[#7aa6c2] px-8 py-5">
            <h3 class="text-white text-xl font-semibold">Editar ambulancia #{{ $ambulancia->id_ambulancia }}</h3>
            <p class="text-blue-100 text-sm mt-1">Modifica los datos de la ambulancia y guarda los cambios.</p>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('ambulancias.update', $ambulancia->id_ambulancia) }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Errores generales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            


            {{-- Fila 1: Placa / Estado --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Placa <span class="text-[#d90000]">*</span></label>
                    <input type="text" name="placa"
                           value="{{ old('placa', $ambulancia->placa) }}"
                           placeholder="Ej. ABC-1234"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('placa') ring-2 ring-red-400 @enderror">
                    @error('placa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-[#d90000]">*</span></label>
                    <select name="estado"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('estado') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona el estado —</option>
                        @foreach(['Disponible', 'En servicio', 'En mantenimiento'] as $estado)
                            <option value="{{ $estado }}"
                                {{ old('estado', $ambulancia->estado) == $estado ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Fila 2: Tipo / Operador --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de ambulancia <span class="text-[#d90000]">*</span></label>
                    <select name="id_tipo_ambulancia"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('id_tipo_ambulancia') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona el tipo —</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_ambulancia }}"
                                {{ old('id_tipo_ambulancia', $ambulancia->id_tipo_ambulancia) == $tipo->id_tipo_ambulancia ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tipo_ambulancia')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operador <span class="text-[#d90000]">*</span></label>
                    <select name="id_operador"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('id_operador') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona el operador —</option>
                        @foreach($operadores as $operador)
                            <option value="{{ $operador->id_usuario }}"
                                {{ old('id_operador', $ambulancia->id_operador) == $operador->id_usuario ? 'selected' : '' }}>
                                {{ $operador->usuario->nombre ?? '' }} {{ $operador->usuario->ap_paterno ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_operador')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('ambulancias.index') }}"
                   class="px-6 py-3 rounded-2xl border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-[#7aa6c2] hover:bg-[#6894b0] text-white font-semibold px-8 py-3 rounded-2xl shadow-md transition">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
