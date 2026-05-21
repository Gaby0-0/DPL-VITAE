<x-layouts.app :title="'Eventos'">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bx bx-calendar-event me-2 text-primary"></i>Eventos</h4>
            <small class="text-muted">Servicios de tipo Evento confirmados por clientes</small>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;">
                        <i class="bx bx-check-circle me-1"></i>Estado
                    </label>
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        @foreach($estados as $val => $label)
                            <option value="{{ $val }}" {{ request('estado') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;">
                        <i class="bx bx-calendar me-1"></i>Desde
                    </label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;">
                        <i class="bx bx-calendar me-1"></i>Hasta
                    </label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['estado', 'fecha_inicio', 'fecha_fin']))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">
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
            <h6 class="mb-0"><i class="bx bx-list-ul me-1"></i>Servicios de Evento</h6>
            <small class="text-muted">Total: {{ $servicios->total() }} registros</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Fecha / Hora</th>
                        <th>Cliente</th>
                        <th>Ambulancia</th>
                        <th>Operador</th>
                        <th class="text-center">Duración</th>
                        <th class="text-center">Personas</th>
                        <th class="text-end">Costo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td class="fw-semibold">{{ $servicio->id_servicio }}</td>
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
                        <td>{{ $servicio->cliente->usuario->nombre ?? '—' }}</td>
                        <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>
                        <td>{{ $servicio->operador->usuario->nombre ?? '—' }}</td>
                        <td class="text-center">
                            @if($servicio->evento)
                                {{ $servicio->evento->duracion }} h
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($servicio->evento)
                                {{ $servicio->evento->personas }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">${{ number_format($servicio->costo_total, 2) }}</td>
                        <td class="text-center">
                            <a href="{{ route('servicios.show', $servicio) }}"
                               class="btn btn-sm btn-outline-info" title="Ver detalle">
                                <i class="bx bx-show"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bx bx-calendar-x fs-2 d-block mb-2"></i>
                            No hay eventos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $servicios->links() }}
        </div>
    </div>

</x-layouts.app>
