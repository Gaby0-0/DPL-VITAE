<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsumosController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        return view('admin.insumos.index', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_insumo' => 'required|string|max:100',
            'costo_unidad' => 'required|numeric|min:0'
        ]);

        Insumo::create([
            'nombre_insumo' => $request->nombre_insumo,
            'costo_unidad' => $request->costo_unidad
        ]);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo registrado correctamente');
    }

    public function show($id)
    {
        $insumo = Insumo::findOrFail($id);
        return response()->json($insumo);
    }

    public function edit($id)
    {
        $insumo = Insumo::findOrFail($id);
        return response()->json($insumo);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_insumo' => 'required|string|max:100',
            'costo_unidad' => 'required|numeric|min:0'
        ]);

        $insumo = Insumo::findOrFail($id);

        $insumo->update([
            'nombre_insumo' => $request->nombre_insumo,
            'costo_unidad' => $request->costo_unidad
        ]);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo actualizado correctamente');
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo eliminado correctamente');
    }
}