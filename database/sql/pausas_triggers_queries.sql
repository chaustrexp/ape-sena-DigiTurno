-- ========================================================
-- SISTEMA DIGITURNO SENA APE - TRIGGERS Y CONSULTAS
-- Componente: Pausas de Asesores
-- ========================================================

-- 1. TRIGGER PARA CÁLCULO AUTOMÁTICO DE DURACIÓN
-- Este trigger se dispara justo antes de actualizar una pausa.
-- Si se está asignando una hora_fin, calcula los minutos transcurridos.
DROP TRIGGER IF EXISTS trg_calc_duracion_receso;
DELIMITER //
CREATE TRIGGER trg_calc_duracion_receso
BEFORE UPDATE ON pausas_asesor
FOR EACH ROW
BEGIN
    IF NEW.hora_fin IS NOT NULL AND OLD.hora_fin IS NULL THEN
        SET NEW.duracion = TIMESTAMPDIFF(MINUTE, NEW.hora_inicio, NEW.hora_fin);
    END IF;
END //
DELIMITER ;

-- 2. TRIGGER PARA EVITAR DOBLE PAUSA ACTIVA
-- Este trigger impide que un asesor inicie un receso si ya tiene uno abierto.
DROP TRIGGER IF EXISTS trg_evitar_doble_receso;
DELIMITER //
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
END //
DELIMITER ;

-- 3. VISTAS DE MONITOREO EN TIEMPO REAL
-- ========================================================

-- A. RESUMEN DE PAUSAS (HOY)
-- Proporciona un consolidado de minutos y cantidad de pausas por asesor.
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

-- B. ESTADO ACTUAL GLOBAL DE ASESORES
-- Permite saber quién está atendiendo, en pausa o libre en este momento.
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

-- 4. CONSULTAS DE REPORTES PARA EL COORDINADOR
-- ========================================================

-- A. RANKING DE ASESORES CON MÁS TIEMPO DE PAUSA (MES ACTUAL)
-- SELECT 
--     asesor, 
--     SUM(duracion) as minutos_mes 
-- FROM pausas_asesor pa
-- JOIN asesor a ON pa.ASESOR_ase_id = a.ase_id
-- JOIN persona p ON a.PERSONA_pers_doc = p.pers_doc
-- WHERE MONTH(pa.hora_inicio) = MONTH(CURDATE())
-- GROUP BY a.ase_id
-- ORDER BY minutos_mes DESC;

-- B. DETECTAR PAUSAS PROLONGADAS (> 20 MINUTOS) HOY
-- SELECT 
--     p.pers_nombres, 
--     pa.hora_inicio, 
--     pa.duracion 
-- FROM pausas_asesor pa
-- JOIN asesor a ON pa.ASESOR_ase_id = a.ase_id
-- JOIN persona p ON a.PERSONA_pers_doc = p.pers_doc
-- WHERE pa.duracion > 20
-- AND DATE(pa.hora_inicio) = CURDATE();

-- C. TIEMPO PROMEDIO DE PAUSA POR ASESOR
-- SELECT 
--     CONCAT(p.pers_nombres, ' ', p.pers_apellidos) as asesor,
--     ROUND(AVG(duracion), 1) as promedio_minutos_por_pausa
-- FROM pausas_asesor pa
-- JOIN asesor a ON pa.ASESOR_ase_id = a.ase_id
-- JOIN persona p ON a.PERSONA_pers_doc = p.pers_doc
-- GROUP BY a.ase_id;
