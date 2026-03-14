<?php

namespace App\Http\Controllers;

use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use App\Models\Operador;
use Illuminate\Http\Request;

class AmbulanciasController extends Controller
{
    public function index()
    {
        $ambulancias = Ambulancia::with(['tipo', 'operador'])->get();

        return view('admin.ambulancias.index', compact('ambulancias'));
    }
}
