-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 15, 2013 at 12:00 PM
-- Server version: 5.5.32-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `phpdbal_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `gtwy_tests`
--

DROP TABLE IF EXISTS `gtwy_tests`;
CREATE TABLE IF NOT EXISTS `gtwy_tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nullable_val` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `nullable_val` (`nullable_val`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table `join_tests`
--

DROP TABLE IF EXISTS `join_tests`;
CREATE TABLE IF NOT EXISTS `join_tests` (
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  UNIQUE KEY `composite_idx` (`id1`,`id2`),
  KEY `id1_idx` (`id1`),
  KEY `id2_idx` (`id2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


SET FOREIGN_KEY_CHECKS=1;


--
-- Add the created column to the join_tests table.
--
ALTER TABLE `join_tests` ADD `created` DATETIME NOT NULL;


