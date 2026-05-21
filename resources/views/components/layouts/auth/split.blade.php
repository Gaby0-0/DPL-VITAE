<div class="authentication-wrapper authentication-cover">
  <div class="authentication-inner row m-0">

    <!-- Left -->
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
      <div class="w-100 d-flex justify-content-center">
        <div>

          <!-- Logo -->
          <a href="{{ url('/') }}" class="app-brand auth-cover-brand gap-2">
            <x-app-logo />
          </a>

          <!-- Imagen -->
          <img
            src="{{ asset('assets/img/illustrations/ambulance-login.png') }}"
            class="img-fluid"
            alt="Sistema de ambulancias"
            width="700"
          />

        </div>
      </div>
    </div>
    <!-- /Left -->

    <!-- Right -->
    <div class="card col-12 col-lg-5 col-xl-4">
      <div class="d-flex align-items-center authentication-bg p-sm-12 p-6 h-100">
        <div class="w-px-400 mx-auto mt-sm-12 mt-8">

          {{ $slot }}

        </div>
      </div>
    </div>
    <!-- /Right -->

  </div>
</div>