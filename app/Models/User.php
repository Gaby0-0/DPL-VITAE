<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function esEmpleado(): bool
    {
        return $this->operador !== null || $this->paramedico !== null;
    }

    public function esCliente(): bool
    {
        return $this->cliente !== null;
    }

    public function esAdmin(): bool
    {
        return $this->operador === null && $this->paramedico === null && $this->cliente === null;
    }

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