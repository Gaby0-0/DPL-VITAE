<x-layouts.app :title="'Direcciones'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Direcciones</h5>
            <a href="{{ route('direcciones.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Calle</th>
                        <th>N° Ext.</th>
                        <th>N° Int.</th>
                        <th>Colonia</th>
                        <th>Municipio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($direcciones as $direccion)
                    <tr>
                        <td>{{ $direccion->id_direccion }}</td>
                        <td>{{ $direccion->nombre_calle }}</td>
                        <td>{{ $direccion->n_exterior }}</td>
                        <td>{{ $direccion->n_interior ?? '—' }}</td>
                        <td>{{ $direccion->colonia->nombre_colonia ?? '—' }}</td>
                        <td>{{ $direccion->colonia->municipio->nombre_municipio ?? '—' }}</td>
                        <td>
                            <a href="{{ route('direcciones.show', $direccion) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('direcciones.edit', $direccion) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('direcciones.destroy', $direccion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $direcciones->links() }}</div>
    </div>
</x-layouts.app>
