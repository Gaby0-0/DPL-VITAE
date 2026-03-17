<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::join('ambulancia', 'servicio.id_ambulancia', '=', 'ambulancia.id_ambulancia')
            ->join('cliente', 'servicio.id_cliente', '=', 'cliente.id_usuario')
            ->select(
                'servicio.id_servicio',
                'servicio.costo_total',
                'servicio.estado',
                'servicio.fecha_hora',
                'servicio.hora_salida',
                'servicio.observaciones',
                'ambulancia.placa',
                'cliente.id_usuario'
            )
            ->orderBy('servicio.fecha_hora', 'desc')
            ->get();

        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        $ambulancias = Ambulancia::all();
        $clientes = Cliente::all();

        return view('admin.servicios.create', compact('ambulancias', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'costo_total'   => 'required|numeric|min:0',
            'estado'        => 'required|in:Programado,En curso,Finalizado,Cancelado',
            'fecha_hora'    => 'required|date',
            'hora_salida'   => 'nullable|date_format:H:i',
            'observaciones' => 'nullable|string|max:5000',
            'id_ambulancia' => 'required|exists:ambulancia,id_ambulancia',
            'id_usuario'    => 'required|exists:cliente,id_usuario',
        ]);

        DB::beginTransaction();

        try {
            Servicio::create([
                'costo_total'   => $request->costo_total,
                'estado'        => $request->estado,
                'fecha_hora'    => $request->fecha_hora,
                'hora_salida'   => $request->hora_salida,
                'observaciones' => $request->observaciones,
                'id_ambulancia' => $request->id_ambulancia,
                'id_usuario'    => $request->id_usuario,
            ]);

            DB::commit();

            return redirect()->route('servicios.index')->with('success', 'Servicio registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $servicio = Servicio::join('ambulancia', 'servicio.id_ambulancia', '=', 'ambulancia.id_ambulancia')
            ->join('cliente', 'servicio.id_usuario', '=', 'cliente.id_usuario')
            ->select(
                'servicio.*',
                'ambulancia.placa',
                'cliente.id_usuario'
            )
            ->where('servicio.id_servicio', $id)
            ->firstOrFail();

        return view('admin.servicios.show', compact('servicio'));
    }

    public function edit($id)
    {
        $servicio   = Servicio::findOrFail($id);
        $ambulancias = Ambulancia::all();
        $clientes   = Cliente::all();

        return view('admin.servicios.edit', compact('servicio', 'ambulancias', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'costo_total'   => 'required|numeric|min:0',
            'estado'        => 'required|in:Programado,En curso,Finalizado,Cancelado',
            'fecha_hora'    => 'required|date',
            'hora_salida'   => 'nullable|date_format:H:i',
            'observaciones' => 'nullable|string|max:5000',
            'id_ambulancia' => 'required|exists:ambulancia,id_ambulancia',
            'id_usuario'    => 'required|exists:cliente,id_usuario',
        ]);

        DB::beginTransaction();

        try {
            $servicio->update([
                'costo_total'   => $request->costo_total,
                'estado'        => $request->estado,
                'fecha_hora'    => $request->fecha_hora,
                'hora_salida'   => $request->hora_salida,
                'observaciones' => $request->observaciones,
                'id_ambulancia' => $request->id_ambulancia,
                'id_usuario'    => $request->id_usuario,
            ]);

            DB::commit();

            return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        DB::beginTransaction();

        try {
            $servicio->delete();

            DB::commit();

            return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}