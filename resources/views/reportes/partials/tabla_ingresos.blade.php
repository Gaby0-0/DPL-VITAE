<table class="preview-table">
    <thead>
        <tr><th>Agrupación</th><th>Cantidad</th><th>Total</th><th>Promedio</th></tr>
    </thead>
    <tbody>
        @forelse($datos as $row)
        <tr>
            <td><strong>{{ $row->agrupacion }}</strong></td>
            <td>{{ $row->cantidad }}</td>
            <td>${{ number_format($row->total, 2) }}</td>
            <td>${{ $row->cantidad ? number_format($row->total / $row->cantidad, 2) : '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros.</td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr style="background:#f5f5f9;font-weight:700;">
            <td style="padding:.65rem 1rem;">TOTAL</td>
            <td style="padding:.65rem 1rem;">{{ $datos->sum('cantidad') }}</td>
            <td style="padding:.65rem 1rem;">${{ number_format($datos->sum('total'), 2) }}</td>
            <td style="padding:.65rem 1rem;">—</td>
        </tr>
    </tfoot>
    @endif
</table>