<x-layouts.app :title="'Operadores'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

    <!-- filtro estado -->
     <div class="col-md-2">
<label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-option me-1"></i>Estado</label>
    <select name="estado" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
        @foreach ($estados as $value => $label)
            <option value="{{ $value }}"
                {{ request('estado') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
        <br>
    <!-- filtro por rango de sueldos-->
     <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-money me-1"></i>Desde</label>
    <input type="number" name="salario_min" placeholder="salario mínimo"
        value="{{ request('salario_hora') }}" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
</div>

        <div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-money me-1"></i>Hasta</label>
    <input type="number" name="salario_max" placeholder="salario máximo"
        value="{{ request('salario_hora') }}" class="form-control border-0 shadow-sm" onchange="this.form.submit()">
</div>
        

</div>
</div>
</form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Operadores</h5>
            <a href="{{ route('operadores.create') }}" class="btn btn-primary btn-sm">
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
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operadores as $operador)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ $operador->usuario->nombre ?? '—' }}
                                {{ $operador->usuario->ap_paterno ?? '' }}
                                {{ $operador->usuario->ap_materno ?? '' }}
                            </div>
                        </td>
                        <td>{{ $operador->usuario->email ?? '—' }}</td>
                        <td>${{ number_format($operador->salario_hora, 2) }}</td>
                        <td>
                            @if($operador->en_servicio)
                                <span class="badge bg-danger">En servicio</span>
                            @else
                                <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('operadores.show', $operador) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('operadores.edit', $operador) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('operadores.destroy', $operador) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $operadores->total() }} registros</small>
            {{ $operadores->links() }}
        </div>
    </div>
</x-layouts.app>
