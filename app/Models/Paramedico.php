<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paramedico extends Model
{
    protected $table = 'paramedico';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'salario_hora'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'servicio_paramedico',
            'id_paramedico',
            'id_servicio'
        );
    }
}