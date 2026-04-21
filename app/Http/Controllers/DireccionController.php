<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\Colonia;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    public function index()
    {
        $direcciones = Direccion::with('colonia.municipio')->paginate(8);
        return view('direcciones.index', compact('direcciones'));
    }

    public function create()
    {
        $colonias = Colonia::with('municipio')->get();
        return view('direcciones.create', compact('colonias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_calle' => 'required|string|max:255',
            'n_exterior' => 'required|string|max:20',
            'n_interior' => 'nullable|string|max:20',
            'id_colonia' => 'required|exists:colonia,id_colonia',
        ]);
        Direccion::create($data);
        return redirect()->route('direcciones.index')->with('success', 'Dirección creada.');
    }

    public function show(Direccion $direccion)
    {
        $direccion->load('colonia.municipio');
        return view('direcciones.show', compact('direccion'));
    }

    public function edit(Direccion $direccion)
    {
        $colonias = Colonia::with('municipio')->get();
        return view('direcciones.edit', compact('direccion', 'colonias'));
    }

    public function update(Request $request, Direccion $direccion)
    {
        $data = $request->validate([
            'nombre_calle' => 'required|string|max:255',
            'n_exterior' => 'required|string|max:20',
            'n_interior' => 'nullable|string|max:20',
            'id_colonia' => 'required|exists:colonia,id_colonia',
        ]);
        $direccion->update($data);
        return redirect()->route('direcciones.index')->with('success', 'Dirección actualizada.');
    }

    public function destroy(Direccion $direccion)
    {
        $direccion->delete();
        return redirect()->route('direcciones.index')->with('success', 'Dirección eliminada.');
    }
}
