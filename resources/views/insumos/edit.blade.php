<x-layouts.app :title="'Editar Insumo'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Insumo #{{ $insumo->id_insumo }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('insumos.update', $insumo) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nombre del Insumo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_insumo" class="form-control @error('nombre_insumo') is-invalid @enderror"
                            value="{{ old('nombre_insumo', $insumo->nombre_insumo) }}" required>
                        @error('nombre_insumo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Costo por Unidad <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="costo_unidad" class="form-control @error('costo_unidad') is-invalid @enderror"
                            value="{{ old('costo_unidad', $insumo->costo_unidad) }}" required>
                        @error('costo_unidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
