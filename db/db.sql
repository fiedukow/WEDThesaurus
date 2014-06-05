-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 05, 2014 at 11:00 PM
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
  `literal` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE IF NOT EXISTS `synonyms` (
  `word_id` int(11) NOT NULL,
  `literal_id` int(11) NOT NULL,
  `quality` decimal(6,5) NOT NULL,
  `display_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`word_id`,`literal_id`),
  KEY `literal_id` (`literal_id`),
  KEY `word_id` (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8mb4_polish_ci NOT NULL,
  `text` text COLLATE utf8mb4_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `literal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `literal_id` (`literal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
