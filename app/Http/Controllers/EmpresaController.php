<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::paginate(8);
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'slogan'      => 'nullable|string',
            'mision'      => 'nullable|string',
            'vision'      => 'nullable|string',
            'valores'     => 'nullable|string',
            'descripcion' => 'nullable|string',
            'telefono'    => 'nullable|string|max:20',
            'correo'      => 'nullable|email|max:150',
            'sitio_web'   => 'nullable|string|max:200',
            'direccion'   => 'nullable|string',
            'costo_km'    => 'nullable|numeric|min:0',
            'logo'        => 'nullable|image|max:2048',
            'imagen'      => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_nombre'] = $request->file('logo')->store('empresa', 'public');
        }
        if ($request->hasFile('imagen')) {
            $data['imagen_nombre'] = $request->file('imagen')->store('empresa', 'public');
        }

        unset($data['logo'], $data['imagen']);
        Empresa::create($data);
        return redirect()->route('empresas.index')->with('success', 'Empresa creada.');
    }

    public function show(Empresa $empresa)
    {
        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'slogan'      => 'nullable|string',
            'mision'      => 'nullable|string',
            'vision'      => 'nullable|string',
            'valores'     => 'nullable|string',
            'descripcion' => 'nullable|string',
            'telefono'    => 'nullable|string|max:20',
            'correo'      => 'nullable|email|max:150',
            'sitio_web'   => 'nullable|string|max:200',
            'direccion'   => 'nullable|string',
            'costo_km'    => 'nullable|numeric|min:0',
            'logo'        => 'nullable|image|max:2048',
            'imagen'      => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            if ($empresa->logo_nombre) Storage::disk('public')->delete($empresa->logo_nombre);
            $data['logo_nombre'] = $request->file('logo')->store('empresa', 'public');
        }
        if ($request->hasFile('imagen')) {
            if ($empresa->imagen_nombre) Storage::disk('public')->delete($empresa->imagen_nombre);
            $data['imagen_nombre'] = $request->file('imagen')->store('empresa', 'public');
        }

        unset($data['logo'], $data['imagen']);
        $empresa->update($data);
        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada.');
    }

    public function destroy(Empresa $empresa)
    {
        if ($empresa->logo_nombre) Storage::disk('public')->delete($empresa->logo_nombre);
        if ($empresa->imagen_nombre) Storage::disk('public')->delete($empresa->imagen_nombre);
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada.');
    }
}
