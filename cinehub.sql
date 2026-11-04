-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Nov-2026 às 12:59
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cinehub`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin_activity_logs`
--

CREATE TABLE `admin_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `route_name` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `status_code` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `admin_activity_logs`
--

INSERT INTO `admin_activity_logs` (`id`, `user_id`, `method`, `route_name`, `path`, `ip_address`, `user_agent`, `status_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'POST', 'admin.categories.store', '/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(2, 1, 'POST', 'admin.categories.store', '/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(3, 1, 'POST', 'admin.categories.store', '/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(4, 1, 'POST', 'admin.categories.store', '/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:07:48', '2026-11-03 23:07:48'),
(5, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:13:08', '2026-11-03 23:13:08'),
(6, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:13:23', '2026-11-03 23:13:23'),
(7, 1, 'PUT', 'admin.genres.update', '/admin/genres/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:13:45', '2026-11-03 23:13:45'),
(8, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(9, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(10, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(11, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(12, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(13, 1, 'POST', 'admin.genres.store', '/admin/genres', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(14, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(15, 1, 'PUT', 'admin.posts.update', '/admin/posts/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:26:34', '2026-11-03 23:26:34');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ad_slots`
--

CREATE TABLE `ad_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'reserved',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_category_blog_genre`
--

CREATE TABLE `blog_category_blog_genre` (
  `blog_category_id` bigint(20) UNSIGNED NOT NULL,
  `blog_genre_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_category_localizations`
--

CREATE TABLE `blog_category_localizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_category_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) NOT NULL,
  `name` varchar(120) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_genres`
--

CREATE TABLE `blog_genres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_genre_localizations`
--

CREATE TABLE `blog_genre_localizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_genre_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(12) NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_genre_post`
--

CREATE TABLE `blog_genre_post` (
  `blog_genre_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:3;', 1793737693),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1793737693;', 1793737693),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1793736362),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1793736362;', 1793736362),
('laravel-cache-cinehub:home:en-gb:popular', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793736738),
('laravel-cache-cinehub:home:en-gb:trending', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793736738),
('laravel-cache-cinehub:home:en-us:popular', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793737054),
('laravel-cache-cinehub:home:en-us:trending', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793737054);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `icon`, `description`, `seo_title`, `seo_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'trivia', 'Trivia', NULL, 'Description', 'Trivia', 'Description', 1, 0, '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(2, 'movies', 'Movies', NULL, 'Description', 'Movies', 'Description', 1, 0, '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(3, 'tv-shows', 'TV Shows', NULL, 'Description', 'TV Shows', 'Description', 1, 0, '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(4, 'games', 'Games', NULL, 'Description', 'Games', 'Description', 1, 0, '2026-11-03 23:07:48', '2026-11-03 23:07:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_genre`
--

CREATE TABLE `category_genre` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `genre_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_genre`
--

INSERT INTO `category_genre` (`category_id`, `genre_id`, `created_at`, `updated_at`) VALUES
(4, 1, '2026-11-03 23:13:23', '2026-11-03 23:13:23'),
(4, 2, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(4, 3, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(4, 4, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(4, 5, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(4, 6, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(4, 7, '2026-11-03 23:15:27', '2026-11-03 23:15:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_localizations`
--

CREATE TABLE `category_localizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_localizations`
--

INSERT INTO `category_localizations` (`id`, `category_id`, `locale`, `is_active`, `slug`, `name`, `description`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'en-us', 1, 'trivia', 'Trivia', 'Description', 'Trivia', 'Description', '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(2, 1, 'en-gb', 1, 'trivia', 'Trivia', 'Description', 'Trivia', 'Description', '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(3, 1, 'en-au', 1, 'trivia', 'Trivia', 'Description', 'Trivia', 'Description', '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(4, 1, 'en-ca', 1, 'trivia', 'Trivia', 'Description', 'Trivia', 'Description', '2026-11-03 23:05:49', '2026-11-03 23:05:49'),
(5, 2, 'en-us', 1, 'movies', 'Movies', 'Description', 'Movies', 'Description', '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(6, 2, 'en-gb', 1, 'films', 'Films', 'Description', 'Films', 'Description', '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(7, 2, 'en-au', 1, 'movies', 'Movies', 'Description', 'Movies', 'Description', '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(8, 2, 'en-ca', 1, 'movies', 'Movies', 'Description', 'Movies', 'Description', '2026-11-03 23:06:38', '2026-11-03 23:06:38'),
(9, 3, 'en-us', 1, 'tv-shows', 'TV Shows', 'Description', 'TV Shows', 'Description', '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(10, 3, 'en-gb', 1, 'tv-series', 'TV Series', 'Description', 'TV Series', 'Description', '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(11, 3, 'en-au', 1, 'tv-shows', 'TV Shows', 'Description', 'TV Shows', 'Description', '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(12, 3, 'en-ca', 1, 'tv-shows', 'TV Shows', 'Description', 'TV Shows', 'Description', '2026-11-03 23:07:13', '2026-11-03 23:07:13'),
(13, 4, 'en-us', 1, 'games', 'Games', 'Description', 'Games', 'Description', '2026-11-03 23:07:48', '2026-11-03 23:07:48'),
(14, 4, 'en-gb', 1, 'games', 'Games', 'Description', 'Games', 'Description', '2026-11-03 23:07:48', '2026-11-03 23:07:48'),
(15, 4, 'en-au', 1, 'games', 'Games', 'Description', 'Games', 'Description', '2026-11-03 23:07:48', '2026-11-03 23:07:48'),
(16, 4, 'en-ca', 1, 'games', 'Games', 'Description', 'Games', 'Description', '2026-11-03 23:07:48', '2026-11-03 23:07:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_post`
--

CREATE TABLE `category_post` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_post`
--

INSERT INTO `category_post` (`category_id`, `post_id`, `created_at`, `updated_at`) VALUES
(4, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comment_reactions`
--

CREATE TABLE `comment_reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `genres`
--

CREATE TABLE `genres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `genres`
--

INSERT INTO `genres` (`id`, `name`, `slug`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Adventure', 'adventure', 1, 0, '2026-11-03 23:13:23', '2026-11-03 23:13:45'),
(2, 'Action', 'action', 1, 0, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(3, 'Action RPG', 'action-rpg', 1, 0, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(4, 'Open World', 'open-world', 1, 0, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(5, 'Stealth', 'stealth', 1, 0, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(6, 'Fantasy', 'fantasy', 1, 0, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(7, 'Hack and Slash', 'hack-and-slash', 1, 0, '2026-11-03 23:15:27', '2026-11-03 23:15:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `genre_localizations`
--

CREATE TABLE `genre_localizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `genre_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `genre_localizations`
--

INSERT INTO `genre_localizations` (`id`, `genre_id`, `locale`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'en-us', 'Adventure', 'adventure', 1, '2026-11-03 23:13:23', '2026-11-03 23:13:45'),
(2, 1, 'en-gb', 'Adventure', 'adventure', 1, '2026-11-03 23:13:23', '2026-11-03 23:13:45'),
(3, 1, 'en-au', 'Adventure', 'adventure', 1, '2026-11-03 23:13:23', '2026-11-03 23:13:45'),
(4, 1, 'en-ca', 'Adventure', 'adventure', 1, '2026-11-03 23:13:23', '2026-11-03 23:13:45'),
(5, 1, 'ja-jp', 'Action', 'action', 1, '2026-11-03 23:13:23', '2026-11-03 23:13:23'),
(6, 2, 'en-us', 'Action', 'action', 1, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(7, 2, 'en-gb', 'Action', 'action', 1, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(8, 2, 'en-au', 'Action', 'action', 1, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(9, 2, 'en-ca', 'Action', 'action', 1, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(10, 3, 'en-us', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(11, 3, 'en-gb', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(12, 3, 'en-au', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(13, 3, 'en-ca', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(14, 4, 'en-us', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(15, 4, 'en-gb', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(16, 4, 'en-au', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(17, 4, 'en-ca', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(18, 5, 'en-us', 'Stealth', 'stealth', 1, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(19, 5, 'en-gb', 'Stealth', 'stealth', 1, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(20, 5, 'en-au', 'Stealth', 'stealth', 1, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(21, 5, 'en-ca', 'Stealth', 'stealth', 1, '2026-11-03 23:14:58', '2026-11-03 23:14:58'),
(22, 6, 'en-us', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(23, 6, 'en-gb', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(24, 6, 'en-au', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(25, 6, 'en-ca', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(26, 7, 'en-us', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(27, 7, 'en-gb', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(28, 7, 'en-au', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(29, 7, 'en-ca', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-03 23:15:27', '2026-11-03 23:15:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `genre_post`
--

CREATE TABLE `genre_post` (
  `genre_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `genre_post`
--

INSERT INTO `genre_post` (`genre_id`, `post_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(2, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(3, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(4, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(5, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(6, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(7, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'trailer',
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `source_url` text DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `media`
--

INSERT INTO `media` (`id`, `post_id`, `locale`, `type`, `provider`, `provider_id`, `thumbnail_path`, `alt_text`, `caption`, `source_url`, `duration_seconds`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'en-us', 'trailer', 'youtube', 'i-OUZ-0eI50', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=i-OUZ-0eI50', NULL, 1, 0, '2026-11-03 23:26:29', '2026-11-03 23:26:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_28_000100_add_is_admin_to_users_table', 1),
(5, '2026_10_28_000200_create_categories_table', 1),
(6, '2026_10_28_000300_create_posts_table', 1),
(7, '2026_10_28_000400_create_post_localizations_table', 1),
(8, '2026_10_28_000500_create_category_post_table', 1),
(9, '2026_10_28_000600_create_media_table', 1),
(10, '2026_10_28_000700_create_comments_table', 1),
(11, '2026_10_28_000800_create_reactions_table', 1),
(12, '2026_10_28_000900_create_post_views_table', 1),
(13, '2026_10_28_001000_create_suggestions_table', 1),
(14, '2026_10_28_001100_add_admin_fields_to_content_tables', 1),
(15, '2026_10_28_001200_add_interaction_indexes', 1),
(16, '2026_10_28_001300_create_contact_messages_table', 1),
(17, '2026_10_28_001400_create_site_settings_table', 1),
(18, '2026_10_28_001500_create_admin_activity_logs_table', 1),
(19, '2026_10_28_001600_create_ad_slots_table', 1),
(20, '2026_10_28_001700_simplify_post_publishing', 1),
(21, '2026_10_28_001800_add_country_specific_content_data', 1),
(22, '2026_10_29_000100_add_spoilers_and_remove_generic_embed_url', 1),
(23, '2026_10_29_170900_create_genres_tables', 1),
(24, '2026_10_30_000001_add_publication_type_to_posts_table', 1),
(25, '2026_10_30_000002_add_article_body_to_post_localizations_table', 1),
(26, '2026_10_30_000003_create_blog_taxonomy_tables', 1),
(27, '2026_10_30_130000_create_genre_localizations_table', 1),
(28, '2026_10_30_132500_create_blog_category_localizations_table', 1),
(29, '2026_11_03_000001_add_cinehub_member_features', 1),
(30, '2026_11_03_000002_create_comment_reactions', 1),
(31, '2026_11_03_000003_create_moderation_terms', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `moderation_terms`
--

CREATE TABLE `moderation_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phrase` varchar(150) NOT NULL,
  `locale` varchar(8) NOT NULL DEFAULT '*',
  `classification` varchar(12) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `publication_type` varchar(20) NOT NULL DEFAULT 'standard',
  `blog_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `status`, `publication_type`, `blog_category_id`, `is_featured`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'published', 'standard', NULL, 0, '2026-11-03 23:26:00', '2026-11-03 23:26:29', '2026-11-03 23:26:34', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `post_localizations`
--

CREATE TABLE `post_localizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `synopsis` longtext DEFAULT NULL,
  `review` longtext DEFAULT NULL,
  `article_body` longtext DEFAULT NULL,
  `spoilers` longtext DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` varchar(320) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `post_localizations`
--

INSERT INTO `post_localizations` (`id`, `post_id`, `locale`, `is_active`, `is_featured`, `slug`, `title`, `excerpt`, `synopsis`, `review`, `article_body`, `spoilers`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'en-us', 1, 0, 'middle-earth-shadow-of-mordor-where-enemies-never-forget', 'Middle-earth: Shadow of Mordor — Where Enemies Never Forget', '<p>In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behind. In Mordor, even death cannot erase a rivalry.</p>', '<p>After losing his family to Sauron\'s forces, Talion, a ranger of Gondor, finds himself bound to a mysterious Elven spirit. Unable to die, he ventures into Mordor seeking revenge, uncovering ancient secrets and confronting ruthless enemies along the way.</p>', '<p>Dear reader, imagine defeating an enemy, walking away victorious, and forgetting about him entirely. A few hours later, that same enemy returns, proudly displaying the scar you gave him and announcing that he remembers you.</p><p>Welcome to <em>Middle-earth: Shadow of Mordor</em>, where even the Orcs know how to hold a grudge.</p><p>Developed by Monolith Productions, this action-adventure game takes us into a darker corner of Tolkien\'s universe, following Talion, a ranger whose tragic past leads him into a relentless pursuit of revenge.</p><p>At first glance, the experience might seem familiar. An open world, dangerous enemies, stealth mechanics, and a combat system reminiscent of the Batman: Arkham series. But beneath that familiar foundation lies something genuinely fascinating.</p><p><strong>The Nemesis System is the real star of the adventure.</strong></p><p>Rather than treating enemies as disposable obstacles, the game gives Orc captains personalities, ambitions, rivalries, and memories. They can rise through the ranks, betray one another, and return from previous encounters with new scars and an understandable desire to remove your head.</p><p>This creates unpredictable stories that feel surprisingly personal.</p><p>You might spend an entire evening hunting a powerful commander, only to discover that an insignificant Orc who defeated you earlier has become an even greater threat.</p><p>And suddenly, your carefully planned revenge has acquired an unexpected appointment.</p><p><strong>Combat is another highlight.</strong></p><p>Talion combines swordsmanship, archery, stealth, and supernatural abilities in encounters that are fast, brutal, and wonderfully satisfying.</p><p>There is genuine pleasure in approaching an enemy fortress silently, eliminating its guards, and watching a carefully constructed strategy unfold.</p><p>Of course, there is also pleasure in abandoning that strategy entirely and charging into battle. Not every brilliant idea survives contact with an Orc army.</p><p>As Talion becomes more powerful, the combat opens up even further. His abilities allow for increasingly creative approaches, making each confrontation an opportunity to experiment.</p><p>However, Mordor is not without its problems.</p><p><strong>The open world can become repetitive.</strong> Several missions rely on familiar objectives, and the environments occasionally lack the variety needed to make exploration consistently rewarding.</p><p>The main story, while intriguing, also struggles to compete with the spontaneous rivalries created by the Nemesis System. Some confrontations feel less impressive than the ordinary battles that preceded them.</p><p>And that is perhaps the game\'s greatest contradiction: its most memorable villains are often the ones it never specifically intended you to remember.</p><p>Still, <em>Middle-earth: Shadow of Mordor</em> accomplishes something remarkable. It makes failure interesting, transforms enemies into characters, and gives ordinary encounters lasting consequences.</p><p>It is not a flawless journey through Middle-earth, but it is a memorable one.</p><p>After all, dear reader, defeating a villain is satisfying. Discovering that he remembers exactly how you defeated him?</p><p>Now that is personal.</p>', NULL, 'Major spoilers ahead.\r\n\r\nThe mysterious spirit accompanying Talion is revealed to be Celebrimbor, the Elven smith connected to the Rings of Power.\r\n\r\nAfter confronting the Black Hand of Sauron, Talion and Celebrimbor prevent the Dark Lord\'s immediate return, but their victory does not end the greater threat.\r\n\r\nRather than seek peace, they decide to continue their struggle and forge a new Ring of Power.\r\n\r\nA troubling choice, considering how the last one turned out.', 'Middle-earth: Shadow of Mordor — Where Enemies Never Forget', 'In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behi', '2026-11-03 23:26:29', '2026-11-03 23:26:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `post_views`
--

CREATE TABLE `post_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `visitor_key` varchar(64) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `post_views`
--

INSERT INTO `post_views` (`id`, `post_id`, `locale`, `visitor_key`, `viewed_at`) VALUES
(1, 1, 'en-us', 'a49bcbd7b7d6804242a47d252358e2c10deea2e690c787caf8dc5c273da4ab9e', '2026-11-03 23:26:43');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reactions`
--

CREATE TABLE `reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `visitor_key` varchar(64) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'like',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `reactions`
--

INSERT INTO `reactions` (`id`, `post_id`, `locale`, `user_id`, `visitor_key`, `type`, `created_at`, `updated_at`) VALUES
(2, 1, 'en-us', 1, '239e271032a246766450c9c3b70f0cc915867a3c0247fd2dbd7b35403189c88b', 'like', '2026-11-03 23:27:31', '2026-11-03 23:27:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('j3ctr1N2N4sms2OQqitFs74wPY7RhUOmv3pbM06L', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ09jRlNvSGNkWlAzWm1SU2piOVRrWE84QW9uMG1PdEV4eEpWYjJQUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9lbi11cy9nYW1lcy9taWRkbGUtZWFydGgtc2hhZG93LW9mLW1vcmRvci13aGVyZS1lbmVtaWVzLW5ldmVyLWZvcmdldCI7czo1OiJyb3V0ZSI7czoxMDoicG9zdHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImViMTQxMmUzODM5ZWVkYWIwNjdkZjI0YmM0YjFmZjZlMWVjZmFiNzgzOGIxY2JhYzVmNTI0Y2NlNGE1OTdiYmMiO30=', 1793737651);

-- --------------------------------------------------------

--
-- Estrutura da tabela `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `suggestions`
--

CREATE TABLE `suggestions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$12$RMzAiEepfh34.3/qGBMK8.cWGgKSi6Y0crSF0PTYQYYM/Z/QWnvyW', 1, NULL, '2026-11-03 23:04:53', '2026-11-03 23:04:53', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_interests`
--

CREATE TABLE `user_interests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kind` varchar(24) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `admin_activity_logs_route_name_index` (`route_name`);

--
-- Índices para tabela `ad_slots`
--
ALTER TABLE `ad_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ad_slots_key_unique` (`key`),
  ADD KEY `ad_slots_is_active_index` (`is_active`);

--
-- Índices para tabela `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_name_unique` (`name`),
  ADD KEY `blog_categories_is_active_index` (`is_active`),
  ADD KEY `blog_categories_sort_order_index` (`sort_order`);

--
-- Índices para tabela `blog_category_blog_genre`
--
ALTER TABLE `blog_category_blog_genre`
  ADD PRIMARY KEY (`blog_category_id`,`blog_genre_id`),
  ADD KEY `blog_category_blog_genre_blog_genre_id_foreign` (`blog_genre_id`);

--
-- Índices para tabela `blog_category_localizations`
--
ALTER TABLE `blog_category_localizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_category_localizations_blog_category_id_locale_unique` (`blog_category_id`,`locale`);

--
-- Índices para tabela `blog_genres`
--
ALTER TABLE `blog_genres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_genres_is_active_index` (`is_active`),
  ADD KEY `blog_genres_sort_order_index` (`sort_order`);

--
-- Índices para tabela `blog_genre_localizations`
--
ALTER TABLE `blog_genre_localizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_genre_localizations_blog_genre_id_locale_unique` (`blog_genre_id`,`locale`),
  ADD UNIQUE KEY `blog_genre_localizations_locale_slug_unique` (`locale`,`slug`),
  ADD KEY `blog_genre_localizations_locale_index` (`locale`),
  ADD KEY `blog_genre_localizations_is_active_index` (`is_active`);

--
-- Índices para tabela `blog_genre_post`
--
ALTER TABLE `blog_genre_post`
  ADD PRIMARY KEY (`blog_genre_id`,`post_id`),
  ADD KEY `blog_genre_post_post_id_foreign` (`post_id`);

--
-- Índices para tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Índices para tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_is_active_index` (`is_active`);

--
-- Índices para tabela `category_genre`
--
ALTER TABLE `category_genre`
  ADD PRIMARY KEY (`category_id`,`genre_id`),
  ADD KEY `category_genre_genre_id_foreign` (`genre_id`);

--
-- Índices para tabela `category_localizations`
--
ALTER TABLE `category_localizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_localizations_category_id_locale_unique` (`category_id`,`locale`),
  ADD UNIQUE KEY `category_localizations_locale_slug_unique` (`locale`,`slug`),
  ADD KEY `category_localizations_locale_index` (`locale`),
  ADD KEY `category_localizations_is_active_index` (`is_active`);

--
-- Índices para tabela `category_post`
--
ALTER TABLE `category_post`
  ADD PRIMARY KEY (`category_id`,`post_id`),
  ADD KEY `category_post_post_id_foreign` (`post_id`);

--
-- Índices para tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_status_index` (`status`),
  ADD KEY `comments_post_status_created_index` (`post_id`,`status`,`created_at`),
  ADD KEY `comments_post_locale_status_created_index` (`post_id`,`locale`,`status`,`created_at`),
  ADD KEY `comments_locale_index` (`locale`),
  ADD KEY `comments_public_thread_idx` (`post_id`,`locale`,`parent_id`,`status`);

--
-- Índices para tabela `comment_reactions`
--
ALTER TABLE `comment_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_reactions_comment_id_user_id_unique` (`comment_id`,`user_id`),
  ADD KEY `comment_reactions_user_id_foreign` (`user_id`);

--
-- Índices para tabela `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_messages_status_index` (`status`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genres_slug_unique` (`slug`);

--
-- Índices para tabela `genre_localizations`
--
ALTER TABLE `genre_localizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre_localizations_genre_id_locale_unique` (`genre_id`,`locale`),
  ADD UNIQUE KEY `genre_localizations_locale_slug_unique` (`locale`,`slug`);

--
-- Índices para tabela `genre_post`
--
ALTER TABLE `genre_post`
  ADD PRIMARY KEY (`genre_id`,`post_id`),
  ADD KEY `genre_post_post_id_foreign` (`post_id`);

--
-- Índices para tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices para tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_post_id_foreign` (`post_id`),
  ADD KEY `media_locale_index` (`locale`),
  ADD KEY `media_type_index` (`type`),
  ADD KEY `media_is_primary_index` (`is_primary`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `moderation_terms`
--
ALTER TABLE `moderation_terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `moderation_terms_phrase_locale_unique` (`phrase`,`locale`),
  ADD KEY `moderation_terms_locale_is_active_index` (`locale`,`is_active`);

--
-- Índices para tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices para tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_author_id_foreign` (`author_id`),
  ADD KEY `posts_status_index` (`status`),
  ADD KEY `posts_is_featured_index` (`is_featured`),
  ADD KEY `posts_published_at_index` (`published_at`),
  ADD KEY `posts_publication_type_index` (`publication_type`),
  ADD KEY `posts_blog_category_id_foreign` (`blog_category_id`);

--
-- Índices para tabela `post_localizations`
--
ALTER TABLE `post_localizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_localizations_post_id_locale_unique` (`post_id`,`locale`),
  ADD UNIQUE KEY `post_localizations_locale_slug_unique` (`locale`,`slug`),
  ADD KEY `post_localizations_locale_index` (`locale`),
  ADD KEY `post_localizations_is_active_index` (`is_active`),
  ADD KEY `post_localizations_is_featured_index` (`is_featured`);

--
-- Índices para tabela `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_views_visitor_key_index` (`visitor_key`),
  ADD KEY `post_views_viewed_at_index` (`viewed_at`),
  ADD KEY `post_views_post_viewed_index` (`post_id`,`viewed_at`),
  ADD KEY `post_views_post_locale_viewed_index` (`post_id`,`locale`,`viewed_at`),
  ADD KEY `post_views_locale_index` (`locale`);

--
-- Índices para tabela `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reactions_post_locale_user_type_unique` (`post_id`,`locale`,`user_id`,`type`),
  ADD UNIQUE KEY `reactions_post_locale_visitor_type_unique` (`post_id`,`locale`,`visitor_key`,`type`),
  ADD UNIQUE KEY `reactions_user_post_locale_type_unique` (`post_id`,`locale`,`user_id`,`type`),
  ADD KEY `reactions_user_id_foreign` (`user_id`),
  ADD KEY `reactions_type_index` (`type`),
  ADD KEY `reactions_post_type_created_index` (`post_id`,`type`,`created_at`),
  ADD KEY `reactions_post_locale_type_created_index` (`post_id`,`locale`,`type`,`created_at`),
  ADD KEY `reactions_locale_index` (`locale`);

--
-- Índices para tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices para tabela `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`);

--
-- Índices para tabela `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suggestions_status_index` (`status`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD KEY `users_is_admin_index` (`is_admin`);

--
-- Índices para tabela `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_favorites_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `user_favorites_post_id_foreign` (`post_id`);

--
-- Índices para tabela `user_interests`
--
ALTER TABLE `user_interests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_interests_unique` (`user_id`,`kind`,`reference_id`),
  ADD KEY `user_interests_kind_reference_id_index` (`kind`,`reference_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `ad_slots`
--
ALTER TABLE `ad_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog_category_localizations`
--
ALTER TABLE `blog_category_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog_genres`
--
ALTER TABLE `blog_genres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog_genre_localizations`
--
ALTER TABLE `blog_genre_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `category_localizations`
--
ALTER TABLE `category_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comment_reactions`
--
ALTER TABLE `comment_reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `genres`
--
ALTER TABLE `genres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `genre_localizations`
--
ALTER TABLE `genre_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `moderation_terms`
--
ALTER TABLE `moderation_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `post_localizations`
--
ALTER TABLE `post_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user_interests`
--
ALTER TABLE `user_interests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `blog_category_blog_genre`
--
ALTER TABLE `blog_category_blog_genre`
  ADD CONSTRAINT `blog_category_blog_genre_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_category_blog_genre_blog_genre_id_foreign` FOREIGN KEY (`blog_genre_id`) REFERENCES `blog_genres` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `blog_category_localizations`
--
ALTER TABLE `blog_category_localizations`
  ADD CONSTRAINT `blog_category_localizations_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `blog_genre_localizations`
--
ALTER TABLE `blog_genre_localizations`
  ADD CONSTRAINT `blog_genre_localizations_blog_genre_id_foreign` FOREIGN KEY (`blog_genre_id`) REFERENCES `blog_genres` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `blog_genre_post`
--
ALTER TABLE `blog_genre_post`
  ADD CONSTRAINT `blog_genre_post_blog_genre_id_foreign` FOREIGN KEY (`blog_genre_id`) REFERENCES `blog_genres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_genre_post_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `category_genre`
--
ALTER TABLE `category_genre`
  ADD CONSTRAINT `category_genre_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_genre_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `category_localizations`
--
ALTER TABLE `category_localizations`
  ADD CONSTRAINT `category_localizations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `category_post`
--
ALTER TABLE `category_post`
  ADD CONSTRAINT `category_post_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_post_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `comment_reactions`
--
ALTER TABLE `comment_reactions`
  ADD CONSTRAINT `comment_reactions_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `genre_localizations`
--
ALTER TABLE `genre_localizations`
  ADD CONSTRAINT `genre_localizations_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `genre_post`
--
ALTER TABLE `genre_post`
  ADD CONSTRAINT `genre_post_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `genre_post_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `post_localizations`
--
ALTER TABLE `post_localizations`
  ADD CONSTRAINT `post_localizations_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `post_views`
--
ALTER TABLE `post_views`
  ADD CONSTRAINT `post_views_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `user_interests`
--
ALTER TABLE `user_interests`
  ADD CONSTRAINT `user_interests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
