<table class="preview-table">
    <thead>
        <tr><th>#</th><th>Paciente</th><th>Sexo</th><th>Peso</th><th>Servicio</th><th>Padecimientos</th></tr>
    </thead>
    <tbody>
        @forelse($datos as $p)
        <tr>
            <td>{{ $p->id_paciente }}</td>
            <td>{{ $p->nombre }} {{ $p->ap_paterno }}</td>
            <td>{{ $p->sexo ?? '—' }}</td>
            <td>{{ $p->peso ? $p->peso.' kg' : '—' }}</td>
            <td>#{{ $p->id_servicio }}</td>
            <td>{{ $p->padecimientos ?: '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>