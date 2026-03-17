<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmbulanciasController extends Controller
{
    public function index()
    {
        $ambulancias = Ambulancia::with(['tipo', 'operador.usuario'])->get();
        $tipos       = TipoAmbulancia::all();

        return view('admin.ambulancias.index', compact('ambulancias', 'tipos'));
    }

    public function create()
    {
        $tipos      = TipoAmbulancia::all();
        $operadores = Operador::with('usuario')->get();

        return view('admin.ambulancias.create', compact('tipos', 'operadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa'              => 'required|string|max:20|unique:ambulancia,placa',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
            'id_operador'        => 'required|exists:operador,id_usuario',
        ], [
            'placa.required'              => 'La placa es obligatoria.',
            'placa.string'                => 'La placa debe ser texto.',
            'placa.max'                   => 'La placa no puede tener más de 20 caracteres.',
            'placa.unique'                => 'Ya existe una ambulancia con esa placa.',
            'estado.required'             => 'El estado es obligatorio.',
            'estado.in'                   => 'El estado seleccionado no es válido.',
            'id_tipo_ambulancia.required' => 'El tipo de ambulancia es obligatorio.',
            'id_tipo_ambulancia.exists'   => 'El tipo de ambulancia seleccionado no existe.',
            'id_operador.required'        => 'El operador es obligatorio.',
            'id_operador.exists'          => 'El operador seleccionado no existe.',
        ]);

        DB::beginTransaction();

        try {
            Ambulancia::create([
                'placa'              => $request->placa,
                'estado'             => $request->estado,
                'id_tipo_ambulancia' => $request->id_tipo_ambulancia,
                'id_operador'        => $request->id_operador,
            ]);

            DB::commit();

            return redirect()->route('ambulancias.index')->with('success', 'Ambulancia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ambulancia = Ambulancia::findOrFail($id);
        $tipos      = TipoAmbulancia::all();
        $operadores = Operador::with('usuario')->get();

        return view('admin.ambulancias.editar', compact('ambulancia', 'tipos', 'operadores'));
    }

    public function update(Request $request, $id)
    {
        $ambulancia = Ambulancia::findOrFail($id);

        $request->validate([
            'placa'              => 'required|string|max:20|unique:ambulancia,placa,' . $id . ',id_ambulancia',
            'estado'             => 'required|in:Disponible,En servicio,En mantenimiento',
            'id_tipo_ambulancia' => 'required|exists:tipo_ambulancia,id_tipo_ambulancia',
            'id_operador'        => 'required|exists:operador,id_usuario',
        ], [
            'placa.required'              => 'La placa es obligatoria.',
            'placa.string'                => 'La placa debe ser texto.',
            'placa.max'                   => 'La placa no puede tener más de 20 caracteres.',
            'placa.unique'                => 'Ya existe una ambulancia con esa placa.',
            'estado.required'             => 'El estado es obligatorio.',
            'estado.in'                   => 'El estado seleccionado no es válido.',
            'id_tipo_ambulancia.required' => 'El tipo de ambulancia es obligatorio.',
            'id_tipo_ambulancia.exists'   => 'El tipo de ambulancia seleccionado no existe.',
            'id_operador.required'        => 'El operador es obligatorio.',
            'id_operador.exists'          => 'El operador seleccionado no existe.',
        ]);

        DB::beginTransaction();

        try {
            $ambulancia->update([
                'placa'              => $request->placa,
                'estado'             => $request->estado,
                'id_tipo_ambulancia' => $request->id_tipo_ambulancia,
                'id_operador'        => $request->id_operador,
            ]);

            DB::commit();

            return redirect()->route('ambulancias.index')->with('success', 'Ambulancia actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ambulancia = Ambulancia::findOrFail($id);

        DB::beginTransaction();

        try {
            $ambulancia->delete();

            DB::commit();

            return redirect()->route('ambulancias.index')->with('success', 'Ambulancia eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
