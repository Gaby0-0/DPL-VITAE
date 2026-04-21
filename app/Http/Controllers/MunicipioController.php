<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
    {
        $municipios = Municipio::paginate(8);
        return view('municipios.index', compact('municipios'));
    }

    public function create()
    {
        return view('municipios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_municipio' => 'required|string|max:255',
        ]);
        Municipio::create($data);
        return redirect()->route('municipios.index')->with('success', 'Municipio creado.');
    }

    public function show(Municipio $municipio)
    {
        $municipio->load('colonias');
        return view('municipios.show', compact('municipio'));
    }

    public function edit(Municipio $municipio)
    {
        return view('municipios.edit', compact('municipio'));
    }

    public function update(Request $request, Municipio $municipio)
    {
        $data = $request->validate([
            'nombre_municipio' => 'required|string|max:255',
        ]);
        $municipio->update($data);
        return redirect()->route('municipios.index')->with('success', 'Municipio actualizado.');
    }

    public function destroy(Municipio $municipio)
    {
        $municipio->delete();
        return redirect()->route('municipios.index')->with('success', 'Municipio eliminado.');
    }
}
