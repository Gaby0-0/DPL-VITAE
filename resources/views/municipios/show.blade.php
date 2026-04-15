<x-layouts.app :title="'Detalle Municipio'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Municipio #{{ $municipio->id_municipio }}</h5>
            <div>
                <a href="{{ route('municipios.edit', $municipio) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('municipios.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $municipio->nombre_municipio }}</dd>
                <dt class="col-sm-4">Colonias registradas</dt>
                <dd class="col-sm-8">{{ $municipio->colonias->count() }}</dd>
            </dl>
            @if($municipio->colonias->isNotEmpty())
            <h6 class="mt-3">Colonias</h6>
            <ul class="list-group list-group-flush">
                @foreach($municipio->colonias as $colonia)
                <li class="list-group-item py-1">{{ $colonia->nombre_colonia }} — CP {{ $colonia->codigo_postal }}</li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
