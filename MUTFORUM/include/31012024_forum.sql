-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 31 jan. 2024 à 15:08
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `message_tbl`
--

DROP TABLE IF EXISTS `message_tbl`;
CREATE TABLE IF NOT EXISTS `message_tbl` (
  `ID_MESSAGE` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_MESSAGE` datetime DEFAULT NULL,
  `CONTENT_MESSAGE` varchar(255) DEFAULT NULL,
  `DISPLAY_` tinyint(1) DEFAULT '1',
  `ID_USER` int(11) DEFAULT NULL,
  `ID_SUJET` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_MESSAGE`),
  KEY `FK_message_tbl_ID_USER_utilisateur_tbl` (`ID_USER`),
  KEY `FK_message_tbl_ID_SUJET_sujet_tbl` (`ID_SUJET`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `message_tbl`
--

INSERT INTO `message_tbl` (`ID_MESSAGE`, `DATE_MESSAGE`, `CONTENT_MESSAGE`, `DISPLAY_`, `ID_USER`, `ID_SUJET`) VALUES
(3, '2023-05-15 00:00:00', 'Ceci est le message sur le sujet 1', 1, 1, 1),
(4, '2023-05-15 09:37:08', 'Un autre message sur le sujet 1', 1, 1, 1),
(5, '2023-05-16 11:39:06', 'Ceci est un message avec des caractÃ¨re [b]gras[/b]', 1, 1, 1),
(6, '2023-05-15 12:05:00', 'TrÃ¨s bonne mutuelle', 1, 1, 2),
(7, '2023-05-15 12:06:00', 'TrÃ¨s mauvais service Ã  fuire de toute urgence', 1, 2, 2),
(8, '2023-05-15 12:05:56', 'C\'est dommage qu\'elle soit obligatoire', 1, 3, 2),
(9, '2023-12-20 00:00:00', 'ceci est un test\r\navec 2 lignes', 1, 19, 1),
(10, '2023-12-20 05:07:37', 'test avec les [b]test[/b]', 1, 19, 1),
(12, '2023-12-20 05:11:23', 'voici le premier message sur ce forum', 1, 19, 3),
(13, '2024-01-12 10:34:50', 'Hey Salut &agrave; tous', 1, 20, 1),
(14, '2024-01-13 09:47:11', 'J&#039;ai une bonne mutuelle quand j&#039;y pense', 1, 20, 2);

--
-- Déclencheurs `message_tbl`
--
DROP TRIGGER IF EXISTS `check_stateUser`;
DELIMITER $$
CREATE TRIGGER `check_stateUser` BEFORE INSERT ON `message_tbl` FOR EACH ROW SET @state = (SELECT `utilisateur_tbl_has_stateuser_tbl`.STATE FROM `utilisateur_tbl_has_stateuser_tbl` WHERE `utilisateur_tbl_has_stateuser_tbl`.USER = new.ID_USER)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `organisme_tbl`
--

DROP TABLE IF EXISTS `organisme_tbl`;
CREATE TABLE IF NOT EXISTS `organisme_tbl` (
  `ID_ORGA` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_ORGANISATION` varchar(45) NOT NULL,
  `DATE_ORGANISME` date NOT NULL,
  `INFO_MUTUELLE` json DEFAULT NULL,
  `LOGO_ORGANISME` varchar(255) DEFAULT NULL,
  `ALLOWED_ORGA` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID_ORGA`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `organisme_tbl`
--

INSERT INTO `organisme_tbl` (`ID_ORGA`, `NOM_ORGANISATION`, `DATE_ORGANISME`, `INFO_MUTUELLE`, `LOGO_ORGANISME`, `ALLOWED_ORGA`) VALUES
(1, 'MUTUELLE1', '2023-05-12', '{\"tel\": \"0102030405\", \"adresse\": [\"adresse mutuelle 1\", \"00000\", \"VILLE1\"]}', 'default.png', 1),
(2, 'Mutuelle 2', '2023-05-15', '{\"adresse\": [\"une adresse\", \"CP\", \"VILLE\"]}', 'default.png', 1),
(3, 'Mutualis', '2023-05-15', '{\"adresse\": \"une adresse\"}', 'default.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reponse_tbl`
--

DROP TABLE IF EXISTS `reponse_tbl`;
CREATE TABLE IF NOT EXISTS `reponse_tbl` (
  `ID_MESSAGE` int(11) NOT NULL,
  `ID_USER` int(11) NOT NULL,
  `CONTENT_REPONSE` varchar(255) NOT NULL,
  `DATE_REPONSE` datetime NOT NULL,
  `DISPLAY` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID_MESSAGE`,`ID_USER`,`DATE_REPONSE`),
  KEY `fk_message_tbl_has_utilisateur_tbl_utilisateur_tbl1_idx` (`ID_USER`),
  KEY `fk_message_tbl_has_utilisateur_tbl_message_tbl1_idx` (`ID_MESSAGE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reponse_tbl`
--

INSERT INTO `reponse_tbl` (`ID_MESSAGE`, `ID_USER`, `CONTENT_REPONSE`, `DATE_REPONSE`, `DISPLAY`) VALUES
(3, 19, 'test', '2024-01-10 10:27:16', 1),
(3, 19, 'non c&#039;est d&eacute;bile', '2024-01-11 09:18:57', 1),
(10, 20, 'Ici une petite r&eacute;ponse Youppii', '2024-01-12 10:53:54', 1);

-- --------------------------------------------------------

--
-- Structure de la table `stateuser_tbl`
--

DROP TABLE IF EXISTS `stateuser_tbl`;
CREATE TABLE IF NOT EXISTS `stateuser_tbl` (
  `ID_STATE` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_STATE` varchar(45) NOT NULL,
  PRIMARY KEY (`ID_STATE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `stateuser_tbl`
--

INSERT INTO `stateuser_tbl` (`ID_STATE`, `LIBELLE_STATE`) VALUES
(1, 'ALLOWED'),
(2, 'TIMEOUT'),
(3, 'BANNED');

-- --------------------------------------------------------

--
-- Structure de la table `sujet_tbl`
--

DROP TABLE IF EXISTS `sujet_tbl`;
CREATE TABLE IF NOT EXISTS `sujet_tbl` (
  `ID_SUJET` int(11) NOT NULL AUTO_INCREMENT,
  `LIB_SUJET` varchar(50) DEFAULT NULL,
  `DATE_SUJET` date DEFAULT NULL,
  `ID_USER` int(11) DEFAULT NULL,
  `ID_THEME` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_SUJET`),
  KEY `FK_sujet_tbl_ID_USER_utilisateur_tbl` (`ID_USER`),
  KEY `FK_sujet_tbl_ID_THEME_theme_tbl` (`ID_THEME`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sujet_tbl`
--

INSERT INTO `sujet_tbl` (`ID_SUJET`, `LIB_SUJET`, `DATE_SUJET`, `ID_USER`, `ID_THEME`) VALUES
(1, 'sujet 1', '2023-11-25', 1, 2),
(2, 'Que pensez-vous de votre mutuelle', '2023-11-25', 4, 3),
(3, 'Que pensez-vous de votre mutuelle', '2023-12-10', NULL, 4);

-- --------------------------------------------------------

--
-- Structure de la table `theme_tbl`
--

DROP TABLE IF EXISTS `theme_tbl`;
CREATE TABLE IF NOT EXISTS `theme_tbl` (
  `ID_THEME` int(11) NOT NULL AUTO_INCREMENT,
  `LIB_THEME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_THEME`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `theme_tbl`
--

INSERT INTO `theme_tbl` (`ID_THEME`, `LIB_THEME`) VALUES
(1, 'Remboursements'),
(2, 'Devis'),
(3, 'Divers'),
(4, 'GENERALE');

-- --------------------------------------------------------

--
-- Structure de la table `type_utilisateur`
--

DROP TABLE IF EXISTS `type_utilisateur`;
CREATE TABLE IF NOT EXISTS `type_utilisateur` (
  `idType_Utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_Type_Utilisateur` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idType_Utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type_utilisateur`
--

INSERT INTO `type_utilisateur` (`idType_Utilisateur`, `LIBELLE_Type_Utilisateur`) VALUES
(1, 'UTILISATEUR'),
(2, 'AGENT'),
(3, 'MODERATEUR');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_tbl`
--

DROP TABLE IF EXISTS `utilisateur_tbl`;
CREATE TABLE IF NOT EXISTS `utilisateur_tbl` (
  `ID_USER` int(11) NOT NULL AUTO_INCREMENT,
  `PSEUDO_USER` varchar(25) NOT NULL,
  `MAIL_USER` varchar(45) NOT NULL,
  `MDP_USER` char(88) NOT NULL,
  `ARRIVE_UTILISATEUR` date NOT NULL,
  `DATA_USER` json DEFAULT NULL,
  `AVATAR_USER` varchar(255) DEFAULT NULL,
  `ID_ORGA` int(11) NOT NULL,
  `TYPE_UTILISATEUR` int(11) NOT NULL,
  `VALID_USER` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID_USER`),
  UNIQUE KEY `PSEUDO_USER` (`PSEUDO_USER`),
  UNIQUE KEY `MAIL_USER` (`MAIL_USER`),
  KEY `fk_utilisateur_tbl_organisme_tbl1_idx` (`ID_ORGA`),
  KEY `TYPE_UTILISATEUR` (`TYPE_UTILISATEUR`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur_tbl`
--

INSERT INTO `utilisateur_tbl` (`ID_USER`, `PSEUDO_USER`, `MAIL_USER`, `MDP_USER`, `ARRIVE_UTILISATEUR`, `DATA_USER`, `AVATAR_USER`, `ID_ORGA`, `TYPE_UTILISATEUR`, `VALID_USER`) VALUES
(1, 'USER1', 'test@mail.fr', 'oBzuh/ipr5brGCiph64hj17w0ETUlefMB+hFRL1BtireHqY7jwrJz0VkVY/wOej3u1Ez1fKrHi7lS8P3B+fYPQ==', '2023-05-15', '{\"dos.\": \"1234567\"}', 'default.png', 1, 1, 1),
(2, 'user2', 'user_2@gmail.com', 'oBzuh/ipr5brGCiph64hj17w0ETUlefMB+hFRL1BtireHqY7jwrJz0VkVY/wOej3u1Ez1fKrHi7lS8P3B+fYPQ==', '2023-05-15', '{\"dos.\": \"1234567\"}', 'default.png', 1, 1, 1),
(3, 'user3', 'user3@gmail.com', 'oBzuh/ipr5brGCiph64hj17w0ETUlefMB+hFRL1BtireHqY7jwrJz0VkVY/wOej3u1Ez1fKrHi7lS8P3B+fYPQ==', '2023-05-15', '{\"dos.\": \"1234567\"}', 'default.png', 1, 1, 1),
(4, 'JPDESH', 'JeanPaul.DESHIRE@gmail.com', 'oBzuh/ipr5brGCiph64hj17w0ETUlefMB+hFRL1BtireHqY7jwrJz0VkVY/wOej3u1Ez1fKrHi7lS8P3B+fYPQ==', '2023-05-15', '{\"dos.\": \"1234567\"}', 'default.png', 2, 1, 1),
(19, 'TEST', 'mail@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2023-12-14', '{\"dos.\": \"30215251\"}', 'default.png', 3, 1, 1),
(20, 'AGENT1', 'agent1@mail.com', '278189ab6bb4efb4170c27246bd2c28573dafd86aa73656363053fbcb478fdb7', '2023-12-30', '{\"nom\": \"Paul\", \"prenom\": \"Jordan\"}', 'default.png', 3, 2, 1),
(22, 'TEST2', 'test2@mail.fr', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2024-01-02', '{\"dos.\": \"1234567\"}', 'default.png', 3, 1, 1),
(23, 'AGENT2', 'agent2@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2024-01-04', '{\"nom\": \"Paul\", \"prenom\": \"Jordan\"}', 'default.png', 2, 2, 0);

--
-- Déclencheurs `utilisateur_tbl`
--
DROP TRIGGER IF EXISTS `after_insert_utilisateur_tbl`;
DELIMITER $$
CREATE TRIGGER `after_insert_utilisateur_tbl` AFTER INSERT ON `utilisateur_tbl` FOR EACH ROW BEGIN
    INSERT INTO utilisateur_tbl_has_stateuser_tbl  (utilisateur_tbl_has_stateuser_tbl.USER, utilisateur_tbl_has_stateuser_tbl.STATE, utilisateur_tbl_has_stateuser_tbl.DATE_STATE)
    VALUES (NEW.ID_USER, 1, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_tbl_has_stateuser_tbl`
--

DROP TABLE IF EXISTS `utilisateur_tbl_has_stateuser_tbl`;
CREATE TABLE IF NOT EXISTS `utilisateur_tbl_has_stateuser_tbl` (
  `USER` int(11) NOT NULL,
  `STATE` int(11) NOT NULL,
  `DATE_STATE` datetime NOT NULL,
  `MOTIF_STATE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`USER`,`STATE`,`DATE_STATE`),
  KEY `fk_utilisateur_tbl_has_StateUser_tbl_StateUser_tbl1_idx` (`STATE`),
  KEY `fk_utilisateur_tbl_has_StateUser_tbl_utilisateur_tbl1_idx` (`USER`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur_tbl_has_stateuser_tbl`
--

INSERT INTO `utilisateur_tbl_has_stateuser_tbl` (`USER`, `STATE`, `DATE_STATE`, `MOTIF_STATE`) VALUES
(1, 1, '2023-05-15 00:00:00', NULL),
(1, 2, '2023-05-16 15:08:08', 'Plusieurs messages d\'appel Ã  la violence'),
(2, 1, '2023-05-15 00:00:00', NULL),
(3, 1, '2023-05-15 00:00:00', NULL),
(4, 1, '2023-05-15 00:00:00', NULL),
(19, 1, '2023-12-14 14:32:37', NULL),
(19, 1, '2023-12-29 08:24:58', 'RÃ©demptiom accordÃ©e'),
(19, 3, '2023-12-28 13:22:31', 'Plusieurs propos injurieux'),
(20, 1, '2023-12-30 14:13:13', NULL),
(22, 1, '2024-01-02 17:17:49', NULL),
(23, 1, '2024-01-04 14:29:50', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `message_tbl`
--
ALTER TABLE `message_tbl`
  ADD CONSTRAINT `FK_message_tbl_ID_SUJET_sujet_tbl` FOREIGN KEY (`ID_SUJET`) REFERENCES `sujet_tbl` (`ID_SUJET`),
  ADD CONSTRAINT `FK_message_tbl_ID_USER_utilisateur_tbl` FOREIGN KEY (`ID_USER`) REFERENCES `utilisateur_tbl` (`ID_USER`);

--
-- Contraintes pour la table `reponse_tbl`
--
ALTER TABLE `reponse_tbl`
  ADD CONSTRAINT `fk_message_tbl_has_utilisateur_tbl_message_tbl1` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `message_tbl` (`ID_MESSAGE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_message_tbl_has_utilisateur_tbl_utilisateur_tbl1` FOREIGN KEY (`ID_USER`) REFERENCES `utilisateur_tbl` (`ID_USER`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `sujet_tbl`
--
ALTER TABLE `sujet_tbl`
  ADD CONSTRAINT `FK_sujet_tbl_ID_THEME_theme_tbl` FOREIGN KEY (`ID_THEME`) REFERENCES `theme_tbl` (`ID_THEME`),
  ADD CONSTRAINT `FK_sujet_tbl_ID_USER_utilisateur_tbl` FOREIGN KEY (`ID_USER`) REFERENCES `utilisateur_tbl` (`ID_USER`);

--
-- Contraintes pour la table `utilisateur_tbl`
--
ALTER TABLE `utilisateur_tbl`
  ADD CONSTRAINT `fk_utilisateur_tbl_organisme_tbl1` FOREIGN KEY (`ID_ORGA`) REFERENCES `organisme_tbl` (`ID_ORGA`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_tbl_has_stateuser_tbl`
--
ALTER TABLE `utilisateur_tbl_has_stateuser_tbl`
  ADD CONSTRAINT `fk_utilisateur_tbl_has_StateUser_tbl_StateUser_tbl1` FOREIGN KEY (`STATE`) REFERENCES `stateuser_tbl` (`ID_STATE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_utilisateur_tbl_has_StateUser_tbl_utilisateur_tbl1` FOREIGN KEY (`USER`) REFERENCES `utilisateur_tbl` (`ID_USER`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
