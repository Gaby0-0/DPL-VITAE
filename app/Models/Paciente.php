<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use SoftDeletes;

    protected $table = 'paciente';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'oxigeno',
        'fecha_nacimiento',
        'sexo',
        'peso',
        'id_servicio',
        'id_direccion'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class,'id_servicio');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class,'id_direccion');
    }

    public function padecimientos()
    {
        return $this->belongsToMany(
            Padecimiento::class,
            'paciente_padecimiento',
            'id_paciente',
            'id_padecimiento'
        );
    }
}