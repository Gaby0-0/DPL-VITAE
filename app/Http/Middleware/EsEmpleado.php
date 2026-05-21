<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsEmpleado
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user->loadMissing(['operador', 'paramedico', 'cliente']);

        if (!$user->esEmpleado()) {
            if ($user->esCliente()) {
                return redirect()->route('cotizaciones.mis-solicitudes');
            }
            return redirect()->route('dashboard');
        }

        if (!$user->activo) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->with('status', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        return $next($request);
    }
}
