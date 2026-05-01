<x-layouts.app :title="'Historial de Servicios'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!--Filtros-->
<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        

    <!-- filtro tipo -->
<div class="col-md-2">
<label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-category me-1"></i>Tipo</label>
    <select name="tipo" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            @foreach ($tipos as $value => $label)
                <option value="{{ $value }}" {{ request('tipo') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
</div>

     <!-- filtro estado -->
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-check-circle me-1"></i>Estado</label>
    <select name="estado" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
            @foreach ($estados as $value => $label)
                <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
</div>

     <!-- filtro ambulancia -->
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-car me-1"></i>Ambulancia</label>
    <select name="ambulancia" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
        <option value="">Todas las ambulancias</option>
            @foreach ($ambulancias as $ambulancia)
                <option value="{{ $ambulancia->id_ambulancia }}"
                {{ request('ambulancia') == $ambulancia->id_ambulancia ? 'selected' : '' }}>
                    {{ $ambulancia->placa }}
                </option>
            @endforeach
        </select>
</div>


        <!-- Filtro por operador -->
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-people me-1"></i>Operadores</label>
        <select name="operador" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
            <option value="">Todos los operadores</option>
                @foreach($operadores as $op)
                    <option value="{{ $op->id_operador }}"
                        {{ request('operador') == $op->id_operador ? 'selected' : '' }}>
                        {{ $op->usuario->name }}
                    </option>
                @endforeach
        </select>
</div>

    <br>

    <!-- filtro fecha -->
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar me-1"></i>Desde</label>
    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control border-0 shadow-sm">
</div>  
  
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-calendar me-1"></i>Hasta</label>
    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control border-0 shadow-sm">
</div>

    <br>
    <!-- filtro por rango de precios-->>
<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-money me-1"></i>Desde</label>
    <input type="number" name="costo_min" placeholder="Costo mínimo" value="{{ request('costo_min') }}" class="form-control border-0 shadow-sm">
</div>

<div class="col-md-2">
    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-money me-1"></i>Desde</label>
    <input type="number" name="costo_max" placeholder="Costo máximo" value="{{ request('costo_max') }}" class="form-control border-0 shadow-sm">
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
</form> 


<!--Tabla-->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bx bx-history me-2"></i>Historial de Servicios</h5>
                <small class="text-muted">Registro completo de todos los servicios</small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha/Hora</th>
                        <th>Costo Total</th>
                        <th>Ambulancia</th>
                        <th>Operador</th>
                        <th>Cliente</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td class="fw-semibold">{{ $servicio->id_servicio }}</td>
                        <td>{{ $servicio->tipo ?? '—' }}</td>
                        <td>
                            @php
                                $badge = match($servicio->estado) {
                                    'Activo'     => 'bg-success',
                                    'Finalizado' => 'bg-secondary',
                                    'Cancelado'  => 'bg-danger',
                                    default      => 'bg-primary',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $servicio->estado }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</td>
                        <td>${{ number_format($servicio->costo_total, 2) }}</td>
                        <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>
                        <td>{{ $servicio->operador->usuario->nombre ?? '—' }}</td>
                        <td>{{ $servicio->cliente->usuario->nombre ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('servicios.show', $servicio) }}"
                               class="btn btn-sm btn-outline-info me-1"
                               title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                            <a href="{{ route('servicios.edit', $servicio) }}"
                               class="btn btn-sm btn-outline-warning me-1"
                               title="Editar">
                                <i class="bx bx-edit"></i> Editar
                            </a>
                            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bx bx-folder-open fs-2 d-block mb-2"></i>
                            No hay servicios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $servicios->total() }} registros</small>
            {{ $servicios->links() }}
        </div>
    </div>
</x-layouts.app>
