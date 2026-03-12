<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // USUARIOS
        for ($i=1; $i<=30; $i++) {
            DB::table('users')->insert([
                'nombre' => 'Nombre'.$i,
                'ap_paterno' => 'ApellidoP'.$i,
                'ap_materno' => 'ApellidoM'.$i,
                'email' => 'usuario'.$i.'@correo.com',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // OPERADORES
        for ($i=1; $i<=10; $i++) {
            DB::table('operador')->insert([
                'id_usuario' => $i,
                'salario_hora' => rand(100,200)
            ]);
        }

        // CLIENTES
        for ($i=11; $i<=20; $i++) {
            DB::table('cliente')->insert([
                'id_usuario' => $i
            ]);
        }

        // PARAMEDICOS
        for ($i=21; $i<=30; $i++) {
            DB::table('paramedico')->insert([
                'id_usuario' => $i,
                'salario_hora' => rand(120,220)
            ]);
        }

        // TIPO AMBULANCIA
        for ($i=1; $i<=10; $i++) {
            DB::table('tipo_ambulancia')->insert([
                'nombre_tipo' => 'Tipo '.$i,
                'descripcion' => 'Descripción tipo '.$i
            ]);
        }

        // AMBULANCIAS
        for ($i=1; $i<=10; $i++) {
            DB::table('ambulancia')->insert([
                'placa' => 'AMB-'.$i,
                'estado' => 'Disponible',
                'id_tipo_ambulancia' => rand(1,10),
                'id_operador' => rand(1,10)
            ]);
        }

        // MUNICIPIOS
        for ($i=1; $i<=10; $i++) {
            DB::table('municipio')->insert([
                'nombre_municipio' => 'Municipio '.$i
            ]);
        }

        // COLONIAS
        for ($i=1; $i<=10; $i++) {
            DB::table('colonia')->insert([
                'nombre_colonia' => 'Colonia '.$i,
                'codigo_postal' => rand(68000,68999),
                'id_municipio' => rand(1,10)
            ]);
        }

        // DIRECCIONES
        for ($i=1; $i<=10; $i++) {
            DB::table('direccion')->insert([
                'nombre_calle' => 'Calle '.$i,
                'n_exterior' => rand(1,100),
                'n_interior' => null,
                'id_colonia' => rand(1,10)
            ]);
        }

        // INSUMOS
        for ($i=1; $i<=10; $i++) {
            DB::table('insumo')->insert([
                'nombre_insumo' => 'Insumo '.$i,
                'costo_unidad' => rand(50,300)
            ]);
        }

        // SERVICIOS
        for ($i=1; $i<=10; $i++) {
            DB::table('servicio')->insert([
                'costo_total' => rand(500,3000),
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'hora_salida' => now(),
                'observaciones' => 'Observación '.$i,
                'tipo' => 'Traslado',
                'id_ambulancia' => rand(1,10),
                'id_cliente' => rand(11,20)
            ]);
        }

        // PACIENTES
        for ($i=1; $i<=10; $i++) {
            DB::table('paciente')->insert([
                'nombre' => 'Paciente '.$i,
                'ap_paterno' => 'ApellidoP '.$i,
                'ap_materno' => 'ApellidoM '.$i,
                'oxigeno' => 'No',
                'fecha_nacimiento' => '1990-01-01',
                'sexo' => 'Masculino',
                'peso' => rand(60,90),
                'id_servicio' => rand(1,10),
                'id_direccion' => rand(1,10)
            ]);
        }

        // EVENTOS
        for ($i=1; $i<=10; $i++) {
            DB::table('evento')->insert([
                'id_servicio' => rand(1,10),
                'duracion' => '2 horas',
                'personas' => rand(5,50)
            ]);
        }

        // PADECIMIENTOS
        for ($i=1; $i<=10; $i++) {
            DB::table('padecimiento')->insert([
                'nombre_padecimiento' => 'Padecimiento '.$i,
                'nivel_riesgo' => 'Medio',
                'costo_extra' => rand(100,500)
            ]);
        }

        // SERVICIO_PARAMEDICO
        for ($i=1; $i<=20; $i++) {
            DB::table('servicio_paramedico')->insertOrIgnore([
                'id_servicio' => rand(1,10),
                'id_paramedico' => rand(21,30)
            ]);
        }

        // SERVICIO_INSUMO
        for ($i=1; $i<=10; $i++) {
            DB::table('servicio_insumo')->insert([
                'id_servicio' => $i,
                'id_insumo' => $i
            ]);
        }

        // PACIENTE_PADECIMIENTO
        for ($i=1; $i<=20; $i++) {
            DB::table('paciente_padecimiento')->insertOrIgnore([
                'id_paciente' => rand(1,10),
                'id_padecimiento' => rand(1,10)
            ]);
        }

    }
}
