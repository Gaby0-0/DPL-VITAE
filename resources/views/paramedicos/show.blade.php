<x-layouts.app :title="'Detalle Paramédico'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Paramédico: {{ $paramedico->usuario->nombre ?? $paramedico->id_usuario }}</h5>
            <div>
                <a href="{{ route('paramedicos.edit', $paramedico) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('paramedicos.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">ID Usuario</dt>
                <dd class="col-sm-8">{{ $paramedico->id_usuario }}</dd>
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $paramedico->usuario->nombre ?? '—' }}</dd>
                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $paramedico->usuario->email ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-layouts.app>
