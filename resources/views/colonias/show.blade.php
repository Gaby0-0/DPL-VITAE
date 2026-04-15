<x-layouts.app :title="'Detalle Colonia'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Colonia #{{ $colonia->id_colonia }}</h5>
            <div>
                <a href="{{ route('colonias.edit', $colonia) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('colonias.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $colonia->nombre_colonia }}</dd>
                <dt class="col-sm-4">Código Postal</dt>
                <dd class="col-sm-8">{{ $colonia->codigo_postal }}</dd>
                <dt class="col-sm-4">Municipio</dt>
                <dd class="col-sm-8">{{ $colonia->municipio->nombre_municipio ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
