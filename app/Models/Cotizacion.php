<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'user_id',
        'numero_guia',
        'nombre',
        'telefono',
        'correo',
        'tipo_servicio',
        'tipo_ambulancia_preferida',
        'descripcion',
        'fecha_requerida',
        'origen',
        'lat_origen',
        'lng_origen',
        'destino',
        'lat_destino',
        'lng_destino',
        'personas',
        'padecimientos_paciente',
        'estado',
        'respuesta',
        'km_distancia',
        'costo',
        'incluye',
        'nombre_paciente',
        'id_ambulancia',
        'horas_servicio',
        'paramedicos_ids',
        'insumos_seleccionados',
        'costo_km_unitario',
        'costo_ambulancia',
        'costo_paramedicos',
        'costo_insumos',
        'decision_cliente',
        'comentario_cliente',
        'datos_paciente',
        'anticipo',
        'mp_preference_id',
        'mp_payment_id',
        'mp_pago_estado',
    ];

    protected $casts = [
        'paramedicos_ids'      => 'array',
        'insumos_seleccionados' => 'array',
        'datos_paciente'       => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id_usuario');
    }

    public function ambulancia()
    {
        return $this->belongsTo(Ambulancia::class, 'id_ambulancia');
    }

    public static function generarGuia(): string
    {
        do {
            $guia = 'COT-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('numero_guia', $guia)->exists());
    
        return $guia;
    }

    // Función para calcular la distancia entre dos puntos geográficos usando la fórmula de Haversine
    public static function haversineKm($lat1, $lng1, $lat2, $lng2): float
    {
        // Radio de la Tierra en kilómetros
        // Fórmula de Haversine
        //para calcular la distancia entre dos puntos geográficos dados sus latitudes y longitudes
        $R    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return round($R * 2 * atan2(sqrt($a), sqrt(1 - $a)), 2);
    }
}
