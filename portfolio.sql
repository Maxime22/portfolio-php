-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 28 mai 2021 à 08:45
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `portfolio`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog_post`
--

DROP TABLE IF EXISTS `blog_post`;
CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `header_post` text NOT NULL,
  `content` text,
  `creation_date` datetime NOT NULL,
  `last_modification_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_blog_post_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `blog_post`
--

INSERT INTO `blog_post` (`id`, `title`, `header_post`, `content`, `creation_date`, `last_modification_date`, `user_id`) VALUES
(1, 'Festival du film d\'auteur', 'Le festival de plein air gratuit au parc Monceau', '<p>Jennifer Edwards &eacute;tait l&#39;organisatrice du festival des Films de Plein Air. Elle ambitionnait&nbsp;de s&eacute;lectionner et de projeter des films d&#39;auteur en plein air du 5 au 8 ao&ucirc;t au parc Monceau &agrave; Paris.</p>\r\n\r\n<div class=\"text-center\"><a href=\"https://s3-eu-west-1.amazonaws.com/sdz-upload/prod/upload/film_festival_parc_shutterstock_220181725.jpg\"><img alt=\"Les projections de films en plein air reviennent Ã  la mode !\" class=\"m-auto\" data-claire-element-id=\"5890371\" id=\"r-3674746\" src=\"https://s3-eu-west-1.amazonaws.com/sdz-upload/prod/upload/film_festival_parc_shutterstock_220181725.jpg\" style=\"margin:auto;\" /></a></div>\r\n\r\n<p>Les projections de films en plein air reviennent &agrave; la mode !<br />\r\nSon association venait juste d&#39;&ecirc;tre cr&eacute;&eacute;e et elle disposait&nbsp;d&#39;un budget limit&eacute;. Elle avait besoin de communiquer en ligne sur son festival, d&#39;annoncer les films projet&eacute;s et de recueillir les r&eacute;servations.</p>\r\n', '2021-05-14 14:47:24', '2021-05-24 07:41:03', 1),
(31, 'BioLibre', 'J\'aurais voulu le savoir avant', '<p>BioLibre collectionne des p&eacute;pites et les partage. On se simplifie la vie tout en partageant des infos positives et utiles. D&eacute;couvrez des applications, des articles, des tutoriels, des vid&eacute;os et tout un tas de renseignements sur diff&eacute;rentes th&eacute;matiques (culture, musique, sant&eacute;, sciences...). La plupart du temps, les articles sont assez courts. Consid&eacute;rez BioLibre comme un rassemblement d&#39;initiatives, d&#39;astuces et/ou d&#39;&eacute;l&eacute;ments culturels riches et positifs. Tout cela dans le but d&#39;apprendre, de s&#39;am&eacute;liorer (et peut &ecirc;tre se rapprocher de son Ikigai) ou bien juste de passer un bon moment. Si on n&#39;essaie pas, on ne sait pas !</p>\r\n\r\n<p>Lien vers BioLibre :&nbsp;<a href=\"https://biolibre.fr/\">https://biolibre.fr/</a></p>\r\n', '2021-05-19 08:08:03', '2021-05-23 07:37:19', 1),
(33, 'Anne Lods Photos', 'Site d\'une photographe de mariage, portraitiste', '<p>Site web r&eacute;alis&eacute; en Angular pour la partie frontend et&nbsp;en Node.js/Express.js. pour la partie backend. Le site est un mini CMS classique permettant, avec un admin, de modifier ce que le client souhaite (r&eacute;organisation de cat&eacute;gories et de photos notamment). La base de donn&eacute;es est en Mongodb Atlas. Le serveur est un&nbsp;VPS OVH sous Nginx.</p>\r\n', '2021-05-23 10:51:21', '2021-05-24 07:06:45', 1);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `content` text,
  `creation_date` datetime NOT NULL,
  `publication_validated` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_blog_post` (`blog_post_id`),
  KEY `fk_comment_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `blog_post_id`, `user_id`, `title`, `content`, `creation_date`, `publication_validated`) VALUES
(1, 1, 1, 'Premier commentaire', 'vsdvdvsfsdfsdfsdfs', '2021-05-18 12:47:03', 1),
(3, 1, 1, 'Bonjouraaaaaaaaa', 'Lalalalalaalalala', '2021-05-18 16:16:15', 1),
(12, 1, 1, 'aaaaaaaaaaaaaabbbbbbbbbbb', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbccccccccccccc', '2021-05-18 16:28:05', 1),
(18, 1, 1, 'cezcezcezczec', 'ezczeczeczeczeczeczeczecez', '2021-05-24 07:13:48', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  `roles` json DEFAULT NULL,
  `username` text NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  `confirmation_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `mail`, `password`, `creation_date`, `roles`, `username`, `is_validated`, `confirmation_token`) VALUES
(1, 'sabouretmaxime@gmail.com', '$2y$12$nQca/3GIDzN6Negh.iNwCuktGfGkHkIMKwOE4TqcBi9y37Zo3kHmi', '2021-05-17 18:41:32', '[\"admin\"]', 'Maxou', 1, NULL),
(3, 'annelods@gmail.com', '$2y$12$UWl228mD/03vPDnphOhaL.2gOgqy/8HbEDC.WzuLT1/DMyNfwQrEK', '2021-05-18 09:40:36', '[\"user\", \"admin\"]', 'Anne', 1, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blog_post`
--
ALTER TABLE `blog_post`
  ADD CONSTRAINT `fk_blog_post_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_blog_post` FOREIGN KEY (`blog_post_id`) REFERENCES `blog_post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
