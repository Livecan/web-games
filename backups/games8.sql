-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 16, 2021 at 03:29 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_tokens_games`
--

INSERT INTO `dr_tokens_games` (`id`, `game_id`, `dr_token_id`, `position`, `user_id`, `group_number`, `dr_token_state_id`) VALUES
(81, 13, 3, 1, NULL, NULL, 1),
(82, 13, 1, 2, NULL, NULL, 1),
(83, 13, 5, 3, NULL, NULL, 1),
(84, 13, 4, 4, NULL, NULL, 1),
(85, 13, 2, 5, NULL, NULL, 1),
(86, 13, 10, 6, NULL, NULL, 1),
(87, 13, 6, 7, NULL, NULL, 1),
(88, 13, 7, NULL, 1, 26, 2),
(89, 13, 8, 9, NULL, NULL, 1),
(90, 13, 9, 10, NULL, NULL, 1),
(91, 13, 13, NULL, 1, 24, 2),
(92, 13, 15, 12, NULL, NULL, 1),
(93, 13, 11, 13, NULL, NULL, 1),
(94, 13, 12, 14, NULL, NULL, 1),
(95, 13, 14, NULL, 3, 25, 2),
(96, 13, 16, 16, NULL, NULL, 1),
(97, 13, 17, 17, NULL, NULL, 1),
(98, 13, 18, 18, NULL, NULL, 1),
(99, 13, 19, 19, NULL, NULL, 1),
(100, 13, 20, 20, NULL, NULL, 1),
(101, 14, 1, 1, NULL, NULL, 1),
(102, 14, 3, NULL, 3, 45, 2),
(103, 14, 5, 3, NULL, NULL, 1),
(104, 14, 4, 4, NULL, NULL, 1),
(105, 14, 2, 5, NULL, NULL, 1),
(106, 14, 8, 6, NULL, NULL, 1),
(107, 14, 7, 7, NULL, NULL, 1),
(108, 14, 6, 8, NULL, NULL, 1),
(109, 14, 9, 9, NULL, NULL, 1),
(110, 14, 10, NULL, 3, 35, 2),
(111, 14, 14, 11, NULL, NULL, 1),
(112, 14, 15, 12, NULL, NULL, 1),
(113, 14, 13, 13, NULL, NULL, 1),
(114, 14, 11, NULL, 1, 44, 2),
(115, 14, 12, 15, NULL, NULL, 1),
(116, 14, 16, 16, NULL, NULL, 1),
(117, 14, 17, 17, NULL, NULL, 1),
(118, 14, 18, 18, NULL, NULL, 1),
(119, 14, 19, 19, NULL, NULL, 1),
(120, 14, 20, 20, NULL, NULL, 1);

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
  `oxygen` int(11) NOT NULL DEFAULT '25',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Game_ID` (`game_id`),
  KEY `Player_ID` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_turns`
--

INSERT INTO `dr_turns` (`id`, `game_id`, `user_id`, `position`, `round`, `roll`, `returning`, `taking`, `oxygen`, `created`, `modified`) VALUES
(15, 12, 1, 3, 1, '1+2', 0, 0, 25, '2021-02-16 17:31:57', '2021-02-16 17:31:57'),
(16, 12, 3, 6, 1, '3+2', 0, 0, 25, '2021-02-16 14:40:49', '2021-02-16 14:40:49'),
(17, 12, 3, 11, 1, '3+2', 0, 0, 25, '2021-02-16 14:41:18', '2021-02-16 14:41:18'),
(18, 12, 3, 16, 1, '3+2', 0, 0, 25, '2021-02-16 14:41:23', '2021-02-16 14:41:23'),
(19, 12, 3, 20, 1, '1+3', 0, 0, 25, '2021-02-16 14:41:34', '2021-02-16 14:41:34'),
(20, 13, 1, 3, 1, '1+2', 0, 0, 25, '2021-02-16 14:55:44', '2021-02-16 14:55:44'),
(21, 13, 3, 6, 1, '2+3', 0, 0, 25, '2021-02-16 15:00:47', '2021-02-16 15:00:47'),
(22, 13, 1, 8, 1, '2+2', 0, 0, 25, '2021-02-16 15:01:00', '2021-02-16 15:01:00'),
(23, 13, 3, 10, 1, '2+1', 0, 0, 25, '2021-02-16 15:02:21', '2021-02-16 15:02:21'),
(24, 13, 1, 11, 1, '1+1', 1, 0, 25, '2021-02-16 15:02:32', '2021-02-16 15:02:49'),
(25, 13, 3, 15, 1, '3+1', 1, 0, 25, '2021-02-16 15:02:57', '2021-02-16 15:03:10'),
(26, 13, 1, 8, 1, '1+2', 1, 0, 25, '2021-02-16 15:03:12', '2021-02-16 15:03:12'),
(27, 13, 3, 10, 1, '2+3', 1, 0, 25, '2021-02-16 15:03:33', '2021-02-16 15:03:33'),
(28, 13, 1, 4, 1, '3+1', 1, 0, 25, '2021-02-16 15:06:09', '2021-02-16 15:06:09'),
(29, 13, 3, 7, 1, '1+2', 1, 0, 25, '2021-02-16 15:07:17', '2021-02-16 15:07:17'),
(30, 13, 1, 0, 1, '3+3', 1, 0, 25, '2021-02-16 15:08:56', '2021-02-16 15:08:56'),
(31, 14, 3, 4, 1, '2+2', 0, 0, 25, '2021-02-16 15:18:25', '2021-02-16 15:18:25'),
(32, 14, 1, 5, 1, '1+3', 0, 0, 25, '2021-02-16 15:21:25', '2021-02-16 15:21:25'),
(33, 14, 3, 7, 1, '1+1', 0, 0, 25, '2021-02-16 15:21:46', '2021-02-16 15:21:46'),
(34, 14, 1, 9, 1, '2+1', 0, 0, 25, '2021-02-16 15:21:56', '2021-02-16 15:21:56'),
(35, 14, 3, 10, 1, '1+1', 0, 0, 25, '2021-02-16 15:22:01', '2021-02-16 15:22:01'),
(36, 14, 1, 13, 1, '1+2', 0, 0, 25, '2021-02-16 15:23:17', '2021-02-16 15:23:17'),
(37, 14, 3, 14, 1, '1+2', 1, 0, 25, '2021-02-16 15:23:26', '2021-02-16 15:23:35'),
(38, 14, 1, 19, 1, '2+3', 0, 0, 25, '2021-02-16 15:23:37', '2021-02-16 15:23:37'),
(39, 14, 3, 11, 1, '3+1', 1, 0, 25, '2021-02-16 15:25:39', '2021-02-16 15:25:39'),
(40, 14, 1, 23, 1, '3+1', 1, 0, 24, '2021-02-16 15:25:48', '2021-02-16 15:27:15'),
(41, 14, 3, 8, 1, '2+2', 1, 0, 24, '2021-02-16 15:27:30', '2021-02-16 15:27:30'),
(42, 14, 1, 19, 1, '2+2', 1, 0, 23, '2021-02-16 15:27:45', '2021-02-16 15:27:45'),
(43, 14, 3, 7, 1, '1+1', 1, 0, 23, '2021-02-16 15:28:17', '2021-02-16 15:28:17'),
(44, 14, 1, 14, 1, '2+3', 1, 0, 22, '2021-02-16 15:28:43', '2021-02-16 15:28:43'),
(45, 14, 3, 2, 1, '3+3', 1, 0, 21, '2021-02-16 15:28:55', '2021-02-16 15:28:55'),
(46, 14, 1, 13, 1, '1+1', 1, 0, 19, '2021-02-16 15:29:03', '2021-02-16 15:29:03');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `type`, `game_state_id`, `created`, `modified`) VALUES
(10, '', 'X', 2, '2021-02-13 20:54:03', '2021-02-14 10:03:07'),
(11, 'NewGame', 'Dr', 2, '2021-02-14 10:10:42', '2021-02-14 10:10:54'),
(12, 'test game!', 'Drowning', 2, '2021-02-16 14:28:43', '2021-02-16 14:30:01'),
(13, 'test13', 'X', 2, '2021-02-16 14:54:27', '2021-02-16 14:54:48'),
(14, 'Another', 'X', 2, '2021-02-16 15:17:48', '2021-02-16 15:18:05');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games_users`
--

INSERT INTO `games_users` (`id`, `game_id`, `user_id`, `order_number`, `next_user_id`) VALUES
(1, 10, 7, 0, NULL),
(2, 11, 1, 0, 1),
(3, 12, 1, 0, 3),
(4, 12, 3, 1, 1),
(5, 13, 1, 0, 3),
(6, 13, 3, 1, 1),
(7, 14, 1, 1, 3),
(8, 14, 3, 0, 1);

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
