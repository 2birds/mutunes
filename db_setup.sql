-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2014 at 02:10 PM
-- Server version: 5.5.35
-- PHP Version: 5.4.4-14+deb7u7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mutunes`
--

-- --------------------------------------------------------

--
-- Table structure for table `battles`
--

CREATE TABLE IF NOT EXISTS `battles` (
  `battleId` int(11) NOT NULL AUTO_INCREMENT,
  `winnerId` int(11) NOT NULL,
  `loserId` int(11) NOT NULL,
  `musician` tinyint(1) NOT NULL,
  PRIMARY KEY (`battleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `battles`
--

INSERT INTO `battles` (`battleId`, `winnerId`, `loserId`, `musician`) VALUES
(1, 8, 1, 0),
(2, 7, 2, 0),
(3, 9, 3, 0),
(4, 3, 4, 0),
(5, 2, 5, 0),
(6, 7, 6, 0),
(7, 7, 2, 0),
(8, 8, 11, 0),
(9, 9, 11, 0),
(10, 9, 10, 0),
(11, 13, 14, 0),
(12, 14, 4, 0),
(13, 3, 8, 0),
(14, 8, 4, 0),
(15, 7, 16, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contributors`
--

CREATE TABLE IF NOT EXISTS `contributors` (
  `name` varchar(75) NOT NULL,
  `email` varchar(75) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `name_2` (`name`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contributors`
--

INSERT INTO `contributors` (`name`, `email`) VALUES
('rob', 'r@r.com');

-- --------------------------------------------------------

--
-- Table structure for table `generalProperties`
--

CREATE TABLE IF NOT EXISTS `generalProperties` (
  `name` char(10) NOT NULL DEFAULT 'properties',
  `popsize` int(11) NOT NULL,
  `totalComparisons` int(11) NOT NULL,
  `generation` int(11) NOT NULL,
  `thresholdForStopping` float NOT NULL,
  `threshold` int(11) NOT NULL,
  `currentPosition` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `generalProperties`
--

INSERT INTO `generalProperties` (`name`, `popsize`, `totalComparisons`, `generation`, `thresholdForStopping`, `threshold`, `currentPosition`) VALUES
('properties', 10, 0, 0, 0, 3, 17);

-- --------------------------------------------------------

--
-- Table structure for table `melodies`
--

CREATE TABLE IF NOT EXISTS `melodies` (
  `id` bigint(5) NOT NULL,
  `melodyString` text NOT NULL,
  `wins` int(5) NOT NULL DEFAULT '0',
  `defeats` int(5) NOT NULL DEFAULT '0',
  `totalWins` int(5) NOT NULL DEFAULT '0',
  `totalDefeats` int(5) NOT NULL DEFAULT '0',
  `introducedAtGeneration` int(3) NOT NULL,
  `parentAId` int(5) NOT NULL,
  `parentBId` int(5) NOT NULL,
  `inPopulation` tinyint(1) NOT NULL,
  `position` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `melodies`
--

INSERT INTO `melodies` (`id`, `melodyString`, `wins`, `defeats`, `totalWins`, `totalDefeats`, `introducedAtGeneration`, `parentAId`, `parentBId`, `inPopulation`, `position`) VALUES
(1, '0:4,-3:4,6:4,-1:4,0:4,-7:4,-3:4,-1:4,2:4,2:4,-3:4,5:4,10:4,-3:4,-1:4,-4:4', 0, 1, 0, 1, 0, 0, 0, 0, 1),
(2, '0:4,-5:4,-3:4,0:4,1:4,-4:4,0:4,6:4,-4:4,1:4,8:4,5:4,-7:4,8:4,-9:4,-6:4', 1, 2, 1, 2, 0, 0, 0, 0, 2),
(3, '0:4,1:4,-7:4,-1:4,2:4,1:4,-5:4,1:4,4:4,2:4,-5:4,-2:4,3:4,0:4,-4:4,1:4', 0, 0, 2, 1, 0, 0, 0, 1, 3),
(4, '0:4,-3:4,0:4,-1:4,-2:4,8:4,-4:4,-2:4,7:4,-5:4,6:4,-8:4,7:4,-3:4,2:4,2:4', 0, 3, 0, 3, 0, 0, 0, 0, 4),
(5, '0:4,1:4,-8:4,8:4,5:4,-4:4,-5:4,0:4,2:4,1:4,-1:4,0:4,0:4,0:4,-3:4,6:4', 0, 1, 0, 1, 0, 0, 0, 0, 5),
(6, '0:4,-3:4,-1:4,-6:4,-2:4,1:4,3:4,5:4,-8:4,-1:4,3:4,3:4,5:4,-9:4,0:4,1:4', 0, 1, 0, 1, 0, 0, 0, 0, 6),
(7, '0:4,-10:4,-1:4,2:4,10:4,7:4,-3:4,7:4,-4:4,4:4,-5:4,-7:4,5:4,7:4,0:4,-4:4', 1, 0, 4, 0, 0, 0, 0, 1, 7),
(8, '0:4,5:4,2:4,3:4,-5:4,1:4,3:4,1:4,-4:4,0:4,-2:4,-10:4,4:4,1:4,1:4,4:4', 1, 1, 3, 1, 0, 0, 0, 1, 8),
(9, '0:4,-3:4,0:4,5:4,-3:4,3:4,0:4,0:4,0:4,0:4,-7:4,-1:4,2:4,-4:4,4:4,-4:4', 0, 0, 3, 0, 0, 0, 0, 1, 9),
(10, '0:4,-1:4,2:4,1:4,6:4,-2:4,0:4,-4:4,-1:4,-2:4,4:4,2:4,1:4,0:4,1:4,3:4', 0, 1, 0, 1, 0, 0, 0, 1, 10),
(11, '0:4,-10:4,-1:4,2:4,10:4,7:4,-3:4,7:1,1:3,-4:4,0:4,-2:4,-10:4,4:4,1:4,1:4,4:4', 0, 2, 0, 2, 1, 7, 8, 0, 1),
(12, '0:4,5:4,2:4,3:4,-5:4,1:4,3:4,1:1,7:3,-4:4,4:4,-5:4,-6:4,5:4,7:4,0:4,-4:4', 0, 0, 0, 0, 1, 8, 7, 1, 6),
(13, '0:4,-3:4,0:4,5:4,-3:4,3:4,0:4,0:4,-4:4,0:4,-2:4,-10:4,4:4,1:4,1:4,4:4', 1, 0, 1, 0, 2, 9, 8, 1, 1),
(14, '0:4,5:4,2:4,3:4,-5:4,1:4,3:4,1:4,0:4,1:4,-7:4,-1:4,2:4,-4:4,4:4,-4:4', 1, 1, 1, 1, 2, 8, 9, 1, 2),
(15, '0:4,1:4,-7:4,-1:4,2:4,1:4,-5:4,1:1,0:3,0:4,0:4,-7:4,-1:4,1:4,-4:4,4:4,-4:4', 0, 0, 0, 0, 3, 3, 9, 1, 4),
(16, '0:4,-3:4,0:4,5:4,-3:4,3:4,0:4,0:1,1:3,4:4,2:4,-5:4,-2:4,3:4,0:4,-4:4,1:4', 0, 1, 0, 1, 3, 9, 3, 1, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
