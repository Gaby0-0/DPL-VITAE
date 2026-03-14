<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Padecimiento extends Model
{
    protected $table = 'padecimiento';
    protected $primaryKey = 'id_padecimiento';
    public $timestamps = false;


    protected $fillable = [
        'nombre_padecimiento',
        'nivel_riesgo',
        'costo_extra'
    ];

    public function pacientes()
    {
        return $this->belongsToMany(
            Paciente::class,
            'paciente_padecimiento',
            'id_padecimiento',
            'id_paciente'
        );
    }
}