-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 14, 2021 at 12:24 PM
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
  PRIMARY KEY (`id`),
  KEY `GAME_TOKEN_GAME_ID` (`game_id`),
  KEY `GAME_TOKEN_TOKEN_ID` (`dr_token_id`),
  KEY `PLAYER_ID` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_tokens_games`
--

INSERT INTO `dr_tokens_games` (`id`, `game_id`, `dr_token_id`, `position`, `user_id`) VALUES
(21, 10, 1, 0, NULL),
(22, 10, 4, 1, NULL),
(23, 10, 3, 2, NULL),
(24, 10, 5, 3, NULL),
(25, 10, 2, 4, NULL),
(26, 10, 6, 5, NULL),
(27, 10, 8, 6, NULL),
(28, 10, 9, 7, NULL),
(29, 10, 7, 8, NULL),
(30, 10, 10, 9, NULL),
(31, 10, 14, 10, NULL),
(32, 10, 12, 11, NULL),
(33, 10, 11, 12, NULL),
(34, 10, 13, 13, NULL),
(35, 10, 15, 14, NULL),
(36, 10, 16, 15, NULL),
(37, 10, 18, 16, NULL),
(38, 10, 17, 17, NULL),
(39, 10, 19, 18, NULL),
(40, 10, 20, 19, NULL),
(41, 11, 5, 1, NULL),
(42, 11, 1, 2, NULL),
(43, 11, 4, 3, NULL),
(44, 11, 3, 4, NULL),
(45, 11, 2, 5, NULL),
(46, 11, 10, 6, NULL),
(47, 11, 8, 7, NULL),
(48, 11, 7, 8, NULL),
(49, 11, 9, 9, NULL),
(50, 11, 6, 10, NULL),
(51, 11, 13, 11, NULL),
(52, 11, 11, 12, NULL),
(53, 11, 12, 13, NULL),
(54, 11, 15, 14, NULL),
(55, 11, 14, 15, NULL),
(56, 11, 16, 16, NULL),
(57, 11, 17, 17, NULL),
(58, 11, 18, 18, NULL),
(59, 11, 19, 19, NULL),
(60, 11, 20, 20, NULL);

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
  `roll` varchar(3) COLLATE utf8_bin NOT NULL,
  `returning` tinyint(1) NOT NULL DEFAULT '0',
  `taking` tinyint(1) NOT NULL DEFAULT '0',
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

INSERT INTO `dr_turns` (`id`, `game_id`, `user_id`, `position`, `round`, `roll`, `returning`, `taking`, `oxygen`, `created`, `modified`) VALUES
(1, 11, 1, 4, 1, '2+2', 0, 0, 25, '2021-02-14 14:32:29', '2021-02-14 14:32:29');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `type`, `game_state_id`, `created`, `modified`) VALUES
(10, '', 'X', 2, '2021-02-13 20:54:03', '2021-02-14 10:03:07'),
(11, 'NewGame', 'Dr', 2, '2021-02-14 10:10:42', '2021-02-14 10:10:54');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games_users`
--

INSERT INTO `games_users` (`id`, `game_id`, `user_id`, `order_number`, `next_user_id`) VALUES
(1, 10, 7, 0, NULL),
(2, 11, 1, 0, NULL);

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
(3, 'Rebekah', '', 0),
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
  ADD CONSTRAINT `dr_tokens_games_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
