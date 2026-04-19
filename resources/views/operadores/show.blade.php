<x-layouts.app :title="'Detalle Operador'">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $operador->usuario->nombre ?? $operador->id_usuario }}</h5>
                    <div>
                        <a href="{{ route('operadores.edit', $operador) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                        <a href="{{ route('operadores.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Estado</dt>
                        <dd class="col-sm-7">
                            @if($enServicio)
                                <span class="badge bg-danger fs-6"><i class="bx bx-ambulance me-1"></i>En servicio</span>
                            @else
                                <span class="badge bg-success fs-6"><i class="bx bx-check-circle me-1"></i>Disponible</span>
                            @endif
                        </dd>
                        <dt class="col-sm-5">Nombre</dt>
                        <dd class="col-sm-7">
                            {{ $operador->usuario->nombre ?? '—' }}
                            {{ $operador->usuario->ap_paterno ?? '' }}
                            {{ $operador->usuario->ap_materno ?? '' }}
                        </dd>
                        <dt class="col-sm-5">Email</dt>
                        <dd class="col-sm-7">{{ $operador->usuario->email ?? '—' }}</dd>
                        <dt class="col-sm-5">Salario/Hora</dt>
                        <dd class="col-sm-7">${{ number_format($operador->salario_hora, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Servicios asignados</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Ambulancia</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operador->servicios->sortByDesc('fecha_hora') as $servicio)
                            <tr>
                                <td>{{ $servicio->id_servicio }}</td>
                                <td>{{ \Carbon\Carbon::parse($servicio->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $servicio->tipo ?? '—' }}</td>
                                <td>{{ $servicio->ambulancia->placa ?? '—' }}</td>
                                <td>
                                    @php
                                        $badge = match($servicio->estado) {
                                            'Activo'     => 'danger',
                                            'Finalizado' => 'secondary',
                                            'Cancelado'  => 'warning',
                                            default      => 'primary',
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $badge }}">{{ $servicio->estado }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Sin servicios</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
