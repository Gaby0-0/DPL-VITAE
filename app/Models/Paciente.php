<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'id_paciente';

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'oxigeno',
        'fecha_nacimiento',
        'sexo',
        'peso'
    ];

    public function Traslado()
    {
        return $this->hasOne(Traslado::class,'id_servicio');
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