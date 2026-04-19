<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colonia extends Model
{
    use SoftDeletes;

    protected $table = 'colonia';
    protected $primaryKey = 'id_colonia';
    public $timestamps = false;

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