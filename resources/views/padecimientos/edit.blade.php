<x-layouts.app :title="'Editar Padecimiento'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Padecimiento #{{ $padecimiento->id_padecimiento }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('padecimientos.update', $padecimiento) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Padecimiento <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_padecimiento" class="form-control @error('nombre_padecimiento') is-invalid @enderror" value="{{ old('nombre_padecimiento', $padecimiento->nombre_padecimiento) }}" required>
                        @error('nombre_padecimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nivel de Riesgo <span class="text-danger">*</span></label>
                        <input type="text" name="nivel_riesgo" class="form-control @error('nivel_riesgo') is-invalid @enderror" value="{{ old('nivel_riesgo', $padecimiento->nivel_riesgo) }}" required>
                        @error('nivel_riesgo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Costo Extra <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="costo_extra" class="form-control @error('costo_extra') is-invalid @enderror" value="{{ old('costo_extra', $padecimiento->costo_extra) }}" required>
                        @error('costo_extra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('padecimientos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
