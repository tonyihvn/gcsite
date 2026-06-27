-- Add missing fields to products and services tables

-- Add icon and website to products table
ALTER TABLE `gintec_products` ADD COLUMN `icon` VARCHAR(255) AFTER `image_url`;
ALTER TABLE `gintec_products` ADD COLUMN `website` VARCHAR(255) AFTER `demo_url`;

-- Add website to services table
ALTER TABLE `gintec_services` ADD COLUMN `website` VARCHAR(255) AFTER `image_url`;
