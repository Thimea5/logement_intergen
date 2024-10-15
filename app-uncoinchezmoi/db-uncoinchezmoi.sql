-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 15 oct. 2024 à 19:35
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db-uncoinchezmoi`
--

-- --------------------------------------------------------

--
-- Structure de la table `guest`
--

DROP TABLE IF EXISTS `guest`;
CREATE TABLE IF NOT EXISTS `guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` varchar(155) DEFAULT NULL,
  `prix_max` double NOT NULL,
  `shopping` tinyint(1) DEFAULT 0,
  `gardening` tinyint(1) DEFAULT 0,
  `chores` tinyint(1) DEFAULT 0,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `host`
--

DROP TABLE IF EXISTS `host`;
CREATE TABLE IF NOT EXISTS `host` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `address` varchar(155) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `photo` varchar(155) NOT NULL,
  `type_logement` varchar(20) NOT NULL,
  `handicap` tinyint(1) DEFAULT 0,
  `smoking` tinyint(1) DEFAULT 0,
  `pets` tinyint(1) DEFAULT 0,
  `shopping` tinyint(1) DEFAULT 0,
  `gardening` tinyint(1) DEFAULT 0,
  `chores` tinyint(1) DEFAULT 0,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `available` tinyint(1) DEFAULT NULL,
  `id_host` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_host` (`id_host`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_post` int(11) NOT NULL,
  `id_guest` int(11) NOT NULL,
  PRIMARY KEY (`id_post`,`id_guest`),
  KEY `id_guest` (`id_guest`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  `password` varchar(155) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `marital_status` varchar(10) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `role` varchar(10) DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
