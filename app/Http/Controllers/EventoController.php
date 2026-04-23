<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Servicio;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    private function rules(): array
    {
        return [
            'id_servicio' => ['required', 'exists:servicio,id_servicio'],
            'duracion'    => ['required', 'numeric', 'min:0.5'],
            'personas'    => ['required', 'integer', 'min:1'],
        ];
    }

    private function messages(): array
    {
        return [
            'id_servicio.required' => 'Debes seleccionar un servicio.',
            'id_servicio.exists'   => 'El servicio seleccionado no es válido.',

            'duracion.required'    => 'La duración es obligatoria.',
            'duracion.numeric'     => 'La duración debe ser un número.',
            'duracion.min'         => 'La duración debe ser mayor a 0.',

            'personas.required'    => 'El número de personas es obligatorio.',
            'personas.integer'     => 'El número de personas debe ser un número entero.',
            'personas.min'         => 'Debe haber al menos 1 persona.',
        ];
    }

    public function index()
    {
        $eventos = Evento::with('servicio')->paginate(8);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('eventos.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());
        Evento::create($data);
        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
    }

    public function show(Evento $evento)
    {
        $evento->load('servicio');
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $servicios = Servicio::all();
        return view('eventos.edit', compact('evento', 'servicios'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $evento->update($data);
        return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente.');
    }
}