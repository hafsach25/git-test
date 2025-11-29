-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 28 nov. 2025 à 21:23
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

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_dm`, `id_typedebesoin`, `status`, `date_creation_dm`, `date_limite_dm`, `urgence_dm`, `description_dm`, `id_service`, `piece_jointe_dm`, `id_demandeur`, `transfere`) VALUES
(2, 2, 'en_cours', '2025-11-27 17:17:30', NULL, 'Haute', 'Licence JetBrains expirée', 2, NULL, 1, 0),
(13, 1, 'en_attente', '2025-11-27 17:20:58', NULL, 'Haute', 'Erreur sur prime vacances', 1, NULL, 1, 0),
(14, 2, 'en_cours', '2025-11-27 17:20:58', NULL, 'Moyenne', 'Formation Management Agile', 2, NULL, 2, 0),
(15, 3, 'traite', '2025-11-27 17:20:58', NULL, 'Faible', 'Déclaration naissance enfant', 3, NULL, 2, 0),
(16, 1, 'en_attente', '2025-11-27 17:21:23', NULL, 'Moyenne', 'Logo pour projet Alpha', 7, NULL, 3, 0),
(17, 2, 'en_attente', '2025-11-27 17:21:23', NULL, 'Haute', 'Budget Q4 Ads', 8, NULL, 3, 0),
(18, 3, 'en attente', '2025-11-27 17:21:23', NULL, 'Urgent', 'Lancement produit bêta', 9, NULL, 3, 0),
(19, 1, 'en_cours', '2025-11-27 21:36:51', '2025-12-04', 'moyen', 'Demande test 1', 1, NULL, 1, NULL),
(20, 2, 'en_cours', '2025-11-27 21:36:51', '2025-12-07', 'élevé', 'Demande test 2', 2, NULL, 1, NULL),
(21, 3, 'validee', '2025-11-27 21:36:51', '2025-12-02', 'faible', 'Demande test 3', 3, NULL, 1, NULL),
(22, 1, 'validee', '2025-11-27 21:36:51', '2025-12-05', 'moyen', 'Demande test 4', 1, NULL, 1, NULL),
(23, 2, 'rejete', '2025-11-27 21:36:51', '2025-11-30', 'élevé', 'Demande test 5', 2, NULL, 1, NULL),
(24, 3, 'traite', '2025-11-27 21:36:51', '2025-11-29', 'faible', 'Demande test 6', 3, NULL, 1, NULL);

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
(1, 'demandeur.it@speedy.com', 'noura123', 'Ouahib Noura', 1, 1, 'Développeur Fullstack'),
(2, 'demandeur.rh@speedy.com', 'pass123', 'Chabab Hafsa', 2, 2, 'Chargé de recrutement'),
(3, 'demandeur.mkt@speedy.com', 'pass123', 'Melluoili Zakaria', 3, 3, 'Graphiste Senior');

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id_dep` int(11) NOT NULL,
  `nom_dep` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`id_dep`, `nom_dep`) VALUES
(1, 'Département IT'),
(2, 'Ressources Humaines'),
(3, 'Marketing & Communication');

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

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id_service`, `nom_service`) VALUES
(1, 'Support Technique'),
(2, 'Réseau & Sécurité'),
(3, 'Développement'),
(4, 'Gestion Paie'),
(5, 'Formation'),
(6, 'Administration RH'),
(7, 'Design Graphique'),
(8, 'Publicité Digitale'),
(9, 'Relations Presse');

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

--
-- Déchargement des données de la table `type_besoin`
--

INSERT INTO `type_besoin` (`id_tb`, `nom_tb`, `description_tb`, `fichier_requis`, `limite_an`, `id_champ_personnalise`) VALUES
(1, 'Achat Matériel Info', 'PC, Écran, Périphériques', 0, 0, NULL),
(2, 'Licence Logiciel', 'Renouvellement ou achat', 0, 0, NULL),
(3, 'Accès VPN', 'Demande accès distant', 0, 0, NULL),
(4, 'Régularisation Salaire', 'Correction fiche de paie', 1, 0, NULL);

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
-- Déchargement des données de la table `validateur`
--

INSERT INTO `validateur` (`id_v`, `email_v`, `mdps_v`, `nom_complet_v`, `id_dep`) VALUES
(1, 'validateur.it@speedy.com', 'pass123', 'Khalid chabab', 1),
(2, 'validateur.rh@speedy.com', 'pass123', 'Ahmed ouahib', 2),
(3, 'validateur.mkt@speedy.com', 'pass123', 'Mounir ghachi', 3);

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
  MODIFY `id_dm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `demandeur`
--
ALTER TABLE `demandeur`
  MODIFY `id_d` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id_dep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id_ev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `id_tr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_besoin`
--
ALTER TABLE `type_besoin`
  MODIFY `id_tb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `validateur`
--
ALTER TABLE `validateur`
  MODIFY `id_v` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
