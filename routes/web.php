<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\AmbulanciasController;
use App\Http\Controllers\PadecimientosController;

//Ruta que irá por default cuando un cliente abra la pagina
Route::get('/', function () {
    return view('inicio');
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


//CRUD PADECIMIENTOS
Route::get('/admin/padecimientos', [PadecimientosController::class, 'index'])->name('padecimientos.index');

//CRUD SERVICIOS


//CRUD INSUMOS
Route::get('/admin/insumos', [InsumosController::class, 'index'])->name('insumos.index');
Route::get('/admin/insumos/create', [InsumosController::class, 'create'])->name('insumos.create');
Route::post('/admin/insumos', [InsumosController::class, 'store'])->name('insumos.store');
Route::get('/admin/insumos/{id}/edit', [InsumosController::class, 'edit'])->name('insumos.edit');
Route::put('/admin/insumos/{id}', [InsumosController::class, 'update'])->name('insumos.update');
Route::delete('/admin/insumos/{id}', [InsumosController::class, 'destroy'])->name('insumos.destroy');