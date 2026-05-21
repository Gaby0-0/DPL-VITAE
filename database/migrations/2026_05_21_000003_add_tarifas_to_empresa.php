<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->decimal('tarifa_operador',  10, 2)->default(100);
            $table->decimal('tarifa_paramedico', 10, 2)->default(80);
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn(['tarifa_operador', 'tarifa_paramedico']);
        });
    }
};
