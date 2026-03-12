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
        Schema::create('paciente', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->string('nombre',100);
            $table->string('ap_paterno',100);
            $table->string('ap_materno',100)->nullable();
            $table->string('oxigeno',10)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo',20)->nullable();
            $table->decimal('peso',8,2)->nullable();
            $table->foreignId('id_servicio')
                ->constrained('servicio','id_servicio');
            $table->foreignId('id_direccion')
                ->nullable()
                ->constrained('direccion','id_direccion')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente');
    }
};
