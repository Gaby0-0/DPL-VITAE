<x-layouts.app :title="'Detalle Ambulancia'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ambulancia: {{ $ambulancia->placa }}</h5>
            <div>
                <a href="{{ route('ambulancias.edit', $ambulancia) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('ambulancias.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8">{{ $ambulancia->id_ambulancia }}</dd>
                <dt class="col-sm-4">Placa</dt>
                <dd class="col-sm-8">{{ $ambulancia->placa }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $ambulancia->estado }}</dd>
                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">{{ $ambulancia->tipo->nombre_tipo ?? '—' }}</dd>

            </dl>
        </div>
    </div>
</x-layouts.app>
