<x-layouts.app :title="'Nueva Ambulancia'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nueva Ambulancia</h5>
            <a href="{{ route('ambulancias.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Volver
            </a>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                    <strong><i class="bx bx-error-circle me-1"></i>Corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('ambulancias.store') }}" method="POST" novalidate>
                @csrf

                <div class="row g-3">

                    {{-- Placa --}}
                    <div class="col-md-4">
                        <label class="form-label">Placa <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="placa"
                            class="form-control @error('placa') is-invalid @enderror"
                            value="{{ old('placa') }}"
                            placeholder="Ej: AMB-001"
                            style="text-transform: uppercase"
                            data-filter="placa"
                            maxlength="20"
                            autofocus
                        >
                        <small class="text-muted">Solo letras mayúsculas, números y guiones.</small>
                        @error('placa')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-4">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($estados as $est)
                                <option value="{{ $est }}" {{ old('estado') === $est ? 'selected' : '' }}>
                                    {{ $est }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipo de Ambulancia --}}
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Ambulancia <span class="text-danger">*</span></label>
                        <select name="id_tipo_ambulancia" class="form-select @error('id_tipo_ambulancia') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_ambulancia }}" {{ old('id_tipo_ambulancia') == $tipo->id_tipo_ambulancia ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_ambulancia')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex gap-2 pt-4 border-top mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Ambulancia
                    </button>
                    <a href="{{ route('ambulancias.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>