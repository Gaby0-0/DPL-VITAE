<table class="preview-table">
    <thead>
        <tr><th>#</th><th>Placa</th><th>Tipo</th><th>Estado</th><th>Costo base</th></tr>
    </thead>
    <tbody>
        @forelse($datos as $a)
        <tr>
            <td>{{ $a->id_ambulancia }}</td>
            <td><strong>{{ $a->placa }}</strong></td>
            <td>{{ $a->nombre_tipo }}</td>
            <td><span class="badge-estado badge-{{ strtolower($a->estado) }}">{{ $a->estado }}</span></td>
            <td>${{ number_format($a->costo_base, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>