@php
    $user        = auth()->user()?->loadMissing(['operador','paramedico','cliente']);
    $esEmpleado  = $user && $user->esEmpleado();
    $iniciales   = $user ? strtoupper(mb_substr($user->nombre,0,1)).strtoupper(mb_substr($user->ap_paterno,0,1)) : '';
    $ambulancias = ($user && $user->operador)
        ? \App\Models\Servicio::where('id_operador', $user->operador->id_usuario)
            ->where('estado', 'Activo')
            ->with('ambulancia')
            ->get()
            ->pluck('ambulancia')
            ->filter()
        : collect();
@endphp

<nav
  class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
  id="layout-navbar">

  <div class="navbar-nav-right d-flex align-items-center w-100 justify-content-between" id="navbar-collapse">

    {{-- Hamburguesa visible solo en móvil (sidebar oculto en pantallas pequeñas) --}}
    <button id="navbar-hamburger-btn"
            class="d-xl-none btn btn-sm p-1 me-2 navbar-hamburger-btn"
            aria-label="Abrir menú">
      <span class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </button>

    @if($esEmpleado)
    {{-- nombre del empleado --}}
    <span class="fw-semibold text-muted small">
      <i class="bx bx-calendar-check me-1"></i>
      {{ $user->nombre }} {{ $user->ap_paterno }}
      &nbsp;·&nbsp;
      @if($user->operador) Operador @else Paramédico @endif
    </span>
    @else
    {{-- buscador solo para admin --}}
    <div class="navbar-nav align-items-center me-auto">
      <div class="nav-item d-flex align-items-center position-relative" id="busqueda-global">
        <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
        <input
          type="text"
          id="navbar-busqueda"
          class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
          placeholder="Buscar..."
          aria-label="Buscar..."
          autocomplete="off" />
        <div id="busqueda-dropdown"
             class="d-none position-absolute bg-white border shadow-lg rounded-2"
             style="top:calc(100% + 8px);left:0;min-width:320px;max-width:420px;z-index:9999;max-height:420px;overflow-y:auto;">
        </div>
      </div>
    </div>
    <style>
      .busqueda-item:hover { background:#f0f0ff; }
      .busqueda-grupo-header { font-size:.68rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; color:#8592a3; background:#f8f8ff; padding:.35rem .85rem; }
    </style>
    <script>
    (function () {
      document.addEventListener('DOMContentLoaded', function () {
        var input    = document.getElementById('navbar-busqueda');
        var dropdown = document.getElementById('busqueda-dropdown');
        var wrap     = document.getElementById('busqueda-global');
        if (!input || !dropdown) return;

        var timer = null;

        function cerrar() {
          dropdown.classList.add('d-none');
          dropdown.innerHTML = '';
        }

        function renderResultados(data) {
          if (!data.length) {
            dropdown.innerHTML = '<div class="p-3 text-muted small text-center">Sin resultados</div>';
            dropdown.classList.remove('d-none');
            return;
          }
          var grupos = {};
          data.forEach(function (r) {
            if (!grupos[r.grupo]) grupos[r.grupo] = [];
            grupos[r.grupo].push(r);
          });
          var html = '';
          Object.keys(grupos).forEach(function (grupo) {
            html += '<div class="busqueda-grupo-header">' + grupo + '</div>';
            grupos[grupo].forEach(function (r) {
              html += '<a href="' + r.url + '" class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-dark busqueda-item">'
                    + '<i class="bx ' + r.icono + ' text-primary" style="font-size:1.1rem;flex-shrink:0;"></i>'
                    + '<div style="min-width:0;">'
                    + '<div class="fw-semibold text-truncate" style="font-size:.84rem;">' + r.label + '</div>'
                    + (r.sub ? '<div class="text-muted text-truncate" style="font-size:.74rem;">' + r.sub + '</div>' : '')
                    + '</div>'
                    + '</a>';
            });
          });
          dropdown.innerHTML = html;
          dropdown.classList.remove('d-none');
        }

        input.addEventListener('input', function () {
          clearTimeout(timer);
          var q = input.value.trim();
          if (q.length < 2) { cerrar(); return; }
          timer = setTimeout(function () {
            fetch('/buscar?q=' + encodeURIComponent(q), {
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(renderResultados)
            .catch(cerrar);
          }, 300);
        });

        document.addEventListener('click', function (e) {
          if (!wrap.contains(e.target)) cerrar();
        });

        input.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') { cerrar(); input.blur(); }
        });
      });
    })();
    </script>
    @endif

    <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

      {{-- dropdown usuario --}}
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        @if(Auth::check())
          <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="perfil-initials" style="width:40px;height:40px;font-size:.9rem;">{{ $iniciales }}</div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <button class="navbar-perfil-btn px-3 py-2"
                      type="button"
                      data-bs-toggle="modal"
                      data-bs-target="#modal-editar-perfil"
                      data-bs-dismiss="dropdown">
                <div class="d-flex align-items-center gap-3">
                  <div class="perfil-initials">{{ $iniciales }}</div>
                  <div class="flex-grow-1 min-width-0">
                    <div class="fw-bold text-truncate" style="font-size:.93rem;">
                      {{ $user->nombre }} {{ $user->ap_paterno }} {{ $user->ap_materno }}
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                      @if($esEmpleado)
                        @if($user->operador)
                          <span class="role-chip bg-label-primary"><i class="bx bx-id-card"></i>Operador</span>
                          @foreach($ambulancias as $amb)
                            <span class="badge bg-label-secondary" style="font-size:.7rem;">
                              <i class="bx bx-ambulance me-1"></i>{{ $amb->placa }}
                            </span>
                          @endforeach
                        @else
                          <span class="role-chip bg-label-success"><i class="bx bx-plus-medical"></i>Paramédico</span>
                        @endif
                      @elseif($user->esCliente())
                        <span class="role-chip bg-label-warning"><i class="bx bx-user"></i>Cliente</span>
                      @else
                        <span class="role-chip bg-label-danger"><i class="bx bx-shield"></i>Admin</span>
                      @endif
                      <span class="text-muted" style="font-size:.75rem;">
                        <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                      </span>
                    </div>
                  </div>
                  <div class="flex-shrink-0 text-muted">
                    <i class="bx bx-edit-alt fs-5"></i>
                  </div>
                </div>
              </button>
            </li>
            <li><div class="dropdown-divider my-1"></div></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">
                  <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Cerrar sesión</span>
                </button>
              </form>
            </li>
          </ul>
        @endif
      </li>

    </ul>
  </div>
</nav>
