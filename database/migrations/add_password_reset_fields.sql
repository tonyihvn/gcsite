-- GINTEC Solutions - Add Password Reset Fields Migration
-- Run this SQL to update your existing database

-- Add password reset fields to users table if they don't exist
ALTER TABLE `gintec_users` 
ADD COLUMN IF NOT EXISTS `reset_token` VARCHAR(255),
ADD COLUMN IF NOT EXISTS `reset_token_expires_at` TIMESTAMP NULL;

-- Create index on reset_token for faster lookups
ALTER TABLE `gintec_users` 
ADD INDEX IF NOT EXISTS idx_reset_token (`reset_token`);

-- Verify the changes
DESCRIBE `gintec_users`;
