<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direccion';
    protected $primaryKey = 'id_direccion';

    protected $fillable = [
        'nombre_calle',
        'n_exterior',
        'n_interior',
        'id_colonia'
    ];

    public function colonia()
    {
        return $this->belongsTo(Colonia::class,'id_colonia');
    }
}