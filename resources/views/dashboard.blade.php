@section('title', 'Dashboard')
<x-layouts.app :title="__('Dashboard')">

    @php
        $totalServicios   = \App\Models\Servicio::count();
        $serviciosActivos = \App\Models\Servicio::where('estado', 'Activo')->count();
        $totalAmbulancia  = \App\Models\Ambulancia::count();
        $ambulanciasDisp  = \App\Models\Ambulancia::where('estado', 'Disponible')->count();
        $totalPacientes   = \App\Models\Paciente::count();
        $totalParamedicos = \App\Models\Paramedico::count();
        $totalClientes    = \App\Models\Cliente::count();
        $totalOperadores  = \App\Models\Operador::count();

        $ultimosServicios = \App\Models\Servicio::with(['ambulancia', 'cliente.usuario'])
            ->orderByDesc('fecha_hora')
            ->limit(10)
            ->get();
    @endphp

    <div class="row g-4 mb-4">

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Servicios Activos</span>
                            <h3 class="card-title mb-2">{{ $serviciosActivos }}</h3>
                            <small class="text-muted">de {{ $totalServicios }} en total</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-ambulance bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Ambulancias Disponibles</span>
                            <h3 class="card-title mb-2">{{ $ambulanciasDisp }}</h3>
                            <small class="text-muted">de {{ $totalAmbulancia }} en total</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-car bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Pacientes</span>
                            <h3 class="card-title mb-2">{{ $totalPacientes }}</h3>
                            <small class="text-muted">registrados</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-user-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="fw-medium d-block mb-1">Personal</span>
                            <h3 class="card-title mb-2">{{ $totalParamedicos + $totalOperadores }}</h3>
                            <small class="text-muted">{{ $totalParamedicos }} paramédicos · {{ $totalOperadores }} operadores</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-group bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
                    <label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-ambulance me-1"></i>Ambulancia</label>
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

    <!-- filtro fecha -->
<!--   <form method="GET" action="{{ url()->current() }}"> -->
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

    </form> 
            </div>
    </div>

    <div class="row g-4">

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Últimos Servicios</h5>
                    <small class="text-muted">Últimos 10 registros</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Ambulancia</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody>


                            @forelse($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->id_servicio }}</td>
                                <td>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $servicio->tipo ?? '—' }}</td>
                                <td>
                                    @php
                                        $badge = match($servicio->estado) {
                                            'Activo'     => 'success',
                                            'Finalizado' => 'secondary',
                                            'Cancelado'  => 'danger',
                                            default      => 'warning',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $badge }}">{{ $servicio->estado }}</span>
                                </td>
                                <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>
                                <td>${{ number_format($servicio->costo_total, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Sin servicios registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Resumen de Personal</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-plus-medical"></i></span>
                                </span>
                                <span>Paramédicos</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $totalParamedicos }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-id-card"></i></span>
                                </span>
                                <span>Operadores</span>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $totalOperadores }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-user"></i></span>
                                </span>
                                <span>Clientes</span>
                            </div>
                            <span class="badge bg-info rounded-pill">{{ $totalClientes }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Estado de Ambulancias</h5>
                </div>
                <div class="card-body">
                    @php
                        $estadosAmb = \App\Models\Ambulancia::selectRaw('estado, count(*) as total')
                            ->groupBy('estado')
                            ->get();
                    @endphp
                    @forelse($estadosAmb as $est)
                    @php
                        $color = match($est->estado) {
                            'Disponible' => 'success',
                            'En servicio', 'En Servicio' => 'warning',
                            'Mantenimiento' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>{{ $est->estado }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress" style="width:120px; height:6px;">
                                <div class="progress-bar bg-{{ $color }}" style="width:{{ $totalAmbulancia ? ($est->total / $totalAmbulancia) * 100 : 0 }}%"></div>
                            </div>
                            <span class="badge bg-label-{{ $color }}">{{ $est->total }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">Sin ambulancias registradas</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
