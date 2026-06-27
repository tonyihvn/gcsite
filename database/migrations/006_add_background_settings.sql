-- Add Background Settings to Theme
ALTER TABLE `gintec_theme_settings` ADD COLUMN `bg_position` VARCHAR(50) DEFAULT 'center' AFTER `bg_overlay_color`;
ALTER TABLE `gintec_theme_settings` ADD COLUMN `bg_size` VARCHAR(50) DEFAULT 'cover' AFTER `bg_position`;
ALTER TABLE `gintec_theme_settings` ADD COLUMN `bg_repeat` VARCHAR(50) DEFAULT 'no-repeat' AFTER `bg_size`;
ALTER TABLE `gintec_theme_settings` ADD COLUMN `bg_attachment` VARCHAR(50) DEFAULT 'fixed' AFTER `bg_repeat`;
