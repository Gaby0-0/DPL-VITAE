
<x-layouts.app :title="'Historial de Servicios'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @vite('resources/css/filtros.css')

    {{-- Encabezado --}}
    <div class="mb-4">
        <h4 class="mb-0 fw-bold"><i class="bx bx-history me-2 text-primary"></i>Historial de Servicios</h4>
        <small class="text-muted">Registro completo de servicios realizados</small>
    </div>

    {{-- Tabs --}}
    @php $tabActivo = request('tipo', ''); @endphp
    <ul class="nav nav-tabs mb-0" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ $tabActivo === '' ? 'active' : '' }}"
               href="{{ route('servicios.index') }}">
                <i class="bx bx-list-ul me-1"></i> Todos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tabActivo === 'Traslado' ? 'active' : '' }}"
               href="{{ route('servicios.index', ['tipo' => 'Traslado']) }}">
                <i class="bx bx-car me-1"></i> Traslados
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tabActivo === 'Evento' ? 'active' : '' }}"
               href="{{ route('servicios.index', ['tipo' => 'Evento']) }}">
                <i class="bx bx-calendar-event me-1"></i> Eventos
            </a>
        </li>
    </ul>

    {{-- Filtros --}}
    <div class="card filtro-card mb-4 border-0 shadow-sm" style="border-top-left-radius:0;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('servicios.index') }}" class="row g-3 align-items-end">

                {{-- Preservar tab activo --}}
                @if($tabActivo)
                    <input type="hidden" name="tipo" value="{{ $tabActivo }}">
                @endif

                <!-- Estado -->
                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-check-circle me-1"></i>Estado
                    </label>
                    <select name="estado" class="form-select filtro-input" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        @foreach ($estados as $value => $label)
                            <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ambulancia -->
                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-car me-1"></i>Ambulancia
                    </label>
                    <select name="ambulancia" class="form-select filtro-input" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach ($ambulancias as $ambulancia)
                            <option value="{{ $ambulancia->id_ambulancia }}"
                                {{ request('ambulancia') == $ambulancia->id_ambulancia ? 'selected' : '' }}>
                                {{ $ambulancia->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Operador -->
                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-people me-1"></i>Operador
                    </label>
                    <select name="operador" class="form-select filtro-input" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($operadores as $op)
                            <option value="{{ $op->id_operador }}"
                                {{ request('operador') == $op->id_operador ? 'selected' : '' }}>
                                {{ $op->usuario->nombre ?? '' }} {{ $op->usuario->ap_paterno ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-calendar me-1"></i>Desde
                    </label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control filtro-input">
                </div>

                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-calendar me-1"></i>Hasta
                    </label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control filtro-input">
                </div>

                <!-- Costos -->
                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-money me-1"></i>Costo mín.
                    </label>
                    <input type="number" name="costo_min" placeholder="0" value="{{ request('costo_min') }}" class="form-control filtro-input">
                </div>

                <div class="col-md-2">
                    <label class="form-label filtro-label">
                        <i class="bx bx-money me-1"></i>Costo máx.
                    </label>
                    <input type="number" name="costo_max" placeholder="0" value="{{ request('costo_max') }}" class="form-control filtro-input">
                </div>

                <!-- Botones -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['estado', 'ambulancia', 'operador', 'fecha_inicio', 'fecha_fin', 'costo_min', 'costo_max']))
                        <a href="{{ route('servicios.index', $tabActivo ? ['tipo' => $tabActivo] : []) }}"
                           class="btn btn-limpiar w-100">
                            <i class="bx bx-x me-1"></i> Limpiar
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0">
                    @if($tabActivo === 'Traslado')
                        <i class="bx bx-car me-1"></i>Traslados
                    @elseif($tabActivo === 'Evento')
                        <i class="bx bx-calendar-event me-1"></i>Eventos
                    @else
                        <i class="bx bx-list-ul me-1"></i>Todos los servicios
                    @endif
                </h6>
            </div>
            <small class="text-muted">Total: {{ $servicios->total() }} registros</small>
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
                        <td>
                            @php
                                $tipoBadge = match($servicio->tipo) {
                                    'Traslado' => 'bg-label-info',
                                    'Evento'   => 'bg-label-primary',
                                    default    => 'bg-label-secondary',
                                };
                            @endphp
                            <span class="badge {{ $tipoBadge }}">{{ $servicio->tipo ?? 'N/A' }}</span>
                        </td>
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
                               class="btn btn-sm btn-outline-info me-1" title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                            <a href="{{ route('servicios.edit', $servicio) }}"
                               class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="bx bx-edit"></i> Editar
                            </a>
                            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bx bx-trash"></i>
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
            <small class="text-muted">Mostrando {{ $servicios->count() }} de {{ $servicios->total() }}</small>
            {{ $servicios->links() }}
        </div>
    </div>
</x-layouts.app>
