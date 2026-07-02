-- ============================================================
-- Referral Management System
-- Migration 010
-- ============================================================

-- Add referral_code column to users (run conditionally via runner script)
ALTER TABLE `gintec_users` ADD COLUMN `referral_code` VARCHAR(20) DEFAULT NULL;
ALTER TABLE `gintec_users` ADD UNIQUE INDEX `referral_code_idx` (`referral_code`);

-- Tracks every click/visit to a referral link
CREATE TABLE IF NOT EXISTS `gintec_referral_clicks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `referral_code` VARCHAR(20) NOT NULL,
    `user_id` INT NOT NULL,
    `link_type` VARCHAR(20) NOT NULL,           -- 'service' or 'product'
    `item_slug` VARCHAR(255) DEFAULT NULL,
    `item_id` INT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `referrer_url` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `rc_user_id_idx` (`user_id`),
    KEY `rc_code_idx` (`referral_code`),
    KEY `rc_created_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leads manually entered by the referrer (people they referred)
CREATE TABLE IF NOT EXISTS `gintec_referrals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,                     -- the referrer
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `interested_in` VARCHAR(255) DEFAULT NULL,  -- service/product they are interested in
    `notes` TEXT DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',     -- pending, contacted, converted, paid
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `ref_user_id_idx` (`user_id`),
    KEY `ref_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
