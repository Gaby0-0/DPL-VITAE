<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Ambulancia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ambulancias = Ambulancia::select('id_ambulancia', 'placa')->get();

        $servicios = Servicio::query()
        ->when($request->tipo, function ($q, $tipo) { //tipo servicio
            $q->where('tipo', $tipo);
        })
        ->when($request->estado, function ($q, $estado) { //estado del servicio
            $q->where('estado', $estado);
        })
        ->when($request->ambulancia, function ($q, $ambulancia) { //por ambulancia (placa)
            $q->where('id_ambulancia', $ambulancia);
        })
        ->when($request->fecha_inicio, function ($q, $fecha) { //filtro por rango de fechas
            $q->whereDate('fecha_hora', '>=', $fecha);
        })
        ->when($request->fecha_fin, function ($q, $fecha) {
            $q->whereDate('fecha_hora', '<=', $fecha . ' 23:59:59');
        })
        ->get();

        $tipos = [
            'Traslado' => 'Traslados',
            'Evento' => 'Eventos',
            'Otro' => 'Otros'
        ];

        /*
        $estados = [
            'Pendiente' => 'Pendiente',
            'En curso' => 'En curso',
            'Completado' => 'Completado',
            'Cancelado' => 'Cancelado'
        ]; 
        */

        $estados = [
            'Activo' => 'Activo',
            'Finalizado' => 'Finalizado',
            'Cancelado' => 'Cancelado',
        ];



        return view('dashboard', compact('servicios', 'tipos', 'estados', 'ambulancias'));
}
}
