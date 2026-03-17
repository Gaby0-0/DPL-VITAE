@extends('layouts.admin')

@section('title', 'Nuevo servicio')
@section('header', 'NUEVO SERVICIO')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('servicios.index') }}" class="hover:text-[#d90000] transition">Servicios</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Nuevo</span>
    </div>

    {{-- Tarjeta del formulario --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Encabezado rojo --}}
        <div class="bg-[#d90000] px-8 py-5">
            <h3 class="text-white text-xl font-semibold">Registrar servicio</h3>
            <p class="text-red-200 text-sm mt-1">Completa los campos para crear un nuevo servicio.</p>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('servicios.store') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf

            {{-- Errores generales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Fila 1: Ambulancia / Cliente --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ambulancia <span class="text-[#d90000]">*</span></label>
                    <select name="id_ambulancia"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('id_ambulancia') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona una ambulancia —</option>
                        @foreach($ambulancias as $ambulancia)
                            <option value="{{ $ambulancia->id_ambulancia }}"
                                {{ old('id_ambulancia') == $ambulancia->id_ambulancia ? 'selected' : '' }}>
                                {{ $ambulancia->placa }} — {{ $ambulancia->tipo }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_ambulancia')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-[#d90000]">*</span></label>
                    <select name="id_cliente"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('id_cliente') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona un cliente —</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}"
                                {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                #{{ $cliente->id_cliente }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_cliente')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Fila 2: Fecha/hora / Hora salida --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora <span class="text-[#d90000]">*</span></label>
                    <input type="datetime-local" name="fecha_hora" value="{{ old('fecha_hora') }}"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('fecha_hora') ring-2 ring-red-400 @enderror">
                    @error('fecha_hora')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora de salida</label>
                    <input type="time" name="hora_salida" value="{{ old('hora_salida') }}"
                           class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('hora_salida') ring-2 ring-red-400 @enderror">
                    @error('hora_salida')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Fila 3: Costo / Estado --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Costo total <span class="text-[#d90000]">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="costo_total" value="{{ old('costo_total') }}"
                               step="0.01" min="0" placeholder="0.00"
                               class="w-full rounded-2xl bg-[#e9e9e9] pl-8 pr-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('costo_total') ring-2 ring-red-400 @enderror">
                    </div>
                    @error('costo_total')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-[#d90000]">*</span></label>
                    <select name="estado"
                            class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] @error('estado') ring-2 ring-red-400 @enderror">
                        <option value="">— Selecciona el estado —</option>
                        @foreach(['Programado', 'En curso', 'Finalizado', 'Cancelado'] as $estado)
                            <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Observaciones --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea name="observaciones" rows="4" placeholder="Notas adicionales sobre el servicio..."
                          class="w-full rounded-2xl bg-[#e9e9e9] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#7aa6c2] resize-none @error('observaciones') ring-2 ring-red-400 @enderror">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('servicios.index') }}"
                   class="px-6 py-3 rounded-2xl border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-[#d90000] hover:bg-red-800 text-white font-semibold px-8 py-3 rounded-2xl shadow-md transition">
                    Guardar servicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection