<x-layouts.app :title="'Detalle Servicio'">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Servicio #{{ $servicio->id_servicio }}</h5>
                    <div>
                        <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                        <a href="{{ route('servicios.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Tipo</dt>
                        <dd class="col-sm-7">{{ $servicio->tipo ?? '—' }}</dd>
                        <dt class="col-sm-5">Estado</dt>
                        <dd class="col-sm-7">{{ $servicio->estado }}</dd>
                        <dt class="col-sm-5">Fecha/Hora</dt>
                        <dd class="col-sm-7">{{ $servicio->fecha_hora }}</dd>
                        <dt class="col-sm-5">Hora Salida</dt>
                        <dd class="col-sm-7">{{ $servicio->hora_salida ?? '—' }}</dd>
                        <dt class="col-sm-5">Costo Total</dt>
                        <dd class="col-sm-7">${{ number_format($servicio->costo_total, 2) }}</dd>
                        <dt class="col-sm-5">Ambulancia</dt>
                        <dd class="col-sm-7">{{ $servicio->ambulancia->placa ?? '—' }}</dd>
                        <dt class="col-sm-5">Operador</dt>
                        <dd class="col-sm-7">{{ $servicio->operador->usuario->nombre ?? '—' }}</dd>
                        <dt class="col-sm-5">Cliente</dt>
                        <dd class="col-sm-7">{{ $servicio->cliente->usuario->nombre ?? '—' }}</dd>
                        <dt class="col-sm-5">Observaciones</dt>
                        <dd class="col-sm-7">{{ $servicio->observaciones ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Pacientes</h6></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($servicio->pacientes as $paciente)
                            <li class="list-group-item">{{ $paciente->nombre }} {{ $paciente->ap_paterno }}</li>
                        @empty
                            <li class="list-group-item text-muted">Sin pacientes</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Paramédicos</h6></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($servicio->paramedicos as $paramedico)
                            <li class="list-group-item">{{ $paramedico->usuario->nombre ?? $paramedico->id_usuario }}</li>
                        @empty
                            <li class="list-group-item text-muted">Sin paramédicos</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Insumos</h6></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($servicio->insumos as $insumo)
                            <li class="list-group-item">{{ $insumo->nombre_insumo }}</li>
                        @empty
                            <li class="list-group-item text-muted">Sin insumos</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
