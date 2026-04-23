<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use Illuminate\Http\Request;

class AmbulanciaController extends Controller
{
    const ESTADOS = ['Disponible', 'En servicio', 'En mantenimiento'];

    private function rules(): array
    {
        return [
            'placa'              => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/'],
            'estado'             => ['required', 'in:Disponible,En servicio,En mantenimiento'],
            'id_tipo_ambulancia' => ['required', 'exists:tipo_ambulancia,id_tipo_ambulancia'],
        ];
    }

    private function messages(): array
    {
        return [
            'placa.required'              => 'La placa es obligatoria.',
            'placa.max'                   => 'La placa no puede superar 20 caracteres.',
            'placa.regex'                 => 'La placa solo puede contener letras mayúsculas, números y guiones.',

            'estado.required'             => 'El estado es obligatorio.',
            'estado.in'                   => 'El estado seleccionado no es válido.',

            'id_tipo_ambulancia.required' => 'Debes seleccionar un tipo de ambulancia.',
            'id_tipo_ambulancia.exists'   => 'El tipo de ambulancia seleccionado no es válido.',
        ];
    }

    public function index(Request $request)
    {
        $tipos = TipoAmbulancia::all();

        $ambulancias = Ambulancia::with('tipo')
            // filtro por estado
        ->when($request->estado, function ($q, $estado) {
            $q->where('estado', $estado);
            })
        ->when($request->tipo, function ($q, $tipo) {
            $q->where('id_tipo_ambulancia', $tipo);
        })
        ->paginate(8);
        return view('ambulancias.index', compact('ambulancias', 'tipos'));
    }

    public function create()
    {
        $tipos   = TipoAmbulancia::all();
        $estados = self::ESTADOS;
        return view('ambulancias.create', compact('tipos', 'estados'));
    }

    public function store(Request $request)
    {
        // Normalizar placa a mayúsculas antes de validar
        $request->merge([
            'placa' => strtoupper(trim($request->placa)),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        Ambulancia::create($data);

        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia creada correctamente.');
    }

    public function show(Ambulancia $ambulancia)
    {
        $ambulancia->load(['tipo']);
        return view('ambulancias.show', compact('ambulancia'));
    }

    public function edit(Ambulancia $ambulancia)
    {
        $tipos   = TipoAmbulancia::all();
        $estados = self::ESTADOS;
        return view('ambulancias.edit', compact('ambulancia', 'tipos', 'estados'));
    }

    public function update(Request $request, Ambulancia $ambulancia)
    {
        // Normalizar placa a mayúsculas antes de validar
        $request->merge([
            'placa' => strtoupper(trim($request->placa)),
        ]);

        $data = $request->validate($this->rules(), $this->messages());

        $ambulancia->update($data);

        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia actualizada correctamente.');
    }

    public function destroy(Ambulancia $ambulancia)
    {
        $ambulancia->delete();
        return redirect()->route('ambulancias.index')->with('success', 'Ambulancia eliminada correctamente.');
    }
}