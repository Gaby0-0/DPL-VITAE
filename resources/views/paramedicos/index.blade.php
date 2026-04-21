<x-layouts.app :title="'Paramédicos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
                        <th>Salario/Hora</th>
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
                        <td>${{ number_format($paramedico->salario_hora, 2) }}</td>
                        <td>
                            <a href="{{ route('paramedicos.show', $paramedico) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('paramedicos.edit', $paramedico) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('paramedicos.destroy', $paramedico) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
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
