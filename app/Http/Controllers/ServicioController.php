<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\Cliente;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServicioController extends Controller
{
    const TIPOS   = ['Traslado', 'Evento', 'Emergencia', 'Otro'];
    const ESTADOS = ['Activo', 'Finalizado', 'Cancelado'];

    private function rules(): array
    {
        return [
            'tipo'          => ['nullable', 'in:Traslado,Evento,Emergencia,Otro'],
            'estado'        => ['required', 'in:Activo,Finalizado,Cancelado'],
            'fecha_hora'    => ['required', 'date'],
            'hora_salida'   => ['nullable', 'date', 'after:fecha_hora'],
            'costo_total'   => ['required', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'id_ambulancia' => ['required', 'exists:ambulancia,id_ambulancia'],
            'id_cliente'    => ['required', 'exists:cliente,id_usuario'],
            'id_operador'   => ['required', 'exists:operador,id_usuario'],
        ];
    }

    private function messages(): array
    {
        return [
            'tipo.in'                => 'El tipo de servicio seleccionado no es válido.',

            'estado.required'        => 'El estado es obligatorio.',
            'estado.in'              => 'El estado seleccionado no es válido.',

            'fecha_hora.required'    => 'La fecha y hora de inicio son obligatorias.',
            'fecha_hora.date'        => 'La fecha y hora de inicio no son válidas.',

            'hora_salida.date'       => 'La hora de salida no es válida.',
            'hora_salida.after'      => 'La hora de salida debe ser posterior a la fecha de inicio.',

            'costo_total.required'   => 'El costo total es obligatorio.',
            'costo_total.numeric'    => 'El costo total debe ser un número.',
            'costo_total.min'        => 'El costo total no puede ser negativo.',

            'observaciones.max'      => 'Las observaciones no pueden superar 500 caracteres.',

            'id_ambulancia.required' => 'Debes seleccionar una ambulancia.',
            'id_ambulancia.exists'   => 'La ambulancia seleccionada no es válida.',

            'id_cliente.required'    => 'Debes seleccionar un cliente.',
            'id_cliente.exists'      => 'El cliente seleccionado no es válido.',

            'id_operador.required'   => 'Debes seleccionar un operador.',
            'id_operador.exists'     => 'El operador seleccionado no es válido.',
        ];
    }

    public function index()
    {
        $servicios = Servicio::with(['ambulancia', 'cliente.usuario', 'operador.usuario'])->paginate(8);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $ambulancias = Ambulancia::all();
        $clientes    = Cliente::with('usuario')->get();
        $operadores  = Operador::with('usuario')->get();
        $tipos       = self::TIPOS;
        $estados     = self::ESTADOS;
        return view('servicios.create', compact('ambulancias', 'clientes', 'operadores', 'tipos', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora);

        Servicio::create($data);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show(Servicio $servicio)
    {
        $servicio->load(['ambulancia', 'cliente.usuario', 'operador.usuario', 'pacientes', 'paramedicos.usuario', 'insumos']);
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $ambulancias = Ambulancia::all();
        $clientes    = Cliente::with('usuario')->get();
        $operadores  = Operador::with('usuario')->get();
        $tipos       = self::TIPOS;
        $estados     = self::ESTADOS;
        return view('servicios.edit', compact('servicio', 'ambulancias', 'clientes', 'operadores', 'tipos', 'estados'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $this->validarDisponibilidadOperador($request->id_operador, $request->fecha_hora, $servicio->id_servicio);

        $servicio->update($data);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }

    private function validarDisponibilidadOperador(int $idOperador, string $fechaHora, ?int $excluirServicioId = null): void
    {
        $activo = Servicio::where('id_operador', $idOperador)
            ->where('estado', 'Activo')
            ->when($excluirServicioId, fn($q) => $q->where('id_servicio', '!=', $excluirServicioId))
            ->exists();

        if ($activo) {
            throw ValidationException::withMessages([
                'id_operador' => 'El operador seleccionado ya tiene un servicio activo en curso y no puede ser asignado.',
            ]);
        }

        $conflicto = Servicio::where('id_operador', $idOperador)
            ->where('fecha_hora', $fechaHora)
            ->when($excluirServicioId, fn($q) => $q->where('id_servicio', '!=', $excluirServicioId))
            ->exists();

        if ($conflicto) {
            throw ValidationException::withMessages([
                'id_operador' => 'El operador seleccionado ya está asignado a otro servicio en esa fecha y hora.',
            ]);
        }
    }
}