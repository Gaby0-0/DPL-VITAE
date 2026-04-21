<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Direccion;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with(['servicio', 'direccion'])->paginate(8);
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.create', compact('servicios', 'direcciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'oxigeno' => 'nullable|numeric',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:1',
            'peso' => 'nullable|numeric',
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
        ]);
        Paciente::create($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente creado.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['servicio', 'direccion.colonia.municipio', 'padecimientos']);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $servicios = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.edit', compact('paciente', 'servicios', 'direcciones'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'oxigeno' => 'nullable|numeric',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:1',
            'peso' => 'nullable|numeric',
            'id_servicio' => 'required|exists:servicio,id_servicio',
            'id_direccion' => 'nullable|exists:direccion,id_direccion',
        ]);
        $paciente->update($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
