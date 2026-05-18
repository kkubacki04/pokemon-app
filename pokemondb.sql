-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 16, 2026 at 07:42 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokemondb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `abilities`
--

CREATE TABLE `abilities` (
  `indeks` int(11) NOT NULL,
  `pokemon_indeks` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `damage` int(11) DEFAULT NULL,
  `effect` varchar(100) DEFAULT NULL,
  `effect_chance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abilities`
--

INSERT INTO `abilities` (`indeks`, `pokemon_indeks`, `name`, `type`, `damage`, `effect`, `effect_chance`) VALUES
(1, 1, 'Vine Whip', 'Grass', 10, NULL, NULL),
(2, 1, 'Solar Beam', 'Grass', 15, NULL, NULL),
(3, 2, 'Vine Whip', 'Grass', 18, NULL, NULL),
(4, 2, 'Solar Beam', 'Grass', 20, NULL, NULL),
(5, 3, 'Vine Whip', 'Grass', 28, NULL, NULL),
(6, 3, 'Solar Beam', 'Grass', 35, NULL, NULL),
(7, 4, 'Ember', 'Fire', 10, NULL, NULL),
(8, 4, 'Flamethrower', 'Fire', 15, NULL, NULL),
(9, 5, 'Ember', 'Fire', 18, NULL, NULL),
(10, 5, 'Fire Blast', 'Fire', 20, NULL, NULL),
(11, 6, 'Fire Spin', 'Fire', 22, NULL, NULL),
(12, 6, 'Fire Blast', 'Fire', 25, NULL, NULL),
(13, 7, 'Water Gun', 'Water', 10, NULL, NULL),
(14, 7, 'Hydro Pump', 'Water', 15, NULL, NULL),
(15, 8, 'Water Gun', 'Water', 18, NULL, NULL),
(16, 8, 'Hydro Pump', 'Water', 20, NULL, NULL),
(17, 9, 'Water Gun', 'Water', 28, NULL, NULL),
(18, 9, 'Hydro Pump', 'Water', 35, NULL, NULL),
(19, 10, 'Tackle', 'Normal', 10, NULL, NULL),
(20, 10, 'String Shot', 'Bug', 15, NULL, NULL),
(21, 11, 'Tackle', 'Normal', 15, NULL, NULL),
(22, 11, 'Harden', 'Normal', 0, 'defense boost', 50),
(23, 12, 'Confusion', 'Psychic', 22, NULL, NULL),
(24, 12, 'Bug Buzz', 'Bug', 28, NULL, NULL),
(25, 13, 'Tackle', 'Normal', 10, NULL, NULL),
(26, 13, 'Poison Sting', 'Poison', 15, 'poison', 30),
(27, 14, 'Tackle', 'Normal', 15, NULL, NULL),
(28, 14, 'Harden', 'Normal', 0, 'defense boost', 50),
(29, 15, 'Fury Cutter', 'Bug', 22, NULL, NULL),
(30, 15, 'Sludge Bomb', 'Poison', 28, 'poison', 30),
(31, 16, 'Tackle', 'Normal', 10, NULL, NULL),
(32, 16, 'Gust', 'Flying', 15, NULL, NULL),
(33, 17, 'Quick Attack', 'Normal', 18, NULL, NULL),
(34, 17, 'Aerial Ace', 'Flying', 20, NULL, NULL),
(35, 18, 'Quick Attack', 'Normal', 22, NULL, NULL),
(36, 18, 'Hurricane', 'Flying', 30, NULL, NULL),
(37, 19, 'Tackle', 'Normal', 10, NULL, NULL),
(38, 19, 'Hyper Fang', 'Normal', 20, NULL, NULL),
(39, 20, 'Bite', 'Dark', 18, NULL, NULL),
(40, 20, 'Hyper Fang', 'Normal', 25, NULL, NULL),
(41, 21, 'Peck', 'Flying', 10, NULL, NULL),
(42, 21, 'Drill Peck', 'Flying', 20, NULL, NULL),
(43, 22, 'Peck', 'Flying', 18, NULL, NULL),
(44, 22, 'Drill Peck', 'Flying', 25, NULL, NULL),
(45, 23, 'Bite', 'Dark', 10, NULL, NULL),
(46, 23, 'Poison Sting', 'Poison', 15, 'poison', 30),
(47, 24, 'Bite', 'Dark', 18, NULL, NULL),
(48, 24, 'Poison Jab', 'Poison', 25, 'poison', 30),
(49, 25, 'Quick Attack', 'Normal', 10, NULL, NULL),
(50, 25, 'Thunderbolt', 'Electric', 25, NULL, NULL),
(51, 26, 'Quick Attack', 'Normal', 10, NULL, NULL),
(52, 26, 'Thunderbolt', 'Electric', 25, NULL, NULL),
(53, 27, 'Scratch', 'Normal', 10, NULL, NULL),
(54, 27, 'Sand Attack', 'Ground', 15, NULL, NULL),
(55, 28, 'Scratch', 'Normal', 18, NULL, NULL),
(56, 28, 'Earthquake', 'Ground', 25, NULL, NULL),
(57, 29, 'Poison Sting', 'Poison', 10, 'poison', 30),
(58, 29, 'Double Kick', 'Fighting', 18, NULL, NULL),
(59, 30, 'Poison Sting', 'Poison', 10, 'poison', 30),
(60, 30, 'Double Kick', 'Fighting', 18, NULL, NULL),
(61, 31, 'Poison Jab', 'Poison', 25, 'poison', 30),
(62, 31, 'Earthquake', 'Ground', 35, NULL, NULL),
(63, 35, 'Pound', 'Normal', 10, NULL, NULL),
(64, 35, 'Moonblast', 'Fairy', 15, NULL, NULL),
(65, 36, 'Pound', 'Normal', 18, NULL, NULL),
(66, 36, 'Moonblast', 'Fairy', 25, NULL, NULL),
(67, 37, 'Ember', 'Fire', 10, NULL, NULL),
(68, 37, 'Flamethrower', 'Fire', 18, NULL, NULL),
(69, 38, 'Ember', 'Fire', 18, NULL, NULL),
(70, 38, 'Flamethrower', 'Fire', 25, NULL, NULL),
(71, 39, 'Pound', 'Normal', 10, NULL, NULL),
(72, 39, 'Sing', 'Normal', 0, 'sleep', 50),
(73, 40, 'Pound', 'Normal', 18, NULL, NULL),
(74, 40, 'Hyper Voice', 'Normal', 25, NULL, NULL),
(75, 41, 'Bite', 'Dark', 10, NULL, NULL),
(76, 41, 'Supersonic', 'Normal', 0, 'confusion', 40),
(77, 42, 'Bite', 'Dark', 18, NULL, NULL),
(78, 42, 'Supersonic', 'Normal', 0, 'confusion', 40),
(79, 43, 'Absorb', 'Grass', 10, NULL, NULL),
(80, 43, 'Sleep Powder', 'Grass', 0, 'sleep', 50),
(81, 44, 'Absorb', 'Grass', 15, NULL, NULL),
(82, 44, 'Sleep Powder', 'Grass', 0, 'sleep', 50),
(83, 45, 'SolarBeam', 'Grass', 25, NULL, NULL),
(84, 45, 'Sleep Powder', 'Grass', 0, 'sleep', 50),
(85, 46, 'Scratch', 'Normal', 10, NULL, NULL),
(86, 46, 'Stun Spore', 'Grass', 0, 'paralyze', 30),
(87, 47, 'Scratch', 'Normal', 18, NULL, NULL),
(88, 47, 'Spore', 'Grass', 0, 'sleep', 50),
(89, 48, 'Tackle', 'Normal', 10, NULL, NULL),
(90, 48, 'Confusion', 'Psychic', 15, NULL, NULL),
(91, 49, 'Tackle', 'Normal', 18, NULL, NULL),
(92, 49, 'Confusion', 'Psychic', 20, NULL, NULL),
(93, 50, 'Scratch', 'Normal', 10, NULL, NULL),
(94, 50, 'Earthquake', 'Ground', 25, NULL, NULL),
(95, 51, 'Scratch', 'Normal', 18, NULL, NULL),
(96, 51, 'Earthquake', 'Ground', 35, NULL, NULL),
(97, 52, 'Scratch', 'Normal', 10, NULL, NULL),
(98, 52, 'Pay Day', 'Normal', 15, NULL, NULL),
(99, 53, 'Scratch', 'Normal', 18, NULL, NULL),
(100, 53, 'Fury Swipes', 'Normal', 25, NULL, NULL),
(101, 54, 'Confusion', 'Psychic', 10, NULL, NULL),
(102, 54, 'Water Gun', 'Water', 15, NULL, NULL),
(103, 55, 'Confusion', 'Psychic', 18, NULL, NULL),
(104, 55, 'Hydro Pump', 'Water', 25, NULL, NULL),
(105, 56, 'Low Kick', 'Fighting', 10, NULL, NULL),
(106, 56, 'Karate Chop', 'Fighting', 15, NULL, NULL),
(107, 57, 'Low Kick', 'Fighting', 18, NULL, NULL),
(108, 57, 'Karate Chop', 'Fighting', 20, NULL, NULL),
(109, 58, 'Bite', 'Dark', 10, NULL, NULL),
(110, 58, 'Flamethrower', 'Fire', 18, NULL, NULL),
(111, 59, 'Bite', 'Dark', 18, NULL, NULL),
(112, 59, 'Flamethrower', 'Fire', 25, NULL, NULL),
(113, 60, 'Water Gun', 'Water', 10, NULL, NULL),
(114, 60, 'Hypnosis', 'Psychic', 0, 'sleep', 40),
(115, 61, 'Water Gun', 'Water', 15, NULL, NULL),
(116, 61, 'Hypnosis', 'Psychic', 0, 'sleep', 40),
(117, 62, 'Water Gun', 'Water', 18, NULL, NULL),
(118, 62, 'Submission', 'Fighting', 25, NULL, NULL),
(119, 63, 'Confusion', 'Psychic', 10, NULL, NULL),
(120, 63, 'Teleport', 'Psychic', 0, 'escape', 40),
(121, 64, 'Confusion', 'Psychic', 18, NULL, NULL),
(122, 64, 'Psychic', 'Psychic', 25, NULL, NULL),
(123, 65, 'Confusion', 'Psychic', 25, NULL, NULL),
(124, 65, 'Psychic', 'Psychic', 35, NULL, NULL),
(125, 66, 'Low Kick', 'Fighting', 10, NULL, NULL),
(126, 66, 'Karate Chop', 'Fighting', 15, NULL, NULL),
(127, 67, 'Low Kick', 'Fighting', 18, NULL, NULL),
(128, 67, 'Karate Chop', 'Fighting', 20, NULL, NULL),
(129, 68, 'Low Kick', 'Fighting', 25, NULL, NULL),
(130, 68, 'Karate Chop', 'Fighting', 30, NULL, NULL),
(131, 69, 'Vine Whip', 'Grass', 10, NULL, NULL),
(132, 69, 'Acid', 'Poison', 15, NULL, NULL),
(133, 70, 'Vine Whip', 'Grass', 15, NULL, NULL),
(134, 70, 'Acid', 'Poison', 20, NULL, NULL),
(135, 71, 'SolarBeam', 'Grass', 25, NULL, NULL),
(136, 71, 'Sludge Bomb', 'Poison', 30, 'poison', 30),
(137, 72, 'Acid', 'Poison', 10, NULL, NULL),
(138, 72, 'Water Gun', 'Water', 15, NULL, NULL),
(139, 73, 'Acid', 'Poison', 18, NULL, NULL),
(140, 73, 'Sludge Bomb', 'Poison', 25, 'poison', 30),
(141, 74, 'Tackle', 'Normal', 10, NULL, NULL),
(142, 74, 'Rock Throw', 'Rock', 15, NULL, NULL),
(143, 75, 'Tackle', 'Normal', 15, NULL, NULL),
(144, 75, 'Rock Slide', 'Rock', 25, NULL, NULL),
(145, 76, 'Tackle', 'Normal', 18, NULL, NULL),
(146, 76, 'Earthquake', 'Ground', 30, NULL, NULL),
(147, 77, 'Tackle', 'Normal', 10, NULL, NULL),
(148, 77, 'Ember', 'Fire', 15, NULL, NULL),
(149, 78, 'Tackle', 'Normal', 18, NULL, NULL),
(150, 78, 'Flamethrower', 'Fire', 25, NULL, NULL),
(151, 79, 'Confusion', 'Psychic', 10, NULL, NULL),
(152, 79, 'Water Gun', 'Water', 15, NULL, NULL),
(153, 80, 'Confusion', 'Psychic', 18, NULL, NULL),
(154, 80, 'Surf', 'Water', 25, NULL, NULL),
(155, 81, 'Tackle', 'Normal', 10, NULL, NULL),
(156, 81, 'ThunderShock', 'Electric', 15, NULL, NULL),
(157, 82, 'Tackle', 'Normal', 15, NULL, NULL),
(158, 82, 'Thunderbolt', 'Electric', 25, NULL, NULL),
(159, 83, 'Peck', 'Flying', 10, NULL, NULL),
(160, 83, 'Aerial Ace', 'Flying', 15, NULL, NULL),
(161, 84, 'Peck', 'Flying', 10, NULL, NULL),
(162, 84, 'Quick Attack', 'Normal', 15, NULL, NULL),
(163, 85, 'Peck', 'Flying', 18, NULL, NULL),
(164, 85, 'Drill Peck', 'Flying', 25, NULL, NULL),
(165, 86, 'Headbutt', 'Normal', 10, NULL, NULL),
(166, 86, 'Water Gun', 'Water', 15, NULL, NULL),
(167, 87, 'Headbutt', 'Normal', 18, NULL, NULL),
(168, 87, 'Ice Beam', 'Ice', 25, NULL, NULL),
(169, 88, 'Pound', 'Normal', 10, NULL, NULL),
(170, 88, 'Sludge', 'Poison', 15, NULL, NULL),
(171, 89, 'Pound', 'Normal', 18, NULL, NULL),
(172, 89, 'Sludge Bomb', 'Poison', 25, 'poison', 30),
(173, 90, 'Tackle', 'Normal', 10, NULL, NULL),
(174, 90, 'Bubble', 'Water', 15, NULL, NULL),
(175, 91, 'Tackle', 'Normal', 15, NULL, NULL),
(176, 91, 'Ice Beam', 'Ice', 25, NULL, NULL),
(177, 92, 'Lick', 'Ghost', 10, NULL, NULL),
(178, 92, 'Night Shade', 'Ghost', 15, NULL, NULL),
(179, 93, 'Lick', 'Ghost', 15, NULL, NULL),
(180, 93, 'Night Shade', 'Ghost', 18, NULL, NULL),
(181, 94, 'Lick', 'Ghost', 18, NULL, NULL),
(182, 94, 'Shadow Ball', 'Ghost', 25, NULL, NULL),
(183, 95, 'Tackle', 'Normal', 10, NULL, NULL),
(184, 95, 'Rock Throw', 'Rock', 15, NULL, NULL),
(185, 96, 'Confusion', 'Psychic', 10, NULL, NULL),
(186, 96, 'Headbutt', 'Normal', 15, NULL, NULL),
(187, 97, 'Confusion', 'Psychic', 18, NULL, NULL),
(188, 97, 'Psychic', 'Psychic', 25, NULL, NULL),
(189, 98, 'Vice Grip', 'Normal', 10, NULL, NULL),
(190, 98, 'Bubble', 'Water', 15, NULL, NULL),
(191, 99, 'Vice Grip', 'Normal', 18, NULL, NULL),
(192, 99, 'Crabhammer', 'Water', 25, NULL, NULL),
(193, 100, 'Tackle', 'Normal', 10, NULL, NULL),
(194, 100, 'ThunderShock', 'Electric', 15, NULL, NULL),
(195, 101, 'Tackle', 'Normal', 18, NULL, NULL),
(196, 101, 'Thunderbolt', 'Electric', 25, NULL, NULL),
(197, 102, 'Tackle', 'Normal', 10, NULL, NULL),
(198, 102, 'Confusion', 'Psychic', 15, NULL, NULL),
(199, 103, 'Confusion', 'Psychic', 18, NULL, NULL),
(200, 103, 'SolarBeam', 'Grass', 30, NULL, NULL),
(201, 104, 'Bone Club', 'Ground', 10, NULL, NULL),
(202, 104, 'Rage', 'Normal', 15, NULL, NULL),
(203, 105, 'Bone Club', 'Ground', 18, NULL, NULL),
(204, 105, 'Bonemerang', 'Ground', 25, NULL, NULL),
(205, 106, 'High Jump Kick', 'Fighting', 25, NULL, NULL),
(206, 106, 'Rolling Kick', 'Fighting', 15, NULL, NULL),
(207, 107, 'Mach Punch', 'Fighting', 15, NULL, NULL),
(208, 107, 'Fire Punch', 'Fire', 18, NULL, NULL),
(209, 108, 'Lick', 'Normal', 10, NULL, NULL),
(210, 108, 'Body Slam', 'Normal', 15, NULL, NULL),
(211, 109, 'Tackle', 'Normal', 10, NULL, NULL),
(212, 109, 'Smog', 'Poison', 15, 'poison', 30),
(213, 110, 'Tackle', 'Normal', 18, NULL, NULL),
(214, 110, 'Sludge', 'Poison', 25, 'poison', 30),
(215, 111, 'Tackle', 'Normal', 10, NULL, NULL),
(216, 111, 'Horn Attack', 'Normal', 15, NULL, NULL),
(217, 112, 'Tackle', 'Normal', 18, NULL, NULL),
(218, 112, 'Earthquake', 'Ground', 30, NULL, NULL),
(219, 113, 'Pound', 'Normal', 10, NULL, NULL),
(220, 113, 'Double-Edge', 'Normal', 15, NULL, NULL),
(221, 114, 'Tackle', 'Normal', 10, NULL, NULL),
(222, 114, 'Bind', 'Normal', 15, NULL, NULL),
(223, 115, 'Comet Punch', 'Normal', 10, NULL, NULL),
(224, 115, 'Mega Punch', 'Normal', 15, NULL, NULL),
(225, 116, 'Bubble', 'Water', 10, NULL, NULL),
(226, 116, 'Smokescreen', 'Normal', 15, NULL, NULL),
(227, 117, 'Bubble', 'Water', 18, NULL, NULL),
(228, 117, 'Hydro Pump', 'Water', 25, NULL, NULL),
(229, 118, 'Peck', 'Flying', 10, NULL, NULL),
(230, 118, 'Water Gun', 'Water', 15, NULL, NULL),
(231, 119, 'Peck', 'Flying', 18, NULL, NULL),
(232, 119, 'Horn Drill', 'Normal', 25, NULL, NULL),
(233, 120, 'Tackle', 'Normal', 10, NULL, NULL),
(234, 120, 'Water Gun', 'Water', 15, NULL, NULL),
(235, 121, 'Tackle', 'Normal', 18, NULL, NULL),
(236, 121, 'Psychic', 'Psychic', 25, NULL, NULL),
(237, 122, 'Confusion', 'Psychic', 15, NULL, NULL),
(238, 122, 'Barrier', 'Psychic', 18, NULL, NULL),
(239, 123, 'Quick Attack', 'Normal', 10, NULL, NULL),
(240, 123, 'Wing Attack', 'Flying', 15, NULL, NULL),
(241, 124, 'Lick', 'Ghost', 10, NULL, NULL),
(242, 124, 'Ice Punch', 'Ice', 15, NULL, NULL),
(243, 125, 'Quick Attack', 'Normal', 10, NULL, NULL),
(244, 125, 'Thunderbolt', 'Electric', 18, NULL, NULL),
(245, 126, 'Ember', 'Fire', 10, NULL, NULL),
(246, 126, 'Fire Blast', 'Fire', 18, NULL, NULL),
(247, 127, 'Vice Grip', 'Normal', 10, NULL, NULL),
(248, 127, 'Swords Dance', 'Normal', 15, NULL, NULL),
(249, 128, 'Tackle', 'Normal', 10, NULL, NULL),
(250, 128, 'Horn Attack', 'Normal', 15, NULL, NULL),
(251, 129, 'Splash', 'Normal', 0, NULL, NULL),
(252, 129, 'Tackle', 'Normal', 10, NULL, NULL),
(253, 130, 'Bite', 'Dark', 25, NULL, NULL),
(254, 130, 'Hyper Beam', 'Normal', 45, NULL, NULL),
(255, 131, 'Water Gun', 'Water', 15, NULL, NULL),
(256, 131, 'Ice Beam', 'Ice', 25, NULL, NULL),
(257, 132, 'Transform', 'Normal', 0, 'escape', 40),
(258, 133, 'Tackle', 'Normal', 10, NULL, NULL),
(259, 133, 'Quick Attack', 'Normal', 15, NULL, NULL),
(260, 134, 'Water Gun', 'Water', 25, NULL, NULL),
(261, 134, 'Hydro Pump', 'Water', 35, NULL, NULL),
(262, 135, 'Quick Attack', 'Normal', 20, NULL, NULL),
(263, 135, 'Thunderbolt', 'Electric', 40, NULL, NULL),
(264, 136, 'Ember', 'Fire', 25, NULL, NULL),
(265, 136, 'Fire Blast', 'Fire', 32, NULL, NULL),
(266, 137, 'Tackle', 'Normal', 10, NULL, NULL),
(267, 137, 'Conversion', 'Normal', 0, 'escape', 40),
(268, 138, 'Tackle', 'Normal', 10, NULL, NULL),
(269, 138, 'Water Gun', 'Water', 15, NULL, NULL),
(270, 139, 'Water Gun', 'Water', 18, NULL, NULL),
(271, 139, 'Hydro Pump', 'Water', 25, NULL, NULL),
(272, 140, 'Tackle', 'Normal', 10, NULL, NULL),
(273, 140, 'Water Gun', 'Water', 15, NULL, NULL),
(274, 141, 'Aqua Jet', 'Water', 20, NULL, NULL),
(275, 141, 'Slash', 'Normal', 18, NULL, NULL),
(276, 142, 'Bite', 'Dark', 25, NULL, NULL),
(277, 142, 'Hyper Beam', 'Normal', 45, NULL, NULL),
(278, 143, 'Body Slam', 'Normal', 25, 'stun', 60),
(279, 143, 'Hyper Beam', 'Normal', 45, NULL, NULL),
(280, 144, 'Peck', 'Flying', 25, NULL, NULL),
(281, 144, 'Ice Beam', 'Ice', 45, 'freeze', 40),
(282, 145, 'Drill Peck', 'Flying', 30, NULL, NULL),
(283, 145, 'Thunderbolt', 'Electric', 45, 'shock', 40),
(284, 146, 'Fire Spin', 'Fire', 30, NULL, NULL),
(285, 146, 'Flamethrower', 'Fire', 45, 'burn', 40),
(286, 147, 'Wrap', 'Normal', 10, NULL, NULL),
(287, 147, 'Leer', 'Normal', 14, NULL, NULL),
(288, 148, 'Dragon Rage', 'Dragon', 18, NULL, NULL),
(289, 148, 'Thunder Wave', 'Electric', 23, NULL, NULL),
(290, 149, 'Hyper Beam', 'Normal', 45, NULL, NULL),
(291, 149, 'Dragon Rush', 'Dragon', 35, NULL, NULL),
(292, 150, 'Psychic', 'Psychic', 50, NULL, NULL),
(293, 150, 'Hyper Beam', 'Normal', 45, NULL, NULL),
(294, 151, 'Pound', 'Normal', 10, NULL, NULL),
(295, 151, 'Psychic', 'Psychic', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `matchresult`
--

CREATE TABLE `matchresult` (
  `id` int(11) NOT NULL,
  `playerWon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matchresult`
--

INSERT INTO `matchresult` (`id`, `playerWon`) VALUES
(15, 'player2'),
(16, 'player2'),
(17, 'player1'),
(18, 'player2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `online_players`
--

CREATE TABLE `online_players` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_players`
--

INSERT INTO `online_players` (`id`, `user_id`, `nickname`) VALUES
(1, 'e57ee1d6-b3ca-4261-93cf-eb50e1255e6d', 'Deathinio'),
(2, 'eb77f233-1002-4bc1-9f22-2736233a860c', 'Deathik999');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pending_players`
--

CREATE TABLE `pending_players` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `nickname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_players`
--

INSERT INTO `pending_players` (`id`, `user_id`, `nickname`) VALUES
(235, '95fbe5d6-1e43-47d5-a6a9-ffd1a18e42ce', 'Deathinio123');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `player1lvl`
--

CREATE TABLE `player1lvl` (
  `id` int(11) NOT NULL,
  `lvl` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player1lvl`
--

INSERT INTO `player1lvl` (`id`, `lvl`) VALUES
(1, 10);

--
-- Wyzwalacze `player1lvl`
--
DELIMITER $$
CREATE TRIGGER `check_player1_winner` AFTER UPDATE ON `player1lvl` FOR EACH ROW BEGIN
    -- Sprawdzanie, czy player1 osiągnął poziom 12
    IF NEW.lvl = 12 THEN
        -- Dodanie wpisu do matchresult
        INSERT INTO matchresult (playerWon) VALUES ('player1');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `player1_squad`
--

CREATE TABLE `player1_squad` (
  `id_pokemona` int(11) NOT NULL,
  `lvl` int(11) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `statusID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player1_squad`
--

INSERT INTO `player1_squad` (`id_pokemona`, `lvl`, `hp`, `statusID`) VALUES
(1, 1, 10, 1),
(2, 6, 100, 2),
(3, 9, 150, 3),
(8, 6, 100, 1),
(9, 9, 150, 2),
(12, 9, 150, 3);

--
-- Wyzwalacze `player1_squad`
--
DELIMITER $$
CREATE TRIGGER `after_hp_update_player1` AFTER UPDATE ON `player1_squad` FOR EACH ROW BEGIN
    -- Sprawdź, czy HP spadło do 0
    IF NEW.hp = 0 AND OLD.hp > 0 THEN
        -- Zwiększ poziom gracza 2
        UPDATE player2lvl SET lvl = lvl + 1 WHERE id = 1; -- Załóżmy, że gracz 2 ma id = 1
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `player2lvl`
--

CREATE TABLE `player2lvl` (
  `id` int(11) NOT NULL,
  `lvl` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player2lvl`
--

INSERT INTO `player2lvl` (`id`, `lvl`) VALUES
(1, 9);

--
-- Wyzwalacze `player2lvl`
--
DELIMITER $$
CREATE TRIGGER `check_player2_winner` AFTER UPDATE ON `player2lvl` FOR EACH ROW BEGIN
    -- Sprawdzanie, czy player2 osiągnął poziom 12
    IF NEW.lvl = 12 THEN
        -- Dodanie wpisu do matchresult
        INSERT INTO matchresult (playerWon) VALUES ('player2');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `player2_squad`
--

CREATE TABLE `player2_squad` (
  `id_pokemona` int(11) NOT NULL,
  `lvl` int(11) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `statusID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player2_squad`
--

INSERT INTO `player2_squad` (`id_pokemona`, `lvl`, `hp`, `statusID`) VALUES
(4, 1, 30, 1),
(5, 6, 100, 2),
(6, 9, 150, 3),
(10, 1, 0, 1),
(17, 6, 100, 2),
(19, 1, 50, 3);

--
-- Wyzwalacze `player2_squad`
--
DELIMITER $$
CREATE TRIGGER `after_hp_update_player2` AFTER UPDATE ON `player2_squad` FOR EACH ROW BEGIN
    -- Sprawdź, czy HP spadło do 0
    IF NEW.hp = 0 AND OLD.hp > 0 THEN
        -- Zwiększ poziom gracza 1
        UPDATE player1lvl SET lvl = lvl + 1 WHERE id = 1; -- Załóżmy, że gracz 1 ma id = 1
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `player_round`
--

CREATE TABLE `player_round` (
  `player_round` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pokemony`
--

CREATE TABLE `pokemony` (
  `indeks` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `typ1` varchar(20) NOT NULL,
  `typ2` varchar(20) DEFAULT NULL,
  `lvlstart` int(11) DEFAULT 1,
  `hpstart` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pokemony`
--

INSERT INTO `pokemony` (`indeks`, `nazwa`, `typ1`, `typ2`, `lvlstart`, `hpstart`) VALUES
(1, 'Bulbasaur', 'Grass', 'Poison', 1, 50),
(2, 'Ivysaur', 'Grass', 'Poison', 6, 100),
(3, 'Venusaur', 'Grass', 'Poison', 9, 150),
(4, 'Charmander', 'Fire', NULL, 1, 50),
(5, 'Charmeleon', 'Fire', NULL, 6, 100),
(6, 'Charizard', 'Fire', 'Flying', 9, 150),
(7, 'Squirtle', 'Water', NULL, 1, 50),
(8, 'Wartortle', 'Water', NULL, 6, 100),
(9, 'Blastoise', 'Water', NULL, 9, 150),
(10, 'Caterpie', 'Bug', NULL, 1, 50),
(11, 'Metapod', 'Bug', NULL, 6, 100),
(12, 'Butterfree', 'Bug', 'Flying', 9, 150),
(13, 'Weedle', 'Bug', 'Poison', 1, 50),
(14, 'Kakuna', 'Bug', 'Poison', 6, 100),
(15, 'Beedrill', 'Bug', 'Poison', 9, 150),
(16, 'Pidgey', 'Normal', 'Flying', 1, 50),
(17, 'Pidgeotto', 'Normal', 'Flying', 6, 100),
(18, 'Pidgeot', 'Normal', 'Flying', 9, 150),
(19, 'Rattata', 'Normal', NULL, 1, 50),
(20, 'Raticate', 'Normal', NULL, 6, 100),
(21, 'Spearow', 'Normal', 'Flying', 1, 50),
(22, 'Fearow', 'Normal', 'Flying', 6, 100),
(23, 'Ekans', 'Poison', NULL, 1, 50),
(24, 'Arbok', 'Poison', NULL, 6, 100),
(25, 'Pikachu', 'Electric', NULL, 1, 50),
(26, 'Raichu', 'Electric', NULL, 6, 100),
(27, 'Sandshrew', 'Ground', NULL, 1, 50),
(28, 'Sandslash', 'Ground', NULL, 6, 100),
(29, 'Nidoran♀', 'Poison', NULL, 1, 50),
(30, 'Nidoran♂', 'Poison', NULL, 6, 100),
(31, 'Nidoking', 'Poison', 'Ground', 9, 150),
(35, 'Clefairy', 'Fairy', NULL, 1, 50),
(36, 'Clefable', 'Fairy', NULL, 6, 100),
(37, 'Vulpix', 'Fire', NULL, 1, 50),
(38, 'Ninetales', 'Fire', NULL, 6, 100),
(39, 'Jigglypuff', 'Normal', 'Fairy', 1, 50),
(40, 'Wigglytuff', 'Normal', 'Fairy', 6, 100),
(41, 'Zubat', 'Poison', 'Flying', 1, 50),
(42, 'Golbat', 'Poison', 'Flying', 6, 100),
(43, 'Oddish', 'Grass', 'Poison', 1, 50),
(44, 'Gloom', 'Grass', 'Poison', 6, 100),
(45, 'Vileplume', 'Grass', 'Poison', 1, 50),
(46, 'Paras', 'Bug', 'Grass', 1, 50),
(47, 'Parasect', 'Bug', 'Grass', 6, 100),
(48, 'Venonat', 'Bug', 'Poison', 1, 50),
(49, 'Venomoth', 'Bug', 'Poison', 6, 100),
(50, 'Diglett', 'Ground', NULL, 1, 50),
(51, 'Dugtrio', 'Ground', NULL, 6, 100),
(52, 'Meowth', 'Normal', NULL, 1, 50),
(53, 'Persian', 'Normal', NULL, 6, 100),
(54, 'Psyduck', 'Water', NULL, 1, 50),
(55, 'Golduck', 'Water', NULL, 6, 100),
(56, 'Mankey', 'Fighting', NULL, 1, 50),
(57, 'Primeape', 'Fighting', NULL, 6, 100),
(58, 'Growlithe', 'Fire', NULL, 1, 50),
(59, 'Arcanine', 'Fire', NULL, 6, 100),
(60, 'Poliwag', 'Water', NULL, 1, 50),
(61, 'Poliwhirl', 'Water', NULL, 6, 100),
(62, 'Poliwrath', 'Water', 'Fighting', 9, 150),
(63, 'Abra', 'Psychic', NULL, 1, 50),
(64, 'Kadabra', 'Psychic', NULL, 6, 100),
(65, 'Alakazam', 'Psychic', NULL, 9, 150),
(66, 'Machop', 'Fighting', NULL, 1, 50),
(67, 'Machoke', 'Fighting', NULL, 6, 100),
(68, 'Machamp', 'Fighting', NULL, 9, 150),
(69, 'Bellsprout', 'Grass', 'Poison', 1, 50),
(70, 'Weepinbell', 'Grass', 'Poison', 6, 100),
(71, 'Victreebel', 'Grass', 'Poison', 9, 150),
(72, 'Tentacool', 'Water', 'Poison', 1, 50),
(73, 'Tentacruel', 'Water', 'Poison', 6, 100),
(74, 'Geodude', 'Rock', 'Ground', 1, 50),
(75, 'Graveler', 'Rock', 'Ground', 6, 100),
(76, 'Golem', 'Rock', 'Ground', 9, 150),
(77, 'Ponyta', 'Fire', NULL, 1, 50),
(78, 'Rapidash', 'Fire', NULL, 6, 100),
(79, 'Slowpoke', 'Water', 'Psychic', 1, 50),
(80, 'Slowbro', 'Water', 'Psychic', 6, 100),
(81, 'Magnemite', 'Electric', 'Steel', 1, 50),
(82, 'Magneton', 'Electric', 'Steel', 6, 100),
(83, 'Farfetch\'d', 'Normal', 'Flying', 1, 50),
(84, 'Doduo', 'Normal', 'Flying', 6, 100),
(85, 'Dodrio', 'Normal', 'Flying', 9, 150),
(86, 'Seel', 'Water', NULL, 1, 50),
(87, 'Dewgong', 'Water', 'Ice', 6, 100),
(88, 'Grimer', 'Poison', NULL, 1, 50),
(89, 'Muk', 'Poison', NULL, 6, 100),
(90, 'Shellder', 'Water', NULL, 1, 50),
(91, 'Cloyster', 'Water', 'Ice', 6, 100),
(92, 'Gastly', 'Ghost', 'Poison', 1, 50),
(93, 'Haunter', 'Ghost', 'Poison', 6, 100),
(94, 'Gengar', 'Ghost', 'Poison', 9, 150),
(95, 'Onix', 'Rock', 'Ground', 1, 50),
(96, 'Drowzee', 'Psychic', NULL, 1, 50),
(97, 'Hypno', 'Psychic', NULL, 6, 100),
(98, 'Krabby', 'Water', NULL, 1, 50),
(99, 'Kingler', 'Water', NULL, 6, 100),
(100, 'Voltorb', 'Electric', NULL, 1, 50),
(101, 'Electrode', 'Electric', NULL, 6, 100),
(102, 'Exeggcute', 'Grass', 'Psychic', 1, 50),
(103, 'Exeggutor', 'Grass', 'Psychic', 6, 100),
(104, 'Cubone', 'Ground', NULL, 1, 50),
(105, 'Marowak', 'Ground', NULL, 6, 100),
(106, 'Hitmonlee', 'Fighting', NULL, 1, 50),
(107, 'Hitmonchan', 'Fighting', NULL, 6, 100),
(108, 'Lickitung', 'Normal', NULL, 1, 50),
(109, 'Koffing', 'Poison', NULL, 1, 50),
(110, 'Weezing', 'Poison', NULL, 6, 100),
(111, 'Rhyhorn', 'Ground', 'Rock', 1, 50),
(112, 'Rhydon', 'Ground', 'Rock', 6, 100),
(113, 'Chansey', 'Normal', NULL, 1, 50),
(114, 'Tangela', 'Grass', NULL, 1, 50),
(115, 'Kangaskhan', 'Normal', NULL, 1, 50),
(116, 'Horsea', 'Water', NULL, 1, 50),
(117, 'Seadra', 'Water', NULL, 6, 100),
(118, 'Goldeen', 'Water', NULL, 1, 50),
(119, 'Seaking', 'Water', NULL, 6, 100),
(120, 'Staryu', 'Water', NULL, 1, 50),
(121, 'Starmie', 'Water', 'Psychic', 6, 100),
(122, 'Mr. Mime', 'Psychic', 'Fairy', 1, 50),
(123, 'Scyther', 'Bug', 'Flying', 1, 50),
(124, 'Jynx', 'Ice', 'Psychic', 1, 50),
(125, 'Electabuzz', 'Electric', NULL, 1, 50),
(126, 'Magmar', 'Fire', NULL, 1, 50),
(127, 'Pinsir', 'Bug', NULL, 1, 50),
(128, 'Tauros', 'Normal', NULL, 1, 50),
(129, 'Magikarp', 'Water', NULL, 1, 50),
(130, 'Gyarados', 'Water', 'Flying', 6, 100),
(131, 'Lapras', 'Water', 'Ice', 9, 150),
(132, 'Ditto', 'Normal', NULL, 1, 50),
(133, 'Eevee', 'Normal', NULL, 1, 50),
(134, 'Vaporeon', 'Water', NULL, 6, 100),
(135, 'Jolteon', 'Electric', NULL, 6, 100),
(136, 'Flareon', 'Fire', NULL, 6, 100),
(137, 'Porygon', 'Normal', NULL, 1, 50),
(138, 'Omanyte', 'Rock', 'Water', 1, 50),
(139, 'Omastar', 'Rock', 'Water', 6, 100),
(140, 'Kabuto', 'Rock', 'Water', 1, 50),
(141, 'Kabutops', 'Rock', 'Water', 6, 100),
(142, 'Aerodactyl', 'Rock', 'Flying', 1, 50),
(143, 'Snorlax', 'Normal', NULL, 9, 200),
(144, 'Articuno', 'Ice', 'Flying', 9, 200),
(145, 'Zapdos', 'Electric', 'Flying', 9, 200),
(146, 'Moltres', 'Fire', 'Flying', 9, 200),
(147, 'Dratini', 'Dragon', NULL, 1, 50),
(148, 'Dragonair', 'Dragon', NULL, 6, 100),
(149, 'Dragonite', 'Dragon', 'Flying', 9, 150),
(150, 'Mewtwo', 'Psychic', NULL, 9, 200),
(151, 'Mew', 'Psychic', NULL, 1, 50);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reset_queue`
--

CREATE TABLE `reset_queue` (
  `id` int(11) NOT NULL,
  `player` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `squad1status`
--

CREATE TABLE `squad1status` (
  `StatusID` int(11) NOT NULL,
  `Stun` int(11) DEFAULT NULL,
  `Sleep` int(11) DEFAULT NULL,
  `Shock` int(11) DEFAULT NULL,
  `Poison` int(11) DEFAULT NULL,
  `Paralyze` int(11) DEFAULT NULL,
  `Freeze` int(11) DEFAULT NULL,
  `Escape` int(11) DEFAULT NULL,
  `DefenseBoost` int(11) DEFAULT NULL,
  `Confusion` int(11) DEFAULT NULL,
  `Burn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `squad1status`
--

INSERT INTO `squad1status` (`StatusID`, `Stun`, `Sleep`, `Shock`, `Poison`, `Paralyze`, `Freeze`, `Escape`, `DefenseBoost`, `Confusion`, `Burn`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `squad2status`
--

CREATE TABLE `squad2status` (
  `StatusID` int(11) NOT NULL,
  `Stun` int(11) DEFAULT NULL,
  `Sleep` int(11) DEFAULT NULL,
  `Shock` int(11) DEFAULT NULL,
  `Poison` int(11) DEFAULT NULL,
  `Paralyze` int(11) DEFAULT NULL,
  `Freeze` int(11) DEFAULT NULL,
  `Escape` int(11) DEFAULT NULL,
  `DefenseBoost` int(11) DEFAULT NULL,
  `Confusion` int(11) DEFAULT NULL,
  `Burn` int(11) DEFAULT NULL,
  `pokemon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `squad2status`
--

INSERT INTO `squad2status` (`StatusID`, `Stun`, `Sleep`, `Shock`, `Poison`, `Paralyze`, `Freeze`, `Escape`, `DefenseBoost`, `Confusion`, `Burn`, `pokemon_id`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `type_chart`
--

CREATE TABLE `type_chart` (
  `type` varchar(20) NOT NULL,
  `strong_against1` varchar(20) DEFAULT NULL,
  `strong_against2` varchar(20) DEFAULT NULL,
  `strong_against3` varchar(20) DEFAULT NULL,
  `strong_against4` varchar(20) DEFAULT NULL,
  `strong_against5` varchar(20) DEFAULT NULL,
  `weak_against1` varchar(20) DEFAULT NULL,
  `weak_against2` varchar(20) DEFAULT NULL,
  `weak_against3` varchar(20) DEFAULT NULL,
  `weak_against4` varchar(20) DEFAULT NULL,
  `weak_against5` varchar(20) DEFAULT NULL,
  `weak_against6` varchar(20) DEFAULT NULL,
  `immune_to` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_chart`
--

INSERT INTO `type_chart` (`type`, `strong_against1`, `strong_against2`, `strong_against3`, `strong_against4`, `strong_against5`, `weak_against1`, `weak_against2`, `weak_against3`, `weak_against4`, `weak_against5`, `weak_against6`, `immune_to`) VALUES
('Bug', 'Grass', 'Psychic', 'Dark', NULL, NULL, 'Fire', 'Fighting', 'Poison', 'Flying', 'Ghost', 'Steel', NULL),
('Dark', 'Psychic', 'Ghost', NULL, NULL, NULL, 'Fighting', 'Dark', 'Fairy', NULL, NULL, NULL, NULL),
('Dragon', 'Dragon', NULL, NULL, NULL, NULL, 'Steel', NULL, NULL, NULL, NULL, NULL, 'Fairy'),
('Electric', 'Water', 'Flying', NULL, NULL, NULL, 'Electric', 'Grass', 'Dragon', NULL, NULL, NULL, 'Ground'),
('Fairy', 'Fighting', 'Dragon', 'Dark', NULL, NULL, 'Fire', 'Poison', 'Steel', NULL, NULL, NULL, NULL),
('Fighting', 'Normal', 'Rock', 'Steel', 'Ice', 'Dark', 'Poison', 'Flying', 'Psychic', 'Bug', 'Fairy', NULL, 'Ghost'),
('Fire', 'Grass', 'Bug', 'Ice', 'Steel', NULL, 'Fire', 'Water', 'Rock', 'Dragon', NULL, NULL, NULL),
('Flying', 'Grass', 'Fighting', 'Bug', NULL, NULL, 'Electric', 'Rock', 'Steel', NULL, NULL, NULL, NULL),
('Ghost', 'Ghost', 'Psychic', NULL, NULL, NULL, 'Dark', NULL, NULL, NULL, NULL, NULL, 'Normal'),
('Grass', 'Water', 'Ground', 'Rock', NULL, NULL, 'Fire', 'Grass', 'Poison', 'Flying', 'Bug', 'Dragon', NULL),
('Ground', 'Fire', 'Electric', 'Poison', 'Rock', 'Steel', 'Grass', 'Bug', NULL, NULL, NULL, NULL, 'Flying'),
('Ice', 'Grass', 'Ground', 'Flying', 'Dragon', NULL, 'Fire', 'Water', 'Ice', 'Steel', NULL, NULL, NULL),
('Normal', NULL, NULL, NULL, NULL, NULL, 'Rock', 'Steel', NULL, NULL, NULL, NULL, 'Ghost'),
('Poison', 'Grass', 'Fairy', NULL, NULL, NULL, 'Poison', 'Ground', 'Rock', 'Ghost', NULL, NULL, 'Steel'),
('Psychic', 'Fighting', 'Poison', NULL, NULL, NULL, 'Psychic', 'Steel', NULL, NULL, NULL, NULL, 'Dark'),
('Rock', 'Fire', 'Ice', 'Flying', 'Bug', NULL, 'Fighting', 'Ground', 'Steel', NULL, NULL, NULL, NULL),
('Steel', 'Rock', 'Ice', 'Fairy', NULL, NULL, 'Fire', 'Water', 'Electric', 'Steel', NULL, NULL, NULL),
('Water', 'Fire', 'Ground', 'Rock', NULL, NULL, 'Water', 'Grass', 'Dragon', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `usedpokemons`
--

CREATE TABLE `usedpokemons` (
  `pokemon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usedpokemons`
--

INSERT INTO `usedpokemons` (`pokemon_id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(8),
(9),
(10),
(12),
(17),
(19);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `nickname`, `password`) VALUES
(12, 'e4bac8fa-b3c6-4c53-9db2-861f90c83201', 'Deathik123', '$2y$10$8yhQjLWU.o8cnlfbm3NOlu0pqmI.9XvDPJVB1en7UMmaxtsAf3pIO'),
(15, '8822cf22-e9ad-4df9-b8ba-9968ca7ece9c', 'Deathinio`', '$2y$10$9ool.79mf.mfzjDFUu9ruueu52LC6VlkdPpPDIvDCwSwUhS2lQYZu'),
(17, 'e57ee1d6-b3ca-4261-93cf-eb50e1255e6d', 'Deathinio', '$2y$10$L/E8/zuJQhH/ENKXOFGHUeGFja6UHLqPFy3QvBl9hDBZG.fwfNJK.'),
(18, 'd85ecb1a-4921-4a05-bd76-067ba68388b2', 'Deathiknio', '$2y$10$PmxvqhGkLV3Xl/zI.v2eeepRhOJ9Oo6HKiZJxMORtxkRrLAnMuTTG'),
(20, 'ce0d4e57-d5ef-4e5b-bb65-faae5e680fa3', 'Deathik', '$2y$10$sobedHTYse7l4qpHfnVpc.fEHLcUYnRO0Wq0mFg1AnUcNF8ihPwIK'),
(21, '1783e98e-5c8f-4785-a655-f5a3792e9ed7', 'Deathik1234', '$2y$10$hYfnH1d9.bh2P4S2jOpKyOfCvRE2ZIC2dPnN4fxdRnsBpGn9JzPQy'),
(22, 'eb77f233-1002-4bc1-9f22-2736233a860c', 'Deathik999', '$2y$10$FudV/7XlJP6VKTSU71arReoGjpSPrtnw36ZNIth2.mS3MVgSbaGB2'),
(23, '855086bf-19f3-478b-9174-3b73bff0336c', 'Ziomekson', '$2y$10$8JMLvcofuuThuW5JyyFh9O4JBtU/vWWRQMHKBjaiH0L6ffyd5X8zq'),
(24, 'b9f46974-c24e-4c54-b05f-ccafb4c8a9cd', 'ziomeczkos', '$2y$10$IQo5XerUT9muYxb0EQnJhO6K3IaMGGAjL6MjVn4ZbrZSAF7/U5o9C'),
(25, '1196f3da-d253-41ef-817a-eb590bb20687', 'ziomeix', '$2y$10$vp5xMWDA6u16NdcRbvupteWwYX5ie5auZKxWp7EZrfS6nxNnYPxZi'),
(26, '2e991d03-5ee3-47bb-9742-f7f28f4c4e8b', 'Ziomal', '$2y$10$A9cbbak7LP4MsqG2pJhX4einviVIYX5CLFSYjvdRZOqj/STV/SeeO'),
(27, '30d041de-c40f-408e-9dd2-47505e00d67f', 'Deathik12345', '$2y$10$UywTLbZYqAHKdyPVctSoCus7f2M70pIS/zo4nchHwG2ZdpNJdiQye'),
(28, '59689bf0-76e4-40c1-9644-61bc4ac8779d', 'abc', '$2y$10$8r4yGp7dFH./NP/OpX1KFef/JdGh9vxXNHnmF6Sxgj1tzOfQcKO9y'),
(35, '39339e99-bacf-4c1e-b28b-724be8cc6ffd', 'Deathik123456', '$2y$10$prH7TSCTQsmOABzV1Srx5e4w5441mCYCMyFlQZmbIea.T8OhvpVii'),
(36, '95fbe5d6-1e43-47d5-a6a9-ffd1a18e42ce', 'Deathinio123', '$2y$10$e4Cw3xBFkARRzD.bSh3x7Oh62mBg0HGyl5uzGEBcP5/zzQkv55nIm');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`indeks`),
  ADD KEY `pokemon_indeks` (`pokemon_indeks`);

--
-- Indeksy dla tabeli `matchresult`
--
ALTER TABLE `matchresult`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `online_players`
--
ALTER TABLE `online_players`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `pending_players`
--
ALTER TABLE `pending_players`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `player1lvl`
--
ALTER TABLE `player1lvl`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `player1_squad`
--
ALTER TABLE `player1_squad`
  ADD PRIMARY KEY (`id_pokemona`),
  ADD KEY `fk_player1_status` (`statusID`);

--
-- Indeksy dla tabeli `player2lvl`
--
ALTER TABLE `player2lvl`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `player2_squad`
--
ALTER TABLE `player2_squad`
  ADD PRIMARY KEY (`id_pokemona`),
  ADD KEY `fk_player2_status` (`statusID`);

--
-- Indeksy dla tabeli `pokemony`
--
ALTER TABLE `pokemony`
  ADD PRIMARY KEY (`indeks`);

--
-- Indeksy dla tabeli `reset_queue`
--
ALTER TABLE `reset_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `squad1status`
--
ALTER TABLE `squad1status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indeksy dla tabeli `squad2status`
--
ALTER TABLE `squad2status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indeksy dla tabeli `type_chart`
--
ALTER TABLE `type_chart`
  ADD PRIMARY KEY (`type`);

--
-- Indeksy dla tabeli `usedpokemons`
--
ALTER TABLE `usedpokemons`
  ADD PRIMARY KEY (`pokemon_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abilities`
--
ALTER TABLE `abilities`
  MODIFY `indeks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `matchresult`
--
ALTER TABLE `matchresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pending_players`
--
ALTER TABLE `pending_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `abilities`
--
ALTER TABLE `abilities`
  ADD CONSTRAINT `abilities_ibfk_1` FOREIGN KEY (`pokemon_indeks`) REFERENCES `pokemony` (`indeks`);

--
-- Constraints for table `player1_squad`
--
ALTER TABLE `player1_squad`
  ADD CONSTRAINT `fk_player1_status` FOREIGN KEY (`statusID`) REFERENCES `squad1status` (`StatusID`);

--
-- Constraints for table `player2_squad`
--
ALTER TABLE `player2_squad`
  ADD CONSTRAINT `fk_player2_status` FOREIGN KEY (`statusID`) REFERENCES `squad2status` (`StatusID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
