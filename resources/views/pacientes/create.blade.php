<x-layouts.app :title="'Nuevo Paciente'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Paciente</h5>
            <a href="{{ route('pacientes.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('pacientes.store') }}" method="POST" novalidate>
                @csrf

                {{-- ── Datos Personales ── --}}
                <h6 class="text-muted text-uppercase small fw-semibold mb-3">
                    <i class="bx bx-user me-1"></i> Datos Personales
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}"
                            placeholder="Ej: Juan"
                            style="text-transform: capitalize"
                            autofocus
                        >
                        @error('nombre')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="ap_paterno"
                            class="form-control @error('ap_paterno') is-invalid @enderror"
                            value="{{ old('ap_paterno') }}"
                            placeholder="Ej: García"
                            style="text-transform: capitalize"
                        >
                        @error('ap_paterno')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Apellido Materno <span class="text-muted small">(opcional)</span>
                        </label>
                        <input
                            type="text"
                            name="ap_materno"
                            class="form-control @error('ap_materno') is-invalid @enderror"
                            value="{{ old('ap_materno') }}"
                            placeholder="Ej: López"
                            style="text-transform: capitalize"
                        >
                        @error('ap_materno')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sexo</label>
                        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('sexo')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input
                            type="date"
                            name="fecha_nacimiento"
                            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                            value="{{ old('fecha_nacimiento') }}"
                            max="{{ date('Y-m-d') }}"
                        >
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Peso <span class="text-muted small">(kg)</span></label>
                        <input
                            type="number"
                            step="0.01"
                            min="0.5"
                            max="500"
                            name="peso"
                            class="form-control @error('peso') is-invalid @enderror"
                            value="{{ old('peso') }}"
                            placeholder="Ej: 70.5"
                        >
                        @error('peso')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Oxígeno <span class="text-muted small">(%)</span></label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="oxigeno"
                            class="form-control @error('oxigeno') is-invalid @enderror"
                            value="{{ old('oxigeno') }}"
                            placeholder="Ej: 98"
                        >
                        @error('oxigeno')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ── Servicio y Dirección ── --}}
                <h6 class="text-muted text-uppercase small fw-semibold mb-3">
                    <i class="bx bx-link me-1"></i> Servicio y Dirección
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Servicio <span class="text-danger">*</span></label>
                        <select name="id_servicio" class="form-select @error('id_servicio') is-invalid @enderror">
                            <option value="">-- Seleccionar servicio --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id_servicio }}" {{ old('id_servicio') == $servicio->id_servicio ? 'selected' : '' }}>
                                    #{{ $servicio->id_servicio }} — {{ $servicio->tipo ?? $servicio->estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_servicio')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dirección <span class="text-muted small">(opcional)</span></label>
                        <select name="id_direccion" class="form-select @error('id_direccion') is-invalid @enderror">
                            <option value="">-- Seleccionar dirección --</option>
                            @foreach($direcciones as $direccion)
                                <option value="{{ $direccion->id_direccion }}" {{ old('id_direccion') == $direccion->id_direccion ? 'selected' : '' }}>
                                    {{ $direccion->nombre_calle }} {{ $direccion->n_exterior }}
                                    @if($direccion->colonia) — {{ $direccion->colonia->nombre_colonia }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('id_direccion')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ── Acciones ── --}}
                <div class="d-flex gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Paciente
                    </button>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>