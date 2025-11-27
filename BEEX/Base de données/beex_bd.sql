-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 27 nov. 2025 à 11:31
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

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
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_ad` int(11) NOT NULL,
  `email_ad` varchar(150) NOT NULL,
  `mdps_ad` varchar(255) NOT NULL,
  `nom_complet_ad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `champ_personnalise`
--

CREATE TABLE `champ_personnalise` (
  `id_cp` int(11) NOT NULL,
  `nom_cp` varchar(100) NOT NULL,
  `type_cp` varchar(50) NOT NULL,
  `obligatoire_cp` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id_dep` int(11) NOT NULL,
  `nom_dep` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id_ev` int(11) NOT NULL,
  `date_ev` datetime DEFAULT current_timestamp(),
  `nom_complet_ev` varchar(150) DEFAULT NULL,
  `type_evenement_ev` varchar(100) DEFAULT NULL,
  `description_ev` text DEFAULT NULL,
  `adresse_ip_ev` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `nom_service` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transfer`
--

CREATE TABLE `transfer` (
  `id_tr` int(11) NOT NULL,
  `id_validateur_createur` int(11) NOT NULL,
  `id_validateur_recepteur` int(11) NOT NULL,
  `date_debut_tr` datetime DEFAULT current_timestamp(),
  `date_fin_tr` datetime DEFAULT NULL,
  `raison_tr` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_besoin`
--

CREATE TABLE `type_besoin` (
  `id_tb` int(11) NOT NULL,
  `nom_tb` varchar(100) NOT NULL,
  `description_tb` text DEFAULT NULL,
  `fichier_requis` tinyint(1) DEFAULT 0,
  `limite_an` int(11) DEFAULT 0,
  `id_champ_personnalise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `validateur`
--

CREATE TABLE `validateur` (
  `id_v` int(11) NOT NULL,
  `email_v` varchar(150) NOT NULL,
  `mdps_v` varchar(255) NOT NULL,
  `nom_complet_v` varchar(150) NOT NULL,
  `id_dep` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_ad`),
  ADD UNIQUE KEY `email_ad` (`email_ad`);

--
-- Index pour la table `champ_personnalise`
--
ALTER TABLE `champ_personnalise`
  ADD PRIMARY KEY (`id_cp`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id_dm`),
  ADD KEY `id_typedebesoin` (`id_typedebesoin`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_demandeur` (`id_demandeur`);

--
-- Index pour la table `demandeur`
--
ALTER TABLE `demandeur`
  ADD PRIMARY KEY (`id_d`),
  ADD UNIQUE KEY `email_d` (`email_d`),
  ADD KEY `id_validateur` (`id_validateur`),
  ADD KEY `id_dep` (`id_dep`);

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id_dep`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id_ev`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- Index pour la table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`id_tr`),
  ADD KEY `id_validateur_createur` (`id_validateur_createur`),
  ADD KEY `id_validateur_recepteur` (`id_validateur_recepteur`);

--
-- Index pour la table `type_besoin`
--
ALTER TABLE `type_besoin`
  ADD PRIMARY KEY (`id_tb`),
  ADD KEY `id_champ_personnalise` (`id_champ_personnalise`);

--
-- Index pour la table `validateur`
--
ALTER TABLE `validateur`
  ADD PRIMARY KEY (`id_v`),
  ADD UNIQUE KEY `email_v` (`email_v`),
  ADD KEY `id_dep` (`id_dep`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_ad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `champ_personnalise`
--
ALTER TABLE `champ_personnalise`
  MODIFY `id_cp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_dm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demandeur`
--
ALTER TABLE `demandeur`
  MODIFY `id_d` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id_dep` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id_ev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `id_tr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_besoin`
--
ALTER TABLE `type_besoin`
  MODIFY `id_tb` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `validateur`
--
ALTER TABLE `validateur`
  MODIFY `id_v` int(11) NOT NULL AUTO_INCREMENT;

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

--
-- Contraintes pour la table `demandeur`
--
ALTER TABLE `demandeur`
  ADD CONSTRAINT `demandeur_ibfk_1` FOREIGN KEY (`id_validateur`) REFERENCES `validateur` (`id_v`),
  ADD CONSTRAINT `demandeur_ibfk_2` FOREIGN KEY (`id_dep`) REFERENCES `departement` (`id_dep`);

--
-- Contraintes pour la table `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `transfer_ibfk_1` FOREIGN KEY (`id_validateur_createur`) REFERENCES `validateur` (`id_v`),
  ADD CONSTRAINT `transfer_ibfk_2` FOREIGN KEY (`id_validateur_recepteur`) REFERENCES `validateur` (`id_v`);

--
-- Contraintes pour la table `type_besoin`
--
ALTER TABLE `type_besoin`
  ADD CONSTRAINT `type_besoin_ibfk_1` FOREIGN KEY (`id_champ_personnalise`) REFERENCES `champ_personnalise` (`id_cp`);

--
-- Contraintes pour la table `validateur`
--
ALTER TABLE `validateur`
  ADD CONSTRAINT `validateur_ibfk_1` FOREIGN KEY (`id_dep`) REFERENCES `departement` (`id_dep`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
