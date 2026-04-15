<x-layouts.app :title="'Detalle Insumo'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Insumo #{{ $insumo->id_insumo }}</h5>
            <div>
                <a href="{{ route('insumos.edit', $insumo) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('insumos.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $insumo->nombre_insumo }}</dd>
                <dt class="col-sm-4">Costo por unidad</dt>
                <dd class="col-sm-8">${{ number_format($insumo->costo_unidad, 2) }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
