<x-layouts.app :title="'Editar Dirección'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Dirección #{{ $direccion->id_direccion }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('direcciones.update', $direccion) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Calle <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_calle" class="form-control @error('nombre_calle') is-invalid @enderror"
                            value="{{ old('nombre_calle', $direccion->nombre_calle) }}" required>
                        @error('nombre_calle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">N° Exterior <span class="text-danger">*</span></label>
                        <input type="text" name="n_exterior" class="form-control @error('n_exterior') is-invalid @enderror"
                            value="{{ old('n_exterior', $direccion->n_exterior) }}" required>
                        @error('n_exterior')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">N° Interior</label>
                        <input type="text" name="n_interior" class="form-control @error('n_interior') is-invalid @enderror"
                            value="{{ old('n_interior', $direccion->n_interior) }}">
                        @error('n_interior')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Colonia <span class="text-danger">*</span></label>
                        <select name="id_colonia" class="form-select @error('id_colonia') is-invalid @enderror" required>
                            <option value="">— Selecciona —</option>
                            @foreach($colonias as $colonia)
                            <option value="{{ $colonia->id_colonia }}" {{ old('id_colonia', $direccion->id_colonia) == $colonia->id_colonia ? 'selected' : '' }}>
                                {{ $colonia->nombre_colonia }} ({{ $colonia->municipio->nombre_municipio ?? '—' }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_colonia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('direcciones.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
