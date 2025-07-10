-- MySQL dump 10.13  Distrib 8.0.33, for macos12.6 (x86_64)
--
-- Host: localhost    Database: sql_db_arenafight
-- ------------------------------------------------------
-- Server version	8.0.33

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
-- Table structure for table `character_items`
--

DROP TABLE IF EXISTS `character_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `character_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `character_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `level` tinyint unsigned NOT NULL DEFAULT '1',
  `bonuses` json DEFAULT NULL,
  `rarity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'common',
  `current_durability` tinyint unsigned NOT NULL DEFAULT '10',
  `max_durability` tinyint unsigned NOT NULL DEFAULT '10',
  `is_broken` tinyint(1) NOT NULL DEFAULT '0',
  `slot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_price` int unsigned DEFAULT NULL,
  `location` enum('inventory','equipped','shop') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'shop',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `character_items_character_id_foreign` (`character_id`),
  KEY `character_items_item_id_foreign` (`item_id`),
  CONSTRAINT `character_items_character_id_foreign` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `character_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `character_items`
--

LOCK TABLES `character_items` WRITE;
/*!40000 ALTER TABLE `character_items` DISABLE KEYS */;
INSERT INTO `character_items` VALUES (1,1,1,1,NULL,'common',22,22,0,'weapon',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(2,1,2,1,NULL,'common',22,22,0,'weapon',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(3,1,3,1,NULL,'common',22,22,0,'armor',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(4,1,4,1,NULL,'common',22,22,0,'helmet',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(5,1,5,1,NULL,'common',22,22,0,'neckless',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(6,1,6,1,NULL,'common',22,22,0,'ring',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(7,1,7,1,NULL,'common',22,22,0,'arms',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(8,1,8,1,NULL,'common',22,22,0,'shield',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(9,1,9,1,NULL,'common',22,22,0,'legs',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(10,1,10,1,NULL,'common',22,22,0,'boots',NULL,'shop','2025-07-09 07:50:40','2025-07-09 07:50:40'),(11,1,1,0,'{\"agility\": 1}','uncommon',20,20,0,'weapon',30,'equipped',NULL,NULL);
/*!40000 ALTER TABLE `character_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `characters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `base_health` int NOT NULL DEFAULT '0',
  `current_health` int NOT NULL DEFAULT '0',
  `health_regeneration_started_at` timestamp NULL DEFAULT NULL,
  `is_in_battle` tinyint(1) NOT NULL DEFAULT '0',
  `strength` int NOT NULL DEFAULT '3',
  `agility` int NOT NULL DEFAULT '3',
  `intuition` int NOT NULL DEFAULT '3',
  `endurance` int NOT NULL DEFAULT '3',
  `stat_points` int NOT NULL DEFAULT '3',
  `level` int NOT NULL DEFAULT '0',
  `experience` bigint unsigned NOT NULL DEFAULT '0',
  `gold` bigint unsigned NOT NULL DEFAULT '0',
  `wins` int unsigned NOT NULL DEFAULT '0',
  `losses` int unsigned NOT NULL DEFAULT '0',
  `draws` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `characters_user_id_foreign` (`user_id`),
  CONSTRAINT `characters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `characters`
--

LOCK TABLES `characters` WRITE;
/*!40000 ALTER TABLE `characters` DISABLE KEYS */;
INSERT INTO `characters` VALUES (1,1,0,36,NULL,0,3,3,3,6,0,0,8,7,1,0,0,'2025-07-09 07:50:40','2025-07-09 08:25:16');
/*!40000 ALTER TABLE `characters` ENABLE KEYS */;
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
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_level` tinyint unsigned NOT NULL DEFAULT '1',
  `buy_price` int unsigned NOT NULL DEFAULT '0',
  `sell_price` int unsigned NOT NULL DEFAULT '0',
  `min_damage` tinyint unsigned DEFAULT NULL,
  `max_damage` tinyint unsigned DEFAULT NULL,
  `defense_by_zone` json DEFAULT NULL,
  `type` enum('sword','axe','mace','knife','armor','helmet','shield','arms','legs','boots','ring','earrings','neckless') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rarity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'common',
  `bonuses` json DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `base_max_durability` int unsigned NOT NULL DEFAULT '20',
  `is_shop` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Гострий Зуб',1,15,5,1,2,'[]','knife','weapon','common','{\"agility\": 1}','/images/items/knifes/knife-0.webp','Дешевий ніж з грубо обробленої сталі, який підійде для різання, кидання або відчаю, але не для серйозної битви.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(2,'Топор учня мʼясника',1,24,6,2,3,'[]','axe','weapon','common','{\"strength\": 1}','/images/items/axe/axe-1.png','Грубий бойовий топор, викуваний з важкого заліза. Недбалий баланс і тупе лезо не роблять його ідеальним, але в руках відчайдушного може завдати смертельного удару.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(3,'Шкіряний Дух Мандрівника',1,12,4,NULL,NULL,'{\"belly\": {\"max\": 2, \"min\": 1}, \"chest\": {\"max\": 4, \"min\": 1}}','armor','armor','common','{\"endurance\": 1}','/images/items/torso/torso-1.png','Проста, але надійна броня з грубої шкіри, яку носять мисливці, початківці та ті, кому треба легкий захист без зайвого тягаря. Пахне дьогтем і пригодами.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(4,'Металевий Шолом Новачка',1,10,3,NULL,NULL,'{\"head\": {\"max\": 4, \"min\": 1}}','helmet','helmet','common','{\"endurance\": 1}','/images/items/helmet/helmet-1.png','Старий, трохи пом’ятий шолом зі сталі. Надійно прикриває голову від легких ударів, але залишає вуха холодними. Ідеальний для тих, хто тільки починає шлях воїна.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(5,'Амулет Початківця',1,7,2,NULL,NULL,'[]','neckless','neckless','common','{\"luck\": 1}','/images/items/neckless/neckless-1.png','Простий дерев’яний амулет на шкіряній нитці. Допомагає трішки більше щастити в бою.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(6,'Кільце Практиканта',1,5,1,NULL,NULL,'[]','ring','ring','common','{\"agility\": 1}','/images/items/ring/ring-1.png','Легке металеве кільце. Його носять новачки, що прагнуть пришвидшити свої рухи.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(7,'Рукавиці Робітника',1,6,2,NULL,NULL,'{\"belly\": {\"max\": 3, \"min\": 1}}','arms','arms','common','{\"strength\": 1}','/images/items/arms/arms-1.png','Шкіряні рукавиці з мозолями. Служать скромним, але надійним захистом для рук.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(8,'Дерев’яний Щит Початківця',1,12,4,NULL,NULL,'[]','shield','shield','common','{\"block\": 2}','/images/items/shield/shield-1.png','Простий щит з дуба. Може витримати кілька ударів і подарувати відчуття безпеки.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(9,'Штани Учня',1,9,3,NULL,NULL,'{\"belt\": {\"max\": 2, \"min\": 1}, \"legs\": {\"max\": 4, \"min\": 2}}','legs','legs','common','{\"endurance\": 1}','/images/items/legs/legs-1.png','Зношені штани з грубого полотна. Не захистять від меча, але не сковують рухів.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18'),(10,'Черевики Новачка',1,7,2,NULL,NULL,'{\"legs\": {\"max\": 4, \"min\": 1}}','boots','boots','common','{\"agility\": 1}','/images/items/boots/boots-1.png','Легкі черевики зі старої шкіри. Допомагають швидше бігати та краще триматися на ногах.',20,1,'2025-07-09 07:50:18','2025-07-09 07:50:18');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (7,'0001_01_01_000000_create_users_table',1),(8,'0001_01_01_000001_create_cache_table',1),(9,'0001_01_01_000002_create_jobs_table',1),(10,'2025_06_14_135241_create_characters_table',1),(11,'2025_06_14_141726_create_monsters_table',1),(12,'2025_06_22_110016_create_items_table',1),(13,'2025_06_22_115416_create_character_items_table',1),(14,'2025_06_29_132559_create_monster_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monster_items`
--

DROP TABLE IF EXISTS `monster_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monster_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `monster_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `bonuses` json DEFAULT NULL,
  `level` tinyint unsigned NOT NULL DEFAULT '1',
  `slot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rarity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'common',
  `sell_price` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monster_items_monster_id_foreign` (`monster_id`),
  KEY `monster_items_item_id_foreign` (`item_id`),
  CONSTRAINT `monster_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monster_items_monster_id_foreign` FOREIGN KEY (`monster_id`) REFERENCES `monsters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monster_items`
--

LOCK TABLES `monster_items` WRITE;
/*!40000 ALTER TABLE `monster_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `monster_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monsters`
--

DROP TABLE IF EXISTS `monsters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monsters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_health` int NOT NULL DEFAULT '20',
  `current_health` int NOT NULL DEFAULT '20',
  `strength` int NOT NULL DEFAULT '3',
  `agility` int NOT NULL DEFAULT '3',
  `intuition` int NOT NULL DEFAULT '3',
  `endurance` int NOT NULL DEFAULT '3',
  `level` int NOT NULL DEFAULT '0',
  `gold` int NOT NULL DEFAULT '0',
  `is_temporary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monsters`
--

LOCK TABLES `monsters` WRITE;
/*!40000 ALTER TABLE `monsters` DISABLE KEYS */;
/*!40000 ALTER TABLE `monsters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('UeCtGcMZJQruh1DQbKrK3yRTNqeZQ1zNtDwzCGzF',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:140.0) Gecko/20100101 Firefox/140.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZTh5WHo0ZG9MaHUySURZdkxlUEhiVlByS01zSWFuNmJvbDVWY0pQNSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2ZpZ2h0LnRlc3QvaW52ZW50b3J5Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9maWdodC50ZXN0L2ludmVudG9yeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1752060573);
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
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ASD','asd@asd.com',NULL,'$2y$12$4cPvAfq2bdcjbCGgbqX53ejIm7hsWIOlBvMDJBRgb6lkXHSArcZIq',NULL,'2025-07-09 07:50:40','2025-07-09 07:50:40');
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

-- Dump completed on 2025-07-09 14:29:33
