-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Nov-2026 às 20:21
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
(15, 1, 'PUT', 'admin.posts.update', '/admin/posts/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-03 23:26:34', '2026-11-03 23:26:34'),
(16, 1, 'PUT', 'admin.posts.update', '/admin/posts/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-04 23:40:39', '2026-11-04 23:40:39'),
(17, 1, 'DELETE', 'admin.posts.destroy', '/admin/posts/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-05 23:20:58', '2026-11-05 23:20:58'),
(18, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-05 23:25:40', '2026-11-05 23:25:40'),
(19, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-05 23:26:06', '2026-11-05 23:26:06'),
(20, 1, 'PUT', 'admin.posts.update', '/admin/posts/2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-05 23:27:12', '2026-11-05 23:27:12'),
(21, 1, 'PUT', 'admin.posts.update', '/admin/posts/2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-06 14:23:01', '2026-11-06 14:23:01'),
(22, 1, 'PUT', 'admin.posts.update', '/admin/posts/2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-06 14:53:14', '2026-11-06 14:53:14');

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
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:3;', 1793912065),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1793912065;', 1793912065),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1793992954),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1793992954;', 1793992954),
('laravel-cache-cinehub:home:de:popular', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793911879),
('laravel-cache-cinehub:home:de:trending', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793911879),
('laravel-cache-cinehub:home:en-us:popular', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;a:18:{s:2:\"id\";i:2;s:5:\"title\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:7:\"excerpt\";s:223:\"In Stranger Things, the disappearance of a young boy exposes the secrets beneath the respectable town of Hawkins. Amid supernatural horrors and dangerous experiments, a handful of unlikely heroes discovers that friendshi...\";s:4:\"type\";s:8:\"tv-shows\";s:10:\"type_label\";s:8:\"TV Shows\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/b9EkMc79ZSU/hqdefault.jpg\";s:9:\"image_alt\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/b9EkMc79ZSU?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"5\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:82:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins/like\";s:3:\"url\";s:77:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793989517),
('laravel-cache-cinehub:home:en-us:trending', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;a:6:{s:5:\"title\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:8:\"subtitle\";s:8:\"TV Shows\";s:5:\"views\";s:1:\"5\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/b9EkMc79ZSU/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/b9EkMc79ZSU?rel=0\";s:3:\"url\";s:77:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793989517),
('laravel-cache-cinehub:home:fr:popular', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793911871),
('laravel-cache-cinehub:home:fr:trending', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1793911871);

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
(3, 8, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 9, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 10, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 11, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 12, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
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
(16, 4, 'en-ca', 1, 'games', 'Games', 'Description', 'Games', 'Description', '2026-11-03 23:07:48', '2026-11-03 23:07:48'),
(17, 1, 'fr', 1, 'anecdotes', 'Anecdotes', 'Découvrez des anecdotes et des faits fascinants sur les films, les séries et les jeux vidéo.', 'Anecdotes | CineHub', 'Explorez des anecdotes et des faits fascinants sur les films, les séries et les jeux vidéo.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(18, 1, 'de', 1, 'wissenswertes', 'Wissenswertes', 'Entdecken Sie spannende Fakten und interessante Hintergründe zu Filmen, Serien und Videospielen.', 'Wissenswertes | CineHub', 'Entdecken Sie spannende Fakten und Geschichten aus der Welt der Unterhaltung.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(19, 2, 'fr', 1, 'films', 'Films', 'Découvrez des films, des critiques et des histoires passionnantes du cinéma.', 'Films | CineHub', 'Explorez le cinéma avec des films, des critiques et des histoires passionnantes.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(20, 2, 'de', 1, 'filme', 'Filme', 'Entdecken Sie Filme, Rezensionen und spannende Geschichten aus der Welt des Kinos.', 'Filme | CineHub', 'Entdecken Sie Filme, Rezensionen und interessante Einblicke in die Welt des Kinos.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(21, 3, 'fr', 1, 'series-tv', 'Séries TV', 'Découvrez des séries télévisées, des critiques et des histoires captivantes.', 'Séries TV | CineHub', 'Découvrez des séries télévisées, des critiques et des analyses sur CineHub.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(22, 3, 'de', 1, 'serien', 'Serien', 'Entdecken Sie Fernsehserien, Rezensionen und spannende Geschichten.', 'Serien | CineHub', 'Entdecken Sie Fernsehserien, Rezensionen und spannende Geschichten auf CineHub.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(23, 4, 'fr', 1, 'jeux-video', 'Jeux vidéo', 'Découvrez des jeux vidéo, des critiques et des aventures passionnantes.', 'Jeux vidéo | CineHub', 'Découvrez des jeux vidéo, des critiques et des aventures dans le monde du gaming.', '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(24, 4, 'de', 1, 'videospiele', 'Videospiele', 'Entdecken Sie Videospiele, Rezensionen und spannende Abenteuer.', 'Videospiele | CineHub', 'Entdecken Sie Videospiele, Rezensionen und spannende Abenteuer auf CineHub.', '2026-11-05 20:47:14', '2026-11-05 20:47:14');

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
(3, 2, '2026-11-05 23:27:11', '2026-11-05 23:27:11'),
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
(7, 'Hack and Slash', 'hack-and-slash', 1, 0, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(8, 'Science Fiction', 'science-fiction', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(9, 'Horror', 'horror', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(10, 'Mystery', 'mystery', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(11, 'Drama', 'drama', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(12, 'Thriller', 'thriller', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14');

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
(29, 7, 'en-ca', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-03 23:15:27', '2026-11-03 23:15:27'),
(30, 1, 'fr', 'Aventure', 'aventure', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(31, 1, 'de', 'Abenteuer', 'abenteuer', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(32, 2, 'fr', 'Action', 'action', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(33, 2, 'de', 'Action', 'action', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(34, 3, 'fr', 'Action-RPG', 'action-rpg', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(35, 3, 'de', 'Action-RPG', 'action-rpg', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(36, 4, 'fr', 'Monde ouvert', 'monde-ouvert', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(37, 4, 'de', 'Offene Spielwelt', 'offene-spielwelt', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(38, 5, 'fr', 'Infiltration', 'infiltration', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(39, 5, 'de', 'Schleichspiel', 'schleichspiel', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(40, 6, 'fr', 'Fantasy', 'fantasy', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(41, 6, 'de', 'Fantasy', 'fantasy', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(42, 7, 'fr', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(43, 7, 'de', 'Hack and Slash', 'hack-and-slash', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(44, 8, 'en-us', 'Science Fiction', 'science-fiction', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(45, 8, 'fr', 'Science-fiction', 'science-fiction', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(46, 8, 'de', 'Science-Fiction', 'science-fiction', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(47, 9, 'en-us', 'Horror', 'horror', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(48, 9, 'fr', 'Horreur', 'horreur', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(49, 9, 'de', 'Horror', 'horror', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(50, 10, 'en-us', 'Mystery', 'mystery', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(51, 10, 'fr', 'Mystère', 'mystere', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(52, 10, 'de', 'Mystery', 'mystery', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(53, 11, 'en-us', 'Drama', 'drama', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(54, 11, 'fr', 'Drame', 'drame', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(55, 11, 'de', 'Drama', 'drama', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(56, 12, 'en-us', 'Thriller', 'thriller', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(57, 12, 'fr', 'Thriller', 'thriller', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(58, 12, 'de', 'Thriller', 'thriller', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14');

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
(7, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(8, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(9, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(10, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(11, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(12, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14');

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
(1, 1, 'en-us', 'trailer', 'youtube', 'i-OUZ-0eI50', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=i-OUZ-0eI50', NULL, 1, 0, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(2, 2, 'en-us', 'trailer', 'youtube', 'b9EkMc79ZSU', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=b9EkMc79ZSU', NULL, 1, 0, '2026-11-05 23:26:06', '2026-11-05 23:26:06');

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
(31, '2026_11_03_000003_create_moderation_terms', 1),
(32, '2026_11_04_000001_ensure_users_remember_token', 2),
(33, '2026_11_04_000100_create_page_engagements_table', 2),
(34, '2026_11_04_180000_add_admin_two_factor_to_users', 3);

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
-- Estrutura da tabela `page_engagements`
--

CREATE TABLE `page_engagements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visit_token` char(36) NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(12) NOT NULL,
  `visitor_key` varchar(64) DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `active_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `engaged` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `page_engagements`
--

INSERT INTO `page_engagements` (`id`, `visit_token`, `post_id`, `locale`, `visitor_key`, `duration_seconds`, `active_seconds`, `engaged`, `started_at`, `updated_at`) VALUES
(1, 'd4610812-8183-480f-9114-2ce7d200ce05', 1, 'en-us', 'd7633b375e1e21513a889b16b8438472f899b4133ca4b253b966eb497c44a35e', 0, 0, 1, '2026-11-04 21:29:52', '2026-11-04 22:31:06'),
(2, '78d1b796-3d49-4592-8059-dc36287530eb', 1, 'en-us', 'd7633b375e1e21513a889b16b8438472f899b4133ca4b253b966eb497c44a35e', 1, 1, 1, '2026-11-04 22:31:07', '2026-11-04 22:31:08'),
(3, '5097823f-3f77-40a7-89ef-b71a12c9b37a', 1, 'en-us', '1e47247073e960868ed7d2e5e6288c0c7f04c5d1a5214f4cc1e49ff5e5f07c55', 0, 0, 1, '2026-11-04 23:10:42', '2026-11-04 23:12:19'),
(4, '1514630e-9ad8-46a7-a7e7-3543df0d3723', 1, 'en-us', '1e47247073e960868ed7d2e5e6288c0c7f04c5d1a5214f4cc1e49ff5e5f07c55', 1, 1, 1, '2026-11-04 23:12:20', '2026-11-04 23:12:21'),
(5, '66d85861-2a6c-4e90-8bd0-61df505bc414', 1, 'en-us', '1e47247073e960868ed7d2e5e6288c0c7f04c5d1a5214f4cc1e49ff5e5f07c55', 0, 0, 0, '2026-11-04 23:17:44', '2026-11-04 23:37:45'),
(6, 'fae2e9e2-6618-4dc9-9437-a8b314bfa5db', 1, 'en-us', 'bac12d8b045f9fd46931992b2b91dfb4c562012d491136cd72dbeab39731fa77', 0, 0, 1, '2026-11-04 23:40:48', '2026-11-04 23:56:45'),
(7, 'eec0fbce-6821-4f3a-ad05-6444bfe5134d', 1, 'en-us', 'bac12d8b045f9fd46931992b2b91dfb4c562012d491136cd72dbeab39731fa77', 1, 0, 1, '2026-11-04 23:56:45', '2026-11-04 23:57:01'),
(8, '38bbe717-401f-4d9f-9598-1a53f52e4aa5', 1, 'en-us', '8396d06b35fbce0418f79969eae71e6c0390ab1aa8e9ca78157c4776420c1e10', 0, 0, 1, '2026-11-05 23:05:50', '2026-11-05 23:13:41'),
(9, '02cacce3-ea42-4dbc-b8ec-f7e9a626c8c6', 2, 'en-us', '4036d1663b122583a24d44e6a9287f0f30d118f8484a211434867e0f4ebe60ee', 0, 0, 1, '2026-11-05 23:28:19', '2026-11-05 23:28:39'),
(10, '8af335ce-5a5a-4136-a5c3-f71302aa2c1d', 2, 'en-us', '287fef68f239ebfafcdb3aa924e2d5a289d5e91c37879e5740995bf584c2340c', 0, 0, 1, '2026-11-05 23:50:10', '2026-11-05 23:53:26'),
(11, '3b39f31b-6b33-4392-928e-c62bdf1b674d', 2, 'en-us', 'fd449ba0941d00eb1b465d596e7bab495886ebe079f0e65378c2ac1383d7c13d', 0, 0, 0, '2026-11-06 14:18:52', '2026-11-06 14:19:00'),
(12, '464045b7-698e-4c8f-8619-59efae235468', 2, 'en-us', '8df176f7c8974b6020e7b41ddd3b6ee9ba1bde83cfcbf4bb0efd7cf4c4fcd2ad', 0, 0, 1, '2026-11-06 14:23:04', '2026-11-06 14:50:15'),
(13, 'a062ff46-02e3-47d0-8681-907d3d00a0e3', 2, 'en-us', '8df176f7c8974b6020e7b41ddd3b6ee9ba1bde83cfcbf4bb0efd7cf4c4fcd2ad', 0, 0, 0, '2026-11-06 14:50:15', '2026-11-06 14:50:16'),
(14, '1d2f5054-e1e9-4fc9-bfaa-72f14cf777fb', 2, 'en-us', '8df176f7c8974b6020e7b41ddd3b6ee9ba1bde83cfcbf4bb0efd7cf4c4fcd2ad', 0, 0, 0, '2026-11-06 14:50:16', '2026-11-06 14:50:17'),
(15, 'b57ace35-1325-4998-bf23-9a1a34212e45', 2, 'en-us', '8df176f7c8974b6020e7b41ddd3b6ee9ba1bde83cfcbf4bb0efd7cf4c4fcd2ad', 0, 0, 0, '2026-11-06 14:50:17', '2026-11-06 14:52:21'),
(16, '12ae6342-870d-4532-875a-893e008653c2', 2, 'en-us', '9e11e1835aa41e24facfc2cf032574b775e3034a44cc71b6ee86ddc5667ff0f4', 1, 0, 1, '2026-11-06 19:38:39', '2026-11-06 20:26:40'),
(17, 'f0ae9285-084b-4f24-af17-6e4b73571f88', 2, 'en-us', '9e11e1835aa41e24facfc2cf032574b775e3034a44cc71b6ee86ddc5667ff0f4', 0, 0, 1, '2026-11-06 21:23:19', '2026-11-06 22:21:34');

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
(1, 1, 'published', 'standard', NULL, 0, '2026-11-03 23:26:00', '2026-11-03 23:26:29', '2026-11-05 23:20:58', '2026-11-05 23:20:58'),
(2, 1, 'published', 'standard', NULL, 0, '2026-11-05 23:26:00', '2026-11-05 23:26:06', '2026-11-05 23:27:11', NULL);

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
(1, 1, 'en-us', 1, 0, 'middle-earth-shadow-of-mordor-where-enemies-never-forget', 'Middle-earth: Shadow of Mordor — Where Enemies Never Forget', '<p>In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behind. In Mordor, even death cannot erase a rivalry.</p>', '<p>After losing his family to Sauron\'s forces, Talion, a ranger of Gondor, finds himself bound to a mysterious Elven spirit. Unable to die, he ventures into Mordor seeking revenge, uncovering ancient secrets and confronting ruthless enemies along the way.</p>', '<p>Dear reader, imagine defeating an enemy, walking away victorious, and giving him no further thought. A few hours later, he returns. There is a fresh scar across his face, a promotion to celebrate, and a rather personal interest in seeing you dead.</p><p>He remembers everything. You, unfortunately, barely remember his name.</p><p>Welcome to <em>Middle-earth: Shadow of Mordor</em>, where even the Orcs know how to hold a grudge.</p><p>Developed by Monolith Productions, this action-adventure game takes us into a darker corner of Tolkien\'s universe. We follow Talion, a ranger of Gondor whose tragic past sets him on a relentless journey through a land where revenge rarely goes according to plan.</p><p>At first glance, much of the experience feels familiar. An open world, dangerous enemies, stealth mechanics, and combat reminiscent of the <em>Batman: Arkham</em> series.</p><p>But beneath that familiar foundation lies something rather extraordinary.</p><h3>The Nemesis System: Enemies Who Remember</h3><p>The true brilliance of <em>Shadow of Mordor</em> is not found in its main storyline, impressive abilities, or even its brutal combat.</p><p>It is found in the enemies who refuse to stay forgotten.</p><p>The <strong>Nemesis System</strong> transforms ordinary Orc captains into characters with personalities, ambitions, rivalries, and surprisingly good memories.</p><p>An Orc who defeats you might rise through the ranks, becoming stronger and considerably more arrogant. Another might survive your sword, returning later with an ugly scar and an understandable desire for revenge.</p><p>And these encounters rarely unfold as expected.</p><p>You might spend an entire evening preparing to eliminate a powerful commander, only to discover that an insignificant Orc from an earlier encounter has become an even greater threat.</p><p>Suddenly, your carefully planned revenge has acquired an unexpected appointment.</p><p>What makes this system fascinating is how naturally these rivalries develop. The game does not need to explain why a particular enemy matters. You remember him because you have history together.</p><p>A history that usually involves someone attempting to remove someone else\'s head.</p><p>Few games make an ordinary defeat feel quite so personal.</p><h3>Combat and Stealth: The Art of Surviving Mordor</h3><p>Fortunately, Talion is not entirely helpless against Mordor\'s enthusiastic population.</p><p>His fighting style combines swordsmanship, archery, stealth, and supernatural abilities, creating encounters that are fast, fluid, and wonderfully satisfying.</p><p>There is something delightful about approaching an enemy fortress silently, studying its defenses, eliminating isolated guards, and watching a carefully constructed strategy unfold.</p><p>Of course, there is also something equally delightful about abandoning that strategy entirely and charging into battle.</p><p>Not every brilliant idea survives contact with an Orc army.</p><p>The combat system rewards timing and awareness, encouraging you to move between enemies while combining attacks, executions, and supernatural powers.</p><p>As Talion grows stronger, new abilities make these confrontations increasingly creative.</p><p>Yet the real pleasure comes from the freedom to approach situations differently. One encounter might reward patience and stealth, while another becomes a spectacular disaster involving far more Orcs than originally anticipated.</p><p>And sometimes, dear reader, surviving a terrible decision is its own kind of victory.</p><h3>The Open World: When Mordor Loses Its Mystery</h3><p>For all its fascinating ideas, however, <em>Shadow of Mordor</em> is not without its weaknesses.</p><p>Mordor is an appropriately hostile place, filled with dangerous fortresses, enemy patrols, and opportunities for unexpected confrontations.</p><p>Unfortunately, its environments do not always offer enough variety to make exploration consistently rewarding.</p><p>Several missions rely on familiar objectives, and certain activities begin to feel repetitive after spending enough time wandering through enemy territory.</p><p>The main storyline faces an even more curious problem.</p><p>Although Talion\'s journey provides an intriguing reason to explore Mordor, its scripted confrontations sometimes struggle to compete with the spontaneous rivalries created by the Nemesis System.</p><p>A carefully presented villain may appear important, only to be overshadowed by an ordinary Orc captain who has spent the past several hours making your life miserable.</p><p>There is something wonderfully ironic about that.</p><p>The game\'s greatest storytelling achievement is also the reason its traditional narrative occasionally feels less memorable.</p><h3>Final Verdict: A Revenge Worth Remembering</h3><p>Despite its repetitive moments, <em>Middle-earth: Shadow of Mordor</em> accomplishes something genuinely remarkable.</p><p>It transforms enemies into personalities, turns failure into an opportunity for unexpected storytelling, and gives ordinary battles consequences that linger long after the fighting ends.</p><p>Its combat remains satisfying, its supernatural abilities add welcome variety, and the Nemesis System introduces a kind of personal rivalry that makes every return to Mordor feel unpredictable.</p><p>The game is not flawless. Its world could offer more variety, and its central story does not always match the brilliance of the adventures you create along the way.</p><p>But perhaps that is precisely what makes it so memorable.</p><p>After all, dear reader, defeating a villain is satisfying.</p><p>Discovering that he remembers exactly how you defeated him, has spent the intervening hours preparing his revenge, and is now waiting somewhere in Mordor?</p><p>Now that is personal.</p>', NULL, 'Major spoilers ahead.\r\n\r\nThe mysterious spirit accompanying Talion is revealed to be Celebrimbor, the Elven smith connected to the Rings of Power.\r\n\r\nAfter confronting the Black Hand of Sauron, Talion and Celebrimbor prevent the Dark Lord\'s immediate return, but their victory does not end the greater threat.\r\n\r\nRather than seek peace, they decide to continue their struggle and forge a new Ring of Power.\r\n\r\nA troubling choice, considering how the last one turned out.', 'Middle-earth: Shadow of Mordor — Where Enemies Never Forget', 'In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behi', '2026-11-03 23:26:29', '2026-11-04 23:40:39'),
(2, 2, 'en-us', 1, 0, 'stranger-things-the-secrets-beneath-hawkins', 'Stranger Things — The Secrets Beneath Hawkins', '<p>In <em>Stranger Things</em>, the disappearance of a young boy exposes the secrets beneath the respectable town of Hawkins. Amid supernatural horrors and dangerous experiments, a handful of unlikely heroes discovers that friendship may be the finest form of courage.</p>', '<p>In 1983, the peaceful town of Hawkins, Indiana, is shaken by the mysterious disappearance of young Will Byers. As his friends search for him, they encounter Eleven, a girl with extraordinary abilities and a troubling past.</p><p>Meanwhile, Will\'s mother, Joyce, and Police Chief Jim Hopper uncover disturbing connections to a secret government laboratory. What begins as a desperate search soon exposes supernatural dangers, hidden experiments, and a terrifying mystery that threatens everything the townspeople hold dear.</p>', '<p>There is something particularly unsettling about a town that appears to have nothing to hide. Neatly kept homes, familiar neighbors, and the reassuring predictability of daily life can persuade even the most suspicious observer that all is well. Hawkins, Indiana, possesses precisely this sort of respectable appearance. Its inhabitants might have continued enjoying their modest peace had certain individuals not developed the unfortunate habit of conducting dangerous experiments beyond public scrutiny.</p><p><strong>Stranger Things</strong>, created by the Duffer Brothers, begins with the disappearance of Will Byers, a young boy whose absence disturbs far more than the tranquility of his hometown. What follows is a remarkable combination of supernatural mystery, science fiction, horror, and the complicated business of growing up.</p><p>Yet the series possesses a quality far more valuable than its collection of frightening creatures: it understands the extraordinary importance of ordinary human attachments.</p><h3>The Quiet Courage of Unlikely Heroes</h3><p>At the heart of <em>Stranger Things</em> lies a group of children whose loyalty proves considerably stronger than their understanding of the dangers surrounding them.</p><p>Mike, Dustin, Lucas, and Will inhabit a world of bicycles, games, schoolyard difficulties, and friendships that occasionally suffer from the familiar inconveniences of pride and jealousy. Their affection is neither flawless nor sentimental. Disagreements arise, trust is tested, and youthful confidence sometimes leads to decisions that would alarm any sensible guardian.</p><p>Their friendship, however, gives the story its emotional foundation.</p><p>The arrival of Eleven introduces a particularly compelling contrast. Possessing extraordinary abilities but little experience of ordinary affection, she discovers that friendship offers something no laboratory could ever provide: the possibility of being valued as a person rather than an instrument.</p><p>Millie Bobby Brown brings considerable expressiveness to a character whose earliest appearances depend more upon silence and gesture than elaborate conversation. The young ensemble likewise makes the friendships believable, allowing the supernatural events to carry genuine emotional weight.</p><h3>When Respectability Conceals Something Dreadful</h3><p>Hawkins presents a rather discouraging portrait of institutional authority.</p><p>While families struggle to understand the extraordinary events unfolding around them, the local laboratory conceals activities whose consequences extend far beyond its walls. One might reasonably expect distinguished scientific minds to exercise a certain degree of caution. Evidently, such expectations are sometimes misplaced.</p><p>The series uses this contrast effectively. The adults entrusted with knowledge and authority are not necessarily those most deserving of confidence, while children dismissed for their improbable suspicions often demonstrate admirable determination.</p><p>Joyce Byers, portrayed by Winona Ryder, provides one of the story\'s strongest emotional performances. Her refusal to abandon hope, even when confronted with disbelief, gives the mystery a deeply personal urgency.</p><p>David Harbour\'s Jim Hopper offers a different kind of strength. Beneath his weary disposition lies a man whose grief and sense of duty gradually complicate his outwardly hardened character.</p><p>Together, these figures prevent the supernatural spectacle from overwhelming the human tragedy beneath it.</p><h3>The Art of Making Darkness Beautiful</h3><p>Few elements of <em>Stranger Things</em> are as immediately recognizable as its recreation of the 1980s.</p><p>The clothing, music, domestic interiors, and cultural references establish a period atmosphere without requiring every scene to announce its historical setting. The synthesizer-driven score contributes substantially to the unease, transforming familiar streets and ordinary buildings into places of considerable suspicion.</p><p>The supernatural horror is especially effective when the series allows uncertainty to do its work. A strange sound, an unexplained disturbance, or the suggestion of something concealed can prove more troubling than a creature displayed in full.</p><p>Nevertheless, the balance is not always maintained.</p><p>As the story expands across its seasons, the scale of its conflicts grows increasingly ambitious. This enlargement provides memorable spectacle, but some extended confrontations and separated storylines weaken the intimacy that made the original mystery so compelling.</p><p>There are occasions when Hawkins appears almost burdened by the importance of its own catastrophes.</p><p>The series is at its strongest when danger remains closely connected to the characters\' fears, loyalties, and personal sacrifices.</p><h3>A Story Greater Than Its Monsters</h3><p>What ultimately distinguishes <em>Stranger Things</em> is not the novelty of its supernatural ideas. Secret experiments, mysterious dimensions, and terrifying creatures have long occupied respectable places in imaginative fiction.</p><p>Its achievement lies in arranging these familiar elements around characters whose relationships give the dangers meaning.</p><p>The friendships evolve, families confront painful uncertainties, and the boundary between childhood and adulthood becomes increasingly difficult to preserve. Beneath the spectacle rests a persistent concern with loss, belonging, and the courage required to protect another person.</p><p><em>Stranger Things</em> occasionally allows its ambitions to exceed its narrative discipline. Yet its atmosphere, performances, and emotional sincerity remain substantial virtues.</p><p>Perhaps its most revealing observation is that extraordinary power does not necessarily belong to those who understand compassion, while courage may flourish among individuals possessing very little power at all.</p><p>And in Hawkins, where respectable institutions so often prove incapable of protecting the innocent, a handful of devoted friends may be the finest society the town has to offer.</p>', NULL, '<p>Will Byers is discovered alive in the Upside Down, a terrifying reflection of Hawkins connected to experiments conducted at the local laboratory. Joyce and Hopper rescue him, while Eleven apparently sacrifices herself to destroy the Demogorgon.</p><p>Yet the danger is far from over. Will remains affected by his experience, and later revelations expose the disturbing origins of Vecna, once known as Henry Creel and One.</p><p>The discovery that Eleven\'s past is intimately connected to these horrors gives the conflict a tragic dimension: the very powers capable of protecting Hawkins are also bound to its darkest secrets.</p>', 'Stranger Things — The Secrets Beneath Hawkins', 'Discover Stranger Things through an insightful review of its supernatural mysteries, unforgettable characters, and the secrets hidden beneath Hawkins.', '2026-11-05 23:26:06', '2026-11-06 14:53:14');

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
(1, 1, 'en-us', 'a49bcbd7b7d6804242a47d252358e2c10deea2e690c787caf8dc5c273da4ab9e', '2026-11-03 23:26:43'),
(2, 1, 'en-us', 'd7633b375e1e21513a889b16b8438472f899b4133ca4b253b966eb497c44a35e', '2026-11-04 21:29:52'),
(3, 1, 'en-us', '1e47247073e960868ed7d2e5e6288c0c7f04c5d1a5214f4cc1e49ff5e5f07c55', '2026-11-04 23:10:41'),
(4, 1, 'en-us', 'bac12d8b045f9fd46931992b2b91dfb4c562012d491136cd72dbeab39731fa77', '2026-11-04 23:40:48'),
(5, 1, 'en-us', '8396d06b35fbce0418f79969eae71e6c0390ab1aa8e9ca78157c4776420c1e10', '2026-11-05 23:05:50'),
(6, 2, 'en-us', '4036d1663b122583a24d44e6a9287f0f30d118f8484a211434867e0f4ebe60ee', '2026-11-05 23:28:18'),
(7, 2, 'en-us', '287fef68f239ebfafcdb3aa924e2d5a289d5e91c37879e5740995bf584c2340c', '2026-11-05 23:50:09'),
(8, 2, 'en-us', 'fd449ba0941d00eb1b465d596e7bab495886ebe079f0e65378c2ac1383d7c13d', '2026-11-06 14:18:52'),
(9, 2, 'en-us', '8df176f7c8974b6020e7b41ddd3b6ee9ba1bde83cfcbf4bb0efd7cf4c4fcd2ad', '2026-11-06 14:23:04'),
(10, 2, 'en-us', '9e11e1835aa41e24facfc2cf032574b775e3034a44cc71b6ee86ddc5667ff0f4', '2026-11-06 19:38:39');

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
('s90M1M92B9EyiIcMfQ8VyNGiLPZQNXV1VaS6vsmL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3pIUkcwb21ER3hhZWFibW5CMHczWnNERlR5aHJkY1dRYXRONlExYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9lbi90di1zaG93cy9zdHJhbmdlci10aGluZ3MtdGhlLXNlY3JldHMtYmVuZWF0aC1oYXdraW5zIjtzOjU6InJvdXRlIjtzOjEwOiJwb3N0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793992894);

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
  `google_id` varchar(255) DEFAULT NULL,
  `admin_two_factor_secret` text DEFAULT NULL,
  `admin_two_factor_last_step` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`, `google_id`, `admin_two_factor_secret`, `admin_two_factor_last_step`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$12$RMzAiEepfh34.3/qGBMK8.cWGgKSi6Y0crSF0PTYQYYM/Z/QWnvyW', 1, NULL, '2026-11-03 23:04:53', '2026-11-03 23:04:53', NULL, NULL, NULL);

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
-- Índices para tabela `page_engagements`
--
ALTER TABLE `page_engagements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_engagements_visit_token_unique` (`visit_token`),
  ADD KEY `page_engagements_post_id_locale_started_at_index` (`post_id`,`locale`,`started_at`),
  ADD KEY `page_engagements_locale_index` (`locale`),
  ADD KEY `page_engagements_started_at_index` (`started_at`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `genre_localizations`
--
ALTER TABLE `genre_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `moderation_terms`
--
ALTER TABLE `moderation_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `page_engagements`
--
ALTER TABLE `page_engagements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `post_localizations`
--
ALTER TABLE `post_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Limitadores para a tabela `page_engagements`
--
ALTER TABLE `page_engagements`
  ADD CONSTRAINT `page_engagements_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

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
