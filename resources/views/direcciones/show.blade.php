<x-layouts.app :title="'Detalle Dirección'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dirección #{{ $direccion->id_direccion }}</h5>
            <div>
                <a href="{{ route('direcciones.edit', $direccion) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('direcciones.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Calle</dt>
                <dd class="col-sm-8">{{ $direccion->nombre_calle }}</dd>
                <dt class="col-sm-4">Número exterior</dt>
                <dd class="col-sm-8">{{ $direccion->n_exterior }}</dd>
                <dt class="col-sm-4">Número interior</dt>
                <dd class="col-sm-8">{{ $direccion->n_interior ?? '—' }}</dd>
                <dt class="col-sm-4">Colonia</dt>
                <dd class="col-sm-8">{{ $direccion->colonia->nombre_colonia ?? '—' }}</dd>
                <dt class="col-sm-4">C.P.</dt>
                <dd class="col-sm-8">{{ $direccion->colonia->codigo_postal ?? '—' }}</dd>
                <dt class="col-sm-4">Municipio</dt>
                <dd class="col-sm-8">{{ $direccion->colonia->municipio->nombre_municipio ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
