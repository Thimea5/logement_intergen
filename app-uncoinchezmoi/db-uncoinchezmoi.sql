-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 16 oct. 2024 à 08:09
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

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
  `id` int NOT NULL AUTO_INCREMENT,
  `photo` varchar(155) DEFAULT NULL,
  `prix_max` double NOT NULL,
  `shopping` tinyint(1) DEFAULT '0',
  `gardening` tinyint(1) DEFAULT '0',
  `chores` tinyint(1) DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `guest`
--

-- --------------------------------------------------------

--
-- Structure de la table `host`
--

DROP TABLE IF EXISTS `host`;
CREATE TABLE IF NOT EXISTS `host` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `address` varchar(155) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `photo` varchar(155) NOT NULL,
  `type_logement` varchar(20) NOT NULL,
  `shopping` tinyint(1) DEFAULT '0',
  `gardening` tinyint(1) DEFAULT '0',
  `chores` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `host`
--

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `handicap` tinyint(1) DEFAULT '0',
  `smoking` tinyint(1) DEFAULT '0',
  `pets` tinyint(1) DEFAULT '0',
  `description` TEXT NOT NULL,
  `price` double NOT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `id_host` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_host` (`id_host`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `post`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_post` int NOT NULL,
  `id_guest` int NOT NULL,
  PRIMARY KEY (`id_post`,`id_guest`),
  KEY `id_guest` (`id_guest`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- Insertion de données dans la table `guest`
INSERT INTO `guest` (`photo`, `prix_max`, `shopping`, `gardening`, `chores`, `description`) VALUES
('photo1.jpg', 150.00, 1, 0, 1, 'Invité sympathique, aime le jardinage.'),
('photo2.jpg', 200.00, 0, 1, 0, 'Amateur de shopping, toujours prêt à aider.'),
('photo3.jpg', 100.00, 1, 1, 1, 'Jeune professionnel, très respectueux des lieux.'),
('photo4.jpg', 175.00, 0, 0, 1, 'Passionné par la cuisine et les tâches ménagères.'),
('photo5.jpg', 250.00, 1, 1, 0, 'Voyageur expérimenté, respectueux des règles.');

-- Insertion de données dans la table `host`
INSERT INTO `host` (`city`, `postal_code`, `address`, `lat`, `lng`, `photo`, `type_logement`, `shopping`, `gardening`, `chores`) VALUES
('Paris', '75001', '123 Rue de Rivoli', 48.8566, 2.3522, 'host1.jpg', 'Appartement', 1, 0, 0),
('Lyon', '69001', '456 Rue de la République', 45.7640, 4.8357, 'host2.jpg', 'Maison', 0, 1, 1),
('Marseille', '13001', '789 Rue Saint-Ferréol', 43.2965, 5.3698, 'host3.jpg', 'Loft', 1, 0, 1),
('Nice', '06000', '321 Promenade des Anglais', 43.7102, 7.2620, 'host4.jpg', 'Villa', 0, 1, 0),
('Toulouse', '31000', '654 Avenue de la République', 43.6045, 1.4442, 'host5.jpg', 'Appartement', 1, 1, 1);

-- Insertion de données dans la table `post`
INSERT INTO `post` (`handicap`, `smoking`, `pets`, `description`, `price`, `available`, `id_host`) VALUES
(0, 1, 0, 'Chambre confortable en plein centre-ville.', 80.00, 1, 1),
(1, 0, 1, 'Studio avec accès pour personnes à mobilité réduite.', 100.00, 1, 2),
(0, 0, 1, 'Appartement avec animaux de compagnie autorisés.', 120.00, 0, 3),
(0, 1, 0, 'Chambre avec balcon et vue sur la mer.', 150.00, 1, 4),
(1, 0, 1, 'Logement adapté avec jardin.', 90.00, 1, 5);

-- Insertion de données dans la table `reservation`
INSERT INTO `reservation` (`id_post`, `id_guest`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);


--
-- Déchargement des données de la table `users`
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
