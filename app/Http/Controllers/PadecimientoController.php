<?php

namespace App\Http\Controllers;

use App\Models\Padecimiento;
use Illuminate\Http\Request;

class PadecimientoController extends Controller
{
    public function index()
    {
        $padecimientos = Padecimiento::paginate(8);
        return view('padecimientos.index', compact('padecimientos'));
    }

    public function create()
    {
        return view('padecimientos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_padecimiento' => 'required|string|max:255',
            'nivel_riesgo' => 'required|string|max:255',
            'costo_extra' => 'required|numeric',
        ]);
        Padecimiento::create($data);
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento creado.');
    }

    public function show(Padecimiento $padecimiento)
    {
        return view('padecimientos.show', compact('padecimiento'));
    }

    public function edit(Padecimiento $padecimiento)
    {
        return view('padecimientos.edit', compact('padecimiento'));
    }

    public function update(Request $request, Padecimiento $padecimiento)
    {
        $data = $request->validate([
            'nombre_padecimiento' => 'required|string|max:255',
            'nivel_riesgo' => 'required|string|max:255',
            'costo_extra' => 'required|numeric',
        ]);
        $padecimiento->update($data);
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento actualizado.');
    }

    public function destroy(Padecimiento $padecimiento)
    {
        $padecimiento->delete();
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento eliminado.');
    }
}
