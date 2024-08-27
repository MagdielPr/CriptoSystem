CREATE DATABASE  IF NOT EXISTS `criptomoedas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `criptomoedas`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: criptomoedas
-- ------------------------------------------------------
-- Server version	8.0.37

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
-- Table structure for table `carteiras`
--

DROP TABLE IF EXISTS `carteiras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carteiras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `criptomoeda_id` int DEFAULT NULL,
  `quantidade` decimal(18,8) DEFAULT '0.00000000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`criptomoeda_id`),
  KEY `fk_carteiras_criptomoeda` (`criptomoeda_id`),
  CONSTRAINT `carteiras_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `carteiras_ibfk_2` FOREIGN KEY (`criptomoeda_id`) REFERENCES `criptomoedas` (`id`),
  CONSTRAINT `fk_carteiras_criptomoeda` FOREIGN KEY (`criptomoeda_id`) REFERENCES `criptomoedas` (`id`),
  CONSTRAINT `fk_carteiras_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carteiras`
--

LOCK TABLES `carteiras` WRITE;
/*!40000 ALTER TABLE `carteiras` DISABLE KEYS */;
INSERT INTO `carteiras` VALUES (1,9,1,2.00000000),(2,9,4,0.00000004),(3,9,2,0.00000000),(5,9,3,0.00000000),(6,11,1,0.00000000),(7,11,4,450.00000000),(8,11,2,45.00000000),(10,11,3,0.00000000),(11,16,1,0.00000000),(12,16,4,0.00000000),(13,16,2,0.00000000),(15,16,3,0.00000000),(16,17,1,0.00000000),(17,17,4,0.00000000),(18,17,2,0.00000000),(20,17,3,0.00000000),(21,14,1,0.00000000),(22,14,4,0.00000000),(23,14,2,0.00000000),(25,14,3,0.00000000),(26,12,1,28.00000000),(27,12,4,0.00000000),(28,12,2,0.00000000),(30,12,3,0.00000000),(31,13,1,0.00000000),(32,13,4,0.00000000),(33,13,2,0.00000000),(35,13,3,0.00000000),(36,10,1,0.00000000),(37,10,4,0.00000000),(38,10,2,0.00000000),(40,10,3,0.00000000),(41,15,1,425.00000000),(42,15,4,0.00000000),(43,15,2,0.00000000),(45,15,3,0.00000000),(322,9,7,0.45000000),(328,11,7,0.00000000),(334,16,7,0.00000000),(340,17,7,0.00000000),(346,14,7,0.00000000),(352,12,7,0.00000000),(358,13,7,0.00000000),(364,10,7,0.00000000),(370,15,7,0.00000000),(474,19,1,0.00000000),(475,19,4,0.00000000),(476,19,2,0.00000000),(478,19,7,0.00000000),(479,19,3,0.00000000);
/*!40000 ALTER TABLE `carteiras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `criptomoedas`
--

DROP TABLE IF EXISTS `criptomoedas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `criptomoedas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `sigla` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criptomoedas`
--

LOCK TABLES `criptomoedas` WRITE;
/*!40000 ALTER TABLE `criptomoedas` DISABLE KEYS */;
INSERT INTO `criptomoedas` VALUES (1,'Bitcoin','BTC'),(2,'Ethereum','ETH'),(3,'Solana','SOL'),(4,'Cardano','ADA'),(7,'Polkadot','DOT');
/*!40000 ALTER TABLE `criptomoedas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacoes`
--

DROP TABLE IF EXISTS `transacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `criptomoeda_id` int DEFAULT NULL,
  `quantidade` decimal(18,8) NOT NULL,
  `data` date NOT NULL,
  `de_usuario_id` int DEFAULT NULL,
  `para_usuario_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transacoes_usuario` (`usuario_id`),
  KEY `fk_transacoes_criptomoeda` (`criptomoeda_id`),
  KEY `fk_transacoes_de_usuario` (`de_usuario_id`),
  KEY `fk_transacoes_para_usuario` (`para_usuario_id`),
  CONSTRAINT `fk_transacoes_criptomoeda` FOREIGN KEY (`criptomoeda_id`) REFERENCES `criptomoedas` (`id`),
  CONSTRAINT `fk_transacoes_de_usuario` FOREIGN KEY (`de_usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_transacoes_para_usuario` FOREIGN KEY (`para_usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_transacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`criptomoeda_id`) REFERENCES `criptomoedas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacoes`
--

LOCK TABLES `transacoes` WRITE;
/*!40000 ALTER TABLE `transacoes` DISABLE KEYS */;
INSERT INTO `transacoes` VALUES (1,9,1,1.00000000,'2024-08-26',9,12),(2,9,1,2.00000000,'2024-08-26',9,12),(3,15,1,25.00000000,'2024-08-26',15,12);
/*!40000 ALTER TABLE `transacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (9,'asada','kasa@gmail.com'),(10,'sasssssssssssssss','support@amonmarket.com'),(11,'asadaaaaaaaaaaaaaaaaa','kasa@gmail.com'),(12,'Magdiel','ambumpr@gmail.com'),(13,'Magdiel','magdielprestes@gmail.com'),(14,'carlosssss','wesley@gmail.com'),(15,'wesley teste','teste@gmail.com'),(16,'CADASTRO','CADASTRAO@gmail.com'),(17,'CADASTRO','CADASTRAO@gmail.comm'),(19,'Teste2w333333333333333333333','miguel@gmial.com');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-26 23:38:49
