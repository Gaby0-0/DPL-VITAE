<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    private function rules(): array
    {
        return [
            'nombre_insumo' => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'costo_unidad'  => ['required', 'numeric', 'min:0'],
        ];
    }

    private function messages(): array
    {
        return [
            'nombre_insumo.required' => 'El nombre del insumo es obligatorio.',
            'nombre_insumo.max'      => 'El nombre no puede superar 150 caracteres.',
            'nombre_insumo.regex'    => 'El nombre solo puede contener letras y espacios.',

            'costo_unidad.required'  => 'El costo por unidad es obligatorio.',
            'costo_unidad.numeric'   => 'El costo debe ser un número.',
            'costo_unidad.min'       => 'El costo no puede ser negativo.',
        ];
    }

    public function index(Request $request)
    {
        $insumos = Insumo::query()
        // filtro por rango de costo
        ->when($request->costo_min, function ($q, $costo) {
            $q->where('costo_unidad', '>=', $costo);
        })
        ->when($request->costo_max, function ($q, $costo) {
            $q->where('costo_unidad', '<=', $costo);
        })
        ->paginate(8);
        return view('insumos.index', compact('insumos'));
    }

    public function create()
    {
        return view('insumos.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre_insumo' => ucwords(strtolower(trim($request->nombre_insumo))),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        Insumo::create($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo creado correctamente.');
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
        $request->merge([
            'nombre_insumo' => ucwords(strtolower(trim($request->nombre_insumo))),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        $insumo->update($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado correctamente.');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado correctamente.');
    }
}