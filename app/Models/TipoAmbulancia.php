<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAmbulancia extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_ambulancia';
    protected $primaryKey = 'id_tipo_ambulancia';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo',
        'descripcion',
        'costo_base',
    ];

    public function ambulancias()
    {
        return $this->hasMany(Ambulancia::class,'id_tipo_ambulancia');
    }
}
