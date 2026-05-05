<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliar el ENUM para incluir AT (Asesor Total — atiende los 4 perfiles)
        DB::statement("ALTER TABLE asesor MODIFY ase_tipo_asesor ENUM('OT','OV','AT') NOT NULL DEFAULT 'OT'");
    }

    public function down(): void
    {
        // Revertir a solo OT y OV (los AT quedarán como OT por defecto)
        DB::statement("UPDATE asesor SET ase_tipo_asesor = 'OT' WHERE ase_tipo_asesor = 'AT'");
        DB::statement("ALTER TABLE asesor MODIFY ase_tipo_asesor ENUM('OT','OV') NOT NULL DEFAULT 'OT'");
    }
};
