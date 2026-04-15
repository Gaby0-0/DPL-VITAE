<x-layouts.app :title="'Editar Colonia'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Colonia #{{ $colonia->id_colonia }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('colonias.update', $colonia) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Nombre de la Colonia <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_colonia" class="form-control @error('nombre_colonia') is-invalid @enderror"
                            value="{{ old('nombre_colonia', $colonia->nombre_colonia) }}" required>
                        @error('nombre_colonia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Código Postal <span class="text-danger">*</span></label>
                        <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror"
                            value="{{ old('codigo_postal', $colonia->codigo_postal) }}" required>
                        @error('codigo_postal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Municipio <span class="text-danger">*</span></label>
                        <select name="id_municipio" class="form-select @error('id_municipio') is-invalid @enderror" required>
                            <option value="">— Selecciona —</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id_municipio }}" {{ old('id_municipio', $colonia->id_municipio) == $municipio->id_municipio ? 'selected' : '' }}>
                                {{ $municipio->nombre_municipio }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_municipio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('colonias.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
