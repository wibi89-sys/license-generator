-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: apotek_cloud
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_settings`
--

LOCK TABLES `app_settings` WRITE;
/*!40000 ALTER TABLE `app_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_branch_code_unique` (`branch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
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
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
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
  `expiration` bigint NOT NULL,
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
-- Table structure for table `cloud_inventory_batches`
--

DROP TABLE IF EXISTS `cloud_inventory_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cloud_inventory_batches` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_date` date NOT NULL,
  `stock_qty` int NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tablet',
  `buy_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `synced_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cloud_inventory_batches_branch_code_expired_date_index` (`branch_code`,`expired_date`),
  KEY `cloud_inventory_batches_branch_code_stock_qty_index` (`branch_code`,`stock_qty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_inventory_batches`
--

LOCK TABLES `cloud_inventory_batches` WRITE;
/*!40000 ALTER TABLE `cloud_inventory_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_inventory_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cloud_shifts`
--

DROP TABLE IF EXISTS `cloud_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cloud_shifts` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terminal_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cashier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opened_at` timestamp NOT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `opening_cash` decimal(15,2) NOT NULL,
  `actual_cash` decimal(15,2) DEFAULT NULL,
  `cash_difference` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cloud_shifts_branch_code_opened_at_index` (`branch_code`,`opened_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_shifts`
--

LOCK TABLES `cloud_shifts` WRITE;
/*!40000 ALTER TABLE `cloud_shifts` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cloud_transaction_items`
--

DROP TABLE IF EXISTS `cloud_transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cloud_transaction_items` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cloud_transaction_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tablet',
  `unit_price` decimal(15,2) NOT NULL,
  `hpp_unit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cloud_transaction_items_cloud_transaction_id_foreign` (`cloud_transaction_id`),
  CONSTRAINT `cloud_transaction_items_cloud_transaction_id_foreign` FOREIGN KEY (`cloud_transaction_id`) REFERENCES `cloud_transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_transaction_items`
--

LOCK TABLES `cloud_transaction_items` WRITE;
/*!40000 ALTER TABLE `cloud_transaction_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cloud_transactions`
--

DROP TABLE IF EXISTS `cloud_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cloud_transactions` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cashier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'otc',
  `subtotal` decimal(15,2) NOT NULL,
  `tuslah_embalase_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL,
  `hpp_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gross_profit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `transaction_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cloud_transactions_branch_code_transaction_date_index` (`branch_code`,`transaction_date`),
  KEY `cloud_transactions_invoice_no_index` (`invoice_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_transactions`
--

LOCK TABLES `cloud_transactions` WRITE;
/*!40000 ALTER TABLE `cloud_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_transactions` ENABLE KEYS */;
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
-- Table structure for table `license_audit_logs`
--

DROP TABLE IF EXISTS `license_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `license_audit_logs` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hwid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `license_audit_logs_license_id_foreign` (`license_id`),
  CONSTRAINT `license_audit_logs_license_id_foreign` FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `license_audit_logs`
--

LOCK TABLES `license_audit_logs` WRITE;
/*!40000 ALTER TABLE `license_audit_logs` DISABLE KEYS */;
INSERT INTO `license_audit_logs` VALUES ('01m80z1szba9xe86bnc82hqbve','01m80z0tz9c7ky3k12kk9e3g57','activated','03639c632c0a739b1767226d778ecea7d005e517ba52ad9a6cd08428a0f481b8','127.0.0.1','1.0.0','Lisensi berhasil diaktivasi pertama kali pada mesin ini.','2026-11-22 06:28:14'),('01m80z1zgm6r3e6vnwxfbknv81','01m80z0tz9c7ky3k12kk9e3g57','rejected_hwid_mismatch','91950b6655fed822fd72b26ee486d8ec5dfd6a47a0c093370a05a6052f2b9960','127.0.0.1','1.0.0','Aktivasi ditolak: HWID mismatch. Terikat ke 03639c632c0a739b1767226d778ecea7d005e517ba52ad9a6cd08428a0f481b8, dicoba dari 91950b6655fed822fd72b26ee486d8ec5dfd6a47a0c093370a05a6052f2b9960','2026-11-22 06:28:20'),('01m80z24gj8mha9r3bv8sawkvm','01m80z0tz9c7ky3k12kk9e3g57','rejected_hwid_mismatch','91950b6655fed822fd72b26ee486d8ec5dfd6a47a0c093370a05a6052f2b9960','127.0.0.1','1.0.0','Aktivasi ditolak: HWID mismatch. Terikat ke 03639c632c0a739b1767226d778ecea7d005e517ba52ad9a6cd08428a0f481b8, dicoba dari 91950b6655fed822fd72b26ee486d8ec5dfd6a47a0c093370a05a6052f2b9960','2026-11-22 06:28:25');
/*!40000 ALTER TABLE `license_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenses` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'APOTEK_POS',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pharmacy_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BR01',
  `plan_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lifetime',
  `limits_json` json NOT NULL,
  `features_json` json NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `bound_hwid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bound_hostname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_reset_count` int NOT NULL DEFAULT '2',
  `reset_used` int NOT NULL DEFAULT '0',
  `activated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `grace_days` int NOT NULL DEFAULT '14',
  `heartbeat_interval_days` int NOT NULL DEFAULT '7',
  `last_heartbeat_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `licenses_license_key_unique` (`license_key`),
  KEY `licenses_product_code_index` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licenses`
--

LOCK TABLES `licenses` WRITE;
/*!40000 ALTER TABLE `licenses` DISABLE KEYS */;
INSERT INTO `licenses` VALUES ('01m80z0tz9c7ky3k12kk9e3g57','APT-DEMO-2026-LIVE','APOTEK_POS','Apt. Pengguna Demo','Apotek Pos Sehat Sentosa','Apotek Pos Sehat Sentosa','BR01','PRO_CLOUD_SYNC','subscription','{\"max_users\": 15, \"max_branches\": 1, \"max_terminals\": 5}','{\"external_api\": false, \"sipnap_export\": true, \"label_printing\": true, \"offsite_backup\": true, \"cloud_sync_pull\": false, \"cloud_sync_push\": true, \"fingerprint_auth\": true, \"multi_branch_ops\": false, \"profit_analytics\": true}','active',NULL,'POS-KASIR-01',3,0,'2026-11-21 23:28:14','2027-11-22 08:41:00',14,7,NULL,'2026-11-21 23:27:43','2026-11-22 08:41:00'),('01m80z0tzhkkpaw4x4hyfae6m6','WRG-DEMO-2026-LIVE','RETAIL_POS','H. Sukses Mandiri','Minimarket & Toko Grosir Sukses','Minimarket & Toko Grosir Sukses','BR01','PRO_CLOUD_SYNC','subscription','{\"max_users\": 15, \"max_branches\": 1, \"max_terminals\": 5}','{\"cloud_sync\": true, \"qris_payment\": true, \"multi_cashier\": true, \"barcode_scanner\": true, \"receipt_printer\": true, \"inventory_tracking\": true, \"daily_profit_analytics\": true}','active',NULL,NULL,3,0,NULL,'2027-11-22 08:41:00',14,7,NULL,'2026-11-21 23:27:43','2026-11-22 08:41:00'),('01m80z0tzmh7ga1bb7b3xhw0vt','ABS-DEMO-2026-LIVE','ATTENDANCE_APP','HRD PT Berkah Utama','PT Berkah Utama Jaya','PT Berkah Utama Jaya','BR01','PRO_CLOUD_SYNC','subscription','{\"max_branches\": 1, \"max_scanners\": 5, \"max_employees\": 100}','{\"cloud_sync\": true, \"export_excel\": true, \"gps_geofence\": true, \"face_recognition\": false, \"shift_scheduling\": true, \"payroll_calculation\": true, \"u_are_u_fingerprint\": true}','active',NULL,NULL,3,0,NULL,'2027-11-22 08:41:00',14,7,NULL,'2026-11-21 23:27:43','2026-11-22 08:41:00'),('01m80z0tzq46m9rta598q3twe5','KLN-DEMO-2026-LIVE','CLINIC_APP','dr. Budi Raharja','Klinik Pratama Sehat Mandiri','Klinik Pratama Sehat Mandiri','BR01','ENTERPRISE','subscription','{\"max_poly\": 8, \"max_doctors\": 10, \"max_terminals\": 5}','{\"cloud_sync\": true, \"patient_queue\": true, \"icd10_diagnosis\": true, \"prescription_sync\": true, \"bpjs_pcare_integration\": true, \"electronic_medical_record\": true}','active',NULL,NULL,3,0,NULL,'2027-11-22 08:41:00',14,7,NULL,'2026-11-21 23:27:43','2026-11-22 08:41:00'),('01m81ynxs2kr812hspd8dkv8fs','APT-DEMO-PRO-2026','APOTEK_POS','Pengguna Demo Apotek','Apotek Mandiri Sehat','Apotek Mandiri Sehat','BR01','PRO_CLOUD_SYNC','subscription','{\"max_users\": 15, \"max_branches\": 1, \"max_terminals\": 5}','{\"external_api\": false, \"sipnap_export\": true, \"label_printing\": true, \"offsite_backup\": true, \"cloud_sync_pull\": false, \"cloud_sync_push\": true, \"fingerprint_auth\": true, \"multi_branch_ops\": false, \"profit_analytics\": true}','active',NULL,NULL,3,0,NULL,'2027-11-22 08:41:00',14,7,NULL,'2026-11-22 08:41:00','2026-11-22 08:41:00');
/*!40000 ALTER TABLE `licenses` ENABLE KEYS */;
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
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_22_000001_create_licenses_and_audit_logs_tables',1),(5,'2026_11_22_000002_create_cloud_sync_and_analytics_tables',1),(6,'2026_11_22_000003_add_product_code_and_business_name_to_licenses_table',1),(7,'2026_11_22_000004_create_app_settings_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('apPXpbxvIao8aebX5vZphr6IR052tInfbndpuuGf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJRWFYxY2xqQmV2YTVYZWFpMGF4c0FlOHNrMEE1Y3VFT054aXZTejhDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795329110),('APSkHZiRzvrMKJCRNSVlEbTsCHaSZucYYiLmMykK',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJ6dk9aQlpHSUhCNmNBTVJjekpkNU4yR3N1ekJSa1R4MmpKekFlTmZyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795328923),('BThsaoQSm611hCoxOwTUuGafkOhIeuvocSPVwNhs',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJpeDRjMVpQQ0ZiY3liSDZGU1FNWlJsWHZJeUxmeXZHVlV5a3JoazY0IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbGljZW5zZS1nZW5lcmF0b3IudGVzdCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbGljZW5zZS1nZW5lcmF0b3IudGVzdFwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795348548),('eDOYlL2wQWlCMqGjts0JCOhQuUORhZah9BgLqH5w',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJ6Ylh6ZWJiWTlGeUJpWTBab1JKWDBBekJ4SDByaTIyUWVoWHFzc3BkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795329740),('fH9bRndhkDfyQDmafEN3gNXSDHOzusFfXGHaCIK2',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJPSjZMYVF0RmhSQ2pwdUpSVEVXSzlUTDVwUHV4RWN6b1I2ZnpEQkxTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6ImI3NDcxMjI3ODc3N2FjZTQxNmFhODAxZjYyNWFhMTIxMjNhMTc0ZWU3YTM3NGU4MTAxYzkyNWU5NWMwNzQ0ODkifQ==',1795329476),('Ht1dTZio2msKmXI8DkFEK1wLIBUoKH1vjygax2MU',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJCT3g3eEVxUEFyWldsek05aWlBVUVLY1JCY1A5TG0xeUt1M2VDOEpYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2FkbWluXC9saWNlbnNlcyIsInJvdXRlIjoiYWRtaW4ubGljZW5zZXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795329161),('j4h33nmrUq1D3w7FT9KZhlvaZyIo45p35nbwBWXS',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJsaWo1OElUZW8wQUxLdnRFd1NHQlBnOUZSMlhtQzFWNHlodXhESm5XIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795333956),('MC13XKs7b8Flpr3XwlICzzCc0BqCGb3KqxRvXjjW',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJwWWt0akF6ZklGckJTMlhlT3hPcEhOQzFFUDhDRWpiSk4xUlIyc2dZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795359898),('nWmGTpBaIwOHrsLJn89PIRjzAOkoIvjMYyKa5Uut',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJJODFlSVUwU3ZQRmxZbUpUYnJyOEdmZTRITWNRZkhrMHcwSHpUTjBlIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbGljZW5zZS1nZW5lcmF0b3IudGVzdFwvYWRtaW5cL2xpY2Vuc2VzIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9saWNlbnNlLWdlbmVyYXRvci50ZXN0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795348547),('o49SnN3sv67ZkJrtbajq7xPEhrFPTSearNHePyOm',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJrUlpiY2VTOUtwRlFnZDE2OEhIdzhGYmxhWGFQeVhYYVN5ZEh2UjNRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbGljZW5zZXMiLCJyb3V0ZSI6ImFkbWluLmxpY2Vuc2VzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795328927),('OGQgneIcgjYBWBdBsqDt4eYtZe6m4qibD0wGsnD6',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiI5NGFkUTFOYUZNOEVucHVncDgyT24zTVFkNk5PYTJCWmNsNHhCNmVPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795329161),('sfZxUOirlGL6vIPnEysQGm60qPkGgDIZcZEr2uC5',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJZc0lKRzhBbkJkUGpVY1g2WW9UOXBnb1hmU1ZHOG90emRGTXJQUnVTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795329788),('uqc5J04WXIJNSYkSELSTFHS2ABW8AGa1boCStG0I',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJsQld5VUMzME9qRWpOVGlTNkxRcnRwS2pZdks1S2JRdnNHRThGVXN0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795360729),('wHJXJGRVL311pQYsI9PGBMCGDjpncBHiKvJeBpSD',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJmcUxQQmFwM3hiZnVibld4YXpJMFVCVUQwYWxPRk0zWVVoZFlJQVJzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3QiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795329144),('XtCuUwqDwh3AmCBACRby2vhp0jemC3Y7EFR5kYzK',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiI4WDM0WGQ3RkFodlQ3SnM4dE1GRmU5aXY0a2x5eENPSWRrMm04Q0xrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795329387),('ydHP3q464L53IIVTnDpXv0w6LfxyzBurAW1C9Dw4',NULL,'127.0.0.1','curl/8.22.0','eyJfdG9rZW4iOiJndkd5YkN0MWNxZGFiQkd5VVNFaFN0dktBb1BCNUhYY1M2eGdXbzRxIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbGljZW5zZS1nZW5lcmF0b3IudGVzdCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbGljZW5zZS1nZW5lcmF0b3IudGVzdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795329387),('ypCPSzpTuEHq62VPGNPUw6bGoVWSlG5ROdhKudL0',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI3Y2JrbFVuVTlFS0VVR2loTU1jSFoxRTFFbjlCUlFpYmJ6bG5iaW03IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xpY2Vuc2UtZ2VuZXJhdG9yLnRlc3RcL2FkbWluXC9saWNlbnNlc1wvY3JlYXRlIiwicm91dGUiOiJhZG1pbi5saWNlbnNlcy5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJiNzQ3MTIyNzg3NzdhY2U0MTZhYTgwMWY2MjVhYTEyMTIzYTE3NGVlN2EzNzRlODEwMWM5MjVlOTVjMDc0NDg5In0=',1795334224);
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
INSERT INTO `users` VALUES (1,'Administrator Hub','admin@apotek.cloud','2026-11-21 23:36:23','$2y$12$k5zt2GT0nXj.qHczvhjDbu/TmVpBIBJ4hFRscoOg1t7fjqXy1Q20W',NULL,'2026-11-21 23:36:23','2026-11-21 23:36:23');
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

-- Dump completed on 2026-11-22 22:41:10
