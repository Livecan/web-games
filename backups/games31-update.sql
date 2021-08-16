DELETE FROM `fo_position2positions` WHERE EXISTS
(SELECT 1 FROM `fo_positions` pos_1
 WHERE pos_1.id = fo_position_from_id
 AND pos_1.fo_track_id = 1
 AND pos_1.order = 81
 AND is_left)

 ALTER TABLE `fo_damages` DROP FOREIGN KEY `fo_damages_ibfk_3`; ALTER TABLE `fo_damages` ADD CONSTRAINT `fo_damages_ibfk_3` FOREIGN KEY (`fo_log_id`) REFERENCES `fo_logs`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `fo_damages` DROP FOREIGN KEY `fo_damages_ibfk_1`; ALTER TABLE `fo_damages` ADD CONSTRAINT `fo_damages_ibfk_1` FOREIGN KEY (`fo_car_id`) REFERENCES `fo_cars`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `fo_damages` DROP FOREIGN KEY `fo_damages_ibfk_3`; ALTER TABLE `fo_damages` ADD CONSTRAINT `fo_damages_ibfk_3` FOREIGN KEY (`fo_log_id`) REFERENCES `fo_logs`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `fo_damages` DROP FOREIGN KEY `fo_damages_ibfk_4`; ALTER TABLE `fo_damages` ADD CONSTRAINT `fo_damages_ibfk_4` FOREIGN KEY (`fo_move_option_id`) REFERENCES `fo_move_options`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `fo_cars` ADD `pits_state` CHAR(1) NULL DEFAULT NULL COMMENT 'P - in pits, L - long stop' AFTER `state`;

INSERT
INTO `fo_position2positions` (
    `fo_position_from_id`,
    `fo_position_to_id`,
    `is_left`,
    `is_straight`,
    `is_right`,
    `is_curve`,
    `is_adjacent`,
    `is_equal_distance`,
    `is_pitlane_move`)
SELECT MAX(f1.`id`),
    (SELECT MAX(f2.`id`) FROM `fo_positions` f2 WHERE f2.`order` = 106 AND f2.`fo_track_id` = 2),
    '0',
    '1',
    '0',
    '0',
    '1',
    '0',
    '0'
FROM `fo_positions` f1 WHERE f1.`order` = 101 AND f1.`fo_track_id` = 2;

ALTER TABLE `fo_cars` ADD `tech_pitstops_left` INT NOT NULL DEFAULT '0' AFTER `stops`;