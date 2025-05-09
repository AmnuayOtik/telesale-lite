ALTER TABLE `customers`
	ADD COLUMN `src_exten` VARCHAR(10) NULL DEFAULT NULL AFTER `full_name`;

ALTER TABLE `customers`
	ADD COLUMN `pbx_channel` VARCHAR(50) NULL DEFAULT NULL AFTER `phone_number`;