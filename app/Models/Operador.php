<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operador extends Model
{
    protected $table = 'operador';
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

    public function ambulancias()
    {
        return $this->hasMany(Ambulancia::class,'id_operador');
    }
}
