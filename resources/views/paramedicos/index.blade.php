<x-layouts.app :title="'Paramédicos'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @vite('resources/css/filtros.css')
    <div class="card filtro-card mb-4 border-0 shadow-sm">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
<form method="GET" action="{{ url()->current() }}">

    <!-- filtro por rango de sueldos-->
         <div class="col-md-2">
        <label class="form-label filtro-label">
            <i class="bx bx-category me-1"></i>Salario mínimo
        </label>
    <input type="number" name="salario_min" placeholder="salario mínimo"
        value="{{ request('salario_hora') }}" class="form-select filtro-input" onchange="this.form.submit()">
</div>

         <div class="col-md-2">
        <label class="form-label filtro-label">
            <i class="bx bx-category me-1"></i>Salario mínimo
        </label>
    <input type="number" name="salario_max" placeholder="salario máximo"
        value="{{ request('salario_hora') }}" class="form-select filtro-input" onchange="this.form.submit()">
</div>

    <!-- Botones -->
    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">
            <i class="bx bx-filter-alt me-1"></i> Filtrar
        </button>

        @if(request()->hasAny(['tipo', 'estado', 'ambulancia', 'fecha_inicio', 'fecha_fin']))
            <a href="{{ url()->current() }}" class="btn btn-limpiar w-100">
                <i class="bx bx-x me-1"></i> Limpiar
            </a>
        @endif
    </div>

</div>
</div>
</form>

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
