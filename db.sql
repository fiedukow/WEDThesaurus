-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 03, 2014 at 07:08 PM
-- Server version: 5.5.37-1
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `WEDT`
--

-- --------------------------------------------------------

--
-- Table structure for table `literals`
--

CREATE TABLE IF NOT EXISTS `literals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `literal` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE IF NOT EXISTS `synonyms` (
  `word_id` int(11) NOT NULL,
  `literal_id` int(11) NOT NULL,
  `quality` decimal(6,5) NOT NULL,
  KEY `literal_id` (`literal_id`),
  KEY `word_id` (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `literal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `literal_id` (`literal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `synonyms`
--
ALTER TABLE `synonyms`
  ADD CONSTRAINT `synonyms_literal_id` FOREIGN KEY (`literal_id`) REFERENCES `literals` (`id`),
  ADD CONSTRAINT `synonyms_words_id` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`);

--
-- Constraints for table `words`
--
ALTER TABLE `words`
  ADD CONSTRAINT `words_literals_id` FOREIGN KEY (`literal_id`) REFERENCES `literals` (`id`);

