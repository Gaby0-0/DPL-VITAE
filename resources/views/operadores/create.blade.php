<x-layouts.app title="Nuevo Operador">

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('operadores.index') }}" class="btn btn-icon btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold">Nuevo Operador</h4>
                <small class="text-muted">Se creará una cuenta de acceso para el operador</small>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <strong><i class="bx bx-error-circle me-1"></i>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('operadores.store') }}" method="POST" novalidate>
            @csrf

            {{-- ── Datos Personales ── --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-user me-1 text-primary"></i>Datos personales</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre') }}"
                                placeholder="Nombre(s)"
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
                                placeholder="Apellido paterno"
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
                                placeholder="Apellido materno"
                                style="text-transform: capitalize"
                            >
                            @error('ap_materno')
                                <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Acceso al Sistema ── --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-lock me-1 text-warning"></i>Acceso al sistema</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="correo@ejemplo.com"
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
                            <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
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
                </div>
            </div>

            {{-- ── Datos Laborales ── --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-id-card me-1 text-success"></i>Datos laborales</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <label class="form-label">Salario por hora <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="salario_hora"
                                class="form-control @error('salario_hora') is-invalid @enderror"
                                value="{{ old('salario_hora') }}"
                                placeholder="0.00"
                            >
                            <span class="input-group-text">MXN/hr</span>
                            @error('salario_hora')
                                <div class="invalid-feedback d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Crear Operador
                </button>
                <a href="{{ route('operadores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
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