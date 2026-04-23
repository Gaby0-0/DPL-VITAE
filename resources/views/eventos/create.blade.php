<x-layouts.app :title="'Nuevo Evento'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nuevo Evento</h5>
            <a href="{{ route('eventos.index') }}" class="btn btn-secondary btn-sm">
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

            <form action="{{ route('eventos.store') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">

                    {{-- Servicio --}}
                    <div class="col-md-6">
                        <label class="form-label">Servicio <span class="text-danger">*</span></label>
                        <select name="id_servicio" class="form-select @error('id_servicio') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
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

                    {{-- Duración --}}
                    <div class="col-md-3">
                        <label class="form-label">Duración <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input
                                type="number"
                                step="0.5"
                                min="0.5"
                                name="duracion"
                                class="form-control @error('duracion') is-invalid @enderror"
                                value="{{ old('duracion') }}"
                                placeholder="Ej: 2"
                                data-filter="decimal"
                            >
                            <span class="input-group-text">hrs</span>
                            @error('duracion')
                                <div class="invalid-feedback d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Personas --}}
                    <div class="col-md-3">
                        <label class="form-label">Personas <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            min="1"
                            name="personas"
                            class="form-control @error('personas') is-invalid @enderror"
                            value="{{ old('personas') }}"
                            placeholder="Ej: 50"
                            data-filter="solo-numeros"
                        >
                        @error('personas')
                            <div class="invalid-feedback"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex gap-2 pt-4 border-top mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Evento
                    </button>
                    <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>