<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insumo extends Model
{
    use SoftDeletes;

    protected $table = 'insumo';
    protected $primaryKey = 'id_insumo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_insumo',
        'costo_unidad'
    ];

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'servicio_insumo',
            'id_insumo',
            'id_servicio'
        );
    }
}