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
        Schema::create('padecimiento', function (Blueprint $table) {
            $table->id('id_padecimiento');
            $table->string('nombre_padecimiento',150);
            $table->string('nivel_riesgo',50)->nullable();
            $table->decimal('costo_extra',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padecimiento');
    }
};
