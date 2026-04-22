<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Direccion;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // ── Normaliza nombres antes de validar ──────────────────────────────────
    private function normalizarNombres(Request $request): void
    {
        $request->merge([
            'nombre'     => ucwords(strtolower(trim($request->nombre))),
            'ap_paterno' => ucwords(strtolower(trim($request->ap_paterno))),
            'ap_materno' => $request->ap_materno
                                ? ucwords(strtolower(trim($request->ap_materno)))
                                : null,
        ]);
    }

    // ── Reglas reutilizables ────────────────────────────────────────────────
    private function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'ap_paterno'       => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'ap_materno'       => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'sexo'             => ['nullable', 'in:M,F'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'peso'             => ['nullable', 'numeric', 'min:0.5', 'max:500'],
            'oxigeno'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'id_servicio'      => ['required', 'exists:servicio,id_servicio'],
            'id_direccion'     => ['nullable', 'exists:direccion,id_direccion'],
        ];
    }

    private function messages(): array
    {
        return [
            'nombre.required'          => 'El nombre es obligatorio.',
            'nombre.max'               => 'El nombre no puede superar 100 caracteres.',
            'nombre.regex'             => 'El nombre solo puede contener letras y espacios.',

            'ap_paterno.required'      => 'El apellido paterno es obligatorio.',
            'ap_paterno.max'           => 'El apellido paterno no puede superar 100 caracteres.',
            'ap_paterno.regex'         => 'El apellido paterno solo puede contener letras y espacios.',

            'ap_materno.max'           => 'El apellido materno no puede superar 100 caracteres.',
            'ap_materno.regex'         => 'El apellido materno solo puede contener letras y espacios.',

            'sexo.in'                  => 'El sexo debe ser Masculino o Femenino.',

            'fecha_nacimiento.date'    => 'La fecha de nacimiento no es válida.',
            'fecha_nacimiento.before'  => 'La fecha de nacimiento debe ser anterior a hoy.',

            'peso.numeric'             => 'El peso debe ser un número.',
            'peso.min'                 => 'El peso debe ser mayor a 0.',
            'peso.max'                 => 'El peso no puede superar 500 kg.',

            'oxigeno.numeric'          => 'El nivel de oxígeno debe ser un número.',
            'oxigeno.min'              => 'El nivel de oxígeno no puede ser negativo.',
            'oxigeno.max'              => 'El nivel de oxígeno no puede superar 100%.',

            'id_servicio.required'     => 'Debes seleccionar un servicio.',
            'id_servicio.exists'       => 'El servicio seleccionado no es válido.',

            'id_direccion.exists'      => 'La dirección seleccionada no es válida.',
        ];
    }

    // ── CRUD ────────────────────────────────────────────────────────────────
    public function index()
    {
        $pacientes = Paciente::with(['servicio', 'direccion'])->paginate(8);
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $servicios   = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.create', compact('servicios', 'direcciones'));
    }

    public function store(Request $request)
    {
        $this->normalizarNombres($request);

        $data = $request->validate($this->rules(), $this->messages());

        Paciente::create($data);

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['servicio', 'direccion.colonia.municipio', 'padecimientos']);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $servicios   = Servicio::all();
        $direcciones = Direccion::with('colonia')->get();
        return view('pacientes.edit', compact('paciente', 'servicios', 'direcciones'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $this->normalizarNombres($request);

        $data = $request->validate($this->rules(), $this->messages());

        $paciente->update($data);

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente eliminado correctamente.');
    }
}