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
        Schema::create('servicio_paramedico', function (Blueprint $table) {
            $table->id('id_servicio_paramedico');
            $table->foreignId('id_servicio')
                ->constrained('servicio','id_servicio');
            $table->foreignId('id_paramedico')
                ->constrained('paramedico','id_usuario');
            $table->unique(['id_servicio','id_paramedico']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_paramedico');
    }
};
