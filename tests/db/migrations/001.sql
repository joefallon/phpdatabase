-- phpMyAdmin SQL Dump
-- version 3.3.10.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2015 at 03:00 PM
-- Server version: 5.5.46
-- PHP Version: 7.0.1-2+deb.sury.org~trusty+1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpdatabase_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `example_entity_table`
--

DROP TABLE IF EXISTS `example_entity_table`;
CREATE TABLE IF NOT EXISTS `example_entity_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nullable` varchar(255) DEFAULT NULL,
  `numeral` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `nullable_val` (`nullable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `example_join_table`
--

DROP TABLE IF EXISTS `example_join_table`;
CREATE TABLE IF NOT EXISTS `example_join_table` (
  `id1` int(11) unsigned NOT NULL,
  `id2` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  UNIQUE KEY `composite_idx` (`id1`,`id2`),
  KEY `id1_idx` (`id1`),
  KEY `id2_idx` (`id2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
