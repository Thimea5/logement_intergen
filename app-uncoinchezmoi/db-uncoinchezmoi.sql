-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 27 sep. 2024 à 14:14
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
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) DEFAULT NULL,
  `lastname` varchar(64) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `type` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `birthdate`, `isActive`, `type`) VALUES
(1, 'Alice', 'Dupont', 'alice.dupont@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1990-01-01', 1, 'admin'),
(2, 'Bob', 'Martin', 'bob.martin@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1992-02-02', 1, 'user'),
(3, 'Charlie', 'Durand', 'charlie.durand@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1988-03-03', 1, 'host'),
(4, 'Diana', 'Petit', 'diana.petit@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1995-04-04', 1, 'guest'),
(5, 'Ethan', 'Moreau', 'ethan.moreau@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1985-05-05', 1, 'admin'),
(6, 'Fiona', 'Garnier', 'fiona.garnier@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1993-06-06', 1, 'user'),
(7, 'Gabriel', 'Lefevre', 'gabriel.lefevre@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1987-07-07', 1, 'host'),
(8, 'Hannah', 'David', 'hannah.david@example.com', '$2y$10$3COtyn7Ui0d7CX.nfpaKH.AM5Dz7QtueM8tD6ckRENKQNoKI7LkdC', '1991-08-08', 1, 'guest');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
