mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 9.6.0, for Win64 (x86_64)
--
-- Host: localhost    Database: furboshirts
-- ------------------------------------------------------
-- Server version	9.6.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `ID_CAT` int NOT NULL AUTO_INCREMENT,
  `PRENDA` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DESCRIPCION` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`ID_CAT`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (6,'Camiseta','Camiseta  Deportiva'),(7,'Pantalones','Pantalones Deportivos'),(8,'Calcetines','Calcetines Deportivos'),(10,'Chandal','Chandal deportivo'),(18,'Chaqueta','Chaqueta Deportiva');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_deportes`
--

DROP TABLE IF EXISTS `categorias_deportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias_deportes` (
  `ID_CAT` int NOT NULL,
  `ID_DEPORTE` int NOT NULL,
  KEY `fk_deporte` (`ID_DEPORTE`),
  KEY `fk_cat_deportes_cat_rel` (`ID_CAT`),
  CONSTRAINT `fk_cat_deportes_cat_rel` FOREIGN KEY (`ID_CAT`) REFERENCES `categorias` (`ID_CAT`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categoria` FOREIGN KEY (`ID_CAT`) REFERENCES `categorias` (`ID_CAT`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_deporte` FOREIGN KEY (`ID_DEPORTE`) REFERENCES `deportes` (`ID_DEPORTE`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_deportes`
--

LOCK TABLES `categorias_deportes` WRITE;
/*!40000 ALTER TABLE `categorias_deportes` DISABLE KEYS */;
INSERT INTO `categorias_deportes` VALUES (8,1),(10,1),(10,2),(18,1),(18,2),(18,3),(7,1),(7,2),(6,1),(6,2),(6,3);
/*!40000 ALTER TABLE `categorias_deportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competiciones`
--

DROP TABLE IF EXISTS `competiciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competiciones` (
  `ID_COMP` int NOT NULL AUTO_INCREMENT,
  `NOMBRE_COMP` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `TIPO_COMP` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_COMP`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competiciones`
--

LOCK TABLES `competiciones` WRITE;
/*!40000 ALTER TABLE `competiciones` DISABLE KEYS */;
INSERT INTO `competiciones` VALUES (1,'La Liga','nacional'),(2,'La Liga Hypermotion','nacional'),(4,'UEFA CHAMPIONS LEAGUE','intercontinental'),(5,'Mundial 2026','seleccion'),(6,'Premier League','nacional'),(8,'Ligue 1','nacional'),(9,'Bundesliga','nacional'),(10,'Eredivise','nacional'),(11,'UEFA Eurapa League','intercontinental'),(12,'UEFA Conference LEAGUE','intercontinental'),(13,'Seria A','nacional'),(14,'Liga MX','nacional'),(15,'Copa Libertadores','intercontinental'),(16,'Euro 2024','seleccion'),(23,'Brasileir??o ','nacional'),(24,'Liga Argentina','nacional');
/*!40000 ALTER TABLE `competiciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deportes`
--

DROP TABLE IF EXISTS `deportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deportes` (
  `ID_DEPORTE` int NOT NULL AUTO_INCREMENT,
  `DEPORTE` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_DEPORTE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deportes`
--

LOCK TABLES `deportes` WRITE;
/*!40000 ALTER TABLE `deportes` DISABLE KEYS */;
INSERT INTO `deportes` VALUES (1,'F??tbol'),(2,'Baloncesto'),(3,'F1');
/*!40000 ALTER TABLE `deportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_pedido`
--

DROP TABLE IF EXISTS `detalles_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles_pedido` (
  `ID_DETALLE` int NOT NULL AUTO_INCREMENT,
  `ID_PEDIDO` int DEFAULT NULL,
  `ID_PRODUCTO` int DEFAULT NULL,
  `TALLA` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PARCHE` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CANTIDAD` int NOT NULL,
  `PRECIO_UNITARIO` decimal(10,2) NOT NULL,
  `DORSAL` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NOMBRE_PERSONALIZADO` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_DETALLE`),
  KEY `fk_det_pedido_p` (`ID_PEDIDO`),
  KEY `fk_det_pedido_prod` (`ID_PRODUCTO`),
  CONSTRAINT `fk_det_pedido` FOREIGN KEY (`ID_PEDIDO`) REFERENCES `pedidos` (`ID_PEDIDO`) ON DELETE CASCADE,
  CONSTRAINT `fk_det_pedido_p` FOREIGN KEY (`ID_PEDIDO`) REFERENCES `pedidos` (`ID_PEDIDO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_det_pedido_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_det_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_pedido`
--

LOCK TABLES `detalles_pedido` WRITE;
/*!40000 ALTER TABLE `detalles_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalles_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entidad_deportiva`
--

DROP TABLE IF EXISTS `entidad_deportiva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidad_deportiva` (
  `ID_EQUIPO` int NOT NULL AUTO_INCREMENT,
  `NOMBRE_EQUIPO` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ESCUDO` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `TIPO` enum('Equipo','Seleccion') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_EQUIPO`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entidad_deportiva`
--

LOCK TABLES `entidad_deportiva` WRITE;
/*!40000 ALTER TABLE `entidad_deportiva` DISABLE KEYS */;
INSERT INTO `entidad_deportiva` VALUES (1,'Real Madrid','assets/img/equipos/1773769722_RealMadrid.png','Equipo'),(2,'Espa??a','assets/img/equipos/1773769988_Espa??a.png','Seleccion'),(3,'Germany','assets/img/equipos/1773770131_Alemania.png','Seleccion'),(4,'Argentina','assets/img/equipos/1773770168_Argentina.png','Seleccion'),(5,'Italia','assets/img/equipos/1773770224_Italia.png','Seleccion'),(6,'Albacete Balompie','assets/img/equipos/1773771290_Albacete.png','Equipo'),(12,'Barcelona','assets/img/equipos/1773953926_Barcelona.png','Equipo'),(13,'Chelsea','assets/img/equipos/1773953941_Chelsea.png','Equipo'),(14,'Arsenal','assets/img/equipos/1773953967_Arsenal.png','Equipo'),(15,'PSG','assets/img/equipos/1773953984_PSG.png','Equipo'),(16,'TRUE BOYS','assets/img/equipos/1774045893_TB.png','Equipo'),(17,'Brasil','assets/img/equipos/1774048243_Brasil.jpg','Seleccion'),(18,'Atletico de Madrid','assets/img/equipos/1774281992_ATM.png','Equipo'),(19,'Real Betis','assets/img/equipos/1774288138_Betih.png','Equipo'),(20,'Bayern','assets/img/equipos/1774288151_Bayern.png','Equipo'),(21,'Getafe','assets/img/equipos/1774288167_Getafe.png','Equipo'),(22,'Real Sociedad','assets/img/equipos/1774288197_RSO.png','Equipo'),(23,'Athletic Bilbao','assets/img/equipos/1774288229_Bilbao.png','Equipo'),(24,'Manchester City','assets/img/equipos/1774288288_City.png','Equipo'),(25,'Manchester United','assets/img/equipos/1774288319_ManU.png','Equipo'),(26,'Roma','assets/img/equipos/1774288342_Roma.png','Equipo'),(27,'Inter de MIlan','assets/img/equipos/1774288371_Inter.png','Equipo'),(28,'AC Milan','assets/img/equipos/1774288388_Milan.png','Equipo'),(29,'Napoli','assets/img/equipos/1774288413_Napoli.png','Equipo'),(30,'Juventus','assets/img/equipos/1774288426_Juve.png','Equipo'),(31,'Bolonia','assets/img/equipos/1774288454_Bolonia.png','Equipo'),(32,'Alcorcon','assets/img/equipos/1774288467_ALK.png','Equipo'),(33,'Al NASR','assets/img/equipos/1774288482_ALNASSR.png','Equipo'),(34,'Ajax','assets/img/equipos/1774288501_AJAX.png','Equipo'),(35,'Pumas UNAM','assets/img/equipos/1774288531_Pumas.png','Equipo'),(36,'Sporting Gijon','assets/img/equipos/1774288556_Gijon.png','Equipo'),(37,'Real Oviedo','assets/img/equipos/1774288569_Oviedo.png','Equipo'),(38,'Malaga','assets/img/equipos/1774288596_Malaga.png','Equipo'),(39,'Osasuna','assets/img/equipos/1774288636_Osasuna.png','Equipo'),(40,'Real Zaragoza','assets/img/equipos/1774288659_Zaragoza.png','Equipo'),(41,'Sevilla','assets/img/equipos/1774289007_Sevilla.png','Equipo'),(42,'Swansea City','assets/img/equipos/1774289027_Swansea.png','Equipo'),(43,'Noruega','assets/img/equipos/1774293693_Noruega.png','Seleccion'),(44,'Estados Unidos','assets/img/equipos/1774293712_USA.png','Seleccion'),(47,'Espa??a Basket','assets/img/equipos/1776808239_Espa??aBasket.png','Seleccion');
/*!40000 ALTER TABLE `entidad_deportiva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturas`
--

DROP TABLE IF EXISTS `facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturas` (
  `N_FACTURA` int NOT NULL AUTO_INCREMENT,
  `RUTA` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ID_PEDIDO` int DEFAULT NULL,
  PRIMARY KEY (`N_FACTURA`),
  UNIQUE KEY `ID_PEDIDO` (`ID_PEDIDO`),
  CONSTRAINT `fk_factura_pedido` FOREIGN KEY (`ID_PEDIDO`) REFERENCES `pedidos` (`ID_PEDIDO`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas`
--

LOCK TABLES `facturas` WRITE;
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes` (
  `ID_IMAGEN` int NOT NULL AUTO_INCREMENT,
  `RUTA` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ID_PRODUCTO` int DEFAULT NULL,
  PRIMARY KEY (`ID_IMAGEN`),
  KEY `fk_img_prod` (`ID_PRODUCTO`),
  CONSTRAINT `fk_img_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes`
--

LOCK TABLES `imagenes` WRITE;
/*!40000 ALTER TABLE `imagenes` DISABLE KEYS */;
INSERT INTO `imagenes` VALUES (10,'assets/img/productos/1774294216_RMA_AWAY_FRONT_24-25.jpeg',8),(11,'assets/img/productos/1774294216_2_RMA_AWAY_BACK_24-25.jpeg',8),(12,'assets/img/productos/1774294312_RMA_AWAY_FRONT_20-21.jpeg',9),(13,'assets/img/productos/1774294312_2_RMA_AWAY_BACK_20-21.jpeg',9),(14,'assets/img/productos/1774294652_Espa??a_Home_Front_2024.jpeg',10),(15,'assets/img/productos/1774294652_2_Espa??a_Home_Back_2024.jpeg',10),(16,'assets/img/productos/1774294765_Argentina_Away_Front_2022.jpeg',11),(17,'assets/img/productos/1774294765_2_Argentina_Away_Back_2022.jpeg',11),(18,'assets/img/productos/1774294873_FCB_AWAY_FRONT_22-23.jpeg',12),(19,'assets/img/productos/1774294873_2_FCB_AWAY_BACK_22-23.jpeg',12),(26,'assets/img/productos/1774895811_Ajax_Away_Fron_23-24.jpeg',16),(27,'assets/img/productos/1774895811_Ajax_Away_Back_23-24.jpeg',16),(31,'assets/img/productos/1774903043_Nor_Away_Front_2021.jpeg',13),(32,'assets/img/productos/1774903043_Nor_Away_Back_2021.jpeg',13),(35,'assets/img/productos/1774950692_RMA_DRAGON_FRONT_14-15.jpeg',19),(36,'assets/img/productos/1774950952_Chelsea_Away_front_25-26.jpeg',20),(37,'assets/img/productos/1774950952_2_Chelsea_Away_back_25-26.jpeg',20),(38,'assets/img/productos/1774951227_Alnassr_Home_Front_22-23.jpeg',21),(39,'assets/img/productos/1774951227_2_Alnassr_Home_Back_22-23.jpeg',21),(40,'assets/img/productos/1774951421_Arsenal_Away_FRONT_22-23.jpeg',22),(41,'assets/img/productos/1774951669_Espa??a_Home_2008.jpeg',23),(42,'assets/img/productos/1774951669_2_Espa??a_HOME_2008_Back.jpeg',23),(43,'assets/img/productos/1774951845_RMA_ALT_FRONT_20-21.jpeg',24),(44,'assets/img/productos/1774951845_2_RMA_ALT_BACK_20-21.jpeg',24),(47,'assets/img/productos/1774952351_RMA_HOME_FRONT_21-22.jpeg',25),(48,'assets/img/productos/1774952351_RMA_HOME_BACK_21-22.jpeg',25),(49,'assets/img/productos/1776034122_TB_POR_FRONT_2023.jpeg',26),(50,'assets/img/productos/1776034122_2_TB_POR_BACK_2023.jpeg',26),(51,'assets/img/productos/1776182027_Albacete_Home_Front_25-26.jpeg',27),(52,'assets/img/productos/1776182027_2_Albacete_Home_Back_25-26.jpeg',27),(53,'assets/img/productos/1776191289_Pumas_Front_24-25.jpeg',28),(54,'assets/img/productos/1776191289_2_Pumas_Back_24-25.jpeg',28),(55,'assets/img/productos/1776215431_ATM_HOME_FRONT_16-17.jpeg',29),(56,'assets/img/productos/1776272833_Swansea_Away_Front_17-18.jpeg',32),(58,'assets/img/productos/1776808743_Espa??aBaket_Away_2014_Front.jpeg',34),(59,'assets/img/productos/1776808743_2_Espa??aBaket_Away_2014_Back.jpeg',34);
/*!40000 ALTER TABLE `imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parches`
--

DROP TABLE IF EXISTS `parches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parches` (
  `ID_LOGO` int NOT NULL AUTO_INCREMENT,
  `PARCHE` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_LOGO`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parches`
--

LOCK TABLES `parches` WRITE;
/*!40000 ALTER TABLE `parches` DISABLE KEYS */;
INSERT INTO `parches` VALUES (1,'assets/img/parches/1773949474_LaLiga.png'),(2,'assets/img/parches/1773950531_LaLiga.png'),(4,'assets/img/parches/1773951972_ucl.png'),(5,'assets/img/parches/1773953159_Mundial2026.png'),(6,'assets/img/parches/1773959734_Premier.png'),(8,'assets/img/parches/1774289150_Ligue1.png'),(9,'assets/img/parches/1774289203_Bundesliga.png'),(10,'assets/img/parches/1774289573_eredivise.png'),(11,'assets/img/parches/1774289663_uel.png'),(12,'assets/img/parches/1774289997_conference.png'),(13,'assets/img/parches/1774290123_SeriaA.png'),(14,'assets/img/parches/1774292129_LigaMx.png'),(15,'assets/img/parches/1774292346_Libertadores.png'),(16,'assets/img/parches/1774292584_euro2024.png'),(23,'assets/img/parches/1776017556_brasileirao.png'),(24,'assets/img/parches/1776030527_LPF.png'),(26,'assets/img/parches/1776210275_Laliga-old.png'),(27,'assets/img/parches/1776210293_Premier-old.png'),(29,'assets/img/parches/1776210741_Laliga-old2.png'),(30,'assets/img/parches/1776215812_ucl-old.png');
/*!40000 ALTER TABLE `parches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `ID_PEDIDO` int NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int DEFAULT NULL,
  `FECHA` datetime DEFAULT CURRENT_TIMESTAMP,
  `TOTAL` decimal(10,2) NOT NULL,
  `ESTADO` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DIRECCION_ENVIO` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `METODO_PAGO` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_PEDIDO`),
  KEY `fk_pedido_user` (`ID_USUARIO`),
  CONSTRAINT `fk_pedido_user` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuarios` (`ID_USUARIO`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `ID_PRODUCTO` int NOT NULL AUTO_INCREMENT,
  `ID_EQUIPO` int DEFAULT NULL,
  `ID_CAT` int DEFAULT NULL,
  `ID_DEPORTE` int DEFAULT NULL,
  `NOMBRE` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `DESCRIPCION` text COLLATE utf8mb4_general_ci,
  `PRECIO` decimal(10,2) NOT NULL,
  `FECHA_ALTA` date DEFAULT NULL,
  `ANO_EDICION` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CARACTERISTICAS` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`ID_PRODUCTO`),
  KEY `fk_prod_cat` (`ID_CAT`),
  KEY `fk_producto_equipo` (`ID_EQUIPO`),
  KEY `fk_prod_deporte` (`ID_DEPORTE`),
  CONSTRAINT `fk_prod_cat` FOREIGN KEY (`ID_CAT`) REFERENCES `categorias` (`ID_CAT`) ON DELETE SET NULL,
  CONSTRAINT `fk_prod_deporte` FOREIGN KEY (`ID_DEPORTE`) REFERENCES `deportes` (`ID_DEPORTE`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_prod_equipo` FOREIGN KEY (`ID_EQUIPO`) REFERENCES `entidad_deportiva` (`ID_EQUIPO`) ON DELETE CASCADE,
  CONSTRAINT `fk_producto_equipo` FOREIGN KEY (`ID_EQUIPO`) REFERENCES `entidad_deportiva` (`ID_EQUIPO`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (8,1,6,1,'Camiseta Real Madrid Visita  Fan Version 24/25','Camiseta del real Madrid de la temporada 2024/25',60.00,'2026-03-23','2025','Escudo, y logos bordados'),(9,1,6,1,'Camiseta Real Madrid Visita Fan Version 20/21','Camiseta del Real Madrid de la temporada 2020/21',30.00,'2026-03-23','2021','Escudo termosellado y logo bordado'),(10,2,6,1,'Camiseta Espa??a Local  Fan versi??n Euro2024','Camiseta usada por la selecci??n espa??ola en la Eurocopa de alemania 2024',45.00,'2026-03-23','2024','Escudo termosellado y logotipo bordado'),(11,4,6,1,'	Camiseta Argentina Visitante Fan versi??n Mundial 2022','Camiseta usada por la seleccion argentina en el mundial de qatar 2022',45.00,'2026-03-23','2022','Camiseta con logos y escudos bordados'),(12,12,6,1,'Camiseta Visitante FC Barcelona  Fan versi??n 22/23','Camiseta usada del Barsa de la temporada 2022/23',55.00,'2026-03-23','2023',''),(13,43,6,1,'Camiseta Noruega Visitante 2021','Camiseta utilizada en 2022 por noruega para partidos amistosos y clasificatorias',25.00,'2026-03-23','2022',''),(16,34,6,1,'Camiseta Ajax Visitante Fan Version 2023/24','Camiseta del Ajax de Amsterdam de la temporada 2023/24',45.00,'2026-03-30','2024','Escudo y logo cosidos'),(19,1,6,1,'Camiseta Alternativa Real Madrid Fan Version 2014/15','Camiseta alternativa del real madrid usada en la temporada 2014/15',45.00,'2026-03-31','2015','Escudo y logos cosidos'),(20,13,6,1,'Camiseta Chelsea Visitante Fan Versi??n 2025/26','Camiseta del Chelsea Visitante usada en la temporada 2025/26',75.00,'2026-03-31','2026','Escudo y logo bordados'),(21,33,6,1,'Camiseta Al Nassr Fan Version Local 2022/23','Camiseta del Al Nassr de la temporada 2022/23',35.00,'2026-03-31','2023','Escudo y logos cosidos'),(22,14,6,1,'Camiseta Arsenal Fan Version 2022/23','Camiseta del Arsenal visitante utilizada en la temporada 2022/23',45.00,'2026-03-31','2023','Escudo y logos cosidos'),(23,2,6,1,'Camiseta Espa??a Local 2008','Camiseta de la seleccion Espa??ola utilizada en la Euro 2008',40.00,'2026-03-31','2008','Escudo y logos cosidos'),(24,1,6,1,'Camiseta Real Madrid Fan Version 2020/21','Camiseta del Real Madrid Alternativa utilizada en la temporada 2020/21',40.00,'2026-03-31','2021','Escudo termosellado y logo cosido'),(25,1,6,1,'Camiseta Real Madrid Local Player Version 2021/22','Camiseta del Real Madrid Local Version Jugador de la temporada 2021/22',45.00,'2026-03-31','2022','Escudo y logo termoselados'),(26,16,6,1,'Camiseta TRUE BOYS Portero 2023','Camiseta TRUE BOYS de Portero usada en la Copa KONAMI del a??o 2023',20.00,'2026-04-13','2023','Escudo pegado y logo cosido'),(27,6,6,1,'Camiseta Albacete Fan Version 2025/26','Camiseta del Albacete Balompie usada en la temporada 2025/26',60.00,'2026-04-14','2026','Escudo y logo bordados'),(28,35,6,1,'Camiseta PUMAS UNAM Fan Version 2024/25','Camiseta de los PUMAS del a??o 2025',45.00,'2026-04-14','2025','Logo bordado'),(29,18,6,1,'Camiseta Atletico de Madrid Fan Version 2016/17','Camiseta del Atl??tico de Madrid usada en la temporada 2016/17',30.00,'2026-04-15','2017','Escudo termosellado y logo bordados'),(32,42,6,1,'Camiseta Fan version Swansea City 2017/18','Camiseta utilizada en la temporada 2017/18 del Swansea City',34.00,'2026-04-15','2018','Escudo y logos bordados'),(34,47,6,2,'R??plica Camiseta Espa??a Visitante 2015','Camiseta de la Selecci??n de B??squet usada en el europeo de 2015',55.00,'2026-04-21','2015','Logo y publicidad termosellado');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_competiciones`
--

DROP TABLE IF EXISTS `productos_competiciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos_competiciones` (
  `ID_PRODUCTO` int NOT NULL,
  `ID_COMP` int NOT NULL,
  KEY `fk_p_comp_prod_rel` (`ID_PRODUCTO`),
  KEY `fk_p_comp_comp_rel` (`ID_COMP`),
  CONSTRAINT `fk_p_comp_comp` FOREIGN KEY (`ID_COMP`) REFERENCES `competiciones` (`ID_COMP`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_comp_comp_rel` FOREIGN KEY (`ID_COMP`) REFERENCES `competiciones` (`ID_COMP`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_comp_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_comp_prod_rel` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_competiciones`
--

LOCK TABLES `productos_competiciones` WRITE;
/*!40000 ALTER TABLE `productos_competiciones` DISABLE KEYS */;
INSERT INTO `productos_competiciones` VALUES (8,1),(8,4),(8,4),(9,1),(9,4),(9,4),(10,5),(10,16),(11,5),(12,1),(12,4),(12,11),(16,10),(19,1),(19,4),(19,4),(20,6),(22,6),(23,5),(23,16),(24,1),(24,4),(24,4),(25,1),(25,4),(25,4),(27,2),(8,4),(9,4),(19,4),(24,4),(25,4),(20,4),(28,14),(8,1),(9,1),(19,1),(24,1),(25,1),(22,6),(29,1),(29,1),(12,1),(12,4),(8,4),(9,4),(19,4),(24,4),(25,4),(12,4),(8,4),(9,4),(19,4),(24,4),(25,4);
/*!40000 ALTER TABLE `productos_competiciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_tallas`
--

DROP TABLE IF EXISTS `productos_tallas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos_tallas` (
  `ID_PRODUCTO` int NOT NULL,
  `ID_TALLA` int NOT NULL,
  `STOCK_ESPECIFICO` int DEFAULT '0',
  KEY `fk_prod_talla_talla` (`ID_TALLA`),
  KEY `fk_prod_talla_p_rel` (`ID_PRODUCTO`),
  CONSTRAINT `fk_prod_talla_p_rel` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prod_talla_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE,
  CONSTRAINT `fk_stock_talla` FOREIGN KEY (`ID_TALLA`) REFERENCES `tallas` (`ID_TALLA`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_tallas`
--

LOCK TABLES `productos_tallas` WRITE;
/*!40000 ALTER TABLE `productos_tallas` DISABLE KEYS */;
INSERT INTO `productos_tallas` VALUES (8,1,40),(8,2,40),(8,4,30),(8,5,25),(8,6,12),(8,7,40),(9,1,25),(9,2,25),(9,4,20),(9,5,14),(9,6,10),(9,7,19),(10,1,20),(10,2,20),(10,4,10),(10,5,9),(10,6,7),(10,7,17),(11,1,12),(11,2,12),(11,4,12),(11,5,9),(11,6,7),(11,7,13),(12,1,22),(12,2,16),(12,4,12),(12,5,22),(12,6,1),(12,7,4),(13,1,35),(13,2,34),(13,4,30),(13,5,24),(13,6,22),(13,7,33),(16,1,20),(16,2,20),(16,4,20),(16,5,15),(16,6,15),(16,7,21),(19,1,15),(19,2,20),(19,4,20),(19,5,12),(19,6,9),(19,7,25),(20,1,30),(20,2,30),(20,4,30),(20,5,21),(20,6,14),(20,7,26),(21,1,25),(21,2,25),(21,4,25),(21,5,20),(21,6,14),(21,7,30),(22,1,30),(22,2,25),(22,4,21),(22,5,14),(22,6,10),(22,7,18),(23,1,9),(23,2,8),(23,4,12),(23,5,7),(23,6,3),(23,7,10),(24,1,15),(24,2,15),(24,4,14),(24,5,20),(24,6,14),(24,7,21),(25,1,10),(25,2,15),(25,4,12),(25,5,13),(25,6,9),(25,7,18),(26,1,30),(26,2,25),(26,4,21),(26,5,14),(26,6,12),(26,7,22),(27,1,30),(27,2,30),(27,4,30),(27,5,30),(27,6,30),(27,7,30),(28,1,25),(28,2,26),(28,4,27),(28,5,13),(28,6,15),(28,7,30),(29,1,15),(29,2,15),(29,4,15),(29,5,9),(29,6,4),(29,7,15),(32,1,12),(32,2,12),(32,4,12),(32,5,7),(32,6,5),(32,7,14),(34,1,14),(34,2,14),(34,4,15),(34,5,12),(34,6,10),(34,7,9);
/*!40000 ALTER TABLE `productos_tallas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tallas`
--

DROP TABLE IF EXISTS `tallas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tallas` (
  `ID_TALLA` int NOT NULL AUTO_INCREMENT,
  `TALLA` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_TALLA`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tallas`
--

LOCK TABLES `tallas` WRITE;
/*!40000 ALTER TABLE `tallas` DISABLE KEYS */;
INSERT INTO `tallas` VALUES (1,'L'),(2,'M'),(4,'XS'),(5,'XL'),(6,'2XL'),(7,'S');
/*!40000 ALTER TABLE `tallas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporadas`
--

DROP TABLE IF EXISTS `temporadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temporadas` (
  `ID_COMP` int NOT NULL,
  `ID_EQUIPO` int NOT NULL,
  `ID_LOGO` int NOT NULL,
  `ANO_EDICION` int NOT NULL,
  `PARCHE_ESPECIAL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  KEY `fk_cp_logo` (`ID_LOGO`),
  KEY `fk_cp_equipo` (`ID_EQUIPO`),
  KEY `idx_respaldo_fk` (`ID_COMP`,`ID_EQUIPO`,`ID_LOGO`),
  CONSTRAINT `fk_cp_comp` FOREIGN KEY (`ID_COMP`) REFERENCES `competiciones` (`ID_COMP`) ON DELETE CASCADE,
  CONSTRAINT `fk_cp_equipo` FOREIGN KEY (`ID_EQUIPO`) REFERENCES `entidad_deportiva` (`ID_EQUIPO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cp_logo` FOREIGN KEY (`ID_LOGO`) REFERENCES `parches` (`ID_LOGO`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporadas`
--

LOCK TABLES `temporadas` WRITE;
/*!40000 ALTER TABLE `temporadas` DISABLE KEYS */;
INSERT INTO `temporadas` VALUES (1,1,26,2021,'assets/img/parches/1776214108_LaligaWin-old.png'),(1,1,1,2025,'assets/img/parches/1773960001_LaligaWin.png'),(1,12,1,2023,NULL),(1,12,1,2026,'assets/img/parches/1773960458_LaligaWin.png'),(1,18,26,2017,NULL),(1,21,1,2025,NULL),(2,6,2,2026,NULL),(4,13,4,2026,'assets/img/parches/1776790382_chelseaWin.png'),(5,2,5,2026,NULL),(5,3,5,2026,NULL),(5,4,5,2026,'assets/img/parches/1774293743_MundialWin.png'),(5,17,5,2026,NULL),(6,13,6,2026,'assets/img/parches/1776790349_chelseaWin2.png'),(6,14,6,2023,NULL),(6,24,6,2022,'assets/img/parches/1774291734_PremierWin.png'),(8,15,8,2025,NULL),(9,20,9,2025,NULL),(9,20,9,2026,'assets/img/parches/1774289558_bundelisgaWin.png'),(10,34,10,2022,'assets/img/parches/1774289630_erediviseWin.png'),(11,12,11,2023,NULL),(12,39,12,2024,NULL),(13,27,13,2025,'assets/img/parches/1774292371_Seria_aWin.png'),(13,29,13,2026,'assets/img/parches/1774290485_Seria_aWin.png'),(14,35,14,2025,NULL),(16,2,16,2024,'assets/img/parches/1774292676_NationsWIn24.png'),(16,5,16,2024,'assets/img/parches/1774292708_euro2020Win.png'),(4,1,4,2025,'assets/img/parches/1776787866_ucl15.png'),(4,12,4,2023,'assets/img/parches/1776787918_ucl5.png'),(4,1,30,2021,'assets/img/parches/1776787960_ucl13.png');
/*!40000 ALTER TABLE `temporadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `ID_USUARIO` int NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `APELLIDOS` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CORREO` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PASSWD` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ROL` enum('admin','cliente') COLLATE utf8mb4_general_ci DEFAULT 'cliente',
  `FECHA_REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IMAGEN_USER` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NOMBRE_USUARIO` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_USUARIO`),
  UNIQUE KEY `CORREO` (`CORREO`),
  UNIQUE KEY `NOMBRE_USUARIO` (`NOMBRE_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (4,'Ruben','Lopez-Reina Robledillo','lopezreinarobledilloruben@gmail.com','$2y$10$EZijgC8y8hhS8dwSh6W6TeSBJoDI5RkffE5qiySjxml2RrppgRBLy','admin','2026-03-13 19:36:29','assets/img/users/1776088027_mbappe.jpg','R0BL3'),(5,'Ruben','Mu??oz Maga??a','rl4845011@gmail.com','$2y$10$CWGyDC3zdLWieKzqwey3v.pV9cpj6CmgSuqSGj7/welLAyTj5Dr/2','cliente','2026-03-13 19:45:36','assets/img/users/1776088076_satriano.jpg','29Ruben'),(13,'Jose','Bordalas','robleruben22@gmail.com','$2y$10$RCqrJd2HuyNNP7EVxoLL/e2PC2HYyo2Ud22Pb4bX1DQn8F52JJ7We','cliente','2026-03-24 15:14:32','assets/img/users/1774367889_bordalas.jpg','Bordaneta69');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valoracion`
--

DROP TABLE IF EXISTS `valoracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `valoracion` (
  `ID_VALORACION` int NOT NULL AUTO_INCREMENT,
  `ID_USER` int DEFAULT NULL,
  `ID_PRODUCTO` int DEFAULT NULL,
  `PUNTUACION` int NOT NULL,
  `COMENTARIOS` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`ID_VALORACION`),
  KEY `fk_val_user` (`ID_USER`),
  KEY `fk_val_prod` (`ID_PRODUCTO`),
  CONSTRAINT `fk_val_prod` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE,
  CONSTRAINT `fk_val_user` FOREIGN KEY (`ID_USER`) REFERENCES `usuarios` (`ID_USUARIO`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valoracion`
--

LOCK TABLES `valoracion` WRITE;
/*!40000 ALTER TABLE `valoracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `valoracion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-22 14:52:56
