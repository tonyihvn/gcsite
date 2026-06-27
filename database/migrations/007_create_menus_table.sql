-- Create Menus Table
CREATE TABLE IF NOT EXISTS `gintec_menus` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(255) NOT NULL,
    `url` VARCHAR(500),
    `icon` VARCHAR(100),
    `parent_id` INT,
    `menu_order` INT DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `gintec_menus`(`id`) ON DELETE CASCADE,
    KEY `menu_order_idx` (`menu_order`),
    KEY `parent_id_idx` (`parent_id`),
    KEY `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Menu Items
INSERT INTO `gintec_menus` (`label`, `url`, `icon`, `parent_id`, `menu_order`, `status`) VALUES
('Home', '/', 'fas fa-home', NULL, 1, 'active'),
('Services', '/services', 'fas fa-cogs', NULL, 2, 'active'),
('Products', '/products', 'fas fa-box', NULL, 3, 'active'),
('About', '/about', 'fas fa-info-circle', NULL, 4, 'active'),
('Team', '/team', 'fas fa-users', NULL, 5, 'active'),
('Partners', '/partners', 'fas fa-handshake', NULL, 6, 'active'),
('Blog', '/blog', 'fas fa-newspaper', NULL, 7, 'active'),
('Contact', '/contact', 'fas fa-envelope', NULL, 8, 'active');
