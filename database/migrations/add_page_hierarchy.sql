-- Add parent page hierarchy and menu ordering to pages table
ALTER TABLE `gintec_pages` ADD COLUMN `parent_id` INT NULL AFTER `password`;
ALTER TABLE `gintec_pages` ADD COLUMN `menu_order` INT DEFAULT 0 AFTER `parent_id`;
ALTER TABLE `gintec_pages` ADD COLUMN `description` TEXT AFTER `title`;
ALTER TABLE `gintec_pages` ADD FOREIGN KEY (`parent_id`) REFERENCES `gintec_pages`(`id`) ON DELETE SET NULL;
ALTER TABLE `gintec_pages` ADD INDEX idx_parent_id (`parent_id`);
ALTER TABLE `gintec_pages` ADD INDEX idx_menu_order (`menu_order`);

-- Verify the changes
SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'gintec_pages' AND TABLE_SCHEMA = DATABASE()
ORDER BY ORDINAL_POSITION;
