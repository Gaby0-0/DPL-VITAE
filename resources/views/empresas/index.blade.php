@section('title', 'Empresas')
<x-layouts.app :title="'Empresas'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Empresas</h5>
            <a href="{{ route('empresas.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Nueva
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nombre</th>
                        <th>Slogan</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empresas as $empresa)
                    <tr>
                        <td>
                            @if($empresa->logo_nombre)
                                <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" alt="Logo" style="height:40px;object-fit:contain;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $empresa->nombre }}</td>
                        <td>{{ $empresa->slogan ?? '—' }}</td>
                        <td>{{ $empresa->telefono ?? '—' }}</td>
                        <td>{{ $empresa->correo ?? '—' }}</td>
                        <td>
                            <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                            <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar empresa?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $empresas->total() }} registros</small>
            {{ $empresas->links() }}
        </div>
    </div>
</x-layouts.app>
