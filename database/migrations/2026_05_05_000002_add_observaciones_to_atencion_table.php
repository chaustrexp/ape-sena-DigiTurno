<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CU-02: Agrega el campo observaciones a la tabla atencion.
 * Permite al asesor registrar la conclusión del trámite al finalizar la atención.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atencion', function (Blueprint $table) {
            if (!Schema::hasColumn('atencion', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('atnc_hora_fin')
                      ->comment('Conclusión o notas del trámite registradas por el asesor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('atencion', function (Blueprint $table) {
            if (Schema::hasColumn('atencion', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
        });
    }
};
