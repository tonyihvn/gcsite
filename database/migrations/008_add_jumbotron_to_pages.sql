-- Add Jumbotron Fields to Pages Table
ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `page_header` TEXT AFTER `description`;
ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `page_subheader` VARCHAR(500) AFTER `page_header`;
ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `header_bg_image` VARCHAR(255) AFTER `page_subheader`;
ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `header_bg_color` VARCHAR(7) DEFAULT '#f8f9fa' AFTER `header_bg_image`;
