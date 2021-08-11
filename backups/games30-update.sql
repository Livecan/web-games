ALTER TABLE `games_users` ADD `last_request` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `next_user_id`;

ALTER TABLE `fo_games` DROP FOREIGN KEY `fo_games_ibfk_1`; ALTER TABLE `fo_games` ADD CONSTRAINT `fo_games_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `games_users` DROP FOREIGN KEY `games_users_ibfk_1`; ALTER TABLE `games_users` ADD CONSTRAINT `games_users_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `fo_cars` DROP FOREIGN KEY `fo_cars_ibfk_1`; ALTER TABLE `fo_cars` ADD CONSTRAINT `fo_cars_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
