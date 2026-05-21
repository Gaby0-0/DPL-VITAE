<?php

namespace App\Console\Commands;

use App\Models\Cotizacion;
use Illuminate\Console\Command;

class CancelarCotizacionesExpiradas extends Command
{
    protected $signature   = 'cotizaciones:cancelar-expiradas';
    protected $description = 'Cancela cotizaciones aceptadas cuyo plazo de confirmación expiró sin respuesta del cliente';

    public function handle(): void
    {
        $canceladas = Cotizacion::where('estado', 'Aceptada')
            ->whereNull('decision_cliente')
            ->whereNotNull('confirmacion_expires_at')
            ->where('confirmacion_expires_at', '<', now())
            ->update([
                'estado'    => 'Cancelada',
                'respuesta' => 'El tiempo límite para confirmar el servicio ha expirado.',
            ]);

        $this->info("Cotizaciones canceladas por expiración: {$canceladas}");
    }
}
