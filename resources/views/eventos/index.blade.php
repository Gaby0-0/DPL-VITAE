<x-layouts.app :title="'Eventos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<!-- Filtros -->
     <form method="GET" action="{{ url()->current() }}">

    <button type="submit">Filtrar</button>

<br>

      <!-- filtro por rango de horas-->>
    <input type="number" name="duracion_min" placeholder="Minimo de horas"
        value="{{ request('duracion_min') }}">

    <input type="number" name="duracion_max" placeholder="Máximo de horas"
        value="{{ request('duracion_max') }}">

<br>

     <!-- filtro por rango de personas-->>
    <input type="number" name="personas_min" placeholder="Mínimo de personas"
        value="{{ request('personas_min') }}">

    <input type="number" name="personas_max" placeholder="Máximo de personas"
        value="{{ request('personas_max') }}">

</form>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Eventos</h5>
           <!--  <a href="{{ route('eventos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a> -->
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Duración</th>
                        <th>Personas</th>
<!--                         <th>Acciones</th>
 -->                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                    <tr>
                        <td>{{ $evento->id_evento }}</td>
                        <td>{{ $evento->servicio->tipo ?? '—'}}</td>
                        <td>{{ $evento->duracion }}</td>
                        <td>{{ $evento->personas }}</td>
                       <!--  <td>
                            <a href="{{ route('eventos.show', $evento) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td> -->
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $eventos->total() }} registros</small>
            {{ $eventos->links() }}
        </div>
    </div>
</x-layouts.app>
