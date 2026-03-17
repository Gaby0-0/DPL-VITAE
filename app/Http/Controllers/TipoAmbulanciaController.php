<?php

namespace App\Http\Controllers;

use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoAmbulanciaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:100|unique:tipo_ambulancia,nombre_tipo',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre_tipo.required' => 'El nombre del tipo es obligatorio.',
            'nombre_tipo.string'   => 'El nombre debe ser texto.',
            'nombre_tipo.max'      => 'El nombre no puede tener más de 100 caracteres.',
            'nombre_tipo.unique'   => 'Ya existe un tipo de ambulancia con ese nombre.',
            'descripcion.string'   => 'La descripción debe ser texto.',
            'descripcion.max'      => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        DB::beginTransaction();

        try {
            TipoAmbulancia::create([
                'nombre_tipo' => $request->nombre_tipo,
                'descripcion' => $request->descripcion,
            ]);

            DB::commit();

            return redirect()->route('ambulancias.index', ['#tipos'])->with('success', 'Tipo de ambulancia registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $tipo = TipoAmbulancia::findOrFail($id);

        return view('admin.ambulancias.tipo_editar', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoAmbulancia::findOrFail($id);

        $request->validate([
            'nombre_tipo' => 'required|string|max:100|unique:tipo_ambulancia,nombre_tipo,' . $id . ',id_tipo_ambulancia',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre_tipo.required' => 'El nombre del tipo es obligatorio.',
            'nombre_tipo.string'   => 'El nombre debe ser texto.',
            'nombre_tipo.max'      => 'El nombre no puede tener más de 100 caracteres.',
            'nombre_tipo.unique'   => 'Ya existe un tipo de ambulancia con ese nombre.',
            'descripcion.string'   => 'La descripción debe ser texto.',
            'descripcion.max'      => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        DB::beginTransaction();

        try {
            $tipo->update([
                'nombre_tipo' => $request->nombre_tipo,
                'descripcion' => $request->descripcion,
            ]);

            DB::commit();

            return redirect()->route('ambulancias.index')->with('success', 'Tipo de ambulancia actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tipo = TipoAmbulancia::findOrFail($id);

        DB::beginTransaction();

        try {
            $tipo->delete();

            DB::commit();

            return redirect()->route('ambulancias.index')->with('success', 'Tipo de ambulancia eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
