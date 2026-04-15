<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('usuario')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'ap_paterno'  => 'required|string|max:100',
            'ap_materno'  => 'nullable|string|max:100',
            'telefono'    => 'nullable|string|max:20',
            'email'       => 'required|email|max:150|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
        ]);

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
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'ap_paterno' => 'required|string|max:100',
            'ap_materno' => 'nullable|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'required|email|max:150|unique:users,email,' . $cliente->id_usuario . ',id_usuario',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

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
        $cliente->usuario->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}
