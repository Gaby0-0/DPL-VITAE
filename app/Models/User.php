<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function operador()
    {
        return $this->hasOne(Operador::class,'id_usuario');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class,'id_usuario');
    }

    public function paramedico()
    {
        return $this->hasOne(Paramedico::class,'id_usuario');
    }
}