<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;

    protected $fillable = [
        'id_servicio',
        'tipo',
        'duracion',
        'personas',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
}