-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 19, 2012 at 11:25 PM
-- Server version: 5.1.65
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flickpsc_svtgsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `description` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `status` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `designer`
--

CREATE TABLE IF NOT EXISTS `designer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='To add columns as needed.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g2u`
--

CREATE TABLE IF NOT EXISTS `g2u` (
  `gid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  PRIMARY KEY (`gid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `creator` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `status` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `genre` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `description` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `datestarted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(10) NOT NULL,
  `progress` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `invites` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `groupindex` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `url` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `l2c`
--

CREATE TABLE IF NOT EXISTS `l2c` (
  `lid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  PRIMARY KEY (`lid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Tid` int(10) NOT NULL,
  `xcoord` int(4) NOT NULL,
  `ycoord` int(4) NOT NULL,
  `hour` int(4) NOT NULL,
  `name` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL,
  `username` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `message` tinytext COLLATE latin1_general_ci NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `readstatus` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `suggestion` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `originator` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

CREATE TABLE IF NOT EXISTS `time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` tinytext COLLATE latin1_general_ci NOT NULL,
  `days` int(10) NOT NULL,
  `image` tinytext COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `passcode` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `email` tinytext COLLATE latin1_general_ci NOT NULL,
  `invites` text COLLATE latin1_general_ci NOT NULL,
  `gravatar` varchar(64) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wiki`
--

CREATE TABLE IF NOT EXISTS `wiki` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `changemade` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
