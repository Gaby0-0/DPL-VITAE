<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    protected $table = 'colonia';
    protected $primaryKey = 'id_colonia';

    protected $fillable = [
        'nombre_colonia',
        'codigo_postal',
        'id_municipio'
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class,'id_municipio');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class,'id_colonia');
    }
}