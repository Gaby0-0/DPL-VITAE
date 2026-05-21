<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\Cliente;
use App\Models\Operador;
use App\Models\Servicio;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like    = "%{$q}%";
        $results = [];

        // Ambulancias por placa
        Ambulancia::where('placa', 'like', $like)
            ->limit(5)->get()
            ->each(function ($a) use (&$results) {
                $results[] = [
                    'grupo' => 'Ambulancias',
                    'icono' => 'bx-ambulance',
                    'label' => $a->placa,
                    'sub'   => $a->estado,
                    'url'   => route('ambulancias.show', $a),
                ];
            });

        // Clientes por nombre / email
        Cliente::with('usuario')
            ->whereHas('usuario', fn($u) => $u
                ->where('nombre',     'like', $like)
                ->orWhere('ap_paterno','like', $like)
                ->orWhere('email',     'like', $like))
            ->limit(5)->get()
            ->each(function ($c) use (&$results) {
                $nombre = trim(($c->usuario->nombre ?? '') . ' ' . ($c->usuario->ap_paterno ?? ''));
                $results[] = [
                    'grupo' => 'Clientes',
                    'icono' => 'bx-user',
                    'label' => $nombre,
                    'sub'   => $c->usuario->email ?? '',
                    'url'   => route('clientes.show', $c),
                ];
            });

        // Operadores por nombre
        Operador::with('usuario')
            ->whereHas('usuario', fn($u) => $u
                ->where('nombre',      'like', $like)
                ->orWhere('ap_paterno', 'like', $like))
            ->limit(5)->get()
            ->each(function ($o) use (&$results) {
                $nombre = trim(($o->usuario->nombre ?? '') . ' ' . ($o->usuario->ap_paterno ?? ''));
                $results[] = [
                    'grupo' => 'Operadores',
                    'icono' => 'bx-id-card',
                    'label' => $nombre,
                    'sub'   => $o->usuario->email ?? '',
                    'url'   => route('operadores.show', $o),
                ];
            });

        // Servicios por ID numérico
        if (is_numeric($q)) {
            Servicio::where('id_servicio', (int) $q)
                ->limit(3)->get()
                ->each(function ($s) use (&$results) {
                    $results[] = [
                        'grupo' => 'Servicios',
                        'icono' => 'bx-map-pin',
                        'label' => 'Servicio #' . $s->id_servicio,
                        'sub'   => $s->estado . ' · ' . ($s->tipo ?? '—'),
                        'url'   => route('servicios.show', $s),
                    ];
                });
        }

        // Cotizaciones por número de guía o nombre del solicitante
        Cotizacion::where('numero_guia', 'like', $like)
            ->orWhere('nombre', 'like', $like)
            ->limit(5)->get()
            ->each(function ($c) use (&$results) {
                $results[] = [
                    'grupo' => 'Cotizaciones',
                    'icono' => 'bx-file',
                    'label' => $c->numero_guia,
                    'sub'   => $c->nombre . ' · ' . $c->estado,
                    'url'   => route('cotizaciones.show', $c),
                ];
            });

        return response()->json($results);
    }
}
