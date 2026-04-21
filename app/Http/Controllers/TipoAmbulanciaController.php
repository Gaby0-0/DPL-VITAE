<?php

namespace App\Http\Controllers;

use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class TipoAmbulanciaController extends Controller
{
    public function index()
    {
        $tipos = TipoAmbulancia::paginate(8);
        return view('tipos-ambulancia.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos-ambulancia.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo_base'  => 'required|numeric|min:0',
        ]);
        TipoAmbulancia::create($data);
        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia creado.');
    }

    public function show(TipoAmbulancia $tipoAmbulancia)
    {
        return view('tipos-ambulancia.show', compact('tipoAmbulancia'));
    }

    public function edit(TipoAmbulancia $tipoAmbulancia)
    {
        return view('tipos-ambulancia.edit', compact('tipoAmbulancia'));
    }

    public function update(Request $request, TipoAmbulancia $tipoAmbulancia)
    {
        $data = $request->validate([
            'nombre_tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo_base'  => 'required|numeric|min:0',
        ]);
        $tipoAmbulancia->update($data);
        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia actualizado.');
    }

    public function destroy(TipoAmbulancia $tipoAmbulancia)
    {
        $tipoAmbulancia->delete();
        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia eliminado.');
    }
}
