<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Auditoría CU-01/CU-02: Agrega tur_hora_llamado a la tabla turno.
 * Permite calcular el tiempo de espera real (tur_hora_llamado - tur_hora_fecha)
 * separado del tiempo de atención (atnc_hora_fin - atnc_hora_inicio).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            if (!Schema::hasColumn('turno', 'tur_hora_llamado')) {
                $table->dateTime('tur_hora_llamado')
                      ->nullable()
                      ->after('tur_hora_fecha')
                      ->comment('Timestamp cuando el asesor llama al turno (CU-02)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            if (Schema::hasColumn('turno', 'tur_hora_llamado')) {
                $table->dropColumn('tur_hora_llamado');
            }
        });
    }
};
