<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            // Add tur_estado column if it doesn't exist
            if (!Schema::hasColumn('turno', 'tur_estado')) {
                $table->enum('tur_estado', ['Espera', 'Atendiendo', 'Finalizado', 'Ausente'])
                      ->default('Espera')
                      ->after('tur_id');
            }

            // Add indexes for optimization
            $table->index('tur_perfil');
            $table->index('tur_estado');
            $table->index('tur_hora_fecha');
        });

        // Initialize tur_estado based on atencion table
        DB::table('turno')
            ->whereIn('tur_id', function ($query) {
                $query->select('TURNO_tur_id')
                      ->from('atencion')
                      ->whereNull('atnc_hora_fin');
            })
            ->update(['tur_estado' => 'Atendiendo']);

        DB::table('turno')
            ->whereIn('tur_id', function ($query) {
                $query->select('TURNO_tur_id')
                      ->from('atencion')
                      ->whereNotNull('atnc_hora_fin');
            })
            ->update(['tur_estado' => 'Finalizado']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turno', function (Blueprint $table) {
            $table->dropIndex(['tur_perfil']);
            $table->dropIndex(['tur_estado']);
            $table->dropIndex(['tur_hora_fecha']);
            $table->dropColumn('tur_estado');
        });
    }
};
