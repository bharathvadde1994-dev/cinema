-- KinoBlick cinema advertising platform
-- Import this file in phpMyAdmin or MySQL to create the initial database.
-- Compatible with MySQL 8 / MariaDB 10.4+.

CREATE DATABASE IF NOT EXISTS `cinema_booking`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `cinema_booking`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `booking_status_history`;
DROP TABLE IF EXISTS `booking_media_assignments`;
DROP TABLE IF EXISTS `media_assets`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `booking_items`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `cinema_rate_cards`;
DROP TABLE IF EXISTS `cinema_screens`;
DROP TABLE IF EXISTS `cinemas`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `companies`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `companies` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `contact_email` VARCHAR(150) NOT NULL,
  `contact_phone` VARCHAR(50) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `billing_address` VARCHAR(255) DEFAULT NULL,
  `billing_additional` VARCHAR(255) DEFAULT NULL,
  `billing_city` VARCHAR(120) DEFAULT NULL,
  `billing_state` VARCHAR(120) DEFAULT NULL,
  `billing_postcode` VARCHAR(40) DEFAULT NULL,
  `billing_country` VARCHAR(120) DEFAULT NULL,
  `delivery_address` VARCHAR(255) DEFAULT NULL,
  `delivery_additional` VARCHAR(255) DEFAULT NULL,
  `delivery_city` VARCHAR(120) DEFAULT NULL,
  `delivery_state` VARCHAR(120) DEFAULT NULL,
  `delivery_postcode` VARCHAR(40) DEFAULT NULL,
  `delivery_country` VARCHAR(120) DEFAULT NULL,
  `vat_number` VARCHAR(80) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_companies_email` (`contact_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` INT UNSIGNED DEFAULT NULL,
  `role` ENUM('advertiser', 'admin') NOT NULL DEFAULT 'advertiser',
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `salutation` VARCHAR(20) DEFAULT NULL,
  `business_type` VARCHAR(120) DEFAULT NULL,
  `auth_provider` VARCHAR(20) NOT NULL DEFAULT 'email',
  `google_sub` VARCHAR(120) DEFAULT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_email` (`email`),
  UNIQUE KEY `uk_users_google_sub` (`google_sub`),
  KEY `idx_users_company` (`company_id`),
  CONSTRAINT `fk_users_company`
    FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cinemas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `city` VARCHAR(120) NOT NULL,
  `region` VARCHAR(120) DEFAULT NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Germany',
  `address_line` VARCHAR(255) NOT NULL,
  `postal_code` VARCHAR(20) DEFAULT NULL,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `monthly_reach` INT UNSIGNED DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cinemas_slug` (`slug`),
  KEY `idx_cinemas_city` (`city`),
  KEY `idx_cinemas_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cinema_screens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cinema_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `screen_type` ENUM('standard', 'premium', 'imax', 'vip', 'other') NOT NULL DEFAULT 'standard',
  `seat_capacity` INT UNSIGNED DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_screens_cinema` (`cinema_id`),
  CONSTRAINT `fk_screens_cinema`
    FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cinema_rate_cards` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cinema_id` INT UNSIGNED NOT NULL,
  `screen_id` INT UNSIGNED DEFAULT NULL,
  `ad_length_seconds` SMALLINT UNSIGNED NOT NULL,
  `term_months` SMALLINT UNSIGNED NOT NULL,
  `play_frequency` VARCHAR(100) DEFAULT NULL,
  `monthly_price` DECIMAL(12,2) NOT NULL,
  `setup_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `currency` CHAR(3) NOT NULL DEFAULT 'EUR',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rate_card_cinema` (`cinema_id`),
  KEY `idx_rate_card_screen` (`screen_id`),
  KEY `idx_rate_card_lookup` (`cinema_id`, `ad_length_seconds`, `term_months`, `status`),
  CONSTRAINT `fk_rate_card_cinema`
    FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rate_card_screen`
    FOREIGN KEY (`screen_id`) REFERENCES `cinema_screens` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_reference` VARCHAR(30) NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `company_id` INT UNSIGNED DEFAULT NULL,
  `campaign_name` VARCHAR(180) NOT NULL,
  `term_months` SMALLINT UNSIGNED NOT NULL,
  `ad_length_seconds` SMALLINT UNSIGNED NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `play_frequency` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('draft', 'pending_payment', 'paid', 'awaiting_uploads', 'under_review', 'approved', 'live', 'completed', 'cancelled') NOT NULL DEFAULT 'draft',
  `payment_status` ENUM('pending', 'paid', 'failed', 'refunded', 'partially_refunded') NOT NULL DEFAULT 'pending',
  `media_status` ENUM('not_uploaded', 'partial', 'uploaded', 'review_required', 'approved') NOT NULL DEFAULT 'not_uploaded',
  `subtotal_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `setup_fee_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `currency` CHAR(3) NOT NULL DEFAULT 'EUR',
  `payment_plan` VARCHAR(20) NOT NULL DEFAULT 'one_time',
  `initial_payment_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `recurring_payment_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `remaining_balance_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_booking_reference` (`booking_reference`),
  KEY `idx_booking_user` (`user_id`),
  KEY `idx_booking_company` (`company_id`),
  KEY `idx_booking_status` (`status`),
  CONSTRAINT `fk_booking_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_booking_company`
    FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `booking_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `cinema_id` INT UNSIGNED NOT NULL,
  `screen_id` INT UNSIGNED DEFAULT NULL,
  `rate_card_id` INT UNSIGNED DEFAULT NULL,
  `ad_length_seconds` SMALLINT UNSIGNED NOT NULL,
  `term_months` SMALLINT UNSIGNED NOT NULL,
  `play_frequency` VARCHAR(100) DEFAULT NULL,
  `monthly_price` DECIMAL(12,2) NOT NULL,
  `setup_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `line_total` DECIMAL(12,2) NOT NULL,
  `status` ENUM('selected', 'paid', 'awaiting_uploads', 'under_review', 'approved', 'live', 'completed', 'cancelled') NOT NULL DEFAULT 'selected',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_items_booking` (`booking_id`),
  KEY `idx_booking_items_cinema` (`cinema_id`),
  KEY `idx_booking_items_screen` (`screen_id`),
  KEY `idx_booking_items_rate_card` (`rate_card_id`),
  CONSTRAINT `fk_booking_items_booking`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_booking_items_cinema`
    FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_booking_items_screen`
    FOREIGN KEY (`screen_id`) REFERENCES `cinema_screens` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_booking_items_rate_card`
    FOREIGN KEY (`rate_card_id`) REFERENCES `cinema_rate_cards` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `provider` VARCHAR(80) NOT NULL DEFAULT 'manual',
  `transaction_reference` VARCHAR(120) DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'EUR',
  `status` ENUM('pending', 'authorized', 'paid', 'failed', 'refunded', 'partially_refunded') NOT NULL DEFAULT 'pending',
  `paid_at` DATETIME DEFAULT NULL,
  `provider_payload` JSON DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payments_booking` (`booking_id`),
  KEY `idx_payments_status` (`status`),
  CONSTRAINT `fk_payments_booking`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `media_assets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `company_id` INT UNSIGNED DEFAULT NULL,
  `asset_slot` VARCHAR(40) DEFAULT NULL,
  `file_type` ENUM('video', 'image', 'document') NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `stored_name` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(120) DEFAULT NULL,
  `file_size_bytes` BIGINT UNSIGNED DEFAULT NULL,
  `storage_path` VARCHAR(255) NOT NULL,
  `review_status` ENUM('pending', 'approved', 'rejected', 'replace_requested') NOT NULL DEFAULT 'pending',
  `review_notes` TEXT DEFAULT NULL,
  `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_booking` (`booking_id`),
  KEY `idx_media_user` (`user_id`),
  KEY `idx_media_company` (`company_id`),
  KEY `idx_media_status` (`review_status`),
  CONSTRAINT `fk_media_booking`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_media_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_media_company`
    FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `booking_media_assignments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `booking_item_id` INT UNSIGNED DEFAULT NULL,
  `media_asset_id` INT UNSIGNED NOT NULL,
  `assignment_scope` ENUM('all_selected_cinemas', 'single_cinema') NOT NULL DEFAULT 'single_cinema',
  `assigned_by_user_id` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_assignment_booking` (`booking_id`),
  KEY `idx_media_assignment_item` (`booking_item_id`),
  KEY `idx_media_assignment_asset` (`media_asset_id`),
  CONSTRAINT `fk_media_assignment_booking`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_media_assignment_item`
    FOREIGN KEY (`booking_item_id`) REFERENCES `booking_items` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_media_assignment_asset`
    FOREIGN KEY (`media_asset_id`) REFERENCES `media_assets` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_media_assignment_user`
    FOREIGN KEY (`assigned_by_user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `booking_status_history` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `changed_by_user_id` INT UNSIGNED DEFAULT NULL,
  `old_status` VARCHAR(50) DEFAULT NULL,
  `new_status` VARCHAR(50) NOT NULL,
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status_history_booking` (`booking_id`),
  KEY `idx_status_history_user` (`changed_by_user_id`),
  CONSTRAINT `fk_status_history_booking`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_status_history_user`
    FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `admin_booking_deletions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_reference` VARCHAR(30) NOT NULL,
  `deleted_booking_id` INT UNSIGNED DEFAULT NULL,
  `admin_user_id` INT UNSIGNED DEFAULT NULL,
  `delete_reason` TEXT NOT NULL,
  `snapshot_json` JSON DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_admin_booking_deletions_reference` (`booking_reference`),
  KEY `idx_admin_booking_deletions_admin_user` (`admin_user_id`),
  CONSTRAINT `fk_admin_booking_deletions_admin_user`
    FOREIGN KEY (`admin_user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `companies`
(`id`, `name`, `contact_email`, `contact_phone`, `website`, `billing_address`, `billing_city`, `billing_country`, `delivery_address`, `delivery_city`, `delivery_country`, `vat_number`)
VALUES
(1, 'Nova Mobility GmbH', 'marketing@novamobility.test', '+49 30 123456', 'https://novamobility.test', 'Alexanderplatz 1', 'Berlin', 'Germany', 'Alexanderplatz 1', 'Berlin', 'Germany', 'DE123456789'),
(2, 'Fresh Market AG', 'ads@freshmarket.test', '+49 40 765432', 'https://freshmarket.test', 'Market Street 8', 'Hamburg', 'Germany', 'Market Street 8', 'Hamburg', 'Germany', 'DE987654321');

-- Local admin login for development: admin@kinoblick.test / Admin@12345
INSERT INTO `users`
(`id`, `company_id`, `role`, `first_name`, `last_name`, `email`, `password_hash`, `phone`, `status`)
VALUES
(1, NULL, 'admin', 'Cinema', 'Admin', 'admin@kinoblick.test', '$2y$10$Hy0V9hXtBGUDyEUrJgHZTuyjXO8XxvPOF.ZHW7skpWdzrNT6qh88G', '+49 30 555000', 'active'),
(2, 1, 'advertiser', 'Nina', 'Keller', 'nina@novamobility.test', '$2y$10$hpI/fS1cFwAE0Wa/VDQgHe/L82DQ1nUV6QS0AFUcwV/P34a6g2HH.', '+49 30 555111', 'active'),
(3, 2, 'advertiser', 'Jonas', 'Fischer', 'jonas@freshmarket.test', '$2y$10$hpI/fS1cFwAE0Wa/VDQgHe/L82DQ1nUV6QS0AFUcwV/P34a6g2HH.', '+49 40 555222', 'active');

INSERT INTO `cinemas`
(`id`, `name`, `slug`, `city`, `region`, `country`, `address_line`, `postal_code`, `latitude`, `longitude`, `monthly_reach`, `description`, `status`)
VALUES
(1, 'Astor Grand Cinema', 'astor-grand-cinema', 'Berlin', 'Berlin', 'Germany', 'Potsdamer Platz 7', '10785', 52.5096000, 13.3759000, 42000, 'Large premium city-centre cinema with strong business audience.', 'active'),
(2, 'Harbor Lights Multiplex', 'harbor-lights-multiplex', 'Hamburg', 'Hamburg', 'Germany', 'Kehrwieder 4', '20457', 53.5450000, 9.9867000, 28500, 'Modern multiplex close to the harbour district.', 'active'),
(3, 'Bavaria Plaza Screens', 'bavaria-plaza-screens', 'Munich', 'Bavaria', 'Germany', 'Leopoldstrasse 54', '80802', 48.1590000, 11.5864000, 24400, 'High-spend urban audience with premium lobby placement.', 'active'),
(4, 'Rhein Forum Cinema', 'rhein-forum-cinema', 'Cologne', 'North Rhine-Westphalia', 'Germany', 'Hohenzollernring 22', '50672', 50.9391000, 6.9447000, 31200, 'Strong downtown footfall and mixed-format screen network.', 'active'),
(5, 'Skyline Center Cinema', 'skyline-center-cinema', 'Frankfurt', 'Hesse', 'Germany', 'Zeil 121', '60313', 50.1139000, 8.6793000, 36800, 'Central premium site close to shopping and finance districts.', 'active'),
(6, 'Neckar Screen House', 'neckar-screen-house', 'Stuttgart', 'Baden-Wurttemberg', 'Germany', 'Konigstrasse 41', '70173', 48.7783000, 9.1805000, 29500, 'City-centre cinema with balanced weekday and weekend traffic.', 'active'),
(7, 'Riverside Film Gallery', 'riverside-film-gallery', 'Dusseldorf', 'North Rhine-Westphalia', 'Germany', 'Graf-Adolf-Platz 6', '40213', 51.2217000, 6.7800000, 30100, 'Strong reach with premium foyer branding options.', 'active'),
(8, 'Leipzig Lichtspielhaus', 'leipzig-lichtspielhaus', 'Leipzig', 'Saxony', 'Germany', 'Petersstrasse 20', '04109', 51.3397000, 12.3731000, 21400, 'Urban cinema serving a young city audience.', 'active'),
(9, 'Elbe Vista Cinema', 'elbe-vista-cinema', 'Dresden', 'Saxony', 'Germany', 'Prager Strasse 8', '01069', 51.0439000, 13.7373000, 22800, 'Downtown venue with strong commuter visibility.', 'active'),
(10, 'Ruhr Tower Screens', 'ruhr-tower-screens', 'Dortmund', 'North Rhine-Westphalia', 'Germany', 'Kampstrasse 45', '44137', 51.5142000, 7.4653000, 24700, 'Busy city centre screens with family-focused scheduling.', 'active'),
(11, 'Essen Grand Multiplex', 'essen-grand-multiplex', 'Essen', 'North Rhine-Westphalia', 'Germany', 'Limbecker Platz 1A', '45127', 51.4566000, 7.0116000, 25200, 'High-volume multiplex close to retail core.', 'active'),
(12, 'Hanover Central Screens', 'hanover-central-screens', 'Hannover', 'Lower Saxony', 'Germany', 'Bahnhofstrasse 12', '30159', 52.3759000, 9.7320000, 23600, 'Reliable weekly attendance with strong commuter footfall.', 'active'),
(13, 'Old Town Cinema Nurnberg', 'old-town-cinema-nurnberg', 'Nuremberg', 'Bavaria', 'Germany', 'Karolinenstrasse 30', '90402', 49.4521000, 11.0767000, 21900, 'Historic city-centre catchment with strong evening traffic.', 'active'),
(14, 'Waterfront Movie Hub', 'waterfront-movie-hub', 'Bremen', 'Bremen', 'Germany', 'Sodernstrasse 11', '28195', 53.0793000, 8.8017000, 20500, 'Compact cinema hub with strong local recall.', 'active'),
(15, 'Capitol Cinema Bonn', 'capitol-cinema-bonn', 'Bonn', 'North Rhine-Westphalia', 'Germany', 'Bertha-von-Suttner-Platz 2', '53111', 50.7374000, 7.1025000, 19100, 'Professional audience base with steady weekday performance.', 'active'),
(16, 'Mannheim CineForum', 'mannheim-cineforum', 'Mannheim', 'Baden-Wurttemberg', 'Germany', 'Planken O7', '68161', 49.4875000, 8.4660000, 18300, 'Regional cinema with mixed retail and commuter audience.', 'active'),
(17, 'Freiburg Altstadt Cinema', 'freiburg-altstadt-cinema', 'Freiburg', 'Baden-Wurttemberg', 'Germany', 'Kaiser-Joseph-Strasse 168', '79098', 47.9961000, 7.8494000, 17600, 'Compact premium venue serving a strong student audience.', 'active'),
(18, 'Harborlight Kiel Screens', 'harborlight-kiel-screens', 'Kiel', 'Schleswig-Holstein', 'Germany', 'Holstenstrasse 54', '24103', 54.3233000, 10.1350000, 16500, 'Northern city-centre venue with family and commuter reach.', 'active'),
(19, 'Baltic Star Cinema', 'baltic-star-cinema', 'Rostock', 'Mecklenburg-Vorpommern', 'Germany', 'Kröpeliner Strasse 70', '18055', 54.0887000, 12.1400000, 15800, 'Regional premium screens near the main shopping streets.', 'active'),
(20, 'Mainz Forum Filmhaus', 'mainz-forum-filmhaus', 'Mainz', 'Rhineland-Palatinate', 'Germany', 'Ludwigsstrasse 10', '55116', 49.9992000, 8.2740000, 17100, 'Popular city-centre cinema with balanced programming.', 'active'),
(21, 'Wiesbaden Palace Screens', 'wiesbaden-palace-screens', 'Wiesbaden', 'Hesse', 'Germany', 'Kirchgasse 52', '65183', 50.0822000, 8.2417000, 16900, 'Premium local venue with upscale catchment.', 'active'),
(22, 'Augsburg Ring Cinema', 'augsburg-ring-cinema', 'Augsburg', 'Bavaria', 'Germany', 'Annastrasse 18', '86150', 48.3668000, 10.8987000, 18200, 'Well-positioned screen network with regional reach.', 'active'),
(23, 'Ruhr Lichtspiele Bochum', 'ruhr-lichtspiele-bochum', 'Bochum', 'North Rhine-Westphalia', 'Germany', 'Kortumstrasse 70', '44787', 51.4818000, 7.2162000, 18800, 'Central Ruhrgebiet location with strong repeat visitation.', 'active'),
(24, 'Schwebebahn Cinema', 'schwebebahn-cinema', 'Wuppertal', 'North Rhine-Westphalia', 'Germany', 'Alte Freiheit 9', '42103', 51.2562000, 7.1508000, 16200, 'Compact cinema cluster near rail and shopping links.', 'active');

INSERT INTO `cinema_screens`
(`id`, `cinema_id`, `name`, `screen_type`, `seat_capacity`, `status`)
VALUES
(1, 1, 'Screen 1', 'premium', 220, 'active'),
(2, 1, 'Screen 2', 'standard', 180, 'active'),
(3, 2, 'Screen A', 'standard', 190, 'active'),
(4, 2, 'Screen B', 'premium', 240, 'active'),
(5, 3, 'Hall 1', 'vip', 160, 'active'),
(6, 4, 'Main Hall', 'premium', 260, 'active'),
(7, 5, 'Hall 1', 'premium', 210, 'active'),
(8, 5, 'Hall 2', 'standard', 180, 'active'),
(9, 6, 'Hall 1', 'premium', 200, 'active'),
(10, 6, 'Hall 2', 'standard', 170, 'active'),
(11, 7, 'Hall 1', 'premium', 220, 'active'),
(12, 7, 'Hall 2', 'standard', 175, 'active'),
(13, 8, 'Hall 1', 'standard', 160, 'active'),
(14, 8, 'Hall 2', 'standard', 145, 'active'),
(15, 9, 'Hall 1', 'premium', 180, 'active'),
(16, 9, 'Hall 2', 'standard', 150, 'active'),
(17, 10, 'Hall 1', 'premium', 190, 'active'),
(18, 10, 'Hall 2', 'standard', 160, 'active'),
(19, 11, 'Hall 1', 'premium', 200, 'active'),
(20, 11, 'Hall 2', 'standard', 180, 'active'),
(21, 12, 'Hall 1', 'standard', 170, 'active'),
(22, 12, 'Hall 2', 'premium', 190, 'active'),
(23, 13, 'Hall 1', 'standard', 165, 'active'),
(24, 13, 'Hall 2', 'vip', 140, 'active'),
(25, 14, 'Hall 1', 'standard', 155, 'active'),
(26, 14, 'Hall 2', 'standard', 130, 'active'),
(27, 15, 'Hall 1', 'premium', 180, 'active'),
(28, 15, 'Hall 2', 'standard', 150, 'active'),
(29, 16, 'Hall 1', 'standard', 160, 'active'),
(30, 16, 'Hall 2', 'standard', 150, 'active'),
(31, 17, 'Hall 1', 'premium', 170, 'active'),
(32, 17, 'Hall 2', 'standard', 140, 'active'),
(33, 18, 'Hall 1', 'standard', 150, 'active'),
(34, 18, 'Hall 2', 'standard', 135, 'active'),
(35, 19, 'Hall 1', 'standard', 145, 'active'),
(36, 19, 'Hall 2', 'premium', 165, 'active'),
(37, 20, 'Hall 1', 'standard', 150, 'active'),
(38, 20, 'Hall 2', 'premium', 170, 'active'),
(39, 21, 'Hall 1', 'premium', 175, 'active'),
(40, 21, 'Hall 2', 'standard', 145, 'active'),
(41, 22, 'Hall 1', 'standard', 160, 'active'),
(42, 22, 'Hall 2', 'premium', 180, 'active'),
(43, 23, 'Hall 1', 'standard', 165, 'active'),
(44, 23, 'Hall 2', 'standard', 155, 'active'),
(45, 24, 'Hall 1', 'standard', 150, 'active'),
(46, 24, 'Hall 2', 'premium', 170, 'active');

INSERT INTO `cinema_rate_cards`
(`id`, `cinema_id`, `screen_id`, `ad_length_seconds`, `term_months`, `play_frequency`, `monthly_price`, `setup_fee`, `currency`, `status`)
VALUES
(1, 1, 1, 30, 3, 'Before each main feature', 3100.00, 150.00, 'EUR', 'active'),
(2, 1, 1, 30, 6, 'Before each main feature', 2850.00, 150.00, 'EUR', 'active'),
(3, 2, 4, 30, 3, 'Before each main feature', 2600.00, 100.00, 'EUR', 'active'),
(4, 2, 4, 30, 6, 'Before each main feature', 2400.00, 100.00, 'EUR', 'active'),
(5, 3, 5, 15, 3, 'Before each main feature', 1800.00, 100.00, 'EUR', 'active'),
(6, 4, 6, 45, 6, 'Before each main feature', 3200.00, 150.00, 'EUR', 'active'),
(7, 5, 7, 30, 3, 'Before each main feature', 2950.00, 120.00, 'EUR', 'active'),
(8, 6, 9, 30, 3, 'Before each main feature', 2480.00, 110.00, 'EUR', 'active'),
(9, 7, 11, 30, 3, 'Before each main feature', 2660.00, 110.00, 'EUR', 'active'),
(10, 8, 13, 30, 3, 'Before each main feature', 1980.00, 90.00, 'EUR', 'active'),
(11, 9, 15, 30, 3, 'Before each main feature', 2050.00, 90.00, 'EUR', 'active'),
(12, 10, 17, 30, 3, 'Before each main feature', 2210.00, 95.00, 'EUR', 'active'),
(13, 11, 19, 30, 3, 'Before each main feature', 2280.00, 95.00, 'EUR', 'active'),
(14, 12, 22, 30, 3, 'Before each main feature', 2140.00, 90.00, 'EUR', 'active'),
(15, 13, 24, 30, 3, 'Before each main feature', 2070.00, 90.00, 'EUR', 'active'),
(16, 14, 25, 30, 3, 'Before each main feature', 1880.00, 80.00, 'EUR', 'active'),
(17, 15, 27, 30, 3, 'Before each main feature', 1930.00, 80.00, 'EUR', 'active'),
(18, 16, 29, 30, 3, 'Before each main feature', 1860.00, 80.00, 'EUR', 'active'),
(19, 17, 31, 30, 3, 'Before each main feature', 1790.00, 80.00, 'EUR', 'active'),
(20, 18, 33, 30, 3, 'Before each main feature', 1710.00, 75.00, 'EUR', 'active'),
(21, 19, 36, 30, 3, 'Before each main feature', 1680.00, 75.00, 'EUR', 'active'),
(22, 20, 38, 30, 3, 'Before each main feature', 1760.00, 75.00, 'EUR', 'active'),
(23, 21, 39, 30, 3, 'Before each main feature', 1820.00, 75.00, 'EUR', 'active'),
(24, 22, 42, 30, 3, 'Before each main feature', 1890.00, 80.00, 'EUR', 'active'),
(25, 23, 43, 30, 3, 'Before each main feature', 1840.00, 80.00, 'EUR', 'active'),
(26, 24, 46, 30, 3, 'Before each main feature', 1750.00, 75.00, 'EUR', 'active');

INSERT INTO `bookings`
(`id`, `booking_reference`, `user_id`, `company_id`, `campaign_name`, `term_months`, `ad_length_seconds`, `start_date`, `end_date`, `play_frequency`, `status`, `payment_status`, `media_status`, `subtotal_amount`, `setup_fee_total`, `discount_amount`, `tax_amount`, `grand_total`, `currency`, `notes`)
VALUES
(1, 'KB-24061', 2, 1, 'Nova Mobility Summer Push', 6, 30, '2026-07-01', '2026-12-31', 'Before each main feature', 'awaiting_uploads', 'paid', 'partial', 50400.00, 350.00, 0.00, 0.00, 50750.00, 'EUR', 'Customer requested one master trailer across all booked cinemas.'),
(2, 'KB-24012', 3, 2, 'Fresh Market Autumn Promo', 3, 30, '2026-05-15', '2026-08-14', 'Before each main feature', 'live', 'paid', 'approved', 14400.00, 0.00, 0.00, 0.00, 14400.00, 'EUR', 'Campaign currently live in two cinemas.');

INSERT INTO `booking_items`
(`id`, `booking_id`, `cinema_id`, `screen_id`, `rate_card_id`, `ad_length_seconds`, `term_months`, `play_frequency`, `monthly_price`, `setup_fee`, `line_total`, `status`)
VALUES
(1, 1, 1, 1, 2, 30, 6, 'Before each main feature', 2850.00, 150.00, 17250.00, 'awaiting_uploads'),
(2, 1, 2, 4, 4, 30, 6, 'Before each main feature', 2400.00, 100.00, 14500.00, 'awaiting_uploads'),
(3, 1, 4, 6, 6, 30, 6, 'Before each main feature', 3100.00, 100.00, 19000.00, 'awaiting_uploads'),
(4, 2, 1, 2, 1, 30, 3, 'Before each main feature', 2400.00, 0.00, 7200.00, 'live'),
(5, 2, 3, 5, 5, 30, 3, 'Before each main feature', 2400.00, 0.00, 7200.00, 'live');

INSERT INTO `payments`
(`id`, `booking_id`, `provider`, `transaction_reference`, `amount`, `currency`, `status`, `paid_at`, `provider_payload`)
VALUES
(1, 1, 'stripe', 'pi_demo_24061', 50750.00, 'EUR', 'paid', '2026-05-21 10:15:00', NULL),
(2, 2, 'stripe', 'pi_demo_24012', 14400.00, 'EUR', 'paid', '2026-05-15 14:30:00', NULL);

INSERT INTO `media_assets`
(`id`, `booking_id`, `user_id`, `company_id`, `file_type`, `original_name`, `stored_name`, `mime_type`, `file_size_bytes`, `storage_path`, `review_status`, `review_notes`, `uploaded_at`)
VALUES
(1, 1, 2, 1, 'video', 'nova-30s-master.mp4', 'media_1_nova_30s_master.mp4', 'video/mp4', 145230012, 'uploads/bookings/1/media_1_nova_30s_master.mp4', 'approved', 'Approved for all selected cinemas.', '2026-05-21 11:00:00'),
(2, 1, 2, 1, 'image', 'nova-screen-poster.jpg', 'media_2_nova_screen_poster.jpg', 'image/jpeg', 5230012, 'uploads/bookings/1/media_2_nova_screen_poster.jpg', 'replace_requested', 'Need higher resolution for one premium screen.', '2026-05-21 11:05:00'),
(3, 2, 3, 2, 'video', 'freshmarket-spot.mp4', 'media_3_freshmarket_spot.mp4', 'video/mp4', 112400001, 'uploads/bookings/2/media_3_freshmarket_spot.mp4', 'approved', 'Approved and scheduled.', '2026-05-16 09:10:00');

INSERT INTO `booking_media_assignments`
(`id`, `booking_id`, `booking_item_id`, `media_asset_id`, `assignment_scope`, `assigned_by_user_id`)
VALUES
(1, 1, NULL, 1, 'all_selected_cinemas', 2),
(2, 1, 1, 2, 'single_cinema', 2),
(3, 2, NULL, 3, 'all_selected_cinemas', 3);

INSERT INTO `booking_status_history`
(`id`, `booking_id`, `changed_by_user_id`, `old_status`, `new_status`, `comment`, `created_at`)
VALUES
(1, 1, 2, 'draft', 'paid', 'Customer completed checkout.', '2026-05-21 10:16:00'),
(2, 1, 2, 'paid', 'awaiting_uploads', 'Waiting for media package.', '2026-05-21 10:17:00'),
(3, 2, 3, 'paid', 'approved', 'Media approved for all cinemas.', '2026-05-16 10:00:00'),
(4, 2, 1, 'approved', 'live', 'Campaign activated on schedule.', '2026-05-15 18:00:00');
