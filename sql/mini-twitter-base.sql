-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 19 août 2025 à 21:20
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mini-twitter`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `date_time` datetime NOT NULL,
  `tweet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_signaled` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250723145450', '2025-07-23 15:09:32', 30),
('DoctrineMigrations\\Version20250724075645', '2025-07-24 08:28:28', 36),
('DoctrineMigrations\\Version20250724080453', '2025-07-24 08:28:28', 43),
('DoctrineMigrations\\Version20250724081310', '2025-07-24 08:28:28', 63),
('DoctrineMigrations\\Version20250724081911', '2025-07-24 08:28:28', 61),
('DoctrineMigrations\\Version20250724094012', '2025-07-24 12:06:07', 18),
('DoctrineMigrations\\Version20250724121517', '2025-07-24 12:24:23', 27),
('DoctrineMigrations\\Version20250728135025', '2025-07-28 14:48:56', 18),
('DoctrineMigrations\\Version20250728145348', '2025-07-28 14:53:57', 17),
('DoctrineMigrations\\Version20250729081926', '2025-07-29 08:19:57', 109),
('DoctrineMigrations\\Version20250730073238', '2025-07-30 08:00:35', 64),
('DoctrineMigrations\\Version20250730080102', '2025-07-30 10:17:37', 49),
('DoctrineMigrations\\Version20250730080540', '2025-07-30 10:28:32', 52),
('DoctrineMigrations\\Version20250730081027', '2025-07-30 10:17:37', 14);

-- --------------------------------------------------------

--
-- Structure de la table `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `url_media` varchar(255) NOT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `url_media`, `tweet_id`, `comment_id`) VALUES
(22, '/uploads/images/golden-688b6340f1b3f.png', NULL, 34),
(23, '/uploads/images/berger-688b6392ab03f.png', 19, NULL),
(25, '/uploads/images/golden-688bbf895b278.png', NULL, 37);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tweet`
--

CREATE TABLE `tweet` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `creation_time` datetime NOT NULL,
  `id_user_id` int(11) NOT NULL,
  `is_signaled` tinyint(1) NOT NULL,
  `original_tweet_id` int(11) DEFAULT NULL,
  `retweet_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) NOT NULL,
  `age` double NOT NULL,
  `is_banned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `firstname`, `is_admin`, `is_active`, `photo`, `nickname`, `age`, `is_banned`) VALUES
(14, 'user@test.com', '[]', '$2y$13$dnJ7MSe5TIOKnwSEQgyYgufMQcWtrhj1En46hVIwur0W2y1zyg6bS', 'User', 'Compte', NULL, 1, 'golden-688a31b31a976.png', 'User', 36, 0),
(15, 'admin@test.com', '[\"ROLE_ADMIN\"]', '$2y$13$n6GtzfpLy/1ogJ6kIc89X.L6eF/WiTsJmuAx6hIFb.jJrKe08PC62', 'Admin', 'Compte', NULL, 1, 'bouvier-688a31d16c9a0.png', 'Admin', 40, 0),
(16, 'inactif@test.com', '[]', '$2y$13$0R6u2NYezvxgQsWLVXSKjO1xA3ms0ZDsC3J3lATbFBYR24/kWc7pu', 'Utilisateur désactivé', 'Inactif', NULL, 1, 'berger-688b605fac4cf.png', 'Inactif', 18, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C1041E39B` (`tweet_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AC6340B3A76ED395` (`user_id`),
  ADD KEY `IDX_AC6340B31041E39B` (`tweet_id`),
  ADD KEY `IDX_AC6340B3F8697D13` (`comment_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6A2CA10C1041E39B` (`tweet_id`),
  ADD KEY `IDX_6A2CA10CF8697D13` (`comment_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `tweet`
--
ALTER TABLE `tweet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3D660A3B79F37AE5` (`id_user_id`),
  ADD KEY `IDX_3D660A3B331E048A` (`original_tweet_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tweet`
--
ALTER TABLE `tweet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C1041E39B` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `FK_AC6340B31041E39B` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`),
  ADD CONSTRAINT `FK_AC6340B3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_AC6340B3F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `FK_6A2CA10C1041E39B` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6A2CA10CF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `tweet`
--
ALTER TABLE `tweet`
  ADD CONSTRAINT `FK_3D660A3B331E048A` FOREIGN KEY (`original_tweet_id`) REFERENCES `tweet` (`id`),
  ADD CONSTRAINT `FK_3D660A3B79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
