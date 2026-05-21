<x-layouts.app :title="'Usuarios'">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bx bx-user-check me-2 text-primary"></i>Usuarios</h4>
            <small class="text-muted">Cuentas de clientes registradas en el sistema</small>
        </div>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Nuevo usuario
        </a>
    </div>

    {{-- Búsqueda --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;">
                        <i class="bx bx-search me-1"></i>Buscar
                    </label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="form-control" placeholder="Nombre, apellido o correo…">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-search me-1"></i> Buscar
                    </button>
                    @if(request('buscar'))
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bx bx-x me-1"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bx bx-list-ul me-1"></i>Listado de usuarios</h6>
            <small class="text-muted">Total: {{ $clientes->total() }} registros</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    @php $u = $cliente->usuario; @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm">
                                    <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                                        {{ strtoupper(substr($u->nombre ?? '?', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="fw-semibold d-block">
                                        {{ trim(($u->nombre ?? '') . ' ' . ($u->ap_paterno ?? '') . ' ' . ($u->ap_materno ?? '')) ?: '—' }}
                                    </span>
                                    <small class="text-muted">#{{ $cliente->id_usuario }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->email ?? '—' }}</td>
                        <td>{{ $u->telefono ?? '—' }}</td>
                        <td class="text-center">
                            @if($u && $u->activo)
                                <span class="badge bg-label-success">Activo</span>
                            @else
                                <span class="badge bg-label-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('clientes.show', $cliente) }}"
                               class="btn btn-sm btn-outline-info me-1" title="Ver detalle">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('clientes.edit', $cliente) }}"
                               class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este usuario cliente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bx bx-user-x fs-2 d-block mb-2"></i>
                            @if(request('buscar'))
                                No se encontraron usuarios con "{{ request('buscar') }}"
                            @else
                                No hay usuarios registrados
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $clientes->links() }}
        </div>
    </div>

</x-layouts.app>
