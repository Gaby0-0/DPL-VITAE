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
        Schema::create('salario_minimo', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_empleado');
            $table->decimal('monto',8, 2);
            $table->date('vigente desde');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salario_minimo');
    }
};
