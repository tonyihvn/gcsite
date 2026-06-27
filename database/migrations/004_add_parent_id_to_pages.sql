ALTER TABLE `gintec_pages` ADD COLUMN `parent_id` INT NULL AFTER `slug`;
ALTER TABLE `gintec_pages` ADD COLUMN `menu_order` INT DEFAULT 0 AFTER `parent_id`;
ALTER TABLE `gintec_pages` ADD KEY `idx_parent_id` (`parent_id`);
ALTER TABLE `gintec_pages` ADD KEY `idx_menu_order` (`menu_order`);
