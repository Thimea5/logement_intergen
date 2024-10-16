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

INSERT INTO `guest` (`id`, `photo`, `prix_max`, `shopping`, `gardening`, `chores`, `description`) VALUES
(1, 'photo1.jpg', 500, 1, 0, 1, 'Cherche un logement avec jardin et qui accepte les animaux.'),
(2, 'photo2.jpg', 600, 0, 1, 0, 'Préférerais un endroit calme, idéal pour les promenades.'),
(3, 'photo3.jpg', 450, 1, 1, 1, 'Urgent : Je recherche un logement pour quelques mois.'),
(4, 'photo4.jpg', 700, 0, 0, 1, 'Je suis une personne propre et respectueuse, cherche une chambre à louer.'),
(5, 'photo5.jpg', 550, 1, 0, 0, 'Logement pour personne seule, avec accès à internet.');

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
  `handicap` tinyint(1) DEFAULT '0',
  `smoking` tinyint(1) DEFAULT '0',
  `pets` tinyint(1) DEFAULT '0',
  `shopping` tinyint(1) DEFAULT '0',
  `gardening` tinyint(1) DEFAULT '0',
  `chores` tinyint(1) DEFAULT '0',
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `host`
--

INSERT INTO `host` (`id`, `city`, `postal_code`, `address`, `lat`, `lng`, `photo`, `type_logement`, `handicap`, `smoking`, `pets`, `shopping`, `gardening`, `chores`, `price`) VALUES
(1, 'Paris', '75001', '10 Rue de Rivoli', 48.860846, 2.341308, 'host_photo1.jpg', 'appartement', 0, 1, 0, 1, 0, 1, 1500),
(2, 'Lyon', '69001', '5 Place Bellecour', 45.757813, 4.832013, 'host_photo2.jpg', 'chambre', 0, 0, 1, 0, 1, 0, 800),
(3, 'Marseille', '13001', '20 Rue de la République', 43.296482, 5.36978, 'host_photo3.jpg', 'maison', 0, 1, 1, 0, 0, 1, 1200),
(4, 'Nice', '06000', '15 Promenade des Anglais', 43.6954, 7.27332, 'host_photo4.jpg', 'studio', 0, 0, 0, 1, 0, 0, 900),
(5, 'Bordeaux', '33000', '3 Rue Sainte-Catherine', 44.837139, -0.58064, 'host_photo5.jpg', 'appartement', 0, 1, 1, 0, 1, 0, 1100);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `available` tinyint(1) DEFAULT NULL,
  `id_host` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_host` (`id_host`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `available`, `id_host`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5);

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

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `mail`, `password`, `firstname`, `lastname`, `birthdate`, `genre`, `tel`, `marital_status`, `active`, `role`) VALUES
(49, 'ghostache.contact@gmail.com', '$2y$10$7WJcYcZES0bo/qUA1aaOIOWMRSG21mWMHe3wWTZ1mLOGdqdMyj10u', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
