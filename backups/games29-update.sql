ALTER TABLE `fo_cars` ADD `team` INT NOT NULL DEFAULT '1' COMMENT 'Is used to match the correct pits - numbered 1-5' AFTER `user_id`;
ALTER TABLE `fo_cars` ADD `last_pit_lap` INT NOT NULL DEFAULT '0' AFTER `stops`;
ALTER TABLE `fo_positions` ADD `team_pits` INT NULL AFTER `starting_position`;

UPDATE `fo_positions` SET `team_pits` = 1 WHERE `fo_track_id` = 1 AND `order` IN (456, 457)
UPDATE `fo_positions` SET `team_pits` = 2 WHERE `fo_track_id` = 1 AND `order` IN (454, 455)
UPDATE `fo_positions` SET `team_pits` = 3 WHERE `fo_track_id` = 1 AND `order` IN (452, 453)
UPDATE `fo_positions` SET `team_pits` = 4 WHERE `fo_track_id` = 1 AND `order` IN (450, 451)
UPDATE `fo_positions` SET `team_pits` = 5 WHERE `fo_track_id` = 1 AND `order` IN (448, 449)

--//TODO: do Daytona pits