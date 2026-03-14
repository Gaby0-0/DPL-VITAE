<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambulancia extends Model
{
    protected $table = 'ambulancia';
    protected $primaryKey = 'id_ambulancia';
    public $timestamps = false;


    protected $fillable = [
        'placa',
        'estado',
        'id_tipo_ambulancia',
        'id_operador'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoAmbulancia::class,'id_tipo_ambulancia');
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class,'id_operador');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class,'id_ambulancia');
    }
}