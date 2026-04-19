<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;

    protected $table = 'servicio';
    protected $primaryKey = 'id_servicio';
    public $timestamps = false;

    protected $casts = [
        'deleted_at' => 'datetime',
        'fecha_hora' => 'datetime',
        'hora_salida' => 'datetime',
    ];

    protected $fillable = [
        'costo_total',
        'estado',
        'fecha_hora',
        'hora_salida',
        'observaciones',
        'tipo',
        'id_ambulancia',
        'id_cliente',
        'id_operador',
    ];

    public function ambulancia()
    {
        return $this->belongsTo(Ambulancia::class,'id_ambulancia');
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class,'id_operador');
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