<x-layouts.app :title="'Nuevo Insumo'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Insumo</h5>
            <a href="{{ route('insumos.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('insumos.store') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-8">
                        <label class="form-label">Nombre del Insumo <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="nombre_insumo"
                            class="form-control @error('nombre_insumo') is-invalid @enderror"
                            value="{{ old('nombre_insumo') }}"
                            placeholder="Ej: Oxígeno, Vendas, Guantes"
                            style="text-transform: capitalize"
                            maxlength="150"
                            autofocus
                        >
                        @error('nombre_insumo')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Costo por Unidad --}}
                    <div class="col-md-4">
                        <label class="form-label">Costo por Unidad <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input
                                type="number"
                                name="costo_unidad"
                                step="0.01"
                                min="0"
                                class="form-control @error('costo_unidad') is-invalid @enderror"
                                value="{{ old('costo_unidad') }}"
                                placeholder="0.00"
                            >
                            <span class="input-group-text">MXN</span>
                            @error('costo_unidad')
                                <div class="invalid-feedback d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="d-flex gap-2 pt-4 border-top mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Insumo
                    </button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>