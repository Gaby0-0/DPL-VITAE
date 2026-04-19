<x-layouts.app :title="'Historial de Servicios'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
