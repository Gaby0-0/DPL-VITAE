<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add id_operador to servicio
        Schema::table('servicio', function (Blueprint $table) {
            $table->unsignedBigInteger('id_operador')->nullable()->after('id_ambulancia');
            $table->foreign('id_operador')
                ->references('id_usuario')
                ->on('operador')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        // Remove id_operador from ambulancia
        Schema::table('ambulancia', function (Blueprint $table) {
            $table->dropForeign(['id_operador']);
            $table->dropColumn('id_operador');
        });
    }

    public function down(): void
    {
        // Restore id_operador to ambulancia
        Schema::table('ambulancia', function (Blueprint $table) {
            $table->unsignedBigInteger('id_operador')->nullable()->after('id_tipo_ambulancia');
            $table->foreign('id_operador')
                ->references('id_usuario')
                ->on('operador')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        // Remove id_operador from servicio
        Schema::table('servicio', function (Blueprint $table) {
            $table->dropForeign(['id_operador']);
            $table->dropColumn('id_operador');
        });
    }
};
