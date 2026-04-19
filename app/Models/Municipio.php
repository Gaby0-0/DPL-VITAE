<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use SoftDeletes;

    protected $table = 'municipio';
    protected $primaryKey = 'id_municipio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_municipio'
    ];

    public function colonias()
    {
        return $this->hasMany(Colonia::class,'id_municipio');
    }
}