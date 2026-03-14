<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Padecimiento;

class PadecimientosController extends Controller
{
    public function index()
    {
        $padecimientos = Padecimiento::all();
        return view('admin.padecimientos.index', compact('padecimientos'));
    }
}
