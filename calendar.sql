CREATE DATABASE  IF NOT EXISTS `calendar` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `calendar`;
-- MySQL dump 10.16  Distrib 10.1.30-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: calendar
-- ------------------------------------------------------
-- Server version	10.1.30-MariaDB-0ubuntu0.17.10.1

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
-- Table structure for table `event_participants`
--

DROP TABLE IF EXISTS `event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event` (`event`),
  KEY `user` (`user`),
  CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event`) REFERENCES `events` (`id`),
  CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_participants`
--

LOCK TABLES `event_participants` WRITE;
/*!40000 ALTER TABLE `event_participants` DISABLE KEYS */;
INSERT INTO `event_participants` VALUES (2,5,1),(3,5,1),(4,5,1),(5,5,2),(6,7,1),(7,7,2),(8,8,1),(9,8,2),(10,9,1),(11,9,2),(12,10,1),(13,11,1),(14,12,1),(15,13,2),(16,14,2),(17,15,1),(18,15,2),(19,15,3);
/*!40000 ALTER TABLE `event_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_create` datetime DEFAULT NULL,
  `event_last_update` datetime DEFAULT NULL,
  `event_owner` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_owner` (`event_owner`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`event_owner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (5,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 11:24:21','2018-05-16 22:32:33',1,1),(6,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 12:00:33','2018-05-16 22:32:33',1,1),(7,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 12:01:48','2018-05-16 22:32:33',1,1),(8,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 19:40:11','2018-05-16 22:32:33',1,1),(9,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 19:41:16','2018-05-16 22:32:33',1,1),(10,'Comprar novo computador','2018-05-16',NULL,'2018-05-16 19:41:51','2018-05-16 22:40:44',1,0),(11,'Ir ao dentista','2018-05-19',NULL,'2018-05-16 19:44:32','2018-05-16 22:32:33',1,1),(12,'Fazer tcc','2018-05-20',NULL,'2018-05-16 19:44:46','2018-05-16 22:40:18',1,0),(13,'Ir ao dentista','2018-05-19',NULL,'2018-05-16 21:07:44','2018-05-16 22:32:33',1,0),(14,'Ir ao dentista','2018-05-20',NULL,'2018-05-16 21:08:10','2018-05-16 22:32:33',1,0),(15,'Ir ao dentista','2018-05-16',NULL,'2018-05-16 22:28:39','2018-05-16 22:32:33',1,NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `lastname` text,
  `email` text,
  `password` text,
  `user_create_date` datetime DEFAULT NULL,
  `user_last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Rafael','Castro','rafael@usuario.com','123123','2018-05-15 09:12:22','0000-00-00 00:00:00'),(2,'Verônica','Castro','veronica@usuario.com','321321','2018-05-15 09:13:45',NULL),(3,'Max','Willian','max@usuarios.com','123456','2018-05-15 10:00:11',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-17  9:26:18
