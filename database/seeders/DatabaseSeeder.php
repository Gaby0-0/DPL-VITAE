<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── EMPRESA ────────────────────────────────────────────────────────
        DB::table('empresa')->insertOrIgnore([
            'nombre'      => 'Vitae Ambulancias',
            'slogan'      => 'Tu salud, nuestra prioridad',
            'descripcion' => 'Somos una empresa dedicada a brindar servicios de traslado médico y atención prehospitalaria con los más altos estándares de calidad. Contamos con personal capacitado y unidades equipadas para atender cualquier emergencia.',
            'mision'      => 'Proporcionar servicios de atención médica prehospitalaria y traslado de pacientes con rapidez, seguridad y calidez humana, garantizando la integridad de quienes confían en nosotros.',
            'vision'      => 'Ser la empresa líder en servicios de ambulancias y atención prehospitalaria en la región, reconocida por nuestra excelencia, tecnología y compromiso con la vida.',
            'valores'     => "Responsabilidad\nCompromiso\nHonestidad\nRespeto\nCalidad\nEmpatía",
            'telefono'    => '951 123 4567',
            'correo'      => 'contacto@vitae.com',
            'sitio_web'   => 'https://www.vitae.com',
            'direccion'   => 'Av. Independencia 100, Centro, Oaxaca de Juárez, Oax.',
            'costo_km'    => 25.00,
        ]);

        DB::table('salario_minimo')->insert([
            ['tipo_empleado' => 'operador', 'monto' => 28.55,'vigente_desde' => '2025-01-01'],
            ['tipo_empleado' => 'administrativo', 'monto' => 35.55,'vigente_desde' => '2025-01-01'],
        ]);

        // ── USUARIOS ───────────────────────────────────────────────────────
        for ($i = 1; $i <= 30; $i++) {
            DB::table('users')->insert([
                'nombre'     => 'Nombre' . $i,
                'ap_paterno' => 'ApellidoP' . $i,
                'ap_materno' => 'ApellidoM' . $i,
                'email'      => 'usuario' . $i . '@correo.com',
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── OPERADORES ─────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('operador')->insert([
                'id_usuario'   => $i,
                'salario_hora' => rand(100, 200),
            ]);
        }

        // ── CLIENTES ───────────────────────────────────────────────────────
        for ($i = 11; $i <= 20; $i++) {
            DB::table('cliente')->insert([
                'id_usuario' => $i,
            ]);
        }

        // ── PARAMEDICOS ────────────────────────────────────────────────────
        for ($i = 21; $i <= 30; $i++) {
            DB::table('paramedico')->insert([
                'id_usuario'   => $i,
                'salario_hora' => rand(120, 220),
            ]);
        }

        // ── TIPOS DE AMBULANCIA ────────────────────────────────────────────
        DB::table('tipo_ambulancia')->insert([
            ['nombre_tipo' => 'Básica',    'descripcion' => 'Ambulancia básica'],
            ['nombre_tipo' => 'Avanzada',  'descripcion' => 'Ambulancia avanzada'],
        ]);

        // ── AMBULANCIAS ────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ambulancia')->insert([
                'placa'              => 'AMB-' . $i,
                'estado'             => 'Disponible',
                'id_tipo_ambulancia' => rand(1, 2),
            ]);
        }

        // ── MUNICIPIOS ─────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('municipio')->insert([
                'nombre_municipio' => 'Municipio ' . $i,
            ]);
        }

        // ── COLONIAS ───────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('colonia')->insert([
                'nombre_colonia' => 'Colonia ' . $i,
                'codigo_postal'  => rand(68000, 68999),
                'id_municipio'   => rand(1, 10),
            ]);
        }

        // ── DIRECCIONES ────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('direccion')->insert([
                'nombre_calle' => 'Calle ' . $i,
                'n_exterior'   => rand(1, 100),
                'n_interior'   => null,
                'id_colonia'   => rand(1, 10),
            ]);
        }

        // ── INSUMOS ────────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('insumo')->insert([
                'nombre_insumo' => 'Insumo ' . $i,
                'costo_unidad'  => rand(50, 300),
            ]);
        }

        // ── SERVICIOS ──────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('servicio')->insert([
                'costo_total'   => rand(500, 3000),
                'estado'        => 'Activo',
                'fecha_hora'    => now(),
                'hora_salida'   => now(),
                'observaciones' => 'Observación ' . $i,
                'tipo'          => 'Traslado',
                'id_ambulancia' => rand(1, 10),
                'id_cliente'    => rand(11, 20),
            ]);
        }

        // ── PACIENTES ──────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('paciente')->insert([
                'nombre'          => 'Paciente ' . $i,
                'ap_paterno'      => 'ApellidoP ' . $i,
                'ap_materno'      => 'ApellidoM ' . $i,
                'oxigeno'         => 'No',
                'fecha_nacimiento' => '1990-01-01',
                'sexo'            => 'Masculino',
                'peso'            => rand(60, 90),
                'id_servicio'     => rand(1, 10),
                'id_direccion'    => rand(1, 10),
            ]);
        }

        // ── EVENTOS ────────────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('evento')->insert([
                'id_servicio' => rand(1, 10),
                'duracion'    => '2 horas',
                'personas'    => rand(5, 50),
            ]);
        }

        // ── PADECIMIENTOS ──────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('padecimiento')->insert([
                'nombre_padecimiento' => 'Padecimiento ' . $i,
                'nivel_riesgo'        => 'Medio',
                'costo_extra'         => rand(100, 500),
            ]);
        }

        // ── SERVICIO_PARAMEDICO ────────────────────────────────────────────
        for ($i = 1; $i <= 20; $i++) {
            DB::table('servicio_paramedico')->insertOrIgnore([
                'id_servicio'   => rand(1, 10),
                'id_paramedico' => rand(21, 30),
            ]);
        }

        // ── SERVICIO_INSUMO ────────────────────────────────────────────────
        for ($i = 1; $i <= 10; $i++) {
            DB::table('servicio_insumo')->insert([
                'id_servicio' => $i,
                'id_insumo'   => $i,
            ]);
        }

        // ── PACIENTE_PADECIMIENTO ──────────────────────────────────────────
        for ($i = 1; $i <= 20; $i++) {
            DB::table('paciente_padecimiento')->insertOrIgnore([
                'id_paciente'    => rand(1, 10),
                'id_padecimiento' => rand(1, 10),
            ]);
        }
    }
}