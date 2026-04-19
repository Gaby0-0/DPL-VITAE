<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Servicio;

class Operador extends Model
{
    use SoftDeletes;

    protected $table = 'operador';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    public $timestamps = false;

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
        return $this->hasMany(Servicio::class,'id_operador');
    }
}
