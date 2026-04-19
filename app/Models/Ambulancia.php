<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambulancia extends Model
{
    use SoftDeletes;

    protected $table = 'ambulancia';
    protected $primaryKey = 'id_ambulancia';
    public $timestamps = false;

    protected $fillable = [
        'placa',
        'estado',
        'id_tipo_ambulancia',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoAmbulancia::class,'id_tipo_ambulancia');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class,'id_ambulancia');
    }
}