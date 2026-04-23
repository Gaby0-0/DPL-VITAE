<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Servicio;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $eventos = Evento::with('servicio')
        //duracion
        ->when($request->duracion_min, function ($q, $duracion) {
            $q->where('duracion', '>=', $duracion);
        })
        ->when($request->duracion_max, function ($q, $duracion) {
            $q->where('duracion', '<=', $duracion);
        })
        //personas
        ->when($request->personas_min, function ($q, $personas) {
            $q->where('personas', '>=', $personas);
        })
        ->when($request->personas_max, function ($q, $personas) {
            $q->where('personas', '<=', $personas);
        })
        ->paginate(8);
        return view('eventos.index', compact('eventos', 'eventos'));
    }

    /* public function create()
    {
        $servicios = Servicio::all();
        return view('eventos.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'duracion' => 'required|numeric',
            'personas' => 'required|integer',
        ]);
        Evento::create($data);
        return redirect()->route('eventos.index')->with('success', 'Evento creado.');
    }

    public function show(Evento $evento)
    {
        $evento->load('servicio');
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $servicios = Servicio::all();
        return view('eventos.edit', compact('evento', 'servicios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'duracion' => 'required|numeric',
            'personas' => 'required|integer',
        ]);
        $evento->update($data);
        return redirect()->route('eventos.index')->with('success', 'Evento actualizado.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado.');
    } */
}
