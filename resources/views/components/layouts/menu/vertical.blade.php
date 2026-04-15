<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="overflow-y: auto; height: 100vh;">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link"><x-app-logo /></a>
  </div>

  <div class="menu-inner-shadow"></div>

  @php $esEmpleado = auth()->check() && auth()->user()->loadMissing(['operador','paramedico','cliente'])->esEmpleado(); @endphp

  <ul class="menu-inner py-1">

    @if($esEmpleado)
    {{-- menú empleados --}}

    <li class="menu-item {{ request()->is('mi-panel') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('empleado.mi-panel') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-calendar-check"></i>
        <div class="text-truncate">Mi Panel</div>
      </a>
    </li>

    @else
    {{-- menú administradores --}}

    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('dashboard') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div class="text-truncate">{{ __('Dashboard') }}</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Operaciones</span>
    </li>

    <li class="menu-item {{ request()->is('servicios*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-ambulance"></i>
        <div class="text-truncate">Servicios</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('servicios') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('servicios.index') }}" wire:navigate>Todos los Servicios</a>
        </li>
        <li class="menu-item {{ request()->is('eventos*') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('eventos.index') }}" wire:navigate>Eventos</a>
        </li>
      </ul>
    </li>

    <li class="menu-item {{ request()->is('pacientes*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div class="text-truncate">Pacientes</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('pacientes') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('pacientes.index') }}" wire:navigate>Pacientes</a>
        </li>
        <li class="menu-item {{ request()->is('padecimientos*') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('padecimientos.index') }}" wire:navigate>Padecimientos</a>
        </li>
      </ul>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Personal</span>
    </li>

    <li class="menu-item {{ request()->is('operadores*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('operadores.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-id-card"></i>
        <div class="text-truncate">Operadores</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('paramedicos*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('paramedicos.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div class="text-truncate">Paramédicos</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('clientes*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('clientes.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div class="text-truncate">Clientes</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Flota</span>
    </li>

    <li class="menu-item {{ request()->is('ambulancias*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-car"></i>
        <div class="text-truncate">Ambulancias</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('ambulancias') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('ambulancias.index') }}" wire:navigate>Ambulancias</a>
        </li>
        <li class="menu-item {{ request()->is('tipos-ambulancia*') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('tipos-ambulancia.index') }}" wire:navigate>Tipos de Ambulancia</a>
        </li>
      </ul>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Catálogos</span>
    </li>

    <li class="menu-item {{ request()->is('insumos*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('insumos.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div class="text-truncate">Insumos</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('empresas*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('empresas.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-buildings"></i>
        <div class="text-truncate">Empresas</div>
      </a>
    </li>


    <li class="menu-item {{ request()->is('cotizaciones*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('cotizaciones.index') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-calculator"></i>
        <div class="text-truncate">Cotizaciones</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Cuenta</span>
    </li>

    <li class="menu-item {{ request()->is('settings/*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div class="text-truncate">{{ __('Settings') }}</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('settings.profile') }}" wire:navigate>{{ __('Profile') }}</a>
        </li>
        <li class="menu-item {{ request()->routeIs('settings.password') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('settings.password') }}" wire:navigate>{{ __('Password') }}</a>
        </li>
      </ul>
    </li>

    @endif

  </ul>
</aside>
<!-- / Menu -->

<script>
  document.querySelectorAll('.menu-toggle').forEach(function(menuToggle) {
    menuToggle.addEventListener('click', function() {
      const menuItem = menuToggle.closest('.menu-item');
      menuItem.classList.toggle('open');
    });
  });
</script>
