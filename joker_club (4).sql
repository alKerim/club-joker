-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2026 at 02:04 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joker_club`
--

-- --------------------------------------------------------

--
-- Table structure for table `demandes_adhesion`
--

CREATE TABLE `demandes_adhesion` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `message` text,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','accepte','refuse') DEFAULT 'en_attente',
  `date_demande` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demandes_adhesion`
--

INSERT INTO `demandes_adhesion` (`id`, `nom`, `email`, `telephone`, `message`, `mot_de_passe`, `statut`, `date_demande`) VALUES
(13, 'smati safa', 'safasmati@gmail.com', '', 'je suis interesse a les  soft skills', '$2y$10$nttoi.ZNuQEhgjcUXT44ke4Dk5r1D.sMC1IY8aEYRefesf5GxlMfu', 'accepte', '2026-04-16 10:26:23'),
(14, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', 'je suis interesse par le club', '$2y$10$9jtOTHm.Lp6FupPk71kZTef1I5dcbi8ojmKwkk1NuV0tODekgPiyG', 'accepte', '2026-04-16 12:58:17'),
(16, 'yousef2', 'yousef@gmail.com', '25567899', '', '$2y$10$veGO6/dVajU4jE9Vl0IoVOSsonaGv0UkLwy4gSIFe/hM/XaqLJQKy', 'refuse', '2026-04-16 14:49:03'),
(17, 'nv safa', 'nvsafa@esen.tn', '55414327', '', '$2y$10$e07YB7g.mnXcHFuNLzwMyeM65ZGEgHTnuHoy8QlU1jYPkLYir9Euq', 'accepte', '2026-04-16 16:26:24'),
(18, 'youssef', 'youssef@gmail.com', '22345678', '', '$2y$10$xM2J56fdOn6FLtllZOBp6eWuFS1d8OuRLDoV3szbfLKL41eDIy/xa', 'accepte', '2026-04-16 16:27:12'),
(19, 'amine guebsi', 'amineguebsi@gmail.com', '56442778', 'Je souhaite rejoindre le Club Joker pour développer mes compétences, participer à des activités enrichissantes et intégrer une équipe dynamique.', '$2y$10$AK8D/IivJrVk7PvFfyJpzOTYfa2GzLbc.O2FXJOXPJ2L7clXqR1BG', 'en_attente', '2026-04-16 16:31:20'),
(20, 'eya heshmi', 'eyaheshmi@gmail.com', '93447660', 'Je souhaite rejoindre le Club Joker pour apprendre de nouvelles choses, rencontrer des personnes motivées et contribuer à des projets intéressants.', '$2y$10$oPTkfI1tttkA2ZPf7pPN9ulj..3N7NW2DdIggHO59qSr.7Joki7/y', 'en_attente', '2026-04-16 16:33:07'),
(21, 'amal', 'amal@gmail.com', '93183900', '', '$2y$10$7MLfagDnsZViE2iiaAheQeHWD7M.gRjg4eQO.3Q4GGcHAwW9OXSra', 'refuse', '2026-04-29 12:55:03'),
(22, 'amal', 'amal@gmail.com', '93183900', '', '$2y$10$6qZ2AqX2SjhRe/JYlUZXJ.4B9R9/poSDcDq4uVdDxhBKeAPA7GTbm', 'en_attente', '2026-04-29 12:56:04'),
(23, 'kmar yahyaoui', 'kmaryah@gmail.com', '98550709', 'JE VEUIX FAIRE DES SOFTSKILLS', '$2y$10$GHlPJ9I2h7mKPcKl3NqiAuxuavTGuYSp2siZ0NUOHo9HASxa8PaQe', 'en_attente', '2026-05-01 16:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

CREATE TABLE `evenements` (
  `id` int NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text,
  `date_evenement` date NOT NULL,
  `heure` time DEFAULT NULL,
  `lieu` varchar(150) DEFAULT NULL,
  `type` enum('public','prive') DEFAULT 'public',
  `max_participants` int DEFAULT '30',
  `id_createur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `date_evenement`, `heure`, `lieu`, `type`, `max_participants`, `id_createur`) VALUES
(1, 'Hackathon Joker 2026', '48h d\'innovation et de code.', '2026-07-10', '09:00:00', 'ESEN', 'public', 50, 1),
(2, 'Artisanet', 'ArtisaNet relie tradition, innovation et entrepreneuriat local.', '2025-04-30', '10:00:00', 'ESEN Manouba', 'public', 100, 1),
(3, 'Cœur à Cœur', 'Journée de dépistage, soutien psy & bien-être', '2026-04-14', '14:00:00', 'ESEN', 'public', 80, 1),
(22, 'Formation Linkedin', 'Formation', '2026-05-07', '10:30:00', 'ESEN Manouba', 'prive', 30, 1),
(23, 'E-turath', '', '2026-05-05', '10:43:00', 'ESEN Manouba', 'public', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `inscriptions_evenements`
--

CREATE TABLE `inscriptions_evenements` (
  `id` int NOT NULL,
  `id_evenement` int NOT NULL,
  `nom_visiteur` varchar(100) DEFAULT NULL,
  `email_visiteur` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inscriptions_evenements`
--

INSERT INTO `inscriptions_evenements` (`id`, `id_evenement`, `nom_visiteur`, `email_visiteur`, `telephone`, `date_inscription`, `id_utilisateur`) VALUES
(1, 1, 'Sarra Ben Ali', 'sarra@joker.tn', '22111222', '2026-04-11 14:06:39', NULL),
(2, 1, 'Yassine Khalil', 'yassine@joker.tn', '22333444', '2026-04-11 14:06:39', 1),
(3, 2, 'Sarra Ben Ali', 'sarra@joker.tn', '22111222', '2026-04-11 14:06:39', NULL),
(4, 2, 'smati safa', 'safasmati@gmail.com', '55414327', '2026-04-11 14:58:47', NULL),
(5, 2, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-11 15:47:48', NULL),
(6, 1, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-13 13:19:48', 1),
(7, 2, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-14 12:38:23', 1),
(8, 2, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-15 09:35:27', NULL),
(9, 2, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-15 09:35:42', NULL),
(10, 1, 'smati safa', 'safasmati@gmail.com', '55414327', '2026-04-16 14:38:22', NULL),
(11, 3, 'smati safa', 'mariem.marhag@esen.tn', '', '2026-04-16 14:46:12', 21),
(12, 22, 'yousef', 'youssef@gmail.com', '', '2026-04-16 14:55:29', NULL),
(13, 23, 'salma ayachi', 'salmayachi12@gmail.com', '93183900', '2026-04-27 13:44:31', 1),
(14, 23, 'smati safa', 'safasmati@gmail.com', '', '2026-04-27 13:45:38', NULL),
(15, 23, 'yousef', 'youssef@gmail.com', '', '2026-04-27 13:45:48', NULL),
(16, 2, 'yousef', 'youssef@gmail.com', '', '2026-04-27 13:52:31', 21);

-- --------------------------------------------------------

--
-- Table structure for table `presences`
--

CREATE TABLE `presences` (
  `id` int NOT NULL,
  `id_reunion` int NOT NULL,
  `id_membre` int NOT NULL,
  `statut` enum('present','absent') DEFAULT 'absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presences`
--

INSERT INTO `presences` (`id`, `id_reunion`, `id_membre`, `statut`) VALUES
(19, 2, 22, 'present'),
(20, 2, 21, 'absent'),
(25, 4, 22, 'absent'),
(26, 4, 21, 'absent');

-- --------------------------------------------------------

--
-- Table structure for table `reunions`
--

CREATE TABLE `reunions` (
  `id` int NOT NULL,
  `titre` varchar(200) NOT NULL,
  `date_reunion` date NOT NULL,
  `heure` time DEFAULT NULL,
  `lieu` varchar(150) DEFAULT NULL,
  `ordre_du_jour` text,
  `lien_meet` varchar(300) DEFAULT NULL,
  `type` enum('bureau','projet','generale','formation') DEFAULT 'bureau',
  `id_createur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reunions`
--

INSERT INTO `reunions` (`id`, `titre`, `date_reunion`, `heure`, `lieu`, `ordre_du_jour`, `lien_meet`, `type`, `id_createur`) VALUES
(2, 'Réunion Bureau', '2026-05-08', '17:00:00', 'Salle Club', 'Bilan mensuel et planification', 'https://meet.google.com/sjt-cqey-dzi', 'bureau', 1),
(3, 'Réunion Hackathon', '2026-06-11', '15:30:00', 'Bibliothèque', 'Organisation du hackathon', 'https://meet.google.com/hkf-aqmk-nmx', 'projet', 1),
(4, 'AG Membres', '2026-05-20', '18:00:00', 'Amphi A', 'Assemblée générale mensuelle', 'https://meet.google.com/muh-uish-wht', 'generale', 1);

-- --------------------------------------------------------

--
-- Table structure for table `taches`
--

CREATE TABLE `taches` (
  `id` int NOT NULL,
  `titre` varchar(200) NOT NULL,
  `id_assigne` int DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `priorite` enum('haute','moyenne','faible') DEFAULT 'moyenne',
  `statut` enum('en_cours','termine') DEFAULT 'en_cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taches`
--

INSERT INTO `taches` (`id`, `titre`, `id_assigne`, `deadline`, `priorite`, `statut`) VALUES
(6, 'Rédiger newsletter mensuelle', 1, '2025-05-20', 'faible', 'en_cours'),
(9, 'PV', 22, '2026-04-17', 'haute', 'en_cours'),
(14, 'Stand', 21, '2026-04-30', 'moyenne', 'en_cours'),
(15, 'Artisanet', 24, '2026-04-29', 'faible', 'en_cours'),
(16, 'Réunion Bureau', 25, '2026-05-27', 'faible', 'en_cours');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','membre') NOT NULL DEFAULT 'membre',
  `date_inscription` date NOT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `date_inscription`, `telephone`) VALUES
(1, 'mariem', 'mariem.marhag@esen.tn', '$2y$10$rVl29oDVGLP6yiz2FKDUbecjyLwk3BDgi0vgfNPby/xe7JTpAANVS', 'admin', '2026-04-11', '53564211'),
(21, 'smati safa', 'safasmati@gmail.com', '$2y$10$nttoi.ZNuQEhgjcUXT44ke4Dk5r1D.sMC1IY8aEYRefesf5GxlMfu', 'membre', '2026-04-16', ''),
(22, 'salma ayachi', 'salmayachi12@gmail.com', '$2y$10$9jtOTHm.Lp6FupPk71kZTef1I5dcbi8ojmKwkk1NuV0tODekgPiyG', 'membre', '2026-04-16', '93183900'),
(24, 'youssef', 'youssef@gmail.com', '$2y$10$xM2J56fdOn6FLtllZOBp6eWuFS1d8OuRLDoV3szbfLKL41eDIy/xa', 'membre', '2026-04-16', '22345678'),
(25, 'nv safa', 'nvsafa@esen.tn', '$2y$10$e07YB7g.mnXcHFuNLzwMyeM65ZGEgHTnuHoy8QlU1jYPkLYir9Euq', 'membre', '2026-04-16', '55414327');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demandes_adhesion`
--
ALTER TABLE `demandes_adhesion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_createur` (`id_createur`);

--
-- Indexes for table `inscriptions_evenements`
--
ALTER TABLE `inscriptions_evenements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_evenement` (`id_evenement`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `presences`
--
ALTER TABLE `presences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_presence` (`id_reunion`,`id_membre`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Indexes for table `reunions`
--
ALTER TABLE `reunions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_createur` (`id_createur`);

--
-- Indexes for table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_assigne` (`id_assigne`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `demandes_adhesion`
--
ALTER TABLE `demandes_adhesion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `inscriptions_evenements`
--
ALTER TABLE `inscriptions_evenements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `presences`
--
ALTER TABLE `presences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `reunions`
--
ALTER TABLE `reunions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `taches`
--
ALTER TABLE `taches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_ibfk_1` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inscriptions_evenements`
--
ALTER TABLE `inscriptions_evenements`
  ADD CONSTRAINT `inscriptions_evenements_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `evenements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_evenements_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `presences_ibfk_1` FOREIGN KEY (`id_reunion`) REFERENCES `reunions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presences_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reunions`
--
ALTER TABLE `reunions`
  ADD CONSTRAINT `reunions_ibfk_1` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `taches_ibfk_1` FOREIGN KEY (`id_assigne`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
