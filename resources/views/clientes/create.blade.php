<x-layouts.app :title="'Nuevo Cliente'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Cliente</h5>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('clientes.store') }}" method="POST" novalidate>
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
                            data-filter="solo-letras"
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
                            data-filter="solo-letras"
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
                            data-filter="solo-letras"
                        >
                        @error('ap_materno')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Teléfono <span class="text-muted small">(opcional)</span>
                        </label>
                        <input
                            type="text"
                            name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono') }}"
                            placeholder="Ej: 951 123 4567"
                            data-filter="telefono"
                            maxlength="15"
                        >
                        @error('telefono')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ── Acceso al Sistema ── --}}
                <h6 class="text-muted text-uppercase small fw-semibold mb-3">
                    <i class="bx bx-lock me-1"></i> Acceso al Sistema
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="correo@ejemplo.com"
                            data-filter="email"
                        >
                        @error('email')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimo 8 caracteres"
                            >
                            <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Repite la contraseña"
                            >
                            <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ── Acciones ── --}}
                <div class="d-flex gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Cliente
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

    <script>
    function togglePass(btn) {
        const input = btn.closest('.input-group').querySelector('input');
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }
    </script>

</x-layouts.app>