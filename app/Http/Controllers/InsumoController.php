<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::paginate(8);
        return view('insumos.index', compact('insumos'));
    }

    public function create()
    {
        return view('insumos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_insumo' => 'required|string|max:255',
            'costo_unidad' => 'required|numeric',
        ]);
        Insumo::create($data);
        return redirect()->route('insumos.index')->with('success', 'Insumo creado.');
    }

    public function show(Insumo $insumo)
    {
        return view('insumos.show', compact('insumo'));
    }

    public function edit(Insumo $insumo)
    {
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'nombre_insumo' => 'required|string|max:255',
            'costo_unidad' => 'required|numeric',
        ]);
        $insumo->update($data);
        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado.');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado.');
    }
}
