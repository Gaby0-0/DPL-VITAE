@extends('layouts.admin')

@section('title', 'Editar tipo de ambulancia')
@section('header', 'EDITAR TIPO DE AMBULANCIA')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('ambulancias.index') }}" class="hover:text-[#d90000] transition">Ambulancias</a>
        <span>/</span>
        <a href="{{ route('ambulancias.index') }}#tipos" class="hover:text-[#d90000] transition">Tipos</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Editar #{{ $tipo->id_tipo_ambulancia }}</span>
    </div>

    {{-- Tarjeta del formulario --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Encabezado azul --}}
        <div class="bg-[#7aa6c2] px-8 py-5">
            <h3 class="text-white text-xl font-semibold">Editar tipo de ambulancia #{{ $tipo->id_tipo_ambulancia }}</h3>
            <p class="text-blue-100 text-sm mt-1">Modifica los datos del tipo y guarda los cambios.</p>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('tipo_ambulancia.update', $tipo->id_tipo_ambulancia) }}" method="POST" class="px-8 py-8 space-y-6">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre <span class="text-[#d90000]">*</span>
                </label>
                <input type="text" name="nombre_tipo"
                       value="{{ old('nombre_tipo', $tipo->nombre_tipo) }}"
                       placeholder="Ej. Tipo I"
                       class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('nombre_tipo') ring-2 ring-red-400 @enderror">
                @error('nombre_tipo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="4"
                          placeholder="Descripción del tipo de ambulancia..."
                          class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('descripcion') ring-2 ring-red-400 @enderror">{{ old('descripcion', $tipo->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('ambulancias.index') }}#tipos"
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
