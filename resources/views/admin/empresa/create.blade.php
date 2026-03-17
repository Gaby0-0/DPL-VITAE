@extends('layouts.admin')

@section('title', 'Registrar empresa')
@section('header', 'REGISTRAR EMPRESA')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('empresa.index') }}" class="hover:text-[#d90000] transition">Empresa</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Nueva</span>
    </div>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="bg-[#d90000] px-8 py-5">
            <h3 class="text-white text-xl font-semibold">Registrar datos de la empresa</h3>
            <p class="text-red-200 text-sm mt-1">Completa los campos con la información institucional.</p>
        </div>

        <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data" class="px-8 py-8 space-y-6">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-[#d90000]">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre de la empresa"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('nombre') ring-2 ring-red-400 @enderror">
                    @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slogan</label>
                    <input type="text" name="slogan" value="{{ old('slogan') }}" placeholder="Ej. Tu salud, nuestra misión"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('slogan') ring-2 ring-red-400 @enderror">
                    @error('slogan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Breve descripción de la empresa..."
                          class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('descripcion') ring-2 ring-red-400 @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Misión / Visión --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Misión</label>
                    <textarea name="mision" rows="4" placeholder="Misión de la empresa..."
                              class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('mision') ring-2 ring-red-400 @enderror">{{ old('mision') }}</textarea>
                    @error('mision')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Visión</label>
                    <textarea name="vision" rows="4" placeholder="Visión de la empresa..."
                              class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('vision') ring-2 ring-red-400 @enderror">{{ old('vision') }}</textarea>
                    @error('vision')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Valores --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valores</label>
                <textarea name="valores" rows="3" placeholder="Valores institucionales..."
                          class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('valores') ring-2 ring-red-400 @enderror">{{ old('valores') }}</textarea>
                @error('valores')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Contacto --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 555-123-4567"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('telefono') ring-2 ring-red-400 @enderror">
                    @error('telefono')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" name="correo" value="{{ old('correo') }}" placeholder="contacto@empresa.com"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('correo') ring-2 ring-red-400 @enderror">
                    @error('correo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sitio web</label>
                    <input type="text" name="sitio_web" value="{{ old('sitio_web') }}" placeholder="www.empresa.com"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('sitio_web') ring-2 ring-red-400 @enderror">
                    @error('sitio_web')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección física de la empresa"
                       class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('direccion') ring-2 ring-red-400 @enderror">
                @error('direccion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Imágenes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('logo') ring-2 ring-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">JPG, PNG. Máx. 2 MB.</p>
                    @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen principal</label>
                    <input type="file" name="imagen" accept="image/*"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('imagen') ring-2 ring-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">JPG, PNG. Máx. 4 MB.</p>
                    @error('imagen')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('empresa.index') }}"
                   class="px-6 py-3 rounded-2xl border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-[#d90000] hover:bg-red-800 text-white font-semibold px-8 py-3 rounded-2xl shadow-md transition">
                    Guardar empresa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
