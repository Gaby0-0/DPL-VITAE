<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'slogan',
        'mision',
        'vision',
        'valores',
        'descripcion',
        'logo',
        'logo_nombre',
        'logo_tipo',
        'imagen',
        'imagen_nombre',
        'imagen_tipo',
        'telefono',
        'correo',
        'sitio_web',
        'direccion'
    ];
}