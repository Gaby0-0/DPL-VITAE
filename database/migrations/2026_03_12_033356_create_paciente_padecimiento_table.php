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
        Schema::create('paciente_padecimiento', function (Blueprint $table) {
            $table->id('id_paciente_padecimiento');
            $table->foreignId('id_paciente')
                ->constrained('paciente','id_paciente');
            $table->foreignId('id_padecimiento')
                ->constrained('padecimiento','id_padecimiento');
            $table->unique(['id_paciente','id_padecimiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente_padecimiento');
    }
};
