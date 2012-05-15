-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2012 at 10:35 AM
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
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webId` int(11) NOT NULL,
  `pageCaption` varchar(200) NOT NULL,
  `pageTitle` varchar(200) NOT NULL,
  `pageKeywords` varchar(200) NOT NULL,
  `pageMetatags` varchar(200) NOT NULL,
  `pageUrl` varchar(200) NOT NULL,
  `pageContent` longtext NOT NULL,
  `pageCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `reportStatus` tinyint(5) NOT NULL DEFAULT '0',
  `reportCheckStatus` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `webId`, `pageCaption`, `pageTitle`, `pageKeywords`, `pageMetatags`, `pageUrl`, `pageContent`, `pageCreated`, `status`, `reportStatus`, `reportCheckStatus`) VALUES
(2, 19, 'you', 'your page title', 'you', 'test', 'http://www.ausus.com/page', 'testing change', '2012-05-08 19:00:00', '1', 0, '0'),
(3, 19, 'test', 'test', 'terst', 'test', 'http://www.ausus.com/page2', 'testing ', '2012-05-08 19:00:00', '1', 0, '0'),
(4, 20, '', 'your page title', 'title page', '', 'http://www.phiplanet.com/page', 'check check check check ', '2012-05-10 17:12:58', '1', 0, '0'),
(5, 20, '', 'your page title', '', '', 'http://www.phiplanet.com/page', '', '2012-05-14 16:57:07', '1', 1, '0'),
(6, 22, '', 'your page title', 'page Keywords', '', 'http://www.spiders.com/page1', '', '2012-05-14 16:57:38', '1', 2, '0'),
(7, 22, '', 'your page title', 'page Keywords', '', 'http://www.yahoo.com/page2', 'Meta Description', '2012-05-14 17:04:55', '1', 3, ''),
(8, 22, '', 'your page title', 'page Keywords', '', 'http://www.yahoo.com/page3', 'Meta Description', '2012-05-14 17:07:03', '1', 3, '0'),
(9, 22, '', 'your page title', 'page Keywords', '', 'http://www.yahoo.com/page4', 'Meta Description', '2012-05-14 17:10:23', '1', 3, '0'),
(10, 19, '', '', '', '', 'http://www.ausus.com/p1', '', '2012-05-15 04:12:51', '1', 0, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
