{{-- ════════════════════════════════════════════════════════════
  tabla_servicios.blade.php  (resources/views/reportes/partials/)
════════════════════════════════════════════════════════════ --}}
<table class="preview-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Ambulancia</th>
            <th>Operador</th>
            <th>Cliente</th>
            <th>Costo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $s)
        <tr>
            <td>{{ $s->id_servicio }}</td>
            <td>{{ \Carbon\Carbon::parse($s->fecha_hora)->format('d/m/Y H:i') }}</td>
            <td>{{ $s->tipo ?? '—' }}</td>
            <td><span class="badge-estado badge-{{ strtolower($s->estado) }}">{{ $s->estado }}</span></td>
            <td>{{ $s->placa }}</td>
            <td>{{ $s->operador ?? '—' }}</td>
            <td>{{ $s->cliente }}</td>
            <td>${{ number_format($s->costo_total, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros para los filtros seleccionados.</td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr style="background:#f5f5f9;font-weight:700;">
            <td colspan="7" style="text-align:right;padding:.65rem 1rem;color:#566a7f;">
                {{ $datos->count() }} servicios — Total:
            </td>
            <td style="padding:.65rem 1rem;color:#566a7f;">${{ number_format($datos->sum('costo_total'), 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>