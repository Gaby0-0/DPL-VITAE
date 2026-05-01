<x-layouts.app :title="'Eventos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<!-- Filtros -->
<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

    
      <!-- filtro por rango de horas-->>
       <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-time me-1"></i>Desde</label>
    <input type="number" name="duracion_min" placeholder="Minimo de horas"
        value="{{ request('duracion_min') }}" class="form-control border-0 shadow-sm">
</div>

        <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-time me-1"></i>Hasta</label>
    <input type="number" name="duracion_max" placeholder="Máximo de horas"
        value="{{ request('duracion_max') }}" class="form-control border-0 shadow-sm">
</div>

<br>

     <!-- filtro por rango de personas-->>
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-person me-1"></i>Desde</label>
     <input type="number" name="personas_min" placeholder="Mínimo de personas"
        value="{{ request('personas_min') }}" class="form-control border-0 shadow-sm">
</div>

        <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-person me-1"></i>Hasta</label>
    <input type="number" name="personas_max" placeholder="Máximo de personas"
        value="{{ request('personas_max') }}" class="form-control border-0 shadow-sm">
</div>


</div>
</div>
</form>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Eventos</h5>
            <a href="{{ route('eventos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Duración</th>
                        <th>Personas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                    <tr>
                        <td>{{ $evento->id_evento }}</td>
                        <td>{{ $evento->servicio->tipo ?? '—'}}</td>
                        <td>{{ $evento->duracion }}</td>
                        <td>{{ $evento->personas }}</td>
                        <td>
                            <a href="{{ route('eventos.show', $evento) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
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
            <small class="text-muted">Total: {{ $eventos->total() }} registros</small>
            {{ $eventos->links() }}
        </div>
    </div>
</x-layouts.app>
