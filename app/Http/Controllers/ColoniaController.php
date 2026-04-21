<?php

namespace App\Http\Controllers;

use App\Models\Colonia;
use App\Models\Municipio;
use Illuminate\Http\Request;

class ColoniaController extends Controller
{
    public function index()
    {
        $colonias = Colonia::with('municipio')->paginate(8);
        return view('colonias.index', compact('colonias'));
    }

    public function create()
    {
        $municipios = Municipio::all();
        return view('colonias.create', compact('municipios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_colonia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'id_municipio' => 'required|exists:municipio,id_municipio',
        ]);
        Colonia::create($data);
        return redirect()->route('colonias.index')->with('success', 'Colonia creada.');
    }

    public function show(Colonia $colonia)
    {
        $colonia->load('municipio');
        return view('colonias.show', compact('colonia'));
    }

    public function edit(Colonia $colonia)
    {
        $municipios = Municipio::all();
        return view('colonias.edit', compact('colonia', 'municipios'));
    }

    public function update(Request $request, Colonia $colonia)
    {
        $data = $request->validate([
            'nombre_colonia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'id_municipio' => 'required|exists:municipio,id_municipio',
        ]);
        $colonia->update($data);
        return redirect()->route('colonias.index')->with('success', 'Colonia actualizada.');
    }

    public function destroy(Colonia $colonia)
    {
        $colonia->delete();
        return redirect()->route('colonias.index')->with('success', 'Colonia eliminada.');
    }
}
