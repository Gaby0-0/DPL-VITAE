<?php

namespaceApp\Models;

use Illuminate\Database\Eloquent\Model;

class Traslado extends Model{
    protected $table = 'traslado';
    protected $primaryKey = 'id_traslado';

    protected $fillable = [
        'redondo',
        'id_servicio',
        'id_origen',
        'id_destino',
        'id_paciente'
    ];

    public function servicio(){
        return $this-> belongsTo(Servicio::class,'id_servicio');
    }

    public function origen(){
        return $this-> belongsTo(Direccion::class, 'id_origen');
    }

    public function destino(){
        return $this-> belongsTo(Direccion::class, 'id_destino');
    }

    public function paciente(){
        return $this-> belongsTo(Paciente::class, 'id_paciente');
    }
}