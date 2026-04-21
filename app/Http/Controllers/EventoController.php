<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Servicio;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with('servicio')->paginate(8);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
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
    }
}
