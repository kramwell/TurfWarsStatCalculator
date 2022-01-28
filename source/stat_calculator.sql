-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 18, 2022 at 08:46 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stat_calculator`
--

-- --------------------------------------------------------

--
-- Table structure for table `saveresults`
--

DROP TABLE IF EXISTS `saveresults`;
CREATE TABLE IF NOT EXISTS `saveresults` (
  `id` varchar(32) NOT NULL,
  `weaponlist` varchar(2000) NOT NULL,
  `attack` varchar(32) NOT NULL,
  `defence` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `mobmembers` int(32) NOT NULL,
  `datestamp` int(10) NOT NULL,
  `showit` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

DROP TABLE IF EXISTS `weapons`;
CREATE TABLE IF NOT EXISTS `weapons` (
  `weaponname` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `attack` int(2) NOT NULL,
  `defence` int(2) NOT NULL,
  `price` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `upkeep` int(30) NOT NULL,
  `srtname` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `type` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `more` varchar(1) COLLATE latin1_general_ci NOT NULL DEFAULT 's',
  KEY `weaponname` (`weaponname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`weaponname`, `attack`, `defence`, `price`, `upkeep`, `srtname`, `type`, `more`) VALUES
('Riot Shield', 3, 6, '16000', 0, 'riotshield', 'armor', 's'),
('Kevlar Vest', 1, 4, '8000', 0, 'kevlarvest', 'armor', 's'),
('Body Armor', 2, 15, '25000', 200, 'bodyarmor', 'armor', 's'),
('Kevlar-Lined Suit', 4, 23, '50000', 0, 'kevlarlined', 'armor', 's'),
('Shank', 1, 0, '400', 0, 'shank', 'melee', 's'),
('Brass Knuckles', 3, 2, '4000', 0, 'brass', 'melee', ''),
('Saturday Night Special', 2, 1, '1000', 0, 'saturday', 'weapon', 's'),
('German Stiletto Knife', 7, 3, '35000', 0, 'german', 'melee', 's'),
('9mm Handgun', 3, 2, '2500', 0, 'handgun', 'weapon', 's'),
('Chainsaw', 10, 5, '30000', 0, 'chainsaw', 'melee', 's'),
('Galesi Model 503', 8, 9, '6500', 0, 'galesi', 'weapon', '\''),
('Molotov Cocktail', 7, 3, '4000', 0, 'molotov', 'weapon', 's'),
('.57 Magnum', 5, 3, '10000', 0, 'magnum', 'weapon', 's'),
('Grenade', 10, 8, '10000', 0, 'grenade', 'weapon', 's'),
('AK-47', 15, 12, '20000', 100, 'ak47', 'weapon', 's'),
('Sawed-Off Shotgun', 13, 14, '25000', 0, 'shotgun', 'weapon', 's'),
('Glock 31', 14, 10, '30000', 0, 'glock', 'weapon', 's'),
('xm400 Minigun', 18, 16, '100000', 250, 'xm400', 'weapon', 's'),
('RPG', 22, 14, '200000', 500, 'rpg', 'weapon', 's'),
('Tommy Gun', 24, 12, '300000', 750, 'tommygun', 'weapon', 's'),
('.338 Lapua Rifle', 20, 17, '350000', 350, 'lapua', 'weapon', 's'),
('AR-15 Assult Rifle', 30, 14, '500000', 0, 'ar15', 'weapon', 's'),
('Garrote', 3, 1, '5000', 0, 'garrote', 'melee', 's'),
('Meat Cleaver', 2, 1, '2000', 0, 'cleaver', 'melee', 's'),
('Steel-toed Shoes', 5, 4, '60000', 60, 'steeltoed', 'melee', ''),
('Lupara', 20, 20, '80000', 0, 'lupara', 'weapon', 's'),
('Machete', 12, 4, '110000', 0, 'machete', 'melee', 's'),
('Bazooka', 40, 0, '400000', 0, 'bazooka', 'weapon', 's'),
('Bren Gun', 50, 29, '700000', 0, 'brengun', 'weapon', 's'),
('Slugger', 9, 5, '75000', 0, 'slugger', 'melee', 's'),
('Potato Masher', 13, 9, '0', 0, 'potatomasher', 'weapon', 's'),
('Beretta Model 38A', 28, 20, '450000', 0, 'beretta', 'weapon', 's');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
