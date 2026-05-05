<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * CU-02: Estandariza ase_tipo_asesor a valores definidos en el spec:
 *   OT = Orientador Técnico       → atiende General + Prioritario
 *   OV = Orientador de Víctimas   → atiende Victima + Empresario
 *
 * Migra valores legacy ('General', 'Especializado', null) al nuevo estándar.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Normalizar valores existentes antes de cambiar el tipo de columna
        DB::statement("UPDATE asesor SET ase_tipo_asesor = 'OV' WHERE ase_tipo_asesor IN ('Especializado', 'Victimas', 'OV')");
        DB::statement("UPDATE asesor SET ase_tipo_asesor = 'OT' WHERE ase_tipo_asesor IS NULL OR ase_tipo_asesor NOT IN ('OT', 'OV')");

        // 2. Cambiar columna a ENUM con los dos valores válidos
        DB::statement("ALTER TABLE asesor MODIFY ase_tipo_asesor ENUM('OT','OV') NOT NULL DEFAULT 'OT'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE asesor MODIFY ase_tipo_asesor VARCHAR(45) DEFAULT NULL");
    }
};
