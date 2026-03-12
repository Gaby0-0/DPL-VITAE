<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->decimal('costo_total',10,2);
            $table->string('estado',50);
            $table->dateTime('fecha_hora');
            $table->dateTime('hora_salida')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('tipo',100)->nullable();
            $table->foreignId('id_ambulancia')
                ->constrained('ambulancia','id_ambulancia');
            $table->foreignId('id_cliente')
                ->constrained('cliente','id_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio');
    }
};
