<x-layouts.app :title="'Operadores'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tarifa global de operadores --}}
    <div class="card mb-4 border-0 shadow-sm" style="border-left: 4px solid #696cff !important;">
        <div class="card-body py-3">
            <form action="{{ route('operadores.tarifa') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bx bx-dollar-circle text-primary fs-5"></i>
                        <span class="fw-semibold">Tarifa por hora — todos los operadores</span>
                    </div>
                    <small class="text-muted">Esta tarifa aplica para el cálculo de todas las cotizaciones.</small>
                </div>
                <div class="col-md-3 ms-auto">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">$</span>
                        <input type="number" name="tarifa_operador" step="0.01" min="0"
                               class="form-control @error('tarifa_operador') is-invalid @enderror"
                               value="{{ old('tarifa_operador', $empresa->tarifa_operador ?? 100) }}" required>
                        <span class="input-group-text">MXN/hr</span>
                        <button class="btn btn-primary" type="submit">
                            <i class="bx bx-save me-1"></i>Guardar
                        </button>
                    </div>
                    @error('tarifa_operador')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
        <div class="card-body p-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

                <div class="col-md-2">
                    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;">
                        <i class="bx bx-toggle-left me-1"></i>Estado cuenta
                    </label>
                    <select name="activo" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;">
                        <i class="bx bx-option me-1"></i>Disponibilidad
                    </label>
                    <select name="estado" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach ($estados as $value => $label)
                            <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>


            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Operadores</h5>
            <a href="{{ route('operadores.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Cuenta</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operadores as $operador)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ $operador->usuario->nombre ?? '—' }}
                                {{ $operador->usuario->ap_paterno ?? '' }}
                                {{ $operador->usuario->ap_materno ?? '' }}
                            </div>
                        </td>
                        <td>{{ $operador->usuario->email ?? '—' }}</td>
                        <td>
                            @if($operador->usuario?->activo)
                                <span class="badge bg-success"><i class="bx bx-check me-1"></i>Activo</span>
                            @else
                                <span class="badge bg-secondary"><i class="bx bx-x me-1"></i>Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if($operador->en_servicio)
                                <span class="badge bg-danger">En servicio</span>
                            @else
                                <span class="badge bg-label-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('operadores.show', $operador) }}" class="btn btn-sm btn-info" title="Ver"><i class="bx bx-show"></i></a>
                            <a href="{{ route('operadores.edit', $operador) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bx bx-edit"></i></a>

                            {{-- Toggle activar/desactivar --}}
                            <form action="{{ route('operadores.toggle-activo', $operador) }}" method="POST" class="d-inline">
                                @csrf
                                @if($operador->usuario?->activo)
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Desactivar"
                                        onclick="return confirm('¿Desactivar la cuenta de este operador?')">
                                        <i class="bx bx-toggle-right"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Activar"
                                        onclick="return confirm('¿Activar la cuenta de este operador?')">
                                        <i class="bx bx-toggle-left"></i>
                                    </button>
                                @endif
                            </form>

                            <form action="{{ route('operadores.destroy', $operador) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar permanentemente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Eliminar"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $operadores->total() }} registros</small>
            {{ $operadores->links() }}
        </div>
    </div>
</x-layouts.app>
