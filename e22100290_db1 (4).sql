-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 07 déc. 2024 à 08:46
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1-log
-- Version de PHP : 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e22100290_db1`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`e22100290sql`@`%` PROCEDURE `DeleteCandidature` (IN `Code` CHAR(20), IN `Code2` CHAR(8))   BEGIN
	DECLARE Id INT;

	-- Récupérer l'ID de la candidature en fonction du code
	SELECT id_candidature_can INTO Id
	FROM T_candidature_can
	WHERE code_candidature_can = Code
    && code_inscription_can = Code2;

	-- Supprimer les documents associés à cette candidature
	DELETE FROM T_document_doc
	WHERE id_candidature_can = Id;

	-- Supprimer les notes des jurys associées à cette candidature
	DELETE FROM T_candidature_jury_car
	WHERE id_candidature_can = Id;

	-- Supprimer la candidature elle-même
	DELETE FROM T_candidature_can
	WHERE id_candidature_can = Id;
END$$

CREATE DEFINER=`e22100290sql`@`%` PROCEDURE `new_actualite` ()   BEGIN
SET @id := (SELECT dernier_concour());
SET @nom := (SELECT nom_con FROM T_concours_con WHERE id_concours_con = @id);
SET @date_debut := (SELECT date_debut_con FROM T_concours_con WHERE id_concours_con = @id);
SET @desc := (SELECT description_con FROM T_concours_con WHERE id_concours_con = @id);
SET @mail := (SELECT email_cpt FROM T_concours_con WHERE id_concours_con = @id);
 INSERT INTO T_actualite_act (`etat_act`, `titre_actualite_act`, `date_actualite_act`, `contenu_act`, `id_concours_con`, `email_cpt`) VALUES('A',@nom,CURDATE(),CONCAT_WS(' ',@nom, @date_debut , @desc ) , Dernier_concour() , @mail);
END$$

--
-- Fonctions
--
CREATE DEFINER=`e22100290sql`@`%` FUNCTION `créateur_concours` (`id` INT) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    RETURN (SELECT email_cpt FROM T_concours_con WHERE id_concours_con = id);
END$$

CREATE DEFINER=`e22100290sql`@`%` FUNCTION `Dernier_concour` () RETURNS INT(11)  BEGIN RETURN (SELECT MAX(id_concours_con)FROM T_concours_con);
 END$$

CREATE DEFINER=`e22100290sql`@`%` FUNCTION `donner_cate` (`id` INT) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  RETURN (SELECT GROUP_CONCAT(cat.nom_cat SEPARATOR " - ") FROM `T_concours_con` AS con JOIN T_categorie_concours_coc AS coc ON coc.id_concours_con=con.id_concours_con JOIN T_categorie_cat AS cat ON cat.id_categorie_cat=coc.id_categorie_cat WHERE id = con.id_concours_con)$$

CREATE DEFINER=`e22100290sql`@`%` FUNCTION `donner_document` (`id` INT) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  RETURN (SELECT GROUP_CONCAT(CONCAT(nom_doc,"<br/>",description_doc) SEPARATOR "<br/>")FROM T_document_doc WHERE id_candidature_can = id)$$

CREATE DEFINER=`e22100290sql`@`%` FUNCTION `donner_jury` (`id` INT) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  RETURN (SELECT GROUP_CONCAT(CONCAT(" ",jur.nom_jur," ",jur.prenom_jur) SEPARATOR "<br/>") FROM `T_concours_con` AS con JOIN T_concours_jury_coj AS coj ON coj.id_concours_con=con.id_concours_con JOIN T_jury_jur AS jur ON jur.email_cpt = coj.email_cpt WHERE coj.id_concours_con = id)$$

CREATE DEFINER=`e22100290sql`@`%` FUNCTION `phase_concour` (`id` INT) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    SET @date_debut := (SELECT date_debut_con FROM T_concours_con WHERE id_concours_con = id);
    SET @date_can := ADDDATE(@date_debut, (SELECT nb_j_candidature_con FROM T_concours_con WHERE id_concours_con = id));
    SET @date_pre := ADDDATE(@date_can, (SELECT nb_j_preselect_con FROM T_concours_con WHERE id_concours_con = id));
    SET @date_sel := ADDDATE(@date_pre, (SELECT nb_j_select_con FROM T_concours_con WHERE id_concours_con = id));

    IF (@date_debut > CURDATE()) THEN 
        RETURN 'A venir'; 
    ELSEIF (@date_debut <= CURDATE() AND CURDATE() <= @date_can) THEN
        RETURN 'inscription';
    ELSEIF (@date_can < CURDATE() AND CURDATE() <= @date_pre) THEN
        RETURN 'preselection final';
    ELSEIF (@date_pre < CURDATE() AND CURDATE() <= @date_sel) THEN
        RETURN 'selection final';
    ELSE  
        RETURN 'terminé';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `ADMIN`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `ADMIN` (
`email_cpt` varchar(300)
,`mot_de_passe_cpt` char(64)
,`etat` char(1)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `JURY`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `JURY` (
`email_cpt` varchar(300)
,`mot_de_passe_cpt` char(64)
,`etat` char(1)
,`discipline_jur` varchar(60)
,`nom_jur` varchar(60)
,`prenom_jur` varchar(60)
,`bio_jur` varchar(1000)
,`url_jur` varchar(300)
);

-- --------------------------------------------------------

--
-- Structure de la table `T_actualite_act`
--

CREATE TABLE `T_actualite_act` (
  `id_actualite_act` int(11) NOT NULL,
  `etat_act` char(1) NOT NULL,
  `titre_actualite_act` varchar(60) NOT NULL,
  `date_actualite_act` date NOT NULL,
  `contenu_act` varchar(300) NOT NULL,
  `id_concours_con` int(11) NOT NULL,
  `email_cpt` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_actualite_act`
--

INSERT INTO `T_actualite_act` (`id_actualite_act`, `etat_act`, `titre_actualite_act`, `date_actualite_act`, `contenu_act`, `id_concours_con`, `email_cpt`) VALUES
(1, 'A', 'Lancement du Concours de Décoration d\'Île', '2024-09-01', 'Le concours de décoration d\'île est officiellement lancé ! Participez pour montrer votre talent en aménagement extérieur.', 1, 'marie@example.com'),
(2, 'A', 'Ouverture du Concours de Création de Paternes', '2024-10-05', 'Les inscriptions pour le concours de création de paternes sont ouvertes ! Présentez vos meilleurs motifs et inspirez la communauté.', 2, 'marie@example.com'),
(3, 'A', 'Début du Concours de Décoration d\'Intérieur', '2024-09-15', 'Le concours de décoration d\'intérieur démarre aujourd\'hui. Montrez vos compétences dans la création d\'espaces cosy et accueillants.', 3, 'marc@example.com'),
(4, 'A', 'Annonce des finalistes - Concours de Création de Vêtements', '2024-08-20', 'Les finalistes du concours de création de vêtements ont été annoncés. Découvrez leurs designs innovants et inspirants.', 4, 'severine@example.com'),
(5, 'A', 'Concours de Décoration d\'Extérieur - Phase finale', '2024-11-15', 'Le concours de décoration d\'extérieur entre dans sa phase finale. Les participants doivent soumettre leurs créations avant la date limite.', 5, 'williams@example.com'),
(6, 'A', 'Nouveau Jury pour le Concours de Création de Paternes', '2024-10-10', 'Isabelle Caudmont rejoint le jury du concours de création de paternes. Son expertise dans les motifs pixelisés promet une sélection exigeante.', 2, 'severine@example.com'),
(7, 'A', 'Cérémonie de Remise des Prix - Concours de Décoration d\'Île', '2024-11-30', 'La cérémonie de remise des prix pour le concours de décoration d\'île aura lieu le 30 novembre. Venez découvrir les gagnants et leurs créations !', 1, 'marc@example.com'),
(8, 'A', 'Concours de Décoration d\'Extérieur', '2024-10-14', 'Concours de Décoration d\'Extérieur 2024-12-01 Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(9, 'A', 'Concours de Décoration d\'Extérieur', '2024-10-14', 'Concours de Décoration d\'Extérieur 2024-12-01 Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(23, 'A', 'Concours de Décoration d\'Île', '2024-10-15', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-20 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'organisateur.anch@gmail.com'),
(24, 'A', 'Concours de Décoration d\'Île', '2024-10-15', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-20 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'marie@example.com'),
(25, 'A', 'Concours de Décoration d\'Île', '2024-10-15', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-20 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'marc@example.com'),
(27, 'A', 'Concours de Décoration d\'Île', '2024-11-02', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-01 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'organisateur.anch@gmail.com'),
(28, 'A', 'Concours de Création de Paternes', '2024-11-02', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-10-29 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'organisateur.anch@gmail.com'),
(29, 'A', 'Concours de Décoration d\'Intérieur', '2024-11-02', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-10-27 descriptif Un concours visant à récompenser les meilleures décorations d\'intérieur sur Animal Crossing.', 3, 'organisateur.anch@gmail.com'),
(30, 'A', 'Concours de Décoration d\'Île', '2024-11-02', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-10-28 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'organisateur.anch@gmail.com'),
(31, 'A', 'Concours de Décoration d\'Île', '2024-11-02', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-10-24 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'organisateur.anch@gmail.com'),
(32, 'A', 'Concours de Décoration d\'Extérieurs', '2024-11-12', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2025-05-14 descriptif Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(34, 'A', 'Concours de Création de Paternes', '2024-11-28', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-26 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(35, 'A', 'Concours de Création de Paternes', '2024-11-28', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-28 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(36, 'A', 'Concours de Création de Paternes', '2024-11-28', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-29 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(37, 'A', 'Concours de Création de Paternes', '2024-11-28', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-20 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(38, 'A', 'Concours de Création de Paternes', '2024-11-28', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-23 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(39, 'A', ' Attention, changement du nom du concours', '2024-11-28', ' Attention, changement du nom du concours Concours de Décoration d\'Île devient Concours de Décoration d Île', 1, 'organisateur.anch@gmail.com'),
(40, 'A', ' Attention, changement du nom du concours', '2024-11-28', ' Attention, changement du nom du concours Concours de Décoration d\'Intérieur devient Concours de Décoration d Intérieur', 3, 'organisateur.anch@gmail.com'),
(41, 'A', ' Attention, changement du nom du concours', '2024-11-28', ' Attention, changement du nom du concours Concours de Décoration d\'Extérieurs devient Concours de Décoration d Extérieurs', 5, 'organisateur.anch@gmail.com'),
(42, 'A', 'Concours de Création de Paternes', '2024-11-30', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-26 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(43, 'A', 'Concours de Création de Paternes', '2024-11-30', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-28 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(44, 'A', 'Concours de Création de Paternes', '2024-11-30', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-27 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(45, 'A', 'Concours de Création de Paternes', '2024-11-30', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-26 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(46, 'A', 'Concours de Décoration d Île', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-12-17 descriptif Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 1, 'marc@example.com'),
(47, 'A', 'Concours de Création de Paternes', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-30 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(48, 'A', 'Concours de Décoration d Intérieur', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-27 descriptif Un concours visant à récompenser les meilleures décorations d\'intérieur sur Animal Crossing.', 3, 'severine@example.com'),
(49, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-19 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(50, 'A', 'Concours de Création de Paternes', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-29 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(51, 'A', 'Concours de Décoration d Extérieurs', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-07-16 descriptif Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(52, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-18 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(53, 'A', 'Concours de Décoration d Extérieurs', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-07-14 descriptif Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(54, 'A', 'Concours de Décoration d Extérieurs', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-07-13 descriptif Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(55, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-16 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(56, 'A', 'Concours de Décoration d Extérieurs', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-07-11 descriptif Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 5, 'marie@example.com'),
(57, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-13 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(58, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-15 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(59, 'A', 'Concours de Création de Vêtements', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-19 descriptif Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 4, 'organisateur.anch@gmail.com'),
(60, 'A', 'Concours de Décoration d Intérieur', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-28 descriptif Un concours visant à récompenser les meilleures décorations d\'intérieur sur Animal Crossing.', 3, 'severine@example.com'),
(61, 'A', 'Concours de Décoration d Intérieur', '2024-12-03', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-12-02 descriptif Un concours visant à récompenser les meilleures décorations d\'intérieur sur Animal Crossing.', 3, 'severine@example.com'),
(62, 'A', 'Concours de Création de Paternes', '2024-12-04', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-12-01 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com'),
(63, 'A', 'Concours de Création de Paternes', '2024-12-04', 'MODIFICATIONS DU CONCOURS =>  date de debut: 2024-11-30 descriptif Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 2, 'williams@example.com');

-- --------------------------------------------------------

--
-- Structure de la table `T_administration_adm`
--

CREATE TABLE `T_administration_adm` (
  `email_cpt` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_administration_adm`
--

INSERT INTO `T_administration_adm` (`email_cpt`) VALUES
('marc@example.com'),
('marie@example.com'),
('organisateur.anch@gmail.com'),
('severine@example.com'),
('test@admin.com'),
('test@admin2.com'),
('williams@example.com');

-- --------------------------------------------------------

--
-- Structure de la table `T_candidature_can`
--

CREATE TABLE `T_candidature_can` (
  `id_candidature_can` int(11) NOT NULL,
  `nom_can` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `prenom_can` varchar(60) NOT NULL,
  `email_can` varchar(300) NOT NULL,
  `code_candidature_can` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `code_inscription_can` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `presentation_can` varchar(1000) NOT NULL,
  `selection_can` char(1) NOT NULL,
  `annulation_can` char(1) NOT NULL,
  `id_concours_con` int(11) NOT NULL,
  `id_categorie_cat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_candidature_can`
--

INSERT INTO `T_candidature_can` (`id_candidature_can`, `nom_can`, `prenom_can`, `email_can`, `code_candidature_can`, `code_inscription_can`, `presentation_can`, `selection_can`, `annulation_can`, `id_concours_con`, `id_categorie_cat`) VALUES
(1, 'Durand', 'Alice', 'alice@example.com', 'CAN202420260123456', 'qzf7F5Za', 'Je suis passionnée par la décoration intérieure depuis plusieurs années et je souhaite participer à ce concours pour partager mes idées.', 'P', 'N', 2, 1),
(2, 'Lemoine', 'Bruno', 'bruno@example.com', 'CAN2234567890123456', '7g3Uto0W', 'Designer de paysages, je crée des environnements extérieurs inspirés par la nature et l\'équilibre.', 'N', 'N', 2, 1),
(3, 'Martin', 'Cécile', 'cecile@example.com', 'CAN3234567890123456', 'EQd6k4z8', 'Créatrice de motifs dans Animal Crossing, j\'adore travailler sur des designs uniques et colorés.', 'N', 'N', 3, 1),
(4, 'Renard', 'David', 'david@example.com', 'CAN4234567890123456', '5wO5yW4w', 'Architecte passionné, je suis ici pour présenter mes idées d\'aménagement urbain dans le jeu.', 'N', 'N', 1, 2),
(5, 'Leroy', 'Emma', 'emma@example.com', 'CAN5234567890123456', '8N58nyUe', 'Ma spécialité est la création de jardins zen et de paysages calmes dans Animal Crossing.', 'F', 'N', 4, 2),
(6, 'Blanc', 'Fabien', 'fabien@example.com', 'CAN6234567890123456', '0p2DlhO8', 'Je propose des conceptions avant-gardistes pour la décoration intérieure de votre maison sur l\'île.', 'P', 'N', 2, 2),
(7, 'Petit', 'Gabrielle', 'gabrielle@example.com', 'CAN7234567890123456', 'S5g0g6Cx', 'Créatrice passionnée de vêtements, j\'aime jouer avec les motifs floraux et les couleurs éclatantes.', 'N', 'N', 1, 3),
(8, 'Roux', 'Hugo', 'hugo@example.com', 'CAN8234567890123456', 'T2xx6O5t', 'Je participe avec des créations minimalistes pour les extérieurs urbains et modernes.', 'N', 'N', 1, 3),
(9, 'Morin', 'Isabelle', 'isabelle@example.com', 'CAN9234567890123456', 'aO7Z9ps3', 'Designer spécialisée dans les vêtements, je propose des créations chic et décontractées.', 'P', 'N', 2, 4),
(10, 'Fabre', 'Julien', 'julien@example.com', 'CAN0234567890123456', 'rd8A7b8X', 'Mes créations se concentrent sur les motifs géométriques et abstraits pour les sols et murs.', 'S', 'N', 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `T_candidature_jury_car`
--

CREATE TABLE `T_candidature_jury_car` (
  `id_candidature_can` int(11) NOT NULL,
  `email_cpt` varchar(300) NOT NULL,
  `note_car` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_candidature_jury_car`
--

INSERT INTO `T_candidature_jury_car` (`id_candidature_can`, `email_cpt`, `note_car`) VALUES
(1, 'fredo@example.com', 4),
(1, 'julien@example.com', 3),
(1, 'roland@example.com', 4),
(2, 'fredo@example.com', 1),
(2, 'roland@example.com', 1),
(3, 'isabelle@example.com', 3),
(3, 'martine@example.com', 4),
(4, 'roland@example.com', 5),
(5, 'fredo@example.com', 2),
(6, 'valérie@example.com', 4),
(7, 'clarisse@example.com', 3),
(7, 'julien@example.com', 2),
(8, 'roland@example.com', 5),
(9, 'clarisse@example.com', 4),
(10, 'isabelle@example.com', 3),
(10, 'martine@example.com', 2);

-- --------------------------------------------------------

--
-- Structure de la table `T_categorie_cat`
--

CREATE TABLE `T_categorie_cat` (
  `id_categorie_cat` int(11) NOT NULL,
  `nom_cat` varchar(60) NOT NULL,
  `description_cat` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_categorie_cat`
--

INSERT INTO `T_categorie_cat` (`id_categorie_cat`, `nom_cat`, `description_cat`) VALUES
(1, 'Gothique', 'Une catégorie sombre et élégante, avec des éléments architecturaux anciens, des couleurs sombres et des motifs victoriens.'),
(2, 'Estival', 'Une catégorie joyeuse et lumineuse, avec des thèmes d\'été, des couleurs vives et des motifs floraux et tropicaux.'),
(3, 'Futuriste', 'Une catégorie axée sur des designs modernes et avant-gardistes, avec des lignes épurées, des éléments technologiques et des concepts futuristes.'),
(4, 'Occidentale', 'Une catégorie inspirée par la culture occidentale, avec des éléments traditionnels, rustiques, et des designs de style ranch ou Far West.'),
(5, 'Asiatique', 'Une catégorie basée sur l\'esthétique des cultures asiatiques, avec des jardins zen, des temples, des couleurs apaisantes et des motifs traditionnels.');

-- --------------------------------------------------------

--
-- Structure de la table `T_categorie_concours_coc`
--

CREATE TABLE `T_categorie_concours_coc` (
  `id_categorie_cat` int(11) NOT NULL,
  `id_concours_con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_categorie_concours_coc`
--

INSERT INTO `T_categorie_concours_coc` (`id_categorie_cat`, `id_concours_con`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 4),
(3, 1),
(3, 3),
(3, 5),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(5, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `T_compte_cpt`
--

CREATE TABLE `T_compte_cpt` (
  `email_cpt` varchar(300) NOT NULL,
  `mot_de_passe_cpt` char(64) NOT NULL,
  `etat` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_compte_cpt`
--

INSERT INTO `T_compte_cpt` (`email_cpt`, `mot_de_passe_cpt`, `etat`) VALUES
('anastasia@example.com', '17a2cc45692a9fe794eac47a9d532c867503b66eb076973392ec13633b9d796d', 'J'),
('clarisse@example.com', 'd921a5f7a9011976937f6e908d15c2a2e5e638a6922926683cec1d2a58d265f0', 'J'),
('fredo@example.com', 'd8f73a048bd786998120f2e64c1548b230a055bb6c1d396127ed357eef1db2eb', 'J'),
('isabelle@example.com', '45c8c52f66f589fe720eda68d42e34a52c6fcb84f70cacf1e31ac2ad19a99fa9', 'J'),
('julien@example.com', '99f644077a27a5027f63d4a9be5653b08ce14370b76d6ffaa4b90088c471d4fe', 'J'),
('marc@example.com', '7fcc2f5dc04d785d886ecfa6d7a1abfd35b64838b90d1c36accf545e749cda21', 'A'),
('marco@example.com', '32b036be86866e0721be27d6dae436367b9666932f2b68413549de66ad2afa57', 'J'),
('marie@example.com', '0a103cf7f894471f66711a968c8be3b382b1db7b34d963acfb0a78b58a65a88c', 'D'),
('martine@example.com', '3f8da5f9725b10c3ab6a0d104364ba0c58755ece048846b94a8cd61abb58a1cd', 'D'),
('organisateur.anch@gmail.com', '276cb233d21f7b6271ab05f6808d1a73fbe38d76dcf0dfd78583808dfbdbac72', 'A'),
('roland@example.com', '082dab37e48de447f285a3a9952c473930adbf3950dc014ec3d63d04100034da', 'J'),
('sanndo@admin.fr', '3d883c33f3a513d2d98dc42a876c873ecfd27776f1feee67a1a3bb1b4323ce22', 'J'),
('severine@example.com', '8a9cd8b15bfb4cc5b539babea2c88746019a24d0654a2b130a72f1fb44c1b6d7', 'A'),
('test@admin.com', '0638300a6f5a0ac404d5c226a53ad93567b018a1e2e8f0fe601250b1e0f3ed88', 'A'),
('test@admin2.com', '20cca879f5d94d3b4d93079a3487fb4fdc385d355ed4ca105fa4fae29bffb903', 'A'),
('test@jury.com', '20cca879f5d94d3b4d93079a3487fb4fdc385d355ed4ca105fa4fae29bffb903', 'J'),
('test@jury2.com', '4e672425923c4f28d1e5df5a8d2b4c7d4c6c264052eb934ab5dbe9a0ca57acda', 'J'),
('theo@example.com', '170ea90a0a057b0e952fe9a4dc2ac22090d7b09f0fb33e9386d4405207d51b76', 'J'),
('valérie@example.com', '969f6b0daccabf40fa276297cc444fb38e78cadd9e810b847d3edcb389de8bdc', 'J'),
('williams@example.com', '7032e39f30b40e8e8a6914f1168ecac4ced026f7b223573d7415df4fbccd9a35', 'A');

--
-- Déclencheurs `T_compte_cpt`
--
DELIMITER $$
CREATE TRIGGER `salage` BEFORE INSERT ON `T_compte_cpt` FOR EACH ROW BEGIN
SET NEW.mot_de_passe_cpt = SHA2(CONCAT("mdpcacestmoi" , new.mot_de_passe_cpt),256) ;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sup_compte` BEFORE DELETE ON `T_compte_cpt` FOR EACH ROW BEGIN
	UPDATE `T_concours_con` SET `email_cpt` = NULL WHERE email_cpt = OLD.email_cpt;
	DELETE FROM T_actualite_act WHERE email_cpt = OLD.email_cpt;
    DELETE FROM T_administration_adm WHERE email_cpt = OLD.email_cpt;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `T_concours_con`
--

CREATE TABLE `T_concours_con` (
  `id_concours_con` int(11) NOT NULL,
  `nom_con` varchar(60) NOT NULL,
  `image_con` varchar(200) DEFAULT NULL,
  `description_con` varchar(300) NOT NULL,
  `edition_con` varchar(60) NOT NULL,
  `date_debut_con` date NOT NULL,
  `nom_discipline_con` varchar(60) NOT NULL,
  `nb_j_select_con` int(11) NOT NULL,
  `email_cpt` varchar(300) NOT NULL,
  `nb_j_preselect_con` int(11) NOT NULL,
  `nb_j_candidature_con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_concours_con`
--

INSERT INTO `T_concours_con` (`id_concours_con`, `nom_con`, `image_con`, `description_con`, `edition_con`, `date_debut_con`, `nom_discipline_con`, `nb_j_select_con`, `email_cpt`, `nb_j_preselect_con`, `nb_j_candidature_con`) VALUES
(1, 'Concours de Décoration d Île', 'http://example.com/decoration_ile.png', 'Un concours de décoration insulaire mettant en valeur la créativité des joueurs dans l\'aménagement d\'îles paradisiaques.', 'Édition 2024', '2024-12-17', 'Décoration d\'Île', 5, 'marc@example.com', 5, 5),
(2, 'Concours de Création de Paternes', 'http://example.com/creation_paternes.png', 'Un concours dédié à la création de motifs uniques pour vêtements et décoration d\'îles.', 'Édition 2024', '2024-11-30', 'Création de Paternes', 3, 'williams@example.com', 3, 3),
(3, 'Concours de Décoration d Intérieur', 'http://example.com/decoration_interieur.png', 'Un concours visant à récompenser les meilleures décorations d\'intérieur sur Animal Crossing.', 'Édition 2024', '2024-12-02', 'Décoration d\'Intérieur', 4, 'severine@example.com', 4, 4),
(4, 'Concours de Création de Vêtements', 'http://example.com/creation_vetements.png', 'Un concours pour les créateurs de vêtements, offrant aux participants l\'opportunité de présenter des tenues stylées et originales.', 'Édition 2024', '2024-11-19', 'Création de Vêtements', 6, 'organisateur.anch@gmail.com', 6, 6),
(5, 'Concours de Décoration d Extérieurs', 'http://example.com/decoration_exterieur.png', 'Concours récompensant les meilleurs espaces extérieurs créés dans Animal Crossing.', 'Édition 2024', '2024-07-11', 'Décoration d\'Extérieur', 5, 'marie@example.com', 5, 5);

--
-- Déclencheurs `T_concours_con`
--
DELIMITER $$
CREATE TRIGGER `SUP_concours` BEFORE DELETE ON `T_concours_con` FOR EACH ROW BEGIN
	DELETE FROM T_categorie_concours_coc WHERE id_concours_con = OLD.id_concours_con;
    DELETE FROM T_actualite_act WHERE id_concours_con = OLD.id_concours_con;
    DELETE FROM T_document_doc WHERE id_candidature_can IN (SELECT id_candidature_can FROM T_candidature_can WHERE id_concours_con = OLD.id_concours_con);
    DELETE FROM T_candidature_jury_car WHERE id_candidature_can IN (SELECT id_candidature_can FROM T_candidature_can WHERE id_concours_con = OLD.id_concours_con);
    DELETE FROM T_candidature_can WHERE id_concours_con = OLD.id_concours_con;
    DELETE FROM T_message_mes WHERE id_discution_fil IN (SELECT id_discution_fil FROM T_discution_fil WHERE id_concours_con = OLD.id_concours_con);
    DELETE FROM T_discution_fil WHERE id_concours_con = OLD.id_concours_con;
    DELETE FROM T_concours_jury_coj  WHERE id_concours_con = OLD.id_concours_con;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `concourac` AFTER INSERT ON `T_concours_con` FOR EACH ROW BEGIN
CALL new_actualite();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `modif_concours` AFTER UPDATE ON `T_concours_con` FOR EACH ROW BEGIN
IF OLD.nom_con != NEW.nom_con
AND OLD.date_debut_con = NEW.date_debut_con
AND OLD.description_con = NEW.description_con
AND OLD.edition_con= NEW.edition_con
AND OLD.image_con= NEW.image_con
AND OLD.nb_j_select_con = NEW.nb_j_select_con
AND OLD.nb_j_preselect_con = NEW.nb_j_preselect_con
AND OLD.nb_j_candidature_con = NEW.nb_j_candidature_con
AND OLD.email_cpt= NEW.email_cpt
AND OLD.nom_discipline_con= NEW.nom_discipline_con THEN
    INSERT INTO T_actualite_act (`etat_act`, `titre_actualite_act`, `date_actualite_act`, `contenu_act`, `id_concours_con`, `email_cpt`) VALUES('A',' Attention, changement du nom du concours',CURDATE(),CONCAT_WS(' ',' Attention, changement du nom du concours',OLD.nom_con,'devient',NEW.nom_con),NEW.id_concours_con, "organisateur.anch@gmail.com");
ELSE
    INSERT INTO T_actualite_act (`etat_act`, `titre_actualite_act`, `date_actualite_act`, `contenu_act`, `id_concours_con`, `email_cpt`) VALUES('A',NEW.nom_con,CURDATE(),CONCAT_WS(' ','MODIFICATIONS DU CONCOURS => ','date de debut:',NEW.date_debut_con,'descriptif',NEW.description_con), NEW.id_concours_con, NEW.email_cpt);
END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `T_concours_jury_coj`
--

CREATE TABLE `T_concours_jury_coj` (
  `id_concours_con` int(11) NOT NULL,
  `email_cpt` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_concours_jury_coj`
--

INSERT INTO `T_concours_jury_coj` (`id_concours_con`, `email_cpt`) VALUES
(1, 'fredo@example.com'),
(1, 'roland@example.com'),
(2, 'isabelle@example.com'),
(2, 'martine@example.com'),
(2, 'test@jury2.com'),
(3, 'marco@example.com'),
(3, 'valérie@example.com'),
(4, 'clarisse@example.com'),
(4, 'julien@example.com'),
(5, 'test@jury2.com');

-- --------------------------------------------------------

--
-- Structure de la table `T_discution_fil`
--

CREATE TABLE `T_discution_fil` (
  `id_discution_fil` int(11) NOT NULL,
  `sujet_fil` varchar(200) NOT NULL,
  `id_concours_con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_discution_fil`
--

INSERT INTO `T_discution_fil` (`id_discution_fil`, `sujet_fil`, `id_concours_con`) VALUES
(1, 'Discussion générale sur les critères de sélection', 1),
(2, 'Échange d\'idées pour la création gothique', 2),
(3, 'Conseils et astuces pour améliorer les projets futuristes', 3);

-- --------------------------------------------------------

--
-- Structure de la table `T_document_doc`
--

CREATE TABLE `T_document_doc` (
  `id_document_doc` int(11) NOT NULL,
  `nom_doc` varchar(60) NOT NULL,
  `description_doc` varchar(300) NOT NULL,
  `id_candidature_can` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_document_doc`
--

INSERT INTO `T_document_doc` (`id_document_doc`, `nom_doc`, `description_doc`, `id_candidature_can`) VALUES
(1, 'CV', 'CV complet de la candidate', 1),
(2, 'Portfolio', 'Portfolio des créations réalisées par le candidat', 1),
(3, 'Lettre de motivation', 'Lettre de motivation expliquant l\'intérêt pour le concours', 2),
(4, 'Présentation projet', 'Présentation détaillée du projet soumis au concours', 2),
(5, 'Références', 'Liste des références professionnelles du candidat', 3),
(6, 'Rapport de stage', 'Rapport de stage réalisé dans une entreprise liée au domaine du concours', 3),
(8, 'Certificat', 'Certificat prouvant les qualifications du candidat', 5),
(9, 'Plan de projet', 'Plan de projet soumis pour la compétition', 6),
(10, 'Lettre de recommandation', 'Lettre de recommandation d\'un ancien professeur', 6),
(15, 'img', 'inspirations-ile-animal-crossing-640x400', 1),
(17, 'img', 'inspirations-ile-animal-crossing-640x400', 6);

-- --------------------------------------------------------

--
-- Structure de la table `T_jury_jur`
--

CREATE TABLE `T_jury_jur` (
  `discipline_jur` varchar(60) NOT NULL,
  `nom_jur` varchar(60) NOT NULL,
  `prenom_jur` varchar(60) NOT NULL,
  `bio_jur` varchar(1000) NOT NULL,
  `url_jur` varchar(300) NOT NULL,
  `email_cpt` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_jury_jur`
--

INSERT INTO `T_jury_jur` (`discipline_jur`, `nom_jur`, `prenom_jur`, `bio_jur`, `url_jur`, `email_cpt`) VALUES
('Décoration d\'extérieur', 'Moore', 'Anastasia', 'Anastasia Moore est une experte en aménagement extérieur dans Animal Crossing, reconnue pour ses designs structurés et bien organisés. Son approche combine des éléments naturels avec un agencement méthodique, créant des jardins zen, des parcs urbains et des espaces communautaires. Sa maîtrise des allées, des plans d\'eau et des terrasses lui permet de transformer n\'importe quelle île en un havre de paix parfaitement équilibré. Son projet phare, \"Jardin Symétrique\", est admiré pour son design géométrique et ses espaces parfaitement optimisés.', 'http://example.com/moore_anastasia', 'anastasia@example.com'),
('Création de vêtements', 'Anderson', 'Clarisse', 'Je m\'appelle Clarisse Anderson, mais on me connaît surtout sous le nom de Clacla. Je suis créatrice de vêtements dans Animal Crossing et je me spécialise dans les tenues bohèmes et décontractées. Inspirée par la nature, les motifs floraux et les textiles artisanaux, j’aime créer des robes longues, des chemises légères et des chapeaux de paille qui apportent un style à la fois chic et relax aux joueurs. Ma collection \"Soleil Bohème\" a rencontré un grand succès grâce à ses pièces colorées et confortables, idéales pour les îles tropicales. J’adore partager mes créations et tutoriels sur les réseaux sociaux, et j’ai la chance d\'inspirer une large communauté de joueurs à travers le monde.', 'http://example.com/anderson_clarisse', 'clarisse@example.com'),
('Décoration d\'île', 'Blondel', 'Fredo', 'Blondel Fredo, est une créateur d\'île sur Animal Crossing connue pour ses talents dans la création de paysages naturels apaisants. Depuis la sortie de New Horizons, je me suis spécialisée dans la décoration d\'îles inspirées de la nature, mêlant jardins japonais, forêts enchantées et plages tropicales. Passionnée par l\'agencement des espaces verts, je cherche à créer des environnements sereins et accueillants. Mon île la plus célèbre, \"Éden Floral\", a été visitée par des milliers de joueurs à la recherche d\'inspiration zen. Mes conseils et créations sont largement partagés sur les réseaux sociaux.', 'http://example.com/blondel_fredo', 'fredo@example.com'),
('Création de paternes', 'Caudmont', 'Isabelle', 'Isabelle Caudmont, alias PixelMax, est une experte de la création de motifs en pixels dans Animal Crossing. Depuis la sortie de New Horizons, j\'ai conçu une multitude de patern originaux, allant des pavés réalistes aux fresques murales inspirées de l\'art rétro 8-bit. Je suis apprécié pour ma capacité à transformer des concepts complexes en simples motifs pixelisés, offrant ainsi aux joueurs une grande variété d\'options de personnalisation. Ma série \"Pixel Paradise\" a connu un grand succès, avec des motifs utilisés sur des milliers d\'îles.', 'http://example.com/caudmont_isabelle', 'isabelle@example.com'),
('Création de vêtements', 'Taylor', 'Julien', 'Julien Taylor, alias JuJu, est un créateur de vêtements renommé dans Animal Crossing pour ses designs urbains et avant-gardistes. Il puise son inspiration dans la mode de rue et les tendances modernes, offrant aux joueurs des tenues stylées et branchées. Ses créations incluent des vestes en cuir, des sneakers colorées, et des accessoires audacieux. Sa ligne \"Streetwear Chic\" a marqué la communauté pour son approche novatrice du style dans le jeu, mélangeant confort et élégance. Julien est suivi par une large communauté qui apprécie ses designs uniques et sa capacité à réinventer la mode insulaire.', 'http://example.com/taylor_julien', 'julien@example.com'),
('Décoration d\'intérieur', 'Delanoy', 'Marco', 'Delanoy Marco , alias Elo, est un décorateur d\'intérieur sur Animal Crossing connue pour ses designs chaleureux et accueillants. Son style est axé sur la création d\'espaces intérieurs cosy, inspirés du style scandinave avec une touche de vintage. Elo adore utiliser des éléments en bois, des textures douces, et des couleurs neutres pour créer des ambiances relaxantes. Ses intérieurs sont souvent décrits comme de véritables refuges où les joueurs peuvent se détendre. Sa plus grande réalisation est la \"Maison Hygge\", un modèle parfait de confort et d\'harmonie. Il partage ses créations et astuces avec une large communauté de fans sur les réseaux sociaux.', 'http://example.com/delanoy_marco', 'marco@example.com'),
('Création de paternes', 'Daniel', 'Martine', 'Martine Oudot, est une créatrice de motifs sur Animal Crossing renommée pour mes patern floraux délicats et colorés. Passionnée par l\'art textile et le dessin depuis mon enfance, je me suis lancée dans la création de patern pour personnaliser les vêtements et les sols des îles des joueurs. Ses motifs, inspirés des jardins anglais et des fleurs sauvages, apportent une touche romantique à n\'importe quelle île. Sa collection de patern, disponible en téléchargement, est appréciée pour sa précision et ses détails. Je partage régulièrement mes créations sur les réseaux sociaux, où j\'inspire des milliers de joueurs.', 'http://example.com/daniel_martine', 'martine@example.com'),
('Décoration d\'île', 'Oudot', 'Roland', 'Oudot Roland est un architecte virtuel dans Animal Crossing spécialisé dans les îles urbaines et contemporaines. Mes créations, souvent minimalistes et épurées, intègrent des rues pavées, des parcs modernes et des espaces communautaires, avec un grand souci du détail. Passionné par le design, je crée des environnements dynamiques qui combinent fonctionnalité et esthétique. Mon île \"Cité Métropolitaine\" est un modèle d\'organisation urbaine, acclamée par la communauté pour mon design innovant et futuriste. ', 'http://example.com/oudot_roland', 'roland@example.com'),
('dsef', 'zfq', 'sge', 'esg', 'seg', 'test@jury.com'),
('Fleur', 'Roro', 'Mick', 'Mick est un grand décorateur de parterre de fleure ', 'pas url', 'test@jury2.com'),
('Décoration d\'extérieur', 'Wilson', 'Theo', 'Je suis un spécialiste de la décoration d\'extérieur sur Animal Crossing, connu pour mes créations féeriques et enchanteuses. Passionné par la nature et le monde des contes de fées, je transforme les espaces extérieurs en jardins magiques remplis de fleurs colorées, de fontaines scintillantes et de sentiers mystérieux. Mon île \"Forêt Enchantée\" est un véritable chef-d\'œuvre, où chaque recoin raconte une histoire. Mes créations inspirent les joueurs à intégrer plus de nature et de rêve dans leurs îles, avec une touche de romantisme. Je partage mes astuces sur les réseaux sociaux, captivant un public fidèle.', 'http://example.com/wilson_theo', 'theo@example.com'),
('Décoration d\'intérieur', 'Miller', 'Valérie', 'Valérie, alias VaVa, est une experte en décoration d\'intérieur sur Animal Crossing spécialisée dans les designs contemporains. Ses créations se distinguent par l\'utilisation de meubles modernes, des lignes épurées, et des palettes de couleurs audacieuses. VaVa est connue pour transformer des espaces ordinaires en lofts élégants ou en appartements luxueux, mélangeant des styles urbains et minimalistes. Son projet le plus populaire, \"Loft Urbain\", a marqué la communauté pour son innovation en matière d\'utilisation de l\'espace et de la lumière.', 'http://example.com/miller_valérie', 'valérie@example.com');

-- --------------------------------------------------------

--
-- Structure de la table `T_message_mes`
--

CREATE TABLE `T_message_mes` (
  `id_message_mes` int(11) NOT NULL,
  `message_mes` varchar(500) NOT NULL,
  `id_discution_fil` int(11) NOT NULL,
  `email_cpt` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `T_message_mes`
--

INSERT INTO `T_message_mes` (`id_message_mes`, `message_mes`, `id_discution_fil`, `email_cpt`) VALUES
(1, 'Quels sont les critères les plus importants pour la sélection ?', 1, 'fredo@example.com'),
(2, 'Je pense que l\'originalité et la cohérence avec le thème gothique sont primordiales.', 2, 'roland@example.com'),
(3, 'Avez-vous des conseils pour bien structurer un projet futuriste ?', 3, 'martine@example.com'),
(4, 'Je recommande d\'utiliser des éléments technologiques innovants pour les projets futuristes.', 3, 'isabelle@example.com'),
(5, 'Pour le gothique, j\'aime voir des détails historiques dans les créations.', 2, 'marco@example.com'),
(6, 'Le niveau de finition des projets doit aussi être pris en compte.', 1, 'valérie@example.com'),
(7, 'Les participants devraient partager leurs sources d\'inspiration.', 1, 'theo@example.com'),
(8, 'Comment trouver un bon équilibre entre modernité et tradition ?', 3, 'anastasia@example.com'),
(9, 'Les éléments naturels jouent un rôle important dans les projets gothiques.', 2, 'julien@example.com'),
(10, 'Je suis d\'accord, les projets gothiques doivent intégrer l\'histoire et le mystère.', 2, 'clarisse@example.com'),
(11, 'J\'ai trouvé intéressant d\'utiliser des effets de lumière pour un projet futuriste.', 3, 'fredo@example.com'),
(12, 'Pour les concours, je pense que la clarté des explications est essentielle.', 1, 'roland@example.com'),
(13, 'Est-ce que vous privilégiez l\'aspect visuel ou la fonctionnalité ?', 3, 'martine@example.com'),
(14, 'Pour les projets gothiques, les textures sombres et riches sont très efficaces.', 2, 'isabelle@example.com'),
(15, 'Je suis impressionné par la qualité des projets soumis jusqu\'à présent.', 1, 'marco@example.com');

-- --------------------------------------------------------

--
-- Structure de la vue `ADMIN`
--
DROP TABLE IF EXISTS `ADMIN`;

CREATE ALGORITHM=UNDEFINED DEFINER=`e22100290sql`@`%` SQL SECURITY DEFINER VIEW `ADMIN`  AS SELECT `T_compte_cpt`.`email_cpt` AS `email_cpt`, `T_compte_cpt`.`mot_de_passe_cpt` AS `mot_de_passe_cpt`, `T_compte_cpt`.`etat` AS `etat` FROM (`T_compte_cpt` join `T_administration_adm` on(`T_compte_cpt`.`email_cpt` = `T_administration_adm`.`email_cpt`)) ;

-- --------------------------------------------------------

--
-- Structure de la vue `JURY`
--
DROP TABLE IF EXISTS `JURY`;

CREATE ALGORITHM=UNDEFINED DEFINER=`e22100290sql`@`%` SQL SECURITY DEFINER VIEW `JURY`  AS SELECT `T_compte_cpt`.`email_cpt` AS `email_cpt`, `T_compte_cpt`.`mot_de_passe_cpt` AS `mot_de_passe_cpt`, `T_compte_cpt`.`etat` AS `etat`, `T_jury_jur`.`discipline_jur` AS `discipline_jur`, `T_jury_jur`.`nom_jur` AS `nom_jur`, `T_jury_jur`.`prenom_jur` AS `prenom_jur`, `T_jury_jur`.`bio_jur` AS `bio_jur`, `T_jury_jur`.`url_jur` AS `url_jur` FROM (`T_compte_cpt` join `T_jury_jur` on(`T_compte_cpt`.`email_cpt` = `T_jury_jur`.`email_cpt`)) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `T_actualite_act`
--
ALTER TABLE `T_actualite_act`
  ADD PRIMARY KEY (`id_actualite_act`),
  ADD KEY `fk_T_actualité_act_T_concours_con1_idx` (`id_concours_con`),
  ADD KEY `fk_T_actualité_act_T_administration_adm1_idx` (`email_cpt`);

--
-- Index pour la table `T_administration_adm`
--
ALTER TABLE `T_administration_adm`
  ADD PRIMARY KEY (`email_cpt`),
  ADD KEY `fk_T_administration_adm_T_compte_cpt1_idx` (`email_cpt`);

--
-- Index pour la table `T_candidature_can`
--
ALTER TABLE `T_candidature_can`
  ADD PRIMARY KEY (`id_candidature_can`),
  ADD KEY `fk_T_canidature_can_T_concours_con1_idx` (`id_concours_con`),
  ADD KEY `fk_T_candidature_can_T_categorie_cat1_idx` (`id_categorie_cat`);

--
-- Index pour la table `T_candidature_jury_car`
--
ALTER TABLE `T_candidature_jury_car`
  ADD PRIMARY KEY (`id_candidature_can`,`email_cpt`),
  ADD KEY `fk_T_canidature_can_has_T_jury_jur_T_jury_jur1_idx` (`email_cpt`),
  ADD KEY `fk_T_canidature_can_has_T_jury_jur_T_canidature_can1_idx` (`id_candidature_can`);

--
-- Index pour la table `T_categorie_cat`
--
ALTER TABLE `T_categorie_cat`
  ADD PRIMARY KEY (`id_categorie_cat`);

--
-- Index pour la table `T_categorie_concours_coc`
--
ALTER TABLE `T_categorie_concours_coc`
  ADD PRIMARY KEY (`id_categorie_cat`,`id_concours_con`),
  ADD KEY `fk_T_categorie_cat_has_T_concours_con_T_concours_con1_idx` (`id_concours_con`),
  ADD KEY `fk_T_categorie_cat_has_T_concours_con_T_categorie_cat1_idx` (`id_categorie_cat`);

--
-- Index pour la table `T_compte_cpt`
--
ALTER TABLE `T_compte_cpt`
  ADD PRIMARY KEY (`email_cpt`);

--
-- Index pour la table `T_concours_con`
--
ALTER TABLE `T_concours_con`
  ADD PRIMARY KEY (`id_concours_con`),
  ADD KEY `fk_T_concours_con_T_administration_adm1_idx` (`email_cpt`);

--
-- Index pour la table `T_concours_jury_coj`
--
ALTER TABLE `T_concours_jury_coj`
  ADD PRIMARY KEY (`id_concours_con`,`email_cpt`),
  ADD KEY `fk_T_concours_con_has_T_jury_jur_T_jury_jur1_idx` (`email_cpt`),
  ADD KEY `fk_T_concours_con_has_T_jury_jur_T_concours_con1_idx` (`id_concours_con`);

--
-- Index pour la table `T_discution_fil`
--
ALTER TABLE `T_discution_fil`
  ADD PRIMARY KEY (`id_discution_fil`),
  ADD KEY `fk_T_fil_discution_fil_T_concours_con1_idx` (`id_concours_con`);

--
-- Index pour la table `T_document_doc`
--
ALTER TABLE `T_document_doc`
  ADD PRIMARY KEY (`id_document_doc`),
  ADD KEY `fk_T_document_doc_T_canidature_can1_idx` (`id_candidature_can`);

--
-- Index pour la table `T_jury_jur`
--
ALTER TABLE `T_jury_jur`
  ADD PRIMARY KEY (`email_cpt`),
  ADD KEY `fk_T_jury_jur_T_compte_cpt1_idx` (`email_cpt`);

--
-- Index pour la table `T_message_mes`
--
ALTER TABLE `T_message_mes`
  ADD PRIMARY KEY (`id_message_mes`),
  ADD KEY `fk_T_message_mes_T_fil_discution_fil1_idx` (`id_discution_fil`),
  ADD KEY `fk_T_message_mes_T_jury_jur1_idx` (`email_cpt`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `T_actualite_act`
--
ALTER TABLE `T_actualite_act`
  MODIFY `id_actualite_act` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `T_candidature_can`
--
ALTER TABLE `T_candidature_can`
  MODIFY `id_candidature_can` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `T_categorie_cat`
--
ALTER TABLE `T_categorie_cat`
  MODIFY `id_categorie_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `T_concours_con`
--
ALTER TABLE `T_concours_con`
  MODIFY `id_concours_con` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `T_discution_fil`
--
ALTER TABLE `T_discution_fil`
  MODIFY `id_discution_fil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `T_document_doc`
--
ALTER TABLE `T_document_doc`
  MODIFY `id_document_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `T_message_mes`
--
ALTER TABLE `T_message_mes`
  MODIFY `id_message_mes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `T_actualite_act`
--
ALTER TABLE `T_actualite_act`
  ADD CONSTRAINT `fk_T_actualité_act_T_administration_adm1` FOREIGN KEY (`email_cpt`) REFERENCES `T_administration_adm` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_actualité_act_T_concours_con1` FOREIGN KEY (`id_concours_con`) REFERENCES `T_concours_con` (`id_concours_con`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_administration_adm`
--
ALTER TABLE `T_administration_adm`
  ADD CONSTRAINT `fk_T_administration_adm_T_compte_cpt1` FOREIGN KEY (`email_cpt`) REFERENCES `T_compte_cpt` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_candidature_can`
--
ALTER TABLE `T_candidature_can`
  ADD CONSTRAINT `fk_T_candidature_can_T_categorie_cat1` FOREIGN KEY (`id_categorie_cat`) REFERENCES `T_categorie_cat` (`id_categorie_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_canidature_can_T_concours_con1` FOREIGN KEY (`id_concours_con`) REFERENCES `T_concours_con` (`id_concours_con`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_candidature_jury_car`
--
ALTER TABLE `T_candidature_jury_car`
  ADD CONSTRAINT `fk_T_canidature_can_has_T_jury_jur_T_canidature_can1` FOREIGN KEY (`id_candidature_can`) REFERENCES `T_candidature_can` (`id_candidature_can`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_canidature_can_has_T_jury_jur_T_jury_jur1` FOREIGN KEY (`email_cpt`) REFERENCES `T_jury_jur` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_categorie_concours_coc`
--
ALTER TABLE `T_categorie_concours_coc`
  ADD CONSTRAINT `fk_T_categorie_cat_has_T_concours_con_T_categorie_cat1` FOREIGN KEY (`id_categorie_cat`) REFERENCES `T_categorie_cat` (`id_categorie_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_categorie_cat_has_T_concours_con_T_concours_con1` FOREIGN KEY (`id_concours_con`) REFERENCES `T_concours_con` (`id_concours_con`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_concours_con`
--
ALTER TABLE `T_concours_con`
  ADD CONSTRAINT `fk_T_concours_con_T_administration_adm1` FOREIGN KEY (`email_cpt`) REFERENCES `T_administration_adm` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_concours_jury_coj`
--
ALTER TABLE `T_concours_jury_coj`
  ADD CONSTRAINT `fk_T_concours_con_has_T_jury_jur_T_concours_con1` FOREIGN KEY (`id_concours_con`) REFERENCES `T_concours_con` (`id_concours_con`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_concours_con_has_T_jury_jur_T_jury_jur1` FOREIGN KEY (`email_cpt`) REFERENCES `T_jury_jur` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_discution_fil`
--
ALTER TABLE `T_discution_fil`
  ADD CONSTRAINT `fk_T_fil_discution_fil_T_concours_con1` FOREIGN KEY (`id_concours_con`) REFERENCES `T_concours_con` (`id_concours_con`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_document_doc`
--
ALTER TABLE `T_document_doc`
  ADD CONSTRAINT `fk_T_document_doc_T_canidature_can1` FOREIGN KEY (`id_candidature_can`) REFERENCES `T_candidature_can` (`id_candidature_can`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_jury_jur`
--
ALTER TABLE `T_jury_jur`
  ADD CONSTRAINT `fk_T_jury_jur_T_compte_cpt1` FOREIGN KEY (`email_cpt`) REFERENCES `T_compte_cpt` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `T_message_mes`
--
ALTER TABLE `T_message_mes`
  ADD CONSTRAINT `fk_T_message_mes_T_fil_discution_fil1` FOREIGN KEY (`id_discution_fil`) REFERENCES `T_discution_fil` (`id_discution_fil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_message_mes_T_jury_jur1` FOREIGN KEY (`email_cpt`) REFERENCES `T_jury_jur` (`email_cpt`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
