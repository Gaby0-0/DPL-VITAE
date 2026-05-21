@section('title', 'Cotizaciones')
<x-layouts.app :title="'Cotizaciones'">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Boton nueva cotización -->
<div class="col-md-2 d-flex gap-2">
    <a href="{{ route('admin.cotizaciones.create') }}">
        <button class="btn btn-primary w-100" title="Generar Cotizacion">
            <i class="bx bx-plus me-1"></i> Generar Cotización
        </button>
    </a>
</div>


<!-- Filtro estados -->
<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.04), rgba(59, 130, 246, 0.04));">
  <div class="card-body p-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

    <div class="col-md-2">
<label class="form-label text-primary fw-bold" style="font-size: 0.8rem; text-transform: uppercase;"><i class="bx bx-status me-1"></i>Estado</label>
    <select name="estado" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach ($estados as $value => $label)
                <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
</div>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Solicitudes de Cotización</h5>
            <span class="badge bg-primary">{{ $cotizaciones->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Fecha requerida</th>
                        <th>Estado</th>
                        <th>Recibida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->id_cotizacion }}</td>
                        <td>{{ $cotizacion->nombre }}</td>
                        <td>{{ $cotizacion->telefono }}</td>
                        <td>{{ $cotizacion->tipo_servicio }}</td>
                        <td>{{ $cotizacion->fecha_requerida ? \Carbon\Carbon::parse($cotizacion->fecha_requerida)->format('d/m/Y') : '—' }}</td>
                        <td>
                            @php
                                $colorCot = match(true) {
                                    $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'danger',
                                    $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'success',
                                    $cotizacion->estado === 'Pendiente'   => 'warning',
                                    $cotizacion->estado === 'En revisión' => 'info',
                                    $cotizacion->estado === 'Aceptada'    => 'success',
                                    $cotizacion->estado === 'Cancelada'   => 'danger',
                                    default => 'secondary',
                                };
                                $labelCot = match(true) {
                                    $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'declinada'  => 'Declinada',
                                    $cotizacion->estado === 'Aceptada' && $cotizacion->decision_cliente === 'confirmada' => 'Confirmada',
                                    default => $cotizacion->estado,
                                };
                            @endphp
                            <span class="badge bg-label-{{ $colorCot }}">{{ $labelCot }}</span>
                        </td>
                        <td>{{ $cotizacion->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                            <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta cotización?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Sin solicitudes aún</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $cotizaciones->total() }} registros</small>
            {{ $cotizaciones->links() }}
        </div>
    </div>
</x-layouts.app>
