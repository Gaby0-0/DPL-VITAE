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
        'tipo',
        'id_ambulancia',
        'id_cliente'
    ];

    public function ambulancia()
    {
        return $this->belongsTo(Ambulancia::class,'id_ambulancia');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class,'id_servicio');
    }

    public function paramedicos()
    {
        return $this->belongsToMany(
            Paramedico::class,
            'servicio_paramedico',
            'id_servicio',
            'id_paramedico'
        );
    }

    public function insumos()
    {
        return $this->belongsToMany(
            Insumo::class,
            'servicio_insumo',
            'id_servicio',
            'id_insumo'
        );
    }
}