<x-layouts.app :title="'Ambulancias'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

@vite('resources/css/filtros.css')
<div class="card filtro-card mb-4 border-0 shadow-sm">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}"class="filtro-form">

    <select name="estado" class="form-select filtro-label">
        <option value="">Todos</option>
        <option value="Disponible" {{ request('estado') == 'Disponible' ? 'selected' : '' }}>
            Disponible
        </option>
        <option value="No disponible" {{ request('estado') == 'No disponible' ? 'selected' : '' }}>
            No disponible
        </option>
    </select>

    <select name="tipo" class="form-select filtro-label">
        <option value="">Todos los tipos</option>
        @foreach ($tipos as $tipo)
            <option value="{{ $tipo->id_tipo_ambulancia }}"
                {{ request('tipo') == $tipo->id_tipo_ambulancia ? 'selected' : '' }}>
                {{ $tipo->nombre_tipo }}
            </option>
        @endforeach
    </select>

</div>
</div>
</form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ambulancias</h5>
            <a href="{{ route('ambulancias.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Placa</th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ambulancias as $ambulancia)
                    <tr>
                        <td>{{ $ambulancia->id_ambulancia }}</td>
                        <td>{{ $ambulancia->placa }}</td>
                        <td>{{ $ambulancia->estado }}</td>
                        <td>{{ $ambulancia->tipo->nombre_tipo ?? '—' }}</td>
                        <td>
                            <a href="{{ route('ambulancias.show', $ambulancia) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('ambulancias.edit', $ambulancia) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('ambulancias.destroy', $ambulancia) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
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
            <small class="text-muted">Total: {{ $ambulancias->total() }} registros</small>
            {{ $ambulancias->links() }}
        </div>
    </div>
</x-layouts.app>
