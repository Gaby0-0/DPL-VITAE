<table class="preview-table">
    <thead>
        <tr><th>#</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Salario/hora</th></tr>
    </thead>
    <tbody>
        @forelse($datos as $p)
        <tr>
            <td>{{ $p->id_usuario }}</td>
            <td>{{ $p->nombre }} {{ $p->ap_paterno }}</td>
            <td>{{ $p->email }}</td>
            <td><span class="badge-estado badge-{{ $p->rol === 'Operador' ? 'aceptada' : 'aprobado' }}">{{ $p->rol }}</span></td>
            <td>${{ number_format($p->salario_hora, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:#a1acb8;">Sin registros.</td></tr>
        @endforelse
    </tbody>
</table>