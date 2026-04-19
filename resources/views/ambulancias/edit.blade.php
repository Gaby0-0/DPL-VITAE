<x-layouts.app :title="'Editar Ambulancia'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Ambulancia: {{ $ambulancia->placa }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('ambulancias.update', $ambulancia) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Placa <span class="text-danger">*</span></label>
                        <input type="text" name="placa" class="form-control @error('placa') is-invalid @enderror" value="{{ old('placa', $ambulancia->placa) }}" required>
                        @error('placa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach(['Disponible','En servicio','En mantenimiento'] as $est)
                                <option value="{{ $est }}" {{ old('estado', $ambulancia->estado) === $est ? 'selected' : '' }}>{{ $est }}</option>
                            @endforeach
                        </select>
                        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Ambulancia <span class="text-danger">*</span></label>
                        <select name="id_tipo_ambulancia" class="form-select @error('id_tipo_ambulancia') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_ambulancia }}" {{ old('id_tipo_ambulancia', $ambulancia->id_tipo_ambulancia) == $tipo->id_tipo_ambulancia ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_ambulancia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('ambulancias.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
