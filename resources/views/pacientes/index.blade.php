<x-layouts.app :title="'Pacientes'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<!-- filtros -->
<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

    <!-- filtro sexo -->
<div class="col-md-2">
<label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-sex me-1"></i>Sexo</label>
    <select name="sexo" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
            <option value="">Todos los pacientes</option>
            @foreach ($sexos as $value => $label)
                <option value="{{ $value }}" {{ request('sexo') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
</div>
        <br>
        
    <!--filtro fechas de nacimiento-->
    <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar me-1"></i>Desde</label>
    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control border-0 shadow-sm">
</div>

<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar me-1"></i>Hasta</label>
    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control border-0 shadow-sm">
</div>

 <!-- Botones -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" title="Aplicar Filtros">
                        <i class="bx bx-filter-alt me-1"></i> Filtrar fecha
                    </button>
                    @if(request()->hasAny(['tipo', 'estado', 'ambulancia', 'fecha_inicio', 'fecha_fin']))
                        <a href="{{ url()->current() }}" class="btn btn-limpiar w-100 shadow-sm" title="Limpiar filtros">
                            <i class="bx bx-x me-1"></i> Limpiar
                        </a>
                    @endif
                </div>

</div>
</div>
</form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pacientes</h5>
            <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nuevo
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Ap. Paterno</th>
                        <th>Ap. Materno</th>
                        <th>Sexo</th>
                        <th>Fecha Nac.</th>
                        <!--
                        <th>Servicio</th>
-->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                    <tr>
                        <td>{{ $paciente->id_paciente }}</td>
                        <td>{{ $paciente->nombre }}</td>
                        <td>{{ $paciente->ap_paterno }}</td>
                        <td>{{ $paciente->ap_materno ?? '—' }}</td>
                        <td>{{ $paciente->sexo ?? '—' }}</td>
                        <td>{{ $paciente->fecha_nacimiento ?? '—' }}</td>
                        <!--
                        <td>{{ $paciente->id_servicio }}</td>
-->
                        <td>
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $pacientes->total() }} registros</small>
            {{ $pacientes->links() }}
        </div>
    </div>
</x-layouts.app>
