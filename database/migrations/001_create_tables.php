<?php
/**
 * Database Schema Migrations
 * Run this to set up the database
 * GINTEC Solutions
 */

$migrations = [
    // Users Table
    "CREATE TABLE IF NOT EXISTS `gintec_users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `first_name` VARCHAR(100) NOT NULL,
        `last_name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(255) UNIQUE NOT NULL,
        `phone` VARCHAR(20),
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('user', 'admin', 'vendor') DEFAULT 'user',
        `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        `email_verified_at` TIMESTAMP NULL,
        `last_login` TIMESTAMP NULL,
        `remember_token` VARCHAR(255),
        `reset_token` VARCHAR(255),
        `reset_token_expires_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Products Table
    "CREATE TABLE IF NOT EXISTS `gintec_products` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE,
        `description` LONGTEXT,
        `features` LONGTEXT,
        `pricing_model` ENUM('freemium', 'subscription', 'one_time', 'custom') DEFAULT 'subscription',
        `base_price` DECIMAL(12, 2),
        `currency` VARCHAR(3) DEFAULT 'NGN',
        `category` VARCHAR(100),
        `image_url` VARCHAR(255),
        `icon` VARCHAR(255),
        `demo_url` VARCHAR(255),
        `documentation_url` VARCHAR(255),
        `website` VARCHAR(255),
        `status` ENUM('draft', 'published', 'discontinued') DEFAULT 'draft',
        `sort_order` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Services Table
    "CREATE TABLE IF NOT EXISTS `gintec_services` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE,
        `description` LONGTEXT,
        `detailed_content` LONGTEXT,
        `icon` VARCHAR(255),
        `image_url` VARCHAR(255),
        `website` VARCHAR(255),
        `base_price` DECIMAL(12, 2),
        `currency` VARCHAR(3) DEFAULT 'NGN',
        `delivery_days` INT,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `sort_order` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Subscriptions Table
    "CREATE TABLE IF NOT EXISTS `gintec_subscriptions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `product_id` INT NOT NULL,
        `plan_name` VARCHAR(100),
        `plan_price` DECIMAL(12, 2),
        `currency` VARCHAR(3) DEFAULT 'NGN',
        `billing_cycle` ENUM('monthly', 'quarterly', 'yearly', 'one_time') DEFAULT 'monthly',
        `status` ENUM('active', 'paused', 'cancelled', 'expired') DEFAULT 'active',
        `start_date` DATE,
        `end_date` DATE,
        `renewal_date` DATE,
        `auto_renew` BOOLEAN DEFAULT TRUE,
        `payment_method` VARCHAR(50),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES gintec_users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES gintec_products(id),
        INDEX idx_user_id (user_id),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Invoices Table
    "CREATE TABLE IF NOT EXISTS `gintec_invoices` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `invoice_number` VARCHAR(50) UNIQUE NOT NULL,
        `user_id` INT NOT NULL,
        `subscription_id` INT,
        `amount` DECIMAL(12, 2) NOT NULL,
        `currency` VARCHAR(3) DEFAULT 'NGN',
        `status` ENUM('draft', 'sent', 'viewed', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
        `due_date` DATE,
        `paid_date` DATE,
        `payment_method` VARCHAR(50),
        `notes` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES gintec_users(id) ON DELETE CASCADE,
        FOREIGN KEY (subscription_id) REFERENCES gintec_subscriptions(id),
        INDEX idx_user_id (user_id),
        INDEX idx_status (status),
        UNIQUE KEY uk_invoice_number (invoice_number)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Payments Table
    "CREATE TABLE IF NOT EXISTS `gintec_payments` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `invoice_id` INT,
        `user_id` INT NOT NULL,
        `amount` DECIMAL(12, 2) NOT NULL,
        `currency` VARCHAR(3) DEFAULT 'NGN',
        `payment_method` VARCHAR(50),
        `reference` VARCHAR(255),
        `status` ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',
        `metadata` JSON,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES gintec_users(id) ON DELETE CASCADE,
        FOREIGN KEY (invoice_id) REFERENCES gintec_invoices(id),
        INDEX idx_user_id (user_id),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Pages Table
    "CREATE TABLE IF NOT EXISTS `gintec_pages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE NOT NULL,
        `content` LONGTEXT,
        `meta_title` VARCHAR(255),
        `meta_description` TEXT,
        `meta_keywords` TEXT,
        `featured_image` VARCHAR(255),
        `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        `visibility` ENUM('public', 'private', 'password') DEFAULT 'public',
        `password` VARCHAR(255),
        `created_by` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Testimonials/Feedback Table
    "CREATE TABLE IF NOT EXISTS `gintec_feedbacks` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255),
        `company` VARCHAR(255),
        `title` VARCHAR(255),
        `message` LONGTEXT NOT NULL,
        `rating` INT DEFAULT 5,
        `type` ENUM('feedback', 'testimonial', 'bug_report', 'feature_request') DEFAULT 'feedback',
        `status` ENUM('new', 'reviewed', 'responded', 'archived') DEFAULT 'new',
        `admin_notes` TEXT,
        `responded_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Settings Table
    "CREATE TABLE IF NOT EXISTS `gintec_settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `key` VARCHAR(255) UNIQUE NOT NULL,
        `value` LONGTEXT,
        `type` ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
        `group` VARCHAR(100),
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_key (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Slides/Carousel Table
    "CREATE TABLE IF NOT EXISTS `gintec_slides` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255),
        `description` TEXT,
        `image_url` VARCHAR(255) NOT NULL,
        `link_url` VARCHAR(255),
        `button_text` VARCHAR(100),
        `sort_order` INT DEFAULT 0,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_order (sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Media/Uploads Table
    "CREATE TABLE IF NOT EXISTS `gintec_media` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `filename` VARCHAR(255) NOT NULL,
        `file_type` VARCHAR(50),
        `file_size` INT,
        `url` VARCHAR(255),
        `uploaded_by` INT,
        `alt_text` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (uploaded_by) REFERENCES gintec_users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Blog/News Table
    "CREATE TABLE IF NOT EXISTS `gintec_blog_posts` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE NOT NULL,
        `excerpt` TEXT,
        `content` LONGTEXT NOT NULL,
        `featured_image` VARCHAR(255),
        `author_id` INT,
        `category` VARCHAR(100),
        `tags` VARCHAR(500),
        `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        `views_count` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `published_at` TIMESTAMP NULL,
        FOREIGN KEY (author_id) REFERENCES gintec_users(id),
        UNIQUE KEY uk_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Knowledge Base/FAQ Table
    "CREATE TABLE IF NOT EXISTS `gintec_faqs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `question` VARCHAR(500) NOT NULL,
        `answer` LONGTEXT NOT NULL,
        `category` VARCHAR(100),
        `sort_order` INT DEFAULT 0,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_category (category),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // AI Chat Sessions Table
    "CREATE TABLE IF NOT EXISTS `gintec_chat_sessions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `session_id` VARCHAR(255) UNIQUE NOT NULL,
        `user_id` INT,
        `conversation_data` LONGTEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES gintec_users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Audit Logs Table
    "CREATE TABLE IF NOT EXISTS `gintec_audit_logs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT,
        `action` VARCHAR(100) NOT NULL,
        `model` VARCHAR(100),
        `model_id` INT,
        `changes` JSON,
        `ip_address` VARCHAR(45),
        `user_agent` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES gintec_users(id) ON DELETE SET NULL,
        INDEX idx_user_id (user_id),
        INDEX idx_action (action)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Team Members Table
    "CREATE TABLE IF NOT EXISTS `gintec_team_members` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `title` VARCHAR(100) NOT NULL,
        `department` VARCHAR(100),
        `bio` LONGTEXT,
        `image` VARCHAR(255),
        `email` VARCHAR(255),
        `phone` VARCHAR(20),
        `linkedin_url` VARCHAR(255),
        `twitter_url` VARCHAR(255),
        `sort_order` INT DEFAULT 0,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_sort_order (sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Partners Table
    "CREATE TABLE IF NOT EXISTS `gintec_partners` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `category` VARCHAR(100),
        `description` LONGTEXT,
        `logo` VARCHAR(255),
        `website` VARCHAR(255),
        `contact_email` VARCHAR(255),
        `contact_person` VARCHAR(255),
        `sort_order` INT DEFAULT 0,
        `featured` BOOLEAN DEFAULT 0,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_featured (featured),
        INDEX idx_sort_order (sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // About Page Content Table
    "CREATE TABLE IF NOT EXISTS `gintec_about` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `section_name` VARCHAR(100) UNIQUE NOT NULL,
        `title` VARCHAR(255),
        `content` LONGTEXT,
        `image` VARCHAR(255),
        `sort_order` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_sort_order (sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

return $migrations;
