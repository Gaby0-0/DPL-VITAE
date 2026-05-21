<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Servicio;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $servicios = Servicio::with(['ambulancia', 'cliente.usuario', 'operador.usuario', 'evento'])
            ->where('tipo', 'Evento')
            ->when($request->estado, fn($q, $v) => $q->where('estado', $v))
            ->when($request->fecha_inicio, fn($q, $v) => $q->where('fecha_hora', '>=', $v))
            ->when($request->fecha_fin, fn($q, $v) => $q->where('fecha_hora', '<=', $v . ' 23:59:59'))
            ->orderByDesc('fecha_hora')
            ->paginate(8)->withQueryString();

        $estados = ['Activo' => 'Activo', 'Finalizado' => 'Finalizado', 'Cancelado' => 'Cancelado'];
        return view('eventos.index', compact('servicios', 'estados'));
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
