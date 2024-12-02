-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-uccm-user.alwaysdata.net
-- Generation Time: Dec 01, 2024 at 05:27 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uccm-user_db-uncoinchezmoi`
--

DROP TABLE IF EXISTS `message`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `comment`;
DROP TABLE IF EXISTS `conversation`;
DROP TABLE IF EXISTS `post`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `content`, `creation_date`, `id_user`, `id_post`) VALUES
(1, 'Great place to stay!', '2024-11-01', 2, 1),
(2, 'Very cozy and quiet.', '2024-11-02', 3, 2),
(3, 'Ideal location for business trips.', '2024-11-03', 4, 3),
(4, 'Nice house, but a bit small.', '2024-11-04', 5, 4),
(5, 'Perfect for a family vacation.', '2024-11-05', 6, 5),
(6, 'Great apartment with a view.', '2024-11-06', 7, 6),
(7, 'Spacious, but needs some maintenance.', '2024-11-07', 8, 7),
(8, 'Loved the apartment!', '2024-11-08', 9, 8),
(9, 'Excellent location, very central.', '2024-11-09', 10, 9),
(10, 'Comfortable and well-equipped.', '2024-11-10', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `id_user1` int(11) DEFAULT NULL,
  `id_user2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`id`, `creation_date`, `id_user1`, `id_user2`) VALUES
(1, '2024-11-01', 1, 2),
(2, '2024-11-02', 3, 4),
(3, '2024-11-03', 5, 6),
(4, '2024-11-04', 7, 8),
(5, '2024-11-05', 9, 10),
(6, '2024-11-06', 2, 3),
(7, '2024-11-07', 4, 5),
(8, '2024-11-08', 6, 7),
(9, '2024-11-09', 8, 9),
(10, '2024-11-10', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `id_conversation` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `content`, `creation_date`, `id_conversation`, `id_user`) VALUES
(1, 'Hello, I am interested in your property.', '2024-11-01', 1, 1),
(2, 'Sure, let me know if you have any questions.', '2024-11-02', 1, 2),
(3, 'Can you send more pictures?', '2024-11-03', 2, 3),
(4, 'Absolutely, I will send them shortly.', '2024-11-04', 2, 4),
(5, 'Is the property still available?', '2024-11-05', 3, 5),
(6, 'Yes, it is available.', '2024-11-06', 3, 6),
(7, 'I would like to book the place for next month.', '2024-11-07', 4, 7),
(8, 'Great, I will reserve it for you.', '2024-11-08', 4, 8),
(9, 'Thanks for your help!', '2024-11-09', 5, 9),
(10, 'You\'re welcome! Looking forward to your stay.', '2024-11-10', 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `cheminPhoto` varchar(255) DEFAULT NULL,
  `type_logement` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` double DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `nb_photo` int(11) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `city`, `postal_code`, `address`, `lat`, `lng`, `cheminPhoto`, `type_logement`, `description`, `price`, `size`, `nb_photo`, `available`, `id_user`) VALUES
(1, 'Paris', '75001', '12 Rue de la Paix', 48.8566, 2.3522, 'host_photo1', 'Apartment', 'Spacious and modern apartment', 1200, 60, 3, 1, 1),
(2, 'Lyon', '69002', '45 Rue de la République', 45.764, 4.8357, 'host_photo2', 'House', 'Cozy family house', 1500, 120, 4, 1, 2),
(3, 'Marseille', '13001', '3 Boulevard du Prado', 43.2965, 5.3698, 'host_photo3', 'Apartment', 'City center apartment', 800, 40, 2, 1, 3),
(4, 'Bordeaux', '33000', '78 Avenue du Général', 44.8378, -0.5792, 'host_photo4', 'Studio', 'Small studio for rent', 600, 25, 1, 1, 4),
(5, 'Nice', '06000', '20 Promenade des Anglais', 43.7102, 7.262, 'host_photo5', 'House', 'Beachfront house', 2500, 200, 5, 1, 5),
(6, 'Toulouse', '31000', '15 Rue Alsace Lorraine', 43.6047, 1.4442, 'host_photo6', 'Apartment', 'Nice apartment in city center', 950, 50, 2, 1, 6),
(7, 'Nantes', '44000', '32 Rue de la Loire', 47.2186, -1.5536, 'host_photo7', 'House', 'Modern family house', 1800, 100, 4, 1, 7),
(8, 'Strasbourg', '67000', '10 Rue de l\'Université', 48.5734, 7.7521, 'host_photo8', 'Apartment', 'Spacious apartment with terrace', 1300, 80, 3, 1, 8),
(9, 'Lille', '59000', '22 Rue Faidherbe', 50.6292, 3.0573, 'host_photo9', 'Studio', 'Charming studio in the center', 700, 30, 2, 1, 9),
(10, 'Rennes', '35000', '55 Rue de la Poste', 48.1173, -1.6778, 'host_photo10', 'House', 'Quiet suburban house', 1300, 110, 3, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id_post`, `id_user`) VALUES
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(3, 6),
(3, 7),
(4, 8),
(4, 9),
(5, 1),
(5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `gardening` tinyint(1) DEFAULT NULL,
  `errand` tinyint(1) DEFAULT NULL,
  `diy` tinyint(1) DEFAULT NULL,
  `cleaning` tinyint(1) DEFAULT NULL,
  `chatting` tinyint(1) DEFAULT NULL,
  `cooking` tinyint(1) DEFAULT NULL,
  `petSitting` tinyint(1) DEFAULT NULL,
  `carSharing` tinyint(1) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `gardening`, `errand`, `diy`, `cleaning`, `chatting`, `cooking`, `petSitting`, `carSharing`, `id_user`) VALUES
(1, 1, 0, 0, 1, 1, 0, 1, 0, 1),
(2, 0, 1, 1, 0, 1, 1, 0, 1, 2),
(3, 0, 0, 1, 1, 1, 0, 0, 1, 3),
(4, 1, 1, 0, 1, 0, 0, 1, 0, 4),
(5, 0, 1, 0, 0, 1, 1, 0, 1, 5),
(6, 1, 0, 1, 0, 1, 1, 1, 0, 6),
(7, 0, 0, 1, 1, 1, 1, 0, 1, 7),
(8, 1, 0, 1, 1, 0, 1, 1, 0, 8),
(9, 0, 1, 0, 1, 1, 1, 0, 1, 9),
(10, 1, 1, 0, 1, 0, 1, 1, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `isComplete` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `mail`, `password`, `firstname`, `lastname`, `birthdate`, `genre`, `tel`, `marital_status`, `photo`, `active`, `type`, `isComplete`) VALUES
(1, 'alice@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Alice', 'Dupont', '1990-05-15', 'F', '0601020304', 'Single', 'alice.jpg', 1, 'guest', 1),
(2, 'bob@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Bob', 'Martin', '1985-03-22', 'M', '0605060708', 'Married', 'bob.jpg', 1, 'host', 1),
(3, 'charlie@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Charlie', 'Durand', '1988-07-30', 'M', '0612345678', 'Single', 'charlie.jpg', 1, 'guest', 1),
(4, 'diana@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Diana', 'Leblanc', '1992-12-12', 'F', '0623456789', 'Married', 'diana.jpg', 1, 'host', 1),
(5, 'eva@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Eva', 'Lemoine', '1983-06-10', 'F', '0634567890', 'Single', 'eva.jpg', 1, 'guest', 1),
(6, 'frank@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Frank', 'Michel', '1995-09-05', 'M', '0645678901', 'Single', 'frank.jpg', 1, 'guest', 1),
(7, 'george@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'George', 'Benoit', '1990-11-01', 'M', '0656789012', 'Married', 'george.jpg', 1, 'host', 1),
(8, 'hannah@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Hannah', 'Pires', '1998-01-22', 'F', '0667890123', 'Single', 'hannah.jpg', 1, 'guest', 1),
(9, 'irene@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Irene', 'Gauthier', '1991-04-10', 'F', '0678901234', 'Single', 'irene.jpg', 1, 'guest', 1),
(10, 'jack@example.com', '$2y$10$9OiEJdiYmqNZcJAevTlo9.72e1y6Vc694r27qqPhZu85Z8ImszBC2', 'Jack', 'Moreau', '1987-10-14', 'M', '0689012345', 'Married', 'jack.jpg', 1, 'host', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_post` (`id_post`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user1` (`id_user1`),
  ADD KEY `id_user2` (`id_user2`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_conversation` (`id_conversation`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_post`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`);

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`id_user1`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`id_user2`) REFERENCES `users` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_conversation`) REFERENCES `conversation` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
