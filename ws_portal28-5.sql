-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 05, 2012 at 04:26 PM
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
-- Table structure for table `websites`
--

DROP TABLE IF EXISTS `websites`;
CREATE TABLE IF NOT EXISTS `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `businessId` int(11) NOT NULL,
  `webTitle` varchar(100) NOT NULL,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `filePdf` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cronJobStatus` enum('1','0') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`createdAt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `websites`
--

INSERT INTO `websites` (`id`, `businessId`, `webTitle`, `url`, `filePdf`, `createdAt`, `status`, `cronJobStatus`) VALUES
(20, 0, 'web test', 'http://www.phiplanet.com/', 'frontend/webpdf/', '2012-04-26 07:57:42', '1', '1'),
(12, 3, 'web4', 'http://www.google.com.pk/', '', '2012-06-04 22:11:07', '0', '0'),
(19, 4, 'testi', 'http://www.hotmail.com', 'frontend/webpdf/', '2012-06-05 11:10:11', '1', '1'),
(21, 3, 'spider', 'http://www.spiders.com', 'frontend/webpdf/', '2012-05-08 17:51:47', '1', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
