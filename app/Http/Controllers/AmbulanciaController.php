<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class AmbulanciaController extends Controller
{
    public function index()
    {
        $ambulancias = Ambulancia::with(['tipo'])->paginate(15);
        return view('ambulancias.index', compact('ambulancias'));
    }

    public function create()
    {
        $tipos = TipoAmbulancia::all();
        return view('ambulancias.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placa'              => 'required|string|max:20',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
        ]);
        Ambulancia::create($data);
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia creada.');
    }

    public function show(Ambulancia $ambulancia)
    {
        $ambulancia->load(['tipo']);
        return view('ambulancias.show', compact('ambulancia'));
    }

    public function edit(Ambulancia $ambulancia)
    {
        $tipos = TipoAmbulancia::all();
        return view('ambulancias.edit', compact('ambulancia', 'tipos'));
    }

    public function update(Request $request, Ambulancia $ambulancia)
    {
        $data = $request->validate([
            'placa'              => 'required|string|max:20',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
        ]);
        $ambulancia->update($data);
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia actualizada.');
    }

    public function destroy(Ambulancia $ambulancia)
    {
        $ambulancia->delete();
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia eliminada.');
    }
}
