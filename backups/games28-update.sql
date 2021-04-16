ALTER TABLE `games`.`fo_move_options` ADD `is_next_lap` BOOLEAN NOT NULL DEFAULT FALSE AFTER `fo_position_id`;
ALTER TABLE `games_users` CHANGE `ready_state` `ready_state` CHAR(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'N' COMMENT 'R - ready,\r\nN - not ready';
UPDATE `games_users` SET `ready_state`='N'
