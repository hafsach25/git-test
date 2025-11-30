-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 30 nov. 2025 à 14:57
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
-- Structure de la table `demandeur`
--

CREATE TABLE `demandeur` (
  `id_d` int(11) NOT NULL,
  `email_d` varchar(150) NOT NULL,
  `mdps_d` varchar(255) NOT NULL,
  `nom_complet_d` varchar(150) NOT NULL,
  `id_validateur` int(11) DEFAULT NULL,
  `id_dep` int(11) DEFAULT NULL,
  `poste_d` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demandeur`
--

INSERT INTO `demandeur` (`id_d`, `email_d`, `mdps_d`, `nom_complet_d`, `id_validateur`, `id_dep`, `poste_d`) VALUES
(1, 'demandeur.it@speedy.com', '$2y$10$IMfx8SgtdvcVSeQYSO6a6OslqruEkpY/RqMczYv9kQYQNWED7HJrW\n$2y$10$IMfx8SgtdvcVSeQYSO6a6OslqruEkpY/RqMczYv9kQYQNWED7HJrW\n$2y$10$IMfx8SgtdvcVSeQYSO6a6OslqruEkpY/RqMczYv9kQYQNWED7HJrW', 'Ouahib Noura', 1, 1, 'Développeur Fullstack'),
(2, 'demandeur.rh@speedy.com', '$2y$10$n8gihHrpn5eduOiwhG7kM.dRt5KTpTuWjHmSO3dURJu1V7U/AJZNq', 'Chabab Hafsa', 2, 2, 'Chargé de recrutement'),
(3, 'demandeur.mkt@speedy.com', '$2y$10$XKx5dewwxRkEaBP6n9vNs.SjqoKkHKx9DS26fHd7pgr/pv5IDif9G', 'Melluoili Zakaria', 3, 3, 'Graphiste Senior');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demandeur`
--
ALTER TABLE `demandeur`
  ADD PRIMARY KEY (`id_d`),
  ADD UNIQUE KEY `email_d` (`email_d`),
  ADD KEY `id_validateur` (`id_validateur`),
  ADD KEY `id_dep` (`id_dep`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demandeur`
--
ALTER TABLE `demandeur`
  MODIFY `id_d` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demandeur`
--
ALTER TABLE `demandeur`
  ADD CONSTRAINT `demandeur_ibfk_1` FOREIGN KEY (`id_validateur`) REFERENCES `validateur` (`id_v`),
  ADD CONSTRAINT `demandeur_ibfk_2` FOREIGN KEY (`id_dep`) REFERENCES `departement` (`id_dep`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
