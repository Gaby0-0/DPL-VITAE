<table class="preview-table">
    <thead>
        <tr>
            <th>Guía</th><th>Fecha</th><th>Solicitante</th>
            <th>Tipo</th><th>Estado</th><th>Costo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $c)
        <tr>
            <td><strong>{{ $c->numero_guia }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y') }}</td>
            <td>{{ $c->nombre }}</td>
            <td>{{ $c->tipo_servicio }}</td>
            <td><span class="badge-estado badge-{{ strtolower(str_replace(' ','',$c->estado)) }}">{{ $c->estado }}</span></td>
            <td>${{ $c->costo ? number_format($c->costo, 2) : '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros.</td></tr>
        @endforelse
    </tbody>
    @if($datos->count())
    <tfoot>
        <tr style="background:#f5f5f9;font-weight:700;">
            <td colspan="5" style="text-align:right;padding:.65rem 1rem;color:#566a7f;">{{ $datos->count() }} cotizaciones — Total:</td>
            <td style="padding:.65rem 1rem;">${{ number_format($datos->sum('costo'), 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>