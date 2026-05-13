<x-layouts.app :title="'Editar Evento'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Evento #{{ $evento->id_evento }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('eventos.update', $evento) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Servicio <span class="text-danger">*</span></label>
                        <select name="id_servicio" class="form-select @error('id_servicio') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id_servicio }}" {{ old('id_servicio', $evento->id_servicio) == $servicio->id_servicio ? 'selected' : '' }}>
                                    #{{ $servicio->id_servicio }} - {{ $servicio->tipo ?? $servicio->estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_servicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duración <span class="text-danger">*</span></label>
                        <input 
                        type="number" 
                        step="0.01" 
                        min="0.5"
                        name="duracion" 
                        class="form-control @error('duracion') is-invalid @enderror" 
                        value="{{ old('duracion', $evento->duracion) }}" 
                        required
                        >
                        @error('duracion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Personas <span class="text-danger">*</span></label>
                        <input type="number" min="1" name="personas" class="form-control @error('personas') is-invalid @enderror" value="{{ old('personas', $evento->personas) }}" required>
                        @error('personas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('eventos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
