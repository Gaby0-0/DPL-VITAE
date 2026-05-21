<x-layouts.app :title="'Paramédicos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tarifa global de paramédicos --}}
    <div class="card mb-4 border-0 shadow-sm" style="border-left: 4px solid #71dd37 !important;">
        <div class="card-body py-3">
            <form action="{{ route('paramedicos.tarifa') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bx bx-dollar-circle text-success fs-5"></i>
                        <span class="fw-semibold">Tarifa por hora — todos los paramédicos</span>
                    </div>
                    <small class="text-muted">Esta tarifa aplica para el cálculo de todas las cotizaciones.</small>
                </div>
                <div class="col-md-3 ms-auto">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">$</span>
                        <input type="number" name="tarifa_paramedico" step="0.01" min="0"
                               class="form-control @error('tarifa_paramedico') is-invalid @enderror"
                               value="{{ old('tarifa_paramedico', $empresa->tarifa_paramedico ?? 80) }}" required>
                        <span class="input-group-text">MXN/hr</span>
                        <button class="btn btn-success" type="submit">
                            <i class="bx bx-save me-1"></i>Guardar
                        </button>
                    </div>
                    @error('tarifa_paramedico')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </form>
        </div>
    </div>

    @vite('resources/css/filtros.css')
    <div class="card filtro-card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-toggle-left me-1"></i>Estado
                    </label>
                    <select name="activo" class="form-select filtro-input" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>


                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['activo', 'salario_min', 'salario_max']))
                        <a href="{{ url()->current() }}" class="btn btn-limpiar w-100">
                            <i class="bx bx-x me-1"></i> Limpiar
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Paramédicos</h5>
            <a href="{{ route('paramedicos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paramedicos as $paramedico)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ $paramedico->usuario->nombre ?? '—' }}
                                {{ $paramedico->usuario->ap_paterno ?? '' }}
                                {{ $paramedico->usuario->ap_materno ?? '' }}
                            </div>
                        </td>
                        <td>{{ $paramedico->usuario->email ?? '—' }}</td>
                        <td>
                            @if($paramedico->usuario?->activo)
                                <span class="badge bg-success"><i class="bx bx-check me-1"></i>Activo</span>
                            @else
                                <span class="badge bg-secondary"><i class="bx bx-x me-1"></i>Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('paramedicos.show', $paramedico) }}" class="btn btn-sm btn-info" title="Ver"><i class="bx bx-show"></i></a>
                            <a href="{{ route('paramedicos.edit', $paramedico) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bx bx-edit"></i></a>

                            {{-- Toggle activar/desactivar --}}
                            <form action="{{ route('paramedicos.toggle-activo', $paramedico) }}" method="POST" class="d-inline">
                                @csrf
                                @if($paramedico->usuario?->activo)
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Desactivar"
                                        onclick="return confirm('¿Desactivar la cuenta de este paramédico?')">
                                        <i class="bx bx-toggle-right"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Activar"
                                        onclick="return confirm('¿Activar la cuenta de este paramédico?')">
                                        <i class="bx bx-toggle-left"></i>
                                    </button>
                                @endif
                            </form>

                            <form action="{{ route('paramedicos.destroy', $paramedico) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar permanentemente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Eliminar"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $paramedicos->total() }} registros</small>
            {{ $paramedicos->links() }}
        </div>
    </div>
</x-layouts.app>
