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
        Schema::create('colonia', function (Blueprint $table) {

            $table->id('id_colonia');
            $table->string('nombre_colonia',100);
            $table->string('codigo_postal',10)->nullable();
            $table->foreignId('id_municipio')
                ->constrained('municipio','id_municipio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colonia');
    }
};
