<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicio';
    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'costo_total',
        'estado',
        'fecha_hora',
        'hora_salida',
        'observaciones',
        'id_ambulancia',
        'id_cliente'
    ];

     // Un servicio tiene una ambulancia
    public function ambulancia()
    {
        return $this->belongsTo(Ambulancia::class, 'id_ambulancia');
    }

    // Un servicio pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    
    public function insumo(){
        return $this->belongsToMany(
            Insumo::class,
            'Servicio_Insumo',
            'id_servicio',
            'id_insumo'
        );
    }

    public function paramedico(){
        return $this->belongsToMany(
            Paramedico::class,
            'Servicio_Paramedico',
            'id_servicio',
            'id_paramedico'
        );
    }

    public function operador(){
        return $this->belongsTo(operador::class, 'id_operador');
    }
}