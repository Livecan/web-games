ALTER TABLE `games`.`fo_move_options` ADD `is_next_lap` BOOLEAN NOT NULL DEFAULT FALSE AFTER `fo_position_id`;
ALTER TABLE `games_users` CHANGE `ready_state` `ready_state` CHAR(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'N' COMMENT 'R - ready,\r\nN - not ready';
UPDATE `games_users` SET `ready_state`='N';
INSERT INTO `fo_position2positions` (`id`, `fo_position_from_id`, `fo_position_to_id`, `is_left`, `is_straight`, `is_right`, `is_curve`, `is_adjacent`, `is_equal_distance`, `is_pitlane_move`) VALUES (NULL, '392', '396', '0', '0', '0', '1', '1', '0', '0');
ALTER TABLE `users` ADD `is_beta` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_admin`;
UPDATE `fo_positions` SET `order`=`order`-515 WHERE id IN (512, 513, 514, 515);
