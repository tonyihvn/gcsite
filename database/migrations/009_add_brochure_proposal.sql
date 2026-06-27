-- Add brochure and proposal fields to products and services tables
ALTER TABLE `gintec_products` ADD COLUMN IF NOT EXISTS `brochure_url` VARCHAR(255) AFTER `image_url`;
ALTER TABLE `gintec_products` ADD COLUMN IF NOT EXISTS `proposal_url` VARCHAR(255) AFTER `brochure_url`;

ALTER TABLE `gintec_services` ADD COLUMN IF NOT EXISTS `brochure_url` VARCHAR(255) AFTER `image_url`;
ALTER TABLE `gintec_services` ADD COLUMN IF NOT EXISTS `proposal_url` VARCHAR(255) AFTER `brochure_url`;
