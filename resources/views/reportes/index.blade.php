<x-layouts.app :title="'Reportes'">

<style>
    .rep-wrapper        { padding: 2rem; max-width: 1300px; margin: 0 auto; }
    .rep-header         { display:flex; align-items:center; gap:1rem; margin-bottom:2rem; }
    .rep-header h1      { font-size:1.75rem; font-weight:700; color:#566a7f; margin:0; }

    .rep-grid           { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr));
                          gap:1.25rem; margin-bottom:2.5rem; }

    .rep-card           { background:#fff; border-radius:14px;
                          box-shadow:0 2px 12px rgba(86,106,127,.08);
                          border:2px solid transparent; cursor:pointer; transition:.2s; overflow:hidden; }
    .rep-card:hover     { border-color:#696cff; transform:translateY(-3px);
                          box-shadow:0 8px 24px rgba(105,108,255,.18); }
    .rep-card.active    { border-color:#696cff; }

    .rep-card-top       { background:linear-gradient(135deg,#696cff 0%,#5a5db5 100%);
                          padding:1.25rem 1.5rem; display:flex; align-items:center; gap:.75rem; }
    .rep-card-top svg   { width:28px; height:28px; stroke:#fff; fill:none; flex-shrink:0; }
    .rep-card-top span  { font-size:1rem; font-weight:700; color:#fff; }

    .rep-card-body      { padding:1rem 1.5rem 1.25rem; }
    .rep-card-body p    { font-size:.82rem; color:#a1acb8; margin:0 0 .85rem; line-height:1.5; }
    .rep-card-body .tag { display:inline-block; background:#f5f5f9; border-radius:6px;
                          font-size:.72rem; color:#697a8d; padding:.2rem .55rem; margin:.15rem; }

    .rep-filtros        { background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(86,106,127,.08);
                          padding:1.5rem; margin-bottom:2rem; display:none; }
    .rep-filtros.show   { display:block; }
    .rep-filtros h3     { font-size:1rem; font-weight:700; color:#566a7f; margin:0 0 1.25rem;
                          display:flex; align-items:center; gap:.5rem; }

    .filtros-row        { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem; }

    .form-group         { display:flex; flex-direction:column; gap:.35rem; }
    .form-group label   { font-size:.78rem; font-weight:600; color:#697a8d;
                          text-transform:uppercase; letter-spacing:.04em; }
    .form-group select,
    .form-group input   { border:1.5px solid #d9dee3; border-radius:8px; padding:.55rem .75rem;
                          font-size:.88rem; color:#566a7f; background:#f5f5f9;
                          outline:none; transition:.15s; }
    .form-group select:focus,
    .form-group input:focus { border-color:#696cff; background:#fff;
                               box-shadow:0 0 0 3px rgba(105,108,255,.12); }

    .btn-generar        { margin-top:1.5rem; display:flex; gap:.75rem; flex-wrap:wrap; }

    .btn-rep-primary    { background:linear-gradient(135deg,#696cff,#5a5db5);
                          color:#fff; border:none; border-radius:10px; padding:.65rem 1.5rem;
                          font-size:.9rem; font-weight:600; cursor:pointer; display:flex;
                          align-items:center; gap:.5rem; transition:.2s;
                          box-shadow:0 3px 10px rgba(105,108,255,.35); }
    .btn-rep-primary:hover  { transform:translateY(-1px); box-shadow:0 6px 18px rgba(105,108,255,.45); }
    .btn-rep-primary svg    { width:18px; height:18px; stroke:currentColor; fill:none; }

    .btn-rep-secondary  { background:#fff; color:#696cff; border:1.5px solid #696cff;
                          border-radius:10px; padding:.65rem 1.5rem; font-size:.9rem; font-weight:600;
                          cursor:pointer; display:flex; align-items:center; gap:.5rem; transition:.2s; }
    .btn-rep-secondary:hover{ background:#f5f5f9; }
    .btn-rep-secondary svg  { width:18px; height:18px; stroke:currentColor; fill:none; }

    .rep-preview        { background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(86,106,127,.08);
                          overflow:hidden; display:none; }
    .rep-preview.show   { display:block; }
    .rep-preview-head   { background:linear-gradient(90deg,#696cff,#5a5db5);
                          padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center; }
    .rep-preview-head h4{ color:#fff; margin:0; font-size:1rem; }
    .rep-preview-head small{ color:rgba(255,255,255,.75); font-size:.78rem; }

    .preview-table      { width:100%; border-collapse:collapse; font-size:.85rem; }
    .preview-table th   { background:#f5f5f9; padding:.65rem 1rem; text-align:left;
                          font-size:.75rem; font-weight:700; color:#697a8d; text-transform:uppercase;
                          letter-spacing:.04em; border-bottom:2px solid #d9dee3; }
    .preview-table td   { padding:.65rem 1rem; border-bottom:1px solid #d9dee3; color:#566a7f; }
    .preview-table tr:last-child td { border-bottom:none; }
    .preview-table tr:hover td      { background:#f5f5f9; }

    .badge-estado       { display:inline-block; font-size:.72rem; font-weight:600;
                          padding:.2rem .6rem; border-radius:999px; }
    .badge-activo       { background:#e8f8ee; color:#28a745; }
    .badge-pendiente    { background:#fff8e1; color:#e0a800; }
    .badge-cancelado    { background:#fce8e8; color:#dc3545; }
    .badge-aceptada     { background:#e7f3ff; color:#0d6efd; }
    .badge-aprobado     { background:#e8f8ee; color:#28a745; }
    .badge-disponible   { background:#e8f8ee; color:#28a745; }

    .spinner            { width:40px; height:40px; border:4px solid #d9dee3;
                          border-top-color:#696cff; border-radius:50%;
                          animation:rep-spin .7s linear infinite; margin:2rem auto; }
    @keyframes rep-spin { to{ transform:rotate(360deg); } }

    .preview-empty      { text-align:center; padding:3rem; color:#a1acb8; font-size:.9rem; }

    @media(max-width:640px){
        .rep-wrapper  { padding:1rem; }
        .filtros-row  { grid-template-columns:1fr; }
        .btn-generar  { flex-direction:column; }
    }
</style>

<div class="rep-wrapper">

    {{-- Encabezado --}}
    <div class="rep-header">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none"
             stroke="#696cff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
        <div>
            <h1>Centro de Reportes</h1>
            <p style="margin:0;font-size:.85rem;color:#a1acb8;">Genera y descarga reportes en PDF de cualquier módulo</p>
        </div>
    </div>

    {{-- Tipos de reporte --}}
    <div class="rep-grid">

        <div class="rep-card" onclick="seleccionarReporte('servicios', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <span>Servicios</span>
            </div>
            <div class="rep-card-body">
                <p>Listado de todos los servicios con ambulancias, operadores, clientes y costos.</p>
                <span class="tag">Fechas</span><span class="tag">Estado</span><span class="tag">Tipo</span>
            </div>
        </div>

        <div class="rep-card" onclick="seleccionarReporte('cotizaciones', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span>Cotizaciones</span>
            </div>
            <div class="rep-card-body">
                <p>Reporte de solicitudes/cotizaciones con estado, costos y datos del cliente.</p>
                <span class="tag">Estado</span><span class="tag">Fechas</span><span class="tag">Tipo servicio</span>
            </div>
        </div>

        <div class="rep-card" onclick="seleccionarReporte('personal', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>Personal</span>
            </div>
            <div class="rep-card-body">
                <p>Listado de operadores y paramédicos con salario/hora y estado.</p>
                <span class="tag">Rol</span><span class="tag">Salario</span>
            </div>
        </div>

        <div class="rep-card" onclick="seleccionarReporte('ambulancias', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13" rx="1"/>
                    <path d="M16 8h4l3 5v3h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                <span>Ambulancias</span>
            </div>
            <div class="rep-card-body">
                <p>Inventario de ambulancias con tipo, placa, estado y costo base.</p>
                <span class="tag">Tipo</span><span class="tag">Estado</span>
            </div>
        </div>

        <div class="rep-card" onclick="seleccionarReporte('pacientes', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Pacientes</span>
            </div>
            <div class="rep-card-body">
                <p>Registro de pacientes atendidos con padecimientos, datos y servicio vinculado.</p>
                <span class="tag">Sexo</span><span class="tag">Padecimientos</span>
            </div>
        </div>

        <div class="rep-card" onclick="seleccionarReporte('ingresos', this)">
            <div class="rep-card-top">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                <span>Ingresos</span>
            </div>
            <div class="rep-card-body">
                <p>Resumen financiero de ingresos por servicios completados y cotizaciones aprobadas.</p>
                <span class="tag">Por mes</span><span class="tag">Por tipo</span>
            </div>
        </div>

    </div>

    {{-- Filtros --}}
    <div class="rep-filtros" id="seccionFiltros">
        <h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#696cff"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            Filtros — <span id="tituloReporte" style="color:#696cff;"></span>
        </h3>

        <div class="filtros-row" id="filtrosDinamicos"></div>

        <div class="btn-generar">
            <button class="btn-rep-primary" onclick="previsualizarReporte()">
                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Vista previa
            </button>
            <button class="btn-rep-primary" onclick="generarPDF()">
                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar PDF
            </button>
            <button class="btn-rep-secondary" onclick="limpiarReporte()">
                <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.96"/></svg>
                Limpiar
            </button>
        </div>
    </div>

    {{-- Vista previa --}}
    <div class="rep-preview" id="seccionPreview">
        <div class="rep-preview-head">
            <h4 id="previewTitulo">Vista previa</h4>
            <small id="previewSubtitulo"></small>
        </div>
        <div id="previewContenido" style="overflow-x:auto;">
            <div class="preview-empty">Selecciona un tipo de reporte y aplica los filtros.</div>
        </div>
    </div>

</div>

<script>
let reporteActual = null;

const CONFIGS = {
    servicios: {
        titulo: 'Servicios',
        filtros: [
            { name:'fecha_inicio',  label:'Fecha inicio', type:'date' },
            { name:'fecha_fin',     label:'Fecha fin',    type:'date' },
            { name:'estado',        label:'Estado',       type:'select', opciones:['Todos','Activo','Completado','Cancelado'] },
            { name:'tipo_servicio', label:'Tipo',         type:'select', opciones:['Todos','Traslado','Evento'] },
        ]
    },
    cotizaciones: {
        titulo: 'Cotizaciones',
        filtros: [
            { name:'fecha_inicio',  label:'Fecha inicio',  type:'date' },
            { name:'fecha_fin',     label:'Fecha fin',     type:'date' },
            { name:'estado',        label:'Estado',        type:'select', opciones:['Todos','Pendiente','Aceptada','Aprobado','Rechazada'] },
            { name:'tipo_servicio', label:'Tipo servicio', type:'select', opciones:['Todos','Traslado','Evento'] },
        ]
    },
    personal: {
        titulo: 'Personal',
        filtros: [
            { name:'rol', label:'Rol', type:'select', opciones:['Todos','Operadores','Paramédicos'] },
        ]
    },
    ambulancias: {
        titulo: 'Ambulancias',
        filtros: [
            { name:'tipo_amb', label:'Tipo',   type:'select', opciones:['Todos','Básica','Avanzada'] },
            { name:'estado',   label:'Estado', type:'select', opciones:['Todos','Disponible','En servicio','Mantenimiento'] },
        ]
    },
    pacientes: {
        titulo: 'Pacientes',
        filtros: [
            { name:'fecha_inicio', label:'Fecha inicio', type:'date' },
            { name:'fecha_fin',    label:'Fecha fin',    type:'date' },
        ]
    },
    ingresos: {
        titulo: 'Ingresos',
        filtros: [
            { name:'fecha_inicio', label:'Fecha inicio', type:'date' },
            { name:'fecha_fin',    label:'Fecha fin',    type:'date' },
            { name:'agrupar',      label:'Agrupar por',  type:'select', opciones:['Mes','Tipo de servicio','Ambulancia'] },
        ]
    },
};

function seleccionarReporte(tipo, card) {
    reporteActual = tipo;
    document.querySelectorAll('.rep-card').forEach(c => c.classList.remove('active'));
    card.classList.add('active');

    const cfg = CONFIGS[tipo];
    document.getElementById('tituloReporte').textContent = cfg.titulo;

    const cont = document.getElementById('filtrosDinamicos');
    cont.innerHTML = cfg.filtros.map(f => {
        if (f.type === 'date') return `
            <div class="form-group">
                <label>${f.label}</label>
                <input type="date" name="${f.name}" id="f_${f.name}">
            </div>`;
        if (f.type === 'select') return `
            <div class="form-group">
                <label>${f.label}</label>
                <select name="${f.name}" id="f_${f.name}">
                    ${f.opciones.map(o => `<option value="${o === 'Todos' ? '' : o}">${o}</option>`).join('')}
                </select>
            </div>`;
        return '';
    }).join('');

    document.getElementById('seccionFiltros').classList.add('show');
    document.getElementById('seccionPreview').classList.remove('show');
    document.getElementById('previewContenido').innerHTML =
        '<div class="preview-empty">Haz clic en <b>Vista previa</b> para ver los datos.</div>';

    document.getElementById('seccionFiltros').scrollIntoView({ behavior:'smooth', block:'start' });
}

function getFiltros() {
    const data = {};
    document.querySelectorAll('#filtrosDinamicos [id^="f_"]').forEach(el => {
        data[el.name] = el.value;
    });
    return data;
}

function previsualizarReporte() {
    if (!reporteActual) return;
    const filtros = getFiltros();
    const preview = document.getElementById('seccionPreview');
    const cont    = document.getElementById('previewContenido');

    preview.classList.add('show');
    cont.innerHTML = '<div class="spinner"></div>';
    document.getElementById('previewTitulo').textContent    = CONFIGS[reporteActual].titulo;
    document.getElementById('previewSubtitulo').textContent = new Date().toLocaleDateString('es-MX', {day:'2-digit', month:'long', year:'numeric'});

    const params = new URLSearchParams({ tipo: reporteActual, ...filtros });

    fetch(`{{ route('reportes.preview') }}?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { cont.innerHTML = data.html; })
    .catch(() => {
        cont.innerHTML = '<div class="preview-empty" style="color:#dc3545;">Error al cargar la vista previa.</div>';
    });

    preview.scrollIntoView({ behavior:'smooth', block:'start' });
}

function generarPDF() {
    if (!reporteActual) { alert('Selecciona un tipo de reporte primero.'); return; }
    const filtros = getFiltros();
    const params  = new URLSearchParams({ tipo: reporteActual, ...filtros });
    window.location.href = `{{ route('reportes.pdf') }}?${params}`;
}

function limpiarReporte() {
    reporteActual = null;
    document.querySelectorAll('.rep-card').forEach(c => c.classList.remove('active'));
    document.getElementById('seccionFiltros').classList.remove('show');
    document.getElementById('seccionPreview').classList.remove('show');
}
</script>

</x-layouts.app>