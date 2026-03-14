<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAmbulancia extends Model
{
    protected $table = 'tipo_ambulancia';
    protected $primaryKey = 'id_tipo_ambulancia';
    public $timestamps = false;


    protected $fillable = [
        'nombre_tipo',
        'descripcion'
    ];

    public function ambulancias()
    {
        return $this->hasMany(Ambulancia::class,'id_tipo_ambulancia');
    }
}
