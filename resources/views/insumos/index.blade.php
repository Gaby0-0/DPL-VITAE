<x-layouts.app :title="'Insumos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!--Filtros-->
    <form method="GET" action="{{ url()->current() }}">
        

    <button type="submit">Filtrar</button>

        <br>
    <!-- filtro por rango de precios-->>
    <input type="number" name="costo_min" placeholder="Costo mínimo"
        value="{{ request('costo_min') }}">

    <input type="number" name="costo_max" placeholder="Costo máximo"
        value="{{ request('costo_max') }}">

</form> 

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Insumos</h5>
            <a href="{{ route('insumos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Insumo</th>
                        <th>Costo Unitario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->id_insumo }}</td>
                        <td>{{ $insumo->nombre_insumo }}</td>
                        <td>${{ number_format($insumo->costo_unidad, 2) }}</td>
                        <td>
                            <a href="{{ route('insumos.show', $insumo) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('insumos.edit', $insumo) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('insumos.destroy', $insumo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $insumos->total() }} registros</small>
            {{ $insumos->links() }}
        </div>
    </div>
</x-layouts.app>
