<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cotizacion')->nullable()->after('tipo');
            $table->string('forma_pago', 20)->nullable()->after('id_cotizacion'); // 'efectivo' | 'online'
            $table->foreign('id_cotizacion')
                  ->references('id_cotizacion')
                  ->on('cotizaciones')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            $table->dropForeign(['id_cotizacion']);
            $table->dropColumn(['id_cotizacion', 'forma_pago']);
        });
    }
};
