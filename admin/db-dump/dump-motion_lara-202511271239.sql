-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: motion_lara
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB


--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;


CREATE TABLE `amenities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `booking_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_advance_booking_days` int(11) NOT NULL DEFAULT 30,
  `min_advance_booking_hours` int(11) NOT NULL DEFAULT 2,
  `requires_approval` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `available_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_days`)),
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `max_booking_duration_hours` int(11) DEFAULT NULL,
  `terms_and_conditions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amenities_slug_unique` (`slug`),
  KEY `amenities_colony_id_foreign` (`colony_id`),
  CONSTRAINT `amenities_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

LOCK TABLES `amenities` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `booking_slots`
--

DROP TABLE IF EXISTS `booking_slots`;
CREATE TABLE `booking_slots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `amenity_id` bigint(20) unsigned NOT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_slots_amenity_id_foreign` (`amenity_id`),
  KEY `booking_slots_colony_id_foreign` (`colony_id`),
  CONSTRAINT `booking_slots_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_slots_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_slots`
--

LOCK TABLES `booking_slots` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `amenity_id` bigint(20) unsigned NOT NULL,
  `resident_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `slot_id` bigint(20) unsigned DEFAULT NULL,
  `booking_number` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `number_of_guests` int(11) NOT NULL DEFAULT 1,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_amenity_id_foreign` (`amenity_id`),
  KEY `bookings_resident_id_foreign` (`resident_id`),
  KEY `bookings_unit_id_foreign` (`unit_id`),
  KEY `bookings_slot_id_foreign` (`slot_id`),
  KEY `bookings_approved_by_foreign` (`approved_by`),
  KEY `bookings_colony_id_foreign` (`colony_id`),
  CONSTRAINT `bookings_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `booking_slots` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `charges`
--

DROP TABLE IF EXISTS `charges`;
CREATE TABLE `charges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('fixed','per_sqft','per_unit') NOT NULL DEFAULT 'fixed',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `per_sqft_rate` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `charges_slug_unique` (`slug`),
  KEY `charges_colony_id_foreign` (`colony_id`),
  CONSTRAINT `charges_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `charges`
--

LOCK TABLES `charges` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `colonies`
--

DROP TABLE IF EXISTS `colonies`;
CREATE TABLE `colonies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('active','suspended','expired','trial') NOT NULL DEFAULT 'trial',
  `expires_at` date DEFAULT NULL,
  `max_units` int(11) NOT NULL DEFAULT 100,
  `max_residents` int(11) NOT NULL DEFAULT 500,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colonies_code_unique` (`code`),
  KEY `colonies_plan_id_foreign` (`plan_id`),
  CONSTRAINT `colonies_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `colonies`
--

LOCK TABLES `colonies` WRITE;

INSERT INTO `colonies` VALUES (1,'Demo Colony','DEMO001','123 Main Street','Mumbai','Maharashtra','400001','+91-1234567890','demo@colony.com',NULL,1,'active',NULL,100,500,NULL,'2025-11-22 07:47:35','2025-11-22 07:47:35',NULL);

UNLOCK TABLES;

--
-- Table structure for table `complaint_categories`
--

DROP TABLE IF EXISTS `complaint_categories`;


CREATE TABLE `complaint_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaint_categories_slug_unique` (`slug`),
  KEY `complaint_categories_colony_id_foreign` (`colony_id`),
  CONSTRAINT `complaint_categories_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `complaint_categories`
--

LOCK TABLES `complaint_categories` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `complaint_comments`
--

DROP TABLE IF EXISTS `complaint_comments`;


CREATE TABLE `complaint_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `comment` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_comments_complaint_id_foreign` (`complaint_id`),
  KEY `complaint_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `complaint_comments_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaint_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `complaint_comments`
--

LOCK TABLES `complaint_comments` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;


CREATE TABLE `complaints` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `resident_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed','rejected') NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `resolved_at` date DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_ticket_number_unique` (`ticket_number`),
  KEY `complaints_resident_id_foreign` (`resident_id`),
  KEY `complaints_unit_id_foreign` (`unit_id`),
  KEY `complaints_assigned_to_foreign` (`assigned_to`),
  KEY `complaints_category_id_foreign` (`category_id`),
  KEY `complaints_colony_id_foreign` (`colony_id`),
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;


CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_slug_unique` (`slug`),
  KEY `expense_categories_colony_id_foreign` (`colony_id`),
  CONSTRAINT `expense_categories_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;


CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','cheque','online','bank_transfer') NOT NULL DEFAULT 'cash',
  `receipt_number` varchar(255) DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_vendor_id_foreign` (`vendor_id`),
  KEY `expenses_colony_id_foreign` (`colony_id`),
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;


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


--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;


CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_11_21_121537_create_roles_table',2),(6,'2025_11_21_121546_create_permissions_table',2),(7,'2025_11_21_121557_create_role_user_table',2),(8,'2025_11_21_121605_create_permission_role_table',2),(9,'2025_11_22_120306_create_units_table',3),(10,'2025_11_22_120312_create_residents_table',3),(11,'2025_11_22_120317_create_move_in_out_logs_table',3),(12,'2025_11_22_120321_create_charges_table',3),(16,'2025_11_22_120326_create_monthly_bills_table',4),(17,'2025_11_22_120330_create_payments_table',4),(18,'2025_11_22_120335_create_expenses_table',5),(19,'2025_11_22_120339_create_expense_categories_table',5),(20,'2025_11_22_120343_create_vendors_table',5),(21,'2025_11_22_120349_create_complaints_table',6),(22,'2025_11_22_120353_create_complaint_comments_table',6),(23,'2025_11_22_120358_create_complaint_categories_table',6),(24,'2025_11_22_120403_create_visitors_table',6),(25,'2025_11_22_120407_create_visitor_logs_table',6),(26,'2025_11_22_120410_create_vehicles_table',6),(27,'2025_11_22_120415_create_amenities_table',6),(28,'2025_11_22_120419_create_booking_slots_table',6),(29,'2025_11_22_120428_create_bookings_table',6),(30,'2025_11_22_120431_create_notices_table',6),(31,'2025_11_22_120434_create_society_settings_table',6),(32,'2025_11_22_124552_add_foreign_keys_to_expenses_table',6),(33,'2025_11_22_124600_add_foreign_keys_to_complaints_table',6),(34,'2025_11_22_124646_add_foreign_keys_to_complaints_table',6),(35,'2025_11_22_125444_create_colonies_table',7),(36,'2025_11_22_125447_create_subscription_plans_table',7),(37,'2025_11_22_125451_create_user_colonies_table',7),(38,'2025_11_22_125500_add_colony_id_to_tenant_tables',7),(39,'2025_11_22_125510_add_colony_context_to_users_table',7),(40,'2025_11_22_125515_add_colony_id_to_tenant_tables',7),(41,'2025_11_22_125520_update_roles_table_for_multitenancy',7),(42,'2025_11_22_125523_add_colony_context_to_users_table',7),(43,'2025_11_22_125532_update_roles_table_for_multitenancy',7),(44,'2025_11_22_125700_add_plan_foreign_key_to_colonies_table',7),(45,'2025_11_22_130044_add_plan_foreign_key_to_colonies_table',7);

UNLOCK TABLES;

--
-- Table structure for table `monthly_bills`
--

DROP TABLE IF EXISTS `monthly_bills`;


CREATE TABLE `monthly_bills` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `resident_id` bigint(20) unsigned DEFAULT NULL,
  `bill_number` varchar(255) NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `month` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pending_amount` decimal(10,2) NOT NULL,
  `late_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','partial','paid','overdue') NOT NULL DEFAULT 'pending',
  `charge_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`charge_details`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `monthly_bills_bill_number_unique` (`bill_number`),
  KEY `monthly_bills_unit_id_foreign` (`unit_id`),
  KEY `monthly_bills_resident_id_foreign` (`resident_id`),
  KEY `monthly_bills_colony_id_foreign` (`colony_id`),
  CONSTRAINT `monthly_bills_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monthly_bills_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE SET NULL,
  CONSTRAINT `monthly_bills_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `monthly_bills`
--

LOCK TABLES `monthly_bills` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `move_in_out_logs`
--

DROP TABLE IF EXISTS `move_in_out_logs`;


CREATE TABLE `move_in_out_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `resident_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `type` enum('move_in','move_out') NOT NULL,
  `date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `move_in_out_logs_resident_id_foreign` (`resident_id`),
  KEY `move_in_out_logs_unit_id_foreign` (`unit_id`),
  KEY `move_in_out_logs_created_by_foreign` (`created_by`),
  KEY `move_in_out_logs_colony_id_foreign` (`colony_id`),
  CONSTRAINT `move_in_out_logs_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `move_in_out_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `move_in_out_logs_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `move_in_out_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `move_in_out_logs`
--

LOCK TABLES `move_in_out_logs` WRITE;
INSERT INTO `move_in_out_logs` VALUES (1,NULL,1,1,'move_in','2025-12-12',NULL,4,'2025-11-26 07:23:23','2025-11-26 07:23:23');

UNLOCK TABLES;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;


CREATE TABLE `notices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('general','maintenance','meeting','emergency','event') NOT NULL DEFAULT 'general',
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `publish_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `send_notification` tinyint(1) NOT NULL DEFAULT 1,
  `target_audience` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_audience`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notices_created_by_foreign` (`created_by`),
  KEY `notices_colony_id_foreign` (`colony_id`),
  CONSTRAINT `notices_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `notices`
--

LOCK TABLES `notices` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;


CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;


CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `bill_id` bigint(20) unsigned DEFAULT NULL,
  `resident_id` bigint(20) unsigned DEFAULT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','cheque','online','bank_transfer','upi','card') NOT NULL DEFAULT 'cash',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_gateway` varchar(255) DEFAULT NULL,
  `gateway_response` text DEFAULT NULL,
  `payment_date` date NOT NULL,
  `cheque_number` varchar(255) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  KEY `payments_bill_id_foreign` (`bill_id`),
  KEY `payments_resident_id_foreign` (`resident_id`),
  KEY `payments_unit_id_foreign` (`unit_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  KEY `payments_colony_id_foreign` (`colony_id`),
  CONSTRAINT `payments_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `monthly_bills` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;


CREATE TABLE `permission_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
INSERT INTO `permission_role` VALUES (1,4,1,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(2,3,1,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(3,2,1,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(4,1,1,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(5,4,2,'2025-11-21 06:49:07','2025-11-21 06:49:07'),(6,1,2,'2025-11-21 06:49:07','2025-11-21 06:49:07'),(7,1,3,'2025-11-21 06:49:07','2025-11-21 06:49:07');

UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;


CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
INSERT INTO `permissions` VALUES (1,'View Dashboard','view-dashboard','dashboard',NULL,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(2,'Manage Users','manage-users','users',NULL,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(3,'Manage Roles','manage-roles','roles',NULL,'2025-11-21 06:49:06','2025-11-21 06:49:06'),(4,'Manage Content','manage-content','content',NULL,'2025-11-21 06:49:06','2025-11-21 06:49:06');

UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;


CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `residents`
--

DROP TABLE IF EXISTS `residents`;


CREATE TABLE `residents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `alternate_phone` varchar(255) DEFAULT NULL,
  `type` enum('owner','tenant','family_member') NOT NULL DEFAULT 'owner',
  `status` enum('active','inactive','moved_out') NOT NULL DEFAULT 'active',
  `date_of_birth` date DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `move_in_date` date DEFAULT NULL,
  `move_out_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `residents_email_unique` (`email`),
  KEY `residents_unit_id_foreign` (`unit_id`),
  KEY `residents_colony_id_foreign` (`colony_id`),
  CONSTRAINT `residents_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `residents_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `residents`
--

LOCK TABLES `residents` WRITE;
INSERT INTO `residents` VALUES (1,NULL,1,'C.L. Mehra','kaushal2314503703@mujonline.edu.in','8387919175',NULL,'owner','active','2025-12-12','444444444444','BYZPM7289P','Same','Kam','8387919175','2025-12-12',NULL,'No Notes','2025-11-26 07:23:23','2025-11-26 07:23:23',NULL);

UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;


CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_role_id_user_id_unique` (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
INSERT INTO `role_user` VALUES (1,1,1,'2025-11-21 07:04:15','2025-11-21 07:04:15'),(2,1,2,'2025-11-22 06:16:33','2025-11-22 06:16:33'),(3,2,2,'2025-11-22 06:16:33','2025-11-22 06:16:33'),(4,3,2,'2025-11-22 06:16:33','2025-11-22 06:16:33');

UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;


CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `scope` enum('global','colony') NOT NULL DEFAULT 'colony',
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_colony_id_foreign` (`colony_id`),
  CONSTRAINT `roles_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
INSERT INTO `roles` VALUES (1,'Administrator','admin','Full system access',1,'colony',NULL,'2025-11-21 06:49:06','2025-11-22 07:17:25'),(2,'Editor','editor','Content management access',0,'colony',NULL,'2025-11-21 06:49:06','2025-11-22 07:17:25'),(3,'Viewer','viewer','Read-only dashboard access',0,'colony',NULL,'2025-11-21 06:49:07','2025-11-22 07:17:25'),(4,'Colony Admin','colony_admin','Full access to colony management',1,'colony',1,'2025-11-22 07:47:35','2025-11-22 07:47:35'),(5,'Manager','manager','Manager access',0,'colony',1,'2025-11-22 07:47:35','2025-11-22 07:47:35'),(6,'Accountant','accountant','Billing and accounting access',0,'colony',1,'2025-11-22 07:47:35','2025-11-22 07:47:35');

UNLOCK TABLES;

--
-- Table structure for table `society_settings`
--

DROP TABLE IF EXISTS `society_settings`;


CREATE TABLE `society_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `society_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `bank_name` text DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_ifsc` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `payment_gateway_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_gateway_config`)),
  `sms_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sms_config`)),
  `email_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`email_config`)),
  `notification_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `society_settings_colony_id_foreign` (`colony_id`),
  CONSTRAINT `society_settings_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `society_settings`
--

LOCK TABLES `society_settings` WRITE;
INSERT INTO `society_settings` VALUES (1,1,'Society Name','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-25 06:15:30','2025-11-25 06:15:30');

UNLOCK TABLES;

--
-- Table structure for table `subscription_plans`
--

DROP TABLE IF EXISTS `subscription_plans`;


CREATE TABLE `subscription_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` varchar(255) NOT NULL DEFAULT 'monthly',
  `max_units` int(11) NOT NULL DEFAULT 100,
  `max_residents` int(11) NOT NULL DEFAULT 500,
  `max_staff` int(11) NOT NULL DEFAULT 10,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `trial_days` int(11) NOT NULL DEFAULT 14,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `subscription_plans`
--

LOCK TABLES `subscription_plans` WRITE;
INSERT INTO `subscription_plans` VALUES (1,'Basic Plan','basic','Basic plan for small colonies',999.00,'monthly',100,500,10,NULL,1,14,'2025-11-22 07:47:35','2025-11-22 07:47:35'),(2,'Premium Plan','premium','Premium plan for large colonies',2499.00,'monthly',500,2500,50,NULL,1,30,'2025-11-22 07:47:35','2025-11-22 07:47:35');

UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;


CREATE TABLE `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `unit_number` varchar(255) NOT NULL,
  `block` varchar(255) DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `type` enum('flat','shop','office','parking') NOT NULL DEFAULT 'flat',
  `area` decimal(10,2) DEFAULT NULL,
  `status` enum('occupied','vacant','under_construction') NOT NULL DEFAULT 'vacant',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_unit_number_unique` (`unit_number`),
  KEY `units_colony_id_foreign` (`colony_id`),
  CONSTRAINT `units_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
INSERT INTO `units` VALUES (1,1,'1','A','G','flat',1000.00,'occupied','Dummy','2025-11-26 07:21:31','2025-11-26 07:21:31',NULL);

UNLOCK TABLES;

--
-- Table structure for table `user_colonies`
--

DROP TABLE IF EXISTS `user_colonies`;


CREATE TABLE `user_colonies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `colony_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_colonies_user_id_colony_id_unique` (`user_id`,`colony_id`),
  KEY `user_colonies_colony_id_foreign` (`colony_id`),
  KEY `user_colonies_role_id_foreign` (`role_id`),
  CONSTRAINT `user_colonies_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_colonies_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_colonies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `user_colonies`
--

LOCK TABLES `user_colonies` WRITE;
INSERT INTO `user_colonies` VALUES (1,4,1,4,1,'2025-11-22 07:47:36','2025-11-22 07:55:02');
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;


CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `current_colony_id` bigint(20) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_current_colony_id_foreign` (`current_colony_id`),
  CONSTRAINT `users_current_colony_id_foreign` FOREIGN KEY (`current_colony_id`) REFERENCES `colonies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'Super Admin','admin@example.com',0,NULL,NULL,'$2y$10$D9cgb/wnAjj5h.zqu0KTkO8V9DBc57nt6de91JU9ATArA8WZLVAGm',NULL,'2025-11-21 07:04:15','2025-11-21 07:04:15'),(2,'KAUSHAL MEHRA','kaushal.mehra@motion.ac.in',0,NULL,NULL,'$2y$10$2RT.56VCdK9J/WcjQXrFlu8PwyuM/dUSVOnMKWvsMynk2XS1PYDBK',NULL,'2025-11-22 06:16:33','2025-11-22 06:16:33'),(3,'Super Admin','superadmin@example.com',1,1,NULL,'$2y$10$nA0uvRZlZlTnDCpJuGKc7u44QDncNm.44.EOdZSpPkrevqexntKky',NULL,'2025-11-22 07:47:35','2025-11-25 05:15:10'),(4,'Colony Admin','admin@demo.com',0,1,NULL,'$2y$10$Dl5ucYPx7.cTnAiOdUf4QuitrbRYamWtDC8O8EFc5LoUCl7jlirMa',NULL,'2025-11-22 07:47:35','2025-11-22 07:47:35');

UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;


CREATE TABLE `vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `resident_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `vehicle_number` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `parking_type` enum('covered','open','basement') DEFAULT NULL,
  `parking_slot_number` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_vehicle_number_unique` (`vehicle_number`),
  KEY `vehicles_resident_id_foreign` (`resident_id`),
  KEY `vehicles_unit_id_foreign` (`unit_id`),
  KEY `vehicles_colony_id_foreign` (`colony_id`),
  CONSTRAINT `vehicles_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vehicles_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vehicles_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;


CREATE TABLE `vendors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `alternate_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_colony_id_foreign` (`colony_id`),
  CONSTRAINT `vendors_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `visitor_logs`
--

DROP TABLE IF EXISTS `visitor_logs`;


CREATE TABLE `visitor_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `visitor_id` bigint(20) unsigned DEFAULT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `visitor_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `entry_time` datetime NOT NULL,
  `exit_time` datetime DEFAULT NULL,
  `entry_verified_by` bigint(20) unsigned DEFAULT NULL,
  `exit_verified_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_logs_visitor_id_foreign` (`visitor_id`),
  KEY `visitor_logs_unit_id_foreign` (`unit_id`),
  KEY `visitor_logs_entry_verified_by_foreign` (`entry_verified_by`),
  KEY `visitor_logs_exit_verified_by_foreign` (`exit_verified_by`),
  KEY `visitor_logs_colony_id_foreign` (`colony_id`),
  CONSTRAINT `visitor_logs_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitor_logs_entry_verified_by_foreign` FOREIGN KEY (`entry_verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `visitor_logs_exit_verified_by_foreign` FOREIGN KEY (`exit_verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `visitor_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitor_logs_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `visitor_logs`
--

LOCK TABLES `visitor_logs` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;


CREATE TABLE `visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `colony_id` bigint(20) unsigned DEFAULT NULL,
  `resident_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `number_of_visitors` int(11) NOT NULL DEFAULT 1,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `otp` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `expected_arrival` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitors_resident_id_foreign` (`resident_id`),
  KEY `visitors_unit_id_foreign` (`unit_id`),
  KEY `visitors_colony_id_foreign` (`colony_id`),
  CONSTRAINT `visitors_colony_id_foreign` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitors_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitors_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
UNLOCK TABLES;

--
-- Dumping routines for database 'motion_lara'
--


-- Dump completed on 2025-11-27 12:39:47
