-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 29 nov. 2025 à 19:56
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `beex_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `id_dm` int(11) NOT NULL,
  `id_typedebesoin` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'En attente',
  `date_creation_dm` datetime DEFAULT current_timestamp(),
  `date_limite_dm` date DEFAULT NULL,
  `urgence_dm` varchar(20) DEFAULT NULL,
  `description_dm` text DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL,
  `piece_jointe_dm` varchar(255) DEFAULT NULL,
  `id_demandeur` int(11) NOT NULL,
  `transfere` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_dm`, `id_typedebesoin`, `status`, `date_creation_dm`, `date_limite_dm`, `urgence_dm`, `description_dm`, `id_service`, `piece_jointe_dm`, `id_demandeur`, `transfere`) VALUES
(2, 2, 'en_cours', '2025-11-27 17:17:30', NULL, 'Haute', 'Licence JetBrains expirée', 2, NULL, 1, 0),
(13, 1, 'en_attente', '2025-11-27 17:20:58', NULL, 'Haute', 'Erreur sur prime vacances', 1, NULL, 1, 0),
(14, 2, 'en_cours', '2025-11-27 17:20:58', NULL, 'Normale', 'Formation Management Agile', 2, NULL, 2, 0),
(15, 3, 'traite', '2025-11-27 17:20:58', NULL, 'Faible', 'Déclaration naissance enfant', 3, NULL, 2, 0),
(16, 1, 'en_attente', '2025-11-27 17:21:23', NULL, 'Normale', 'Logo pour projet Alpha', 7, NULL, 3, 0),
(17, 2, 'en_attente', '2025-11-27 17:21:23', NULL, 'Haute', 'Budget Q4 Ads', 8, NULL, 3, 0),
(18, 3, 'en attente', '2025-11-27 17:21:23', NULL, 'critique', 'Lancement produit bêta', 9, NULL, 3, 0),
(19, 1, 'en_cours', '2025-11-27 21:36:51', '2025-12-04', 'Normale', 'modifier Demande test 1', 1, NULL, 1, NULL),
(20, 2, 'en_cours', '2025-11-27 21:36:51', '2025-12-07', 'normale', 'Demande test 22222', 2, NULL, 1, NULL),
(21, 3, 'validee', '2025-11-27 21:36:51', '2025-12-02', 'faible', 'Demande test 3', 3, NULL, 1, NULL),
(22, 1, 'validee', '2025-11-27 21:36:51', '2025-12-05', 'Normale', 'Demande test 4', 1, NULL, 1, NULL),
(23, 2, 'rejete', '2025-11-27 21:36:51', '2025-11-30', 'critique', 'Demande test 5', 2, NULL, 1, NULL),
(24, 3, 'traite', '2025-11-27 21:36:51', '2025-11-29', 'Faible', 'Demande test 6', 3, NULL, 1, NULL),
(29, 2, 'en_attente', '2025-11-29 01:24:49', '2026-03-12', 'Normale', 'HHHHHHHHHHHH', NULL, '[]', 1, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id_dm`),
  ADD KEY `id_typedebesoin` (`id_typedebesoin`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_demandeur` (`id_demandeur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_dm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_typedebesoin`) REFERENCES `type_besoin` (`id_tb`),
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`),
  ADD CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`id_demandeur`) REFERENCES `demandeur` (`id_d`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
