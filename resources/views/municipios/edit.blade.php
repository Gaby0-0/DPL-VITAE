<x-layouts.app :title="'Editar Municipio'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Municipio #{{ $municipio->id_municipio }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('municipios.update', $municipio) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Municipio <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_municipio" class="form-control @error('nombre_municipio') is-invalid @enderror"
                            value="{{ old('nombre_municipio', $municipio->nombre_municipio) }}" required>
                        @error('nombre_municipio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('municipios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
