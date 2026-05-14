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
INSERT INTO `brands` VALUES (1,'Samsung','samsung',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,'Apple','apple',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,'Xiaomi','xiaomi',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(4,'OnePlus','oneplus',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(5,'Google','google',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(6,'ASUS','asus',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(7,'Lenovo','lenovo',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(8,'HP','hp',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(9,'Dell','dell',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(10,'Acer','acer',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(11,'Sony','sony',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(12,'Realme','realme',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49');
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
INSERT INTO `cache` VALUES ('git-infosys-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:1;',1778734604),('git-infosys-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1778734604;',1778734604);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (1,NULL,'odFRwfRCayeoUCwWarJcEuISB3okwbIHFicBFKtn',16,NULL,1,126000.00,'{\"ram\":\"16 GB\"}','2026-05-12 06:42:43','2026-05-12 06:42:43'),(2,8,NULL,12,NULL,1,75999.00,NULL,'2026-05-12 07:27:26','2026-05-12 07:27:26'),(5,NULL,'qWF1COmfZfDQfJlGajttmjLWXHQ0ZAspj5DZCIBk',15,NULL,1,121000.00,NULL,'2026-05-12 11:57:12','2026-05-12 11:57:12');
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
INSERT INTO `categories` VALUES (1,'Mobile','mobile','2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,'Laptop','laptop','2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,'Tablet','tablet','2026-05-12 03:35:49','2026-05-12 03:35:49'),(4,'Earbuds','earbuds','2026-05-12 03:35:49','2026-05-12 03:35:49'),(5,'Smartwatch','smartwatch','2026-05-12 03:35:49','2026-05-12 03:35:49'),(6,'Accessory','accessory','2026-05-12 03:35:49','2026-05-12 03:35:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness','This is a test message to ensure the contact flow works properly.',1,'2026-05-13 21:14:06','2026-05-13 22:39:15'),(2,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness','This is a test message to ensure the contact flow works properly.',1,'2026-05-13 21:15:25','2026-05-13 22:39:14'),(3,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness','This is a test message to ensure the contact flow works properly.',1,'2026-05-13 21:16:04','2026-05-13 22:39:13'),(4,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness','This is a test message to ensure the contact flow works properly.',1,'2026-05-13 21:16:39','2026-05-13 22:39:12'),(5,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness Contact Form','This is a test message to ensure the contact flow works properly. Form test 2.',1,'2026-05-13 21:17:03','2026-05-13 22:39:11'),(6,'QA Tester Final','qa@tester.com','9812345678','Success Message Test','This is a test message to catch the success notification.',1,'2026-05-13 21:18:11','2026-05-13 22:39:10'),(7,'QA Tester','qa@tester.com','9812345678','Testing Production Readiness','This is a test message to ensure the contact flow works properly.',1,'2026-05-13 21:22:03','2026-05-13 22:39:09'),(8,'ram lal','ramu@gmail.com','9874563210','Custom PC Build Request (AI Recommended)','Hi Git Infosys Team,\n\nI would like to order the following custom PC build recommended by your AI Builder. Please contact me to confirm the order and parts availability:\n\n---\n\n### 1. **Recommended Build**  \n**Purpose:** Budget Build for Gaming/Light Productivity  \n\n| **Component**       | **Model**                                | **Price (NPR)** |  \n|----------------------|------------------------------------------|-----------------|  \n| **CPU**              | Intel Core i5-13400F (6C/12T)            | 35,000          |  \n| **GPU**              | NVIDIA GTX 1650 Super 4GB                | 25,000          |  \n| **RAM**              | Corsair Vengeance LPX 16GB DDR4 3200MHz  | 12,000          |  \n| **Storage**          | Western Digital Red Plus 1TB HDD         | 10,000          |  \n| **Motherboard**      | MSI B660M MORTAR WIFI DDR4               | 28,000          |  \n| **PSU**              | Corsair CX650 650W Bronze                 | 22,000          |  \n| **Cooling**          | Cooler Master Hyper 212 (Air)            | 8,000           |  \n| **Case**             | Cooler Master MasterBox Q300L            | 15,000          |  \n\n---\n\n### 2. **Total Estimated Cost**  \n**NPR 147,000**  \n*(Note: Prices are based on current market rates in Nepal as of 2024. Prices may vary slightly depending on availability.)*  \n\n---\n\n### 3. **Performance Summary**  \nThis build is optimized for **budget gaming** (1080p at medium-high settings in titles like *Valorant*, *CS2*, and *Apex Legends*) and **light productivity** (multitasking, office work, and media consumption). The i5-13400F paired with the GTX 1650 Super ensures smooth performance for entry-level gaming, while the 16GB RAM and 1TB HDD provide ample storage and multitasking capability.  \n\n---\n\n### 4. **Compatibility Notes**  \n- **CPU & Motherboard:** The Intel Core i5-13400F is compatible with the **MSI B660M MORTAR WIFI** (DDR4).  \n- **RAM:** 16GB DDR4 3200MHz is supported by the motherboard.  \n- **GPU:** The GTX 1650 Super fits in standard ATX cases and requires a 6-pin PCIe power connector (provided by the Corsair CX650).  \n- **Storage:** The 1TB HDD is compatible with the motherboardΓÇÖs SATA ports.  \n\n---\n\n### 5. **Upgrade Path**  \nIf your budget increases, prioritize upgrading the **GPU** first for better gaming performance (e.g., RTX 3060 or RX 6600 XT). Alternatively, upgrading to a **16GB DDR4 3600MHz** kit or adding a **second 1TB HDD** for storage expansion would also enhance the systemΓÇÖs capabilities.  \n\n---\n\n**Purchase All Components from Git Infosys**  \nGit Infosys offers competitive pricing and reliable service for all components in Nepal. Avoid third-party retailers like Hukut, Daraz, or Nagmani IT for this build.  \n\n**Total Cost:** NPR 147,000  \n**Build Ready for Immediate Purchase at Git Infosys.**',1,'2026-05-13 22:07:57','2026-05-13 22:39:09');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadget_images`
--

LOCK TABLES `gadget_images` WRITE;
/*!40000 ALTER TABLE `gadget_images` DISABLE KEYS */;
INSERT INTO `gadget_images` VALUES (1,13,'gadgets/gallery/dell-xps-15-laptop-2024-3d-model-1bc64b0a87.webp','',0,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,13,'gadgets/gallery/dell-xps-15-laptop-2024-3d-model-6ca2e01d4e.jpg','',0,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,17,'gadgets/gallery/01KRGTY33X01J76X6ASD300SJE.png',NULL,0,'2026-05-13 08:28:00','2026-05-13 08:28:00'),(4,17,'gadgets/gallery/01KRGTY344SPN9T7J13Y22182E.png',NULL,0,'2026-05-13 08:28:00','2026-05-13 08:28:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadget_variants`
--

LOCK TABLES `gadget_variants` WRITE;
/*!40000 ALTER TABLE `gadget_variants` DISABLE KEYS */;
INSERT INTO `gadget_variants` VALUES (1,15,'','',NULL,8,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,15,'','',NULL,5,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,15,'','',NULL,2,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(4,15,'','',NULL,3,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(5,15,'','',NULL,6,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(6,16,'ram','12 GB',122000.00,5,1,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(7,16,'ram','16 GB',126000.00,5,1,'2026-05-12 03:35:49','2026-05-12 03:35:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gadgets`
--

LOCK TABLES `gadgets` WRITE;
/*!40000 ALTER TABLE `gadgets` DISABLE KEYS */;
INSERT INTO `gadgets` VALUES (1,1,1,'Samsung Galaxy S24 Ultra','samsung-galaxy-s24-ultra',NULL,NULL,NULL,'',189999.00,NULL,'2025-12-12',1,1,'Samsung Galaxy S24 Ultra is a flagship device featuring Snapdragon 8 Gen 3 processor, 6.8\" Dynamic AMOLED 2X, 120Hz display, and 200MP + 50MP + 10MP + 12MP camera setup. Powered by a 5000 mAh, 45W battery.',NULL,7,'2026-03-24 10:05:32','2026-05-13 21:11:49'),(2,2,1,'iPhone 15 Pro Max','iphone-15-pro-max',NULL,NULL,NULL,'',209999.00,NULL,'2025-06-28',1,1,'iPhone 15 Pro Max is a flagship device featuring A17 Pro processor, 6.7\" Super Retina XDR OLED, 120Hz display, and 48MP + 12MP + 12MP camera setup. Powered by a 4441 mAh, 27W battery.',NULL,7,'2026-03-24 10:05:32','2026-03-26 01:32:27'),(3,3,1,'Xiaomi 14 Ultra','xiaomi-14-ultra',NULL,NULL,NULL,'',129999.00,NULL,'2025-10-25',1,1,'Xiaomi 14 Ultra is a flagship device featuring Snapdragon 8 Gen 3 processor, 6.73\" LTPO AMOLED, 120Hz display, and 50MP + 50MP + 50MP camera setup. Powered by a 5000 mAh, 90W battery.',NULL,12,'2026-03-24 10:05:32','2026-05-12 21:39:14'),(4,4,1,'OnePlus 12','oneplus-12',NULL,NULL,NULL,'',84999.00,NULL,'2025-08-15',1,1,'OnePlus 12 is a flagship device featuring Snapdragon 8 Gen 3 processor, 6.82\" LTPO AMOLED, 120Hz display, and 50MP + 48MP + 64MP camera setup. Powered by a 5400 mAh, 100W battery.',NULL,13,'2026-03-24 10:05:32','2026-05-12 11:38:39'),(5,5,1,'Google Pixel 8 Pro','google-pixel-8-pro',NULL,NULL,NULL,'',119999.00,125000.00,'2026-01-24',1,0,'Google Pixel 8 Pro is a flagship device featuring Google Tensor G3 processor, 6.7\" LTPO OLED, 120Hz display, and 50MP + 48MP + 48MP camera setup. Powered by a 5050 mAh, 30W battery.',NULL,28,'2026-03-24 10:05:32','2026-03-27 00:28:33'),(6,1,1,'Samsung Galaxy A55','samsung-galaxy-a55',NULL,NULL,NULL,'',45999.00,NULL,'2025-12-18',0,0,'Samsung Galaxy A55 is a flagship device featuring Exynos 1480 processor, 6.6\" Super AMOLED, 120Hz display, and 50MP + 12MP + 5MP camera setup. Powered by a 5000 mAh, 25W battery.',NULL,4,'2026-03-24 10:05:32','2026-03-26 01:32:27'),(7,12,1,'Realme GT 5 Pro','realme-gt-5-pro',NULL,NULL,NULL,'',62999.00,NULL,'2025-09-22',0,0,'Realme GT 5 Pro is a flagship device featuring Snapdragon 8 Gen 3 processor, 6.78\" AMOLED, 144Hz display, and 50MP + 8MP + 50MP camera setup. Powered by a 5400 mAh, 100W battery.',NULL,1,'2026-03-24 10:05:32','2026-03-26 01:32:27'),(8,2,1,'iPhone 15','iphone-15',NULL,NULL,NULL,'',134999.00,NULL,'2025-10-31',0,0,'iPhone 15 is a flagship device featuring A16 Bionic processor, 6.1\" Super Retina XDR OLED, 60Hz display, and 48MP + 12MP camera setup. Powered by a 3877 mAh, 20W battery.',NULL,12,'2026-03-24 10:05:32','2026-03-26 01:32:27'),(9,2,2,'MacBook Pro 14 M3 Pro','macbook-pro-14-m3-pro',NULL,NULL,NULL,'',299999.00,NULL,'2026-01-20',1,1,'MacBook Pro 14 M3 Pro features Apple M3 Pro processor, 14.2\" Liquid Retina XDR, 120Hz display, 18 GB RAM, and 512 GB SSD storage.',NULL,45,'2026-03-24 10:05:32','2026-05-13 19:47:30'),(10,6,2,'ASUS ROG Strix G16','asus-rog-strix-g16',NULL,NULL,NULL,'',179999.00,150000.00,'2025-08-13',1,1,'ASUS ROG Strix G16 features Intel Core i9-13980HX processor, 16\" FHD+ 165Hz IPS display, 16 GB DDR5 RAM, and 1 TB SSD storage.',NULL,5,'2026-03-24 10:05:32','2026-05-13 05:41:24'),(11,7,2,'Lenovo ThinkPad X1 Carbon Gen 11','lenovo-thinkpad-x1-carbon-gen-11',NULL,NULL,NULL,'',215999.00,NULL,'2025-09-01',1,1,'Lenovo ThinkPad X1 Carbon Gen 11 features Intel Core i7-1365P processor, 14\" 2.8K OLED, 90Hz display, 16 GB LPDDR5 RAM, and 512 GB SSD storage.',NULL,3,'2026-03-24 10:05:32','2026-03-26 01:32:27'),(12,8,2,'HP Pavilion 15','hp-pavilion-15',NULL,NULL,NULL,'',75999.00,72000.00,'2025-07-14',1,0,'HP Pavilion 15 features AMD Ryzen 5 7530U processor, 15.6\" FHD IPS display, 8 GB DDR4 RAM, and 512 GB SSD storage.',NULL,7,'2026-03-24 10:05:32','2026-05-12 07:27:23'),(13,9,2,'Dell XPS 15','dell-xps-15',NULL,'gadgets/01KRFMY6Y21J92ZWWY271ZQMBD.png',NULL,NULL,249999.00,255000.00,'2025-11-09',0,0,'<p>Dell XPS 15 features Intel Core i7-13700H processor, 15.6&quot; 3.5K OLED, 60Hz display, 16 GB DDR5 RAM, and 512 GB SSD storage.</p>',NULL,63,'2026-03-24 10:05:32','2026-05-12 21:45:06'),(14,10,2,'Acer Aspire 5','acer-aspire-5',NULL,NULL,NULL,'',62999.00,19000.00,'2025-10-05',0,0,'Acer Aspire 5 features AMD Ryzen 5 7520U processor, 15.6\" FHD IPS display, 8 GB DDR5 RAM, and 512 GB SSD storage.',NULL,8,'2026-03-24 10:05:32','2026-03-27 00:31:01'),(15,6,2,'Notebook Pro','asus-notebook-pro',NULL,NULL,NULL,'',121000.00,NULL,NULL,1,0,'',NULL,24,'2026-05-11 06:05:24','2026-05-12 21:45:02'),(16,1,1,'Samsung Galaxy S25 Ultra','samsung-galaxy-s25-ultra-demo',NULL,NULL,NULL,'',120000.00,125000.00,NULL,1,0,'Demo product to show variant pricing. Base price NPR 1,20,000 is for 12GB RAM + 256GB storage. Select higher RAM or storage to see the price update live.',NULL,41,'2026-05-11 06:24:22','2026-05-13 08:36:07'),(17,1,1,'samsung s26 ultra','samsung-s26-ultra',NULL,'gadgets/01KRGTY32X0MHF7EGY04BYAJ68.png',NULL,NULL,180000.00,195000.00,'2026-05-04',1,0,'<p></p>',NULL,14,'2026-05-12 12:03:51','2026-05-13 21:48:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_12_000001_create_brands_table',1),(5,'2026_05_12_000002_create_categories_table',1),(6,'2026_05_12_000003_create_gadgets_table',1),(7,'2026_05_12_000004_create_spec_sheets_table',1),(8,'2026_05_12_000005_create_gadget_variants_table',1),(9,'2026_05_12_000006_create_gadget_images_table',1),(10,'2026_05_12_000007_create_price_histories_table',1),(11,'2026_05_12_000008_create_orders_table',1),(12,'2026_05_12_000009_create_cart_items_table',1),(13,'2026_05_12_000010_create_reviews_table',1),(14,'2026_05_12_000011_create_news_and_guides_table',1),(15,'2026_05_12_000012_create_user_profiles_table',1),(16,'2026_05_12_000013_add_is_admin_to_users_table',2),(17,'2026_05_12_000014_create_sliders_table',3),(18,'2026_05_12_000015_create_brand_category_table',4),(19,'2026_05_12_000016_create_page_contents_table',5),(20,'2026_05_12_000017_create_product_variants_table',6),(21,'2026_05_12_000018_add_product_variant_id_to_cart_and_orders',6),(22,'2026_05_14_010456_create_contact_messages_table',7),(23,'2026_05_14_014305_add_price_tracker_description_to_gadgets_table',8);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_articles`
--

LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
INSERT INTO `news_articles` VALUES (1,3,'Samsung Galaxy S25 Ultra Leaked: Everything We Know So Far','samsung-galaxy-s25-ultra-leaked-everything-we-know-so-far','<p>Samsung\'s next flagship smartphone, the Galaxy S25 Ultra, has been leaked extensively online. According to reliable sources, the device will feature the Snapdragon 8 Gen 4 processor, a titanium frame, and a significantly improved camera system with a 200MP main sensor.</p><p>The phone is expected to launch in January 2025 with a starting price of NPR 199,999. Samsung is reportedly working on new AI features powered by Galaxy AI 2.0.</p>','mobile',NULL,1,4284,'Samsung Galaxy S25 Ultra Leaked: Everything We Know So Far',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(2,3,'Apple Announces M4 MacBook Pro with Revolutionary Performance','apple-announces-m4-macbook-pro-with-revolutionary-performance','<p>Apple has officially announced the M4 MacBook Pro lineup, featuring the all-new M4, M4 Pro, and M4 Max chips. The new processors deliver up to 2x faster CPU performance and 3x faster GPU performance compared to M3.</p><p>The starting price in Nepal is expected to be NPR 329,999 for the base M4 model. nice</p>','laptop',NULL,1,3329,'Apple Announces M4 MacBook Pro with Revolutionary Performance',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(3,3,'ChatGPT-5 Rumored to Launch by End of 2025','chatgpt-5-rumored-to-launch-by-end-of-2025','<p>OpenAI is reportedly preparing to launch GPT-5, its next-generation AI model, by the end of 2025. The new model is expected to demonstrate significant improvements in reasoning, coding, and creative tasks.</p><p>Industry experts believe GPT-5 could revolutionize how we interact with AI assistants and could bring near-AGI capabilities.</p>','ai',NULL,1,1458,'ChatGPT-5 Rumored to Launch by End of 2025',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(4,3,'Nepal Telecom Launches 5G Services in Kathmandu Valley','nepal-telecom-launches-5g-services-in-kathmandu-valley','<p>Nepal Telecom has officially launched 5G services in Kathmandu Valley, marking a significant milestone in Nepal\'s telecommunications history. The service is currently available in select areas of Kathmandu, Lalitpur, and Bhaktapur.</p><p>Users with 5G-capable devices can expect download speeds of up to 1 Gbps.</p>','telecom',NULL,1,3013,'Nepal Telecom Launches 5G Services in Kathmandu Valley',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(5,3,'Best Budget Gaming Laptops Under NPR 100,000 in 2025','best-budget-gaming-laptops-under-npr-100000-in-2025','<p>Looking for a gaming laptop that won\'t break the bank? We\'ve compiled a list of the best gaming laptops available in Nepal under NPR 100,000. These laptops offer excellent performance for popular games like Valorant, CS2, and GTA V.</p><p>Our top picks include the Acer Nitro 5, Lenovo IdeaPad Gaming 3, and ASUS TUF Gaming F15.</p>','laptop',NULL,1,4460,'Best Budget Gaming Laptops Under NPR 100,000 in 2025',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(6,3,'OnePlus 13 Gets OxygenOS 15 Update with New Features','oneplus-13-gets-oxygenos-15-update-with-new-features','<p>OnePlus has started rolling out the OxygenOS 15 update for the OnePlus 13, bringing several new features including enhanced AI photo editing, improved battery optimization, and a refreshed user interface. good</p>','mobile',NULL,1,4894,'OnePlus 13 Gets OxygenOS 15 Update with New Features',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(7,3,'Sony PlayStation 6 Development Confirmed by Insiders','sony-playstation-6-development-confirmed-by-insiders','<p>Industry insiders have confirmed that Sony is actively developing the PlayStation 6. While the console is not expected to launch before 2028, development kits are reportedly being shared with select game studios.</p><p>The PS6 is rumored to feature AMD\'s next-gen RDNA 5 architecture and custom AI processing capabilities.</p>','gaming',NULL,1,4398,'Sony PlayStation 6 Development Confirmed by Insiders',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-03-24 10:05:32'),(8,3,'Google Gemini Ultra 2.0 Sets New AI Benchmark Records','google-gemini-ultra-20-sets-new-ai-benchmark-records','<p><i>Google\'s latest AI model, Gemini Ultra 2.0, has set new records across multiple AI benchmarks. The model demonstrates superior performance in mathematical reasoning, code generation, and multimodal understanding.</i></p>','ai',NULL,1,3310,'Google Gemini Ultra 2.0 Sets New AI Benchmark Records',0,0,0,0,0,0,'2026-03-24 10:05:32','2026-05-12 03:39:38'),(9,5,'Samsung Galaxy A37 5g Price in Nepal','samsung-galaxy-a37-5g-price-in-nepal','this is a new news.','tech','news/website.jpg',1,9,'',0,0,0,0,0,0,'2026-03-26 22:38:31','2026-05-12 21:43:08'),(10,2,'Test Production Readiness','test-production-readiness','<p>This is a test article</p>','tech',NULL,1,0,NULL,0,0,0,0,0,0,'2026-05-13 21:00:29','2026-05-13 21:00:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,5,NULL,119999.00,1,'{}','2026-05-12 03:35:50','2026-05-12 03:35:50'),(2,2,14,NULL,62999.00,1,'{}','2026-05-12 03:35:50','2026-05-12 03:35:50'),(3,3,1,NULL,189999.00,1,NULL,'2026-05-13 21:11:19','2026-05-13 21:11:19'),(4,4,17,NULL,180000.00,1,NULL,'2026-05-13 21:19:11','2026-05-13 21:19:11'),(5,5,16,NULL,126000.00,1,'{\"ram\":\"16 GB\"}','2026-05-13 21:46:19','2026-05-13 21:46:19'),(6,5,17,NULL,180000.00,1,NULL,'2026-05-13 21:46:19','2026-05-13 21:46:19');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,NULL,'Kabindra','Koirala','kabindrakoirala86@gmail.com','9864100282','Balaju, kathmandu','esewa',0,119999.00,'shipped','2026-03-24 11:58:21','2026-05-11 05:04:45'),(2,NULL,'Ram Lal','Hari','kabinnn@gmail.com','9865321456','df afasdf asdfasd fasd fasdfasd f','esewa',0,62999.00,'delivered','2026-05-11 04:59:47','2026-05-11 05:12:32'),(3,10,'Test','User','qa@gitinfosys.com','9876543210','Test Address, Kathmandu','cod',0,189999.00,'pending','2026-05-13 21:11:19','2026-05-13 21:11:19'),(4,10,'efasd f','d fasd fss','qa@gitinfosys.com','9865231456','dfa sdfas fdasd f','cod',0,180000.00,'pending','2026-05-13 21:19:11','2026-05-13 21:19:11'),(5,2,'sdfsa d','df asd sad','admin@gitinfosys.com','9865231456','saadf asdf sad f','cod',0,306000.00,'pending','2026-05-13 21:46:19','2026-05-13 21:46:19');
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
INSERT INTO `page_contents` VALUES (1,'about','Nepal\'s Trusted Tech Platform','We help Nepali consumers make smarter, more confident tech purchasing decisions through honest reviews, real-time price tracking, and expert guides.',NULL,'Learn about Git Infosys ΓÇö the team, mission, and values behind Nepal\'s leading tech review, gadget comparison, and price tracking platform.','{\"mission\":\"To be Nepal\'s most trusted source for gadget reviews, price comparisons, and tech news \\u2014 empowering every buyer with unbiased, data-driven insights.\",\"vision\":\"A Nepal where every consumer has access to transparent, up-to-date technology information and can shop with complete confidence.\",\"story\":\"Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal. What began as a simple price tracker has grown into Nepal\'s comprehensive tech ecosystem \\u2014 covering reviews, comparisons, buying guides, and an e-commerce hub. Today, we serve thousands of Nepali consumers every month, helping them find the right gadgets at the right prices.\"}','2026-05-12 10:38:30','2026-05-12 10:38:30'),(2,'contact','Get In Touch','Have a question, feedback, or partnership inquiry? We\'d love to hear from you.',NULL,'Have a question, review request, or partnership proposal? Contact the Git Infosys team and we\'ll get back to you shortly.','{\"address\":\"Kathmandu, Nepal\",\"email\":\"info@gitinfosys.com\",\"phone\":\"+977 000 000 000\",\"hours\":\"Sun \\u2013 Fri: 9 AM \\u2013 6 PM\",\"map_lat\":\"27.7172\",\"map_lng\":\"85.3240\"}','2026-05-12 10:38:30','2026-05-12 10:38:30'),(3,'services','Our Platform Services','Git Infosys is Nepal\'s ultimate tech ecosystem. We offer an integrated suite of tools, reviews, and shopping experiences to make your tech life smarter.',NULL,'Explore what Git Infosys offers ΓÇö gadget reviews, price comparison, buying guides, sponsored content, and more for Nepal\'s tech community.',NULL,'2026-05-12 10:38:30','2026-05-12 10:38:30'),(4,'terms','Terms & Conditions','Please read these terms carefully before using our platform.','<h2>1. Acceptance of Terms</h2><p>By accessing and using the Git Infosys website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our platform.</p><h2>2. Use of the Platform</h2><p>Git Infosys provides technology product reviews, comparisons, news, and an e-commerce hub. Our platform is intended for informational purposes to help consumers make informed purchasing decisions.</p><ul><li>You must be at least 16 years old to use this platform.</li><li>You agree not to misuse the platform or help anyone else do so.</li><li>You may not use the platform for any illegal or unauthorized purpose.</li></ul><h2>3. Product Information &amp; Pricing</h2><p>We make every effort to display accurate product information and pricing. However, prices and availability are subject to change without notice. Git Infosys is not responsible for pricing errors or discrepancies between our listed prices and those at retail locations.</p><h2>4. User Accounts</h2><p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password. You agree to notify us immediately of any unauthorized use of your account.</p><h2>5. Intellectual Property</h2><p>All content on Git Infosys, including text, graphics, logos, images, and software, is the property of Git Infosys and is protected by copyright and intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written permission.</p><h2>6. Purchases &amp; Payments</h2><p>All purchases made through our platform are subject to product availability. We reserve the right to refuse or cancel any order at any time. Refunds and returns are handled according to our return policy communicated at the time of purchase.</p><h2>7. Limitation of Liability</h2><p>Git Infosys shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the platform. Our total liability shall not exceed the amount you paid for the specific product or service.</p><h2>8. Changes to Terms</h2><p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website. Your continued use of the platform constitutes acceptance of the modified terms.</p><h2>9. Contact</h2><p>If you have any questions about these Terms and Conditions, please contact us via our <a href=\"/contact\">contact page</a>.</p>','Read the terms and conditions governing your use of the Git Infosys website, products, and services.',NULL,'2026-05-12 10:38:30','2026-05-12 10:38:30'),(5,'privacy','Privacy Policy','Your privacy is important to us. This policy explains how we collect and use your data.','<h3>1. Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us. This may include:</p><ul><li><p>Name, email address, and password</p></li><li><p>Billing and shipping address</p></li><li><p>Phone number (for order communications)</p></li><li><p>Purchase history and preferences</p></li><li><p>Comments and reviews you submit</p></li></ul><h3>2. How We Use Your Information</h3><p>We use the information we collect to:</p><ul><li><p>Process transactions and send related information</p></li><li><p>Send promotional communications (with your consent)</p></li><li><p>Respond to comments and questions</p></li><li><p>Improve our products and services</p></li><li><p>Monitor and analyze usage patterns</p></li></ul><h3>3. Information Sharing</h3><p>We do not sell, trade, or otherwise transfer your personal information to outside parties except to trusted third parties who assist us in operating our website (such as payment processors and shipping partners), as long as those parties agree to keep this information confidential.</p><h3>4. Cookies</h3><p>Our site uses cookies to enhance your browsing experience. Cookies are small files that a site transfers to your computer&#039;s hard drive through your Web browser that enables the site to recognize your browser and remember certain information. You can choose to disable cookies through your browser settings.</p><h3>5. Data Security</h3><p>We implement a variety of security measures to maintain the safety of your personal information. Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems.</p><h3>6. Your Rights</h3><p>You have the right to:</p><ul><li><p>Access the personal information we hold about you</p></li><li><p>Request correction of inaccurate data</p></li><li><p>Request deletion of your account and associated data</p></li><li><p>Opt-out of marketing communications at any time</p></li></ul><h3>7. Third-Party Links</h3><p>Our website may contain links to other sites. We are not responsible for the privacy practices or the content of such websites. We encourage you to review the privacy policies of any third-party sites you visit.</p><h3>8. Changes to This Policy</h3><p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p><h3>9. Contact Us</h3><p>If you have any questions about this Privacy Policy, please <a href=\"/contact\">contact us</a>.</p>','Understand how Git Infosys collects, uses, and protects your personal information when you use our platform.',NULL,'2026-05-12 10:38:30','2026-05-12 10:48:36');
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
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_histories`
--

LOCK TABLES `price_histories` WRITE;
/*!40000 ALTER TABLE `price_histories` DISABLE KEYS */;
INSERT INTO `price_histories` VALUES (1,1,191707.00,'2025-09-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,1,190741.00,'2025-10-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,1,194282.00,'2025-11-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(4,1,197354.00,'2025-12-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(5,1,187698.00,'2026-01-23','2026-05-12 03:35:49','2026-05-12 03:35:49'),(6,1,186332.00,'2026-02-22','2026-05-12 03:35:49','2026-05-12 03:35:49'),(7,2,215311.00,'2025-09-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(8,2,212711.00,'2025-10-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(9,2,208379.00,'2025-11-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(10,2,208121.00,'2025-12-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(11,2,219438.00,'2026-01-23','2026-05-12 03:35:49','2026-05-12 03:35:49'),(12,2,210283.00,'2026-02-22','2026-05-12 03:35:49','2026-05-12 03:35:49'),(13,3,125197.00,'2025-09-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(14,3,135771.00,'2025-10-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(15,3,139845.00,'2025-11-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(16,3,127155.00,'2025-12-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(17,3,137817.00,'2026-01-23','2026-05-12 03:35:49','2026-05-12 03:35:49'),(18,3,139612.00,'2026-02-22','2026-05-12 03:35:49','2026-05-12 03:35:49'),(19,4,90811.00,'2025-09-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(20,4,81275.00,'2025-10-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(21,4,90955.00,'2025-11-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(22,4,87259.00,'2025-12-24','2026-05-12 03:35:49','2026-05-12 03:35:49'),(23,4,85103.00,'2026-01-23','2026-05-12 03:35:49','2026-05-12 03:35:49'),(24,4,91675.00,'2026-02-22','2026-05-12 03:35:49','2026-05-12 03:35:49'),(25,5,129326.00,'2025-09-25','2026-05-12 03:35:49','2026-05-12 03:35:49'),(26,5,124800.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(27,5,129780.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(28,5,115536.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(29,5,129635.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(30,5,119507.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(31,6,44898.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(32,6,49141.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(33,6,53499.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(34,6,41776.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(35,6,46458.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(36,6,52366.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(37,7,58164.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(38,7,70348.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(39,7,62828.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(40,7,65245.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(41,7,70777.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(42,7,62456.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(43,8,136079.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(44,8,131608.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(45,8,141814.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(46,8,130366.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(47,8,130188.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(48,8,139377.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(49,9,309124.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(50,9,300677.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(51,9,311493.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(52,9,293381.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(53,9,300796.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(54,9,308734.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(55,10,186334.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(56,10,175579.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(57,10,179330.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(58,10,172991.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(59,10,191910.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(60,10,189577.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(61,11,216713.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(62,11,208417.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(63,11,217082.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(64,11,222910.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(65,11,218376.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(66,11,220493.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(67,12,79664.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(68,12,79118.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(69,12,85032.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(70,12,72071.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(71,12,66452.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(72,12,81898.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(73,13,253758.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50'),(74,14,70149.00,'2025-09-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(75,14,74020.00,'2025-10-25','2026-05-12 03:35:50','2026-05-12 03:35:50'),(76,14,68687.00,'2025-11-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(77,14,58901.00,'2025-12-24','2026-05-12 03:35:50','2026-05-12 03:35:50'),(78,14,76708.00,'2026-01-23','2026-05-12 03:35:50','2026-05-12 03:35:50'),(79,14,55293.00,'2026-02-22','2026-05-12 03:35:50','2026-05-12 03:35:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,3,NULL,'Black','12 GB','512 GB',NULL,172000.00,170000.00,0,NULL,1,'2026-05-12 21:37:30','2026-05-12 21:37:30'),(2,3,NULL,'Brown','16 GB','1 TB',NULL,249999.00,220999.00,4,NULL,1,'2026-05-12 21:39:09','2026-05-12 21:39:09');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,14,4,'User Review','user-review-1','Great device! The Acer Aspire 5 exceeded my expectations. Highly recommend it.',7.0,NULL,NULL,NULL,1,0,0,0,0,0,0,'2026-03-24 10:05:33','2026-05-12 03:35:50'),(2,13,4,'User Review','user-review-2','Great device! The Dell XPS 15 exceeded my expectations. Highly recommend it.',9.0,NULL,NULL,NULL,1,0,0,0,0,0,0,'2026-03-24 10:05:33','2026-05-12 03:35:50'),(3,12,4,'User Review','user-review-3','Great device! The HP Pavilion 15 exceeded my expectations. Highly recommend it.',9.0,NULL,NULL,NULL,1,0,0,0,1,0,0,'2026-03-24 10:05:33','2026-05-12 11:39:29'),(4,11,4,'User Review','user-review-4','Great device! The Lenovo ThinkPad X1 Carbon Gen 11 exceeded my expectations. Highly recommend it.',7.0,NULL,NULL,NULL,1,0,0,0,0,0,0,'2026-03-24 10:05:33','2026-05-12 03:35:50'),(5,10,4,'User Review','user-review-5','Great device! The ASUS ROG Strix G16 exceeded my expectations. Highly recommend it.',8.0,NULL,NULL,NULL,1,0,0,0,0,0,0,'2026-03-24 10:05:33','2026-05-12 03:35:50'),(6,3,4,'User Review','user-review-6','Best smartphone',9.0,NULL,NULL,NULL,1,0,0,0,0,0,0,'2026-03-24 11:15:09','2026-05-12 03:35:50'),(7,6,2,'User Review','user-review-7','best',1.0,NULL,NULL,NULL,1,0,0,0,2,0,0,'2026-03-26 02:14:26','2026-05-13 06:49:38');
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
INSERT INTO `sessions` VALUES ('M0I9CbpNBuIUi9MjKZ28h10hPiI0ltOO141KJLp6',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJOREJ3Y0toTGFxMHNMQlZLbFhib0Jsb2lKd1VSOG5sQTVFUnJFY3NzIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2NvbXBhcmU/Y2F0ZWdvcnk9bW9iaWxlJmcxPWlwaG9uZS0xNS1wcm8tbWF4JmcyPW9uZXBsdXMtMTIiLCJyb3V0ZSI6ImNvbXBhcmUuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiYmRjYjViNjU0MThjZmYxYTY4OGEzNDk1MzdjNTM0YzkwNDYyOTM1ZmYwMTlkNGMwMjgxNjUwYWM5ZDE3ZDYxYSIsInRhYmxlcyI6eyIyNTg4ODc1ZDExYzQ3NTY1ODRkMDIxZGZmYTgzNTUyM19jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlkIiwibGFiZWwiOiJPcmRlciAjIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVzZXIubmFtZSIsImxhYmVsIjoiQ3VzdG9tZXIiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidG90YWxfYW1vdW50IiwibGFiZWwiOiJUb3RhbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdGF0dXMiLCJsYWJlbCI6IlN0YXR1cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwYXltZW50X21ldGhvZCIsImxhYmVsIjoiUGF5bWVudCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJEYXRlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCIyOGE0NTVjYzc4MTAzNjBlMzc2NGFmZWM1ZjI1YWZlN19jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImxvZ28iLCJsYWJlbCI6IkxvZ28iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzbHVnIiwibGFiZWwiOiJTbHVnIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNhdGVnb3JpZXMubmFtZSIsImxhYmVsIjoiQ2F0ZWdvcmllcyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJnYWRnZXRzX2NvdW50IiwibGFiZWwiOiJQcm9kdWN0cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XSwiY2MwMzQ3MzJkN2QxMWY3M2JiZmY3NzI4ZWI5NmM4YmFfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ0aXRsZSIsImxhYmVsIjoiVGl0bGUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZ2FkZ2V0Lm5hbWUiLCJsYWJlbCI6IkdhZGdldCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJyYXRpbmciLCJsYWJlbCI6IlJhdGluZyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19wdWJsaXNoZWQiLCJsYWJlbCI6IklzIHB1Ymxpc2hlZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJDcmVhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCI5MzAxNGQ4ZWJiYjgwMzU5NmY1ZGVlYmZkMTliNTUwOF9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlkIiwibGFiZWwiOiJPcmRlciAjIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImZpcnN0X25hbWUiLCJsYWJlbCI6IkN1c3RvbWVyIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Iml0ZW1zX2NvdW50IiwibGFiZWwiOiJJdGVtcyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpdGVtcy5nYWRnZXQubmFtZSIsImxhYmVsIjoiUHJvZHVjdHMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidG90YWxfYW1vdW50IiwibGFiZWwiOiJUb3RhbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwYXltZW50X21ldGhvZCIsImxhYmVsIjoiUGF5bWVudCBtZXRob2QiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfcGFpZCIsImxhYmVsIjoiUGFpZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdGF0dXMiLCJsYWJlbCI6IlN0YXR1cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJEYXRlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCI0NzNhZGJjOTZmYmI1NjNjMTllNDEzZDA1YmMwNTA2MV9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWUiLCJsYWJlbCI6Ik5hbWUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZW1haWwiLCJsYWJlbCI6IkVtYWlsIGFkZHJlc3MiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGhvbmUiLCJsYWJlbCI6IlBob25lIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InN1YmplY3QiLCJsYWJlbCI6IlN1YmplY3QiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfcmVhZCIsImxhYmVsIjoiSXMgcmVhZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJDcmVhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOmZhbHNlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6dHJ1ZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVwZGF0ZWRfYXQiLCJsYWJlbCI6IlVwZGF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfV0sImIwNWVlYWI2MGZmODhjMWYwOGY3MDk1MTAwYTYwYzliX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaW1hZ2UiLCJsYWJlbCI6IkltYWdlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWUiLCJsYWJlbCI6Ik5hbWUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiYnJhbmQubmFtZSIsImxhYmVsIjoiQnJhbmQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY2F0ZWdvcnkubmFtZSIsImxhYmVsIjoiQ2F0ZWdvcnkiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicHJpY2UiLCJsYWJlbCI6IlByaWNlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX2ZlYXR1cmVkIiwibGFiZWwiOiJGZWF0dXJlZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc190cmVuZGluZyIsImxhYmVsIjoiVHJlbmRpbmciLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidmlld3NfY291bnQiLCJsYWJlbCI6IlZpZXdzIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dfX0=',1778733503),('MKTzu5Hc6u9sIhEcmTs3Q8zczAfsN887Z6tY4O0b',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJLS2YxVlJReTg5MktjQ3BzVkU5ZzlGMlBEMEY5SW5mNVJTNUdFRVdPIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluIiwicm91dGUiOiJmaWxhbWVudC5hZG1pbi5wYWdlcy5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjUsInBhc3N3b3JkX2hhc2hfd2ViIjoiZmM4YzY5Y2UxNTU3YzAxZGY3NmRiZTQ4ZDk0ODEzOTlhYzkzM2QxMGMxMjc0MTU3MWZiYTZiZDdhOTAxMTBiNSIsInRhYmxlcyI6eyIyNTg4ODc1ZDExYzQ3NTY1ODRkMDIxZGZmYTgzNTUyM19jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlkIiwibGFiZWwiOiJPcmRlciAjIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVzZXIubmFtZSIsImxhYmVsIjoiQ3VzdG9tZXIiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidG90YWxfYW1vdW50IiwibGFiZWwiOiJUb3RhbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdGF0dXMiLCJsYWJlbCI6IlN0YXR1cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwYXltZW50X21ldGhvZCIsImxhYmVsIjoiUGF5bWVudCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJEYXRlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCI0NzNhZGJjOTZmYmI1NjNjMTllNDEzZDA1YmMwNTA2MV9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWUiLCJsYWJlbCI6Ik5hbWUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZW1haWwiLCJsYWJlbCI6IkVtYWlsIGFkZHJlc3MiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGhvbmUiLCJsYWJlbCI6IlBob25lIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InN1YmplY3QiLCJsYWJlbCI6IlN1YmplY3QiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfcmVhZCIsImxhYmVsIjoiSXMgcmVhZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJDcmVhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOmZhbHNlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6dHJ1ZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVwZGF0ZWRfYXQiLCJsYWJlbCI6IlVwZGF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfV0sImU2NDQ4MzNmNGU0ZTA4NzEyMzE1ZGE3MWIzM2ZhY2QyX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJlbWFpbCIsImxhYmVsIjoiRW1haWwgYWRkcmVzcyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJlbWFpbF92ZXJpZmllZF9hdCIsImxhYmVsIjoiRW1haWwgdmVyaWZpZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfYWRtaW4iLCJsYWJlbCI6IklzIGFkbWluIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6IkNyZWF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlZF9hdCIsImxhYmVsIjoiVXBkYXRlZCBhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9XSwiOTMwMTRkOGViYmI4MDM1OTZmNWRlZWJmZDE5YjU1MDhfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpZCIsImxhYmVsIjoiT3JkZXIgIyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJmaXJzdF9uYW1lIiwibGFiZWwiOiJDdXN0b21lciIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpdGVtc19jb3VudCIsImxhYmVsIjoiSXRlbXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXRlbXMuZ2FkZ2V0Lm5hbWUiLCJsYWJlbCI6IlByb2R1Y3RzIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRvdGFsX2Ftb3VudCIsImxhYmVsIjoiVG90YWwiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGF5bWVudF9tZXRob2QiLCJsYWJlbCI6IlBheW1lbnQgbWV0aG9kIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX3BhaWQiLCJsYWJlbCI6IlBhaWQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3RhdHVzIiwibGFiZWwiOiJTdGF0dXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiRGF0ZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XSwiYjA1ZWVhYjYwZmY4OGMxZjA4ZjcwOTUxMDBhNjBjOWJfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpbWFnZSIsImxhYmVsIjoiSW1hZ2UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJicmFuZC5uYW1lIiwibGFiZWwiOiJCcmFuZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjYXRlZ29yeS5uYW1lIiwibGFiZWwiOiJDYXRlZ29yeSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwcmljZSIsImxhYmVsIjoiUHJpY2UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfZmVhdHVyZWQiLCJsYWJlbCI6IkZlYXR1cmVkIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX3RyZW5kaW5nIiwibGFiZWwiOiJUcmVuZGluZyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ2aWV3c19jb3VudCIsImxhYmVsIjoiVmlld3MiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sIjU3OWVlMzBiMWRhODUzZWVmZjAzNzE2M2RlMTcwMDg4X2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzbHVnIiwibGFiZWwiOiJTbHVnIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImdhZGdldHNfY291bnQiLCJsYWJlbCI6IlByb2R1Y3RzIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dfX0=',1778734853),('nc7y9O0se2FCPqxPKqdmABnAE2APmtKw9FehUMmB',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8115','eyJfdG9rZW4iOiJPZjV1eEtnZklIckM0Q3NRU0VkbWRwUFJqcFF2OWRIY21paklldHNjIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1778733074);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Git Infosys - Nepal\'s Best Tech Guide Platform',NULL,NULL,'sliders/01KRFK627GGMMPTCZSHN6TYZH6.png','Hot Deal','Browse Products','/products','violet',NULL,NULL,'dark',1,1,'2026-05-12 10:44:14','2026-05-12 21:42:04'),(2,'New Test Slider',NULL,NULL,NULL,'Review','See Review','reviews/user-review-1','violet',NULL,NULL,'dark',1,2,'2026-05-12 21:50:42','2026-05-12 21:50:42');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spec_sheets`
--

LOCK TABLES `spec_sheets` WRITE;
/*!40000 ALTER TABLE `spec_sheets` DISABLE KEYS */;
INSERT INTO `spec_sheets` VALUES (1,1,'6.8\" Dynamic AMOLED 2X, 120Hz','Snapdragon 8 Gen 3','12 GB','256/512 GB/1 TB','5000 mAh, 45W','200MP + 50MP + 10MP + 12MP','Android 14, One UI 6.1','5G, Wi-Fi 7, Bluetooth 5.3, NFC','232g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(2,2,'6.7\" Super Retina XDR OLED, 120Hz','A17 Pro','8 GB','256/512 GB/1 TB','4441 mAh, 27W','48MP + 12MP + 12MP','iOS 17','5G, Wi-Fi 6E, Bluetooth 5.3, NFC','221g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(3,3,'6.73\" LTPO AMOLED, 120Hz','Snapdragon 8 Gen 3','16 GB','512 GB','5000 mAh, 90W','50MP + 50MP + 50MP','Android 14, HyperOS','5G, Wi-Fi 7, Bluetooth 5.4','227g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(4,4,'6.82\" LTPO AMOLED, 120Hz','Snapdragon 8 Gen 3','12/16 GB','256/512 GB','5400 mAh, 100W','50MP + 48MP + 64MP','Android 14, OxygenOS 14','5G, Wi-Fi 7, Bluetooth 5.4','220g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(5,5,'6.7\" LTPO OLED, 120Hz','Google Tensor G3','12 GB','128/256/512 GB/1 TB','5050 mAh, 30W','50MP + 48MP + 48MP','Android 14','5G, Wi-Fi 7, Bluetooth 5.3','213g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(6,6,'6.6\" Super AMOLED, 120Hz','Exynos 1480','8 GB','128/256 GB','5000 mAh, 25W','50MP + 12MP + 5MP','Android 14, One UI 6.1','5G, Wi-Fi 6, Bluetooth 5.3','213g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(7,7,'6.78\" AMOLED, 144Hz','Snapdragon 8 Gen 3','12/16 GB','256/512 GB','5400 mAh, 100W','50MP + 8MP + 50MP','Android 14, Realme UI 5.0','5G, Wi-Fi 7, Bluetooth 5.4','199g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(8,8,'6.1\" Super Retina XDR OLED, 60Hz','A16 Bionic','6 GB','128/256/512 GB','3877 mAh, 20W','48MP + 12MP','iOS 17','5G, Wi-Fi 6, Bluetooth 5.3','171g','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(9,9,'14.2\" Liquid Retina XDR, 120Hz','Apple M3 Pro','18 GB','512 GB SSD','17 hrs','','macOS Sonoma','Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4','1.55 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(10,10,'16\" FHD+ 165Hz IPS','Intel Core i9-13980HX','16 GB DDR5','1 TB SSD','90 Wh','','Windows 11','Wi-Fi 6E, Bluetooth 5.2','2.5 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(11,11,'14\" 2.8K OLED, 90Hz','Intel Core i7-1365P','16 GB LPDDR5','512 GB SSD','57 Wh','','Windows 11 Pro','Wi-Fi 6E, Bluetooth 5.2, 4G LTE','1.12 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(12,12,'15.6\" FHD IPS','AMD Ryzen 5 7530U','8 GB DDR4','512 GB SSD','41 Wh','','Windows 11 Home','Wi-Fi 6, Bluetooth 5.2','1.75 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(13,13,'15.6\" 3.5K OLED, 60Hz','Intel Core i7-13700H','16 GB DDR5','512 GB SSD','86 Wh','','Windows 11','Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4','1.86 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49'),(14,14,'15.6\" FHD IPS','AMD Ryzen 5 7520U','8 GB DDR5','512 GB SSD','50 Wh','','Windows 11 Home','Wi-Fi 6, Bluetooth 5.1','1.76 kg','',NULL,'2026-05-12 03:35:49','2026-05-12 03:35:49');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tech_guides`
--

LOCK TABLES `tech_guides` WRITE;
/*!40000 ALTER TABLE `tech_guides` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','kabindrawordpress@gmail.com',NULL,'$2y$12$YODaDFtdJe/dVPnCfQFnUOEFwDN7ck.3yP9hG2dV5Mm0GGlA7/gfu',1,NULL,'2026-05-12 01:14:55','2026-05-12 03:28:48'),(2,'Admin User','admin@gitinfosys.com',NULL,'$2y$12$1j1z2vMvqSyvp7ZQhMNeduQnGVRXZ.ZWFPZmUjtrf/l1boCmL4OVq',1,NULL,'2026-03-24 10:05:31','2026-05-12 06:50:30'),(3,'Tech Editor','editor@gitinfosys.com',NULL,'$2y$12$UswUif8t/xUvOYXjFfNn8.XNjf9FX5Zq8W85ZWDzSShcAWSx3IU/O',1,NULL,'2026-03-24 10:05:32','2026-05-13 23:05:18'),(4,'testuser','test@test.com',NULL,'$2y$12$UswUif8t/xUvOYXjFfNn8.XNjf9FX5Zq8W85ZWDzSShcAWSx3IU/O',0,NULL,'2026-03-24 10:05:32','2026-05-13 23:05:18'),(5,'Kabindra1','kabindrakoirala86@gmail.com',NULL,'$2y$12$UswUif8t/xUvOYXjFfNn8.XNjf9FX5Zq8W85ZWDzSShcAWSx3IU/O',1,NULL,'2026-03-24 11:59:47','2026-05-13 23:05:18'),(6,'Kabindra2','koirala@gmail.com',NULL,'$2y$12$UswUif8t/xUvOYXjFfNn8.XNjf9FX5Zq8W85ZWDzSShcAWSx3IU/O',0,NULL,'2026-03-24 12:03:26','2026-05-13 23:05:18'),(7,'kabuuu','kabu@gmail.com',NULL,'$2y$12$UswUif8t/xUvOYXjFfNn8.XNjf9FX5Zq8W85ZWDzSShcAWSx3IU/O',0,NULL,'2026-03-25 07:16:36','2026-05-13 23:05:18'),(8,'Hari lal','govinda@gmail.com',NULL,'$2y$12$JuqCLyU4XQ02XjVgqm44JecvDsgAWQ1BYZV1PCQ/6czSKaC/K.Cqi',0,NULL,'2026-05-12 06:43:07','2026-05-12 06:43:07'),(9,'Saroj Ghimire','saroj@gmail.com',NULL,'$2y$12$PHqq.pGbGb2wPNhUTPTbJuagQCfzucFvvU/lFQ8u/bevA7eM1frnq',0,NULL,'2026-05-13 08:22:03','2026-05-13 08:22:03'),(10,'Test User','qa@gitinfosys.com',NULL,'$2y$12$TiiuL.8H6eq/Ryc9rLTffOL4PBa8x4UhuNSA1oXf/U2nOq6mL3.VO',0,NULL,'2026-05-13 21:09:16','2026-05-13 21:09:16');
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

-- Dump completed on 2026-05-14 10:53:17
