-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 03:15 AM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ws_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `ncustomers`
--

DROP TABLE IF EXISTS `ncustomers`;
CREATE TABLE IF NOT EXISTS `ncustomers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customerName` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `website` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phoneNo` int(11) DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `lastUpdated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ncustomers`
--

INSERT INTO `ncustomers` (`id`, `customerName`, `email`, `website`, `phoneNo`, `address`, `status`, `dateCreated`, `lastUpdated`) VALUES
(1, 'Tahir Khan', 'tahirpak@gmail.com', NULL, 123434, NULL, '1', NULL, '2012-06-21 07:41:18'),
(2, 'Tahir Khan', 'tahirpak1@gmail.com', NULL, 123434, NULL, '1', NULL, '2012-06-21 08:06:27'),
(3, 'Tahir Khan', 'tahirpak2@gmail.com', 'http://hotmail.com', 123434, NULL, '1', '2012-06-21 00:00:00', '2012-06-21 08:14:17'),
(4, 'Tahir Khan', 'tahirpak3@gmail.com', 'http://hotmail.com', 123434, NULL, '1', '2012-06-21 13:06:32', '2012-06-21 08:16:32'),
(5, 'Tahir Khan', 'tahirpak4@gmail.com', 'http://hotmail.com', 123434, NULL, '1', '2012-06-21 01:06:36', '2012-06-21 08:18:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
