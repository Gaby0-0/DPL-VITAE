<?php

namespace App\Http\Controllers;

use App\Models\Paramedico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParamedicoController extends Controller
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
    public function index()
    {
        $paramedicos = Paramedico::with('usuario')->paginate(8);
        return view('paramedicos.index', compact('paramedicos'));
    }

    public function create()
    {
        return view('paramedicos.create');
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

            Paramedico::create([
                'id_usuario'   => $user->id_usuario,
                'salario_hora' => $request->salario_hora,
            ]);
        });

        return redirect()->route('paramedicos.index')->with('success', 'Paramédico creado correctamente.');
    }

    public function show(Paramedico $paramedico)
    {
        $paramedico->load('usuario');
        return view('paramedicos.show', compact('paramedico'));
    }

    public function edit(Paramedico $paramedico)
    {
        $paramedico->load('usuario');
        return view('paramedicos.edit', compact('paramedico'));
    }

    public function update(Request $request, Paramedico $paramedico)
    {
        $this->normalizarNombres($request);

        $request->validate(
            array_merge($this->rulesPersonales(), [
                'email'        => ['required', 'email', 'max:150', 'unique:users,email,' . $paramedico->id_usuario . ',id_usuario'],
                'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
                'salario_hora' => ['required', 'numeric', 'min:0'],
            ]),
            array_merge($this->messagesPersonales(), $this->messagesAcceso())
        );

        DB::transaction(function () use ($request, $paramedico) {
            $userData = [
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email'      => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $paramedico->usuario->update($userData);
            $paramedico->update(['salario_hora' => $request->salario_hora]);
        });

        return redirect()->route('paramedicos.index')->with('success', 'Paramédico actualizado correctamente.');
    }

    public function destroy(Paramedico $paramedico)
    {
        DB::transaction(function () use ($paramedico) {
            $paramedico->delete();
            $paramedico->usuario->delete();
        });
        return redirect()->route('paramedicos.index')->with('success', 'Paramédico eliminado.');
    }
}