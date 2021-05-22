ALTER TABLE `fo_position2positions` DROP FOREIGN KEY `fo_position2positions_ibfk_1`; ALTER TABLE `fo_position2positions` ADD CONSTRAINT `fo_position2positions_ibfk_1` FOREIGN KEY (`fo_position_from_id`) REFERENCES `fo_positions`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `fo_position2positions` DROP FOREIGN KEY `fo_position2positions_ibfk_2`; ALTER TABLE `fo_position2positions` ADD CONSTRAINT `fo_position2positions_ibfk_2` FOREIGN KEY (`fo_position_to_id`) REFERENCES `fo_positions`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `fo_logs` DROP `ranking`;
ALTER TABLE `fo_logs` ADD `lap` INT NULL AFTER `fo_car_id`;
ALTER TABLE `fo_cars` ADD `ranking` INT NULL AFTER `order`;