<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsumosController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        return view('admin.insumos.index', compact('insumos'));
    }

    
}