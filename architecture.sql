-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 15 sep. 2022 à 17:04
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `architecture`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `miniplan_id` int(11) NOT NULL,
  `panier_id` int(11) DEFAULT NULL,
  `etat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `payement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `txreference` int(11) DEFAULT NULL,
  `prix` double NOT NULL,
  `retrait` tinyint(1) DEFAULT NULL,
  `demande` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statusmail` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `achat`
--

INSERT INTO `achat` (`id`, `plan_id`, `users_id`, `miniplan_id`, `panier_id`, `etat`, `created_at`, `payement`, `txreference`, `prix`, `retrait`, `demande`, `statusmail`) VALUES
(1, 4, 7, 23, NULL, 'Confirmer', '2022-09-15 15:00:44', 'PAYGATE', 913109, 1, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

CREATE TABLE `achats` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adress`
--

CREATE TABLE `adress` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `architecte`
--

CREATE TABLE `architecte` (
  `id` int(11) NOT NULL,
  `num_banque` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consulter`
--

CREATE TABLE `consulter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plans_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `consulter`
--

INSERT INTO `consulter` (`id`, `user_id`, `plans_id`, `created_at`) VALUES
(1, NULL, 2, '2022-09-15 14:48:26'),
(2, NULL, 1, '2022-09-15 14:49:20'),
(3, NULL, 4, '2022-09-15 14:51:09');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fichiers`
--

CREATE TABLE `fichiers` (
  `id` int(11) NOT NULL,
  `miniplans_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fichiers`
--

INSERT INTO `fichiers` (`id`, `miniplans_id`, `nom`) VALUES
(1, 1, 'FONDATION.pdf-632335fb9bd1c.pdf'),
(2, 2, 'EDICULE.pdf-63233622df2c1.pdf'),
(3, 3, 'COUPES.pdf-63233638bd98e.pdf'),
(4, 4, 'R+1.pdf-6323364de394c.pdf'),
(5, 5, 'Villa_Duplex_plan_complet.pdf-63233742acecf.pdf'),
(6, 6, 'FONDATION.pdf-632337a64a364.pdf'),
(7, 7, 'R+1.pdf-632337bbf332b.pdf'),
(8, 8, 'DEVIS R+1.pdf-6323381f915aa.pdf'),
(9, 9, 'DEVIS RDC.pdf-632338402a0c3.pdf'),
(10, 10, 'façade gauche et droite.pdf-6323386555bda.pdf'),
(11, 11, 'façade principale et arrière.pdf-6323388d8d581.pdf'),
(12, 12, 'FONDATION.pdf-6323392693f17.pdf'),
(13, 13, 'R+1.pdf-6323393ca2995.pdf'),
(14, 14, 'RDC Ens.pdf-6323394fe01c7.pdf'),
(15, 15, 'COUPES.pdf-6323397948300.pdf'),
(16, 16, 'EDICULE.pdf-6323399798531.pdf'),
(17, 17, 'FONDATION.pdf-63233a56975ac.pdf'),
(18, 18, 'COUPES.pdf-63233a733358b.pdf'),
(19, 19, 'DEVIS CHAMBRE NON DALLE.pdf-63233a8b1be21.pdf'),
(20, 20, 'EDICULE.pdf-63233aa3216e8.pdf'),
(21, 21, 'DEVIS CLOTURE.pdf-63233ac837f24.pdf'),
(22, 22, 'DEVIS RECAP.pdf-63233ae950a44.pdf'),
(23, 23, 'COUPES.pdf-63233afa0c83a.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `forme`
--

CREATE TABLE `forme` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forme`
--

INSERT INTO `forme` (`id`, `libelle`) VALUES
(5, '4*4'),
(3, 'Forme en L'),
(1, 'Forme en U'),
(2, 'Quelconque'),
(4, 'Rectangulaire');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `plans_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `plans_id`, `nom`) VALUES
(1, 1, '1663251813-63233565994ec.jpeg'),
(2, 1, '1663251813-63233565a098b.jpeg'),
(3, 1, '1663251813-63233565a12ae.jpeg'),
(4, 2, '1663252361-6323378937f8a.jpeg'),
(5, 2, '1663252361-6323378939ff6.jpeg'),
(6, 2, '1663252361-632337893a64b.jpeg'),
(7, 2, '1663252361-632337893ac7b.jpeg'),
(8, 2, '1663252361-632337893b292.jpeg'),
(9, 3, '1663252737-63233901a2339.jpeg'),
(10, 3, '1663252737-63233901a4269.jpeg'),
(11, 3, '1663252737-63233901a4b32.jpeg'),
(12, 4, '1663253043-63233a3348975.jpeg'),
(13, 4, '1663253043-63233a334a49d.jpeg'),
(14, 4, '1663253043-63233a334ce19.jpeg'),
(15, 4, '1663253043-63233a334d4e6.jpeg'),
(16, 4, '1663253043-63233a334db61.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `contenu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `recipient_id`, `contenu`, `titre`, `message`, `created_at`, `is_read`) VALUES
(1, 7, 1, NULL, 'Construction de la maison', 'Je voudrais que l\'architecte qui s\'est chargé de mettre en place ce plan me construise ma maison.\r\n\r\nCordialement\r\nMr AMEWANU', '2022-09-15 15:02:46', 0);

-- --------------------------------------------------------

--
-- Structure de la table `miniplans`
--

CREATE TABLE `miniplans` (
  `id` int(11) NOT NULL,
  `plans_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `tx_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `miniplans`
--

INSERT INTO `miniplans` (`id`, `plans_id`, `user_id`, `titre`, `prix`, `tx_reference`, `vente`) VALUES
(1, 1, 1, 'Fondation', 10000, NULL, NULL),
(2, 1, 1, 'Edicule', 20000, NULL, NULL),
(3, 1, 1, 'coupe', 25000, NULL, NULL),
(4, 1, 1, 'R+1', 30000, NULL, NULL),
(5, 1, 1, 'ALBA PLAN COMPLET', 200000, NULL, NULL),
(6, 2, 1, 'Fondation', 10000, NULL, NULL),
(7, 2, 1, 'R+1', 20000, NULL, NULL),
(8, 2, 1, 'DEVIS R+1', 20000, NULL, NULL),
(9, 2, 1, 'RDC', 35000, NULL, NULL),
(10, 2, 1, 'FACADE GAUCHE ET DROITE', 25000, NULL, NULL),
(11, 2, 1, 'FACADE ARRIERE ET PRINCIPALE', 45000, NULL, NULL),
(12, 3, 1, 'FONDATION', 25000, NULL, NULL),
(13, 3, 1, 'PLAN R+1', 10000, NULL, NULL),
(14, 3, 1, 'PLAN RDC ENS', 25000, NULL, NULL),
(15, 3, 1, 'COUPES', 40000, NULL, NULL),
(16, 3, 1, 'EDICULE', 50000, NULL, NULL),
(17, 4, 1, 'FONDATION', 25000, NULL, NULL),
(18, 4, 1, 'COUPES', 20000, NULL, NULL),
(19, 4, 1, 'DEVIS CHAMBRE', 10000, NULL, NULL),
(20, 4, 1, 'EDICULE', 25000, NULL, NULL),
(21, 4, 1, 'DEVIS CLOTURE', 30000, NULL, NULL),
(22, 4, 1, 'DEVIS RECAP', 25000, NULL, NULL),
(23, 4, 1, 'PLAN TEST', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` double NOT NULL,
  `code` int(11) NOT NULL,
  `txreference` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `payement` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `statusmail` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `alpha2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_en_gb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_fr_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `forme_id` int(11) NOT NULL,
  `superficie_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `nbre_piece` int(11) NOT NULL,
  `nbre_etage` int(11) NOT NULL,
  `garage` int(11) DEFAULT NULL,
  `vente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plans`
--

INSERT INTO `plans` (`id`, `type_id`, `user_id`, `forme_id`, `superficie_id`, `titre`, `description`, `nbre_piece`, `nbre_etage`, `garage`, `vente`) VALUES
(1, 1, 1, 3, 6, 'ALBA', 'Alba est une maison moderne.\r\n\r\nSa forme en « L » bien adaptée à un mode de vie contemporain, favorise l\'intimité de ses habitants.\r\n\r\nLe salon-séjour particulièrement lumineux propose de grandes baies en double exposition.\r\n\r\nL\'espace nuit est pensé pour faciliter le confort de chacun.\r\n\r\nAlba est une maison belle, confortable, agréable à vivre', 5, 1, 2, NULL),
(2, 1, 1, 2, 1, 'MAISON DUPLEX', 'MAISON DUPLEX', 5, 2, 2, NULL),
(3, 1, 1, 4, 4, 'CEDRAT', 'CEDRAT est une maison spacieuse, fonctionnelle et lumineuse.\r\n\r\nLe modèle de maison est déclinable de 80 à 115 m².', 4, 1, 1, NULL),
(4, 1, 1, 5, 2, 'JONQUILLE', 'JONQUILLE EST UNE MAISON DE 113 m2 AVEC 5 PIECES ET 4 CHAMBRES SUR UNE SUPERFICIE DE CONSTRUCTION REALISABLE DE 300 m2', 5, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `superficie`
--

CREATE TABLE `superficie` (
  `id` int(11) NOT NULL,
  `nombredelots` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `superficie`
--

INSERT INTO `superficie` (`id`, `nombredelots`) VALUES
(2, '1/2lot-->300m2'),
(4, '123 m²'),
(1, '1lot-->600m2'),
(5, '200m2'),
(3, '2lots-->1200m2'),
(6, '400m2');

-- --------------------------------------------------------

--
-- Structure de la table `tx_reference`
--

CREATE TABLE `tx_reference` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id`, `libelle`) VALUES
(2, 'Appartements'),
(1, 'Maisons');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pays_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenoms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_token` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banque` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` int(11) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `roles` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pays_id`, `email`, `password`, `pseudo`, `prenoms`, `activation_token`, `adresse`, `banque`, `tel`, `is_verified`, `roles`) VALUES
(1, NULL, 'marchedesplans@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SldNWjh6T1FYVWVxYWxPUw$Zms35Fz3jN5bfO8c7rXldGkeLf6+2yul4r35tuN9jgk', 'Admin', 'Marchédesplans', NULL, NULL, NULL, NULL, 1, 'R0'),
(2, NULL, 'yajadjanohoun@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UWp0ZWJDQ1Y5c0U2UnguZA$nKM11D5DK2/Ube4u27eSg/doE60tJoly/kz/f1U9gws', 'ADJANOHOUN', 'Yao Jean-Eudes', NULL, NULL, NULL, NULL, NULL, 'R2'),
(3, NULL, 'jeaneudesadjanohoun@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WGtIanhTWTNBNDVuYlVncg$3xNasbuwQvplIsWasrwBuNz6+KzpllEN4Ib/Mcf+Re4', 'ADJANOHUN', 'Akou Sandrine', NULL, NULL, NULL, NULL, NULL, 'R2'),
(4, NULL, 'hubertine@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bkZwWkNHR2JmdzVJQmRocg$sMhMEcsxNSTuybIfrwVa6zJ5PWwxIWBpR39D9kqpk9s', 'BUAGBE', 'Akouvi Hubertine', NULL, NULL, NULL, NULL, NULL, 'R2'),
(5, NULL, 'mohamedcisse@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RUVHdzVsU1VNL0kyOW5RcA$4ztAol/OpcMJ6kAvPkfL6boTklaVBa51IGcC/jMt10o', 'CISSE', 'Mohamed', NULL, NULL, NULL, NULL, NULL, 'R3'),
(6, NULL, 'fidelia@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$LllGTXlDSnhhVWdoSG13Wg$snURdq1Zu+laEeA+9797pULdWuRfZwXbMJy3hDmxuEU', 'BALABIA', 'Fidélia', NULL, NULL, NULL, NULL, NULL, 'R3'),
(7, NULL, 'jean108adjanohoun@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dXVUMkFtQk1nL1Z1OXFwZg$yi6mJjyvnan8RdKXDxkdp7WLh2OC1gMh1g2w7XLO9wU', 'AMEWUNA', 'Kossi Franc', NULL, NULL, NULL, NULL, 1, 'R3');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registered_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `withdrawal`
--

CREATE TABLE `withdrawal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prix` double NOT NULL,
  `commission` double NOT NULL,
  `reste` double NOT NULL,
  `tel` int(11) NOT NULL,
  `etat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_26A98456E899029B` (`plan_id`),
  ADD KEY `IDX_26A9845667B3B43D` (`users_id`),
  ADD KEY `IDX_26A98456F3C5792A` (`miniplan_id`),
  ADD KEY `IDX_26A98456F77D927C` (`panier_id`);

--
-- Index pour la table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `adress`
--
ALTER TABLE `adress`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `architecte`
--
ALTER TABLE `architecte`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `consulter`
--
ALTER TABLE `consulter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_69A83B75A76ED395` (`user_id`),
  ADD KEY `IDX_69A83B7580446EEB` (`plans_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `fichiers`
--
ALTER TABLE `fichiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_969DB4AB3C39B18A` (`miniplans_id`);

--
-- Index pour la table `forme`
--
ALTER TABLE `forme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9EBAEA6A4D60759` (`libelle`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E01FBE6A80446EEB` (`plans_id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307FF624B39D` (`sender_id`),
  ADD KEY `IDX_B6BD307FE92F8F78` (`recipient_id`);

--
-- Index pour la table `miniplans`
--
ALTER TABLE `miniplans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_83B6CF2B80446EEB` (`plans_id`),
  ADD KEY `IDX_83B6CF2BA76ED395` (`user_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_24CC0DF2A76ED395` (`user_id`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_356798D1C54C8C93` (`type_id`),
  ADD KEY `IDX_356798D1A76ED395` (`user_id`),
  ADD KEY `IDX_356798D1BCE84E7C` (`forme_id`),
  ADD KEY `IDX_356798D1240A0569` (`superficie_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `superficie`
--
ALTER TABLE `superficie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E4DE844B6A7E2569` (`nombredelots`);

--
-- Index pour la table `tx_reference`
--
ALTER TABLE `tx_reference`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8CDE5729A4D60759` (`libelle`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  ADD KEY `IDX_1483A5E9A6E44244` (`pays_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_1D1C63B386CC499D` (`pseudo`);

--
-- Index pour la table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6D2D3B45A76ED395` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `achats`
--
ALTER TABLE `achats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `adress`
--
ALTER TABLE `adress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `architecte`
--
ALTER TABLE `architecte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consulter`
--
ALTER TABLE `consulter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `forme`
--
ALTER TABLE `forme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `miniplans`
--
ALTER TABLE `miniplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `superficie`
--
ALTER TABLE `superficie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tx_reference`
--
ALTER TABLE `tx_reference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `withdrawal`
--
ALTER TABLE `withdrawal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `FK_26A9845667B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_26A98456E899029B` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `FK_26A98456F3C5792A` FOREIGN KEY (`miniplan_id`) REFERENCES `miniplans` (`id`),
  ADD CONSTRAINT `FK_26A98456F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`);

--
-- Contraintes pour la table `consulter`
--
ALTER TABLE `consulter`
  ADD CONSTRAINT `FK_69A83B7580446EEB` FOREIGN KEY (`plans_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `FK_69A83B75A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `fichiers`
--
ALTER TABLE `fichiers`
  ADD CONSTRAINT `FK_969DB4AB3C39B18A` FOREIGN KEY (`miniplans_id`) REFERENCES `miniplans` (`id`);

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `FK_E01FBE6A80446EEB` FOREIGN KEY (`plans_id`) REFERENCES `plans` (`id`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FE92F8F78` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `miniplans`
--
ALTER TABLE `miniplans`
  ADD CONSTRAINT `FK_83B6CF2B80446EEB` FOREIGN KEY (`plans_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `FK_83B6CF2BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `FK_356798D1240A0569` FOREIGN KEY (`superficie_id`) REFERENCES `superficie` (`id`),
  ADD CONSTRAINT `FK_356798D1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_356798D1BCE84E7C` FOREIGN KEY (`forme_id`) REFERENCES `forme` (`id`),
  ADD CONSTRAINT `FK_356798D1C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9A6E44244` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`);

--
-- Contraintes pour la table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD CONSTRAINT `FK_6D2D3B45A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
