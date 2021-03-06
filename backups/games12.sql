-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 12, 2021 at 05:20 PM
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
-- Table structure for table `dr_results`
--

DROP TABLE IF EXISTS `dr_results`;
CREATE TABLE IF NOT EXISTS `dr_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `games_users_id` (`user_id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_results`
--

INSERT INTO `dr_results` (`id`, `game_id`, `user_id`, `score`) VALUES
(1, 7, 1, 20);

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
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_tokens_games`
--

INSERT INTO `dr_tokens_games` (`id`, `game_id`, `dr_token_id`, `position`, `user_id`, `group_number`, `dr_token_state_id`) VALUES
(1, 1, 4, 1, NULL, NULL, 1),
(2, 1, 2, NULL, 1, 2, 3),
(3, 1, 3, 12, NULL, 3, 1),
(4, 1, 1, 2, NULL, NULL, 1),
(5, 1, 5, NULL, 1, 5, 3),
(6, 1, 8, 3, NULL, NULL, 1),
(7, 1, 6, NULL, 1, 6, 3),
(8, 1, 9, 5, NULL, NULL, 1),
(9, 1, 7, 6, NULL, NULL, 1),
(10, 1, 10, 7, NULL, NULL, 1),
(11, 1, 15, 4, NULL, 15, 1),
(12, 1, 11, 8, NULL, NULL, 1),
(13, 1, 12, 12, NULL, 3, 1),
(14, 1, 14, 9, NULL, NULL, 1),
(15, 1, 13, 11, NULL, 13, 1),
(16, 1, 16, 12, NULL, 3, 1),
(17, 1, 17, 10, NULL, 17, 1),
(18, 1, 19, NULL, 1, 19, 3),
(19, 1, 18, 13, NULL, 18, 1),
(20, 1, 20, 13, NULL, 18, 1),
(21, 2, 1, 1, NULL, NULL, 1),
(22, 2, 3, 2, NULL, NULL, 1),
(23, 2, 5, 3, NULL, NULL, 1),
(24, 2, 4, 4, NULL, NULL, 1),
(25, 2, 2, 5, NULL, NULL, 1),
(26, 2, 8, 13, NULL, 8, 1),
(27, 2, 9, 13, NULL, 8, 1),
(28, 2, 7, 6, NULL, NULL, 1),
(29, 2, 10, 13, NULL, 8, 1),
(30, 2, 6, 7, NULL, NULL, 1),
(31, 2, 14, 8, NULL, NULL, 1),
(32, 2, 13, NULL, 1, 13, 3),
(33, 2, 11, 9, NULL, NULL, 1),
(34, 2, 12, 14, NULL, 12, 1),
(35, 2, 15, NULL, 1, 15, 3),
(36, 2, 17, 10, NULL, NULL, 1),
(37, 2, 16, 14, NULL, 12, 1),
(38, 2, 18, 11, NULL, NULL, 1),
(39, 2, 19, 14, NULL, 12, 1),
(40, 2, 20, 12, NULL, NULL, 1),
(41, 3, 3, 1, NULL, NULL, 1),
(42, 3, 1, 2, NULL, NULL, 1),
(43, 3, 5, 3, NULL, NULL, 1),
(44, 3, 4, 4, NULL, NULL, 1),
(45, 3, 2, 15, NULL, 2, 1),
(46, 3, 6, 5, NULL, NULL, 1),
(47, 3, 9, 16, NULL, 9, 1),
(48, 3, 8, 16, NULL, 9, 1),
(49, 3, 10, 16, NULL, 9, 1),
(50, 3, 7, 6, NULL, NULL, 1),
(51, 3, 14, 7, NULL, NULL, 1),
(52, 3, 12, 17, NULL, 12, 1),
(53, 3, 15, 8, NULL, NULL, 1),
(54, 3, 11, 9, NULL, NULL, 1),
(55, 3, 13, 17, NULL, 12, 1),
(56, 3, 17, 10, NULL, NULL, 1),
(57, 3, 16, 11, NULL, NULL, 1),
(58, 3, 19, 12, NULL, NULL, 1),
(59, 3, 18, 13, NULL, NULL, 1),
(60, 3, 20, 14, NULL, NULL, 1),
(61, 4, 3, 1, NULL, NULL, 1),
(62, 4, 1, 2, NULL, NULL, 1),
(63, 4, 4, 3, NULL, NULL, 1),
(64, 4, 5, 4, NULL, NULL, 1),
(65, 4, 2, 5, NULL, NULL, 1),
(66, 4, 8, 6, NULL, NULL, 1),
(67, 4, 9, 7, NULL, NULL, 1),
(68, 4, 6, 8, NULL, NULL, 1),
(69, 4, 7, 9, NULL, NULL, 1),
(70, 4, 10, 10, NULL, NULL, 1),
(71, 4, 12, 11, NULL, NULL, 1),
(72, 4, 15, 12, NULL, NULL, 1),
(73, 4, 11, 13, NULL, NULL, 1),
(74, 4, 13, 14, NULL, NULL, 1),
(75, 4, 14, 15, NULL, NULL, 1),
(76, 4, 17, 16, NULL, NULL, 1),
(77, 4, 18, 17, NULL, NULL, 1),
(78, 4, 16, 18, NULL, NULL, 1),
(79, 4, 20, 19, NULL, NULL, 1),
(80, 4, 19, 20, NULL, NULL, 1),
(81, 5, 1, 1, NULL, NULL, 1),
(82, 5, 3, 2, NULL, NULL, 1),
(83, 5, 2, 3, NULL, NULL, 1),
(84, 5, 4, 4, NULL, NULL, 1),
(85, 5, 5, 5, NULL, NULL, 1),
(86, 5, 7, 6, NULL, NULL, 1),
(87, 5, 6, 7, NULL, NULL, 1),
(88, 5, 9, 8, NULL, NULL, 1),
(89, 5, 10, 9, NULL, NULL, 1),
(90, 5, 8, 10, NULL, NULL, 1),
(91, 5, 15, 11, NULL, NULL, 1),
(92, 5, 13, 12, NULL, NULL, 1),
(93, 5, 11, 13, NULL, NULL, 1),
(94, 5, 14, 14, NULL, NULL, 1),
(95, 5, 12, 15, NULL, NULL, 1),
(96, 5, 16, 16, NULL, NULL, 1),
(97, 5, 17, 17, NULL, NULL, 1),
(98, 5, 19, 18, NULL, NULL, 1),
(99, 5, 18, 19, NULL, NULL, 1),
(100, 5, 20, 20, NULL, NULL, 1),
(101, 6, 1, 1, NULL, NULL, 1),
(102, 6, 3, 2, NULL, NULL, 1),
(103, 6, 4, 3, NULL, NULL, 1),
(104, 6, 5, 4, NULL, NULL, 1),
(105, 6, 2, 5, NULL, NULL, 1),
(106, 6, 9, 6, NULL, NULL, 1),
(107, 6, 8, 7, NULL, NULL, 1),
(108, 6, 7, 8, NULL, NULL, 1),
(109, 6, 10, 9, NULL, NULL, 1),
(110, 6, 6, 10, NULL, NULL, 1),
(111, 6, 15, 11, NULL, NULL, 1),
(112, 6, 14, 12, NULL, NULL, 1),
(113, 6, 11, 13, NULL, NULL, 1),
(114, 6, 13, 14, NULL, NULL, 1),
(115, 6, 12, 15, NULL, NULL, 1),
(116, 6, 16, 16, NULL, NULL, 1),
(117, 6, 20, 17, NULL, NULL, 1),
(118, 6, 17, 18, NULL, NULL, 1),
(119, 6, 18, 19, NULL, NULL, 1),
(120, 6, 19, 20, NULL, NULL, 1),
(121, 7, 1, 1, NULL, NULL, 1),
(122, 7, 3, 2, NULL, NULL, 1),
(123, 7, 4, 13, NULL, 4, 1),
(124, 7, 5, 3, NULL, NULL, 1),
(125, 7, 2, 13, NULL, 4, 1),
(126, 7, 8, 4, NULL, NULL, 1),
(127, 7, 9, 5, NULL, 9, 1),
(128, 7, 6, 6, NULL, NULL, 1),
(129, 7, 7, NULL, 1, 7, 3),
(130, 7, 10, 7, NULL, 10, 1),
(131, 7, 14, 13, NULL, 4, 1),
(132, 7, 11, 8, NULL, NULL, 1),
(133, 7, 13, 14, NULL, 13, 1),
(134, 7, 12, 9, NULL, NULL, 1),
(135, 7, 15, 14, NULL, 13, 1),
(136, 7, 16, 10, NULL, NULL, 1),
(137, 7, 17, 11, NULL, NULL, 1),
(138, 7, 18, 12, NULL, NULL, 1),
(139, 7, 19, NULL, 1, 19, 3),
(140, 7, 20, 14, NULL, 13, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dr_turns`
--

INSERT INTO `dr_turns` (`id`, `game_id`, `user_id`, `position`, `round`, `roll`, `returning`, `taking`, `dropping`, `oxygen`, `created`, `modified`) VALUES
(1, 1, 1, 3, 1, '2+1', 0, 1, 0, 25, '2021-02-28 09:01:19', '2021-02-28 11:17:38'),
(2, 1, 1, 4, 1, '1+1', 0, 0, 0, 24, '2021-03-04 18:23:28', '2021-03-04 18:23:28'),
(3, 1, 1, 5, 1, '1+1', 0, 0, 0, 23, '2021-03-04 18:30:03', '2021-03-04 18:30:03'),
(4, 1, 1, 6, 1, '1+1', 0, 0, 0, 22, '2021-03-04 18:30:06', '2021-03-04 18:30:06'),
(5, 1, 1, 11, 1, '3+3', 0, 0, 0, 21, '2021-03-04 18:30:07', '2021-03-04 18:30:07'),
(6, 1, 1, 13, 1, '2+1', 0, 0, 0, 20, '2021-03-04 18:31:24', '2021-03-04 18:31:24'),
(7, 1, 1, 17, 1, '2+3', 0, 0, 0, 19, '2021-03-08 14:50:59', '2021-03-08 14:50:59'),
(8, 1, 1, 20, 1, '3+3', 1, 0, 0, 18, '2021-03-08 14:51:04', '2021-03-08 14:51:04'),
(9, 1, 1, 17, 1, '1+3', 1, 1, 0, 17, '2021-03-08 14:51:05', '2021-03-08 14:51:08'),
(10, 1, 1, 16, 1, '2+1', 1, 1, 0, 15, '2021-03-08 14:57:42', '2021-03-08 14:58:19'),
(11, 1, 1, 14, 1, '3+2', 1, 0, 0, 12, '2021-03-08 14:58:22', '2021-03-08 14:58:22'),
(12, 1, 1, 13, 1, '2+2', 1, 1, 0, 9, '2021-03-08 14:58:24', '2021-03-08 14:59:01'),
(13, 1, 1, 13, 1, '1+1', 1, 0, 0, 5, '2021-03-08 14:59:04', '2021-03-08 14:59:04'),
(14, 1, 1, 13, 1, '3+1', 1, 0, 0, 1, '2021-03-08 15:27:34', '2021-03-08 15:27:34'),
(15, 1, 1, 13, 1, '1+1', 1, 0, 0, -3, '2021-03-08 15:28:02', '2021-03-08 15:28:02'),
(16, 1, 1, 2, 2, '1+1', 0, 0, 0, 25, '2021-03-08 15:28:02', '2021-03-08 15:28:02'),
(17, 1, 1, 7, 2, '2+3', 0, 0, 0, 25, '2021-03-08 15:28:12', '2021-03-08 15:28:12'),
(18, 1, 1, 11, 2, '3+1', 0, 0, 0, 25, '2021-03-08 15:28:41', '2021-03-08 15:28:41'),
(19, 1, 1, 13, 2, '1+1', 0, 1, 0, 25, '2021-03-08 15:28:49', '2021-03-08 15:29:03'),
(20, 1, 1, 15, 2, '2+1', 0, 1, 0, 24, '2021-03-08 15:29:10', '2021-03-08 15:29:18'),
(21, 1, 1, 16, 2, '1+2', 0, 1, 0, 22, '2021-03-08 15:29:25', '2021-03-08 15:29:44'),
(22, 1, 1, 17, 2, '3+1', 0, 1, 0, 19, '2021-03-08 15:29:50', '2021-03-08 15:29:59'),
(23, 1, 1, 19, 2, '3+3', 0, 0, 0, 15, '2021-03-08 15:30:05', '2021-03-08 15:30:05'),
(24, 1, 1, 20, 2, '3+3', 1, 0, 0, 11, '2021-03-08 17:46:55', '2021-03-08 17:46:55'),
(25, 1, 1, 20, 2, '2+2', 1, 0, 0, 7, '2021-03-08 17:50:37', '2021-03-08 17:50:37'),
(26, 1, 1, 18, 2, '3+2', 1, 0, 0, 4, '2021-03-08 17:53:26', '2021-03-08 17:53:26'),
(27, 1, 1, 17, 2, '3+1', 1, 0, 0, 1, '2021-03-08 17:53:31', '2021-03-08 17:53:31'),
(28, 1, 1, 16, 2, '2+2', 1, 0, 0, -2, '2021-03-08 17:59:37', '2021-03-08 17:59:37'),
(29, 1, 1, 5, 3, '3+2', 0, 0, 0, 25, '2021-03-08 17:59:38', '2021-03-08 17:59:38'),
(30, 1, 1, 9, 3, '1+3', 0, 0, 0, 25, '2021-03-08 17:59:46', '2021-03-08 17:59:46'),
(31, 1, 1, 12, 3, '1+2', 0, 0, 0, 25, '2021-03-08 17:59:56', '2021-03-08 17:59:56'),
(32, 1, 1, 13, 3, '3+2', 1, 1, 0, 25, '2021-03-08 18:00:00', '2021-03-08 18:13:32'),
(33, 1, 1, 10, 3, '2+2', 1, 1, 0, 24, '2021-03-08 18:13:37', '2021-03-08 18:13:43'),
(34, 1, 1, 8, 3, '1+3', 1, 0, 0, 22, '2021-03-08 18:13:54', '2021-03-08 18:13:54'),
(35, 1, 1, 6, 3, '2+2', 1, 1, 0, 20, '2021-03-08 18:13:57', '2021-03-08 18:14:01'),
(36, 1, 1, 6, 3, '2+1', 1, 0, 0, 17, '2021-03-08 18:14:07', '2021-03-08 18:14:07'),
(37, 1, 1, 4, 3, '2+2', 1, 1, 0, 15, '2021-03-09 07:48:54', '2021-03-09 07:49:26'),
(38, 1, 1, 2, 3, '2+3', 1, 1, 0, 12, '2021-03-09 07:51:48', '2021-03-09 07:58:50'),
(39, 1, 1, 1, 3, '2+3', 1, 0, 0, 8, '2021-03-09 07:59:36', '2021-03-09 07:59:36'),
(40, 1, 1, 0, 3, '3+3', 1, 0, 0, 4, '2021-03-09 08:04:25', '2021-03-09 08:04:25'),
(41, 1, 1, 4, 4, '3+1', 0, 0, 0, 25, '2021-03-09 08:04:25', '2021-03-09 08:04:25'),
(42, 2, 1, 2, 1, '1+1', 0, 0, 0, 25, '2021-03-09 08:07:52', '2021-03-09 08:07:52'),
(43, 2, 1, 4, 1, '1+1', 0, 0, 0, 25, '2021-03-09 08:08:41', '2021-03-09 08:08:41'),
(44, 2, 1, 8, 1, '3+1', 0, 0, 0, 25, '2021-03-09 08:34:04', '2021-03-09 08:34:04'),
(45, 2, 1, 12, 1, '2+2', 1, 1, 0, 25, '2021-03-09 08:34:07', '2021-03-09 08:34:14'),
(46, 2, 1, 9, 1, '2+2', 1, 1, 0, 24, '2021-03-09 08:34:14', '2021-03-09 08:34:17'),
(47, 2, 1, 7, 1, '3+1', 1, 1, 0, 22, '2021-03-11 19:05:53', '2021-03-11 19:11:38'),
(48, 2, 1, 6, 1, '1+3', 1, 1, 0, 19, '2021-03-11 19:11:44', '2021-03-11 19:11:49'),
(49, 2, 1, 6, 1, '3+1', 1, 0, 0, 15, '2021-03-12 08:47:31', '2021-03-12 08:47:31'),
(50, 2, 1, 6, 1, '1+1', 1, 0, 0, 11, '2021-03-12 08:47:42', '2021-03-12 08:47:42'),
(51, 2, 1, 6, 1, '1+3', 1, 1, 0, 7, '2021-03-12 08:47:51', '2021-03-12 08:51:24'),
(52, 2, 1, 6, 1, '2+2', 1, 1, 0, 3, '2021-03-12 08:51:33', '2021-03-12 08:52:30'),
(53, 2, 1, 6, 1, '2+1', 1, 0, 0, -1, '2021-03-12 08:52:41', '2021-03-12 08:52:41'),
(54, 2, 1, 5, 2, '3+2', 0, 0, 0, 25, '2021-03-12 08:52:41', '2021-03-12 08:52:41'),
(55, 2, 1, 10, 2, '2+3', 0, 0, 0, 25, '2021-03-12 08:52:53', '2021-03-12 08:52:53'),
(56, 2, 1, 13, 2, '1+2', 0, 0, 0, 25, '2021-03-12 08:52:57', '2021-03-12 08:52:57'),
(57, 2, 1, 18, 2, '2+3', 0, 1, 0, 25, '2021-03-12 08:53:13', '2021-03-12 08:53:18'),
(58, 2, 1, 20, 2, '1+2', 1, 0, 0, 24, '2021-03-12 08:53:27', '2021-03-12 08:53:27'),
(59, 2, 1, 15, 2, '3+3', 1, 1, 0, 23, '2021-03-12 08:53:34', '2021-03-12 08:53:37'),
(60, 2, 1, 13, 2, '1+3', 1, 1, 0, 21, '2021-03-12 08:53:43', '2021-03-12 08:53:48'),
(61, 2, 1, 13, 2, '1+2', 1, 0, 0, 18, '2021-03-12 08:53:53', '2021-03-12 08:53:53'),
(62, 2, 1, 11, 2, '3+1', 1, 1, 0, 16, '2021-03-12 08:55:56', '2021-03-12 08:56:04'),
(63, 2, 1, 10, 2, '2+2', 1, 1, 0, 13, '2021-03-12 08:56:08', '2021-03-12 08:56:13'),
(64, 2, 1, 10, 2, '2+2', 1, 0, 1, 9, '2021-03-12 08:56:17', '2021-03-12 08:56:23'),
(65, 2, 1, 10, 2, '2+1', 1, 0, 0, 6, '2021-03-12 08:56:39', '2021-03-12 08:56:39'),
(66, 2, 1, 10, 2, '1+2', 1, 0, 0, 3, '2021-03-12 08:57:03', '2021-03-12 08:57:03'),
(67, 2, 1, 10, 2, '2+1', 1, 0, 0, 0, '2021-03-12 08:57:29', '2021-03-12 08:57:29'),
(68, 2, 1, 6, 3, '3+3', 0, 0, 0, 25, '2021-03-12 08:57:29', '2021-03-12 08:57:29'),
(69, 2, 1, 10, 3, '2+2', 0, 1, 0, 25, '2021-03-12 08:57:34', '2021-03-12 09:00:02'),
(70, 2, 1, 12, 3, '2+1', 1, 1, 0, 24, '2021-03-12 09:00:08', '2021-03-12 09:00:35'),
(71, 2, 1, 8, 3, '3+3', 1, 0, 0, 22, '2021-03-12 09:00:35', '2021-03-12 09:00:35'),
(72, 2, 1, 6, 3, '2+2', 1, 0, 0, 20, '2021-03-12 09:00:38', '2021-03-12 09:00:38'),
(73, 2, 1, 3, 3, '2+3', 1, 0, 0, 18, '2021-03-12 09:00:42', '2021-03-12 09:00:42'),
(74, 2, 1, 2, 3, '2+1', 1, 0, 0, 16, '2021-03-12 09:00:46', '2021-03-12 09:00:46'),
(75, 2, 1, 0, 3, '2+2', 1, 0, 0, 14, '2021-03-12 09:00:52', '2021-03-12 09:00:52'),
(76, 2, 1, 5, 4, '2+3', 0, 0, 0, 25, '2021-03-12 09:00:52', '2021-03-12 09:00:52'),
(77, 3, 1, 5, 1, '3+2', 0, 1, 0, 25, '2021-03-12 14:10:41', '2021-03-12 14:10:59'),
(254, 3, 1, 10, 1, '2+3', 0, 0, 0, 25, '2021-03-12 14:15:23', '2021-03-12 14:15:23'),
(255, 3, 1, 14, 1, '2+2', 1, 1, 0, 25, '2021-03-12 14:16:22', '2021-03-12 14:16:48'),
(256, 3, 1, 11, 1, '1+3', 1, 1, 0, 24, '2021-03-12 14:17:50', '2021-03-12 14:17:53'),
(257, 3, 1, 8, 1, '2+3', 1, 1, 0, 22, '2021-03-12 14:19:26', '2021-03-12 14:19:36'),
(258, 3, 1, 7, 1, '2+2', 1, 1, 0, 19, '2021-03-12 14:20:57', '2021-03-12 14:21:02'),
(259, 3, 1, 6, 1, '3+2', 1, 1, 0, 15, '2021-03-12 14:21:46', '2021-03-12 14:22:29'),
(260, 3, 1, 6, 1, '1+1', 1, 0, 0, 10, '2021-03-12 14:25:58', '2021-03-12 14:25:58'),
(261, 3, 1, 6, 1, '3+2', 1, 0, 0, 5, '2021-03-12 14:25:58', '2021-03-12 14:25:58'),
(262, 3, 1, 6, 1, '3+2', 1, 0, 0, 0, '2021-03-12 14:25:58', '2021-03-12 14:25:58'),
(263, 3, 1, 4, 2, '2+2', 0, 0, 0, 25, '2021-03-12 14:25:58', '2021-03-12 14:25:58'),
(264, 3, 1, 7, 2, '1+2', 0, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(265, 3, 1, 11, 2, '3+1', 0, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(266, 3, 1, 14, 2, '2+1', 0, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(267, 3, 1, 18, 2, '2+2', 0, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(268, 3, 1, 20, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(269, 3, 1, 17, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(270, 3, 1, 13, 2, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(271, 3, 1, 9, 2, '1+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(272, 3, 1, 3, 2, '2+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(273, 3, 1, 0, 2, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(274, 3, 1, -1, 2, '2+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(275, 3, 1, -2, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(276, 3, 1, -3, 2, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(277, 3, 1, -4, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(278, 3, 1, -5, 2, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(279, 3, 1, -6, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(280, 3, 1, -7, 2, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(281, 3, 1, -8, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(282, 3, 1, -9, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(283, 3, 1, -10, 2, '1+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(284, 3, 1, -11, 2, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(285, 3, 1, -12, 2, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(286, 3, 1, -13, 2, '1+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(287, 3, 1, -14, 2, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(288, 3, 1, -15, 2, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(289, 3, 1, -16, 2, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(290, 3, 1, -17, 2, '1+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(291, 3, 1, -18, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(292, 3, 1, -19, 2, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(293, 3, 1, -20, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(294, 3, 1, -21, 2, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(295, 3, 1, -22, 2, '3+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(296, 3, 1, -23, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(297, 3, 1, -24, 2, '1+3', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(298, 3, 1, -25, 2, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(299, 3, 1, -26, 2, '1+1', 1, 0, 0, 25, '2021-03-12 14:26:13', '2021-03-12 14:26:13'),
(300, 3, 1, -27, 2, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:14', '2021-03-12 14:26:14'),
(301, 3, 1, 3, 3, '2+1', 0, 0, 0, 25, '2021-03-12 14:26:14', '2021-03-12 14:26:14'),
(302, 3, 1, 8, 3, '2+3', 0, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(303, 3, 1, 12, 3, '1+3', 0, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(304, 3, 1, 15, 3, '1+2', 0, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(305, 3, 1, 19, 3, '1+3', 0, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(306, 3, 1, 20, 3, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(307, 3, 1, 16, 3, '1+3', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(308, 3, 1, 11, 3, '2+3', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(309, 3, 1, 7, 3, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(310, 3, 1, 3, 3, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(311, 3, 1, 0, 3, '2+3', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(312, 3, 1, -1, 3, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(313, 3, 1, -2, 3, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(314, 3, 1, -3, 3, '3+3', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(315, 3, 1, -4, 3, '1+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(316, 3, 1, -5, 3, '2+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(317, 3, 1, -6, 3, '1+3', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(318, 3, 1, -7, 3, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(319, 3, 1, -8, 3, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(320, 3, 1, -9, 3, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(321, 3, 1, -10, 3, '1+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(322, 3, 1, -11, 3, '3+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(323, 3, 1, -12, 3, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(324, 3, 1, -13, 3, '3+1', 1, 0, 0, 25, '2021-03-12 14:26:17', '2021-03-12 14:26:17'),
(325, 3, 1, -14, 3, '2+2', 1, 0, 0, 25, '2021-03-12 14:26:18', '2021-03-12 14:26:18'),
(326, 3, 1, 3, 4, '1+2', 0, 0, 0, 25, '2021-03-12 14:26:18', '2021-03-12 14:26:18'),
(327, 4, 1, 6, 1, '3+3', 0, 0, 0, 25, '2021-03-12 14:27:32', '2021-03-12 14:27:32'),
(328, 4, 1, 8, 1, '1+1', 0, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(329, 4, 1, 14, 1, '3+3', 0, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(330, 4, 1, 17, 1, '1+2', 0, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(331, 4, 1, 20, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(332, 4, 1, 15, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(333, 4, 1, 10, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(334, 4, 1, 6, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(335, 4, 1, 2, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(336, 4, 1, 0, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(337, 4, 1, -1, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(338, 4, 1, -2, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(339, 4, 1, -3, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(340, 4, 1, -4, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(341, 4, 1, -5, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(342, 4, 1, -6, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(343, 4, 1, -7, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(344, 4, 1, -8, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(345, 4, 1, -9, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(346, 4, 1, -10, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(347, 4, 1, -11, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(348, 4, 1, -12, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(349, 4, 1, -13, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(350, 4, 1, -14, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(351, 4, 1, -15, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(352, 4, 1, -16, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(353, 4, 1, -17, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(354, 4, 1, -18, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(355, 4, 1, -19, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(356, 4, 1, -20, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(357, 4, 1, -21, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(358, 4, 1, -22, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(359, 4, 1, -23, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(360, 4, 1, -24, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:27:39', '2021-03-12 14:27:39'),
(361, 4, 1, -25, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:27:40', '2021-03-12 14:27:40'),
(362, 4, 1, 4, 2, '3+1', 0, 0, 0, 25, '2021-03-12 14:27:40', '2021-03-12 14:27:40'),
(363, 5, 1, 3, 1, '2+1', 0, 0, 0, 25, '2021-03-12 14:28:34', '2021-03-12 14:28:34'),
(364, 5, 1, 7, 1, '3+1', 0, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(365, 5, 1, 10, 1, '2+1', 0, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(366, 5, 1, 12, 1, '1+1', 0, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(367, 5, 1, 17, 1, '2+3', 0, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(368, 5, 1, 20, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(369, 5, 1, 16, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(370, 5, 1, 10, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(371, 5, 1, 5, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(372, 5, 1, 1, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(373, 5, 1, 0, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(374, 5, 1, -1, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(375, 5, 1, -2, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(376, 5, 1, -3, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(377, 5, 1, -4, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(378, 5, 1, -5, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(379, 5, 1, -6, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(380, 5, 1, -7, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(381, 5, 1, -8, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(382, 5, 1, -9, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(383, 5, 1, -10, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(384, 5, 1, -11, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(385, 5, 1, -12, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(386, 5, 1, -13, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(387, 5, 1, -14, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(388, 5, 1, -15, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(389, 5, 1, -16, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(390, 5, 1, -17, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(391, 5, 1, -18, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(392, 5, 1, -19, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(393, 5, 1, -20, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(394, 5, 1, -21, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(395, 5, 1, -22, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(396, 5, 1, -23, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(397, 5, 1, -24, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(398, 5, 1, -25, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(399, 5, 1, -26, 1, '1+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(400, 5, 1, -27, 1, '3+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(401, 5, 1, -28, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(402, 5, 1, -29, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:28:40', '2021-03-12 14:28:40'),
(403, 5, 1, -30, 1, '3+2', 1, 0, 0, 25, '2021-03-12 14:28:41', '2021-03-12 14:28:41'),
(404, 5, 1, 3, 2, '2+1', 0, 0, 0, 25, '2021-03-12 14:28:41', '2021-03-12 14:28:41'),
(405, 6, 1, 4, 1, '1+3', 0, 0, 0, 25, '2021-03-12 14:29:32', '2021-03-12 14:29:32'),
(406, 6, 1, 9, 1, '3+2', 0, 0, 0, 25, '2021-03-12 14:29:37', '2021-03-12 14:29:37'),
(407, 6, 1, 13, 1, '3+1', 0, 0, 0, 25, '2021-03-12 14:29:37', '2021-03-12 14:29:37'),
(408, 6, 1, 17, 1, '3+1', 0, 0, 0, 25, '2021-03-12 14:29:37', '2021-03-12 14:29:37'),
(409, 6, 1, 19, 1, '1+1', 0, 0, 0, 25, '2021-03-12 14:29:37', '2021-03-12 14:29:37'),
(410, 6, 1, 20, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(411, 6, 1, 16, 1, '2+2', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(412, 6, 1, 17, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(413, 6, 1, 18, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(414, 6, 1, 15, 1, '2+3', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(415, 6, 1, 17, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(416, 6, 1, 17, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:29:38', '2021-03-12 14:29:38'),
(417, 6, 1, 17, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(418, 6, 1, 14, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(419, 6, 1, 10, 1, '3+1', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(420, 6, 1, 7, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(421, 6, 1, 5, 1, '1+1', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(422, 6, 1, 2, 1, '2+1', 1, 0, 0, 25, '2021-03-12 14:29:40', '2021-03-12 14:29:40'),
(423, 6, 1, 0, 1, '1+2', 1, 0, 0, 25, '2021-03-12 14:29:42', '2021-03-12 14:29:42'),
(424, 6, 1, 4, 2, '2+2', 0, 0, 0, 25, '2021-03-12 14:29:42', '2021-03-12 14:29:42'),
(425, 7, 1, 4, 1, '1+3', 0, 0, 0, 25, '2021-03-12 14:35:11', '2021-03-12 14:35:11'),
(426, 7, 1, 7, 1, '2+1', 0, 0, 0, 25, '2021-03-12 14:35:15', '2021-03-12 14:35:15'),
(427, 7, 1, 11, 1, '3+1', 0, 0, 0, 25, '2021-03-12 14:35:35', '2021-03-12 14:35:35'),
(428, 7, 1, 15, 1, '1+3', 1, 1, 0, 25, '2021-03-12 14:36:01', '2021-03-12 14:36:20'),
(429, 7, 1, 13, 1, '2+1', 1, 1, 0, 24, '2021-03-12 14:36:20', '2021-03-12 14:36:29'),
(430, 7, 1, 11, 1, '3+1', 1, 1, 0, 22, '2021-03-12 14:36:29', '2021-03-12 14:36:36'),
(431, 7, 1, 10, 1, '1+3', 1, 0, 0, 19, '2021-03-12 14:36:36', '2021-03-12 14:36:36'),
(432, 7, 1, 10, 1, '1+2', 1, 0, 0, 16, '2021-03-12 14:36:41', '2021-03-12 14:36:41'),
(433, 7, 1, 9, 1, '2+2', 1, 0, 0, 13, '2021-03-12 14:36:45', '2021-03-12 14:36:45'),
(434, 7, 1, 6, 1, '3+3', 1, 0, 0, 10, '2021-03-12 14:36:52', '2021-03-12 14:36:52'),
(435, 7, 1, 6, 1, '1+1', 1, 0, 0, 7, '2021-03-12 14:37:02', '2021-03-12 14:37:02'),
(436, 7, 1, 6, 1, '1+2', 1, 0, 0, 4, '2021-03-12 14:37:05', '2021-03-12 14:37:05'),
(437, 7, 1, 5, 1, '3+1', 1, 0, 0, 1, '2021-03-12 14:37:11', '2021-03-12 14:37:11'),
(438, 7, 1, 2, 1, '3+3', 1, 0, 0, -2, '2021-03-12 14:37:19', '2021-03-12 14:37:19'),
(439, 7, 1, 4, 2, '1+3', 0, 0, 0, 25, '2021-03-12 14:37:19', '2021-03-12 14:37:19'),
(440, 7, 1, 9, 2, '2+3', 0, 0, 0, 25, '2021-03-12 15:22:14', '2021-03-12 15:22:14'),
(441, 7, 1, 12, 2, '1+2', 0, 0, 0, 25, '2021-03-12 15:22:40', '2021-03-12 15:22:40'),
(442, 7, 1, 16, 2, '2+2', 0, 1, 0, 25, '2021-03-12 15:22:46', '2021-03-12 15:22:48'),
(443, 7, 1, 19, 2, '2+2', 1, 0, 0, 24, '2021-03-12 15:22:52', '2021-03-12 15:23:07'),
(444, 7, 1, 14, 2, '3+3', 1, 0, 0, 23, '2021-03-12 15:23:09', '2021-03-12 15:23:09'),
(445, 7, 1, 12, 2, '2+1', 1, 0, 0, 22, '2021-03-12 15:23:14', '2021-03-12 15:23:14'),
(446, 7, 1, 9, 2, '2+2', 1, 1, 0, 21, '2021-03-12 15:23:16', '2021-03-12 15:23:23'),
(447, 7, 1, 8, 2, '2+1', 1, 0, 0, 19, '2021-03-12 15:23:23', '2021-03-12 15:23:23'),
(448, 7, 1, 6, 2, '1+3', 1, 0, 0, 17, '2021-03-12 15:23:26', '2021-03-12 15:23:26'),
(449, 7, 1, 3, 2, '3+2', 1, 0, 0, 15, '2021-03-12 15:23:28', '2021-03-12 15:23:28'),
(450, 7, 1, 0, 2, '2+3', 1, 0, 0, 13, '2021-03-12 15:23:30', '2021-03-12 15:23:30'),
(451, 7, 1, 5, 3, '2+3', 0, 0, 0, 25, '2021-03-12 15:23:30', '2021-03-12 15:23:30'),
(452, 7, 1, 10, 3, '2+3', 0, 0, 0, 25, '2021-03-12 15:23:59', '2021-03-12 15:23:59'),
(453, 7, 1, 12, 3, '1+1', 0, 0, 0, 25, '2021-03-12 15:24:02', '2021-03-12 15:24:02'),
(454, 7, 1, 15, 3, '2+1', 0, 1, 0, 25, '2021-03-12 15:24:04', '2021-03-12 15:24:13'),
(455, 7, 1, 16, 3, '1+1', 1, 1, 0, 24, '2021-03-12 15:24:15', '2021-03-12 15:24:21'),
(456, 7, 1, 13, 3, '2+3', 1, 0, 0, 22, '2021-03-12 15:24:21', '2021-03-12 15:24:21'),
(457, 7, 1, 11, 3, '1+3', 1, 0, 0, 20, '2021-03-12 15:24:23', '2021-03-12 15:24:23'),
(458, 7, 1, 7, 3, '3+3', 1, 0, 0, 18, '2021-03-12 15:24:27', '2021-03-12 15:24:27'),
(459, 7, 1, 5, 3, '2+2', 1, 1, 0, 16, '2021-03-12 15:24:30', '2021-03-12 15:24:36'),
(460, 7, 1, 4, 3, '3+1', 1, 0, 0, 13, '2021-03-12 15:24:36', '2021-03-12 15:24:36'),
(461, 7, 1, 3, 3, '1+3', 1, 1, 0, 10, '2021-03-12 15:24:38', '2021-03-12 15:24:45'),
(462, 7, 1, 2, 3, '2+3', 1, 0, 0, 6, '2021-03-12 15:24:45', '2021-03-12 15:24:45'),
(504, 7, 1, 0, 3, '3+2', 1, 0, 0, 6, '2021-03-12 16:05:04', '2021-03-12 16:05:04'),
(505, 7, 1, 5, 4, '2+3', 0, 0, 0, 25, '2021-03-12 16:05:04', '2021-03-12 17:16:23');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `type`, `game_state_id`, `created`, `modified`) VALUES
(1, 'X', 'Y', 2, '2021-02-28 09:01:07', '2021-02-28 09:01:19'),
(2, 'Refresher', 'X', 2, '2021-03-09 08:07:41', '2021-03-09 08:07:52'),
(3, 'Whole game?', 'X', 2, '2021-03-12 14:10:33', '2021-03-12 14:10:41'),
(4, 'Again', 'e', 2, '2021-03-12 14:27:22', '2021-03-12 14:27:32'),
(5, 'X', 'as', 2, '2021-03-12 14:28:25', '2021-03-12 14:28:34'),
(6, 'A', 'sd', 2, '2021-03-12 14:29:22', '2021-03-12 14:29:32'),
(7, 'Another', 'X', 3, '2021-03-12 14:35:04', '2021-03-12 16:05:04');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `games_users`
--

INSERT INTO `games_users` (`id`, `game_id`, `user_id`, `order_number`, `next_user_id`) VALUES
(1, 1, 1, 0, 1),
(2, 2, 1, 0, 1),
(3, 3, 1, 0, 1),
(4, 4, 1, 0, 1),
(5, 5, 1, 0, 1),
(6, 6, 1, 0, 1),
(7, 7, 1, 0, 1);

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
-- Constraints for table `dr_results`
--
ALTER TABLE `dr_results`
  ADD CONSTRAINT `dr_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dr_results_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

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
