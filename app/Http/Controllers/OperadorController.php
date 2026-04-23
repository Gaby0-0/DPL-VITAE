<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperadorController extends Controller
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

    private function rulesPersonales(): array
    {
        return [
            'nombre'     => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'ap_paterno' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
            'ap_materno' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/'],
        ];
    }

    private function messagesPersonales(): array
    {
        return [
            'nombre.required'     => 'El nombre es obligatorio.',
            'nombre.max'          => 'El nombre no puede superar 100 caracteres.',
            'nombre.regex'        => 'El nombre solo puede contener letras y espacios.',

            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'ap_paterno.max'      => 'El apellido paterno no puede superar 100 caracteres.',
            'ap_paterno.regex'    => 'El apellido paterno solo puede contener letras y espacios.',

            'ap_materno.max'      => 'El apellido materno no puede superar 100 caracteres.',
            'ap_materno.regex'    => 'El apellido materno solo puede contener letras y espacios.',
        ];
    }

    private function messagesAcceso(): array
    {
        return [
            'email.required'        => 'El correo electrónico es obligatorio.',
            'email.email'           => 'Ingresa un correo electrónico válido.',
            'email.max'             => 'El correo no puede superar 150 caracteres.',
            'email.unique'          => 'Este correo electrónico ya está registrado.',

            'password.required'     => 'La contraseña es obligatoria.',
            'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'    => 'Las contraseñas no coinciden.',

            'salario_hora.required' => 'El salario por hora es obligatorio.',
            'salario_hora.numeric'  => 'El salario debe ser un número.',
            'salario_hora.min'      => 'El salario no puede ser negativo.',
        ];
    }

    // ── CRUD ────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $operadores = Operador::with('usuario')
        ->when($request->estado, function ($q, $estado) {
            $q->where('estado', $estado);
        })
                // filtro por rango de salario
        ->when($request->salario_min, function ($q, $salario_min) {
            $q->where('salario_hora', '>=', $salario_min);
        })
        ->when($request->salario_max, function ($q, $salario_max) {
            $q->where('salario_hora', '<=', $salario_max);
        })
        ->withCount(['servicios as en_servicio' => fn($q) => $q->where('estado', 'Activo')])
        ->paginate(8);

        $estados = [
            'Disponible' => 'Disponible',
            'No disponible' => 'No disponible'
        ];

        return view('operadores.index', compact('operadores', 'estados'));
    }

    public function create()
    {
        return view('operadores.create');
    }

    public function store(Request $request)
    {
        $this->normalizarNombres($request);

        $request->validate(
            array_merge($this->rulesPersonales(), [
                'email'        => ['required', 'email', 'max:150', 'unique:users,email'],
                'password'     => ['required', 'string', 'min:8', 'confirmed'],
                'salario_hora' => ['required', 'numeric', 'min:0'],
            ]),
            array_merge($this->messagesPersonales(), $this->messagesAcceso())
        );

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
            ]);

            Operador::create([
                'id_usuario'   => $user->id_usuario,
                'salario_hora' => $request->salario_hora,
            ]);
        });

        return redirect()->route('operadores.index')->with('success', 'Operador creado correctamente.');
    }

    public function show(Operador $operador)
    {
        $operador->load(['usuario', 'servicios.ambulancia']);
        $enServicio = $operador->servicios->where('estado', 'Activo')->isNotEmpty();
        return view('operadores.show', compact('operador', 'enServicio'));
    }

    public function edit(Operador $operador)
    {
        $operador->load('usuario');
        return view('operadores.edit', compact('operador'));
    }

    public function update(Request $request, Operador $operador)
    {
        $this->normalizarNombres($request);

        $request->validate(
            array_merge($this->rulesPersonales(), [
                'email'        => ['required', 'email', 'max:150', 'unique:users,email,' . $operador->id_usuario . ',id_usuario'],
                'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
                'salario_hora' => ['required', 'numeric', 'min:0'],
            ]),
            array_merge($this->messagesPersonales(), $this->messagesAcceso())
        );

        DB::transaction(function () use ($request, $operador) {
            $userData = [
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email'      => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $operador->usuario->update($userData);
            $operador->update(['salario_hora' => $request->salario_hora]);
        });

        return redirect()->route('operadores.index')->with('success', 'Operador actualizado correctamente.');
    }

    public function destroy(Operador $operador)
    {
        DB::transaction(function () use ($operador) {
            $operador->delete();
            $operador->usuario->delete();
        });
        return redirect()->route('operadores.index')->with('success', 'Operador eliminado.');
    }
}