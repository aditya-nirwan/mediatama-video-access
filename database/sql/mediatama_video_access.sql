-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: mediatama_video_access
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `access_requests`
--

DROP TABLE IF EXISTS `access_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `video_id` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `responded_by` bigint unsigned DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `access_requests_video_id_foreign` (`video_id`),
  KEY `access_requests_responded_by_foreign` (`responded_by`),
  KEY `access_requests_user_id_video_id_status_index` (`user_id`,`video_id`,`status`),
  CONSTRAINT `access_requests_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `access_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `access_requests_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_requests`
--

LOCK TABLES `access_requests` WRITE;
/*!40000 ALTER TABLE `access_requests` DISABLE KEYS */;
INSERT INTO `access_requests` VALUES (1,2,1,'Saya perlu mempelajari rumus dasar untuk pekerjaan.','approved',1,'2026-11-19 08:11:09','Disetujui untuk 3 hari.','2026-11-20 08:11:09','2026-11-20 08:11:09'),(2,3,2,'Mohon akses untuk persiapan pelatihan minggu depan.','pending',NULL,NULL,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09'),(3,4,1,'Ingin mengulang materi Excel.','approved',1,'2026-11-15 08:11:09','Disetujui untuk 4 hari.','2026-11-20 08:11:09','2026-11-20 08:11:09'),(4,5,3,'Penasaran isi videonya.','rejected',1,'2026-11-20 02:11:09','Materi ini hanya untuk peserta yang sudah terdaftar.','2026-11-20 08:11:09','2026-11-20 08:11:09');
/*!40000 ALTER TABLE `access_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_19_220718_create_videos_table',1),(5,'2026_11_19_220726_create_access_requests_table',1),(6,'2026_11_19_220732_create_video_accesses_table',1),(7,'2026_11_19_220736_create_watch_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@mediatama.test',NULL,'$2y$12$J9rBubFHK5YzD.Ao3QZEhOfNffB0mQnQLUu8HCiMj8ZuxKG.nq3Fq','admin','081200000001','Salatiga, Jawa Tengah',1,NULL,'2026-11-20 08:11:08','2026-11-20 08:11:08',NULL),(2,'Aditya Nirwan','adit@customer.test',NULL,'$2y$12$HxVDp.vMP6lwxZeLOcN2Y.PIf.BNygbwWdWLOzetJU1.YUKaT8Kza','customer','081200000002','Salatiga, Jawa Tengah',1,NULL,'2026-11-20 08:11:08','2026-11-20 08:11:08',NULL),(3,'Sri Handayani','sri@customer.test',NULL,'$2y$12$pos5Mebuhr.3f8qCg6beTeKLYL/j/aoKGtOBrh8jbe4lQloBan3lW','customer','081200000003','Salatiga, Jawa Tengah',1,NULL,'2026-11-20 08:11:08','2026-11-20 08:11:08',NULL),(4,'Irfan Faqih','irfan@customer.test',NULL,'$2y$12$RvY4JeqNIvvKwWA2XLc.A.1ETUaKNoUsraizYQFfSubRt2Zu1VUqq','customer','081200000004','Salatiga, Jawa Tengah',1,NULL,'2026-11-20 08:11:08','2026-11-20 08:11:08',NULL),(5,'Baskoro Sulaksono','baskoro@customer.test',NULL,'$2y$12$l8/Wb/EBegJ7fESfxfssieqPDtMYenjFm3GBO4jHkQGdnD68HQ/Ue','customer','081200000005','Salatiga, Jawa Tengah',1,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_accesses`
--

DROP TABLE IF EXISTS `video_accesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `video_accesses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `video_id` bigint unsigned NOT NULL,
  `access_request_id` bigint unsigned DEFAULT NULL,
  `granted_by` bigint unsigned NOT NULL,
  `starts_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `revoked_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `video_accesses_video_id_foreign` (`video_id`),
  KEY `video_accesses_access_request_id_foreign` (`access_request_id`),
  KEY `video_accesses_granted_by_foreign` (`granted_by`),
  KEY `video_accesses_revoked_by_foreign` (`revoked_by`),
  KEY `video_accesses_user_id_video_id_expires_at_index` (`user_id`,`video_id`,`expires_at`),
  CONSTRAINT `video_accesses_access_request_id_foreign` FOREIGN KEY (`access_request_id`) REFERENCES `access_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `video_accesses_granted_by_foreign` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `video_accesses_revoked_by_foreign` FOREIGN KEY (`revoked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `video_accesses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `video_accesses_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_accesses`
--

LOCK TABLES `video_accesses` WRITE;
/*!40000 ALTER TABLE `video_accesses` DISABLE KEYS */;
INSERT INTO `video_accesses` VALUES (1,2,1,1,1,'2026-11-19 08:11:09','2026-11-22 08:11:09',NULL,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09'),(2,2,3,NULL,1,'2026-11-20 06:11:09','2026-11-25 08:11:09',NULL,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09'),(3,3,3,NULL,1,'2026-11-20 07:21:09','2026-11-20 08:21:09',NULL,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09'),(4,4,1,3,1,'2026-11-15 08:11:09','2026-11-19 08:11:09',NULL,NULL,'2026-11-20 08:11:09','2026-11-20 08:11:09');
/*!40000 ALTER TABLE `video_accesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_bytes` bigint unsigned NOT NULL,
  `duration_seconds` int unsigned DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint unsigned NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_slug_unique` (`slug`),
  KEY `videos_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `videos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'Pengenalan Microsoft Excel','pengenalan-microsoft-excel','Mengenal antarmuka Excel dan rumus dasar.','videos/sample-1.mp4','sample-1.mp4','video/mp4',153305,30,'thumbnails/sample-1.png',1,1,'2026-11-20 08:11:09','2026-11-20 08:11:09',NULL),(2,'Dasar Microsoft Word','dasar-microsoft-word','Pengaturan dokumen, format teks, dan pencetakan.','videos/sample-2.mp4','sample-2.mp4','video/mp4',230218,45,'thumbnails/sample-2.png',1,1,'2026-11-20 08:11:09','2026-11-20 08:11:09',NULL),(3,'Teknik Presentasi PowerPoint','teknik-presentasi-powerpoint','Menyusun slide yang ringkas dan mudah dibaca.','videos/sample-3.mp4','sample-3.mp4','video/mp4',304403,60,'thumbnails/sample-3.png',1,1,'2026-11-20 08:11:09','2026-11-20 08:11:09',NULL);
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `watch_logs`
--

DROP TABLE IF EXISTS `watch_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `watch_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `video_access_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `video_id` bigint unsigned NOT NULL,
  `last_position_seconds` int unsigned NOT NULL DEFAULT '0',
  `watch_count` int unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_watched_at` timestamp NULL DEFAULT NULL,
  `last_watched_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `watch_logs_video_access_id_unique` (`video_access_id`),
  KEY `watch_logs_user_id_foreign` (`user_id`),
  KEY `watch_logs_video_id_foreign` (`video_id`),
  CONSTRAINT `watch_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `watch_logs_video_access_id_foreign` FOREIGN KEY (`video_access_id`) REFERENCES `video_accesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `watch_logs_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watch_logs`
--

LOCK TABLES `watch_logs` WRITE;
/*!40000 ALTER TABLE `watch_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `watch_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-20 15:11:13
