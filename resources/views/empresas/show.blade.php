<x-layouts.app :title="'Detalle Empresa'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $empresa->nombre }}</h5>
            <div>
                <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit me-1"></i>Editar</a>
                <a href="{{ route('empresas.index') }}" class="btn btn-sm btn-secondary ms-1">Volver</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @if($empresa->logo_nombre)
                <div class="col-md-2 text-center">
                    <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="Logo" class="img-fluid" style="max-height:100px;object-fit:contain;">
                    <div class="small text-muted mt-1">Logo</div>
                </div>
                @endif
                @if($empresa->imagen_nombre)
                <div class="col-md-3 text-center">
                    <img src="{{ asset('storage/' . $empresa->imagen_nombre) }}" alt="Imagen" class="img-fluid rounded" style="max-height:100px;object-fit:cover;">
                    <div class="small text-muted mt-1">Imagen</div>
                </div>
                @endif
            </div>
            <dl class="row mt-3">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $empresa->nombre }}</dd>

                <dt class="col-sm-3">Slogan</dt>
                <dd class="col-sm-9">{{ $empresa->slogan ?? '—' }}</dd>

                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $empresa->telefono ?? '—' }}</dd>

                <dt class="col-sm-3">Correo</dt>
                <dd class="col-sm-9">{{ $empresa->correo ?? '—' }}</dd>

                <dt class="col-sm-3">Sitio web</dt>
                <dd class="col-sm-9">{{ $empresa->sitio_web ?? '—' }}</dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9">{{ $empresa->direccion ?? '—' }}</dd>

                <dt class="col-sm-3">Costo por km</dt>
                <dd class="col-sm-9">${{ number_format($empresa->costo_km ?? 0, 2) }}</dd>

                @if($empresa->mision)
                <dt class="col-sm-3">Misión</dt>
                <dd class="col-sm-9">{{ $empresa->mision }}</dd>
                @endif

                @if($empresa->vision)
                <dt class="col-sm-3">Visión</dt>
                <dd class="col-sm-9">{{ $empresa->vision }}</dd>
                @endif

                @if($empresa->valores)
                <dt class="col-sm-3">Valores</dt>
                <dd class="col-sm-9">{{ $empresa->valores }}</dd>
                @endif

                @if($empresa->descripcion)
                <dt class="col-sm-3">Descripción</dt>
                <dd class="col-sm-9">{{ $empresa->descripcion }}</dd>
                @endif
            </dl>
        </div>
    </div>
</x-layouts.app>
