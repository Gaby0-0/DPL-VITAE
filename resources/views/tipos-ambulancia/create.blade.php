<x-layouts.app :title="'Nuevo Tipo de Ambulancia'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Tipo de Ambulancia</h5>
            <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('tipos-ambulancia.store') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="nombre_tipo"
                            class="form-control @error('nombre_tipo') is-invalid @enderror"
                            value="{{ old('nombre_tipo') }}"
                            placeholder="Ej: Básica, Avanzada"
                            style="text-transform: capitalize"
                            data-filter="solo-letras"
                            maxlength="100"
                            autofocus
                        >
                        @error('nombre_tipo')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Costo Base --}}
                    <div class="col-md-6">
                        <label class="form-label">Costo base del servicio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input
                                type="number"
                                name="costo_base"
                                step="0.01"
                                min="0"
                                class="form-control @error('costo_base') is-invalid @enderror"
                                value="{{ old('costo_base', 0) }}"
                                placeholder="0.00"
                                data-filter="decimal"
                            >
                            <span class="input-group-text">MXN</span>
                            @error('costo_base')
                                <div class="invalid-feedback d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Costo fijo por usar este tipo de ambulancia en un servicio.</small>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-12">
                        <label class="form-label">
                            Descripción <span class="text-muted small">(opcional)</span>
                        </label>
                        <textarea
                            name="descripcion"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            rows="3"
                            maxlength="500"
                            placeholder="Describe las características de este tipo de ambulancia..."
                        >{{ old('descripcion') }}</textarea>
                        <small class="text-muted">Máximo 500 caracteres.</small>
                        @error('descripcion')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex gap-2 pt-4 border-top mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Tipo
                    </button>
                    <a href="{{ route('tipos-ambulancia.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>