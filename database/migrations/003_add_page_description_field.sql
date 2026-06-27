-- Add description field to pages table
-- GINTEC Solutions

ALTER TABLE `gintec_pages` ADD COLUMN `description` TEXT AFTER `slug`;
