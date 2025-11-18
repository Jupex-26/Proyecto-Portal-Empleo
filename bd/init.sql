-- MySQL dump 10.13  Distrib 8.0.42, for macos15 (x86_64)
--
-- Host: localhost    Database: empleo
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alum_cursado_ciclo`
--

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


DROP SCHEMA IF EXISTS `empleo` ;

-- -----------------------------------------------------
-- Schema empleo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `empleo` ;
USE `empleo` ;



DROP TABLE IF EXISTS `alum_cursado_ciclo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alum_cursado_ciclo` (
  `alumno_id` int NOT NULL,
  `ciclo_id` int NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_alum-cursado-ciclo_alumno1_idx` (`alumno_id`),
  KEY `fk_alum-cursado-ciclo_ciclo1_idx` (`ciclo_id`),
  CONSTRAINT `fk_alum-cursado-ciclo_alumno1` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`),
  CONSTRAINT `fk_alum-cursado-ciclo_ciclo1` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alum_cursado_ciclo`
--

LOCK TABLES `alum_cursado_ciclo` WRITE;
/*!40000 ALTER TABLE `alum_cursado_ciclo` DISABLE KEYS */;
INSERT INTO `alum_cursado_ciclo` VALUES (3,1,'2023-09-01 00:00:00','2025-06-30 00:00:00',1),(5,2,'2022-09-01 00:00:00',NULL,2),(9,3,'2025-11-14 14:03:08',NULL,3),(10,3,'2025-11-14 14:03:08',NULL,4),(11,3,'2025-11-14 14:03:08',NULL,5),(12,3,'2025-11-14 14:03:08',NULL,6),(13,3,'2025-11-14 14:03:08',NULL,7),(14,3,'2025-11-14 14:03:08',NULL,8),(15,3,'2025-11-14 14:03:08',NULL,9),(16,3,'2025-11-14 14:03:08',NULL,10),(17,3,'2025-11-14 14:03:08',NULL,11),(18,3,'2025-11-14 14:03:08',NULL,12),(19,3,'2025-11-14 14:03:08',NULL,13),(20,3,'2025-11-14 14:03:08',NULL,14),(21,3,'2025-11-14 14:03:08',NULL,15),(22,3,'2025-11-14 14:03:08',NULL,16),(24,3,'2025-11-14 14:03:08',NULL,18),(25,3,'2025-11-14 14:03:08',NULL,19),(26,3,'2025-11-14 14:03:08',NULL,20),(27,3,'2025-11-14 14:03:08',NULL,21),(28,3,'2025-11-14 14:03:42',NULL,22),(29,3,'2025-11-14 14:03:42',NULL,23),(30,3,'2025-11-14 14:03:42',NULL,24),(34,113,'2023-09-01 00:00:00','2025-06-30 00:00:00',26),(36,11,'2025-11-17 08:55:06',NULL,27),(40,104,'2025-11-17 21:28:11',NULL,28);
/*!40000 ALTER TABLE `alum_cursado_ciclo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumno`
--

DROP TABLE IF EXISTS `alumno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumno` (
  `id` int NOT NULL,
  `ap1` varchar(45) NOT NULL,
  `ap2` varchar(45) DEFAULT NULL,
  `cv` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_copy1_user1_idx` (`id`),
  CONSTRAINT `fk_user_copy1_user1` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
INSERT INTO `alumno` VALUES (3,'Pérez','García','cv_maria.pdf','2000-05-12 00:00:00',''),(5,'López','Martínez','cv_juan.pdf','1998-11-23 00:00:00',''),(9,'Gómez','López','','1990-05-12 00:00:00',''),(10,'Pérez','Ruiz','','1992-08-23 00:00:00',''),(11,'Martínez','Santos','','1988-11-03 00:00:00',''),(12,'Hernández','García','','1995-02-19 00:00:00',''),(13,'Ramírez','Torres','','1991-07-08 00:00:00',''),(14,'Morales','Fernández','','1993-09-14 00:00:00',''),(15,'Castro','Ortega','','1989-12-22 00:00:00',''),(16,'Navarro','Delgado','','1994-06-30 00:00:00',''),(17,'Flores','Mendoza','','1990-03-15 00:00:00',''),(18,'Serrano','Reyes','','1992-10-05 00:00:00',''),(19,'Vargas','Campos','','1987-01-28 00:00:00',''),(20,'Silva','Carrillo','','1991-04-19 00:00:00',''),(21,'Cruz','Romero','','1993-07-02 00:00:00',''),(22,'Iglesias','Rojas','','1990-09-21 00:00:00',''),(24,'Cortés','Soto','','1995-01-03 00:00:00',''),(25,'Núñez','León','','1992-05-28 00:00:00',''),(26,'Ramos','Moreno','','1991-12-11 00:00:00',''),(27,'Molina','Pérez','','1993-03-25 00:00:00',''),(28,'Núñez','León','','1992-05-28 00:00:00',''),(29,'Lara','Campos','','1990-08-07 00:00:00',''),(30,'Molina','Pérez','','1993-03-25 00:00:00',''),(33,'Silva','','','2025-11-16 00:00:00',''),(34,'alumno','','','2003-03-26 00:00:00',''),(35,'maricon','choles','','2025-11-07 00:00:00',''),(36,'Fuentes','Vega','','1988-11-17 00:00:00',''),(37,'Silva','','','2025-11-17 00:00:00',''),(38,'Peru','','','2025-11-17 00:00:00',''),(39,'mario','','','2025-11-17 00:00:00',''),(40,'Molina','Pérez','','1993-03-25 00:00:00',''),(41,'Silva','','','2025-11-18 00:00:00','');
/*!40000 ALTER TABLE `alumno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciclo`
--

DROP TABLE IF EXISTS `ciclo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciclo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `familia_id` int NOT NULL,
  `nivel` enum('BASICO','MEDIO','SUPERIOR','ESPECIALIZACION') NOT NULL,
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ciclo_familia1_idx` (`familia_id`),
  CONSTRAINT `fk_ciclo_familia1` FOREIGN KEY (`familia_id`) REFERENCES `familia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciclo`
--

LOCK TABLES `ciclo` WRITE;
/*!40000 ALTER TABLE `ciclo` DISABLE KEYS */;
INSERT INTO `ciclo` VALUES (1,1,'BASICO','Técnico Básico en Acceso y Conservación en Instalaciones Deportivas'),(2,1,'MEDIO','Técnico en Guía en el Medio Natural y de Tiempo Libre'),(3,1,'MEDIO','Técnico en Actividades Ecuestres'),(4,1,'SUPERIOR','Técnico Superior en Acondicionamiento Físico'),(5,1,'SUPERIOR','Técnico Superior en Enseñanza y Animación Sociodeportiva'),(6,2,'BASICO','Técnico Básico en Servicios Administrativos'),(7,2,'BASICO','Técnico Básico en Informática de Oficina'),(8,2,'MEDIO','Técnico en Gestión Administrativa'),(9,2,'SUPERIOR','Técnico Superior en Administración y Finanzas'),(10,2,'SUPERIOR','Técnico Superior en Asistencia a la Dirección'),(11,3,'BASICO','Técnico Básico en Agro-jardinería y Composiciones Florales'),(12,3,'BASICO','Técnico Básico en Actividades Agropecuarias'),(13,3,'BASICO','Técnico Básico en Aprovechamientos Forestales'),(14,3,'MEDIO','Técnico en Producción Agropecuaria'),(15,3,'MEDIO','Técnico en Aprovechamiento y Conservación del Medio Natural'),(16,3,'MEDIO','Técnico en Jardinería y Floristería'),(17,3,'MEDIO','Técnico en Producción Agroecológica'),(18,3,'SUPERIOR','Técnico Superior en Gestión Forestal y del Medio Natural'),(19,3,'SUPERIOR','Técnico Superior en Paisajismo y Medio Rural'),(20,3,'SUPERIOR','Técnico Superior en Ganadería y Asistencia en Sanidad Animal'),(21,3,'ESPECIALIZACION','Master en Floristería y Arte Floral'),(22,4,'BASICO','Técnico Básico en Artes Gráficas'),(23,4,'MEDIO','Técnico en Preimpresión Digital'),(24,4,'MEDIO','Técnico en Impresión Gráfica'),(25,4,'MEDIO','Técnico en Postimpresión y Acabados Gráficos'),(26,4,'SUPERIOR','Técnico Superior en Diseño y Gestión de la Producción Gráfica'),(27,4,'SUPERIOR','Técnico Superior en Diseño y Edición de Publicaciones Impresas y Multimedia'),(28,5,'SUPERIOR','Técnico Superior en Ebanistería Artística'),(29,5,'SUPERIOR','Técnico Superior en Artes Aplicadas a la Escultura'),(30,5,'SUPERIOR','Técnico Superior en Joyería Artística'),(31,6,'BASICO','Técnico Básico en Servicios Comerciales'),(32,6,'MEDIO','Técnico en Actividades Comerciales'),(33,6,'SUPERIOR','Técnico Superior en Comercio Internacional'),(34,6,'SUPERIOR','Técnico Superior en Marketing y Publicidad'),(35,6,'SUPERIOR','Técnico Superior en Gestión de Ventas y Espacios Comerciales'),(36,6,'SUPERIOR','Técnico Superior en Transporte y Logística'),(37,6,'ESPECIALIZACION','Master en Comercio Electrónico'),(38,6,'ESPECIALIZACION','Master en Posicionamiento en Buscadores (SEO/SEM) y Comunicación en Redes Sociales'),(39,6,'ESPECIALIZACION','Master en Redacción de Contenidos Digitales para Marketing y Ventas'),(40,7,'BASICO','Técnico Básico en Reforma y Mantenimiento de Edificios'),(41,7,'MEDIO','Técnico en Construcción'),(42,7,'MEDIO','Técnico en Obras de Interior, Decoración y Restauración'),(43,7,'SUPERIOR','Técnico Superior en Proyectos de Edificación'),(44,7,'SUPERIOR','Técnico Superior en Proyectos de Obra Civil'),(45,7,'SUPERIOR','Técnico Superior en Organización y Control de Obras de Construcción'),(46,7,'ESPECIALIZACION','Master en Modelado de la Información en la Construcción (BIM)'),(47,8,'BASICO','Técnico Básico en Electricidad y Electrónica'),(48,8,'BASICO','Técnico Básico en Instalaciones Electrotécnicas y Mecánica'),(49,8,'MEDIO','Técnico en Instalaciones Eléctricas y Automáticas'),(50,8,'MEDIO','Técnico en Instalaciones de Telecomunicaciones'),(51,8,'SUPERIOR','Técnico Superior en Automatización y Robótica Industrial'),(52,8,'SUPERIOR','Técnico Superior en Sistemas Electrotécnicos y Automatizados'),(53,8,'SUPERIOR','Técnico Superior en Mantenimiento Electrónico'),(54,8,'SUPERIOR','Técnico Superior en Sistemas de Telecomunicaciones e Informáticos'),(55,8,'SUPERIOR','Técnico Superior en Electromedicina Clínica'),(56,8,'ESPECIALIZACION','Master en Ciberseguridad en Entornos de las Tecnologías de Operación'),(57,8,'ESPECIALIZACION','Master en Implementación de Redes 5G'),(58,8,'ESPECIALIZACION','Master en Robótica Colaborativa'),(59,8,'ESPECIALIZACION','Master en Sistemas de Señalización y Telecomunicaciones Ferroviarias'),(60,9,'MEDIO','Técnico en Redes y Estaciones de Tratamiento de Aguas'),(61,9,'SUPERIOR','Técnico Superior en Energías Renovables'),(62,9,'SUPERIOR','Técnico Superior en Eficiencia Energética y Energía Solar Térmica'),(63,9,'SUPERIOR','Técnico Superior en Gestión del Agua'),(64,9,'ESPECIALIZACION','Master en Auditoría Energética'),(65,10,'BASICO','Técnico Básico en Fabricación y Montaje'),(66,10,'BASICO','Técnico Básico en Fabricación de Elementos Metálicos'),(67,10,'MEDIO','Técnico en Mecanizado'),(68,10,'MEDIO','Técnico en Soldadura y Calderería'),(69,10,'MEDIO','Técnico en Conformado por Moldeo de Metales y Polímeros'),(70,10,'SUPERIOR','Técnico Superior en Diseño en Fabricación Mecánica'),(71,10,'SUPERIOR','Técnico Superior en Programación de la Producción en Fabricación Mecánica'),(72,10,'SUPERIOR','Técnico Superior en Construcciones Metálicas'),(73,10,'ESPECIALIZACION','Master en Fabricación Aditiva'),(74,10,'ESPECIALIZACION','Master en Materiales Compuestos en la Industria Aeroespacial'),(75,11,'BASICO','Técnico Básico en Cocina y Restauración'),(76,11,'BASICO','Técnico Básico en Actividades de Panadería y Pastelería'),(77,11,'BASICO','Técnico Básico en Alojamiento y Lavandería'),(78,11,'MEDIO','Técnico en Cocina y Gastronomía'),(79,11,'MEDIO','Técnico en Servicios en Restauración'),(80,11,'MEDIO','Técnico en Comercialización de Productos Alimentarios'),(81,11,'SUPERIOR','Técnico Superior en Dirección de Cocina'),(82,11,'SUPERIOR','Técnico Superior en Dirección de Servicios de Restauración'),(83,11,'SUPERIOR','Técnico Superior en Gestión de Alojamientos Turísticos'),(84,11,'SUPERIOR','Técnico Superior en Guía, Información y Asistencias Turísticas'),(85,11,'ESPECIALIZACION','Master en Coordinación del Personal en Reuniones Profesionales, Congresos, Ferias, Exposiciones y Eventos'),(86,11,'ESPECIALIZACION','Master en Panadería y Bollería Artesanales'),(87,12,'BASICO','Técnico Básico en Peluquería y Estética'),(88,12,'MEDIO','Técnico en Peluquería y Cosmética Capilar'),(89,12,'MEDIO','Técnico en Estética y Belleza'),(90,12,'SUPERIOR','Técnico Superior en Estilismo y Dirección de Peluquería'),(91,12,'SUPERIOR','Técnico Superior en Asesoría de Imagen Personal y Corporativa'),(92,12,'SUPERIOR','Técnico Superior en Caracterización y Maquillaje Profesional'),(93,12,'SUPERIOR','Técnico Superior en Estética Integral y Bienestar'),(94,12,'SUPERIOR','Técnico Superior en Termalismo y Bienestar'),(95,13,'MEDIO','Técnico en Vídeo Disc-Jockey y Sonido'),(96,13,'SUPERIOR','Técnico Superior en Producción de Audiovisuales y Espectáculos'),(97,13,'SUPERIOR','Técnico Superior en Iluminación, Captación y Tratamiento de Imagen'),(98,13,'SUPERIOR','Técnico Superior en Sonido para Audiovisuales y Espectáculos'),(99,13,'ESPECIALIZACION','Master en Audiodescripción y Subtitulación'),(100,14,'BASICO','Técnico Básico en Industrias Alimentarias'),(101,14,'MEDIO','Técnico en Panadería, Repostería y Confitería'),(102,14,'MEDIO','Técnico en Elaboración de Productos Alimenticios'),(103,14,'MEDIO','Técnico en Aceites de Oliva y Vinos'),(104,14,'SUPERIOR','Técnico Superior en Procesos y Calidad en la Industria Alimentaria'),(105,14,'SUPERIOR','Técnico Superior en Vitivinicultura'),(106,14,'ESPECIALIZACION','Master en Tecnología y Gestión Quesera'),(107,15,'MEDIO','Técnico en Piedra Natural'),(108,15,'MEDIO','Técnico en Excavaciones y Sondeos'),(109,15,'SUPERIOR','Técnico Superior en Exploración y Sondajes'),(110,16,'BASICO','Técnico Básico en Informática y Comunicaciones'),(111,16,'BASICO','Técnico Básico en Informática de Oficina'),(112,16,'MEDIO','Técnico en Sistemas Microinformáticos y Redes'),(113,16,'SUPERIOR','Técnico Superior en Desarrollo de Aplicaciones Web (DAW)'),(114,16,'SUPERIOR','Técnico Superior en Administración de Sistemas Informáticos en Red (ASIR)'),(115,16,'SUPERIOR','Técnico Superior en Desarrollo de Aplicaciones Multiplataforma (DAM)'),(116,16,'ESPECIALIZACION','Master en Ciberseguridad en Entornos de las Tecnologías de la Información'),(117,16,'ESPECIALIZACION','Master en Inteligencia Artificial y Big Data'),(118,16,'ESPECIALIZACION','Master en Desarrollo de Videojuegos y Realidad Virtual'),(119,16,'ESPECIALIZACION','Master en Desarrollo de Aplicaciones en Lenguaje Python'),(120,16,'ESPECIALIZACION','Master en Administración de Recursos y Servicios en la Nube'),(121,17,'BASICO','Técnico Básico en Mantenimiento de Viviendas'),(122,17,'MEDIO','Técnico en Mantenimiento Electromecánico'),(123,17,'MEDIO','Técnico en Instalaciones Frigoríficas y de Climatización'),(124,17,'MEDIO','Técnico en Instalaciones de Producción de Calor'),(125,17,'SUPERIOR','Técnico Superior en Mecatrónica Industrial'),(126,17,'SUPERIOR','Técnico Superior en Desarrollo de Proyectos de Instalaciones Térmicas y de Fluidos'),(127,17,'SUPERIOR','Técnico Superior en Mantenimiento de Instalaciones Térmicas y de Fluidos'),(128,17,'SUPERIOR','Técnico Superior en Mantenimiento Aeromecánico de Aviones con Motor de Turbina'),(129,17,'SUPERIOR','Técnico Superior en Mantenimiento Aeromecánico de Helicópteros con Motor de Turbina'),(130,17,'ESPECIALIZACION','Master en Digitalización del Mantenimiento Industrial'),(131,17,'ESPECIALIZACION','Master en Fabricación Inteligente'),(132,17,'ESPECIALIZACION','Master en Modelado de la Información en la Construcción (BIM)'),(133,18,'BASICO','Técnico Básico en Carpintería y Mueble'),(134,18,'MEDIO','Técnico en Carpintería y Mueble'),(135,18,'MEDIO','Técnico en Instalación y Amueblamiento'),(136,18,'SUPERIOR','Técnico Superior en Diseño y Amueblamiento'),(137,19,'BASICO','Técnico Básico en Mantenimiento de Embarcaciones Deportivas y de Recreo'),(138,19,'BASICO','Técnico Básico en Actividades Marítimo-Pesqueras'),(139,19,'MEDIO','Técnico en Navegación y Pesca de Litoral'),(140,19,'MEDIO','Técnico en Mantenimiento y Control de la Maquinaria de Buques y Embarcaciones'),(141,19,'SUPERIOR','Técnico Superior en Transporte Marítimo y Pesca de Altura'),(142,19,'SUPERIOR','Técnico Superior en Organización del Mantenimiento de Maquinaria de Buques y Embarcaciones'),(143,20,'MEDIO','Técnico en Planta Química'),(144,20,'SUPERIOR','Técnico Superior en Química Industrial'),(145,20,'SUPERIOR','Técnico Superior en Laboratorio de Análisis y de Control de Calidad'),(146,20,'ESPECIALIZACION','Master en Cultivos Celulares'),(147,21,'MEDIO','Técnico en Cuidados Auxiliares de Enfermería'),(148,21,'MEDIO','Técnico en Farmacia y Parafarmacia'),(149,21,'MEDIO','Técnico en Emergencias Sanitarias'),(150,21,'SUPERIOR','Técnico Superior en Laboratorio Clínico y Biomédico'),(151,21,'SUPERIOR','Técnico Superior en Higiene Bucodental'),(152,21,'SUPERIOR','Técnico Superior en Radioterapia y Dosimetría'),(153,21,'SUPERIOR','Técnico Superior en Imagen para el Diagnóstico y Medicina Nuclear'),(154,21,'SUPERIOR','Técnico Superior en Prótesis Dentales'),(155,21,'SUPERIOR','Técnico Superior en Dietética'),(156,22,'MEDIO','Técnico en Emergencias y Protección Civil'),(157,22,'SUPERIOR','Técnico Superior en Coordinación de Emergencias y Protección Civil'),(158,22,'SUPERIOR','Técnico Superior en Educación y Control Ambiental'),(159,22,'SUPERIOR','Técnico Superior en Química y Salud Ambiental'),(160,23,'BASICO','Técnico Básico en Actividades Domésticas y Limpieza de Edificios'),(161,23,'SUPERIOR','Técnico Superior en Educación Infantil'),(162,23,'SUPERIOR','Técnico Superior en Integración Social'),(163,23,'SUPERIOR','Técnico Superior en Promoción de Igualdad de Género'),(164,23,'SUPERIOR','Técnico Superior en Mediación Comunicativa'),(165,23,'SUPERIOR','Técnico Superior en Animación Sociocultural y Turística'),(166,23,'SUPERIOR','Técnico Superior en Formación para la Movilidad Segura y Sostenible'),(167,24,'BASICO','Técnico Básico en Tapicería y Cortinaje'),(168,24,'BASICO','Técnico Básico en Arreglo y Reparación de Artículos Textiles y de Piel'),(169,24,'MEDIO','Técnico en Confección y Moda'),(170,24,'MEDIO','Técnico en Calzado y Complementos de Moda'),(171,24,'SUPERIOR','Técnico Superior en Patronaje y Moda'),(172,24,'SUPERIOR','Técnico Superior en Diseño Técnico en Textil y Piel'),(173,25,'BASICO','Técnico Básico en Mantenimiento de Vehículos'),(174,25,'MEDIO','Técnico en Electromecánica de Vehículos Automóviles'),(175,25,'MEDIO','Técnico en Carrocería'),(176,25,'SUPERIOR','Técnico Superior en Automoción'),(177,25,'SUPERIOR','Técnico Superior en Mantenimiento de Sistemas Electrónicos y Aviónicos en Aeronaves'),(178,25,'ESPECIALIZACION','Master en Mantenimiento de Vehículos Híbridos y Eléctricos'),(179,25,'ESPECIALIZACION','Master en Aeronaves Pilotadas de Forma Remota-Drones'),(180,25,'ESPECIALIZACION','Mantenimiento Avanzado de Sistemas de Material Rodante Ferroviario'),(181,25,'ESPECIALIZACION','Digitalización del Mantenimiento Industrial (M. Aeronáutica)'),(182,26,'BASICO','Técnico Básico en Vidriería y Alfarería'),(183,26,'SUPERIOR','Técnico Superior en Desarrollo y Fabricación de Productos Cerámicos');
/*!40000 ALTER TABLE `ciclo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciclo_tiene_oferta`
--

DROP TABLE IF EXISTS `ciclo_tiene_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciclo_tiene_oferta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ciclo_id` int NOT NULL,
  `oferta_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ciclo-tiene-oferta_ciclo1_idx` (`ciclo_id`),
  KEY `fk_ciclo-tiene-oferta_oferta1_idx` (`oferta_id`),
  CONSTRAINT `fk_ciclo-tiene-oferta_ciclo1` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclo` (`id`),
  CONSTRAINT `fk_ciclo-tiene-oferta_oferta1` FOREIGN KEY (`oferta_id`) REFERENCES `oferta` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciclo_tiene_oferta`
--

LOCK TABLES `ciclo_tiene_oferta` WRITE;
/*!40000 ALTER TABLE `ciclo_tiene_oferta` DISABLE KEYS */;
INSERT INTO `ciclo_tiene_oferta` VALUES (1,1,1),(2,2,2),(3,115,6),(4,113,6),(7,113,9),(10,113,10),(11,115,10),(14,128,11),(15,29,11);
/*!40000 ALTER TABLE `ciclo_tiene_oferta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa` (
  `id` int NOT NULL,
  `correoContacto` varchar(45) NOT NULL,
  `telefonoContacto` varchar(45) NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '0',
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa_user1_idx` (`id`),
  CONSTRAINT `fk_empresa_user1` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES (2,'rrhh@innovatech.com','600123456',1,'Empresa tecnológica especializada en IA.'),(4,'contacto@softsolutions.com','611987654',1,'Consultora de software empresarial.'),(6,'amsystem@gmail.com','666666666',1,'Empresa tecnológica especializada en IA.'),(7,'nter@gmail.com','666666666',1,'Consultora de software empresarial.');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familia`
--

DROP TABLE IF EXISTS `familia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familia`
--

LOCK TABLES `familia` WRITE;
/*!40000 ALTER TABLE `familia` DISABLE KEYS */;
INSERT INTO `familia` VALUES (1,'Actividades Físicas y Deportivas'),(2,'Administración y Gestión'),(3,'Agraria'),(4,'Artes Gráficas'),(5,'Artes y Artesanías'),(6,'Comercio y Marketing'),(7,'Edificación y Obra Civil'),(8,'Electricidad y Electrónica'),(9,'Energía y Agua'),(10,'Fabricación Mecánica'),(11,'Hostelería y Turismo'),(12,'Imagen Personal'),(13,'Imagen y Sonido'),(14,'Industrias Alimentarias'),(15,'Industrias Extractivas'),(16,'Informática y Comunicaciones'),(17,'Instalación y Mantenimiento'),(18,'Madera, Mueble y Corcho'),(19,'Marítimo-Pesquera'),(20,'Química'),(21,'Sanidad'),(22,'Seguridad y Medio Ambiente'),(23,'Servicios Socioculturales y a la Comunidad'),(24,'Textil, Confección y Piel'),(25,'Transporte y Mantenimiento de Vehículos'),(26,'Vidrio y Cerámica');
/*!40000 ALTER TABLE `familia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oferta`
--

DROP TABLE IF EXISTS `oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oferta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(1000) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oferta_empresa1_idx` (`empresa_id`),
  CONSTRAINT `fk_oferta_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oferta`
--

LOCK TABLES `oferta` WRITE;
/*!40000 ALTER TABLE `oferta` DISABLE KEYS */;
INSERT INTO `oferta` VALUES (1,2,'Desarrollador Web Junior','Puesto para prácticas en desarrollo web.','2025-10-01 09:00:00','2025-12-31 17:00:00'),(2,4,'Técnico de Sistemas','Asistente en administración de redes y sistemas.','2025-09-15 09:00:00','2025-11-30 17:00:00'),(6,7,'Desarrollador Web','Gente con experiencia y gente con cojoneh','2025-11-20 00:00:00','2025-11-29 00:00:00'),(9,7,'Desarrollador Web','Buscamos un/a Desarrollador/a Web talentoso/a y creativo/a para unirse a nuestro equipo y llevar nuestros proyectos digitales al siguiente nivel. La persona ideal es apasionada por la tecnología, tiene experiencia en desarrollo front-end y back-end, y está al día con las últimas tendencias en diseño y programación web.','2025-11-15 00:00:00','2025-11-29 00:00:00'),(10,7,'Desarrollador Web','Buscamos un/a Desarrollador/a Web talentoso/a y creativo/a para unirse a nuestro equipo y llevar nuestros proyectos digitales al siguiente nivel. La persona ideal es apasionada por la tecnología, tiene experiencia en desarrollo front-end y back-end, y está al día con las últimas tendencias en diseño y programación web.','2025-11-15 00:00:00','2025-11-21 00:00:00'),(11,7,'Prueba','Plataforma educativa digital con cursos personalizados.','2025-11-06 00:00:00','2025-11-19 00:00:00');
/*!40000 ALTER TABLE `oferta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Empresa'),(3,'Alumno');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitud` (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno_id` int NOT NULL,
  `oferta_id` int NOT NULL,
  `estado` enum('PROCESO','ACEPTADO','DENEGADO','INTERESADO') DEFAULT 'PROCESO',
  PRIMARY KEY (`id`),
  KEY `fk_solicitud_alumno1_idx` (`alumno_id`),
  KEY `fk_solicitud_oferta1_idx` (`oferta_id`),
  CONSTRAINT `fk_solicitud_alumno1` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`),
  CONSTRAINT `fk_solicitud_oferta1` FOREIGN KEY (`oferta_id`) REFERENCES `oferta` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
INSERT INTO `solicitud` VALUES (1,3,1,'PROCESO'),(2,5,2,'ACEPTADO'),(21,34,10,'PROCESO');
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` VALUES (1,'ABC123',NULL),(2,'XYZ789',NULL),(3,'QWE456',NULL),(4,'726367754a090ee0c92e1a1f73cd4ab5bed0ee5d','2025-11-19 09:23:44'),(5,'45ce0629e8ab86da907204350ee8e31a7b47d6c9','2025-11-17 22:34:27'),(6,'ed659803c9d13561d910fc895e5050fe74fa41aa','2025-11-17 22:42:18'),(7,'bed3c3f1396e7f4ac055cf7dcaa2271a6e4e4388','2025-11-18 21:27:22'),(8,'8ecb2e97e7c2ad8869f57114a56681873329b7bf','2025-11-19 09:28:32'),(9,'97ef4efb4fc82e0f7498a778f5eaf30131917e9b','2025-11-18 09:15:41'),(10,'1ee28ce72b953e37be3e061e28b9a6acde953700','2025-11-18 11:39:55');
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `rol_id` int NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `token_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_user_rol_idx` (`rol_id`),
  KEY `fk_user_token1_idx` (`token_id`),
  CONSTRAINT `fk_user_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`),
  CONSTRAINT `fk_user_token1` FOREIGN KEY (`token_id`) REFERENCES `token` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Carlos Pérez','carlos.perez@admin.com','admin123',1,'Calle Mayor 123','foto1.jpg',1),(2,'Innovatech SA','contacto@innovatech.com','',2,'Av. Industria 45','empresa1.png',2),(3,'María López','maria.lopez@gmail.com','alumno123',3,'Calle Sol 22','maria.jpg',3),(4,'SoftSolutions SL','rrhh@softsolutions.com','$2y$10$RPWYrHvBomxqVImf/JrAl.leK9Q8n2Xa0OyOt3iluIieMBOxbLS6u',2,'Paseo del Parque 5','empresa2.png',NULL),(5,'Juan García','juan.garcia@gmail.com','alumno456',3,'Calle Luna 10','juan.jpg',NULL),(6,'AM System','amsystem@gmail.com','',2,'Av. Industria 45','empresa_6.png',NULL),(7,'NTer','nter@gmail.com','$2y$10$FG1TYzXDfyUOhyAxmhA22e3pqCHfeGg0jxswsT9d0tuR3iQePzUuq',2,'Paseo de la Estación, 1','empresa_7.png',4),(8,'admin','admin@gmail.com','$2y$10$3pyxNggbYnA6iZ9svPcYUOZW/4OKRpZ2yAR1hLhlBba2PGo.aW6.i',1,'calle jaen, 20, jaen','empresa_8.jpeg',7),(9,'Carlos','carlos.gomez1@gmail.com','Carlos@0311',3,'Calle Falsa 123, Madrid','',NULL),(10,'María','maria.perez2@gmail.com','María@0311',3,'Avenida del Sol 45, Barcelona','',NULL),(11,'José','jose.martinez3@gmail.com','José@0311',3,'Calle Mayor 12, Valencia','',NULL),(12,'Lucía','lucia.hernandez4@gmail.com','Lucía@0311',3,'Plaza Nueva 7, Sevilla','',NULL),(13,'Andrés','andres.ramirez5@gmail.com','Andrés@0311',3,'Calle Real 34, Bilbao','',NULL),(14,'Elena','elena.morales6@gmail.com','Elena@0311',3,'Avenida de la Paz 56, Málaga','',NULL),(15,'Javier','javier.castro7@gmail.com','Javier@0311',3,'Calle Luna 9, Zaragoza','',NULL),(16,'Sofía','sofia.navarro8@gmail.com','Sofía@0311',3,'Paseo del Prado 21, Granada','',NULL),(17,'Raúl','raul.flores9@gmail.com','Raúl@0311',3,'Calle Victoria 17, Murcia','',NULL),(18,'Ana','ana.serrano10@gmail.com','Ana@0311',3,'Avenida Constitución 3, Salamanca','',NULL),(19,'Miguel','miguel.vargas11@gmail.com','Miguel@0311',3,'Calle Jardín 8, Toledo','',NULL),(20,'Laura','laura.silva12@gmail.com','Laura@0311',3,'Plaza Mayor 11, Valladolid','',NULL),(21,'David','david.cruz13@gmail.com','David@0311',3,'Calle Gran Vía 14, León','',NULL),(22,'Paula','paula.iglesias14@gmail.com','Paula@0311',3,'Avenida Libertad 5, Cádiz','',NULL),(24,'Isabel','isabel.cortes16@gmail.com','Isabel@0311',3,'Paseo de las Flores 42, Girona','',NULL),(25,'Daniel','daniel.nunez17@gmail.com','Daniel@0311',3,'Calle del Sol 19, Pamplona','',NULL),(26,'Patricia','patricia.ramos18@gmail.com','Patricia@0311',3,'Avenida de Europa 33, Santander','',NULL),(27,'Natalia','natalia.molina20@gmail.com','Natalia@0311',3,'Plaza de la Cruz 6, Oviedo','',NULL),(28,'Daniel','daniasda17@gmail.com','Daniel@0311',3,'Calle del Sol 19, Pamplona','',NULL),(29,'Sergio','laurasd@gmail.com','Sergio@0311',3,'Calle Río 16, Logroño','',NULL),(30,'Natalia','natad0@gmail.com','Natalia@0311',3,'Plaza de la Cruz 6, Oviedo','',NULL),(33,'prueba','prueba26@gmail.com','$2y$10$hMm8DkHHJmHRnJlFa2e6CeBNGwAqgE/XyI/xjyoMTjkdS/GFyJ2F2',3,'Av. Industria 45','',6),(34,'alumno','alumno@gmail.com','$2y$10$bAdXGXVUZOkZJfkl4pxsXuB6ZeCzXB7mmJOoHCol.n3pM5xtDL3yW',3,'alumno','empresa_8.jpeg',8),(35,'zela','zelamarica@gmail.com','$2y$10$IRdFNhXHCMY4i6uAq5krUOomsKhYdykgNuVUXjRJkI4cgB736Ztye',3,'maricons','',NULL),(36,'Alberto','alberto.fuentes15@gmail.com','Alberto@5511',3,'Calle Olivo 27, Salamanca','',NULL),(37,'Innovatech SA','nacho@gmail.com','$2y$10$Ncm0LLXvQM.xlzOOn9QHQO22Ypaxeq9fXjksoq81JqT.GqCFcGU.G',3,'Calle Aprendizaje 7','',9),(38,'Ruben','nofibra@gmail.com','$2y$10$FJ5xh.j3HJ7LttQ1KveErO3GGeXbKq81NUCPO6j5FIB32j18x8hPC',3,'fibrasmart','',NULL),(39,'mario','mario@gmail.com','$2y$10$/7kKCwA0FXzAOPMnCrdc6e0wNGZ/i0EBzIaz4kF46yDYBYGnymSfC',3,'mario','',10),(40,'Natalia','nataasda@gmail.com','Natalia@2811',3,'Plaza de la Cruz 6, Oviedo','',NULL),(41,'Innovatech S.A.','silverio@gmail.com','$2y$10$rNL6aE8s25n0rWg4s1D0f.reWytMcWdjeVoxRqyowXzLOn63Oi/su',3,'afarf','',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-18 10:35:28
