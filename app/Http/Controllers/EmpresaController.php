<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();

        return view('admin.empresa.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'slogan'      => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'mision'      => 'nullable|string',
            'vision'      => 'nullable|string',
            'valores'     => 'nullable|string',
            'telefono'    => 'nullable|string|max:20',
            'correo'      => 'nullable|email|max:150',
            'sitio_web'   => 'nullable|string|max:200',
            'direccion'   => 'nullable|string|max:255',
            'logo'        => 'nullable|image|max:2048',
            'imagen'      => 'nullable|image|max:4096',
        ], [
            'nombre.required'   => 'El nombre de la empresa es obligatorio.',
            'nombre.max'        => 'El nombre no puede tener más de 150 caracteres.',
            'slogan.max'        => 'El slogan no puede tener más de 255 caracteres.',
            'telefono.max'      => 'El teléfono no puede tener más de 20 caracteres.',
            'correo.email'      => 'El correo electrónico no tiene un formato válido.',
            'correo.max'        => 'El correo no puede tener más de 150 caracteres.',
            'sitio_web.max'     => 'El sitio web no puede tener más de 200 caracteres.',
            'logo.image'        => 'El logo debe ser una imagen (jpg, png, etc.).',
            'logo.max'          => 'El logo no puede pesar más de 2 MB.',
            'imagen.image'      => 'La imagen debe ser un archivo de imagen.',
            'imagen.max'        => 'La imagen no puede pesar más de 4 MB.',
        ]);

        DB::beginTransaction();

        try {
            $datos = $request->only([
                'nombre', 'slogan', 'descripcion', 'mision',
                'vision', 'valores', 'telefono', 'correo',
                'sitio_web', 'direccion',
            ]);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $datos['logo']        = file_get_contents($file->getRealPath());
                $datos['logo_nombre'] = $file->getClientOriginalName();
                $datos['logo_tipo']   = $file->getMimeType();
            }

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $datos['imagen']        = file_get_contents($file->getRealPath());
                $datos['imagen_nombre'] = $file->getClientOriginalName();
                $datos['imagen_tipo']   = $file->getMimeType();
            }

            Empresa::create($datos);

            DB::commit();

            return redirect()->route('empresa.index')->with('success', 'Datos de la empresa registrados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);

        return view('admin.empresa.editar', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:150',
            'slogan'      => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'mision'      => 'nullable|string',
            'vision'      => 'nullable|string',
            'valores'     => 'nullable|string',
            'telefono'    => 'nullable|string|max:20',
            'correo'      => 'nullable|email|max:150',
            'sitio_web'   => 'nullable|string|max:200',
            'direccion'   => 'nullable|string|max:255',
            'logo'        => 'nullable|image|max:2048',
            'imagen'      => 'nullable|image|max:4096',
        ], [
            'nombre.required'   => 'El nombre de la empresa es obligatorio.',
            'nombre.max'        => 'El nombre no puede tener más de 150 caracteres.',
            'slogan.max'        => 'El slogan no puede tener más de 255 caracteres.',
            'telefono.max'      => 'El teléfono no puede tener más de 20 caracteres.',
            'correo.email'      => 'El correo electrónico no tiene un formato válido.',
            'correo.max'        => 'El correo no puede tener más de 150 caracteres.',
            'sitio_web.max'     => 'El sitio web no puede tener más de 200 caracteres.',
            'logo.image'        => 'El logo debe ser una imagen (jpg, png, etc.).',
            'logo.max'          => 'El logo no puede pesar más de 2 MB.',
            'imagen.image'      => 'La imagen debe ser un archivo de imagen.',
            'imagen.max'        => 'La imagen no puede pesar más de 4 MB.',
        ]);

        DB::beginTransaction();

        try {
            $datos = $request->only([
                'nombre', 'slogan', 'descripcion', 'mision',
                'vision', 'valores', 'telefono', 'correo',
                'sitio_web', 'direccion',
            ]);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $datos['logo']        = file_get_contents($file->getRealPath());
                $datos['logo_nombre'] = $file->getClientOriginalName();
                $datos['logo_tipo']   = $file->getMimeType();
            }

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $datos['imagen']        = file_get_contents($file->getRealPath());
                $datos['imagen_nombre'] = $file->getClientOriginalName();
                $datos['imagen_tipo']   = $file->getMimeType();
            }

            $empresa->update($datos);

            DB::commit();

            return redirect()->route('empresa.index')->with('success', 'Datos de la empresa actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);

        DB::beginTransaction();

        try {
            $empresa->delete();

            DB::commit();

            return redirect()->route('empresa.index')->with('success', 'Registro eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /** Sirve el logo almacenado como binario */
    public function logo($id)
    {
        $empresa = Empresa::findOrFail($id);

        if (!$empresa->logo) {
            abort(404);
        }

        return response($empresa->logo)
            ->header('Content-Type', $empresa->logo_tipo ?? 'image/png');
    }

    /** Sirve la imagen almacenada como binario */
    public function imagen($id)
    {
        $empresa = Empresa::findOrFail($id);

        if (!$empresa->imagen) {
            abort(404);
        }

        return response($empresa->imagen)
            ->header('Content-Type', $empresa->imagen_tipo ?? 'image/png');
    }
}
