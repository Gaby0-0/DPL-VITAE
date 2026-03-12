<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;

    protected $fillable = [
        'id_usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class,'id_cliente');
    }
}