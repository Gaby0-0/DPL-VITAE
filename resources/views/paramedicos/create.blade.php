<x-layouts.app :title="'Nuevo Padecimiento'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Padecimiento</h5>
            <a href="{{ route('padecimientos.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('padecimientos.store') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Nombre del Padecimiento <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="nombre_padecimiento"
                            class="form-control @error('nombre_padecimiento') is-invalid @enderror"
                            value="{{ old('nombre_padecimiento') }}"
                            placeholder="Ej: Diabetes, Hipertensión"
                            style="text-transform: capitalize"
                            data-filter="solo-letras"
                            autofocus
                        >
                        @error('nombre_padecimiento')
                            <div class="invalid-feedback">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nivel de Riesgo --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Nivel de Riesgo <span class="text-danger">*</span>
                        </label>
                        <select
                            name="nivel_riesgo"
                            class="form-select @error('nivel_riesgo') is-invalid @enderror"
                        >
                            <option value="">-- Seleccionar --</option>
                            @foreach($nivelesRiesgo as $nivel)
                                <option value="{{ $nivel }}" {{ old('nivel_riesgo') == $nivel ? 'selected' : '' }}>
                                    {{ $nivel }}
                                </option>
                            @endforeach
                        </select>
                        @error('nivel_riesgo')
                            <div class="invalid-feedback">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Costo Extra --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Costo Extra <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="costo_extra"
                                class="form-control @error('costo_extra') is-invalid @enderror"
                                value="{{ old('costo_extra') }}"
                                placeholder="0.00"
                                data-filter="decimal"
                            >
                            <span class="input-group-text">MXN</span>
                        </div>
                        @error('costo_extra')
                            <div class="invalid-feedback d-block">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex gap-2 pt-4 border-top mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Padecimiento
                    </button>
                    <a href="{{ route('padecimientos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</x-layouts.app>