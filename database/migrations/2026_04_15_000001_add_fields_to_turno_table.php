<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            if (!Schema::hasColumn('turno', 'tur_perfil')) {
                $table->enum('tur_perfil', ['General', 'Victima', 'Prioritario', 'Empresario'])
                      ->default('General')
                      ->after('tur_tipo');
            }

            if (!Schema::hasColumn('turno', 'tur_tipo_atencion')) {
                $table->enum('tur_tipo_atencion', ['Normal', 'Especial'])
                      ->default('Normal')
                      ->after('tur_perfil');
            }

            if (!Schema::hasColumn('turno', 'tur_servicio')) {
                $table->enum('tur_servicio', ['Orientacion', 'Formacion', 'Emprendimiento'])
                      ->default('Orientacion')
                      ->after('tur_tipo_atencion');
            }

            if (!Schema::hasColumn('turno', 'tur_telefono')) {
                $table->string('tur_telefono', 20)
                      ->nullable()
                      ->after('tur_servicio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            $cols = ['tur_perfil', 'tur_tipo_atencion', 'tur_servicio', 'tur_telefono'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('turno', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
