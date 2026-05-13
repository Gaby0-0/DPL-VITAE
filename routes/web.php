<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PadecimientoController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ParamedicoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AmbulanciaController;
use App\Http\Controllers\TipoAmbulanciaController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ColoniaController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;

// ── Rutas públicas ──────────────────────────────────────────────────────────
Route::get('/', function () {
    $empresa = \App\Models\Empresa::first();
    return view('welcome', compact('empresa'));
})->name('home');

Route::get('cotizaciones/gracias', [CotizacionController::class, 'gracias'])->name('cotizaciones.gracias');
Route::get('cotizaciones/rastrear', [CotizacionController::class, 'rastrear'])->name('cotizaciones.rastrear');

// ── Portal del empleado ─────────────────────────────────────────────────────
Route::middleware(['auth', 'es.empleado'])->group(function () {
    Route::get('mi-panel', [EmpleadoController::class, 'miPanel'])->name('empleado.mi-panel');
    Route::put('mi-panel/perfil', [EmpleadoController::class, 'actualizarPerfil'])->name('empleado.perfil.update');
});

// ── Portal del cliente ──────────────────────────────────────────────────────
Route::middleware(['auth', 'es.cliente'])->group(function () {
    Route::get('cotizaciones/solicitar',  [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::post('cotizaciones/solicitar', [CotizacionController::class, 'store'])->name('cotizaciones.store');

    Route::get('mis-solicitudes',                           [CotizacionController::class, 'misSolicitudes'])->name('cotizaciones.mis-solicitudes');
    Route::get('mis-solicitudes/{cotizacion}',              [CotizacionController::class, 'miEstado'])->name('cotizaciones.mi-estado');
    Route::post('mis-solicitudes/{cotizacion}/confirmar',   [CotizacionController::class, 'confirmar'])->name('cotizaciones.confirmar');
    Route::post('mis-solicitudes/{cotizacion}/declinar',    [CotizacionController::class, 'declinar'])->name('cotizaciones.declinar');
    Route::get('mis-solicitudes/{cotizacion}/descargar',    [CotizacionController::class, 'descargar'])->name('cotizaciones.descargar');

    // MercadoPago
    Route::get('mis-solicitudes/{cotizacion}/pagar',        [PagoController::class, 'iniciar'])->name('cotizaciones.pago.iniciar');
    Route::get('mis-solicitudes/{cotizacion}/pago/success', [PagoController::class, 'success'])->name('cotizaciones.pago.success');
    Route::get('mis-solicitudes/{cotizacion}/pago/failure', [PagoController::class, 'failure'])->name('cotizaciones.pago.failure');
    Route::get('mis-solicitudes/{cotizacion}/pago/pending', [PagoController::class, 'pending'])->name('cotizaciones.pago.pending');
});

// Webhook MP (público, sin auth ni es.cliente)
Route::post('webhooks/mercadopago', [PagoController::class, 'webhook'])
    ->name('webhooks.mercadopago')
    ->withoutMiddleware(['auth']);

// ── Panel de administración ─────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'es.admin'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');

    Route::resource('servicios',         ServicioController::class);
    Route::resource('eventos',           EventoController::class);
    Route::resource('pacientes',         PacienteController::class);
    Route::resource('padecimientos',     PadecimientoController::class);
    Route::resource('operadores', OperadorController::class)->parameters(['operadores' => 'operador']);
    Route::resource('paramedicos',       ParamedicoController::class);
    Route::resource('clientes',          ClienteController::class);
    Route::resource('ambulancias',       AmbulanciaController::class);
    Route::resource('tipos-ambulancia',  TipoAmbulanciaController::class)
        ->parameters(['tipos-ambulancia' => 'tipoAmbulancia']);
    Route::resource('insumos',           InsumoController::class);
    Route::resource('empresas',          EmpresaController::class);
    Route::resource('municipios',        MunicipioController::class);
    Route::resource('colonias',          ColoniaController::class);
    Route::resource('direcciones',       DireccionController::class);

    Route::get('cotizaciones',                          [CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('cotizaciones/{cotizacion}',             [CotizacionController::class, 'show'])->name('cotizaciones.show');
    Route::put('cotizaciones/{cotizacion}',             [CotizacionController::class, 'update'])->name('cotizaciones.update');
    Route::post('cotizaciones/{cotizacion}/aceptar',    [CotizacionController::class, 'aceptar'])->name('cotizaciones.aceptar');
    Route::post('cotizaciones/{cotizacion}/rechazar',   [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');
    Route::delete('cotizaciones/{cotizacion}',          [CotizacionController::class, 'destroy'])->name('cotizaciones.destroy');
});

require __DIR__ . '/auth.php';
