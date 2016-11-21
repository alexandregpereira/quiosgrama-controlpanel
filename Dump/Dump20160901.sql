-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: quiosgrama
-- ------------------------------------------------------
-- Server version	5.7.13-0ubuntu0.16.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `amount`
--

DROP TABLE IF EXISTS `amount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount` (
  `id` varchar(50) NOT NULL,
  `value` double NOT NULL,
  `paid_method` int(11) NOT NULL,
  `bill` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_amount_ref_bill_idx` (`bill`),
  CONSTRAINT `fk_amount_ref_bill` FOREIGN KEY (`bill`) REFERENCES `bill` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill` (
  `id` varchar(50) NOT NULL,
  `open_time` datetime DEFAULT NULL,
  `close_time` datetime DEFAULT NULL,
  `paid_time` datetime DEFAULT NULL,
  `waiter_open_table` int(11) DEFAULT NULL,
  `waiter_close_table` int(11) DEFAULT NULL,
  `bill_time` datetime NOT NULL,
  `table_number` int(11) NOT NULL,
  `service_paid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idWaiterCloseTable` (`waiter_close_table`),
  KEY `tableNumber` (`table_number`),
  KEY `fk_waiter_open_table_ref_functionary_idx` (`waiter_open_table`),
  CONSTRAINT `fk_table_number_ref_table_name` FOREIGN KEY (`table_number`) REFERENCES `table_name` (`number`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_waiter_close_table_ref_functionary` FOREIGN KEY (`waiter_close_table`) REFERENCES `functionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_waiter_open_table_ref_functionary` FOREIGN KEY (`waiter_open_table`) REFERENCES `functionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `temp_flag` int(11) NOT NULL,
  `present_flag` int(11) NOT NULL,
  `table` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `table` (`table`),
  CONSTRAINT `fk_client_ref_table_name` FOREIGN KEY (`table`) REFERENCES `table_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cofins`
--

DROP TABLE IF EXISTS `cofins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cofins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `cst` varchar(2) DEFAULT NULL,
  `v_bc` varchar(15) DEFAULT NULL,
  `p_cofins` varchar(5) DEFAULT NULL,
  `q_bc_prod` varchar(16) DEFAULT NULL,
  `v_aliq_prod` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cofins`
--

LOCK TABLES `cofins` WRITE;
/*!40000 ALTER TABLE `cofins` DISABLE KEYS */;
INSERT INTO `cofins` VALUES (1,'COFINSNT','08',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cofins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complement`
--

DROP TABLE IF EXISTS `complement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `price` double(10,2) NOT NULL,
  `drawable` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idComplement_UNIQUE` (`id`),
  UNIQUE KEY `description_UNIQUE` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complement`
--

LOCK TABLES `complement` WRITE;
/*!40000 ALTER TABLE `complement` DISABLE KEYS */;
INSERT INTO `complement` VALUES (127,'Uva',3.00,'ic_orange'),(128,'Maracujá',3.00,'ic_orange'),(129,'Abacaxi',3.00,'ic_orange'),(130,'Limão',3.00,'ic_lemon'),(131,'Coco',3.00,'ic_orange'),(132,'Kiwi',5.00,'ic_orange'),(133,'Morango',5.00,'ic_orange'),(134,'Copo c/ gelo e limão',0.00,'ic_glass_ice'),(135,'Copo c/ gelo',0.00,'ic_glass_ice'),(136,'Leite condesado',1.00,'ic_sugar'),(137,' ',0.00,NULL),(138,'s salada',0.00,NULL),(139,'2 molhos',0.00,NULL),(140,'ovo',0.00,NULL),(141,'Limão; Leite condesado',4.00,NULL);
/*!40000 ALTER TABLE `complement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complement_type`
--

DROP TABLE IF EXISTS `complement_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complement_type` (
  `product_type` int(11) NOT NULL,
  `complement` int(11) NOT NULL,
  PRIMARY KEY (`product_type`,`complement`),
  KEY `idProductType` (`product_type`),
  KEY `complement` (`complement`),
  CONSTRAINT `fk_complement_type_ref_complement` FOREIGN KEY (`complement`) REFERENCES `complement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_complement_type_ref_product_type` FOREIGN KEY (`product_type`) REFERENCES `product_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complement_type`
--

LOCK TABLES `complement_type` WRITE;
/*!40000 ALTER TABLE `complement_type` DISABLE KEYS */;
INSERT INTO `complement_type` VALUES (17,135),(18,134),(18,135),(19,127),(19,128),(19,129),(19,130),(19,131),(19,132),(19,133),(19,135),(19,136),(20,135),(20,136),(22,135),(22,136),(23,135);
/*!40000 ALTER TABLE `complement_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destination`
--

DROP TABLE IF EXISTS `destination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `icon_name` varchar(45) NOT NULL,
  `printer_ip` varchar(45) NOT NULL DEFAULT '192.168.0.3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destination`
--

LOCK TABLES `destination` WRITE;
/*!40000 ALTER TABLE `destination` DISABLE KEYS */;
INSERT INTO `destination` VALUES (1,'Cozinha','ic_prate','192.168.0.3'),(2,'Barman','ic_drink','192.168.0.3'),(3,'Sucos','ic_juice','192.168.0.3');
/*!40000 ALTER TABLE `destination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device`
--

DROP TABLE IF EXISTS `device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imei` varchar(50) NOT NULL,
  `registration_id` varchar(200) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `exclusion_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `imei_UNIQUE` (`imei`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `functionary`
--

DROP TABLE IF EXISTS `functionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `functionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `device` int(11) DEFAULT NULL,
  `admin_flag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `IMEI` (`device`),
  CONSTRAINT `fk_functionary_ref_device` FOREIGN KEY (`device`) REFERENCES `device` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icms`
--

DROP TABLE IF EXISTS `icms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `orig` varchar(1) NOT NULL,
  `cst` varchar(2) DEFAULT NULL,
  `p_icms` varchar(5) DEFAULT NULL,
  `csosn` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icms`
--

LOCK TABLES `icms` WRITE;
/*!40000 ALTER TABLE `icms` DISABLE KEYS */;
INSERT INTO `icms` VALUES (1,'ICMS00','0','00','10.00',NULL);
/*!40000 ALTER TABLE `icms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kiosk`
--

DROP TABLE IF EXISTS `kiosk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kiosk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET latin1 NOT NULL,
  `cnpj` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `address` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `expiration_time` datetime NOT NULL,
  `licence` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `valid_licence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `licence_UNIQUE` (`licence`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kiosk`
--

LOCK TABLES `kiosk` WRITE;
/*!40000 ALTER TABLE `kiosk` DISABLE KEYS */;
INSERT INTO `kiosk` VALUES (1,'Sagitárius',NULL,'teste','2017-06-20 00:00:00','575c57e0d1bd2',1);
/*!40000 ALTER TABLE `kiosk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Administra&ccedil;&atilde;o'),(3,'Card&aacute;pio');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pis`
--

DROP TABLE IF EXISTS `pis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `cst` varchar(2) DEFAULT NULL,
  `v_bc` varchar(15) DEFAULT NULL,
  `p_pis` varchar(5) DEFAULT NULL,
  `q_bc_prod` varchar(16) DEFAULT NULL,
  `v_aliq_prod` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pis`
--

LOCK TABLES `pis` WRITE;
/*!40000 ALTER TABLE `pis` DISABLE KEYS */;
INSERT INTO `pis` VALUES (1,'PISNT','08',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `pis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poi`
--

DROP TABLE IF EXISTS `poi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `x_pos_in_dpi` int(11) NOT NULL,
  `y_pos_in_dpi` int(11) NOT NULL,
  `image` varchar(45) NOT NULL,
  `map_page_number` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `functionary` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idFunctionary` (`functionary`),
  CONSTRAINT `fk_poi_ref_functionary` FOREIGN KEY (`functionary`) REFERENCES `functionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poi`
--

LOCK TABLES `poi` WRITE;
/*!40000 ALTER TABLE `poi` DISABLE KEYS */;
/*!40000 ALTER TABLE `poi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `description` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `product_type` int(11) NOT NULL,
  `popularity` int(11) DEFAULT '0',
  `tax` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `idProductType` (`product_type`),
  KEY `fk_product_ref_tax_idx` (`tax`),
  CONSTRAINT `fk_product_ref_product_type` FOREIGN KEY (`product_type`) REFERENCES `product_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (78,54,'Cação',NULL,55.00,24,10,1),(79,55,'Porquinho',NULL,55.00,24,1,1),(80,56,'Espada',NULL,50.00,24,1,1),(81,306,'Abadejo',NULL,75.00,24,1,1),(82,138,'Baiaco',NULL,70.00,24,1,1),(83,301,'Pescada - filé',NULL,70.00,24,1,1),(84,313,'Tilápia',NULL,65.00,24,1,1),(85,50,'Camarão empanado',NULL,55.00,24,9,1),(86,51,'Camarão branco - médio',NULL,65.00,24,1,1),(87,52,'Camarão a paulista','Alho e óleo - médio',75.00,24,1,1),(88,57,'Lula à dorê',NULL,65.00,24,1,1),(89,228,'Lula provençal',NULL,75.00,24,1,1),(90,58,'Fritas',NULL,30.00,24,1,1),(91,308,'Fritas c/ queijo',NULL,35.00,24,1,1),(92,59,'Mandioca',NULL,30.00,24,1,1),(93,311,'Mandioca c/ queijo',NULL,35.00,24,1,1),(94,229,'Frango a passarinho',NULL,40.00,24,1,1),(95,48,'Calabresa','Acebolada c/ pão fatiado',40.00,24,1,1),(96,49,'MIni Kibe (12 un.)',NULL,27.00,24,1,1),(97,149,'Bolinho de mandioca c/ carne seca (12 un.)',NULL,27.00,24,1,1),(98,150,'Bolinho de bacalhau','12 un. c/ puro bacalhau',40.00,24,1,1),(99,307,'Torresmo a pururuca',NULL,30.00,24,1,1),(100,302,'Cebola empanada',NULL,35.00,24,1,1),(101,160,'Salada','Alface, tomate, palmito e cebola',40.00,24,1,1),(102,240,'Frios','Salame - provolone - azeitonas',30.00,24,1,1),(103,60,'Casquinha de siri (Unidade)',NULL,20.00,24,1,1),(104,16,'Carne',NULL,9.00,25,1,1),(105,17,'Queijo',NULL,9.00,25,1,1),(106,18,'Palmito',NULL,9.00,25,1,1),(107,19,'Carne c/ queijo',NULL,9.00,25,1,1),(108,45,'Pizza',NULL,9.00,25,1,1),(109,46,'Frango c/ catupiry',NULL,9.00,25,1,1),(110,184,'Camarão',NULL,12.00,25,1,1),(111,350,'Misto',NULL,12.00,25,1,1),(112,24,'Hambúrguer salada','hambúrguer, presunto, maionese, batata palha, alface e tomate',11.00,27,1,1),(113,25,'X Salada','hambúrguer, queijo, presunto, maionese, batata palha, alface e tomate',13.00,27,1,1),(114,26,'X Burguer','hambúrguer, queijo, presunto, maionese, batata palha',11.00,27,1,1),(115,27,'Hambúrguer','hambúrguer, queijo, presunto, maionese, batata palha',10.00,27,10,1),(116,28,'Bauru','presunto, queijo e tomate',11.00,27,1,1),(117,29,'Misto quente','presunto e queijo',11.00,27,1,1),(118,30,'Misto frio','presunto e queijo frios',11.00,27,1,1),(119,31,'Queijo quente','queijo derretido e pão',11.00,27,1,1),(120,32,'Bauru Salada','presunto, queijo, alface e tomate',13.00,27,1,1),(121,33,'Queijo quente salada','queijo derretido, alface e tomate',11.00,27,1,1),(122,230,'Calabresa c/ queijo',NULL,13.00,27,1,1),(123,10,'Coxinha',NULL,6.00,26,1,1),(124,12,'Kibe',NULL,6.00,26,1,1),(125,159,'Açaí na tigela c/ banana',NULL,22.00,28,1,1),(126,158,'Açaí na tigela c/ morango',NULL,24.00,28,1,1),(127,157,'Açaí no copo',NULL,13.00,28,1,1),(128,35,'Morango',NULL,9.00,20,1,1),(129,36,'Acerola',NULL,9.00,20,1,1),(130,37,'Maracujá',NULL,9.00,20,1,1),(131,38,'Caju',NULL,9.00,20,1,1),(132,39,'Laranja',NULL,9.00,20,1,1),(133,40,'Abacaxi',NULL,9.00,20,1,1),(134,41,'Limão',NULL,9.00,20,1,1),(135,252,'Abacaxi c/ hortelã',NULL,9.00,20,1,1),(136,253,'Laranja c/ acerola',NULL,9.00,20,1,1),(137,10000,'Coca-Cola',NULL,5.00,18,1,1),(138,10001,'Coca-Cola zero',NULL,5.00,18,1,1),(139,10002,'Guaraná',NULL,5.00,18,1,1),(140,10003,'Tônica',NULL,5.00,18,1,1),(141,10004,'Fanta',NULL,5.00,18,1,1),(142,10005,'Citrus',NULL,5.00,18,1,1),(143,10006,'Soda',NULL,5.00,18,1,1),(144,5,'Água',NULL,3.50,18,1,1),(145,21,'Água c/ gás',NULL,4.00,18,1,1),(146,261,'Suco Del Valle',NULL,6.00,18,1,1),(147,4,'Água de coco',NULL,6.00,18,1,1),(148,20000,'Brahma',NULL,5.00,17,1,1),(149,20001,'Skol',NULL,5.00,17,1,1),(150,20002,'Itaipava',NULL,5.00,17,1,1),(151,70000,'Antarctica',NULL,4.00,17,1,1),(152,30000,'Budweiser',NULL,6.00,17,1,1),(153,30001,'Cerveja s/ álcool',NULL,6.00,17,1,1),(154,30002,'Bohêmia - Malzbier',NULL,6.00,17,1,1),(155,225,'Stella Long Neck',NULL,7.00,17,1,1),(156,92,'Caipirinha de pinga',NULL,14.00,19,1,1),(157,127,'Caipirinha de vodka',NULL,16.00,19,1,1),(158,185,'Caipirinha de saquê',NULL,16.00,19,1,1),(159,197,'Caipirinha de sagatiba',NULL,16.00,19,1,1),(160,96,'Caipirinha de ypioca',NULL,15.00,19,1,1),(161,86,'Caipirinha de rum',NULL,16.00,19,1,1),(162,63,'Rabo de Galo',NULL,6.00,23,1,1),(163,64,'Cuba Libre',NULL,12.00,23,1,1),(164,67,'Campari',NULL,12.00,23,1,1),(165,68,'Cinzano',NULL,6.00,23,1,1),(166,69,'Martini',NULL,7.00,23,1,1),(167,70,'São Francisco',NULL,6.00,23,1,1),(168,71,'Ypioca',NULL,6.00,23,1,1),(169,72,'Conhaque',NULL,6.00,23,1,1),(170,77,'Pinga 51',NULL,3.00,23,1,1),(171,78,'Cynar',NULL,7.00,23,1,1),(172,79,'Esprimida',NULL,4.00,23,1,1),(173,132,'Sminorff',NULL,9.00,23,1,1),(174,209,'Sagatiba',NULL,9.00,23,1,1),(175,224,'Gim',NULL,7.00,23,1,1),(176,65,'Natu',NULL,15.00,21,1,1),(177,214,'Red Label',NULL,20.00,21,1,1),(178,95,'Espanhola c/ abacaxi',NULL,20.00,22,1,1),(179,94,'Espanhola c/ morango',NULL,22.00,22,1,1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_request`
--

DROP TABLE IF EXISTS `product_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request` varchar(50) NOT NULL,
  `product` int(11) NOT NULL,
  `complement` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `valid` int(11) NOT NULL DEFAULT '1',
  `transfer_route` varchar(50) DEFAULT NULL,
  `product_request_time` datetime NOT NULL DEFAULT '2016-04-14 21:53:59',
  `status` int(11) NOT NULL DEFAULT '0',
  `printing` int(11) NOT NULL DEFAULT '0',
  `print_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idRequest` (`request`),
  KEY `idProduct` (`product`),
  KEY `description` (`complement`),
  CONSTRAINT `fk_product_request_ref_complement` FOREIGN KEY (`complement`) REFERENCES `complement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_request_ref_product` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_request_ref_request` FOREIGN KEY (`request`) REFERENCES `request` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=574 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `priority` int(11) NOT NULL,
  `tab_image` varchar(30) CHARACTER SET latin1 NOT NULL,
  `button_image` varchar(30) CHARACTER SET latin1 NOT NULL,
  `icon_image` varchar(30) CHARACTER SET latin1 NOT NULL,
  `color` varchar(30) CHARACTER SET latin1 NOT NULL,
  `destination` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idDestination` (`destination`),
  CONSTRAINT `fk_type_ref_destination` FOREIGN KEY (`destination`) REFERENCES `destination` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_type`
--

LOCK TABLES `product_type` WRITE;
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
INSERT INTO `product_type` VALUES (17,'Cervejas',1,'tab_beer','ic_beer','ic_info_beer','#ffb700',2),(18,'Refrigerantes',2,'tab_soda','ic_soda','ic_info_soda','#0028ff',2),(19,'Caipirinhas',3,'tab_drink','ic_drink','ic_info_drink','#00b53d',2),(20,'Sucos',4,'tab_juice','ic_juice','ic_info_juice','#8e00ff',3),(21,'Whisky',5,'tab_drink','ic_drink','ic_info_drink','#cc6100',2),(22,'Drinks',6,'tab_drink','ic_drink','ic_info_drink','#0cad73',2),(23,'Bebidas Quentes',7,'tab_drink','ic_drink','ic_info_drink','#000000',2),(24,'Porções',2,'tab_portion','ic_portion','ic_info_portion','#ea1212',1),(25,'Pastéis',3,'tab_salty','ic_salty','ic_info_salty','#e0e500',1),(26,'Salgados',4,'tab_salty','ic_salty','ic_info_salty','#ff7a00',1),(27,'Lanches',1,'tab_snack','ic_snack','ic_info_snack','#d86511',1),(28,'Açaí',6,'tab_soda','ic_soda','ic_info_soda','#3d2060',2);
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request` (
  `id` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `functionary` int(11) NOT NULL,
  `bill` varchar(50) NOT NULL,
  `new` int(11) NOT NULL DEFAULT '0',
  `valid` int(11) NOT NULL DEFAULT '1',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idFunctionary` (`functionary`),
  KEY `fk_request_ref_bill_idx` (`bill`),
  CONSTRAINT `fk_request_ref_bill` FOREIGN KEY (`bill`) REFERENCES `bill` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_request_ref_functionary` FOREIGN KEY (`functionary`) REFERENCES `functionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `screen`
--

DROP TABLE IF EXISTS `screen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `screen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  `url` varchar(150) NOT NULL,
  `list_on_the_screen` int(11) NOT NULL DEFAULT '0',
  `need_administrator_permission` int(11) NOT NULL DEFAULT '0',
  `module` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_screen_module_idx` (`module`),
  CONSTRAINT `fk_screen_ref_module` FOREIGN KEY (`module`) REFERENCES `module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `screen`
--

LOCK TABLES `screen` WRITE;
/*!40000 ALTER TABLE `screen` DISABLE KEYS */;
INSERT INTO `screen` VALUES (1,'Cadastro de M&oacute;dulo','/cadastro-de-modulo',0,1,1),(2,'M&oacute;dulos','/listagem-de-modulos',1,1,1),(3,'Cadastro de Tela','/cadastro-de-tela',0,1,1),(4,'Telas','/listagem-de-telas',1,1,1),(5,'Cadastro de Permiss&atilde;o','/cadastro-de-permissao',0,1,1),(6,'Permiss&otilde;es','/listagem-de-permissoes',1,1,1),(7,'Edi&ccedil;&atilde;o de M&oacute;dulo','/edicao-de-modulo',0,1,1),(8,'Edi&ccedil;&atilde;o de Permiss&atilde;o','/edicao-de-permissao',0,1,1),(9,'Cadastro de Usu&aacute;rio','/cadastro-de-usuario',0,1,1),(10,'Usu&aacute;rios','/listagem-de-usuarios',1,1,1),(11,'Edi&ccedil;&atilde;o de Usu&aacute;rio','/edicao-de-usuario',0,1,1),(12,'Cadastro de Funcion&aacute;rio','/cadastro-de-funcionario',0,1,1),(13,'Funcion&aacute;rios','/listagem-de-funcionarios',1,1,1),(14,'Cadastro de Dispositivo','/cadastro-de-dispositivo',0,1,1),(15,'Dispositivos','/listagem-de-dispositivos',1,1,1),(16,'Edi&ccedil;&atilde;o de Dispositivo','/edicao-de-dispositivo',0,1,1),(17,'Edi&ccedil;&atilde;o de Funcion&aacute;rio','/edicao-de-funcionario',0,1,1),(18,'Cadastro de Tipo de Produto','/cadastro-de-tipo-de-produto',0,0,3),(19,'Tipos de Produto','/listagem-de-tipos-de-produto',1,0,3),(20,'Cadastro de Produto','/cadastro-de-produto',0,0,3),(21,'Produtos','/listagem-de-produtos',1,0,3),(22,'Edi&ccedil;&atilde;o de Tipo de Produto','/edicao-de-tipo-de-produto',0,0,3),(23,'Cadastro de Complemento','/cadastro-de-complemento',0,0,3),(24,'Complementos','/listagem-de-complementos',1,0,3),(25,'Edi&ccedil;&atilde;o de Complemento','/edicao-de-complemento',0,0,3),(26,'Edi&ccedil;&atilde;o de Tela','/edicao-de-tela',0,1,1),(27,'Edi&ccedil;&atilde;o de Produto','/edicao-de-produto',0,0,3),(28,'Cadastro de Poi','/cadastro-de-poi',0,1,3),(29,'Pois','/listagem-de-pois',1,1,3),(30,'Edi&ccedil;&atilde;o de Poi','/edicao-de-poi',0,1,3),(31,'Cadastro de Cliente','/cadastro-de-cliente',0,1,1),(32,'Clientes','/listagem-de-clientes',1,1,1),(33,'Edi&ccedil;&atilde;o de Cliente','/edicao-de-cliente',0,1,1),(34,'Cadastro de Mesa','/cadastro-de-mesa',0,1,1),(35,'Mesas','/listagem-de-mesas',1,1,1),(36,'Edi&ccedil;&atilde;o de Mesa','/edicao-de-mesa',0,1,1),(38,'Dashboard','/dashboard',0,0,1);
/*!40000 ALTER TABLE `screen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_name`
--

DROP TABLE IF EXISTS `table_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `x_pos_in_dpi` int(11) NOT NULL,
  `y_pos_in_dpi` int(11) NOT NULL,
  `map_page_number` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `functionary` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `client_temp` varchar(50) DEFAULT NULL,
  `show` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `number_UNIQUE` (`number`),
  KEY `idFunctionary` (`functionary`),
  KEY `fk_table_name_ref__idx` (`client`),
  CONSTRAINT `fk_table_name_ref_client` FOREIGN KEY (`client`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table_name_ref_functionary` FOREIGN KEY (`functionary`) REFERENCES `functionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_name`
--

LOCK TABLES `table_name` WRITE;
/*!40000 ALTER TABLE `table_name` DISABLE KEYS */;
INSERT INTO `table_name` VALUES (86,30,48,327,1,'2016-08-25 19:08:00',NULL,NULL,NULL,1),(87,1,48,274,1,'2016-08-26 21:23:22',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `table_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax`
--

DROP TABLE IF EXISTS `tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ncm` int(11) NOT NULL,
  `v_item12741` varchar(15) NOT NULL,
  `icms` int(11) NOT NULL,
  `pis` int(11) NOT NULL,
  `cofins` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tax_ref_icms_idx` (`icms`),
  KEY `fk_tax_ref_pis_idx` (`pis`),
  KEY `fk_tax_ref_cofins_idx` (`cofins`),
  CONSTRAINT `fk_tax_ref_cofins` FOREIGN KEY (`cofins`) REFERENCES `cofins` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_ref_icms` FOREIGN KEY (`icms`) REFERENCES `icms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_ref_pis` FOREIGN KEY (`pis`) REFERENCES `pis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax`
--

LOCK TABLES `tax` WRITE;
/*!40000 ALTER TABLE `tax` DISABLE KEYS */;
INSERT INTO `tax` VALUES (1,47061000,'1.00',1,1,1);
/*!40000 ALTER TABLE `tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `name` varchar(250) NOT NULL,
  `register_date` date NOT NULL,
  `administrator` int(1) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'quiosgrama','8d2c60f7bedebbd93f32d0b87ff8758776415861aff60f2abb6d2899dc4c2a0b1abc49139243af4a4916a2fd4c6faf879ac490633b720c5e9e19d6ab71afe868','Quiosgrama','2015-08-03',1,'quiosgrama@email.com');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permission`
--

DROP TABLE IF EXISTS `user_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `module` int(11) DEFAULT NULL,
  `screen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_user_permissions_user_idx` (`user`),
  KEY `fk_user_permissions_module_idx` (`module`),
  KEY `fk_user_permissions_screen_idx` (`screen`),
  CONSTRAINT `fk_user_permission_ref_module` FOREIGN KEY (`module`) REFERENCES `module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_permission_ref_screen` FOREIGN KEY (`screen`) REFERENCES `screen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_permission_ref_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (7,4,1,NULL),(8,4,3,NULL);
/*!40000 ALTER TABLE `user_permission` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-01 17:09:40
