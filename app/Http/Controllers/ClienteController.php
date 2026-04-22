<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
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
            'telefono'   => ['nullable', 'string', 'max:15', 'regex:/^[0-9\s\-\+\(\)]+$/'],
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

            'telefono.max'        => 'El teléfono no puede superar 15 caracteres.',
            'telefono.regex'      => 'El teléfono solo puede contener números, espacios, guiones y paréntesis.',
        ];
    }

    private function messagesAcceso(): array
    {
        return [
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Ingresa un correo electrónico válido.',
            'email.max'          => 'El correo no puede superar 150 caracteres.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',

            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }

    // ── CRUD ────────────────────────────────────────────────────────────────
    public function index()
    {
        $clientes = Cliente::with('usuario')->paginate(8);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $this->normalizarNombres($request);

        $request->validate(
            array_merge($this->rulesPersonales(), [
                'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]),
            array_merge($this->messagesPersonales(), $this->messagesAcceso())
        );

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'telefono'   => $request->telefono,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
            ]);

            Cliente::create(['id_usuario' => $user->id_usuario]);
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['usuario', 'servicios']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('usuario');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->normalizarNombres($request);

        $request->validate(
            array_merge($this->rulesPersonales(), [
                'email'    => ['required', 'email', 'max:150', 'unique:users,email,' . $cliente->id_usuario . ',id_usuario'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]),
            array_merge($this->messagesPersonales(), $this->messagesAcceso())
        );

        DB::transaction(function () use ($request, $cliente) {
            $userData = [
                'nombre'     => $request->nombre,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'telefono'   => $request->telefono,
                'email'      => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $cliente->usuario->update($userData);
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        DB::transaction(function () use ($cliente) {
            $cliente->delete();
            $cliente->usuario->delete();
        });
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}