<?php

namespace App\Http\Controllers;

use App\Models\Padecimiento;
use Illuminate\Http\Request;

class PadecimientoController extends Controller
{
    const NIVELES_RIESGO = ['Bajo', 'Medio', 'Alto'];

    private function rules(): array
    {
        return [
            'nombre_padecimiento' => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'nivel_riesgo'        => ['required', 'in:Bajo,Medio,Alto'],
            'costo_extra'         => ['required', 'numeric', 'min:0'],
        ];
    }

    private function messages(): array
    {
        return [
            'nombre_padecimiento.required' => 'El nombre del padecimiento es obligatorio.',
            'nombre_padecimiento.max'      => 'El nombre no puede superar 150 caracteres.',
            'nombre_padecimiento.regex'    => 'El nombre solo puede contener letras y espacios.',

            'nivel_riesgo.required'        => 'El nivel de riesgo es obligatorio.',
            'nivel_riesgo.in'              => 'El nivel de riesgo debe ser Bajo, Medio o Alto.',

            'costo_extra.required'         => 'El costo extra es obligatorio.',
            'costo_extra.numeric'          => 'El costo extra debe ser un número.',
            'costo_extra.min'              => 'El costo extra no puede ser negativo.',
        ];
    }

    public function index()
    {
        $padecimientos = Padecimiento::paginate(8);
        return view('padecimientos.index', compact('padecimientos'));
    }

    public function create()
    {
        $nivelesRiesgo = self::NIVELES_RIESGO;
        return view('padecimientos.create', compact('nivelesRiesgo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());
        Padecimiento::create($data);
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento creado correctamente.');
    }

    public function show(Padecimiento $padecimiento)
    {
        return view('padecimientos.show', compact('padecimiento'));
    }

    public function edit(Padecimiento $padecimiento)
    {
        $nivelesRiesgo = self::NIVELES_RIESGO;
        return view('padecimientos.edit', compact('padecimiento', 'nivelesRiesgo'));
    }

    public function update(Request $request, Padecimiento $padecimiento)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $padecimiento->update($data);
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento actualizado correctamente.');
    }

    public function destroy(Padecimiento $padecimiento)
    {
        $padecimiento->delete();
        return redirect()->route('padecimientos.index')->with('success', 'Padecimiento eliminado correctamente.');
    }
}