<x-layouts.app :title="'Nuevo paramédico'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Registrar paramédico</h5>
            <a href="{{ route('paramedicos.index') }}" class="btn btn-secondary btn-sm">
                Volver
            </a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('paramedicos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre(s) </label>
                    <input 
                    type="text" 
                    name="nombre"
                    class="form-control @error('nombre') is-invalid @enderror"
                    value="{{ old('nombre') }}"
                    >
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3">

                    <div class="col-md-6">
                            <label class="form-label">Apellido paterno</label>
                            <input
                                type="text"
                                name="ap_paterno"
                                class="form-control @error('ap_paterno') is-invalid @enderror"
                                value="{{ old('ap_paterno') }}"
                            >
                    </div>

                    <div class="col-md-6">
                            <label class="form-label">Apellido materno</label>
                            <input
                                type="text"
                                name="ap_materno"
                                class="form-control @error('ap_materno') is-invalid @enderror"
                                value="{{ old('ap_materno') }}"
                            >
                    </div>
                </div>
    
                <div class = "row g-3">
                    <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Salario por hora</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="salario_hora"
                                class="form-control @error('salario_hora') is-invalid @enderror"
                                value="{{ old('salario_hora') }}"
                            >
                        </div>
                    </div>    

                    <div class = "row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                            >
                        </div>
                    </div>

                

                <div class="mt-4 d-flex  gap-2">
                    <button type="submit" class="btn btn-primary px-10">Guardar</button>
                    <a href="{{ route('paramedicos.index') }}" class="btn btn-secondary px-10">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>


