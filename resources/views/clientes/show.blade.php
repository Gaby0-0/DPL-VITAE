<x-layouts.app :title="'Detalle Cliente'">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cliente: {{ $cliente->usuario->nombre ?? $cliente->id_usuario }}</h5>
                    <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $cliente->id_usuario }}</dd>
                        <dt class="col-sm-4">Nombre</dt>
                        <dd class="col-sm-8">{{ $cliente->usuario->nombre ?? '—' }}</dd>
                        <dt class="col-sm-4">Apellido Paterno</dt>
                        <dd class="col-sm-8">{{ $cliente->usuario->ap_paterno ?? '—' }}</dd>
                        <dt class="col-sm-4">Apellido Materno</dt>
                        <dd class="col-sm-8">{{ $cliente->usuario->ap_materno ?? '—' }}</dd>
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $cliente->usuario->email ?? '—' }}</dd>
                        <dt class="col-sm-4">Teléfono</dt>
                        <dd class="col-sm-8">{{ $cliente->usuario->telefono ?? '—' }}</dd>
                    </dl>
                    <div class="mt-3">
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">
                            <i class="bx bx-edit me-1"></i>Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Servicios del Cliente</h6></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($cliente->servicios as $servicio)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>#{{ $servicio->id_servicio }} — {{ $servicio->tipo ?? $servicio->estado }}</span>
                                <span class="text-muted">{{ $servicio->fecha_hora }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin servicios</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
