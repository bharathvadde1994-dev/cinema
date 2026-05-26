ALTER TABLE `users`
  ADD COLUMN `email_verified_at` DATETIME DEFAULT NULL AFTER `password_hash`,
  ADD COLUMN `email_verification_code_hash` VARCHAR(255) DEFAULT NULL AFTER `email_verified_at`,
  ADD COLUMN `email_verification_expires_at` DATETIME DEFAULT NULL AFTER `email_verification_code_hash`,
  ADD COLUMN `password_reset_code_hash` VARCHAR(255) DEFAULT NULL AFTER `email_verification_expires_at`,
  ADD COLUMN `password_reset_expires_at` DATETIME DEFAULT NULL AFTER `password_reset_code_hash`;

UPDATE `users`
SET `email_verified_at` = COALESCE(`email_verified_at`, NOW())
WHERE `role` IN ('admin', 'advertiser');
