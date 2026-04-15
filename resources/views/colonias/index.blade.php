<x-layouts.app :title="'Colonias'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Colonias</h5>
            <a href="{{ route('colonias.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>C.P.</th>
                        <th>Municipio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colonias as $colonia)
                    <tr>
                        <td>{{ $colonia->id_colonia }}</td>
                        <td>{{ $colonia->nombre_colonia }}</td>
                        <td>{{ $colonia->codigo_postal }}</td>
                        <td>{{ $colonia->municipio->nombre_municipio ?? '—' }}</td>
                        <td>
                            <a href="{{ route('colonias.show', $colonia) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('colonias.edit', $colonia) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('colonias.destroy', $colonia) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
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
        <div class="card-footer">{{ $colonias->links() }}</div>
    </div>
</x-layouts.app>
