<x-layouts.app title="Editar Operador">

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('operadores.index') }}" class="btn btn-icon btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold">Editar Operador</h4>
                <small class="text-muted">
                    {{ $operador->usuario->nombre }} {{ $operador->usuario->ap_paterno }}
                </small>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('operadores.update', $operador) }}" method="POST">
            @csrf @method('PUT')

            {{-- datos personales --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-user me-1 text-primary"></i>Datos personales</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $operador->usuario->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido paterno <span class="text-danger">*</span></label>
                            <input type="text" name="ap_paterno"
                                class="form-control @error('ap_paterno') is-invalid @enderror"
                                value="{{ old('ap_paterno', $operador->usuario->ap_paterno) }}" required>
                            @error('ap_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido materno</label>
                            <input type="text" name="ap_materno"
                                class="form-control @error('ap_materno') is-invalid @enderror"
                                value="{{ old('ap_materno', $operador->usuario->ap_materno) }}">
                            @error('ap_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- acceso --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-lock me-1 text-warning"></i>Acceso al sistema</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $operador->usuario->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Dejar vacío para no cambiar">
                                <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation"
                                    class="form-control" placeholder="Repite la nueva contraseña">
                                <span class="input-group-text cursor-pointer" onclick="togglePass(this)">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- datos laborales --}}

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Guardar cambios
                </button>
                <a href="{{ route('operadores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>

    </div>
</div>

<script>
function togglePass(btn) {
    var input = btn.closest('.input-group').querySelector('input');
    var icon  = btn.querySelector('i');
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
