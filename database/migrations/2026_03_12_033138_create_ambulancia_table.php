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
        Schema::create('ambulancia', function (Blueprint $table) {
            $table->id('id_ambulancia');
            $table->string('placa',20)->unique();
            $table->string('estado',50);
            $table->foreignId('id_tipo_ambulancia')
                ->constrained('tipo_ambulancia','id_tipo_ambulancia')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('id_operador')
                ->constrained('operador','id_usuario')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulancia');
    }
};
