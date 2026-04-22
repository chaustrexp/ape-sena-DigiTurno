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
        // 1. Trigger para cálculo automático de duración
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_calc_duracion_receso;
            CREATE TRIGGER trg_calc_duracion_receso
            BEFORE UPDATE ON pausas_asesor
            FOR EACH ROW
            BEGIN
                IF NEW.hora_fin IS NOT NULL AND OLD.hora_fin IS NULL THEN
                    SET NEW.duracion = TIMESTAMPDIFF(MINUTE, NEW.hora_inicio, NEW.hora_fin);
                END IF;
            END
        ");

        // 2. Trigger para evitar doble pausa activa
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_evitar_doble_receso;
            CREATE TRIGGER trg_evitar_doble_receso
            BEFORE INSERT ON pausas_asesor
            FOR EACH ROW
            BEGIN
                DECLARE pausas_abiertas INT;
                SELECT COUNT(*) INTO pausas_abiertas 
                FROM pausas_asesor 
                WHERE ASESOR_ase_id = NEW.ASESOR_ase_id AND hora_fin IS NULL;
                
                IF pausas_abiertas > 0 THEN
                    SIGNAL SQLSTATE '45000' 
                    SET MESSAGE_TEXT = 'ERROR: El asesor ya tiene un receso activo en curso.';
                END IF;
            END
        ");

        // 3. Vistas de Monitoreo
        DB::unprepared("
            CREATE OR REPLACE VIEW view_resumen_pausas_hoy AS
            SELECT 
                a.ase_id as modulo,
                CONCAT(p.pers_nombres, ' ', p.pers_apellidos) as asesor,
                COUNT(pa.id) as total_pausas,
                SUM(COALESCE(pa.duracion, 0)) as minutos_totales,
                MAX(pa.hora_inicio) as ultimo_receso
            FROM asesor a
            JOIN persona p ON a.PERSONA_pers_doc = p.pers_doc
            LEFT JOIN pausas_asesor pa ON a.ase_id = pa.ASESOR_ase_id 
                AND DATE(pa.hora_inicio) = CURDATE()
            GROUP BY a.ase_id, p.pers_nombres, p.pers_apellidos;
        ");

        DB::unprepared("
            CREATE OR REPLACE VIEW view_estado_actual_asesores AS
            SELECT 
                a.ase_id as modulo,
                CONCAT(p.pers_nombres, ' ', p.pers_apellidos) as asesor,
                CASE 
                    WHEN EXISTS (SELECT 1 FROM pausas_asesor WHERE ASESOR_ase_id = a.ase_id AND hora_fin IS NULL) THEN 'EN RECESO'
                    WHEN EXISTS (SELECT 1 FROM atencion WHERE ASESOR_ase_id = a.ase_id AND atnc_hora_fin IS NULL) THEN 'ATENDIENDO'
                    ELSE 'DISPONIBLE'
                END as estado,
                (SELECT TIMESTAMPDIFF(MINUTE, hora_inicio, NOW()) FROM pausas_asesor WHERE ASESOR_ase_id = a.ase_id AND hora_fin IS NULL LIMIT 1) as minutos_en_receso_actual
            FROM asesor a
            JOIN persona p ON a.PERSONA_pers_doc = p.pers_doc;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_calc_duracion_receso;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_evitar_doble_receso;");
        DB::unprepared("DROP VIEW IF EXISTS view_resumen_pausas_hoy;");
        DB::unprepared("DROP VIEW IF EXISTS view_estado_actual_asesores;");
    }
};
