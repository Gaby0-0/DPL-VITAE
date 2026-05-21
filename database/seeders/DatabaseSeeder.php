<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Borrar todo ──────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ([
            'paciente_padecimiento','servicio_insumo','servicio_paramedico',
            'paciente','evento','servicio','cotizaciones',
            'ambulancia','tipo_ambulancia',
            'paramedico','operador','cliente','users',
            'empresa','insumo','padecimiento','salario_minimo',
            'direccion','colonia','municipio',
        ] as $tabla) {
            DB::table($tabla)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $pass = Hash::make('12345678');
        $now  = Carbon::now();

        // ── EMPRESA ──────────────────────────────────────────────────────────
        DB::table('empresa')->insert([
            'nombre'            => 'Vitae Ambulancias',
            'slogan'            => 'Tu salud, nuestra prioridad',
            'descripcion'       => 'Empresa líder en traslados médicos y atención prehospitalaria en Oaxaca.',
            'mision'            => 'Brindar atención médica prehospitalaria con rapidez, seguridad y calidez.',
            'vision'            => 'Ser la empresa líder en servicios de ambulancias en la región.',
            'valores'           => "Responsabilidad\nCompromiso\nHonestidad\nRespeto",
            'telefono'          => '951 123 4567',
            'correo'            => 'contacto@vitae.com',
            'sitio_web'         => 'https://vitae.com',
            'direccion'         => 'Av. Independencia 100, Centro, Oaxaca de Juárez, Oax.',
            'costo_km'          => 25.00,
            'tarifa_operador'   => 80.00,
            'tarifa_paramedico' => 70.00,
            'lat_base'          => 17.0732000,
            'lng_base'          => -96.7266000,
        ]);

        // ── SALARIO MÍNIMO ───────────────────────────────────────────────────
        DB::table('salario_minimo')->insert([
            ['tipo_empleado' => 'operador',       'monto' => 28.55, 'vigente desde' => '2025-01-01'],
            ['tipo_empleado' => 'administrativo', 'monto' => 35.55, 'vigente desde' => '2025-01-01'],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // ADMIN  (user_id = 1)
        // ────────────────────────────────────────────────────────────────────
        DB::table('users')->insert([
            'nombre'     => 'Admin',
            'ap_paterno' => 'DPL',
            'ap_materno' => 'Vitae',
            'email'      => 'admin@dpl.com',
            'password'   => $pass,
            'activo'     => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        // id_usuario = 1 — sin registro en operador/paramedico/cliente → es admin

        // ────────────────────────────────────────────────────────────────────
        // OPERADORES  (user_ids 2-6)
        // ────────────────────────────────────────────────────────────────────
        foreach ([
            [2, 'Carlos',   'Mendez',   'Ríos',     'carlos@dpl.com',   '951 111 0001'],
            [3, 'Roberto',  'Torres',   'Vega',     'roberto@dpl.com',  '951 111 0002'],
            [4, 'Miguel',   'Ruiz',     'Castillo', 'miguel@dpl.com',   '951 111 0003'],
            [5, 'Fernando', 'López',    'Moreno',   'fernando@dpl.com', '951 111 0004'],
            [6, 'Eduardo',  'Sánchez',  'Cruz',     'eduardo@dpl.com',  '951 111 0005'],
        ] as [$uid, $nom, $pat, $mat, $email, $tel]) {
            DB::table('users')->insert([
                'nombre'     => $nom, 'ap_paterno' => $pat, 'ap_materno' => $mat,
                'email'      => $email, 'telefono' => $tel,
                'password'   => $pass, 'activo' => true,
                'created_at' => $now,  'updated_at' => $now,
            ]);
            DB::table('operador')->insert(['id_usuario' => $uid, 'salario_hora' => 0]);
        }

        // ────────────────────────────────────────────────────────────────────
        // PARAMÉDICOS  (user_ids 7-11)
        // ────────────────────────────────────────────────────────────────────
        foreach ([
            [7,  'Ana',      'García',    'López',   'ana@dpl.com',      '951 222 0001'],
            [8,  'Laura',    'Martínez',  'Pérez',   'laura@dpl.com',    '951 222 0002'],
            [9,  'Sofía',    'Hernández', 'Díaz',    'sofia@dpl.com',    '951 222 0003'],
            [10, 'Patricia', 'Gómez',     'Reyes',   'patricia@dpl.com', '951 222 0004'],
            [11, 'María',    'Díaz',      'Flores',  'maria@dpl.com',    '951 222 0005'],
        ] as [$uid, $nom, $pat, $mat, $email, $tel]) {
            DB::table('users')->insert([
                'nombre'     => $nom, 'ap_paterno' => $pat, 'ap_materno' => $mat,
                'email'      => $email, 'telefono' => $tel,
                'password'   => $pass, 'activo' => true,
                'created_at' => $now,  'updated_at' => $now,
            ]);
            DB::table('paramedico')->insert(['id_usuario' => $uid, 'salario_hora' => 0]);
        }

        // ────────────────────────────────────────────────────────────────────
        // CLIENTES  (user_ids 12-16)
        // ────────────────────────────────────────────────────────────────────
        foreach ([
            [12, 'José',      'Rodríguez', 'Soto',    'jose@cliente.com',      '951 333 0001'],
            [13, 'Carmen',    'López',     'Torres',  'carmen@cliente.com',    '951 333 0002'],
            [14, 'David',     'Torres',    'Ramírez', 'david@cliente.com',     '951 333 0003'],
            [15, 'Valentina', 'Cruz',      'Mendoza', 'valentina@cliente.com', '951 333 0004'],
            [16, 'Alejandro', 'Morales',   'Jiménez', 'alejandro@cliente.com', '951 333 0005'],
        ] as [$uid, $nom, $pat, $mat, $email, $tel]) {
            DB::table('users')->insert([
                'nombre'     => $nom, 'ap_paterno' => $pat, 'ap_materno' => $mat,
                'email'      => $email, 'telefono' => $tel,
                'password'   => $pass, 'activo' => true,
                'created_at' => $now,  'updated_at' => $now,
            ]);
            DB::table('cliente')->insert(['id_usuario' => $uid]);
        }

        // ────────────────────────────────────────────────────────────────────
        // TIPOS DE AMBULANCIA  (ids 1-5)
        // ────────────────────────────────────────────────────────────────────
        DB::table('tipo_ambulancia')->insert([
            ['nombre_tipo' => 'Básica',     'descripcion' => 'Traslados simples de pacientes estables.',            'costo_base' => 800.00],
            ['nombre_tipo' => 'Avanzada',   'descripcion' => 'Soporte vital avanzado y monitoreo continuo.',       'costo_base' => 1200.00],
            ['nombre_tipo' => 'Neonatal',   'descripcion' => 'Incubadora y equipo especializado para neonatos.',   'costo_base' => 1500.00],
            ['nombre_tipo' => 'Bariatrica', 'descripcion' => 'Equipo reforzado para pacientes con sobrepeso.',     'costo_base' => 1100.00],
            ['nombre_tipo' => 'UCI Móvil',  'descripcion' => 'Unidad de cuidados intensivos completamente equipada.', 'costo_base' => 2000.00],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // AMBULANCIAS  (ids 1-5)
        // ────────────────────────────────────────────────────────────────────
        DB::table('ambulancia')->insert([
            ['placa' => 'OAX-001-BAS', 'estado' => 'Disponible', 'costo' => 0, 'id_tipo_ambulancia' => 1],
            ['placa' => 'OAX-002-AVZ', 'estado' => 'Disponible', 'costo' => 0, 'id_tipo_ambulancia' => 2],
            ['placa' => 'OAX-003-NEO', 'estado' => 'Disponible', 'costo' => 0, 'id_tipo_ambulancia' => 3],
            ['placa' => 'OAX-004-BAR', 'estado' => 'Disponible', 'costo' => 0, 'id_tipo_ambulancia' => 4],
            ['placa' => 'OAX-005-UCI', 'estado' => 'Disponible', 'costo' => 0, 'id_tipo_ambulancia' => 5],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // INSUMOS  (ids 1-5)
        // ────────────────────────────────────────────────────────────────────
        DB::table('insumo')->insert([
            ['nombre_insumo' => 'Desfibrilador portátil', 'costo_unidad' => 650.00],
            ['nombre_insumo' => 'Tanque de oxígeno',      'costo_unidad' => 200.00],
            ['nombre_insumo' => 'Kit de trauma básico',   'costo_unidad' => 350.00],
            ['nombre_insumo' => 'Monitor cardíaco',       'costo_unidad' => 500.00],
            ['nombre_insumo' => 'Camilla de traslado',    'costo_unidad' => 180.00],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // PADECIMIENTOS  (ids 1-5)
        // ────────────────────────────────────────────────────────────────────
        DB::table('padecimiento')->insert([
            ['nombre_padecimiento' => 'Diabetes mellitus tipo 2', 'nivel_riesgo' => 'Alto',    'costo_extra' => 150.00],
            ['nombre_padecimiento' => 'Hipertensión arterial',    'nivel_riesgo' => 'Medio',   'costo_extra' => 100.00],
            ['nombre_padecimiento' => 'Fractura ósea',            'nivel_riesgo' => 'Medio',   'costo_extra' => 200.00],
            ['nombre_padecimiento' => 'Infarto agudo de miocardio','nivel_riesgo' => 'Crítico', 'costo_extra' => 500.00],
            ['nombre_padecimiento' => 'Insuficiencia respiratoria','nivel_riesgo' => 'Alto',    'costo_extra' => 350.00],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // MUNICIPIO / COLONIA  (para pacientes si se necesitan)
        // ────────────────────────────────────────────────────────────────────
        DB::table('municipio')->insert(['nombre_municipio' => 'Oaxaca de Juárez']);
        DB::table('colonia')->insert([
            'nombre_colonia' => 'Centro Histórico',
            'codigo_postal'  => '68000',
            'id_municipio'   => 1,
        ]);

        // ────────────────────────────────────────────────────────────────────
        // COTIZACIONES
        // Coordenadas reales en Oaxaca de Juárez:
        //   Base empresa   : 17.07320, -96.72660
        //   Hospital Gral  : 17.06510, -96.71980
        //   Estadio BJ     : 17.09120, -96.71800
        //   Col. Reforma   : 17.05220, -96.73150
        //   Las Ánimas     : 17.09120, -96.71800
        // ────────────────────────────────────────────────────────────────────
        $tarifaOp  = 80.00;
        $tarifaPm  = 70.00;
        $costoPorKm = 25.00;

        // ── COT 1: Traslado · Pendiente · cliente José (espera revisión admin)
        DB::table('cotizaciones')->insert([
            'numero_guia'      => 'COT-2026-AA1111',
            'user_id'          => 12,
            'nombre'           => 'José Rodríguez Soto',
            'telefono'         => '951 333 0001',
            'correo'           => 'jose@cliente.com',
            'tipo_servicio'    => 'Traslado',
            'descripcion'      => 'Traslado de paciente diabético a Hospital General para revisión de urgencia.',
            'fecha_requerida'  => Carbon::now()->addDays(2)->format('Y-m-d'),
            'hora_requerida'   => '10:00:00',
            'origen'           => 'Av. Independencia 100, Centro, Oaxaca',
            'lat_origen'       => 17.07320, 'lng_origen' => -96.72660,
            'destino'          => 'Hospital General Dr. Aurelio Valdivieso, Oaxaca',
            'lat_destino'      => 17.06510, 'lng_destino' => -96.71980,
            'personas'         => 1,
            'requiere_oxigeno' => false,
            'estado'           => 'Pendiente',
            'km_distancia'     => null,
            'created_at'       => $now, 'updated_at' => $now,
        ]); // id=1

        // ── COT 2: Traslado · Aceptada · cliente Carmen · sin decisión del cliente
        $h2 = 3; $nPm2 = 2; $km2 = 5.7;
        $c2 = 800.00*$h2 + $tarifaOp*$h2 + $tarifaPm*$nPm2*$h2 + $km2*$costoPorKm; // 2400+240+420+142.5 = 3202.50
        $anticipo2 = round($c2 * 0.5, 2);
        DB::table('cotizaciones')->insert([
            'numero_guia'           => 'COT-2026-BB2222',
            'user_id'               => 13,
            'nombre'                => 'Carmen López Torres',
            'telefono'              => '951 333 0002',
            'correo'                => 'carmen@cliente.com',
            'tipo_servicio'         => 'Traslado',
            'descripcion'           => 'Traslado post-operatorio desde hospital a domicilio.',
            'fecha_requerida'       => Carbon::now()->addDays(3)->format('Y-m-d'),
            'hora_requerida'        => '14:00:00',
            'origen'                => 'Hospital General Dr. Aurelio Valdivieso, Oaxaca',
            'lat_origen'            => 17.06510, 'lng_origen' => -96.71980,
            'destino'               => 'Las Ánimas, Oaxaca',
            'lat_destino'           => 17.09120, 'lng_destino' => -96.71800,
            'personas'              => 1,
            'requiere_oxigeno'      => false,
            'estado'                => 'Aceptada',
            'respuesta'             => 'Cotización aprobada. Ambulancia básica con 2 paramédicos asignados.',
            'id_tipo_ambulancia'    => 1,
            'id_ambulancia'         => 1,  // OAX-001-BAS
            'id_operador'           => 4,  // Miguel (user_id=4)
            'paramedicos_ids'       => json_encode([7, 8]),
            'horas_servicio'        => $h2,
            'km_distancia'          => $km2,
            'costo_km_unitario'     => $costoPorKm,
            'costo_ambulancia'      => 800.00 * $h2,
            'costo_paramedicos'     => $tarifaPm * $nPm2 * $h2,
            'costo_insumos'         => 0,
            'costo'                 => $c2,
            'anticipo'              => $anticipo2,
            'confirmacion_expires_at' => Carbon::now()->addDays(2),
            'nombre_paciente'       => 'Pedro López García',
            'padecimientos_paciente'=> 'Post-operatorio rodilla izquierda',
            'created_at'            => $now, 'updated_at' => $now,
        ]); // id=2

        // ── COT 3: Traslado · Aceptada + Confirmada → Servicio 1 (Activo efectivo)
        $h3 = 2; $nPm3 = 1; $km3 = 4.2;
        $c3 = 800.00*$h3 + $tarifaOp*$h3 + $tarifaPm*$nPm3*$h3 + $km3*$costoPorKm; // 1600+160+140+105 = 2005
        DB::table('cotizaciones')->insert([
            'numero_guia'           => 'COT-2026-CC3333',
            'user_id'               => 14,
            'nombre'                => 'David Torres Ramírez',
            'telefono'              => '951 333 0003',
            'correo'                => 'david@cliente.com',
            'tipo_servicio'         => 'Traslado',
            'descripcion'           => 'Traslado urgente por fractura de cadera a clínica especializada.',
            'fecha_requerida'       => Carbon::today()->format('Y-m-d'),
            'hora_requerida'        => '09:00:00',
            'origen'                => 'Colonia Reforma, Oaxaca de Juárez',
            'lat_origen'            => 17.05220, 'lng_origen' => -96.73150,
            'destino'               => 'Hospital General Dr. Aurelio Valdivieso, Oaxaca',
            'lat_destino'           => 17.06510, 'lng_destino' => -96.71980,
            'personas'              => 1,
            'requiere_oxigeno'      => false,
            'estado'                => 'Aceptada',
            'respuesta'             => 'Cotización aprobada. Ambulancia básica con paramédico especializado.',
            'id_tipo_ambulancia'    => 1,
            'id_ambulancia'         => 2,  // OAX-002-AVZ
            'id_operador'           => 3,  // Roberto (user_id=3)
            'paramedicos_ids'       => json_encode([9]),
            'horas_servicio'        => $h3,
            'km_distancia'          => $km3,
            'costo_km_unitario'     => $costoPorKm,
            'costo_ambulancia'      => 800.00 * $h3,
            'costo_paramedicos'     => $tarifaPm * $nPm3 * $h3,
            'costo_insumos'         => 0,
            'costo'                 => $c3,
            'anticipo'              => 0,
            'decision_cliente'      => 'confirmada',
            'comentario_cliente'    => 'Confirmo. Es urgente, fractura ocurrida esta mañana.',
            'nombre_paciente'       => 'Ana Torres Ruiz',
            'padecimientos_paciente'=> 'Fractura de cadera izquierda. Alergias: Penicilina. Médico: Dr. Ramírez.',
            'created_at'            => $now, 'updated_at' => $now,
        ]); // id=3

        // ── COT 4: Evento · Aceptada + Confirmada + Pago completo online → Servicio 2 (Activo online)
        $h4 = 6; $nPm4 = 3;
        $c4 = 1200.00*$h4 + $tarifaOp*$h4 + $tarifaPm*$nPm4*$h4; // 7200+480+1260 = 8940
        DB::table('cotizaciones')->insert([
            'numero_guia'           => 'COT-2026-DD4444',
            'user_id'               => 15,
            'nombre'                => 'Valentina Cruz Mendoza',
            'telefono'              => '951 333 0004',
            'correo'                => 'valentina@cliente.com',
            'tipo_servicio'         => 'Evento',
            'descripcion'           => 'Cobertura médica para torneo deportivo universitario (200 participantes).',
            'fecha_requerida'       => Carbon::today()->format('Y-m-d'),
            'hora_requerida'        => '08:00:00',
            'origen'                => 'Estadio Benito Juárez, Oaxaca',
            'lat_origen'            => 17.09120, 'lng_origen' => -96.71800,
            'destino'               => 'Estadio Benito Juárez, Oaxaca',
            'lat_destino'           => 17.09120, 'lng_destino' => -96.71800,
            'personas'              => 200,
            'requiere_oxigeno'      => false,
            'estado'                => 'Aceptada',
            'respuesta'             => 'Cobertura aprobada. Ambulancia avanzada con 3 paramédicos por 6 horas.',
            'id_tipo_ambulancia'    => 2,
            'id_ambulancia'         => 3,  // OAX-003-NEO
            'id_operador'           => 5,  // Fernando (user_id=5)
            'paramedicos_ids'       => json_encode([7, 8, 9]),
            'horas_servicio'        => $h4,
            'km_distancia'          => 0,
            'costo_km_unitario'     => 0,
            'costo_ambulancia'      => 1200.00 * $h4,
            'costo_paramedicos'     => $tarifaPm * $nPm4 * $h4,
            'costo_insumos'         => 0,
            'costo'                 => $c4,
            'anticipo'              => $c4,        // pago completo anticipado
            'mp_pago_estado'        => 'approved',
            'mp_preference_id'      => 'VITAE-PREF-TEST-4444',
            'mp_payment_id'         => 'VITAE-PAY-TEST-4444',
            'decision_cliente'      => 'confirmada',
            'comentario_cliente'    => 'Pago realizado en línea. Torneo universitario, acceso por puerta norte.',
            'datos_evento'          => json_encode([
                'nombre'          => 'Torneo Universitario Oaxaca 2026',
                'tipo'            => 'Deportivo',
                'personas'        => 200,
                'responsable'     => 'Lic. Luis Pérez',
                'tel_responsable' => '951 555 9999',
                'indicaciones'    => 'Acceso por puerta norte. Estacionamiento reservado junto al estadio.',
            ]),
            'created_at'            => $now, 'updated_at' => $now,
        ]); // id=4

        // ── COT 5: Traslado · Aceptada + Confirmada → Servicio 3 (Finalizado ayer)
        $h5 = 2; $nPm5 = 2; $km5 = 6.0;
        $c5 = 800.00*$h5 + $tarifaOp*$h5 + $tarifaPm*$nPm5*$h5 + $km5*$costoPorKm; // 1600+160+280+150 = 2190
        DB::table('cotizaciones')->insert([
            'numero_guia'           => 'COT-2026-EE5555',
            'user_id'               => 16,
            'nombre'                => 'Alejandro Morales Jiménez',
            'telefono'              => '951 333 0005',
            'correo'                => 'alejandro@cliente.com',
            'tipo_servicio'         => 'Traslado',
            'descripcion'           => 'Traslado urgente de adulto mayor con insuficiencia renal a hospital de especialidades.',
            'fecha_requerida'       => Carbon::now()->subDay()->format('Y-m-d'),
            'hora_requerida'        => '15:00:00',
            'origen'                => 'Las Ánimas, Oaxaca de Juárez',
            'lat_origen'            => 17.09120, 'lng_origen' => -96.71800,
            'destino'               => 'IMSS Hospital de Especialidades, Oaxaca',
            'lat_destino'           => 17.07320, 'lng_destino' => -96.72660,
            'personas'              => 1,
            'requiere_oxigeno'      => true,
            'estado'                => 'Aceptada',
            'respuesta'             => 'Cotización aprobada con prioridad urgente. Ambulancia básica y 2 paramédicos.',
            'id_tipo_ambulancia'    => 1,
            'id_ambulancia'         => 4,  // OAX-004-BAR
            'id_operador'           => 6,  // Eduardo (user_id=6)
            'paramedicos_ids'       => json_encode([10, 11]),
            'horas_servicio'        => $h5,
            'km_distancia'          => $km5,
            'costo_km_unitario'     => $costoPorKm,
            'costo_ambulancia'      => 800.00 * $h5,
            'costo_paramedicos'     => $tarifaPm * $nPm5 * $h5,
            'costo_insumos'         => 0,
            'costo'                 => $c5,
            'anticipo'              => 0,
            'decision_cliente'      => 'confirmada',
            'comentario_cliente'    => 'Es urgente. Abuelo con insuficiencia renal, necesita oxígeno.',
            'nombre_paciente'       => 'Luis Morales Vázquez',
            'padecimientos_paciente'=> 'Insuficiencia renal crónica estadio 4. Médico: Dr. Sánchez - Nefrología.',
            'created_at'            => $now->copy()->subDay(),
            'updated_at'            => $now->copy()->subDay(),
        ]); // id=5

        // ────────────────────────────────────────────────────────────────────
        // SERVICIOS (5)
        // ────────────────────────────────────────────────────────────────────

        // Srv 1 – Traslado ACTIVO hoy – efectivo – COT 3 – Roberto op – Sofía pm
        $srv1 = DB::table('servicio')->insertGetId([
            'tipo'          => 'Traslado',
            'estado'        => 'Activo',
            'fecha_hora'    => Carbon::today()->setTime(9, 0),
            'costo_total'   => $c3,
            'observaciones' => 'Paciente con fractura de cadera. Traslado en progreso.',
            'id_cotizacion' => 3,
            'forma_pago'    => 'efectivo',
            'id_ambulancia' => 2,
            'id_cliente'    => 14,
            'id_operador'   => 3,
        ]);
        DB::table('servicio_paramedico')->insert([['id_servicio' => $srv1, 'id_paramedico' => 9]]);

        // Srv 2 – Evento ACTIVO hoy – online (pago completo) – COT 4 – Fernando op – Ana+Laura+Sofía pm
        $srv2 = DB::table('servicio')->insertGetId([
            'tipo'          => 'Evento',
            'estado'        => 'Activo',
            'fecha_hora'    => Carbon::today()->setTime(8, 0),
            'costo_total'   => $c4,
            'observaciones' => 'Cobertura en torneo deportivo universitario. Personal en posición.',
            'id_cotizacion' => 4,
            'forma_pago'    => 'online',
            'id_ambulancia' => 3,
            'id_cliente'    => 15,
            'id_operador'   => 5,
        ]);
        DB::table('evento')->insert(['id_servicio' => $srv2, 'duracion' => 6, 'personas' => 200]);
        DB::table('servicio_paramedico')->insert([
            ['id_servicio' => $srv2, 'id_paramedico' => 7],
            ['id_servicio' => $srv2, 'id_paramedico' => 8],
            ['id_servicio' => $srv2, 'id_paramedico' => 9],
        ]);

        // Srv 3 – Traslado FINALIZADO ayer – efectivo – COT 5 – Eduardo op – Patricia+María pm
        $srv3 = DB::table('servicio')->insertGetId([
            'tipo'          => 'Traslado',
            'estado'        => 'Finalizado',
            'fecha_hora'    => Carbon::now()->subDay()->setTime(15, 0),
            'hora_salida'   => Carbon::now()->subDay()->setTime(17, 30),
            'costo_total'   => $c5,
            'observaciones' => 'Traslado completado. Paciente entregado a especialistas en IMSS.',
            'id_cotizacion' => 5,
            'forma_pago'    => 'efectivo',
            'id_ambulancia' => 4,
            'id_cliente'    => 16,
            'id_operador'   => 6,
        ]);
        DB::table('servicio_paramedico')->insert([
            ['id_servicio' => $srv3, 'id_paramedico' => 10],
            ['id_servicio' => $srv3, 'id_paramedico' => 11],
        ]);

        // Srv 4 – Evento FINALIZADO hace 3 días – manual (sin cotizacion) – Miguel op
        $srv4 = DB::table('servicio')->insertGetId([
            'tipo'          => 'Evento',
            'estado'        => 'Finalizado',
            'fecha_hora'    => Carbon::now()->subDays(3)->setTime(10, 0),
            'hora_salida'   => Carbon::now()->subDays(3)->setTime(16, 30),
            'costo_total'   => 5400.00,
            'observaciones' => 'Cobertura en concierto al aire libre. Sin incidentes médicos reportados.',
            'forma_pago'    => 'efectivo',
            'id_ambulancia' => 5,
            'id_cliente'    => 13,
            'id_operador'   => 4,
        ]);
        DB::table('evento')->insert(['id_servicio' => $srv4, 'duracion' => 6, 'personas' => 350]);
        DB::table('servicio_paramedico')->insert([
            ['id_servicio' => $srv4, 'id_paramedico' => 7],
            ['id_servicio' => $srv4, 'id_paramedico' => 8],
        ]);

        // Srv 5 – Traslado CANCELADO ayer – Roberto op
        $srv5 = DB::table('servicio')->insertGetId([
            'tipo'          => 'Traslado',
            'estado'        => 'Cancelado',
            'fecha_hora'    => Carbon::now()->subDay()->setTime(11, 0),
            'costo_total'   => 1400.00,
            'observaciones' => 'Cancelado por el cliente antes del inicio del servicio.',
            'forma_pago'    => 'efectivo',
            'id_ambulancia' => 1,
            'id_cliente'    => 12,
            'id_operador'   => 3,
        ]);
        DB::table('servicio_paramedico')->insert([['id_servicio' => $srv5, 'id_paramedico' => 10]]);

        // ────────────────────────────────────────────────────────────────────
        // PACIENTES  (1 por servicio)
        // ────────────────────────────────────────────────────────────────────
        DB::table('paciente')->insert([
            ['nombre'=>'Ana',    'ap_paterno'=>'Torres',    'ap_materno'=>'Ruiz',     'fecha_nacimiento'=>'1979-05-12','sexo'=>'Femenino', 'peso'=>68.5,'oxigeno'=>'No', 'id_servicio'=>$srv1],
            ['nombre'=>'Marco',  'ap_paterno'=>'González',  'ap_materno'=>'Pérez',    'fecha_nacimiento'=>'1996-08-23','sexo'=>'Masculino','peso'=>75.0,'oxigeno'=>'No', 'id_servicio'=>$srv2],
            ['nombre'=>'Luis',   'ap_paterno'=>'Morales',   'ap_materno'=>'Vázquez',  'fecha_nacimiento'=>'1952-03-17','sexo'=>'Masculino','peso'=>82.0,'oxigeno'=>'Sí', 'id_servicio'=>$srv3],
            ['nombre'=>'Rosa',   'ap_paterno'=>'Sandoval',  'ap_materno'=>'Díaz',     'fecha_nacimiento'=>'1969-11-30','sexo'=>'Femenino', 'peso'=>71.0,'oxigeno'=>'No', 'id_servicio'=>$srv4],
            ['nombre'=>'Pedro',  'ap_paterno'=>'Villanueva','ap_materno'=>'Cruz',     'fecha_nacimiento'=>'1984-07-04','sexo'=>'Masculino','peso'=>88.0,'oxigeno'=>'No', 'id_servicio'=>$srv5],
        ]);

        // ────────────────────────────────────────────────────────────────────
        // INSUMOS POR SERVICIO
        // ────────────────────────────────────────────────────────────────────
        DB::table('servicio_insumo')->insert([
            ['id_servicio'=>$srv1,'id_insumo'=>2], // oxígeno
            ['id_servicio'=>$srv1,'id_insumo'=>3], // kit trauma
            ['id_servicio'=>$srv2,'id_insumo'=>1], // desfibrilador
            ['id_servicio'=>$srv2,'id_insumo'=>4], // monitor cardíaco
            ['id_servicio'=>$srv3,'id_insumo'=>2], // oxígeno
            ['id_servicio'=>$srv3,'id_insumo'=>5], // camilla
            ['id_servicio'=>$srv4,'id_insumo'=>1], // desfibrilador
            ['id_servicio'=>$srv5,'id_insumo'=>3], // kit trauma
        ]);

        // ────────────────────────────────────────────────────────────────────
        // PADECIMIENTOS DE PACIENTES
        // ────────────────────────────────────────────────────────────────────
        DB::table('paciente_padecimiento')->insert([
            ['id_paciente'=>1,'id_padecimiento'=>3], // Ana – Fractura
            ['id_paciente'=>3,'id_padecimiento'=>5], // Luis – Insuf. resp.
            ['id_paciente'=>3,'id_padecimiento'=>1], // Luis – Diabetes
            ['id_paciente'=>4,'id_padecimiento'=>2], // Rosa – HTA
            ['id_paciente'=>5,'id_padecimiento'=>4], // Pedro – Infarto
        ]);

        $this->command->info('');
        $this->command->info('✅  Datos de prueba generados correctamente.');
        $this->command->info('');
        $this->command->info('  ADMIN       admin@dpl.com');
        $this->command->info('  OPERADORES  carlos@dpl.com  roberto@dpl.com  miguel@dpl.com  fernando@dpl.com  eduardo@dpl.com');
        $this->command->info('  PARAMÉDICOS ana@dpl.com  laura@dpl.com  sofia@dpl.com  patricia@dpl.com  maria@dpl.com');
        $this->command->info('  CLIENTES    jose@cliente.com  carmen@cliente.com  david@cliente.com  valentina@cliente.com  alejandro@cliente.com');
        $this->command->info('  CONTRASEÑA  12345678  (todos)');
        $this->command->info('');
    }
}
