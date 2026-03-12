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
        Schema::create('servicio_insumo', function (Blueprint $table) {
            $table->id('id_servicio_insumo');
            $table->foreignId('id_servicio')
                ->constrained('servicio','id_servicio');
            $table->foreignId('id_insumo')
                ->constrained('insumo','id_insumo');
            $table->unique(['id_servicio','id_insumo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_insumo');
    }
};
