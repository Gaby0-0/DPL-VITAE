<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperadorController extends Controller
{
    public function index()
    {
        $operadores = Operador::with('usuario')
            ->withCount(['servicios as en_servicio' => fn($q) => $q->where('estado', 'Activo')])
            ->paginate(15);
        return view('operadores.index', compact('operadores'));
    }

    public function create()
    {
        return view('operadores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'ap_paterno'   => 'required|string|max:100',
            'ap_materno'   => 'nullable|string|max:100',
            'email'        => 'required|email|max:150|unique:users,email',
            'password'     => 'required|string|min:8|confirmed',
            'salario_hora' => 'required|numeric|min:0',
        ]);

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
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'ap_paterno'   => 'required|string|max:100',
            'ap_materno'   => 'nullable|string|max:100',
            'email'        => 'required|email|max:150|unique:users,email,' . $operador->id_usuario . ',id_usuario',
            'password'     => 'nullable|string|min:8|confirmed',
            'salario_hora' => 'required|numeric|min:0',
        ]);

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
