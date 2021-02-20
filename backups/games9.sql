-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 20, 2021 at 09:40 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `games`
--

-- --------------------------------------------------------

--
-- Table structure for table `dr_tokens`
--

DROP TABLE IF EXISTS `dr_tokens`;
CREATE TABLE IF NOT EXISTS `dr_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_tokens`
--

INSERT INTO `dr_tokens` (`id`, `type`, `value`) VALUES
(1, 1, 0),
(2, 1, 1),
(3, 1, 2),
(4, 1, 3),
(5, 1, 3),
(6, 2, 4),
(7, 2, 5),
(8, 2, 6),
(9, 2, 7),
(10, 2, 7),
(11, 3, 8),
(12, 3, 9),
(13, 3, 10),
(14, 3, 11),
(15, 3, 11),
(16, 4, 12),
(17, 4, 13),
(18, 4, 14),
(19, 4, 15),
(20, 4, 16);

-- --------------------------------------------------------

--
-- Table structure for table `dr_tokens_games`
--

DROP TABLE IF EXISTS `dr_tokens_games`;
CREATE TABLE IF NOT EXISTS `dr_tokens_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `dr_token_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_number` int(11) DEFAULT NULL,
  `dr_token_state_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `GAME_TOKEN_GAME_ID` (`game_id`),
  KEY `GAME_TOKEN_TOKEN_ID` (`dr_token_id`),
  KEY `PLAYER_ID` (`user_id`),
  KEY `dr_tokens_games_ibfk_4` (`dr_token_state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_tokens_games`
--

INSERT INTO `dr_tokens_games` (`id`, `game_id`, `dr_token_id`, `position`, `user_id`, `group_number`, `dr_token_state_id`) VALUES
(1, 22, 1, 1, NULL, NULL, 1),
(2, 22, 3, 2, NULL, NULL, 1),
(3, 22, 4, 3, NULL, NULL, 1),
(4, 22, 5, 4, NULL, NULL, 1),
(5, 22, 2, 5, NULL, NULL, 1),
(6, 22, 6, 6, NULL, NULL, 1),
(7, 22, 7, 7, NULL, NULL, 1),
(8, 22, 9, 8, NULL, NULL, 1),
(9, 22, 8, 9, NULL, NULL, 1),
(10, 22, 10, 10, NULL, NULL, 1),
(11, 22, 12, 11, NULL, NULL, 1),
(12, 22, 15, 12, NULL, NULL, 1),
(13, 22, 11, 13, NULL, NULL, 1),
(14, 22, 14, 14, NULL, NULL, 1),
(15, 22, 13, 15, NULL, NULL, 1),
(16, 22, 16, 16, NULL, NULL, 1),
(17, 22, 17, 17, NULL, NULL, 1),
(18, 22, 18, 18, NULL, NULL, 1),
(19, 22, 19, 19, NULL, NULL, 1),
(20, 22, 20, 20, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dr_token_states`
--

DROP TABLE IF EXISTS `dr_token_states`;
CREATE TABLE IF NOT EXISTS `dr_token_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_token_states`
--

INSERT INTO `dr_token_states` (`id`, `name`) VALUES
(1, 'ON_BOARD'),
(2, 'TAKEN'),
(3, 'CLAIMED');

-- --------------------------------------------------------

--
-- Table structure for table `dr_turns`
--

DROP TABLE IF EXISTS `dr_turns`;
CREATE TABLE IF NOT EXISTS `dr_turns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `roll` varchar(4) COLLATE utf8_bin NOT NULL,
  `returning` tinyint(1) NOT NULL DEFAULT '0',
  `taking` tinyint(1) NOT NULL DEFAULT '0',
  `dropping` tinyint(1) NOT NULL DEFAULT '0',
  `oxygen` int(11) NOT NULL DEFAULT '25',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Game_ID` (`game_id`),
  KEY `Player_ID` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_turns`
--

INSERT INTO `dr_turns` (`id`, `game_id`, `user_id`, `position`, `round`, `roll`, `returning`, `taking`, `dropping`, `oxygen`, `created`, `modified`) VALUES
(1, 22, 1, 4, 1, '3+1', 0, 0, 0, 25, '2021-02-19 09:50:00', '2021-02-19 09:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `type` text COLLATE utf8_bin NOT NULL,
  `game_state_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `game_state_id` (`game_state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `type`, `game_state_id`, `created`, `modified`) VALUES
(22, 'Droppa feature', 'Dr', 2, '2021-02-19 09:49:40', '2021-02-19 09:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `games_users`
--

DROP TABLE IF EXISTS `games_users`;
CREATE TABLE IF NOT EXISTS `games_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` int(11) DEFAULT NULL,
  `next_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Game_ID` (`game_id`),
  KEY `Player_ID` (`user_id`),
  KEY `next_user_id` (`next_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games_users`
--

INSERT INTO `games_users` (`id`, `game_id`, `user_id`, `order_number`, `next_user_id`) VALUES
(1, 22, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `game_states`
--

DROP TABLE IF EXISTS `game_states`;
CREATE TABLE IF NOT EXISTS `game_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `game_states`
--

INSERT INTO `game_states` (`id`, `name`) VALUES
(1, 'NEW'),
(2, 'STARTED'),
(3, 'FINISHED');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `is_admin`) VALUES
(1, 'Livecan', '$2y$10$uNezhrCWf7ChyHqXMzYOgOavqJIMlc5YXLhMwv3KhlH5S7dcpdqgC', 1),
(3, 'Rebekah', '$2y$10$yhMzuGA0/vQOr/NxcFQVf.6FeEnhRB6kOtVL8eBssmlu0Z1DLe61u', 0),
(7, 'Mr X', '', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dr_tokens_games`
--
ALTER TABLE `dr_tokens_games`
  ADD CONSTRAINT `dr_tokens_games_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `dr_tokens_games_ibfk_2` FOREIGN KEY (`dr_token_id`) REFERENCES `dr_tokens` (`id`),
  ADD CONSTRAINT `dr_tokens_games_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dr_tokens_games_ibfk_4` FOREIGN KEY (`dr_token_state_id`) REFERENCES `dr_token_states` (`id`);

--
-- Constraints for table `dr_turns`
--
ALTER TABLE `dr_turns`
  ADD CONSTRAINT `dr_turns_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `dr_turns_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_game_state` FOREIGN KEY (`game_state_id`) REFERENCES `game_states` (`id`);

--
-- Constraints for table `games_users`
--
ALTER TABLE `games_users`
  ADD CONSTRAINT `games_users_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `games_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `games_users_ibfk_3` FOREIGN KEY (`next_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
