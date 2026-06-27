-- Add Typography and Color Settings
ALTER TABLE `gintec_settings` ADD COLUMN `primary_color` VARCHAR(7) DEFAULT '#667eea' AFTER `value`;
ALTER TABLE `gintec_settings` ADD COLUMN `secondary_color` VARCHAR(7) DEFAULT '#764ba2' AFTER `primary_color`;
ALTER TABLE `gintec_settings` ADD COLUMN `accent_color` VARCHAR(7) DEFAULT '#667eea' AFTER `secondary_color`;
ALTER TABLE `gintec_settings` ADD COLUMN `text_color` VARCHAR(7) DEFAULT '#333333' AFTER `accent_color`;
ALTER TABLE `gintec_settings` ADD COLUMN `heading_font` VARCHAR(100) DEFAULT 'Segoe UI, Roboto, sans-serif' AFTER `text_color`;
ALTER TABLE `gintec_settings` ADD COLUMN `body_font` VARCHAR(100) DEFAULT 'Segoe UI, Roboto, sans-serif' AFTER `heading_font`;

-- Add unique key to prevent duplicate settings if not exists
CREATE TABLE IF NOT EXISTS `gintec_theme_settings` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `primary_color` VARCHAR(7) DEFAULT '#667eea',
    `secondary_color` VARCHAR(7) DEFAULT '#764ba2',
    `accent_color` VARCHAR(7) DEFAULT '#667eea',
    `text_color` VARCHAR(7) DEFAULT '#333333',
    `heading_font` VARCHAR(100) DEFAULT 'Segoe UI, Roboto, sans-serif',
    `body_font` VARCHAR(100) DEFAULT 'Segoe UI, Roboto, sans-serif',
    `heading_size` INT DEFAULT 28,
    `body_size` INT DEFAULT 14,
    `button_style` VARCHAR(50) DEFAULT 'rounded',
    `border_radius` INT DEFAULT 5,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
