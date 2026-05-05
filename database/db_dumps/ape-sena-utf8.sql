-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: apesena
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asesor`
--

DROP TABLE IF EXISTS `asesor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asesor` (
  `ase_id` int(11) NOT NULL AUTO_INCREMENT,
  `ase_nrocontrato` varchar(45) DEFAULT NULL,
  `ase_tipo_asesor` enum('OT','OV') NOT NULL DEFAULT 'OT',
  `ase_vigencia` varchar(45) DEFAULT NULL,
  `ase_password` varchar(255) DEFAULT NULL,
  `ase_correo` varchar(100) DEFAULT NULL,
  `PERSONA_pers_doc` bigint(20) unsigned DEFAULT NULL,
  `ase_foto` varchar(255) DEFAULT 'images/foto de perfil.jpg',
  PRIMARY KEY (`ase_id`),
  KEY `PERSONA_pers_doc` (`PERSONA_pers_doc`),
  CONSTRAINT `asesor_ibfk_1` FOREIGN KEY (`PERSONA_pers_doc`) REFERENCES `persona` (`pers_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asesor`
--

LOCK TABLES `asesor` WRITE;
/*!40000 ALTER TABLE `asesor` DISABLE KEYS */;
INSERT INTO `asesor` VALUES (2,NULL,'OT',NULL,'$2y$12$x6VCLN3TF.J/re3b0i/8ReI1YLrqD7ksTxWuQCeQyPeyqP/3tF8x2','asesor@sena.edu.co',12345678,'images/foto de perfil.jpg'),(3,NULL,'OV',NULL,'asesor123','asesor1@sena.edu.co',11111111,'images/foto de perfil.jpg'),(4,NULL,'OT',NULL,'asesor234','asesor2@sena.edu.co',22222222,'images/foto de perfil.jpg');
/*!40000 ALTER TABLE `asesor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `atencion`
--

DROP TABLE IF EXISTS `atencion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `atencion` (
  `atnc_id` int(11) NOT NULL AUTO_INCREMENT,
  `atnc_hora_inicio` datetime DEFAULT NULL,
  `atnc_hora_fin` datetime DEFAULT NULL,
  `atnc_tipo` enum('General','Prioritaria','Victimas') NOT NULL,
  `ASESOR_ase_id` int(11) DEFAULT NULL,
  `TURNO_tur_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`atnc_id`),
  KEY `ASESOR_ase_id` (`ASESOR_ase_id`),
  KEY `TURNO_tur_id` (`TURNO_tur_id`),
  CONSTRAINT `atencion_ibfk_1` FOREIGN KEY (`ASESOR_ase_id`) REFERENCES `asesor` (`ase_id`),
  CONSTRAINT `atencion_ibfk_2` FOREIGN KEY (`TURNO_tur_id`) REFERENCES `turno` (`tur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atencion`
--

LOCK TABLES `atencion` WRITE;
/*!40000 ALTER TABLE `atencion` DISABLE KEYS */;
/*!40000 ALTER TABLE `atencion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-53f11be06b43e2ad45a869078b38dfd8','i:1;',1776701529),('laravel-cache-53f11be06b43e2ad45a869078b38dfd8:timer','i:1776701529;',1776701529),('laravel-cache-ef855c70c9e517abb1c7e71b78a2eded','i:1;',1777559953),('laravel-cache-ef855c70c9e517abb1c7e71b78a2eded:timer','i:1777559953;',1777559953),('laravel-cache-prioritario_counter','i:0;',1777646746);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_sistema`
--

DROP TABLE IF EXISTS `configuracion_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_sistema` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL COMMENT 'Nombre del parámetro',
  `valor` varchar(255) NOT NULL COMMENT 'Valor del parámetro',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripción del parámetro',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracion_sistema_clave_unique` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_sistema`
--

LOCK TABLES `configuracion_sistema` WRITE;
/*!40000 ALTER TABLE `configuracion_sistema` DISABLE KEYS */;
INSERT INTO `configuracion_sistema` VALUES (1,'ciclo_turno','dia','Ciclo de reinicio de numeración de turnos: dia | semana | mes','2026-04-30 18:10:01','2026-04-30 18:10:01');
/*!40000 ALTER TABLE `configuracion_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordinador`
--

DROP TABLE IF EXISTS `coordinador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coordinador` (
  `coor_id` int(11) NOT NULL AUTO_INCREMENT,
  `coor_vigencia` varchar(45) DEFAULT NULL,
  `coor_correo` varchar(100) DEFAULT NULL,
  `coor_password` varchar(100) DEFAULT NULL,
  `PERSONA_pers_doc` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`coor_id`),
  KEY `PERSONA_pers_doc` (`PERSONA_pers_doc`),
  CONSTRAINT `coordinador_ibfk_1` FOREIGN KEY (`PERSONA_pers_doc`) REFERENCES `persona` (`pers_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordinador`
--

LOCK TABLES `coordinador` WRITE;
/*!40000 ALTER TABLE `coordinador` DISABLE KEYS */;
INSERT INTO `coordinador` VALUES (4,'2027-12-31','coordinador@sena.edu.co','$2y$12$yJ9yA7vUVKcpnV/4hZX1b.r2SaQn43bTyv6o2iYxKpFRCBonVUhZy',9000000001);
/*!40000 ALTER TABLE `coordinador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_03_21_032735_change_pers_doc_to_bigint_on_multiple_tables',2),(5,'2026_04_15_000001_add_fields_to_turno_table',3),(6,'2026_04_15_000002_create_pausas_asesor_table',4),(7,'2026_04_20_000001_add_hora_llamado_to_turno_table',5),(8,'2026_04_20_000002_standardize_ase_tipo_asesor',6),(9,'2026_04_22_024747_add_triggers_to_pausas_asesor',7),(10,'2026_04_25_225000_optimize_turno_table',8),(11,'2026_04_29_000001_add_estado_to_turno_table',9),(12,'2026_04_29_000002_add_credentials_to_coordinador_table',9),(13,'0000_00_00_000000_create_base_schema',10),(14,'2026_04_29_000003_create_configuracion_sistema_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pausas_asesor`
--

DROP TABLE IF EXISTS `pausas_asesor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pausas_asesor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ASESOR_ase_id` int(10) unsigned NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime DEFAULT NULL,
  `duracion` int(10) unsigned DEFAULT NULL COMMENT 'Duración en minutos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pausas_asesor`
--

LOCK TABLES `pausas_asesor` WRITE;
/*!40000 ALTER TABLE `pausas_asesor` DISABLE KEYS */;
/*!40000 ALTER TABLE `pausas_asesor` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_evitar_doble_receso` BEFORE
INSERT ON `pausas_asesor` FOR EACH ROW BEGIN
DECLARE pausas_abiertas INT;
SELECT COUNT(*) INTO pausas_abiertas
FROM pausas_asesor
WHERE ASESOR_ase_id = NEW.ASESOR_ase_id
  AND hora_fin IS NULL;
IF pausas_abiertas > 0 THEN SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'ERROR: El asesor ya tiene un receso activo en curso.';
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_calc_duracion_receso` BEFORE
UPDATE ON `pausas_asesor` FOR EACH ROW BEGIN IF NEW.hora_fin IS NOT NULL
  AND OLD.hora_fin IS NULL THEN
SET NEW.duracion = TIMESTAMPDIFF(MINUTE, NEW.hora_inicio, NEW.hora_fin);
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona` (
  `pers_doc` bigint(20) unsigned NOT NULL,
  `pers_tipodoc` varchar(45) DEFAULT NULL,
  `pers_nombres` varchar(100) DEFAULT NULL,
  `pers_apellidos` varchar(100) DEFAULT NULL,
  `pers_telefono` bigint(10) DEFAULT NULL,
  `pers_fecha_nac` datetime DEFAULT NULL,
  PRIMARY KEY (`pers_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (0,'CC','Usuario','Kiosco',8888888888,NULL),(123,'CC','Test','User',NULL,NULL),(186342,'CE','Usuario','Kiosco',3228574190,NULL),(205741,'CC','Usuario','Kiosco',3205417580,NULL),(208547,'CE','Usuario','Kiosco',3521470956,NULL),(208657,'CC','Usuario','Kiosco',3903507210,NULL),(250859,'CC','Usuario','Kiosco',3603904155,NULL),(555555,'TI','Usuario','Kiosco',355284800,NULL),(624538,'CC','Usuario','Kiosco',3203604472,NULL),(627458,'CC','Usuario','Kiosco',3213244157,NULL),(2507540,'CC','Usuario','Kiosco',3603207510,NULL),(7777777,'CC','pepito','Kiosco',0,NULL),(8051082,'CC','Usuario','Kiosco',3103207240,NULL),(8888888,'CC','Usuario','Kiosco',5555555,NULL),(10001000,'CC','Carlos Coord','Administrador',3001234567,NULL),(10203040,'CC','Ciudadano','Prueba',NULL,NULL),(11111111,'CC','Asesor 1','Especializado',3001111111,'1990-01-01 00:00:00'),(12345678,'CC','Asesor','Pruebas',3000000000,'1990-01-01 00:00:00'),(20002000,'CC','Ana Asesor','Servicio',3109876543,NULL),(20567120,'CC','Usuario','Kiosco',361320457,NULL),(22222222,'CC','Usuario','Kiosco',3229615724,'1990-01-01 00:00:00'),(28888888,'CC','Usuario','Kiosco',3200082408,NULL),(55555555,'CC','Usuario','Kiosco',332415720,NULL),(60321456,'CC','Usuario','Kiosco',3229615723,NULL),(60356258,'CE','Usuario','Kiosco',3603207512,NULL),(87654321,'CC','Andres','General',NULL,'1990-01-01 00:00:00'),(111111111,'CC','Usuario','Kiosco',888888,NULL),(120365584,'CC','Usuario','Kiosco',3222222222,NULL),(123456789,'CC','Test','User',3000000000,NULL),(147895320,'CC','Usuario','Kiosco',380350724,NULL),(300000000,'NIT','Usuario','Kiosco',3208443,NULL),(444444444,'CC','Usuario','Kiosco',320587410,NULL),(666666666,'CC','Usuario','Kiosco',3204874699,NULL),(777777777,'CE','Usuario','Kiosco',360265417,NULL),(1000000001,'CC','Coordinador','Principal SENA',3000000000,NULL),(1005026715,'CC','Usuario','Kiosco',3258479999,NULL),(1062576432,'CC','Usuario','Kiosco',322715489,NULL),(1092529985,'CC','Usuario','Kiosco',3229615724,NULL),(2222222222,'CC','Usuario','Kiosco',3603102478,NULL),(9000000001,'CC','Coordinador','SENA APE',3000000001,NULL),(10125725412,'CC','Usuario','Kiosco',350321470,NULL),(11111111111,'CC','Usuario','Kiosco',88888877,NULL),(22222222222,'CC','Usuario','Kiosco',3502586384,NULL),(77777777777,'TI','Usuario','Kiosco',8888888888,NULL),(99999999999,'CC','Usuario','Kiosco',7777777777,NULL),(111111111111,'CC','Usuario','Kiosco',0,NULL),(145225880000,'CC','Usuario','Kiosco',1745800000,NULL),(222222222222,'CC','Usuario','Kiosco',3333333333,NULL),(555555555555,'CC','Usuario','Kiosco',3503207251,NULL);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitante`
--

DROP TABLE IF EXISTS `solicitante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitante` (
  `sol_id` int(11) NOT NULL AUTO_INCREMENT,
  `sol_tipo` varchar(45) DEFAULT NULL,
  `PERSONA_pers_doc` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`sol_id`),
  KEY `PERSONA_pers_doc` (`PERSONA_pers_doc`),
  CONSTRAINT `solicitante_ibfk_1` FOREIGN KEY (`PERSONA_pers_doc`) REFERENCES `persona` (`pers_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitante`
--

LOCK TABLES `solicitante` WRITE;
/*!40000 ALTER TABLE `solicitante` DISABLE KEYS */;
INSERT INTO `solicitante` VALUES (1,'Externo',1092529985),(2,'Externo',2222222222),(3,'Externo',55555555),(4,'Externo',8888888),(5,'Externo',666666666),(6,'General',123),(7,'Externo',7777777),(8,'Externo',1062576432),(9,'Externo',123456789),(10,'Externo',555555),(11,NULL,300000000),(12,'Externo',120365584),(13,'Externo',111111111111),(14,'Externo',0),(15,'Externo',444444444),(16,'Externo',99999999999),(17,'Externo',111111111),(18,'Externo',10125725412),(19,'Externo',60321456),(20,'Externo',20567120),(21,'Externo',11111111111),(22,'Externo',777777777),(23,'Externo',77777777777),(24,'Externo',147895320),(25,'Prioritario',186342),(26,'Empresario',205741),(27,'Prioritario',2507540),(28,'General',208657),(29,'Prioritario',8051082),(30,'Victima',208547),(31,'Prioritario',28888888),(32,'General',222222222222),(33,'Victima',22222222222),(34,'Prioritario',22222222),(35,'Prioritario',555555555555),(36,NULL,10203040),(37,'Victima',60356258),(38,'Prioritario',627458),(39,'General',624538),(40,'Victima',250859),(41,'Prioritario',1005026715),(42,'General',145225880000);
/*!40000 ALTER TABLE `solicitante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turno` (
  `tur_id` int(11) NOT NULL AUTO_INCREMENT,
  `tur_estado` enum('Espera','Atendiendo','Finalizado','Ausente') NOT NULL DEFAULT 'Espera',
  `tur_hora_fecha` datetime DEFAULT NULL,
  `tur_hora_llamado` datetime DEFAULT NULL COMMENT 'Timestamp cuando el asesor llama al turno (CU-02)',
  `tur_numero` varchar(45) DEFAULT NULL,
  `tur_tipo` enum('General','Prioritario','Victimas') NOT NULL,
  `tur_perfil` enum('General','Víctima','Prioritario','Empresario') NOT NULL DEFAULT 'General',
  `tur_tipo_atencion` enum('Normal','Especial') NOT NULL DEFAULT 'Normal',
  `tur_servicio` enum('Orientación','Formación','Emprendimiento') NOT NULL DEFAULT 'Orientación',
  `tur_telefono` varchar(20) DEFAULT NULL,
  `SOLICITANTE_sol_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tur_id`),
  KEY `SOLICITANTE_sol_id` (`SOLICITANTE_sol_id`),
  KEY `turno_tur_perfil_index` (`tur_perfil`),
  KEY `turno_tur_estado_index` (`tur_estado`),
  KEY `turno_tur_hora_fecha_index` (`tur_hora_fecha`),
  CONSTRAINT `turno_ibfk_1` FOREIGN KEY (`SOLICITANTE_sol_id`) REFERENCES `solicitante` (`sol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_estado_actual_asesores`
--

DROP TABLE IF EXISTS `view_estado_actual_asesores`;
/*!50001 DROP VIEW IF EXISTS `view_estado_actual_asesores`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_estado_actual_asesores` AS SELECT
 1 AS `modulo`,
  1 AS `asesor`,
  1 AS `estado`,
  1 AS `minutos_en_receso_actual` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_resumen_pausas_hoy`
--

DROP TABLE IF EXISTS `view_resumen_pausas_hoy`;
/*!50001 DROP VIEW IF EXISTS `view_resumen_pausas_hoy`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_resumen_pausas_hoy` AS SELECT
 1 AS `modulo`,
  1 AS `asesor`,
  1 AS `total_pausas`,
  1 AS `minutos_totales`,
  1 AS `ultimo_receso` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_estado_actual_asesores`
--

/*!50001 DROP VIEW IF EXISTS `view_estado_actual_asesores`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_estado_actual_asesores` AS select `a`.`ase_id` AS `modulo`,concat(`p`.`pers_nombres`,' ',`p`.`pers_apellidos`) AS `asesor`,case when exists(select 1 from `pausas_asesor` where `pausas_asesor`.`ASESOR_ase_id` = `a`.`ase_id` and `pausas_asesor`.`hora_fin` is null limit 1) then 'EN RECESO' when exists(select 1 from `atencion` where `atencion`.`ASESOR_ase_id` = `a`.`ase_id` and `atencion`.`atnc_hora_fin` is null limit 1) then 'ATENDIENDO' else 'DISPONIBLE' end AS `estado`,(select timestampdiff(MINUTE,`pausas_asesor`.`hora_inicio`,current_timestamp()) from `pausas_asesor` where `pausas_asesor`.`ASESOR_ase_id` = `a`.`ase_id` and `pausas_asesor`.`hora_fin` is null limit 1) AS `minutos_en_receso_actual` from (`asesor` `a` join `persona` `p` on(`a`.`PERSONA_pers_doc` = `p`.`pers_doc`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_resumen_pausas_hoy`
--

/*!50001 DROP VIEW IF EXISTS `view_resumen_pausas_hoy`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_resumen_pausas_hoy` AS select `a`.`ase_id` AS `modulo`,concat(`p`.`pers_nombres`,' ',`p`.`pers_apellidos`) AS `asesor`,count(`pa`.`id`) AS `total_pausas`,sum(coalesce(`pa`.`duracion`,0)) AS `minutos_totales`,max(`pa`.`hora_inicio`) AS `ultimo_receso` from ((`asesor` `a` join `persona` `p` on(`a`.`PERSONA_pers_doc` = `p`.`pers_doc`)) left join `pausas_asesor` `pa` on(`a`.`ase_id` = `pa`.`ASESOR_ase_id` and cast(`pa`.`hora_inicio` as date) = curdate())) group by `a`.`ase_id`,`p`.`pers_nombres`,`p`.`pers_apellidos` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-30  9:47:09
