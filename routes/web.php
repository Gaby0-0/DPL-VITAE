<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\AmbulanciasController;
use App\Http\Controllers\PadecimientosController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\TipoAmbulanciaController;
use App\Http\Controllers\EmpresaController;

//Ruta que irá por default cuando un cliente abra la pagina
Route::get('/', function () {
    $empresa = \App\Models\Empresa::first();
    return view('public.inicio', compact('empresa'));
});

//FORM COTIZACION
Route::get('/cotizacion', function () {
    return view('public.cotizacion');
});

//DASHBOARD ADMIN
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

//CRUD PERSONAL
Route::get('/admin/personal', [PersonalController::class, 'index'])->name('personal.index');
Route::get('/admin/personal/create', [PersonalController::class, 'create'])->name('personal.create');
Route::post('/admin/personal', [PersonalController::class, 'store'])->name('personal.store');
Route::get('/admin/personal/{id}/edit', [PersonalController::class, 'edit'])->name('personal.edit');
Route::put('/admin/personal/{id}', [PersonalController::class, 'update'])->name('personal.update');
Route::delete('/admin/personal/{id}', [PersonalController::class, 'destroy'])->name('personal.destroy');

//CRUD AMBULANCIAS
Route::get('/admin/ambulancias', [AmbulanciasController::class, 'index'])->name('ambulancias.index');
Route::get('/admin/ambulancias/create', [AmbulanciasController::class, 'create'])->name('ambulancias.create');
Route::post('/admin/ambulancias', [AmbulanciasController::class, 'store'])->name('ambulancias.store');
Route::get('/admin/ambulancias/{id}/edit', [AmbulanciasController::class, 'edit'])->name('ambulancias.edit');
Route::put('/admin/ambulancias/{id}', [AmbulanciasController::class, 'update'])->name('ambulancias.update');
Route::delete('/admin/ambulancias/{id}', [AmbulanciasController::class, 'destroy'])->name('ambulancias.destroy');

//CRUD TIPO AMBULANCIA
Route::post('/admin/tipo-ambulancia', [TipoAmbulanciaController::class, 'store'])->name('tipo_ambulancia.store');
Route::get('/admin/tipo-ambulancia/{id}/edit', [TipoAmbulanciaController::class, 'edit'])->name('tipo_ambulancia.edit');
Route::put('/admin/tipo-ambulancia/{id}', [TipoAmbulanciaController::class, 'update'])->name('tipo_ambulancia.update');
Route::delete('/admin/tipo-ambulancia/{id}', [TipoAmbulanciaController::class, 'destroy'])->name('tipo_ambulancia.destroy');

//CRUD EMPRESA
Route::get('/admin/empresa', [EmpresaController::class, 'index'])->name('empresa.index');
Route::get('/admin/empresa/create', [EmpresaController::class, 'create'])->name('empresa.create');
Route::post('/admin/empresa', [EmpresaController::class, 'store'])->name('empresa.store');
Route::get('/admin/empresa/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
Route::put('/admin/empresa/{id}', [EmpresaController::class, 'update'])->name('empresa.update');
Route::delete('/admin/empresa/{id}', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
Route::get('/empresa/{id}/logo', [EmpresaController::class, 'logo'])->name('empresa.logo');
Route::get('/empresa/{id}/imagen', [EmpresaController::class, 'imagen'])->name('empresa.imagen');

//CRUD PADECIMIENTOS
Route::get('/admin/padecimientos', [PadecimientosController::class, 'index'])->name('padecimientos.index');
Route::get('/admin/padecimientos/create', [PadecimientosController::class, 'create'])->name('padecimientos.create');
Route::post('/admin/padecimientos', [PadecimientosController::class, 'store'])->name('padecimientos.store');
Route::get('/admin/padecimientos/{id}/edit', [PadecimientosController::class, 'edit'])->name('padecimientos.edit');
Route::put('/admin/padecimientos/{id}', [PadecimientosController::class, 'update'])->name('padecimientos.update');
Route::delete('/admin/padecimientos/{id}', [PadecimientosController::class, 'delete'])->name('padecimientos.destroy');

//CRUD SERVICIOS
Route::get('/admin/servicios', [ServiciosController::class, 'index'])->name('servicios.index');
Route::get('/admin/servicios/create', [ServiciosController::class, 'create'])->name('servicios.create');
Route::post('/admin/servicios', [ServiciosController::class, 'store'])->name('servicios.store');
Route::get('/admin/servicios/{id}/edit', [ServiciosController::class, 'edit'])->name('servicios.edit');
Route::put('/admin/servicios/{id}', [ServiciosController::class, 'update'])->name('servicios.update');
Route::delete('/admin/servicios/{id}', [ServiciosController::class, 'delete'])->name('servicios.destroy');
Route::get('/admin/servicios/{id}', [ServiciosController::class, 'show'])->name('servicios.show');

//CRUD INSUMOS
Route::get('/admin/insumos', [InsumosController::class, 'index'])->name('insumos.index');
Route::get('/admin/insumos/create', [InsumosController::class, 'create'])->name('insumos.create');
Route::post('/admin/insumos', [InsumosController::class, 'store'])->name('insumos.store');
Route::get('/admin/insumos/{id}/edit', [InsumosController::class, 'edit'])->name('insumos.edit');
Route::put('/admin/insumos/{id}', [InsumosController::class, 'update'])->name('insumos.update');
Route::delete('/admin/insumos/{id}', [InsumosController::class, 'destroy'])->name('insumos.destroy');