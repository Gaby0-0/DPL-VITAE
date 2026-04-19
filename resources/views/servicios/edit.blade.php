<x-layouts.app :title="'Editar Servicio'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Servicio #{{ $servicio->id_servicio }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('servicios.update', $servicio) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach(['Traslado', 'Evento', 'Emergencia', 'Otro'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo', $servicio->tipo) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach(['Activo', 'Finalizado', 'Cancelado'] as $estado)
                            <option value="{{ $estado }}" {{ old('estado', $servicio->estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                            @endforeach
                        </select>
                        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha y Hora <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="fecha_hora" class="form-control @error('fecha_hora') is-invalid @enderror" value="{{ old('fecha_hora', $servicio->fecha_hora) }}" required>
                        @error('fecha_hora')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hora de Salida</label>
                        <input type="datetime-local" name="hora_salida" class="form-control @error('hora_salida') is-invalid @enderror" value="{{ old('hora_salida', $servicio->hora_salida) }}">
                        @error('hora_salida')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Costo Total <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="costo_total" class="form-control @error('costo_total') is-invalid @enderror" value="{{ old('costo_total', $servicio->costo_total) }}" required>
                        @error('costo_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ambulancia <span class="text-danger">*</span></label>
                        <select name="id_ambulancia" class="form-select @error('id_ambulancia') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($ambulancias as $ambulancia)
                                <option value="{{ $ambulancia->id_ambulancia }}" {{ old('id_ambulancia', $servicio->id_ambulancia) == $ambulancia->id_ambulancia ? 'selected' : '' }}>
                                    {{ $ambulancia->placa }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_ambulancia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cliente <span class="text-danger">*</span></label>
                        <select name="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id_usuario }}" {{ old('id_cliente', $servicio->id_cliente) == $cliente->id_usuario ? 'selected' : '' }}>
                                    {{ $cliente->usuario->nombre ?? $cliente->id_usuario }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Operador <span class="text-danger">*</span></label>
                        <select name="id_operador" class="form-select @error('id_operador') is-invalid @enderror" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($operadores as $operador)
                                <option value="{{ $operador->id_usuario }}" {{ old('id_operador', $servicio->id_operador) == $operador->id_usuario ? 'selected' : '' }}>
                                    {{ $operador->usuario->nombre ?? $operador->id_usuario }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_operador')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="3">{{ old('observaciones', $servicio->observaciones) }}</textarea>
                        @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
