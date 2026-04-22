<?php

namespace App\Http\Controllers;

use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class TipoAmbulanciaController extends Controller
{
    private function rules(): array
    {
        return [
            'nombre_tipo' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'costo_base'  => ['required', 'numeric', 'min:0'],
        ];
    }

    private function messages(): array
    {
        return [
            'nombre_tipo.required' => 'El nombre del tipo es obligatorio.',
            'nombre_tipo.max'      => 'El nombre no puede superar 100 caracteres.',
            'nombre_tipo.regex'    => 'El nombre solo puede contener letras y espacios.',

            'descripcion.max'      => 'La descripción no puede superar 500 caracteres.',

            'costo_base.required'  => 'El costo base es obligatorio.',
            'costo_base.numeric'   => 'El costo base debe ser un número.',
            'costo_base.min'       => 'El costo base no puede ser negativo.',
        ];
    }

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
        $request->merge([
            'nombre_tipo' => ucwords(strtolower(trim($request->nombre_tipo))),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        TipoAmbulancia::create($data);

        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia creado correctamente.');
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
        $request->merge([
            'nombre_tipo' => ucwords(strtolower(trim($request->nombre_tipo))),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        $tipoAmbulancia->update($data);

        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia actualizado correctamente.');
    }

    public function destroy(TipoAmbulancia $tipoAmbulancia)
    {
        $tipoAmbulancia->delete();
        return redirect()->route('tipos-ambulancia.index')->with('success', 'Tipo de ambulancia eliminado correctamente.');
    }
}