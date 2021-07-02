ALTER TABLE `fo_cars` ADD `team` INT NOT NULL DEFAULT '1' COMMENT 'Is used to match the correct pits - numbered 1-5' AFTER `user_id`;
ALTER TABLE `fo_cars` ADD `last_pit_lap` INT NOT NULL DEFAULT '0' AFTER `stops`;
ALTER TABLE `fo_positions` ADD `team_pits` INT NULL AFTER `starting_position`;

UPDATE `fo_positions` SET `team_pits` = 1 WHERE `fo_track_id` = 1 AND `order` IN (456, 457);
UPDATE `fo_positions` SET `team_pits` = 2 WHERE `fo_track_id` = 1 AND `order` IN (454, 455);
UPDATE `fo_positions` SET `team_pits` = 3 WHERE `fo_track_id` = 1 AND `order` IN (452, 453);
UPDATE `fo_positions` SET `team_pits` = 4 WHERE `fo_track_id` = 1 AND `order` IN (450, 451);
UPDATE `fo_positions` SET `team_pits` = 5 WHERE `fo_track_id` = 1 AND `order` IN (448, 449);

UPDATE `fo_position2positions` SET `is_adjacent` = FALSE WHERE `fo_position_from_id` IN (SELECT `id` FROM `fo_positions` WHERE `fo_track_id` = 1 AND `order` = 462);

--//TODO: do Daytona pits and remove adjacency from the last pit field for returning to the track

ALTER TABLE `fo_logs` CHANGE `type` `type` CHAR(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'I - initial\\nM - move\\nD - damage\\nR - repair\\nF - finish\\nP - leaving pits';

UPDATE `fo_positions` SET `team_pits` = 1 WHERE `fo_track_id` = 2 AND `order` IN (17,18);
UPDATE `fo_positions` SET `team_pits` = 2 WHERE `fo_track_id` = 2 AND `order` IN (15,16);
UPDATE `fo_positions` SET `team_pits` = 3 WHERE `fo_track_id` = 2 AND `order` IN (13,14);
UPDATE `fo_positions` SET `team_pits` = 4 WHERE `fo_track_id` = 2 AND `order` IN (11,12);
UPDATE `fo_positions` SET `team_pits` = 5 WHERE `fo_track_id` = 2 AND `order` IN (9,10);

ALTER TABLE `fo_tracks` ADD `pitlane_exit_length` INT NOT NULL DEFAULT '1' AFTER `name`;

UPDATE `fo_tracks` SET `pitlane_exit_length`=5 WHERE `id` = 1;
