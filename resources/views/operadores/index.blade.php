<x-layouts.app :title="'Operadores'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
                        <th>Salario/Hora</th>
                        <th>Estado</th>
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
                        <td>${{ number_format($operador->salario_hora, 2) }}</td>
                        <td>
                            @if($operador->en_servicio)
                                <span class="badge bg-danger">En servicio</span>
                            @else
                                <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('operadores.show', $operador) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('operadores.edit', $operador) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('operadores.destroy', $operador) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $operadores->links() }}</div>
    </div>
</x-layouts.app>
