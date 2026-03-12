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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('nombre',150);
            $table->string('slogan')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('valores')->nullable();
            $table->text('descripcion')->nullable();
            $table->binary('logo')->nullable();
            $table->string('logo_nombre',150)->nullable();
            $table->string('logo_tipo',50)->nullable();
            $table->binary('imagen')->nullable();
            $table->string('imagen_nombre',150)->nullable();
            $table->string('imagen_tipo',50)->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('correo',150)->nullable();
            $table->string('sitio_web',200)->nullable();
            $table->string('direccion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
