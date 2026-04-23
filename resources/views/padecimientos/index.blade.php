<x-layouts.app :title="'Padecimientos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<form method="GET" action="{{ url()->current() }}">
        

    <button type="submit">Filtrar</button>

    <!-- filtro nivel riesgo -->
    <select name="nivel_riesgo">
        <option value="">Todos los niveles</option>
        @foreach ($niveles as $value => $label)
            <option value="{{ $value }}"
                {{ request('nivel_riesgo') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

        <br>
    <!-- filtro por rango de precios-->>
    <input type="number" name="costo_min" placeholder="Costo mínimo"
        value="{{ request('costo_min') }}">

    <input type="number" name="costo_max" placeholder="Costo máximo"
        value="{{ request('costo_max') }}">

</form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Padecimientos</h5>
            <a href="{{ route('padecimientos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Nivel de Riesgo</th>
                        <th>Costo Extra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($padecimientos as $padecimiento)
                    <tr>
                        <td>{{ $padecimiento->id_padecimiento }}</td>
                        <td>{{ $padecimiento->nombre_padecimiento }}</td>
                        <td>{{ $padecimiento->nivel_riesgo }}</td>
                        <td>${{ number_format($padecimiento->costo_extra, 2) }}</td>
                        <td>
                            <a href="{{ route('padecimientos.show', $padecimiento) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('padecimientos.edit', $padecimiento) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('padecimientos.destroy', $padecimiento) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
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
            <small class="text-muted">Total: {{ $padecimientos->total() }} registros</small>
            {{ $padecimientos->links() }}
        </div>
    </div>
</x-layouts.app>
