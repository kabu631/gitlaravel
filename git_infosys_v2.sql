-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: git_infosys_v2
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
-- Table structure for table `accessory_types`
--

DROP TABLE IF EXISTS `accessory_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accessory_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accessory_types_name_unique` (`name`),
  UNIQUE KEY `accessory_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessory_types`
--

LOCK TABLES `accessory_types` WRITE;
/*!40000 ALTER TABLE `accessory_types` DISABLE KEYS */;
INSERT INTO `accessory_types` VALUES (1,'Earbuds','earbuds','2026-05-18 11:33:57','2026-05-18 11:33:57'),(2,'Headphones','headphones','2026-05-18 11:33:57','2026-05-18 11:33:57'),(3,'Charger','charger','2026-05-18 11:33:57','2026-05-18 11:33:57'),(4,'Phone Case','phone-case','2026-05-18 11:33:57','2026-05-18 11:33:57'),(5,'USB Cable','usb-cable','2026-05-18 11:33:57','2026-05-18 11:33:57');
/*!40000 ALTER TABLE `accessory_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand_category`
--

DROP TABLE IF EXISTS `brand_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_category` (
  `brand_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`brand_id`,`category_id`),
  KEY `brand_category_category_id_foreign` (`category_id`),
  CONSTRAINT `brand_category_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  CONSTRAINT `brand_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand_category`
--

LOCK TABLES `brand_category` WRITE;
/*!40000 ALTER TABLE `brand_category` DISABLE KEYS */;
INSERT INTO `brand_category` VALUES (1,2),(1,3),(1,5),(2,2),(2,3),(2,4),(2,5),(3,2),(3,4),(3,5),(3,6),(4,2),(4,4),(5,2),(5,4),(6,2),(6,4),(6,5),(7,2),(7,3),(7,4),(8,3),(8,6),(9,3),(9,6),(10,4),(10,6),(11,4),(11,5),(11,6),(12,4),(12,5);
/*!40000 ALTER TABLE `brand_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Samsung','samsung','brands/samsung.jpg','2026-05-18 11:33:57','2026-05-18 11:51:32'),(2,'Apple','apple','brands/apple.jpg','2026-05-18 11:33:57','2026-05-18 11:51:32'),(3,'Xiaomi','xiaomi','brands/xiaomi.jpg','2026-05-18 11:33:57','2026-05-18 11:51:33'),(4,'OnePlus','oneplus','brands/oneplus.jpg','2026-05-18 11:33:57','2026-05-18 11:51:33'),(5,'OPPO','oppo','brands/oppo.jpg','2026-05-18 11:33:57','2026-05-18 11:51:34'),(6,'Realme','realme','brands/realme.jpg','2026-05-18 11:33:57','2026-05-18 11:51:34'),(7,'Sony','sony','brands/sony.jpg','2026-05-18 11:33:57','2026-05-18 11:51:35'),(8,'HP','hp','brands/hp.jpg','2026-05-18 11:33:57','2026-05-18 11:51:35'),(9,'Dell','dell','brands/dell.jpg','2026-05-18 11:33:57','2026-05-18 11:51:36'),(10,'JBL','jbl','brands/jbl.jpg','2026-05-18 11:33:57','2026-05-18 11:51:36'),(11,'boAt','boat','brands/boat.jpg','2026-05-18 11:33:57','2026-05-18 11:51:37'),(12,'Noise','noise','brands/noise.jpg','2026-05-18 11:33:57','2026-05-18 11:51:37');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
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
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('git-infosys-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:1;',1779124785),('git-infosys-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1779124785;',1779124785);
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
  `expiration` bigint(20) NOT NULL,
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
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_key` varchar(40) DEFAULT NULL,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) DEFAULT NULL,
  `variant_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_user_id_foreign` (`user_id`),
  KEY `cart_items_gadget_id_foreign` (`gadget_id`),
  KEY `cart_items_session_key_index` (`session_key`),
  KEY `cart_items_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `cart_items_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Mobile','mobile','2026-05-18 11:33:57','2026-05-18 11:33:57'),(3,'Laptop','laptop','2026-05-18 11:33:57','2026-05-18 11:33:57'),(4,'Earbuds','earbuds','2026-05-18 11:33:57','2026-05-18 11:33:57'),(5,'Smartwatch','smartwatch','2026-05-18 11:33:57','2026-05-18 11:33:57'),(6,'Accessory','accessory','2026-05-18 11:33:57','2026-05-18 11:33:57');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'ram prasad','prasadbhai@gmail.com','9874563210','Tech','hello hello testing testing',0,'2026-05-18 11:29:06','2026-05-18 11:29:06'),(2,'hari lal','gopalbhai@gmail.com','9789654123','Custom PC Build Request (AI Recommended)','Hi Git Infosys Team,\n\nI would like to order the following custom PC build recommended by your AI Builder. Please contact me to confirm the order and parts availability:\n\n---\n\n## Recommended Build\n\n| Component | Model | Est. Price (NPR) |\n|---|---|---:|\n| CPU | **Intel Core i3-14100** | 18,500 |\n| GPU | **Integrated Intel UHD Graphics 730** | 0 |\n| RAM | **16GB (2x8GB) DDR5-5200 CL40** | 8,500 |\n| Storage | **1TB NVMe SSD (Kingston NV2 / WD SN580 class)** | 8,800 |\n| Motherboard | **H610M DDR5 motherboard (MSI / Gigabyte / ASUS)** | 15,500 |\n| PSU | **Corsair CV550 550W 80+ Bronze** | 7,500 |\n| Cooling | **Stock Intel cooler** | 0 |\n| Case | **Micro-ATX case with 3 fans** | 6,500 |\n\n## Total Estimated Cost\n\n**NPR 65,300**\n\n## Performance Summary\n\nThis build is a strong **office and study PC** for Nepal’s market. It will handle:\n\n- MS Office, Google Workspace, Zoom, Teams\n- Web browsing with many tabs\n- Online classes and research\n- Programming, light coding, and IDE use\n- Basic photo editing and light multitasking\n- 1080p video playback smoothly\n\nSince this is an office-focused build, I prioritized **fast RAM, a modern CPU, and a 1TB SSD** for responsiveness and storage comfort. You can also use this system as a solid everyday home PC.\n\n## Compatibility Notes\n\n- **CPU + motherboard:** Intel Core i3-14100 needs an **LGA1700 H610 motherboard**.\n- **RAM type:** Make sure the motherboard is **DDR5**, not DDR4.\n- **Case size:** Choose a **micro-ATX case** to fit the H610M board properly.\n- **PSU headroom:** A **550W Bronze PSU** is more than enough for this configuration and leaves room for a future GPU.\n- **Integrated graphics:** This build does **not require a dedicated GPU** for office/study use, because the i3-14100 includes integrated graphics.\n- **Cooling:** The stock cooler is enough for normal office workloads.\n\n## Upgrade Path\n\nIf you increase budget later, upgrade in this order:\n\n- **First:** Add a **dedicated GPU** if you want light gaming or video editing\n- **Second:** Upgrade to **32GB RAM** for heavier multitasking\n- **Third:** Move to a **better CPU**, such as an **Intel Core i5-14400**\n- **Fourth:** Add a **second SSD** or larger NVMe drive for more storage\n- **Fifth:** Upgrade to a **higher-quality PSU** if you plan a stronger graphics card later\n\nYou can buy all these components directly from **Git Infosys**. If you want, I can also make this into a **cheaper NPR 100,000 max build with a dedicated GPU** or a **fully branded office PC build**.',0,'2026-05-18 11:30:21','2026-05-18 11:30:21');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
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
-- Table structure for table `gadget_images`
--

DROP TABLE IF EXISTS `gadget_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gadget_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gadget_images_gadget_id_foreign` (`gadget_id`),
  CONSTRAINT `gadget_images_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadget_images`
--

LOCK TABLES `gadget_images` WRITE;
/*!40000 ALTER TABLE `gadget_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `gadget_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gadget_variants`
--

DROP TABLE IF EXISTS `gadget_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gadget_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `variant_type` varchar(20) NOT NULL,
  `value` varchar(100) NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gadget_variants_gadget_id_foreign` (`gadget_id`),
  CONSTRAINT `gadget_variants_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadget_variants`
--

LOCK TABLES `gadget_variants` WRITE;
/*!40000 ALTER TABLE `gadget_variants` DISABLE KEYS */;
INSERT INTO `gadget_variants` VALUES (1,1,'color','Onyx Black',NULL,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(2,1,'color','Marble Gray',NULL,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(3,1,'storage','256GB',89999.00,20,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(4,1,'storage','512GB',104999.00,8,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(5,2,'color','Natural Titanium',NULL,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(6,2,'color','Black Titanium',NULL,8,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(7,2,'storage','256GB',149999.00,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(8,2,'storage','512GB',174999.00,5,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(9,3,'color','Black',NULL,20,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(10,3,'color','White',NULL,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(11,3,'storage','256GB',74999.00,25,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(12,3,'storage','512GB',89999.00,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(13,4,'color','Silky Black',NULL,18,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(14,4,'color','Flowy Emerald',NULL,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(15,4,'storage','256GB',79999.00,20,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(16,4,'storage','512GB',94999.00,6,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(17,5,'color','Rock Gray',NULL,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(18,5,'color','Misty Lavender',NULL,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(19,5,'storage','256GB',62999.00,18,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(20,6,'color','Submarine Blue',NULL,20,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(21,6,'color','Pearl White',NULL,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(22,6,'storage','256GB',44999.00,22,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(23,7,'color','Moonstone Gray',NULL,8,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(24,7,'ram','16GB',149999.00,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(25,7,'ram','32GB',169999.00,4,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(26,8,'color','Natural Silver',NULL,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(27,8,'storage','512GB SSD',74999.00,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(28,8,'storage','1TB SSD',84999.00,6,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(29,9,'color','Carbon Black',NULL,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(30,9,'storage','512GB',69999.00,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(31,9,'storage','1TB',79999.00,5,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(32,10,'color','Black',NULL,20,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(33,10,'color','Silver',NULL,15,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(34,11,'color','Active Black',NULL,50,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(35,11,'color','Mint Green',NULL,30,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(36,11,'color','Berry Blue',NULL,25,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(37,12,'color','Graphite',NULL,12,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(38,12,'color','Gold',NULL,8,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(39,12,'other','40mm',34999.00,10,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(40,12,'other','44mm',37999.00,8,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(41,13,'color','Midnight Black',NULL,40,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(42,13,'color','Rose Gold',NULL,30,1,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(43,13,'color','Steel Blue',NULL,25,1,'2026-05-18 11:33:58','2026-05-18 11:33:58');
/*!40000 ALTER TABLE `gadget_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gadgets`
--

DROP TABLE IF EXISTS `gadgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gadgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `accessory_type` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `model_3d` varchar(255) DEFAULT NULL,
  `sketchfab_embed` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `price_tracker_description` text DEFAULT NULL,
  `views_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gadgets_slug_unique` (`slug`),
  KEY `gadgets_brand_id_foreign` (`brand_id`),
  KEY `gadgets_category_id_foreign` (`category_id`),
  CONSTRAINT `gadgets_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gadgets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadgets`
--

LOCK TABLES `gadgets` WRITE;
/*!40000 ALTER TABLE `gadgets` DISABLE KEYS */;
INSERT INTO `gadgets` VALUES (1,1,2,'Samsung Galaxy S24','samsung-galaxy-s24',NULL,'gadgets/samsung-galaxy-s24.jpg',NULL,NULL,89999.00,99999.00,'2024-01-17',1,1,'The Samsung Galaxy S24 packs a Snapdragon 8 Gen 3 processor and a 50MP triple camera system into a sleek 6.2-inch display. Ideal for power users in Nepal.','Track price changes for Samsung Galaxy S24 in Nepal. Get notified when the price drops.',1840,'2026-05-18 11:33:58','2026-05-18 11:51:43'),(2,2,2,'iPhone 15 Pro','iphone-15-pro',NULL,'gadgets/iphone-15-pro.jpg',NULL,NULL,149999.00,164999.00,'2023-09-22',1,1,'The iPhone 15 Pro features the powerful A17 Pro chip, titanium design, and a 48MP main camera with Action button. A premium experience for serious users.','Track price changes for iPhone 15 Pro in Nepal. Get notified when the price drops.',3704,'2026-05-18 11:33:58','2026-05-18 11:48:44'),(3,3,2,'Xiaomi 14','xiaomi-14',NULL,'gadgets/xiaomi-14.jpg',NULL,NULL,74999.00,82999.00,'2024-02-25',0,1,'Xiaomi 14 brings Leica co-engineered cameras and Snapdragon 8 Gen 3 performance to the mid-premium segment at an aggressive price point.','Track price changes for Xiaomi 14 in Nepal. Get notified when the price drops.',728,'2026-05-18 11:33:58','2026-05-18 11:48:45'),(4,4,2,'OnePlus 12','oneplus-12',NULL,'gadgets/oneplus-12.jpg',NULL,NULL,79999.00,87999.00,'2024-01-23',1,0,'The OnePlus 12 features a Hasselblad-tuned 50MP periscope zoom camera, 100W SUPERVOOC charging, and a 6.82-inch 2K ProXDR display.','Track price changes for OnePlus 12 in Nepal. Get notified when the price drops.',2156,'2026-05-18 11:33:58','2026-05-18 11:48:46'),(5,5,2,'OPPO Reno 11 Pro','oppo-reno-11-pro',NULL,'gadgets/oppo-reno-11-pro.jpg',NULL,NULL,62999.00,69999.00,'2024-01-10',0,0,'OPPO Reno 11 Pro features a 50MP triple camera system, 80W SUPERVOOC fast charging, and a premium curved-glass design with MediaTek Dimensity 8200.','Track price changes for OPPO Reno 11 Pro in Nepal. Get notified when the price drops.',1991,'2026-05-18 11:33:58','2026-05-18 11:48:47'),(6,6,2,'Realme 12 Pro+','realme-12-pro',NULL,'gadgets/realme-12-pro.jpg',NULL,NULL,44999.00,49999.00,'2024-02-01',0,1,'Realme 12 Pro+ packs a 50MP Sony IMX890 OIS camera with 3x optical zoom, 67W SUPERVOOC charging, and a curved AMOLED display at an impressive mid-range price.','Track price changes for Realme 12 Pro+ in Nepal. Get notified when the price drops.',5583,'2026-05-18 11:33:58','2026-05-18 11:48:49'),(7,1,3,'Samsung Galaxy Book4 Pro','samsung-galaxy-book4-pro',NULL,'gadgets/samsung-galaxy-book4-pro.jpg',NULL,NULL,149999.00,159999.00,'2024-03-01',1,0,'The Galaxy Book4 Pro is an ultra-slim laptop powered by Intel Core Ultra, featuring a stunning 3K AMOLED display and seamless Galaxy ecosystem integration.','Track price changes for Samsung Galaxy Book4 Pro in Nepal. Get notified when the price drops.',6791,'2026-05-18 11:33:58','2026-05-18 11:48:50'),(8,8,3,'HP Pavilion 15','hp-pavilion-15',NULL,'gadgets/hp-pavilion-15.jpg',NULL,NULL,74999.00,82999.00,'2023-10-15',0,0,'The HP Pavilion 15 is a reliable everyday laptop with AMD Ryzen 5 processing power, 15.6-inch FHD display, and long battery life. Ideal for students and professionals.','Track price changes for HP Pavilion 15 in Nepal. Get notified when the price drops.',5821,'2026-05-18 11:33:58','2026-05-18 11:48:51'),(9,9,3,'Dell Inspiron 15 3520','dell-inspiron-15-3520',NULL,'gadgets/dell-inspiron-15-3520.jpg',NULL,NULL,69999.00,77999.00,'2023-08-20',0,0,'Dell Inspiron 15 3520 offers solid performance with Intel Core i5 12th Gen, 15.6-inch FHD display, and a compact lightweight build for daily use.','Track price changes for Dell Inspiron 15 3520 in Nepal. Get notified when the price drops.',2794,'2026-05-18 11:33:58','2026-05-18 11:48:54'),(10,7,4,'Sony WF-1000XM5','sony-wf-1000xm5',NULL,'gadgets/sony-wf-1000xm5.jpg',NULL,NULL,29999.00,34999.00,'2023-07-01',1,1,'Sony WF-1000XM5 are the world\'s best noise-cancelling earbuds with 8 hours of battery, LDAC Hi-Res Audio support, and premium call quality.','Track price changes for Sony WF-1000XM5 in Nepal. Get notified when the price drops.',4571,'2026-05-18 11:33:58','2026-05-18 11:48:55'),(11,11,4,'boAt Airdopes 141','boat-airdopes-141',NULL,'gadgets/boat-airdopes-141.jpg',NULL,NULL,1699.00,2499.00,'2023-05-15',0,1,'boAt Airdopes 141 delivers up to 42 hours of total playback, ENx mic technology for crystal-clear calls, and a compact design at an unbeatable price.','Track price changes for boAt Airdopes 141 in Nepal. Get notified when the price drops.',3721,'2026-05-18 11:33:58','2026-05-18 11:48:56'),(12,1,5,'Samsung Galaxy Watch 6','samsung-galaxy-watch-6',NULL,'gadgets/samsung-galaxy-watch-6.jpg',NULL,NULL,34999.00,39999.00,'2023-08-11',1,0,'Samsung Galaxy Watch 6 features advanced health monitoring, Wear OS 4, and a sleek design with 40mm/44mm options for fitness and smartwatch enthusiasts.','Track price changes for Samsung Galaxy Watch 6 in Nepal. Get notified when the price drops.',2368,'2026-05-18 11:33:58','2026-05-18 11:48:58'),(13,12,5,'Noise ColorFit Ultra 3','noise-colorfit-ultra-3',NULL,'gadgets/noise-colorfit-ultra-3.jpg',NULL,NULL,3999.00,5999.00,'2023-09-01',0,1,'Noise ColorFit Ultra 3 packs a 1.96-inch AMOLED display, 100+ sports modes, 8-day battery life, and Bluetooth calling at a very affordable price.','Track price changes for Noise ColorFit Ultra 3 in Nepal. Get notified when the price drops.',879,'2026-05-18 11:33:58','2026-05-18 11:48:58');
/*!40000 ALTER TABLE `gadgets` ENABLE KEYS */;
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
  `attempts` smallint(5) unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_12_000001_create_brands_table',1),(5,'2026_05_12_000002_create_categories_table',1),(6,'2026_05_12_000003_create_gadgets_table',1),(7,'2026_05_12_000004_create_spec_sheets_table',1),(8,'2026_05_12_000005_create_gadget_variants_table',1),(9,'2026_05_12_000006_create_gadget_images_table',1),(10,'2026_05_12_000007_create_price_histories_table',1),(11,'2026_05_12_000008_create_orders_table',1),(12,'2026_05_12_000009_create_cart_items_table',1),(13,'2026_05_12_000010_create_reviews_table',1),(14,'2026_05_12_000011_create_news_and_guides_table',1),(15,'2026_05_12_000012_create_user_profiles_table',1),(16,'2026_05_12_000013_add_is_admin_to_users_table',1),(17,'2026_05_12_000014_create_sliders_table',1),(18,'2026_05_12_000015_create_brand_category_table',1),(19,'2026_05_12_000016_create_page_contents_table',1),(20,'2026_05_12_000017_create_product_variants_table',1),(21,'2026_05_12_000018_add_product_variant_id_to_cart_and_orders',1),(22,'2026_05_14_010456_create_contact_messages_table',1),(23,'2026_05_14_014305_add_price_tracker_description_to_gadgets_table',1),(24,'2026_05_14_060816_create_accessory_types_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_articles`
--

DROP TABLE IF EXISTS `news_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('tech','mobile','laptop','gaming','ai','software','gadgets','telecom') NOT NULL DEFAULT 'tech',
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int(10) unsigned NOT NULL DEFAULT 0,
  `meta_description` varchar(160) DEFAULT NULL,
  `react_happy` int(10) unsigned NOT NULL DEFAULT 0,
  `react_sad` int(10) unsigned NOT NULL DEFAULT 0,
  `react_love` int(10) unsigned NOT NULL DEFAULT 0,
  `react_like` int(10) unsigned NOT NULL DEFAULT 0,
  `react_funny` int(10) unsigned NOT NULL DEFAULT 0,
  `react_angry` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_articles_slug_unique` (`slug`),
  KEY `news_articles_user_id_foreign` (`user_id`),
  CONSTRAINT `news_articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_articles`
--

LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
INSERT INTO `news_articles` VALUES (1,20,'Snapdragon 8 Gen 4 Expected to Debut at Qualcomm Summit 2024','snapdragon-8-gen-4-debut-qualcomm-summit-2024','<p>Qualcomm is expected to unveil the Snapdragon 8 Gen 4 mobile processor at its annual Snapdragon Summit in October 2024. Codenamed SM8750, the chip is rumored to feature a brand-new Oryon CPU architecture — the same used in Snapdragon X Elite laptops — promising a massive generational performance leap.</p><p>Early benchmarks show single-core scores up to 3,000 in Geekbench, nearly double that of the Snapdragon 8 Gen 3.</p><h2>What to Expect</h2><ul><li>Oryon CPU with 3nm TSMC fabrication</li><li>Adreno 830 GPU with massive performance uplift</li><li>Hexagon NPU at 80 TOPS for on-device AI</li><li>Wi-Fi 7, Bluetooth 5.4, and X80 5G modem</li></ul><p>The Samsung Galaxy S25 series and OnePlus 13 are expected to be among the first devices launching with Snapdragon 8 Gen 4 in early 2025.</p>','tech','news/article-1.jpg',1,2722,'Qualcomm Snapdragon 8 Gen 4 is expected to launch at Snapdragon Summit 2024 with Oryon CPU and 80 TOPS AI performance.',0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:49:02'),(2,20,'Apple Intelligence Coming to Nepal: What iPhone Users Need to Know','apple-intelligence-nepal-iphone-users-guide','<p>Apple Intelligence, the AI feature suite introduced with iOS 18.1, is gradually rolling out to more regions. Nepali iPhone users could soon access Writing Tools, Image Playground, and the revamped Siri with ChatGPT integration.</p><p>Apple Intelligence requires an iPhone 15 Pro or any iPhone 16 model, or an iPad/Mac with M1 chip or later.</p><h2>Key Features</h2><ul><li><strong>Writing Tools</strong>: Rewrite, proofread, and summarize text in any app</li><li><strong>Smart Reply</strong>: AI-suggested email and message replies</li><li><strong>Image Playground</strong>: Generate images from text prompts on-device</li><li><strong>Priority Notifications</strong>: AI ranks your most important alerts</li><li><strong>Siri with ChatGPT</strong>: Deep conversational AI with web knowledge</li></ul>','mobile','news/article-2.jpg',1,1425,'Apple Intelligence is rolling out globally. Everything Nepali iPhone users need to know about AI features, compatibility, and how to enable them.',0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:49:04'),(3,20,'Best Gaming Laptops in Nepal Under NPR 1.5 Lakh in 2024','best-gaming-laptops-nepal-under-150000-2024','<p>Gaming laptops have become increasingly accessible in Nepal, with powerful options now available under NPR 1.5 lakh.</p><h2>Top Picks</h2><h3>1. ASUS TUF Gaming A15 (2024) — NPR 1,19,999</h3><p>Powered by AMD Ryzen 7 7435HS and RTX 4060, excellent 1080p gaming performance with a 144Hz display.</p><h3>2. Lenovo IdeaPad Gaming 3 Gen 8 — NPR 99,999</h3><p>AMD Ryzen 5 7535HS with RTX 4050 — the best budget gaming laptop under 1 lakh in Nepal.</p><h3>3. HP Victus 16 (RTX 4060) — NPR 1,34,999</h3><p>Intel Core i7-13700H and RTX 4060 — a performance beast with a stunning 144Hz FHD display.</p>','laptop','news/article-3.jpg',1,5996,'Discover the best gaming laptops available in Nepal under NPR 1.5 lakh in 2024, including top picks from ASUS, Lenovo, and HP.',0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:49:05'),(4,20,'NTC and Ncell 5G Rollout in Nepal: Full 2024 Update','ntc-ncell-5g-rollout-nepal-2024','<p>Nepal\'s telecommunications sector is on the brink of a major leap forward as both NTC and Ncell have announced plans to accelerate 5G rollout across major cities by end 2024.</p><h2>Current Status</h2><ul><li><strong>NTC 5G</strong>: Trial phase in Kathmandu, Pokhara, and Chitwan; commercial launch expected Q1 2025</li><li><strong>Ncell 5G</strong>: Infrastructure deployment underway in Kathmandu</li><li><strong>Spectrum</strong>: NTA is finalizing 5G spectrum allocation policy</li></ul><h2>Which Phones Support 5G in Nepal?</h2><p>Popular 5G-capable options in Nepal: Samsung Galaxy S24 series, iPhone 15 Pro, OnePlus 12, and Xiaomi 14 — all support Sub-6GHz 5G bands compatible with Nepal\'s planned spectrum.</p>','telecom','news/article-4.jpg',1,6629,'NTC and Ncell are advancing 5G deployment in Nepal. Full update on timelines, spectrum plans, and compatible phones.',0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:49:06');
/*!40000 ALTER TABLE `news_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscriptions`
--

DROP TABLE IF EXISTS `newsletter_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscriptions_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscriptions`
--

LOCK TABLES `newsletter_subscriptions` WRITE;
/*!40000 ALTER TABLE `newsletter_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `gadget_id` bigint(20) unsigned DEFAULT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `variant_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_gadget_id_foreign` (`gadget_id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `order_items_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` enum('cod','esewa','khalti') NOT NULL DEFAULT 'cod',
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_contents`
--

DROP TABLE IF EXISTS `page_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page` varchar(255) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `subheading` varchar(500) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_contents_page_unique` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_contents`
--

LOCK TABLES `page_contents` WRITE;
/*!40000 ALTER TABLE `page_contents` DISABLE KEYS */;
INSERT INTO `page_contents` VALUES (1,'about','Nepal\'s Trusted Tech Platform','We help Nepali consumers make smarter, more confident tech purchasing decisions through honest reviews, real-time price tracking, and expert guides.',NULL,'Learn about Git Infosys — the team, mission, and values behind Nepal\'s leading tech review, gadget comparison, and price tracking platform.','{\"mission\":\"To be Nepal\'s most trusted source for gadget reviews, price comparisons, and tech news \\u2014 empowering every buyer with unbiased, data-driven insights.\",\"vision\":\"A Nepal where every consumer has access to transparent, up-to-date technology information and can shop with complete confidence.\",\"story\":\"Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal. What began as a simple price tracker has grown into Nepal\'s comprehensive tech ecosystem \\u2014 covering reviews, comparisons, buying guides, and an e-commerce hub. Today, we serve thousands of Nepali consumers every month, helping them find the right gadgets at the right prices.\"}','2026-05-18 11:33:57','2026-05-18 11:33:57'),(2,'contact','Get In Touch','Have a question, feedback, or partnership inquiry? We\'d love to hear from you.','<p></p>','Have a question, review request, or partnership proposal? Contact the Git Infosys team and we\'ll get back to you shortly.','{\"address\":\"Kathmandu, Nepal\",\"email\":\"info@gitinfosys.com.np\",\"phone\":\"+977 9766434622\",\"hours\":\"Sun \\u2013 Fri: 9 AM \\u2013 6 PM\",\"map_lat\":\"27.7027051\",\"map_lng\":\"85.3099064\"}','2026-05-18 11:33:57','2026-05-18 11:50:20'),(3,'services','Our Platform Services','Git Infosys is Nepal\'s ultimate tech ecosystem. We offer an integrated suite of tools, reviews, and shopping experiences to make your tech life smarter.',NULL,'Explore what Git Infosys offers — gadget reviews, price comparison, buying guides, sponsored content, and more for Nepal\'s tech community.',NULL,'2026-05-18 11:33:57','2026-05-18 11:33:57'),(4,'terms','Terms & Conditions','Please read these terms carefully before using our platform.','<h2>1. Acceptance of Terms</h2><p>By accessing and using the Git Infosys website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our platform.</p><h2>2. Use of the Platform</h2><p>Git Infosys provides technology product reviews, comparisons, news, and an e-commerce hub. Our platform is intended for informational purposes to help consumers make informed purchasing decisions.</p><ul><li>You must be at least 16 years old to use this platform.</li><li>You agree not to misuse the platform or help anyone else do so.</li><li>You may not use the platform for any illegal or unauthorized purpose.</li></ul><h2>3. Product Information &amp; Pricing</h2><p>We make every effort to display accurate product information and pricing. However, prices and availability are subject to change without notice. Git Infosys is not responsible for pricing errors or discrepancies between our listed prices and those at retail locations.</p><h2>4. User Accounts</h2><p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password. You agree to notify us immediately of any unauthorized use of your account.</p><h2>5. Intellectual Property</h2><p>All content on Git Infosys, including text, graphics, logos, images, and software, is the property of Git Infosys and is protected by copyright and intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written permission.</p><h2>6. Purchases &amp; Payments</h2><p>All purchases made through our platform are subject to product availability. We reserve the right to refuse or cancel any order at any time. Refunds and returns are handled according to our return policy communicated at the time of purchase.</p><h2>7. Limitation of Liability</h2><p>Git Infosys shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the platform. Our total liability shall not exceed the amount you paid for the specific product or service.</p><h2>8. Changes to Terms</h2><p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website. Your continued use of the platform constitutes acceptance of the modified terms.</p><h2>9. Contact</h2><p>If you have any questions about these Terms and Conditions, please contact us via our <a href=\"/contact\">contact page</a>.</p>','Read the terms and conditions governing your use of the Git Infosys website, products, and services.',NULL,'2026-05-18 11:33:57','2026-05-18 11:33:57'),(5,'privacy','Privacy Policy','Your privacy is important to us. This policy explains how we collect and use your data.','<h2>1. Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us. This may include:</p><ul><li>Name, email address, and password</li><li>Billing and shipping address</li><li>Phone number (for order communications)</li><li>Purchase history and preferences</li><li>Comments and reviews you submit</li></ul><h2>2. How We Use Your Information</h2><p>We use the information we collect to:</p><ul><li>Process transactions and send related information</li><li>Send promotional communications (with your consent)</li><li>Respond to comments and questions</li><li>Improve our products and services</li><li>Monitor and analyze usage patterns</li></ul><h2>3. Information Sharing</h2><p>We do not sell, trade, or otherwise transfer your personal information to outside parties except to trusted third parties who assist us in operating our website (such as payment processors and shipping partners), as long as those parties agree to keep this information confidential.</p><h2>4. Cookies</h2><p>Our site uses cookies to enhance your browsing experience. Cookies are small files that a site transfers to your computer\'s hard drive through your Web browser that enables the site to recognize your browser and remember certain information. You can choose to disable cookies through your browser settings.</p><h2>5. Data Security</h2><p>We implement a variety of security measures to maintain the safety of your personal information. Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems.</p><h2>6. Your Rights</h2><p>You have the right to:</p><ul><li>Access the personal information we hold about you</li><li>Request correction of inaccurate data</li><li>Request deletion of your account and associated data</li><li>Opt-out of marketing communications at any time</li></ul><h2>7. Third-Party Links</h2><p>Our website may contain links to other sites. We are not responsible for the privacy practices or the content of such websites. We encourage you to review the privacy policies of any third-party sites you visit.</p><h2>8. Changes to This Policy</h2><p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p><h2>9. Contact Us</h2><p>If you have any questions about this Privacy Policy, please <a href=\"/contact\">contact us</a>.</p>','Understand how Git Infosys collects, uses, and protects your personal information when you use our platform.',NULL,'2026-05-18 11:33:57','2026-05-18 11:33:57');
/*!40000 ALTER TABLE `page_contents` ENABLE KEYS */;
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
-- Table structure for table `price_histories`
--

DROP TABLE IF EXISTS `price_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_histories_gadget_id_foreign` (`gadget_id`),
  CONSTRAINT `price_histories_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_histories`
--

LOCK TABLES `price_histories` WRITE;
/*!40000 ALTER TABLE `price_histories` DISABLE KEYS */;
INSERT INTO `price_histories` VALUES (14,1,106200.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(15,1,102600.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(16,1,99900.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(17,1,97200.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(18,1,95400.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(19,1,93600.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(20,1,91800.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(21,1,90900.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(22,1,89999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(23,2,168000.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(24,2,165000.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(25,2,162000.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(26,2,159000.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(27,2,157500.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(28,2,154500.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(29,2,153000.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(30,2,151500.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(31,2,149999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(32,3,86200.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(33,3,84000.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(34,3,81700.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(35,3,79500.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(36,3,78000.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(37,3,77200.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(38,3,75700.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(39,3,75000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(40,3,74999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(41,4,91200.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(42,4,88800.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(43,4,86400.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(44,4,84800.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(45,4,83200.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(46,4,81600.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(47,4,80800.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(48,4,80000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(49,4,79999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(50,5,73100.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(51,5,71200.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(52,5,69300.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(53,5,67400.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(54,5,66100.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(55,5,64900.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(56,5,63600.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(57,5,63000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(58,5,62999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(59,6,50800.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(60,6,49500.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(61,6,48600.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(62,6,47200.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(63,6,46300.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(64,6,45900.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(65,6,45400.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(66,6,45000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(67,6,44999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(68,7,165000.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(69,7,162000.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(70,7,159000.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(71,7,157500.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(72,7,156000.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(73,7,154500.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(74,7,153000.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(75,7,151500.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(76,7,149999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(77,8,84000.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(78,8,81700.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(79,8,80200.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(80,8,78700.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(81,8,78000.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(82,8,76500.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(83,8,75700.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(84,8,75000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(85,8,74999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(86,9,77700.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(87,9,75600.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(88,9,74200.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(89,9,72800.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(90,9,72100.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(91,9,71400.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(92,9,70700.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(93,9,70000.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(94,9,69999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(95,10,36000.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(96,10,34800.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(97,10,33900.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(98,10,33000.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(99,10,32100.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(100,10,31500.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(101,10,30600.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(102,10,30300.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(103,10,29999.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(104,11,2200.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(105,11,2100.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(106,11,2000.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(107,11,1900.00,'2025-12-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(108,11,1800.00,'2026-01-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(109,11,1800.00,'2026-02-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(110,11,1700.00,'2026-03-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(111,11,1700.00,'2026-04-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(112,11,1699.00,'2026-05-18','2026-05-18 11:43:07','2026-05-18 11:43:07'),(113,12,39900.00,'2025-09-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(114,12,38800.00,'2025-10-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(115,12,38100.00,'2025-11-01','2026-05-18 11:43:07','2026-05-18 11:43:07'),(116,12,37400.00,'2025-12-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(117,12,36700.00,'2026-01-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(118,12,36000.00,'2026-02-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(119,12,35700.00,'2026-03-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(120,12,35300.00,'2026-04-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(121,12,34999.00,'2026-05-18','2026-05-18 11:43:08','2026-05-18 11:43:08'),(122,13,5400.00,'2025-09-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(123,13,5100.00,'2025-10-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(124,13,4800.00,'2025-11-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(125,13,4600.00,'2025-12-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(126,13,4400.00,'2026-01-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(127,13,4200.00,'2026-02-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(128,13,4100.00,'2026-03-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(129,13,4000.00,'2026-04-01','2026-05-18 11:43:08','2026-05-18 11:43:08'),(130,13,3999.00,'2026-05-18','2026-05-18 11:43:08','2026-05-18 11:43:08');
/*!40000 ALTER TABLE `price_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `storage` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `discounted_price` decimal(12,2) DEFAULT NULL,
  `stock_quantity` int(10) unsigned NOT NULL DEFAULT 0,
  `variant_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_gadget_id_foreign` (`gadget_id`),
  CONSTRAINT `product_variants_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `pros` text DEFAULT NULL,
  `cons` text DEFAULT NULL,
  `verdict` text DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `react_happy` int(10) unsigned NOT NULL DEFAULT 0,
  `react_sad` int(10) unsigned NOT NULL DEFAULT 0,
  `react_love` int(10) unsigned NOT NULL DEFAULT 0,
  `react_like` int(10) unsigned NOT NULL DEFAULT 0,
  `react_funny` int(10) unsigned NOT NULL DEFAULT 0,
  `react_angry` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_slug_unique` (`slug`),
  KEY `reviews_gadget_id_foreign` (`gadget_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,20,'Samsung Galaxy S24 Review: Snapdragon 8 Gen 3 Flagship Value','samsung-galaxy-s24-review-snapdragon-8-gen-3-flagship-value','<p>The Samsung Galaxy S24 represents excellent flagship value for Nepali consumers. With Snapdragon 8 Gen 3 globally, Samsung has finally delivered a consistently fast Galaxy S experience. The 50MP camera system produces stunning photos, especially in night mode.</p><p>Galaxy AI features — Circle to Search, Live Translate, and Generative Edit — work remarkably well. The 4000mAh battery is the weakest point, typically giving 1–1.5 days of usage, but the 25W charging is fast for the capacity.</p>',8.5,'Snapdragon 8 Gen 3 performance\nExcellent 50MP camera system\nGalaxy AI features work genuinely well\nCompact and premium design\nBright 2600-nit display','Only 4000mAh battery\n25W charging (slower than competitors)\nNo charger in box\nGlass back prone to fingerprints','The Galaxy S24 is one of the best compact flagships in Nepal. Snapdragon performance, excellent cameras, and Galaxy AI in a pocket-friendly size.',1,0,0,1,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:43:58'),(2,2,20,'iPhone 15 Pro Review: Titanium, Action Button, and USB-C Finally','iphone-15-pro-review-titanium-action-button-and-usb-c-finally','<p>The iPhone 15 Pro is Apple\'s most refined iPhone yet. The titanium build makes it lighter, and the Action Button adds customizable utility. USB-C adoption means a universal cable — at full USB 3 speeds for the first time.</p><p>The A17 Pro chip is untouchable in performance. The 48MP camera with Tetraprism periscope zoom (5x) takes exceptional portrait and zoom photography. ProRes video is impressive for videographers.</p>',9.0,'A17 Pro chip — fastest mobile processor\nTitanium build — lighter and premium\nExcellent 48MP camera with 5x periscope zoom\nAction Button adds customization\nUSB-C with USB 3 speeds','Very expensive at NPR 149,999\nSmall 3274mAh battery\n27W charging is slow in 2024\nThick camera bump','The iPhone 15 Pro is the best iPhone Apple has made. If you want the best camera, performance, and long-term software support in Nepal, this is it.',1,0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(3,10,20,'Sony WF-1000XM5 Review: Still the Best Noise-Cancelling Earbuds','sony-wf-1000xm5-review-still-the-best-noise-cancelling-earbuds','<p>Sony continues to dominate the premium TWS earbud category with the WF-1000XM5. The ANC is class-leading — it genuinely blocks traffic, chatter, and ambient noise in a way no other earbud under NPR 35,000 can match.</p><p>Sound quality via LDAC is spectacular when paired with a compatible Android phone, offering near-over-ear headphone quality in a tiny package.</p>',9.2,'Best-in-class ANC performance\nLDAC Hi-Res Audio support\nExcellent call quality with AI noise rejection\nPremium build quality and IPX4 rating\nSmall and lightweight','Expensive at NPR 29,999\nNo wireless charging for the case\nRequires correct eartip fit for best ANC','The Sony WF-1000XM5 are the best true wireless earbuds you can buy in Nepal. ANC and sound quality remain unmatched. Highly recommended.',1,0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(4,3,20,'Xiaomi 14 Review: Leica Cameras at a Smarter Price','xiaomi-14-review-leica-cameras-at-a-smarter-price','<p>The Xiaomi 14 is a genuinely exciting smartphone for Nepal. Leica-tuned cameras, Snapdragon 8 Gen 3, and 90W fast charging in a compact body make it a compelling alternative to the Galaxy S24 at a lower price.</p><p>The main 50MP Leica camera with variable aperture is exceptional in outdoor photography. Low-light performance is class-leading in the under-NPR 80,000 segment.</p>',8.7,'Snapdragon 8 Gen 3 performance\nExceptional Leica camera system\n90W HyperCharge — very fast\nCompact premium design\nWi-Fi 7 support','No official global ROM for Nepal\nNo IP68 rating (only IP68 for global markets)\nHyperOS may feel unfamiliar to some users','Xiaomi 14 offers flagship performance and Leica camera quality at a meaningfully lower price than Samsung and Apple equivalents. Excellent value for Nepal.',1,0,0,0,0,0,0,'2026-05-18 11:33:58','2026-05-18 11:33:58');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('ImDiruJgdsORDxkKeQ7I9kUjlF9oQsT5DVTd7DiB',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJHd0lEeG1EOTdqS1FxcU4zN1MzUjdPbGpocmxvOVBUcDhJdFFzTXZQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1779124865),('N1kHvI1XCuNjFFDMkCJzGnSp7OV7bC4vs4zcsL4t',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJ0Y3hzT3FIZkhlRkFjMlpIOXlHdkNWR3JEbzhKRzNmRXBGWmVOa1VnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1779124822),('OZH01gn95ruUY6sdvp4i7K00T7BDWELmw1xanzrJ',20,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJ1cmwiOltdLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfdG9rZW4iOiJBVmNXOFRNUWdNRzRNUWt4VjV5WjNwWkJmNGRLbHpReFI3NzVqa0IwIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIwLCJwYXNzd29yZF9oYXNoX3dlYiI6ImZkMzY2Mjc1OTQ3ZDcwOGY2MDEzYjNhNzhjZmZjZWY1ZTVlNzRlMTU4YTEwNjFkNzIxZTI5MDYzODkzZjA1NmMiLCJ0YWJsZXMiOnsiMjU4ODg3NWQxMWM0NzU2NTg0ZDAyMWRmZmE4MzU1MjNfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpZCIsImxhYmVsIjoiT3JkZXIgIyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ1c2VyLm5hbWUiLCJsYWJlbCI6IkN1c3RvbWVyIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRvdGFsX2Ftb3VudCIsImxhYmVsIjoiVG90YWwiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3RhdHVzIiwibGFiZWwiOiJTdGF0dXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGF5bWVudF9tZXRob2QiLCJsYWJlbCI6IlBheW1lbnQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiRGF0ZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XSwiZDllNGZjMjA1MjMzMzNkZjdmY2EwNDJmMjMwZjNiMGNfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ0aXRsZSIsImxhYmVsIjoiVGl0bGUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY2F0ZWdvcnkiLCJsYWJlbCI6IkNhdGVnb3J5IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InZpZXdzX2NvdW50IiwibGFiZWwiOiJWaWV3cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19wdWJsaXNoZWQiLCJsYWJlbCI6IklzIHB1Ymxpc2hlZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJDcmVhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCJmZjVmNTMzNTkwOWMyZGUzMjllYTNiNjI2YzM1MjI4Ml9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InBhZ2UiLCJsYWJlbCI6IlBhZ2UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaGVhZGluZyIsImxhYmVsIjoiSGVhZGluZyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdWJoZWFkaW5nIiwibGFiZWwiOiJTdWJoZWFkaW5nIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVwZGF0ZWRfYXQiLCJsYWJlbCI6Ikxhc3QgVXBkYXRlZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XX0sImZpbGFtZW50IjpbXX0=',1779125829),('VBajc7VKaYP4TQtFApcB7aGn1iav5lpEAHecFgP3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJDV1NpOWM0NjdYbW40a3dhaU1ieWdSTGFLQ21wZ3ZNUDRsWjZZRGo4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1779125338),('zbSphIcdLqAhd2PSV9g8RY96MhYtppXdFsbcuWCW',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJCcjFZV1hOYUJRNUtuQnBmdWsyajVFcmlCVzFuZERxSnFiMDY0bXF4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbmV3cyIsInJvdXRlIjoibmV3cy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1779124842);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `btn1_text` varchar(255) NOT NULL DEFAULT 'Browse Products',
  `btn1_url` varchar(255) NOT NULL DEFAULT '/gadgets',
  `btn1_style` varchar(255) NOT NULL DEFAULT 'violet',
  `btn2_text` varchar(255) DEFAULT NULL,
  `btn2_url` varchar(255) DEFAULT NULL,
  `btn2_style` varchar(255) NOT NULL DEFAULT 'dark',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Samsung Galaxy S24 Ultra','Experience the Future of Mobile Photography','The most powerful Galaxy ever. With the built-in S Pen and 200MP camera, capture every detail in stunning clarity.','sliders/slider-1.jpg','New Arrival','Shop Now','/gadgets','primary','Learn More','/gadgets','dark',1,1,'2026-05-18 11:33:57','2026-05-18 11:48:59'),(2,'MacBook Air M3','Supercharged by Apple Silicon','The world\'s thinnest laptop. Up to 18 hours of battery life and blazing-fast M3 chip performance.','sliders/slider-2.jpg','Best Seller','Explore Laptops','/gadgets?category=laptop','primary','Compare','/compare','dark',1,2,'2026-05-18 11:33:57','2026-05-18 11:48:59'),(3,'Price Tracker — Never Overpay Again','Real-Time Price Monitoring for Nepal\'s Tech Market','Track price drops across all major gadgets. Set alerts and buy at the perfect moment.','sliders/slider-3.jpg','Free Feature','Track Prices','/gadgets','primary','Browse All','/gadgets','dark',1,3,'2026-05-18 11:33:57','2026-05-18 11:49:00'),(4,'AI-Powered Gadget Comparison','Compare. Analyze. Decide with Confidence.','Our AI compares up to 4 devices side by side and gives you a personalized buying recommendation.','sliders/slider-4.jpg','AI Feature','Try Compare','/compare','primary','Browse Gadgets','/gadgets','dark',1,4,'2026-05-18 11:33:57','2026-05-18 11:49:01');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spec_sheets`
--

DROP TABLE IF EXISTS `spec_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spec_sheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `display` varchar(255) DEFAULT NULL,
  `processor` varchar(255) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `storage` varchar(255) DEFAULT NULL,
  `battery` varchar(255) DEFAULT NULL,
  `camera` varchar(500) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `connectivity` varchar(500) DEFAULT NULL,
  `weight` varchar(100) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `extra_specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_specs`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spec_sheets_gadget_id_unique` (`gadget_id`),
  CONSTRAINT `spec_sheets_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spec_sheets`
--

LOCK TABLES `spec_sheets` WRITE;
/*!40000 ALTER TABLE `spec_sheets` DISABLE KEYS */;
INSERT INTO `spec_sheets` VALUES (1,1,'6.2-inch Dynamic AMOLED 2X, 2340×1080, 120Hz','Snapdragon 8 Gen 3','8GB','256GB','4000mAh, 25W Fast Charging','50MP + 10MP + 12MP | 12MP Selfie','Android 14, One UI 6.1','5G, Wi-Fi 6E, Bluetooth 5.3, NFC','167g','147 × 70.6 × 7.6 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(2,2,'6.1-inch Super Retina XDR OLED, 2556×1179, 120Hz ProMotion','Apple A17 Pro','8GB','256GB','3274mAh, 27W Fast Charging, MagSafe','48MP + 12MP + 12MP | 12MP TrueDepth Selfie','iOS 17','5G, Wi-Fi 6E, Bluetooth 5.3, NFC, USB-C','187g','146.6 × 70.6 × 8.25 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(3,3,'6.36-inch LTPO AMOLED, 2670×1200, 120Hz','Snapdragon 8 Gen 3','12GB','256GB','4610mAh, 90W HyperCharge','50MP Leica + 50MP + 50MP | 32MP Selfie','Android 14, HyperOS','5G, Wi-Fi 7, Bluetooth 5.4, NFC, USB-C','193g','152.8 × 71.5 × 8.2 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(4,4,'6.82-inch LTPO AMOLED, 3168×1440, 1–120Hz','Snapdragon 8 Gen 3','12GB','256GB','5400mAh, 100W SUPERVOOC, 50W AirVOOC','50MP Hasselblad + 48MP + 64MP | 32MP Selfie','Android 14, OxygenOS 14','5G, Wi-Fi 7, Bluetooth 5.4, NFC, USB-C','220g','164.3 × 75.8 × 9.15 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(5,5,'6.74-inch LTPO AMOLED, 2772×1240, 120Hz','MediaTek Dimensity 8200','12GB','256GB','4600mAh, 80W SUPERVOOC','50MP Sony IMX890 + 32MP Telephoto + 8MP | 32MP Selfie','Android 14, ColorOS 14','5G, Wi-Fi 6, Bluetooth 5.3, NFC','185g','161.6 × 74.2 × 8.1 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(6,6,'6.7-inch Curved AMOLED, 2412×1080, 120Hz','Snapdragon 7s Gen 2','8GB','256GB','5000mAh, 67W SUPERVOOC','50MP Sony IMX890 OIS + 64MP 3× Periscope + 8MP | 32MP Selfie','Android 14, Realme UI 5.0','5G, Wi-Fi 6, Bluetooth 5.3, NFC','190g','161.5 × 74 × 8.8 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(7,7,'16-inch 3K AMOLED, 2880×1800, 120Hz','Intel Core Ultra 7 155H','16GB LPDDR5x','512GB NVMe SSD','76Wh, 65W PD Charging','3MP IR Webcam','Windows 11 Home','Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4, USB-C, HDMI','1.55kg','355.4 × 250.4 × 12.5 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(8,8,'15.6-inch FHD IPS, 1920×1080, Anti-glare','AMD Ryzen 5 7530U','8GB DDR4','512GB NVMe SSD','41Wh, up to 8.5 hours','HD 720p Webcam','Windows 11 Home','Wi-Fi 5, Bluetooth 4.2, USB-A ×2, USB-C, HDMI','1.75kg','357.9 × 234.5 × 17.9 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(9,9,'15.6-inch FHD, 1920×1080, 120Hz','Intel Core i5-1235U','8GB DDR4','512GB SSD','54Wh, 65W Adapter','720p HD Webcam','Windows 11 Home','Wi-Fi 5, Bluetooth 5.1, USB-A ×2, USB-C, HDMI, SD Card','1.65kg','357.3 × 235.56 × 16.84 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(10,10,'N/A','Integrated Processor V2','N/A','N/A','8hr (earbuds) + 24hr with case, USB-C Quick Charge','N/A','N/A','Bluetooth 5.3, Multipoint, NFC','5.9g per bud','Case: 49.8 × 50.3 × 27.2 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(11,11,'N/A','N/A','N/A','N/A','6hr (earbuds) + 36hr with case','N/A','N/A','Bluetooth 5.3','4.3g per bud','N/A',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(12,12,'1.3-inch Super AMOLED, 432×432, Sapphire crystal glass','Exynos W930, Dual-core 1.4GHz','2GB','16GB','300mAh (40mm), Wireless Charging','N/A','Wear OS 4.0, One UI Watch 5.0','Bluetooth 5.3, Wi-Fi 2.4/5GHz, GPS, NFC','28.7g (without strap)','38.8 × 40.4 × 9.0 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(13,13,'1.96-inch AMOLED, 410×502','N/A','N/A','N/A','7–8 days typical use, Magnetic charging','N/A','Noise OS','Bluetooth 5.3 calling, GPS','36g with strap','47 × 38 × 10.5 mm',NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58');
/*!40000 ALTER TABLE `spec_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tech_guides`
--

DROP TABLE IF EXISTS `tech_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tech_guides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tech_guides_slug_unique` (`slug`),
  KEY `tech_guides_user_id_foreign` (`user_id`),
  CONSTRAINT `tech_guides_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tech_guides`
--

LOCK TABLES `tech_guides` WRITE;
/*!40000 ALTER TABLE `tech_guides` DISABLE KEYS */;
INSERT INTO `tech_guides` VALUES (1,20,'How to Choose the Right Smartphone in Nepal: Complete Buying Guide 2024','how-to-choose-smartphone-nepal-2024','<h2>Step 1: Set Your Budget</h2><p>In Nepal, smartphones range from NPR 8,000 for entry-level devices to over NPR 2,00,000 for flagships. Budget: under NPR 20,000. Mid-range: NPR 20,000–60,000. Upper-mid: NPR 60,000–1,00,000. Flagship: above 1,00,000.</p><h2>Step 2: Pick Your Priority</h2><ul><li><strong>Camera</strong>: Samsung Galaxy S series, iPhone, Xiaomi</li><li><strong>Battery life</strong>: Realme, Xiaomi Note series, OPPO A series</li><li><strong>Performance</strong>: Snapdragon 8 Gen series or Apple A-series</li><li><strong>Display</strong>: AMOLED with 120Hz refresh rate is the sweet spot</li></ul><h2>Step 3: Consider 5G Readiness</h2><p>With NTC and Ncell rolling out 5G in Nepal, choosing a 5G-capable device makes sense if you plan to keep the phone 3–4 years.</p><h2>Step 4: Check After-Sales Service</h2><p>Always buy from authorized dealers in Nepal. Samsung, Apple, Xiaomi, and OnePlus all have authorized service centers in Kathmandu.</p>','guides/guide-1.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:07'),(2,20,'Laptop vs Tablet: Which Should You Buy for Work and Study in Nepal?','laptop-vs-tablet-work-study-nepal','<h2>The Key Differences</h2><p>Tablets excel at media consumption and light browsing. Laptops dominate for productivity, coding, and content creation. For most students and professionals in Nepal, a laptop is the better overall choice.</p><h2>When to Choose a Tablet</h2><ul><li>You primarily consume content (YouTube, Netflix, reading)</li><li>You want to take handwritten notes</li><li>Your work is fully cloud-based</li></ul><h2>When to Choose a Laptop</h2><ul><li>You code, design, or edit video/photo</li><li>You need Microsoft Office with full functionality</li><li>Your institution requires specific software</li></ul><h2>Best of Both Worlds</h2><p>Consider the iPad Pro with Magic Keyboard or Samsung Galaxy Tab S9 with Book Cover Keyboard — these hybrid solutions are available from NPR 80,000–1,50,000 in Nepal.</p>','guides/guide-2.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:08'),(3,20,'True Wireless Earbuds Buying Guide for Nepal 2024','true-wireless-earbuds-buying-guide-nepal-2024','<h2>Key Specifications Explained</h2><h3>Active Noise Cancellation (ANC)</h3><p>ANC uses microphones to analyze and cancel external noise. Premium ANC: Sony WF-1000XM5 and Apple AirPods Pro 2. Budget ANC: boAt Airdopes Pro series.</p><h3>Battery Life</h3><p>Look for at least 6 hours on the buds and 24 total hours including the case.</p><h3>Codec Support</h3><ul><li><strong>AAC</strong>: Standard for iOS</li><li><strong>aptX/aptX Adaptive</strong>: Best for Android</li><li><strong>LDAC</strong>: Highest quality (Sony)</li></ul><h2>Nepal Price Tiers</h2><ul><li><strong>Budget (under NPR 3,000)</strong>: boAt Airdopes, Noise Air Buds</li><li><strong>Mid-range (NPR 3,000–10,000)</strong>: OnePlus Buds Pro, Realme Buds Air</li><li><strong>Premium (NPR 10,000–35,000)</strong>: Sony WF-1000XM5, Samsung Galaxy Buds2 Pro</li></ul>','guides/guide-3.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:09'),(4,20,'How to Read a Gadget Spec Sheet: A Beginner\'s Guide','how-to-read-gadget-spec-sheet-beginners-guide','<h2>Understanding Display Specs</h2><p>When you see \'6.1-inch Super Retina XDR OLED, 2556x1179, 120Hz\': 6.1-inch is the diagonal screen size. 2556x1179 is the resolution. 120Hz is the refresh rate — higher means smoother scrolling.</p><h2>Processor</h2><p>Snapdragon 8 Gen 3 or Apple A17 Pro are chip names. Newer generations are faster.</p><h2>RAM vs Storage</h2><p>RAM (8GB, 12GB) = working memory for multitasking. Storage (128GB, 256GB) = space for your photos, apps, and files.</p><h2>Battery</h2><p>mAh = capacity. Charging watts = speed. 65W charges most phones from 0–100% in under an hour.</p>','guides/guide-4.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:10'),(5,20,'Top 5 Reasons to Buy a 5G Phone in Nepal Right Now','top-reasons-buy-5g-phone-nepal','<h2>1. Future-Proof Your Investment</h2><p>Phones last 3–5 years. Buy 5G now to be ready when coverage reaches your area without upgrading.</p><h2>2. Better Processors</h2><p>Most 5G phones come with Snapdragon 7 Gen series or above — better performance even without 5G.</p><h2>3. Improved Wi-Fi</h2><p>5G chipsets usually support Wi-Fi 6 and Bluetooth 5.3+.</p><h2>4. Narrowing Price Gap</h2><p>5G phones in Nepal are now available from NPR 20,000–25,000. The premium over 4G is just NPR 2,000–5,000.</p><h2>5. Better Resale Value</h2><p>5G phones hold resale value better as 4G devices depreciate faster once 5G becomes mainstream.</p>','guides/guide-5.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:11'),(6,20,'How to Use the Price Tracker on Git Infosys','how-to-use-price-tracker-git-infosys','<h2>What is the Price Tracker?</h2><p>The Git Infosys Price Tracker monitors gadget prices over time, showing historical price trends so you can identify the best time to buy.</p><h2>How to Access Price History</h2><ol><li>Browse to any gadget page</li><li>Scroll down to the \"Price History\" section</li><li>View the chart showing price changes over past months</li></ol><h2>Tips for Smart Buying</h2><ul><li>Check if the current price is near the historical low</li><li>Look for Dashain/Tihar seasonal price cuts</li><li>Use the Compare tool to find the best value at your budget</li><li>Get AI-powered buying recommendations for personalized advice</li></ul>','guides/guide-6.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:12'),(7,20,'Smartwatch Buying Guide for Nepal 2024','smartwatch-buying-guide-nepal-2024','<h2>Key Features to Look For</h2><h3>Health Monitoring</h3><ul><li>Heart rate sensor (standard on all watches)</li><li>SpO2 blood oxygen sensor</li><li>Sleep tracking</li><li>ECG — available on Samsung Galaxy Watch, Apple Watch</li></ul><h3>Battery Life</h3><p>Premium watches (Apple Watch, Galaxy Watch) last 1–2 days. Budget options like Noise, Fire-Boltt, Amazfit last 7–10 days.</p><h2>Nepal Price Ranges</h2><ul><li><strong>Budget (NPR 2,000–5,000)</strong>: Noise, Amazfit — basic fitness + Bluetooth calling</li><li><strong>Mid-range (NPR 5,000–20,000)</strong>: Amazfit GTR 4, Garmin Forerunner</li><li><strong>Premium (NPR 25,000+)</strong>: Samsung Galaxy Watch, Apple Watch</li></ul>','guides/guide-7.jpg',1,'2026-05-18 11:33:58','2026-05-18 11:49:13');
/*!40000 ALTER TABLE `tech_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_comments`
--

DROP TABLE IF EXISTS `user_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gadget_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_comments_gadget_id_user_id_unique` (`gadget_id`,`user_id`),
  KEY `user_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `user_comments_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_comments`
--

LOCK TABLES `user_comments` WRITE;
/*!40000 ALTER TABLE `user_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_profiles_user_id_unique` (`user_id`),
  CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
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
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (20,'Admin','admin@gitinfosys.com',NULL,'$2y$12$qATImJeSzn0rKRx5DBgDAeCTf0RAOpi8gyj2XPDYsppH3dg7bdoWm',1,NULL,'2026-05-18 11:19:09','2026-05-18 11:33:33'),(21,'Ramesh Thapa','ramesh@example.com','2026-05-18 11:33:57','$2y$12$d50lma8EqKYB/QurKSKl3uDJKXDROc.oy0nRWRRLXHnpQ.ex2mEQy',0,NULL,'2026-05-18 11:33:57','2026-05-18 11:33:57'),(22,'Sita Sharma','sita@example.com','2026-05-18 11:33:58','$2y$12$nrQo8MNtKvtcnxGvH5YzwOxGeTI9Z3TDfyLKQfktkNWmgqQixkUuW',0,NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58'),(23,'Bikash Karki','bikash@example.com','2026-05-18 11:33:58','$2y$12$qVQQ3YWYDGQCOImmBpV79OeDTHwm3NJcWqzxA2wXtwwX55t9vdWEG',0,NULL,'2026-05-18 11:33:58','2026-05-18 11:33:58');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `user_id` bigint(20) unsigned NOT NULL,
  `gadget_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`gadget_id`),
  KEY `wishlists_gadget_id_foreign` (`gadget_id`),
  CONSTRAINT `wishlists_gadget_id_foreign` FOREIGN KEY (`gadget_id`) REFERENCES `gadgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-18 23:22:31
