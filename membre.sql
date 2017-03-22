-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 22 Mars 2017 à 11:12
-- Version du serveur :  10.1.13-MariaDB
-- Version de PHP :  5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `lokisalle`
--

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'hibou', 'test', 'test', 'test', 'test@contact.com', 'm', 0, '2017-03-20 15:18:31'),
(13, 'tata', 'ed3a29d3b020ff12cf241c88e1c91831', 'dryrt', 'ft', 'rftthy@gjhjk', 'm', 1, '2017-03-21 14:20:09'),
(16, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'test', 'test', 'admin@contact.com', 'm', 1, '2017-03-01 00:00:00'),
(18, 'membre', '123456', 'lol', 'lili', 'lolo@lili.fr', 'm', 1, '2017-03-21 14:12:24'),
(19, 'koucha', '123456', 'tabou', 'kouchapane', 'kouchapane@contact.com', 'f', 0, '2017-03-21 14:13:44'),
(20, 'Herve', '123456', 'Duchamps', 'Hervé', 'herve@contact.fr', 'm', 0, '2017-03-21 14:21:10'),
(21, 'Bibo', '123456', 'Nasraoui', 'Bouchra', 'Bouchra@contact.com', 'f', 0, '2017-03-21 14:23:29'),
(22, 'test', 'tetst', 'test', 'test', 'test@contact.com', 'm', 0, '2017-03-21 14:29:36'),
(23, 'test02', '098f6bcd4621d373cade4e832627b4f6', 'test', 'test', 'test@contact.com', 'm', 0, '2017-03-21 14:33:26');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
