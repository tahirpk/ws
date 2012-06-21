-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 03:25 AM
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
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `modules_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `modules_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `modules_status` tinyint(1) NOT NULL DEFAULT '0',
  `sort_id` smallint(5) unsigned NOT NULL,
  `module_actual_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'index',
  PRIMARY KEY (`modules_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`modules_id`, `parent_id`, `modules_name`, `modules_status`, `sort_id`, `module_actual_name`, `action`) VALUES
(1, 0, 'index', 1, 1, 'Dashboard', 'index'),
(4, 0, 'websites', 1, 3, 'Websites', 'index'),
(13, 0, 'customer', 1, 2, 'Customers', 'index'),
(15, 13, 'customer', 1, 2, 'Customers', 'index'),
(19, 13, 'customer', 1, 2, 'Add New Customer', 'add'),
(20, 4, 'websites', 1, 3, 'Websites', 'index'),
(21, 4, 'websites', 1, 3, 'Add New Website', 'add'),
(22, 0, 'reports', 1, 4, 'Reports', 'index'),
(23, 22, 'reports', 1, 4, 'Reports', 'index'),
(25, 0, 'business', 1, 5, 'Business', 'index'),
(26, 25, 'business', 1, 5, ' Businesses', 'index'),
(27, 25, 'business', 1, 5, 'Add New Business', 'add'),
(28, 22, 'reports', 1, 4, 'Add New Report', 'add'),
(29, 0, 'pages', 1, 6, 'Control Panel', 'cronjobpages'),
(30, 29, 'pages', 1, 6, 'Control Panel', 'cronjobpages'),
(31, 0, 'leads', 1, 7, 'Leads', 'index');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
