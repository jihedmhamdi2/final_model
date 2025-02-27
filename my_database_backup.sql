-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: modeling_agency_db
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','$2y$10$zjGQ2JPzICcgM.lYYeGboOehvJCFXv3QHaJWdVJ88tKmvveX/M6ae');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_photos`
--

DROP TABLE IF EXISTS `album_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `album_photos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_id` int NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  CONSTRAINT `album_photos_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_photos`
--

LOCK TABLES `album_photos` WRITE;
/*!40000 ALTER TABLE `album_photos` DISABLE KEYS */;
INSERT INTO `album_photos` VALUES (1,6,'uploads/album_photos/1739133303_IMG_0003.JPG'),(2,6,'uploads/album_photos/1739133303_IMG_0004.JPG');
/*!40000 ALTER TABLE `album_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_images`
--

DROP TABLE IF EXISTS `model_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_id` int DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  CONSTRAINT `model_images_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_images`
--

LOCK TABLES `model_images` WRITE;
/*!40000 ALTER TABLE `model_images` DISABLE KEYS */;
INSERT INTO `model_images` VALUES (1,1,'uploads/album_images/67a6bcfb14d72.jpg','2025-02-08 02:10:03'),(2,1,'uploads/album_images/67a6bd29e3a75.jpg','2025-02-08 02:10:49'),(3,1,'uploads/album_images/67a71826dad93.jpg','2025-02-08 08:39:02'),(4,2,'uploads/album_images/67a719c61873e.jpg','2025-02-08 08:45:58');
/*!40000 ALTER TABLE `model_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `models` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `age` int DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `bio` text,
  `profile_image` varchar(255) DEFAULT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `models_ibfk_1` (`user_id`),
  CONSTRAINT `models_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `models`
--

LOCK TABLES `models` WRITE;
/*!40000 ALTER TABLE `models` DISABLE KEYS */;
INSERT INTO `models` VALUES (1,1,'baha','b',21,2.3,80,'test',NULL,'uploads/profile_images/67a6bcfb13aee.jpg'),(2,3,'teeest','new',24,1.8,NULL,'',NULL,'uploads/profile_images/67a719dfe0966.jpg'),(3,5,'jihed','mhamdi',24,175,NULL,'neew',NULL,'uploads/profile_images/67a9c1af4cbfb.png'),(4,6,'ines','nn',22,162,NULL,'tt',NULL,'uploads/profile_images/67a9f48bbf557.jpg'),(5,7,'tt','tt',22,163,NULL,'sa',NULL,'uploads/profile_pictures/1739131527_IMG_0001_1.JPG'),(6,8,'samar','samar',21,175,NULL,'sam',NULL,'uploads/profile_images/67aa030912996.png'),(7,9,'jihed','tt',23,175,NULL,'nn',NULL,'uploads/profile_images/67aa0d63bf762.png'),(8,10,'nade','nade',22,165,NULL,'ns',NULL,'uploads/profile_images/67aa0361be944.png'),(9,11,'toutou','toutou',21,167,NULL,'nn',NULL,'uploads/profile_images/67aa059262cbf.png'),(10,12,'foufou','foufou',26,165,NULL,'ff',NULL,'uploads/profile_images/67aa0af712bc1.png'),(11,14,'neww','ww',25,182,NULL,'ww',NULL,'uploads/profile_images/67aa0da59d44a.png'),(12,15,'yy','yyy',29,174,NULL,'yyy',NULL,'uploads/profile_images/67aa0eacbfb39.jpg'),(13,16,'ii','iiii',32,164,NULL,'iii',NULL,'uploads/profile_images/1739198654_t├®l├®charg├®.png'),(14,17,'amel','ss',23,175,NULL,'gggg',NULL,'uploads/profile_images/1739198847__MG_9233.JPG'),(15,18,'xx','xxxx',23,165,NULL,'xxx',NULL,'uploads/profile_images/1739199295__MG_9233.JPG');
/*!40000 ALTER TABLE `models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `societies`
--

DROP TABLE IF EXISTS `societies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `societies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `company_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `societies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `societies`
--

LOCK TABLES `societies` WRITE;
/*!40000 ALTER TABLE `societies` DISABLE KEYS */;
/*!40000 ALTER TABLE `societies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('model','society') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'baha_0069','bahagammoudi32@gmail.com','$2y$10$NWFdeYxTJ.eTyKrAM0hDt.FOnzK9XnxjbIogRFUHiF.H8xWeVpa8e','model','2025-02-07 23:41:28'),(2,'test','test@gmail.com','$2y$10$ula2IB5SDbiKtGwNdqU8N.D1eJZscuSBgHbSpQRQtjiAucmL6qqDq','society','2025-02-08 02:12:14'),(3,'test1','test1@gmail.com','$2y$10$zGC3UHsBNoPcx11DQyIy4uzpfB79psTlUt/RXt08QEikIiTvqphTK','model','2025-02-08 08:34:16'),(4,'newone','jihed.mhamdi2019@gmail.com','$2y$10$/pcXOVMsYk02tf5uP4CwW.iKKWdRRe0CbR4Mm1e6fh4VAxiVzjlye','model','2025-02-09 16:10:06'),(5,'jihed','jihed.mh@gmail.com','$2y$10$GzCGzMWfwVgajTia0lDacOvVf25w3UhdmHtSboRLFmDB2EBBW1QWi','model','2025-02-09 16:15:06'),(6,'ines','ines@gmail.com','$2y$10$v42syL6/Mddto4wlAgU1HusfuPOdhw06fiXb.OJg3ji3kAD3UAkEK','model','2025-02-09 19:01:49'),(7,'tt','tt@gmail.com','$2y$10$FgWtviPAX/iskZpXvnpUb.BkgCXDmAue04E3yeudXX82VdP81O9WG','model','2025-02-09 20:05:00'),(8,'samar','samar@gmail.com','$2y$10$GD11hRs6mEHog4hPRUnazu8Am5K1NsHLY5uvQHfkMX6s0aRANu7Ve','model','2025-02-09 20:34:30'),(9,'souhail','souhail@gmail.com','$2y$10$3y6Z2UVy8gKe/yP1MuzarO67a6GcSWsdCFPrjE5lvjjqhcXslje7y','model','2025-02-10 13:28:39'),(10,'nade','nade@gmail.com','$2y$10$wWGV.MikN9qKTTK3wcIG3OV4U0G73SbJuBSQYiW.c7DFvQjWKHFwa','model','2025-02-10 13:46:08'),(11,'toutou','toutou@gmail.com','$2y$10$MgrDo4vZhL/i3BR2EevTXeJNxRV4wJPB8Pxlz.BH4aUEipabrY.am','model','2025-02-10 13:55:58'),(12,'foufou','foufou@gmail.com','$2y$10$uHV.co5ZsvVcjTNbL.z0lu9Lc3oaoroioy0zxXB2lprlp22ePYyZ2','model','2025-02-10 14:18:57'),(13,'qq','qq@gmail.com','$2y$10$M02rYCRPb0Z.wS6UcQbj8eSChEJ4rB3TYKz3u6aXqyx8OyvUbYcya','model','2025-02-10 14:20:51'),(14,'neww','neww@gmail.com','$2y$10$Ym6YyTBFk.VNtPpEXhfSPOkymxkkUaj1o9SM.52R7OBLclELDBF4K','model','2025-02-10 14:30:24'),(15,'yy','yy@gmail.com','$2y$10$TC2hGQKTXOcfxpQRP5F6weuS.qeRAR/o/FCFlvGHAZTXxqc3CIAD2','model','2025-02-10 14:34:44'),(16,'ii','iii@gmail.com','$2y$10$Uo4fBP.lHxZLzBsGDRWI6usHpJe7Vpc2ebq2TxEGGV7UtAtRCG0l.','model','2025-02-10 14:43:46'),(17,'amel','amel@gmail.com','$2y$10$u2ayYydjfrJBXxYN0ugVp.Vv0J4Dnsesv8so3rm.SGhk6R0VnR2xy','model','2025-02-10 14:46:34'),(18,'xx','xx@gmail.com','$2y$10$A0SrkFTctDffZxaIjY7aFOtorqx23lnA5oQukCOjpPZyy9C/ZZhYG','model','2025-02-10 14:54:24');
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

-- Dump completed on 2025-02-11 23:51:06
