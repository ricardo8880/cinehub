-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Nov-2026 às 14:54
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
(22, 1, 'PUT', 'admin.posts.update', '/admin/posts/2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-06 14:53:14', '2026-11-06 14:53:14'),
(23, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 21:32:25', '2026-11-09 21:32:25'),
(24, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 21:32:35', '2026-11-09 21:32:35'),
(25, 1, 'PUT', 'admin.posts.update', '/admin/posts/3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 21:32:50', '2026-11-09 21:32:50'),
(26, 1, 'PUT', 'admin.posts.update', '/admin/posts/3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 21:54:51', '2026-11-09 21:54:51'),
(27, 1, 'POST', 'admin.logout', '/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 21:55:07', '2026-11-09 21:55:07'),
(28, 1, 'DELETE', 'admin.posts.destroy', '/admin/posts/3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 23:18:02', '2026-11-09 23:18:02'),
(29, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 23:21:45', '2026-11-09 23:21:45'),
(30, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 23:35:54', '2026-11-09 23:35:54'),
(31, 1, 'PUT', 'admin.genres.update', '/admin/genres/3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 23:41:02', '2026-11-09 23:41:02'),
(32, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-09 23:42:23', '2026-11-09 23:42:23'),
(33, 1, 'DELETE', 'admin.genres.destroy', '/admin/genres/7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 00:23:53', '2026-11-10 00:23:53'),
(34, 1, 'DELETE', 'admin.genres.destroy', '/admin/genres/27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 00:26:28', '2026-11-10 00:26:28'),
(35, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 00:41:03', '2026-11-10 00:41:03'),
(36, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 00:48:41', '2026-11-10 00:48:41'),
(37, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 00:54:58', '2026-11-10 00:54:58'),
(38, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(39, 1, 'PUT', 'admin.posts.update', '/admin/posts/5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 15:10:34', '2026-11-10 15:10:34'),
(40, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 15:50:33', '2026-11-10 15:50:33'),
(41, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(42, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(43, 1, 'PUT', 'admin.posts.update', '/admin/posts/7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:24:18', '2026-11-10 16:24:18'),
(44, 1, 'PUT', 'admin.posts.update', '/admin/posts/5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:26:32', '2026-11-10 16:26:32'),
(45, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:28:47', '2026-11-10 16:28:47'),
(46, 1, 'PUT', 'admin.posts.update', '/admin/posts/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:30:13', '2026-11-10 16:30:13'),
(47, 1, 'POST', 'admin.posts.store', '/admin/posts', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 302, '2026-11-10 16:48:06', '2026-11-10 16:48:06');

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
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:4;', 1794317963),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1794317963;', 1794317963),
('laravel-cache-cinehub:home:de:popular', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:18:{s:2:\"id\";i:7;s:5:\"title\";s:52:\"Elden Ring – Eine Krone, die mehr als Mut verlangt\";s:7:\"excerpt\";s:229:\"Mein geschätzter Leser, prüfen Sie ein Königreich, bevor Sie seine Krone annehmen. In Elden Ring erwarten Sie zerfallene Burgen, streitende Halbgötter und gefährliche Geheimnisse. Jeder Umweg verführt zur Neugier, doch k...\";s:4:\"type\";s:11:\"videospiele\";s:10:\"type_label\";s:11:\"Videospiele\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/P_T2tvWXIss/hqdefault.jpg\";s:9:\"image_alt\";s:52:\"Elden Ring – Eine Krone, die mehr als Mut verlangt\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/P_T2tvWXIss?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"1\";s:6:\"locale\";s:2:\"de\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:89:\"http://localhost:8000/de/videospiele/elden-ring-eine-krone-die-mehr-als-mut-verlangt/like\";s:3:\"url\";s:84:\"http://localhost:8000/de/videospiele/elden-ring-eine-krone-die-mehr-als-mut-verlangt\";}i:1;a:18:{s:2:\"id\";i:6;s:5:\"title\";s:46:\"Sinners – Wenn der Blues die Dunkelheit ruft\";s:7:\"excerpt\";s:227:\"Mein geschätzter Leser, zwei Brüder kehren nach Mississippi zurück, um einen Abend voller Blues und Freiheit zu ermöglichen. Doch in Sinners lockt die Musik nicht nur Freunde an. Manche Gäste bringen ein Grauen mit, das...\";s:4:\"type\";s:5:\"filme\";s:10:\"type_label\";s:5:\"Filme\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/AL4BwibdBOc/hqdefault.jpg\";s:9:\"image_alt\";s:46:\"Sinners – Wenn der Blues die Dunkelheit ruft\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/AL4BwibdBOc?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"de\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:78:\"http://localhost:8000/de/filme/sinners-wenn-der-blues-die-dunkelheit-ruft/like\";s:3:\"url\";s:73:\"http://localhost:8000/de/filme/sinners-wenn-der-blues-die-dunkelheit-ruft\";}i:2;a:18:{s:2:\"id\";i:4;s:5:\"title\";s:68:\"Clair Obscur: Expedition 33 — Schönheit im Schatten des Abschieds\";s:7:\"excerpt\";s:218:\"Clair Obscur: Expedition 33 verbindet die Pracht einer von der Belle Époque geprägten Welt mit unerbittlicher Vergänglichkeit. Ein außergewöhnliches Rollenspiel über Hoffnung, Verlust und die Last der Erinnerung.\";s:4:\"type\";s:11:\"videospiele\";s:10:\"type_label\";s:11:\"Videospiele\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/2VaLOc1FpSo/hqdefault.jpg\";s:9:\"image_alt\";s:68:\"Clair Obscur: Expedition 33 — Schönheit im Schatten des Abschieds\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/2VaLOc1FpSo?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"de\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:104:\"http://localhost:8000/de/videospiele/clair-obscur-expedition-33-schonheit-im-schatten-des-abschieds/like\";s:3:\"url\";s:99:\"http://localhost:8000/de/videospiele/clair-obscur-expedition-33-schonheit-im-schatten-des-abschieds\";}i:3;a:18:{s:2:\"id\";i:8;s:5:\"title\";s:49:\"Wednesday – Eine Schule für schlechte Manieren\";s:7:\"excerpt\";s:231:\"Mein geschätzter Leser, in der Nevermore Academy sind Werwölfe beinahe gewöhnlich. Ungewöhnlicher ist Wednesday Addams, die jedes Lächeln für einen bedauerlichen Fehler hält. Zwischen Mordfällen, Familiengeheimnissen und...\";s:4:\"type\";s:6:\"serien\";s:10:\"type_label\";s:6:\"Serien\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/Neu2e86sNeI/hqdefault.jpg\";s:9:\"image_alt\";s:49:\"Wednesday – Eine Schule für schlechte Manieren\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/Neu2e86sNeI?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"de\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:81:\"http://localhost:8000/de/serien/wednesday-eine-schule-fur-schlechte-manieren/like\";s:3:\"url\";s:76:\"http://localhost:8000/de/serien/wednesday-eine-schule-fur-schlechte-manieren\";}i:4;a:18:{s:2:\"id\";i:5;s:5:\"title\";s:50:\"Fallout – Der Weltuntergang kennt keine Manieren\";s:7:\"excerpt\";s:228:\"Mein geschätzter Leser, die Welt liegt in Trümmern, doch der Drang, anderen Vorschriften zu machen, lebt prächtig weiter. Fallout führt uns aus tadellos gepflegten Bunkern in ein Ödland voller Gefahren, Geheimnisse und e...\";s:4:\"type\";s:6:\"serien\";s:10:\"type_label\";s:6:\"Serien\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/mKX7WIhDUEQ/hqdefault.jpg\";s:9:\"image_alt\";s:50:\"Fallout – Der Weltuntergang kennt keine Manieren\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/mKX7WIhDUEQ?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"de\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:83:\"http://localhost:8000/de/serien/fallout-der-weltuntergang-kennt-keine-manieren/like\";s:3:\"url\";s:78:\"http://localhost:8000/de/serien/fallout-der-weltuntergang-kennt-keine-manieren\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318617),
('laravel-cache-cinehub:home:de:trending', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:6:{s:5:\"title\";s:52:\"Elden Ring – Eine Krone, die mehr als Mut verlangt\";s:8:\"subtitle\";s:11:\"Videospiele\";s:5:\"views\";s:1:\"1\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/P_T2tvWXIss/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/P_T2tvWXIss?rel=0\";s:3:\"url\";s:84:\"http://localhost:8000/de/videospiele/elden-ring-eine-krone-die-mehr-als-mut-verlangt\";}i:1;a:6:{s:5:\"title\";s:46:\"Sinners – Wenn der Blues die Dunkelheit ruft\";s:8:\"subtitle\";s:5:\"Filme\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/AL4BwibdBOc/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/AL4BwibdBOc?rel=0\";s:3:\"url\";s:73:\"http://localhost:8000/de/filme/sinners-wenn-der-blues-die-dunkelheit-ruft\";}i:2;a:6:{s:5:\"title\";s:68:\"Clair Obscur: Expedition 33 — Schönheit im Schatten des Abschieds\";s:8:\"subtitle\";s:11:\"Videospiele\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/2VaLOc1FpSo/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/2VaLOc1FpSo?rel=0\";s:3:\"url\";s:99:\"http://localhost:8000/de/videospiele/clair-obscur-expedition-33-schonheit-im-schatten-des-abschieds\";}i:3;a:6:{s:5:\"title\";s:49:\"Wednesday – Eine Schule für schlechte Manieren\";s:8:\"subtitle\";s:6:\"Serien\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/Neu2e86sNeI/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/Neu2e86sNeI?rel=0\";s:3:\"url\";s:76:\"http://localhost:8000/de/serien/wednesday-eine-schule-fur-schlechte-manieren\";}i:4;a:6:{s:5:\"title\";s:50:\"Fallout – Der Weltuntergang kennt keine Manieren\";s:8:\"subtitle\";s:6:\"Serien\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/mKX7WIhDUEQ/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/mKX7WIhDUEQ?rel=0\";s:3:\"url\";s:78:\"http://localhost:8000/de/serien/fallout-der-weltuntergang-kennt-keine-manieren\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318617),
('laravel-cache-cinehub:home:en-us:popular', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:18:{s:2:\"id\";i:2;s:5:\"title\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:7:\"excerpt\";s:223:\"In Stranger Things, the disappearance of a young boy exposes the secrets beneath the respectable town of Hawkins. Amid supernatural horrors and dangerous experiments, a handful of unlikely heroes discovers that friendshi...\";s:4:\"type\";s:8:\"tv-shows\";s:10:\"type_label\";s:8:\"TV Shows\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/b9EkMc79ZSU/hqdefault.jpg\";s:9:\"image_alt\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/b9EkMc79ZSU?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"7\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:82:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins/like\";s:3:\"url\";s:77:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins\";}i:1;a:18:{s:2:\"id\";i:4;s:5:\"title\";s:50:\"Clair Obscur: Expedition 33 — A Requiem in Paint\";s:7:\"excerpt\";s:204:\"In Clair Obscur: Expedition 33, beauty keeps company with mortality. This French-inspired RPG transforms a desperate journey into a refined, unsettling meditation on grief, courage, and the price of hope.\";s:4:\"type\";s:5:\"games\";s:10:\"type_label\";s:5:\"Games\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/2VaLOc1FpSo/hqdefault.jpg\";s:9:\"image_alt\";s:50:\"Clair Obscur: Expedition 33 — A Requiem in Paint\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/2VaLOc1FpSo?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"2\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:81:\"http://localhost:8000/en/games/clair-obscur-expedition-33-a-requiem-in-paint/like\";s:3:\"url\";s:76:\"http://localhost:8000/en/games/clair-obscur-expedition-33-a-requiem-in-paint\";}i:2;a:18:{s:2:\"id\";i:6;s:5:\"title\";s:47:\"Sinners — When the Blues Invites the Darkness\";s:7:\"excerpt\";s:0:\"\";s:4:\"type\";s:6:\"movies\";s:10:\"type_label\";s:6:\"Movies\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/bKGxHflevuk/hqdefault.jpg\";s:9:\"image_alt\";s:47:\"Sinners — When the Blues Invites the Darkness\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/bKGxHflevuk?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"1\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:80:\"http://localhost:8000/en/movies/sinners-when-the-blues-invites-the-darkness/like\";s:3:\"url\";s:75:\"http://localhost:8000/en/movies/sinners-when-the-blues-invites-the-darkness\";}i:3;a:18:{s:2:\"id\";i:5;s:5:\"title\";s:52:\"Fallout — The End of the World, Not of Bad Manners\";s:7:\"excerpt\";s:223:\"My dear reader, imagine civilization reduced to rubble while its most ambitious citizens remain determined to give orders. In Fallout, immaculate vaults conceal rather untidy secrets, and the wasteland offers danger, dar...\";s:4:\"type\";s:8:\"tv-shows\";s:10:\"type_label\";s:8:\"TV Shows\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/0kQ8i2FpRDk/hqdefault.jpg\";s:9:\"image_alt\";s:52:\"Fallout — The End of the World, Not of Bad Manners\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/0kQ8i2FpRDk?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"1\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:86:\"http://localhost:8000/en/tv-shows/fallout-the-end-of-the-world-not-of-bad-manners/like\";s:3:\"url\";s:81:\"http://localhost:8000/en/tv-shows/fallout-the-end-of-the-world-not-of-bad-manners\";}i:4;a:18:{s:2:\"id\";i:8;s:5:\"title\";s:53:\"Wednesday — A Most Unwelcome Lesson in Good Manners\";s:7:\"excerpt\";s:223:\"My dear reader, at Nevermore Academy, monsters attend class and Wednesday Addams considers a smile an unfortunate weakness. Between sinister murders, family secrets and an unlikely friendship, Netflix\'s gothic mystery ma...\";s:4:\"type\";s:8:\"tv-shows\";s:10:\"type_label\";s:8:\"TV Shows\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/Di310WS8zLk/hqdefault.jpg\";s:9:\"image_alt\";s:53:\"Wednesday — A Most Unwelcome Lesson in Good Manners\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/Di310WS8zLk?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:5:\"en-us\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:88:\"http://localhost:8000/en/tv-shows/wednesday-a-most-unwelcome-lesson-in-good-manners/like\";s:3:\"url\";s:83:\"http://localhost:8000/en/tv-shows/wednesday-a-most-unwelcome-lesson-in-good-manners\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318643),
('laravel-cache-cinehub:home:en-us:trending', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:6:{s:5:\"title\";s:47:\"Stranger Things — The Secrets Beneath Hawkins\";s:8:\"subtitle\";s:8:\"TV Shows\";s:5:\"views\";s:1:\"2\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/b9EkMc79ZSU/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/b9EkMc79ZSU?rel=0\";s:3:\"url\";s:77:\"http://localhost:8000/en/tv-shows/stranger-things-the-secrets-beneath-hawkins\";}i:1;a:6:{s:5:\"title\";s:50:\"Clair Obscur: Expedition 33 — A Requiem in Paint\";s:8:\"subtitle\";s:5:\"Games\";s:5:\"views\";s:1:\"2\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/2VaLOc1FpSo/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/2VaLOc1FpSo?rel=0\";s:3:\"url\";s:76:\"http://localhost:8000/en/games/clair-obscur-expedition-33-a-requiem-in-paint\";}i:2;a:6:{s:5:\"title\";s:47:\"Sinners — When the Blues Invites the Darkness\";s:8:\"subtitle\";s:6:\"Movies\";s:5:\"views\";s:1:\"1\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/bKGxHflevuk/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/bKGxHflevuk?rel=0\";s:3:\"url\";s:75:\"http://localhost:8000/en/movies/sinners-when-the-blues-invites-the-darkness\";}i:3;a:6:{s:5:\"title\";s:52:\"Fallout — The End of the World, Not of Bad Manners\";s:8:\"subtitle\";s:8:\"TV Shows\";s:5:\"views\";s:1:\"1\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/0kQ8i2FpRDk/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/0kQ8i2FpRDk?rel=0\";s:3:\"url\";s:81:\"http://localhost:8000/en/tv-shows/fallout-the-end-of-the-world-not-of-bad-manners\";}i:4;a:6:{s:5:\"title\";s:46:\"Elden Ring — A Crown Worth More Than Courage\";s:8:\"subtitle\";s:5:\"Games\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/E3Huy2cdih0/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/E3Huy2cdih0?rel=0\";s:3:\"url\";s:73:\"http://localhost:8000/en/games/elden-ring-a-crown-worth-more-than-courage\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318643),
('laravel-cache-cinehub:home:fr:popular', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:18:{s:2:\"id\";i:4;s:5:\"title\";s:63:\"Clair Obscur: Expedition 33 — La splendeur au bord de l\'oubli\";s:7:\"excerpt\";s:210:\"Sous les fastes d\'un monde inspiré de la Belle Époque, Clair Obscur: Expedition 33 déploie un RPG où chaque année prononce une sentence. Une aventure somptueuse, dont la grâce n\'efface jamais la cruauté.\";s:4:\"type\";s:10:\"jeux-video\";s:10:\"type_label\";s:11:\"Jeux vidéo\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/Or5_oQuEIqg/hqdefault.jpg\";s:9:\"image_alt\";s:63:\"Clair Obscur: Expedition 33 — La splendeur au bord de l\'oubli\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/Or5_oQuEIqg?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"1\";s:6:\"locale\";s:2:\"fr\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:98:\"http://localhost:8000/fr/jeux-video/clair-obscur-expedition-33-la-splendeur-au-bord-de-loubli/like\";s:3:\"url\";s:93:\"http://localhost:8000/fr/jeux-video/clair-obscur-expedition-33-la-splendeur-au-bord-de-loubli\";}i:1;a:18:{s:2:\"id\";i:6;s:5:\"title\";s:48:\"Sinners — Quand le blues invite les ténèbres\";s:7:\"excerpt\";s:229:\"Cher lecteur, deux frères reviennent au Mississippi pour offrir à leur communauté une nuit de musique et de liberté. Mais dans Sinners, le blues réveille bien davantage que les souvenirs, et certains invités feraient mie...\";s:4:\"type\";s:5:\"films\";s:10:\"type_label\";s:5:\"Films\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/rOulkHoq2w8/hqdefault.jpg\";s:9:\"image_alt\";s:48:\"Sinners — Quand le blues invite les ténèbres\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/rOulkHoq2w8?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"fr\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:78:\"http://localhost:8000/fr/films/sinners-quand-le-blues-invite-les-tenebres/like\";s:3:\"url\";s:73:\"http://localhost:8000/fr/films/sinners-quand-le-blues-invite-les-tenebres\";}i:2;a:18:{s:2:\"id\";i:8;s:5:\"title\";s:52:\"Mercredi — L\'élégance délicieuse des ténèbres\";s:7:\"excerpt\";s:229:\"Cher lecteur, à l\'Académie Nevermore, les monstres étudient et Mercredi Addams considère le sourire comme une regrettable faiblesse. Meurtres, secrets de famille et amitié improbable : la série Netflix transforme les omb...\";s:4:\"type\";s:9:\"series-tv\";s:10:\"type_label\";s:10:\"Séries TV\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/X7UoGHx7MDI/hqdefault.jpg\";s:9:\"image_alt\";s:52:\"Mercredi — L\'élégance délicieuse des ténèbres\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/X7UoGHx7MDI?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"fr\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:82:\"http://localhost:8000/fr/series-tv/mercredi-lelegance-delicieuse-des-tenebres/like\";s:3:\"url\";s:77:\"http://localhost:8000/fr/series-tv/mercredi-lelegance-delicieuse-des-tenebres\";}i:3;a:18:{s:2:\"id\";i:5;s:5:\"title\";s:51:\"Fallout — L\'apocalypse et les mauvaises manières\";s:7:\"excerpt\";s:227:\"Cher lecteur, la fin du monde aurait pu rendre les hommes modestes. Quelle naïveté ! Dans Fallout, des abris impeccables cachent de fort vilaines ambitions, tandis que les terres irradiées offrent monstres, mystères et u...\";s:4:\"type\";s:9:\"series-tv\";s:10:\"type_label\";s:10:\"Séries TV\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/cNf2kCM5Y0E/hqdefault.jpg\";s:9:\"image_alt\";s:51:\"Fallout — L\'apocalypse et les mauvaises manières\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/cNf2kCM5Y0E?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"fr\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:85:\"http://localhost:8000/fr/series-tv/fallout-lapocalypse-et-les-mauvaises-manieres/like\";s:3:\"url\";s:80:\"http://localhost:8000/fr/series-tv/fallout-lapocalypse-et-les-mauvaises-manieres\";}i:4;a:18:{s:2:\"id\";i:7;s:5:\"title\";s:54:\"Elden Ring — Une couronne au prix de mille épreuves\";s:7:\"excerpt\";s:230:\"Cher lecteur, méfiez-vous des couronnes proposées trop facilement. Dans Elden Ring, les châteaux tombent en ruine, les demi-dieux se disputent les décombres et chaque détour promet un secret. Le pouvoir paraît fort sédui...\";s:4:\"type\";s:10:\"jeux-video\";s:10:\"type_label\";s:11:\"Jeux vidéo\";s:12:\"content_kind\";s:8:\"trailers\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/VngRHfxKQ3I/hqdefault.jpg\";s:9:\"image_alt\";s:54:\"Elden Ring — Une couronne au prix de mille épreuves\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/VngRHfxKQ3I?rel=0\";s:8:\"duration\";s:5:\"--:--\";s:11:\"likes_count\";s:1:\"0\";s:14:\"comments_count\";s:1:\"0\";s:11:\"views_count\";s:1:\"0\";s:6:\"locale\";s:2:\"fr\";s:16:\"publication_type\";s:8:\"standard\";s:11:\"blog_genres\";a:0:{}s:8:\"like_url\";s:90:\"http://localhost:8000/fr/jeux-video/elden-ring-une-couronne-au-prix-de-mille-epreuves/like\";s:3:\"url\";s:85:\"http://localhost:8000/fr/jeux-video/elden-ring-une-couronne-au-prix-de-mille-epreuves\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318648),
('laravel-cache-cinehub:home:fr:trending', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:6:{s:5:\"title\";s:63:\"Clair Obscur: Expedition 33 — La splendeur au bord de l\'oubli\";s:8:\"subtitle\";s:11:\"Jeux vidéo\";s:5:\"views\";s:1:\"1\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/Or5_oQuEIqg/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/Or5_oQuEIqg?rel=0\";s:3:\"url\";s:93:\"http://localhost:8000/fr/jeux-video/clair-obscur-expedition-33-la-splendeur-au-bord-de-loubli\";}i:1;a:6:{s:5:\"title\";s:54:\"Elden Ring — Une couronne au prix de mille épreuves\";s:8:\"subtitle\";s:11:\"Jeux vidéo\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/VngRHfxKQ3I/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/VngRHfxKQ3I?rel=0\";s:3:\"url\";s:85:\"http://localhost:8000/fr/jeux-video/elden-ring-une-couronne-au-prix-de-mille-epreuves\";}i:2;a:6:{s:5:\"title\";s:48:\"Sinners — Quand le blues invite les ténèbres\";s:8:\"subtitle\";s:5:\"Films\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/rOulkHoq2w8/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/rOulkHoq2w8?rel=0\";s:3:\"url\";s:73:\"http://localhost:8000/fr/films/sinners-quand-le-blues-invite-les-tenebres\";}i:3;a:6:{s:5:\"title\";s:52:\"Mercredi — L\'élégance délicieuse des ténèbres\";s:8:\"subtitle\";s:10:\"Séries TV\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/X7UoGHx7MDI/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/X7UoGHx7MDI?rel=0\";s:3:\"url\";s:77:\"http://localhost:8000/fr/series-tv/mercredi-lelegance-delicieuse-des-tenebres\";}i:4;a:6:{s:5:\"title\";s:51:\"Fallout — L\'apocalypse et les mauvaises manières\";s:8:\"subtitle\";s:10:\"Séries TV\";s:5:\"views\";s:1:\"0\";s:9:\"image_url\";s:48:\"https://i.ytimg.com/vi/cNf2kCM5Y0E/hqdefault.jpg\";s:15:\"video_embed_url\";s:56:\"https://www.youtube-nocookie.com/embed/cNf2kCM5Y0E?rel=0\";s:3:\"url\";s:80:\"http://localhost:8000/fr/series-tv/fallout-lapocalypse-et-les-mauvaises-manieres\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1794318648);

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
(2, 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 2, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 6, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 8, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 9, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 10, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 11, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 12, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 13, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 15, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 16, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 17, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 18, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 20, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 21, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 25, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 32, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(2, 33, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 2, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 6, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 8, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 9, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 10, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 11, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 12, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(3, 13, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 15, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 16, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 17, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 18, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 20, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 21, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 25, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 32, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(3, 33, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(4, 1, '2026-11-03 23:13:23', '2026-11-03 23:13:23'),
(4, 2, '2026-11-03 23:14:05', '2026-11-03 23:14:05'),
(4, 3, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(4, 4, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(4, 6, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(4, 8, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 9, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 22, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 23, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 24, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 26, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 28, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 29, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 30, '2026-11-09 21:13:50', '2026-11-09 21:13:50'),
(4, 31, '2026-11-09 21:13:50', '2026-11-09 21:13:50');

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
(2, 6, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(3, 2, '2026-11-05 23:27:11', '2026-11-05 23:27:11'),
(3, 5, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(3, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(4, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(4, 3, '2026-11-09 21:32:35', '2026-11-09 21:32:35'),
(4, 4, '2026-11-09 23:21:45', '2026-11-09 23:21:45'),
(4, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17');

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
(3, 'RPG', 'rpg', 1, 0, '2026-11-03 23:14:25', '2026-11-09 23:41:02'),
(4, 'Open World', 'open-world', 1, 0, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(6, 'Fantasy', 'fantasy', 1, 0, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(8, 'Science Fiction', 'science-fiction', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(9, 'Horror', 'horror', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(10, 'Mystery', 'mystery', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(11, 'Drama', 'drama', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(12, 'Thriller', 'thriller', 1, 0, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(13, 'Animation', 'animation', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(15, 'Comedy', 'comedy', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(16, 'Crime', 'crime', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(17, 'Documentary', 'documentary', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(18, 'Family', 'family', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(20, 'History', 'history', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(21, 'Music', 'music', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(22, 'Platformer', 'platformer', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(23, 'Puzzle', 'puzzle', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(24, 'Racing', 'racing', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(25, 'Romance', 'romance', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(26, 'Rhythm', 'rhythm', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(28, 'Simulation', 'simulation', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(29, 'Sports', 'sports', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(30, 'Strategy', 'strategy', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(31, 'Survival', 'survival', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(32, 'War', 'war', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(33, 'Western', 'western', 1, 0, '2026-11-09 21:07:35', '2026-11-09 21:07:35');

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
(10, 3, 'en-us', 'RPG', 'rpg', 1, '2026-11-03 23:14:25', '2026-11-09 23:41:02'),
(11, 3, 'en-gb', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(12, 3, 'en-au', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(13, 3, 'en-ca', 'Action RPG', 'action-rpg', 1, '2026-11-03 23:14:25', '2026-11-03 23:14:25'),
(14, 4, 'en-us', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(15, 4, 'en-gb', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(16, 4, 'en-au', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(17, 4, 'en-ca', 'Open World', 'open-world', 1, '2026-11-03 23:14:40', '2026-11-03 23:14:40'),
(22, 6, 'en-us', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(23, 6, 'en-gb', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(24, 6, 'en-au', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(25, 6, 'en-ca', 'Fantasy', 'fantasy', 1, '2026-11-03 23:15:11', '2026-11-03 23:15:11'),
(30, 1, 'fr', 'Aventure', 'aventure', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(31, 1, 'de', 'Abenteuer', 'abenteuer', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(32, 2, 'fr', 'Action', 'action', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(33, 2, 'de', 'Action', 'action', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(34, 3, 'fr', 'RPG', 'rpg', 1, '2026-11-05 20:47:14', '2026-11-09 23:41:02'),
(35, 3, 'de', 'RPG', 'rpg', 1, '2026-11-05 20:47:14', '2026-11-09 23:41:02'),
(36, 4, 'fr', 'Monde ouvert', 'monde-ouvert', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(37, 4, 'de', 'Offene Spielwelt', 'offene-spielwelt', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(40, 6, 'fr', 'Fantasy', 'fantasy', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(41, 6, 'de', 'Fantasy', 'fantasy', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
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
(58, 12, 'de', 'Thriller', 'thriller', 1, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(59, 13, 'en-us', 'Animation', 'animation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(61, 15, 'en-us', 'Comedy', 'comedy', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(62, 16, 'en-us', 'Crime', 'crime', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(63, 17, 'en-us', 'Documentary', 'documentary', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(64, 18, 'en-us', 'Family', 'family', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(66, 20, 'en-us', 'History', 'history', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(67, 21, 'en-us', 'Music', 'music', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(68, 22, 'en-us', 'Platformer', 'platformer', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(69, 23, 'en-us', 'Puzzle', 'puzzle', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(70, 24, 'en-us', 'Racing', 'racing', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(71, 26, 'en-us', 'Rhythm', 'rhythm', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(72, 25, 'en-us', 'Romance', 'romance', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(74, 28, 'en-us', 'Simulation', 'simulation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(75, 29, 'en-us', 'Sports', 'sports', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(76, 30, 'en-us', 'Strategy', 'strategy', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(77, 31, 'en-us', 'Survival', 'survival', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(78, 32, 'en-us', 'War', 'war', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(79, 33, 'en-us', 'Western', 'western', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(90, 13, 'fr', 'Animation', 'animation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(92, 15, 'fr', 'Comédie', 'comedie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(93, 16, 'fr', 'Policier', 'policier', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(94, 17, 'fr', 'Documentaire', 'documentaire', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(95, 18, 'fr', 'Famille', 'famille', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(97, 20, 'fr', 'Histoire', 'histoire', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(98, 21, 'fr', 'Musique', 'musique', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(99, 22, 'fr', 'Plateforme', 'plateforme', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(100, 23, 'fr', 'Réflexion', 'reflexion', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(101, 24, 'fr', 'Course', 'course', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(102, 26, 'fr', 'Rythme', 'rythme', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(103, 25, 'fr', 'Romance', 'romance', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(105, 28, 'fr', 'Simulation', 'simulation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(106, 29, 'fr', 'Sport', 'sport', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(107, 30, 'fr', 'Stratégie', 'strategie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(108, 31, 'fr', 'Survie', 'survie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(109, 32, 'fr', 'Guerre', 'guerre', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(110, 33, 'fr', 'Western', 'western', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(121, 13, 'de', 'Animation', 'animation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(123, 15, 'de', 'Komödie', 'komoedie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(124, 16, 'de', 'Krimi', 'krimi', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(125, 17, 'de', 'Dokumentation', 'dokumentation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(126, 18, 'de', 'Familie', 'familie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(128, 20, 'de', 'Historie', 'historie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(129, 21, 'de', 'Musik', 'musik', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(130, 22, 'de', 'Plattformspiel', 'plattformspiel', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(131, 23, 'de', 'Rätsel', 'raetsel', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(132, 24, 'de', 'Rennspiel', 'rennspiel', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(133, 26, 'de', 'Rhythmus', 'rhythmus', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(134, 25, 'de', 'Romantik', 'romantik', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(136, 28, 'de', 'Simulation', 'simulation', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(137, 29, 'de', 'Sport', 'sport', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(138, 30, 'de', 'Strategie', 'strategie', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(139, 31, 'de', 'Überleben', 'ueberleben', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(140, 32, 'de', 'Krieg', 'krieg', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35'),
(141, 33, 'de', 'Western', 'western', 1, '2026-11-09 21:07:35', '2026-11-09 21:07:35');

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
(1, 4, '2026-11-10 00:41:03', '2026-11-10 00:41:03'),
(1, 5, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(1, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(2, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(2, 3, '2026-11-09 21:32:35', '2026-11-09 21:32:35'),
(2, 4, '2026-11-09 23:21:45', '2026-11-09 23:21:45'),
(2, 5, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(2, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(3, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(3, 4, '2026-11-09 23:42:23', '2026-11-09 23:42:23'),
(3, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(4, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(4, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(6, 1, '2026-11-03 23:26:29', '2026-11-03 23:26:29'),
(6, 4, '2026-11-10 00:41:03', '2026-11-10 00:41:03'),
(6, 7, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(6, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(8, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(8, 5, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(9, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(9, 6, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(9, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(10, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(10, 6, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(10, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(11, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(11, 5, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(11, 6, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(12, 2, '2026-11-05 20:47:14', '2026-11-05 20:47:14'),
(12, 6, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(15, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(16, 8, '2026-11-10 16:48:06', '2026-11-10 16:48:06');

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
(2, 2, 'en-us', 'trailer', 'youtube', 'b9EkMc79ZSU', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=b9EkMc79ZSU', NULL, 1, 0, '2026-11-05 23:26:06', '2026-11-05 23:26:06'),
(3, 3, 'en-us', 'trailer', 'youtube', '0s_hq4Bjz70', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=0s_hq4Bjz70&list=RD0s_hq4Bjz70&start_radio=1', NULL, 1, 0, '2026-11-09 21:32:35', '2026-11-09 21:32:35'),
(4, 3, 'fr', 'trailer', 'youtube', '0s_hq4Bjz70', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=0s_hq4Bjz70&list=RD0s_hq4Bjz70&start_radio=1', NULL, 1, 0, '2026-11-09 21:54:51', '2026-11-09 21:54:51'),
(5, 3, 'de', 'trailer', 'youtube', '0s_hq4Bjz70', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=0s_hq4Bjz70&list=RD0s_hq4Bjz70&start_radio=1', NULL, 1, 0, '2026-11-09 21:54:51', '2026-11-09 21:54:51'),
(6, 4, 'en-us', 'trailer', 'youtube', '2VaLOc1FpSo', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=2VaLOc1FpSo', NULL, 1, 0, '2026-11-09 23:21:45', '2026-11-09 23:21:45'),
(7, 4, 'fr', 'trailer', 'youtube', 'Or5_oQuEIqg', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Or5_oQuEIqg', NULL, 1, 0, '2026-11-09 23:21:45', '2026-11-10 16:28:47'),
(8, 4, 'de', 'trailer', 'youtube', '2VaLOc1FpSo', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=2VaLOc1FpSo', NULL, 1, 0, '2026-11-09 23:21:45', '2026-11-09 23:21:45'),
(9, 5, 'en-us', 'trailer', 'youtube', '0kQ8i2FpRDk', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=0kQ8i2FpRDk', NULL, 1, 0, '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(10, 5, 'fr', 'trailer', 'youtube', 'cNf2kCM5Y0E', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=cNf2kCM5Y0E', NULL, 1, 0, '2026-11-10 15:04:17', '2026-11-10 16:26:32'),
(11, 5, 'de', 'trailer', 'youtube', 'mKX7WIhDUEQ', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=mKX7WIhDUEQ', NULL, 1, 0, '2026-11-10 15:04:17', '2026-11-10 16:26:32'),
(12, 6, 'en-us', 'trailer', 'youtube', 'bKGxHflevuk', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=bKGxHflevuk', NULL, 1, 0, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(13, 6, 'fr', 'trailer', 'youtube', 'rOulkHoq2w8', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=rOulkHoq2w8', NULL, 1, 0, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(14, 6, 'de', 'trailer', 'youtube', 'AL4BwibdBOc', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=AL4BwibdBOc', NULL, 1, 0, '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(15, 7, 'en-us', 'trailer', 'youtube', 'E3Huy2cdih0', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=E3Huy2cdih0', NULL, 1, 0, '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(16, 7, 'fr', 'trailer', 'youtube', 'VngRHfxKQ3I', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=VngRHfxKQ3I', NULL, 1, 0, '2026-11-10 16:18:17', '2026-11-10 16:24:18'),
(17, 7, 'de', 'trailer', 'youtube', 'P_T2tvWXIss', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=P_T2tvWXIss', NULL, 1, 0, '2026-11-10 16:18:17', '2026-11-10 16:24:18'),
(18, 8, 'en-us', 'trailer', 'youtube', 'Di310WS8zLk', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Di310WS8zLk', NULL, 1, 0, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(19, 8, 'fr', 'trailer', 'youtube', 'X7UoGHx7MDI', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=X7UoGHx7MDI', NULL, 1, 0, '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(20, 8, 'de', 'trailer', 'youtube', 'Neu2e86sNeI', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Neu2e86sNeI', NULL, 1, 0, '2026-11-10 16:48:06', '2026-11-10 16:48:06');

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
(17, 'f0ae9285-084b-4f24-af17-6e4b73571f88', 2, 'en-us', '9e11e1835aa41e24facfc2cf032574b775e3034a44cc71b6ee86ddc5667ff0f4', 0, 0, 1, '2026-11-06 21:23:19', '2026-11-06 22:56:34'),
(18, 'd6497ed6-cd88-4c9a-a9e7-e27e136ff134', 2, 'en-us', 'dddfad6b31e231e9db291c2a1d44065a0c4819100fe4588e8a6c794a1c6ad1c5', 0, 0, 1, '2026-11-09 21:23:13', '2026-11-09 21:30:33'),
(19, 'f3b27f6a-bf32-47da-b1d8-6f82ddf19ac0', 3, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:32:54', '2026-11-09 21:43:52'),
(20, '64c3e162-2fc3-46c7-b42e-3b78dbbe614c', 3, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:43:53', '2026-11-09 21:44:11'),
(21, '39c949bc-48d5-425c-987e-7ceaaa8bb71f', 2, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:44:13', '2026-11-09 21:44:49'),
(22, 'fa5c5f5c-c704-4fa3-9a4b-13bc6051febf', 2, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:44:49', '2026-11-09 21:52:07'),
(23, 'fdd8c560-97c1-49a8-9dc2-ba6c03b39656', 2, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:52:08', '2026-11-09 21:54:53'),
(24, '55f8f1b9-7627-4ba4-9ed0-3c61c3545b12', 2, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', 0, 0, 1, '2026-11-09 21:54:54', '2026-11-09 21:55:02'),
(25, '0accc681-26fa-4aa6-87eb-4a8d325d6775', 3, 'fr', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 1, '2026-11-09 21:55:22', '2026-11-09 21:55:43'),
(26, '12ea7f41-8d0a-430c-8514-8c3efe5b7164', 3, 'de', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 1, '2026-11-09 21:55:54', '2026-11-09 21:56:29'),
(27, '9ea0a4f8-7919-41e3-913b-c9d2eae7e382', 3, 'fr', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 1, '2026-11-09 21:56:36', '2026-11-09 21:56:41'),
(28, 'f1de2112-c269-42f9-ac50-d1a265803825', 3, 'fr', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 0, '2026-11-09 22:01:42', '2026-11-09 22:01:47'),
(29, 'cab9e7f9-6633-4585-8479-f6aed8f101a3', 3, 'fr', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 2, 0, 1, '2026-11-09 22:01:47', '2026-11-09 22:01:55'),
(30, '70a12939-bf07-4181-8dbd-5ef8b56a7739', 3, 'de', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 1, '2026-11-09 22:02:00', '2026-11-09 22:10:06'),
(31, 'd849be29-2afb-47f9-8381-f53ad17a4cd1', 3, 'de', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', 0, 0, 1, '2026-11-09 22:10:06', '2026-11-09 23:17:41'),
(32, '70fb209b-3262-481d-98fa-0e81cec9f9ff', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:22:01', '2026-11-09 23:22:12'),
(33, '9e8adfff-d5dd-4c86-8402-bbf493171d30', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:22:12', '2026-11-09 23:22:22'),
(34, '253f3906-35c1-4f90-b712-2951a997b9de', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:22:31', '2026-11-09 23:25:36'),
(35, '9ee5381c-ceb7-40a3-82aa-3349b453273a', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:25:40', '2026-11-09 23:25:53'),
(36, 'd9e56752-3663-4469-929e-90b7863af63f', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:30:15', '2026-11-09 23:38:07'),
(37, 'f66f9193-4e6e-437c-b7b0-a4402bd786fe', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-09 23:38:09', '2026-11-09 23:43:25'),
(38, '8b3dd603-b3e3-4bf4-8d61-0f32543b5eb2', 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-10 00:01:28', '2026-11-10 00:01:33'),
(39, '152de030-1d73-412a-9d96-70d50c73be74', 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 1, 1, 1, '2026-11-10 00:01:33', '2026-11-10 00:01:36'),
(40, 'c9dc789e-0bf0-460e-8e96-81176df7592f', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 2, 2, 1, '2026-11-10 00:04:00', '2026-11-10 00:04:03'),
(41, '4744c409-aab8-4302-9714-515f41822d60', 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 1, 0, 1, '2026-11-10 00:41:43', '2026-11-10 00:48:48'),
(42, '079c7480-3fd5-4328-92e3-65be35a18ded', 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-10 00:48:59', '2026-11-10 00:52:03'),
(43, '51c25194-4047-48d5-9baa-339516be7dcf', 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-10 00:52:03', '2026-11-10 00:52:11'),
(44, 'b8a9c04b-7a44-4f69-bdc2-07ec1f773bf9', 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', 0, 0, 1, '2026-11-10 00:52:11', '2026-11-10 00:52:59'),
(45, '8a830c94-0bbb-43dc-bd04-532fbd368c1a', 6, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', 0, 0, 1, '2026-11-10 15:55:34', '2026-11-10 16:18:23'),
(46, '3a4d7aaf-f609-48eb-852d-f9cc78d44ed8', 5, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', 0, 0, 1, '2026-11-10 16:24:33', '2026-11-10 16:26:35'),
(47, 'f438f1e2-4fac-4f62-8b4f-1bdeb9a78c27', 4, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', 0, 0, 1, '2026-11-10 16:26:41', '2026-11-10 16:28:51'),
(48, '1fd121bb-2b8f-4f28-9a7e-c8380ee3b2f6', 7, 'de', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', 0, 0, 1, '2026-11-10 16:38:23', '2026-11-10 16:38:45');

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
(2, 1, 'published', 'standard', NULL, 0, '2026-11-05 23:26:00', '2026-11-05 23:26:06', '2026-11-05 23:27:11', NULL),
(3, 1, 'published', 'standard', NULL, 0, '2026-11-09 21:32:00', '2026-11-09 21:32:35', '2026-11-09 23:18:02', '2026-11-09 23:18:02'),
(4, 1, 'published', 'standard', NULL, 0, '2026-11-09 23:18:00', '2026-11-09 23:21:45', '2026-11-09 23:21:45', NULL),
(5, 1, 'published', 'standard', NULL, 0, '2026-11-10 14:54:00', '2026-11-10 15:04:17', '2026-11-10 15:04:17', NULL),
(6, 1, 'published', 'standard', NULL, 0, '2026-11-10 15:41:00', '2026-11-10 15:52:37', '2026-11-10 15:52:37', NULL),
(7, 1, 'published', 'standard', NULL, 0, '2026-11-10 16:10:00', '2026-11-10 16:18:17', '2026-11-10 16:18:17', NULL),
(8, 1, 'published', 'standard', NULL, 0, '2026-11-10 16:41:00', '2026-11-10 16:48:06', '2026-11-10 16:48:06', NULL);

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
(2, 2, 'en-us', 1, 0, 'stranger-things-the-secrets-beneath-hawkins', 'Stranger Things — The Secrets Beneath Hawkins', '<p>In <em>Stranger Things</em>, the disappearance of a young boy exposes the secrets beneath the respectable town of Hawkins. Amid supernatural horrors and dangerous experiments, a handful of unlikely heroes discovers that friendship may be the finest form of courage.</p>', '<p>In 1983, the peaceful town of Hawkins, Indiana, is shaken by the mysterious disappearance of young Will Byers. As his friends search for him, they encounter Eleven, a girl with extraordinary abilities and a troubling past.</p><p>Meanwhile, Will\'s mother, Joyce, and Police Chief Jim Hopper uncover disturbing connections to a secret government laboratory. What begins as a desperate search soon exposes supernatural dangers, hidden experiments, and a terrifying mystery that threatens everything the townspeople hold dear.</p>', '<p>There is something particularly unsettling about a town that appears to have nothing to hide. Neatly kept homes, familiar neighbors, and the reassuring predictability of daily life can persuade even the most suspicious observer that all is well. Hawkins, Indiana, possesses precisely this sort of respectable appearance. Its inhabitants might have continued enjoying their modest peace had certain individuals not developed the unfortunate habit of conducting dangerous experiments beyond public scrutiny.</p><p><strong>Stranger Things</strong>, created by the Duffer Brothers, begins with the disappearance of Will Byers, a young boy whose absence disturbs far more than the tranquility of his hometown. What follows is a remarkable combination of supernatural mystery, science fiction, horror, and the complicated business of growing up.</p><p>Yet the series possesses a quality far more valuable than its collection of frightening creatures: it understands the extraordinary importance of ordinary human attachments.</p><h3>The Quiet Courage of Unlikely Heroes</h3><p>At the heart of <em>Stranger Things</em> lies a group of children whose loyalty proves considerably stronger than their understanding of the dangers surrounding them.</p><p>Mike, Dustin, Lucas, and Will inhabit a world of bicycles, games, schoolyard difficulties, and friendships that occasionally suffer from the familiar inconveniences of pride and jealousy. Their affection is neither flawless nor sentimental. Disagreements arise, trust is tested, and youthful confidence sometimes leads to decisions that would alarm any sensible guardian.</p><p>Their friendship, however, gives the story its emotional foundation.</p><p>The arrival of Eleven introduces a particularly compelling contrast. Possessing extraordinary abilities but little experience of ordinary affection, she discovers that friendship offers something no laboratory could ever provide: the possibility of being valued as a person rather than an instrument.</p><p>Millie Bobby Brown brings considerable expressiveness to a character whose earliest appearances depend more upon silence and gesture than elaborate conversation. The young ensemble likewise makes the friendships believable, allowing the supernatural events to carry genuine emotional weight.</p><h3>When Respectability Conceals Something Dreadful</h3><p>Hawkins presents a rather discouraging portrait of institutional authority.</p><p>While families struggle to understand the extraordinary events unfolding around them, the local laboratory conceals activities whose consequences extend far beyond its walls. One might reasonably expect distinguished scientific minds to exercise a certain degree of caution. Evidently, such expectations are sometimes misplaced.</p><p>The series uses this contrast effectively. The adults entrusted with knowledge and authority are not necessarily those most deserving of confidence, while children dismissed for their improbable suspicions often demonstrate admirable determination.</p><p>Joyce Byers, portrayed by Winona Ryder, provides one of the story\'s strongest emotional performances. Her refusal to abandon hope, even when confronted with disbelief, gives the mystery a deeply personal urgency.</p><p>David Harbour\'s Jim Hopper offers a different kind of strength. Beneath his weary disposition lies a man whose grief and sense of duty gradually complicate his outwardly hardened character.</p><p>Together, these figures prevent the supernatural spectacle from overwhelming the human tragedy beneath it.</p><h3>The Art of Making Darkness Beautiful</h3><p>Few elements of <em>Stranger Things</em> are as immediately recognizable as its recreation of the 1980s.</p><p>The clothing, music, domestic interiors, and cultural references establish a period atmosphere without requiring every scene to announce its historical setting. The synthesizer-driven score contributes substantially to the unease, transforming familiar streets and ordinary buildings into places of considerable suspicion.</p><p>The supernatural horror is especially effective when the series allows uncertainty to do its work. A strange sound, an unexplained disturbance, or the suggestion of something concealed can prove more troubling than a creature displayed in full.</p><p>Nevertheless, the balance is not always maintained.</p><p>As the story expands across its seasons, the scale of its conflicts grows increasingly ambitious. This enlargement provides memorable spectacle, but some extended confrontations and separated storylines weaken the intimacy that made the original mystery so compelling.</p><p>There are occasions when Hawkins appears almost burdened by the importance of its own catastrophes.</p><p>The series is at its strongest when danger remains closely connected to the characters\' fears, loyalties, and personal sacrifices.</p><h3>A Story Greater Than Its Monsters</h3><p>What ultimately distinguishes <em>Stranger Things</em> is not the novelty of its supernatural ideas. Secret experiments, mysterious dimensions, and terrifying creatures have long occupied respectable places in imaginative fiction.</p><p>Its achievement lies in arranging these familiar elements around characters whose relationships give the dangers meaning.</p><p>The friendships evolve, families confront painful uncertainties, and the boundary between childhood and adulthood becomes increasingly difficult to preserve. Beneath the spectacle rests a persistent concern with loss, belonging, and the courage required to protect another person.</p><p><em>Stranger Things</em> occasionally allows its ambitions to exceed its narrative discipline. Yet its atmosphere, performances, and emotional sincerity remain substantial virtues.</p><p>Perhaps its most revealing observation is that extraordinary power does not necessarily belong to those who understand compassion, while courage may flourish among individuals possessing very little power at all.</p><p>And in Hawkins, where respectable institutions so often prove incapable of protecting the innocent, a handful of devoted friends may be the finest society the town has to offer.</p>', NULL, '<p>Will Byers is discovered alive in the Upside Down, a terrifying reflection of Hawkins connected to experiments conducted at the local laboratory. Joyce and Hopper rescue him, while Eleven apparently sacrifices herself to destroy the Demogorgon.</p><p>Yet the danger is far from over. Will remains affected by his experience, and later revelations expose the disturbing origins of Vecna, once known as Henry Creel and One.</p><p>The discovery that Eleven\'s past is intimately connected to these horrors gives the conflict a tragic dimension: the very powers capable of protecting Hawkins are also bound to its darkest secrets.</p>', 'Stranger Things — The Secrets Beneath Hawkins', 'Discover Stranger Things through an insightful review of its supernatural mysteries, unforgettable characters, and the secrets hidden beneath Hawkins.', '2026-11-05 23:26:06', '2026-11-06 14:53:14'),
(3, 3, 'en-us', 1, 0, 'middle-earth-shadow-of-mordor-vengeance-in-the-land-of-shadows', 'Middle-earth: Shadow of Mordor — Vengeance in the Land of Shadows', '<p>Excerpt</p>', '<p>Synopsis</p>', '<p>There are lands in which a gentleman might seek fortune, admiration, or perhaps a respectable afternoon\'s diversion. Mordor, regrettably, offers none of these comforts. Yet <em>Middle-earth: Shadow of Mordor</em> transforms this inhospitable kingdom into a fascinating stage for vengeance, ambition, and encounters with enemies who possess the rather inconvenient habit of remembering one\'s failures.</p><p>Developed by Monolith Productions, this action-adventure game follows Talion, a ranger whose fate becomes entangled with supernatural powers. His journey unfolds beneath the oppressive shadow of Sauron\'s dominion, where danger waits beyond ruined walls and every hostile gathering threatens to become a most unpleasant social occasion. The premise is familiar enough to admirers of dark fantasy, but the game distinguishes itself through the remarkable freedom it grants players to shape their own conflicts.</p><p>Its most distinguished achievement is undoubtedly the Nemesis System. Rather than treating enemies as disposable obstacles, the game allows certain Uruk captains to develop personal rivalries with Talion. They can rise through the ranks, acquire new strengths, and remember previous encounters. A defeat, therefore, becomes more than an unfortunate interruption. It may establish a rivalry that transforms an otherwise ordinary adversary into a memorable opponent. Few pleasures in Mordor rival the satisfaction of settling an old grievance, particularly when the offending party has grown insufferably confident.</p><p>Combat is another considerable attraction. Talion moves between swordplay, archery, stealth, and spectral abilities with impressive versatility. Successful counters and carefully timed attacks create an elegant rhythm amid the surrounding brutality. The spectacle can be exhilarating, especially when several opponents converge upon the unfortunate ranger. Nevertheless, the generous combat mechanics occasionally make him feel excessively powerful as new abilities become available. One suspects that even the proudest Uruk captain would object to such an unequal arrangement.</p><p>Stealth provides a welcome alternative to open confrontation. Approaching enemy strongholds through elevated positions, identifying vulnerable targets, and choosing the proper moment to strike reward patience rather than reckless enthusiasm. The game\'s exploration likewise offers opportunities for discovery, although Mordor\'s bleak landscapes can sometimes feel visually repetitive. Its atmosphere is convincing, but its limited variety of scenery may diminish the delight of prolonged wandering.</p><p>The narrative supplies a compelling reason for Talion\'s crusade, even if the central storyline seldom matches the unpredictability of the rivalries created by the Nemesis System. Supporting activities can also become repetitive, particularly when familiar objectives begin to outweigh genuine surprises. The distinction is important: the game\'s finest stories often emerge not from its scripted missions but from the consequences of decisions made during play.</p><p>Visually, the ruined fortifications, hostile wilderness, and threatening armies establish an appropriately grim interpretation of Tolkien\'s world. Sound effects and musical arrangements reinforce the sense of a territory governed by violence and fear. The atmosphere is rarely welcoming, which, considering the location, should hardly be counted as a failure of hospitality.</p><p>Ultimately, <em>Shadow of Mordor</em> deserves admiration not because every part of its design is flawless, but because its finest invention gives ordinary confrontations an extraordinary sense of consequence. The combat is satisfying, the atmosphere persuasive, and the Nemesis System remains its defining triumph. It is a dark and absorbing adventure in which revenge acquires personality, enemies develop reputations, and even defeat may become the beginning of a far more interesting story.</p><p>— <strong>Dear Mr. R.</strong></p>', NULL, '<p>Spoilers</p>', 'Middle-earth: Shadow of Mordor — Vengeance in the Land of Shadows', 'Excerpt', '2026-11-09 21:32:35', '2026-11-09 21:32:35'),
(4, 3, 'fr', 1, 0, 'title', 'Title', '<p>Excerpt</p>', '<p>Synopsis</p>', '<p>Review</p>', NULL, '<p>Spoilers</p>', 'Title', 'Excerpt', '2026-11-09 21:54:51', '2026-11-09 21:54:51'),
(5, 3, 'de', 1, 0, 'title', 'Title', '<p>Excerpt</p>', '<p>Synopsis</p>', '<p>Review</p>', NULL, '<p>Spoilers</p>', 'Title', 'Excerpt', '2026-11-09 21:54:51', '2026-11-09 21:54:51'),
(6, 4, 'en-us', 1, 1, 'clair-obscur-expedition-33-a-requiem-in-paint', 'Clair Obscur: Expedition 33 — A Requiem in Paint', '<p>In Clair Obscur: Expedition 33, beauty keeps company with mortality. This French-inspired RPG transforms a desperate journey into a refined, unsettling meditation on grief, courage, and the price of hope.</p>', '<p>In Lumière, the Paintress marks a descending number upon a distant monolith every year. Anyone who has reached that age disappears in an event known as the Gommage. When the number becomes 33, Gustave and his companions embark on a perilous expedition to confront her and end the ritual. Beyond their city lies a magnificent, hostile world shaped by strange creatures, forgotten journeys, and mysteries that challenge everything the expedition believes about its fate.</p>', '<p>There are worlds that invite admiration, and others that seem to demand a moment of silence before one enters. Clair Obscur: Expedition 33 belongs to the latter company. Developed by Sandfall Interactive, this role-playing game dresses an unusually cruel premise in the sumptuous manners of a French-inspired fantasy. In Lumière, the passage of time is not merely regrettable; it is fatal by decree. Every year, the Paintress writes a smaller number upon her monolith, and an entire generation vanishes. The expedition that gives the game its name thus begins not with a promise of glory, but with the rather less fashionable certainty of loss.</p><p>The first distinction is visual. Belle Époque elegance appears in architectural flourishes, graceful silhouettes, and a taste for ornament, yet the surrounding landscapes refuse to obey the laws of any respectable drawing room. Monumental ruins, impossible formations, and creatures of disturbing beauty make exploration feel like wandering through a gallery whose paintings have quietly escaped their frames. This is not decorative excess for its own sake. The contrast between refinement and decay gives the world a character that more conventional fantasy settings often seek, but seldom secure. Even its brighter corners seem aware that daylight is a temporary privilege.</p><p>More impressive still is the restraint with which the central tragedy shapes the cast. Gustave\'s determination is bound to duty and to the future of Lumière; Maelle sees in the journey the possibility of a life beyond the walls that have confined her. Lune approaches the unknown through inquiry, while Sciel carries a warmth made more compelling by the pain beneath it. They do not exist merely to explain the next objective. Their conversations, disagreements, and small intimacies give the expedition a human scale. At its best, the writing allows affection and fear to share the same sentence without demanding applause for either.</p><p>Combat offers an equally deliberate departure from tradition. Commands, abilities, and party composition follow the structure of a turn-based RPG, but an enemy\'s attack asks for more than patient observation. Dodges and parries must be timed in real time; a successful defense can become the beginning of a counterattack. The result is an unusual compact between planning and reflex. One must study an opponent\'s habits, then execute the chosen answer with precision. Such engagement gives even ordinary encounters a measure of ceremony, though those who prefer leisurely turns may find the required timing rather severe.</p><p>The systems beneath that spectacle reward attention. Pictos and Lumina allow useful combinations of passive effects, while each combatant brings distinctive resources and rhythms to the party. Experimentation matters: an apparently modest adjustment can change the way an entire encounter unfolds. There is pleasure in discovering a strategy that is both effective and elegant. Yet complexity has its price. Some combinations can overpower the intended challenge, and the constant emphasis on perfect defensive responses may make extended battles feel more exhausting than their dramatic presentation suggests. A little less insistence on virtuosity would occasionally have been welcome.</p><p>Lorien Testard\'s music provides the journey with its most persuasive companion. Piano, orchestral writing, voices, and unexpected shifts in intensity lend emotional definition to places and people without reducing every scene to the same melancholy refrain. The score does not simply announce sorrow; it gives sorrow different temperatures. It can be intimate one moment and magnificent the next. The performances and careful pacing of quieter exchanges also deserve credit. When the spectacle recedes, the game often becomes more affecting, as though the finest conversation at a grand reception were taking place in a nearly empty corridor.</p><p>Not every aspect is equally polished. Navigation can be less graceful than the scenery, some enemy patterns demand repeated rehearsal, and occasional technical roughness interrupts the illusion of a perfectly composed world. Its appetite for tragedy can also feel relentless. Nevertheless, these reservations do not diminish the greater achievement. Clair Obscur: Expedition 33 understands that style acquires meaning only when placed in service of something worth saying. Beneath the color, the music, and the carefully measured combat lies a question of considerable delicacy: how does one honor a life when time insists upon taking it away? The game refuses to make that question comfortable. It is wiser for the refusal.</p>', NULL, '<p>The expedition\'s greatest deception is the nature of its world: Lumière exists within a magical Canvas created by the real Verso, who died saving his sister Alicia from a fire. Maelle is Alicia, reborn inside the painting without her memories. The Paintress is their grieving mother, Aline, while Renoir seeks to end the family\'s captivity within the Canvas. At the conclusion, siding with Maelle preserves that painted world at a grave personal cost; siding with Verso destroys it, allowing the surviving family to confront its grief.</p>', 'Clair Obscur: Expedition 33 — A Requiem in Paint', 'In Clair Obscur: Expedition 33, enter a world of exquisite beauty and ruthless fate, where a doomed expedition dares to challenge the mysterious Paintress.', '2026-11-09 23:21:45', '2026-11-10 00:54:58'),
(7, 4, 'fr', 1, 1, 'clair-obscur-expedition-33-la-splendeur-au-bord-de-loubli', 'Clair Obscur: Expedition 33 — La splendeur au bord de l\'oubli', '<p>Sous les fastes d\'un monde inspiré de la Belle Époque, Clair Obscur: Expedition 33 déploie un RPG où chaque année prononce une sentence. Une aventure somptueuse, dont la grâce n\'efface jamais la cruauté.</p>', '<p>À Lumière, la Peintresse inscrit chaque année un nombre décroissant sur un monolithe. Tous ceux qui atteignent cet âge disparaissent lors du Gommage. Lorsque le chiffre 33 apparaît, Gustave rejoint une expédition décidée à briser cette condamnation. Accompagné notamment de Maelle, Lune et Sciel, il traverse un continent aussi merveilleux que menaçant. Derrière ses paysages irréels et ses créatures singulières se cachent les traces des voyages précédents et les secrets d\'une malédiction que nul n\'a encore su défaire.</p>', '<p>Il est des tragédies que l\'on annonce à grand fracas; celle de Clair Obscur: Expedition 33 prend la forme plus courtoise, et infiniment plus cruelle, d\'un chiffre. À Lumière, l\'âge n\'est pas seulement un privilège qui s\'épuise: il devient une condamnation publique. Chaque année, la Peintresse abaisse le nombre inscrit sur son monolithe, puis des existences s\'effacent. Sandfall Interactive fait de cette idée le fondement d\'un jeu de rôle dont l\'élégance ne sert jamais à dissimuler la douleur. Elle lui offre, au contraire, un écrin suffisamment somptueux pour que la perte en paraisse plus insupportable encore.</p><p>L\'inspiration Belle Époque se reconnaît dans le goût des courbes, des étoffes, des façades et des détails soignés. Mais il serait imprudent d\'y chercher une reconstitution historique. Le continent se comporte comme un songe dont l\'auteur aurait perdu le sens des proportions. Les perspectives s\'étirent, les architectures défient la raison et les monstres semblent avoir été dessinés par une main aussi inventive qu\'inquiétante. L\'ensemble possède cette rare qualité: on peut en admirer la beauté tout en redoutant ce qu\'elle annonce. Le décor ne fait pas patienter entre deux combats; il participe pleinement au récit.</p><p>La véritable distinction de l\'œuvre tient pourtant à ses personnages. Gustave ne se réduit pas à la bravoure d\'un chef d\'expédition; son engagement naît d\'une responsabilité envers ceux qui resteront. Maelle, plus jeune, découvre dans le départ une possibilité d\'émancipation. Lune cherche des réponses avec l\'obstination d\'un esprit scientifique, tandis que Sciel oppose à la fatalité une chaleur qui n\'a rien de naïf. Leurs rapports se construisent par des silences, des plaisanteries et des confidences autant que par les grandes déclarations. Cette attention aux nuances évite au drame de devenir une simple succession de malheurs prestigieusement mis en scène.</p><p>Il faut aussi rendre justice au système de combat, singulier mariage entre réflexion et promptitude. Les affrontements suivent une organisation au tour par tour: on choisit ses actions, ses compétences, ses priorités. Cependant, quand l\'adversaire frappe, l\'attente n\'est plus permise. Esquives, parades et ripostes demandent un sens précis du rythme. La formule donne une présence remarquable aux ennemis, car leurs gestes doivent être observés plutôt que subis. Elle comporte néanmoins une exigence dont tous les amateurs de RPG ne goûteront pas l\'autorité. À force d\'insister sur le bon instant, certaines rencontres peuvent ressembler à un examen que l\'on repasse avec une dignité de plus en plus fragile.</p><p>Le développement des combattants apporte une profondeur appréciable. Grâce aux Pictos et aux Luminas, des effets passifs se combinent à des techniques propres à chacun. La composition de l\'équipe ne se résume donc pas à choisir ses favoris: elle invite à comprendre les complémentarités, à prévoir une réponse, à mesurer le risque. Cette richesse encourage l\'invention de stratégies personnelles. Elle autorise aussi des assemblages capables d\'aplanir brusquement la difficulté. Voilà un paradoxe assez savoureux: un jeu qui exige tant d\'adresse peut parfois se laisser dominer par une préparation particulièrement judicieuse.</p><p>La musique de Lorien Testard mérite une place d\'honneur. Piano, voix, textures orchestrales et élans plus âpres composent une partition dont la sensibilité ne se confond jamais avec la monotonie. Elle accompagne les moments de bravoure, mais sait surtout ménager la fragilité d\'une parole, l\'attente d\'un départ ou la présence d\'une absence. Les interprétations vocales contribuent à cette impression d\'intimité. Le jeu comprend qu\'un dialogue retenu peut peser davantage qu\'une scène spectaculaire; c\'est là une marque de confiance assez rare dans les grandes aventures contemporaines.</p><p>Quelques réserves demeurent. L\'orientation n\'est pas toujours à la hauteur de l\'inventivité visuelle, les attaques adverses réclament parfois une mémorisation laborieuse, et certaines aspérités techniques troublent la contemplation. La gravité du propos, presque constante, laisse également peu de répit. Ces faiblesses ne sauraient pourtant effacer l\'essentiel. Clair Obscur: Expedition 33 ne se contente pas d\'être un beau RPG français aux combats ingénieux. Il examine ce que l\'on doit aux disparus, ce que l\'on peut encore offrir aux vivants et la manière dont l\'amour, malgré ses manières irréprochables, peut devenir une forme d\'emprise. C\'est une œuvre dont la grâce n\'exige pas l\'oubli de ses blessures.</p>', NULL, '<p>Le monde de Lumière est en réalité une Toile magique liée à Verso, mort dans un incendie en sauvant sa sœur Alicia. Celle-ci est devenue Maelle après être entrée dans la peinture et avoir perdu la mémoire. La Peintresse n\'est autre qu\'Aline, leur mère, réfugiée dans cette création pour fuir son deuil. Renoir, son époux, veut mettre fin à cette fuite. Dans le dernier affrontement, choisir Maelle préserve la Toile au prix de nouvelles souffrances; choisir Verso la détruit et contraint la famille à accepter sa perte.</p>', 'Clair Obscur: Expedition 33 — La splendeur au bord de l\'oubli', 'Dans Clair Obscur: Expedition 33, découvrez un monde d\'une beauté troublante, où une expédition défie la Peintresse et le destin qui condamne les siens.', '2026-11-09 23:21:45', '2026-11-10 16:30:13'),
(8, 4, 'de', 1, 1, 'clair-obscur-expedition-33-schonheit-im-schatten-des-abschieds', 'Clair Obscur: Expedition 33 — Schönheit im Schatten des Abschieds', '<p>Clair Obscur: Expedition 33 verbindet die Pracht einer von der Belle Époque geprägten Welt mit unerbittlicher Vergänglichkeit. Ein außergewöhnliches Rollenspiel über Hoffnung, Verlust und die Last der Erinnerung.</p>', '<p>In der Stadt Lumière erscheint jedes Jahr eine neue, niedrigere Zahl auf dem Monolithen der Malerin. Wer dieses Alter erreicht hat, verschwindet beim sogenannten Gommage. Als die Zahl 33 verkündet wird, bricht Gustave mit einer Expedition auf, um die Malerin zu besiegen und den tödlichen Kreislauf zu beenden. Mit Maelle, Lune, Sciel und weiteren Gefährten bereist er einen rätselhaften Kontinent, dessen wundersame Landschaften ebenso viele Gefahren wie Spuren früherer Expeditionen bergen. Doch hinter dem Fluch scheint mehr zu liegen als bloße Grausamkeit.</p>', '<p>Eine wahrhaft vornehme Gesellschaft würde das Alter wohl mit Takt behandeln. In Clair Obscur: Expedition 33 bleibt für solche Rücksicht keine Gelegenheit. Einmal im Jahr schreibt die Malerin eine Zahl auf ihren Monolithen, und die Menschen, die dieses Lebensalter erreicht haben, verschwinden. Der Countdown folgt einer unbestechlichen Ordnung, gegen die weder Wohlstand noch Tapferkeit etwas ausrichten. Sandfall Interactive stellt dieses grausame Ritual an den Anfang seines Rollenspiels und gewinnt daraus etwas Bemerkenswertes: eine Abenteuerreise, die zugleich vom Mut zum Aufbruch und von der Unmöglichkeit handelt, die Zeit aufzuhalten.</p><p>Schon die Gestaltung macht deutlich, dass hier andere Regeln gelten als in gewöhnlichen Fantasyreichen. Die Belle Époque liefert Formen und Stimmungen, doch keine verlässliche Landkarte. Elegante Architektur steht neben unwirklichen Felsformationen; zwischen prachtvollen Ansichten begegnen einem Wesen, deren Schönheit selten beruhigend wirkt. Farben, Licht und Maßstäbe scheinen einem eigenwilligen künstlerischen Urteil zu gehorchen. Das Ergebnis ist kein Museum vergangener französischer Kultur, sondern eine selbstständige Welt, in der sich Anmut und Bedrohung gegenseitig verstärken. Gerade weil so vieles bezaubernd aussieht, bleibt der Gedanke an seine Zerbrechlichkeit stets gegenwärtig.</p><p>Die Erzählung hätte sich mit einer Schar heldenhafter Opfer begnügen können. Zum Glück besitzen die Mitglieder der Expedition deutlich mehr Persönlichkeit. Gustave trägt Verantwortung, ohne daraus eine Pose zu machen. Maelle sucht jenseits der vertrauten Stadt nach einem eigenen Platz im Leben. Lunes Wissbegierde gibt der Suche eine intellektuelle Schärfe, und Sciels heitere Art lässt die Erfahrung ihres Schmerzes umso deutlicher hervortreten. Solche Unterschiede verleihen den Gesprächen Spannung. Zwischen Trauer und Pflicht finden sich kleine, mitunter überraschend leichte Augenblicke. Die Figuren dürfen einander kennenlernen, anstatt lediglich die Handlung von einer dramatischen Enthüllung zur nächsten zu tragen.</p><p>Spielerisch nimmt Expedition 33 eine ebenso interessante Haltung ein. Es bewahrt den strategischen Aufbau rundenbasierter Kämpfe, fordert während gegnerischer Angriffe jedoch unmittelbare Reaktionen. Ausweichen und Parieren verlangen ein gutes Auge und ein zuverlässiges Gefühl für den richtigen Moment. Gelingt eine Parade, kann daraus ein wirkungsvoller Gegenangriff entstehen. Damit wird die Verteidigung zum aktiven Teil der Taktik. Das verleiht selbst kleineren Gefechten Intensität, birgt aber auch einen Nachteil: Wer den ruhigen Rhythmus klassischer Rollenspiele schätzt, dürfte die zeitkritischen Eingaben als aufdringlich empfinden. Nicht jede Herausforderung gewinnt dadurch, dass sie noch präziseres Timing verlangt.</p><p>Die Möglichkeiten zur Gestaltung der Gruppe entschädigen für manchen strengen Augenblick. Verschiedene Charakterfähigkeiten treffen auf Pictos und Lumina, mit denen sich passive Effekte geschickt verbinden lassen. Wer die Systeme versteht, kann anspruchsvolle Begegnungen durch Vorbereitung erheblich erleichtern. Die Freude liegt dann nicht allein im Sieg, sondern in der Eleganz des gewählten Vorgehens. Allerdings entsteht eine spürbare Unwucht, sobald besonders starke Kombinationen Kämpfe schneller entscheiden, als deren Inszenierung vermuten lässt. Das Balancing hält nicht immer mit dem Erfindungsreichtum der eigenen Mechanik Schritt.</p><p>Von besonderem Rang ist die Musik Lorien Testards. Klavierpassagen, Gesang und weit gespannte orchestrale Motive verleihen den Orten eine unverwechselbare emotionale Färbung. Die Partitur weiß, wann sie einen Auftritt feierlich begleiten und wann sie einem stillen Gespräch den Vortritt lassen muss. Auch die Sprecherleistungen helfen dabei, die Figuren vor bloßer Symbolik zu bewahren. Das Spiel erlaubt sich immer wieder Augenblicke, in denen ein Blick oder ein vorsichtiger Satz mehr Gewicht erhält als die prächtigste Kulisse. Solcher Zurückhaltung begegnet man in einem derart üppig ausgestatteten Werk nicht selbstverständlich.</p><p>Freilich erreicht nicht jedes Detail dieselbe Güte. Die Orientierung in manchen Gebieten bleibt umständlich, bestimmte Angriffsfolgen verlangen ermüdende Wiederholungen, und technische Unebenheiten können die Illusion empfindlich stören. Zudem ist die melancholische Grundstimmung kaum je ganz aufgehoben. Doch aus diesen Einwänden erwächst kein grundsätzliches Urteil gegen das Spiel. Clair Obscur: Expedition 33 ist gerade deshalb überzeugend, weil seine Pracht nicht über einen Mangel an Gedanken hinwegtäuscht. Es fragt, welchen Preis Hoffnung haben darf und wo treue Erinnerung in Unfreiheit umschlägt. Eine angenehme Antwort wird nicht serviert. Für ein Werk von solcher Eleganz ist das eine beachtliche Form von Aufrichtigkeit.</p>', NULL, '<p>Lumière und seine Bewohner gehören zu einer magischen Leinwand, die der wirkliche Verso einst erschuf. Er starb bei einem Brand, als er seine Schwester Alicia rettete. Alicia gelangt später in die Leinwand, verliert ihre Erinnerungen und lebt dort als Maelle. Hinter der Malerin verbirgt sich ihre trauernde Mutter Aline; Renoir versucht, die Familie aus dieser Scheinwelt zurückzuholen. Am Ende entscheiden die Spielenden zwischen Maelle, die die Leinwand um einen hohen Preis bewahrt, und Verso, der sie zerstört, damit die Familie den Verlust endlich annehmen kann.</p>', 'Clair Obscur: Expedition 33 — Schönheit im Schatten des Abschieds', 'Clair Obscur: Expedition 33 entführt in eine prachtvolle Welt, in der eine mutige Expedition der Malerin und einem unerbittlichen Schicksal entschlossen trotzt.', '2026-11-09 23:21:45', '2026-11-10 16:30:13'),
(9, 5, 'en-us', 1, 0, 'fallout-the-end-of-the-world-not-of-bad-manners', 'Fallout — The End of the World, Not of Bad Manners', '<p>My dear reader, imagine civilization reduced to rubble while its most ambitious citizens remain determined to give orders. In Fallout, immaculate vaults conceal rather untidy secrets, and the wasteland offers danger, dark wit, and an uncomfortable lesson in human nature.</p>', '<p>More than two centuries after nuclear war devastated America, Lucy MacLean enjoys a sheltered life in Vault 33, where courtesy is practically a civic duty. When her father is kidnapped, she ventures into the dangerous wasteland to find him. Her path crosses those of Maximus, a young member of the Brotherhood of Steel, and a ruthless bounty hunter known as the Ghoul. Beyond the vault\'s cheerful walls, Lucy discovers that survival demands more than good intentions—and that appearances are seldom trustworthy.</p>', '<p>My dear reader, may I trouble you with a question? If mankind managed to destroy the world, would its surviving members finally acquire some humility? Prime Video\'s <em>Fallout</em> offers a delightfully discouraging answer. The cities are ruined, the countryside is irradiated, and yet there is no shortage of people eager to tell everyone else how to live. Civilization, it seems, has proved considerably more fragile than self-importance.</p><p>Adapted from Bethesda\'s video game universe, the series understands that an adaptation need not reenact a familiar quest to honor its origins. Instead, it invents a story that feels entirely at home among the franchise\'s underground shelters, retrofuturistic technology, grotesque dangers, and suspiciously cheerful promises. You need never have held a game controller to appreciate the spectacle. A working knowledge of human folly will serve you quite well.</p><p><strong>A Proper Young Lady in a Most Improper World</strong></p><p>Consider Lucy MacLean, played with remarkable precision by Ella Purnell. She was raised in Vault 33, an underground community devoted to cooperation, cleanliness, and good manners. When circumstances force her into the wasteland, she approaches strangers with the optimism of a guest arriving for afternoon tea. Unfortunately, her hosts are rather more inclined to threaten her than to offer refreshments.</p><p>That contrast could easily become a tiresome joke. Purnell prevents it. Lucy is inexperienced, certainly, but never empty-headed; her kindness is a conviction rather than a failure to understand danger. As her journey grows harsher, the question is not merely whether she will survive. It is whether she can remain recognizably herself. I confess that I found this far more compelling than another tale about a hero learning to become ruthless.</p><p>Walton Goggins, meanwhile, has the unenviable task of making the Ghoul—a disfigured, merciless bounty hunter—impossible to ignore. He succeeds magnificently. The character moves through the wasteland with the confidence of a gentleman who has abandoned both polite society and any desire to return to it. His dry observations are often wickedly funny; his violence is seldom charming. It would be unwise to invite him to dinner, unless one had already hidden the silverware.</p><p>And then there is Maximus, portrayed by Aaron Moten. He belongs to the Brotherhood of Steel, a militarized order equipped with magnificent suits of power armor and a lofty vocabulary of duty. Underneath those impressive surfaces lie insecurity, ambition, and the ordinary human desire to matter. You may recognize the arrangement, dear reader. Institutions have always been rather fond of dressing uncertainty in ceremonial armor.</p><p><strong>An Apocalypse with Exquisite Décor</strong></p><p>The production\'s visual imagination deserves particular admiration. The vaults resemble carefully maintained monuments to mid-century American optimism: spotless corridors, reassuring colors, and an almost touching faith in procedure. Outside, that optimism has acquired some serious structural damage. Ruined settlements and hazardous creatures make a persuasive case against taking glossy advertisements at face value.</p><p>The music sharpens the joke. Sweet old songs drift across scenes of danger, creating a dissonance that is both amusing and unsettling. Behind the spectacle, the series is interested in corporate promises, military authority, and the extraordinary ease with which self-interest can disguise itself as public service. Those subjects may sound solemn. <em>Fallout</em> knows better than to deliver them as a lecture.</p><p>Its storytelling is not without blemishes. Some encounters depend on remarkably convenient timing, while the leaps from gruesome violence to broad comedy can test the viewer\'s patience. Those who favor restrained science fiction may find the tone a little unruly. I would not argue with them. Even an entertaining disaster ought occasionally to observe the rules of good company.</p><p>Still, the central characters give these extravagances purpose. Lucy, Maximus, and the Ghoul offer competing ideas of loyalty, morality, and survival, and their differences carry the mystery forward without requiring the audience to study the games beforehand.</p><p>What remains with me is not merely the ruined landscape, but the stubborn survival of old habits within it. The bombs have erased a great deal, yet vanity, greed, and the talent for making dubious ambitions sound noble remain in splendid condition. If that is progress, my dear reader, I should hate to witness a setback.</p>', NULL, '<p>Now, dear reader, discretion must leave the room. Lucy discovers that her father, Hank, was a Vault-Tec employee preserved before the war and was responsible for the destruction of Shady Sands. Her mother, Rose, survived the catastrophe only to become a feral ghoul. The bounty hunter was once Cooper Howard, an actor whose wife took part in Vault-Tec discussions about triggering nuclear war. Meanwhile, Norm discovers cryogenically preserved company personnel in Vault 31. Hank escapes toward New Vegas, leaving Lucy to confront a dreadful truth: the old world\'s secrets have outlived the old world itself.</p>', 'Fallout — The End of the World, Not of Bad Manners', 'Fallout, Prime Video\'s sci-fi series, finds dark humor and dangerous secrets in a nuclear wasteland where bad manners survived the end of civilization.', '2026-11-10 15:04:17', '2026-11-10 15:04:17');
INSERT INTO `post_localizations` (`id`, `post_id`, `locale`, `is_active`, `is_featured`, `slug`, `title`, `excerpt`, `synopsis`, `review`, `article_body`, `spoilers`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(10, 5, 'fr', 1, 0, 'fallout-lapocalypse-et-les-mauvaises-manieres', 'Fallout — L\'apocalypse et les mauvaises manières', '<p>Cher lecteur, la fin du monde aurait pu rendre les hommes modestes. Quelle naïveté ! Dans Fallout, des abris impeccables cachent de fort vilaines ambitions, tandis que les terres irradiées offrent monstres, mystères et une leçon délicieusement cruelle sur nos bonnes manières.</p>', '<p>Plus de deux siècles après une guerre nucléaire, Lucy MacLean mène une existence paisible dans l\'Abri 33, où la courtoisie tient presque lieu de loi. Lorsque son père est enlevé, elle quitte ce refuge rassurant pour parcourir les terres désolées. Sa route croise celle de Maximus, jeune membre de la Confrérie de l\'Acier, et d\'un chasseur de primes impitoyable surnommé la Goule. Lucy découvre bientôt qu\'au-dehors, les belles intentions protègent bien moins que la méfiance.</p>', '<p>Mon cher lecteur, j\'aimerais vous soumettre une expérience de pensée. Supposons que l\'humanité fasse sauter ses villes, ses institutions et une bonne partie de ses voisins. Pensez-vous que les survivants renonceraient enfin à leurs prétentions ? <em>Fallout</em>, la série de Prime Video, nous invite à ne pas nourrir d\'espérances excessives. La poussière radioactive recouvre le monde, mais l\'ambition, elle, n\'a manifestement pas souffert de la moindre pénurie.</p><p>Adaptée de l\'univers vidéoludique de Bethesda, cette première saison possède une qualité fort appréciable : elle connaît son héritage sans se comporter comme une visite guidée réservée aux initiés. Point n\'est besoin d\'avoir exploré les jeux pour comprendre les enjeux. Les abris souterrains, les machines d\'un avenir imaginé par l\'Amérique des années 1950 et la violence absurde des terres dévastées suffisent à établir un monde dont chaque détail semble sourire en cachant les dents.</p><p><strong>Une demoiselle bien élevée, fort mal accueillie</strong></p><p>Faisons d\'abord connaissance avec Lucy MacLean. Ella Purnell lui prête une vivacité qui empêche son optimisme de tourner à la sottise. Dans l\'Abri 33, Lucy a appris que l\'entraide et la politesse permettent de traverser les difficultés. Une éducation irréprochable, à ceci près que les habitants de la surface n\'ont pas tous reçu le même manuel de savoir-vivre.</p><p>La jeune femme s\'adresse parfois au danger comme on accueillerait un invité un peu grossier. Cela prête à sourire, bien sûr, mais sa bonté n\'est jamais réduite à une plaisanterie. À mesure que le voyage éprouve ses convictions, elle apprend à se défendre sans renoncer mécaniquement à toute générosité. Permettez-moi d\'insister sur ce point : il est bien plus intéressant de voir une personne lutter pour préserver ses principes que de la regarder devenir insensible par commodité scénaristique.</p><p>Face à elle, Walton Goggins compose une Goule inoubliable. Cet homme ravagé par les radiations exerce le métier peu recommandable de chasseur de primes et semble avoir congédié depuis longtemps le moindre scrupule. Pourtant, son regard, son humour sec et la profondeur progressive de son histoire lui confèrent une singulière autorité. Je ne le recevrais pas dans mon salon sans faire disparaître l\'argenterie, mais j\'écouterais volontiers ce qu\'il a à raconter.</p><p>Maximus, incarné par Aaron Moten, apporte une autre nuance. Membre de la Confrérie de l\'Acier, il évolue parmi des armures imposantes et des discours solennels sur l\'ordre. Sous ces dehors chevaleresques se cachent rivalités, peur du déclassement et désir de reconnaissance. Vous conviendrez, cher lecteur, que l\'uniforme n\'a jamais suffi à fabriquer la vertu.</p><p><strong>Le bon goût au milieu des ruines</strong></p><p>La direction artistique accomplit un travail remarquable. Dans les abris, les couleurs rassurantes et la propreté presque cérémonieuse évoquent un rêve de prospérité soigneusement entretenu. À l\'extérieur, carcasses de bâtiments et créatures hostiles répondent à ce rêve avec une franchise brutale. Le contraste ne sert pas seulement la beauté des images : il donne un visage à l\'hypocrisie des promesses de sécurité absolue.</p><p>La musique pousse l\'ironie plus loin. Des chansons anciennes, légères et sentimentales, accompagnent des scènes qui ne le sont guère. Ce décalage prête à rire, parfois jaune, tout en rappelant que la nostalgie peut devenir un excellent rideau derrière lequel dissimuler les pires intentions.</p><p><em>Fallout</em> s\'intéresse ainsi au pouvoir des entreprises, à la ferveur militaire et aux discours qui présentent des intérêts particuliers comme des sacrifices pour le bien commun. Rassurez-vous : la série ne transforme pas ces questions en sermon. Elle préfère nous laisser observer les contradictions de ses personnages, ce qui est autrement plus divertissant.</p><p>Tout n\'est pas d\'une égale finesse. Certains hasards arrangent commodément les rencontres, et le passage d\'une violence spectaculaire à une plaisanterie outrancière peut désorienter. Le spectateur qui souhaite une science-fiction grave et mesurée trouvera sans doute cette compagnie un peu bruyante. Je comprends sa réserve, même si je pardonne volontiers quelques écarts à une œuvre qui sait si bien ce qu\'elle veut être.</p><p><strong>Ce que les bombes n\'ont pas emporté</strong></p><p>La réussite essentielle de la première saison tient à ses trois figures centrales. Lucy interroge la bonté, Maximus le besoin d\'appartenir à quelque chose, et la Goule les ravages d\'une longue désillusion. Leurs regards ne s\'accordent guère, mais c\'est de cette discorde que naît une véritable tension dramatique.</p><p>Au fond, le spectacle ne nous demande pas seulement comment survivre après l\'apocalypse. Il nous demande ce que nous choisirions de sauver de nous-mêmes. Les villes ont disparu, les usages se sont effondrés, et pourtant les vanités de l\'ancien monde continuent de réclamer leur place à table. Entre nous, cher lecteur, voilà peut-être la catastrophe la plus difficile à réparer.</p>', NULL, '<p>Cher lecteur, voici l\'instant où la discrétion serait malhonnête. Lucy apprend que son père, Hank, travaillait pour Vault-Tec avant la guerre et qu\'il a provoqué la destruction de Shady Sands. Sa mère, Rose, en a réchappé, mais sous la forme d\'une goule devenue sauvage. Le chasseur de primes était autrefois Cooper Howard, acteur dont l\'épouse participait à des discussions sur le déclenchement d\'une guerre nucléaire. Dans l\'Abri 31, Norm découvre des employés conservés par cryogénisation. Hank prend la fuite vers New Vegas : décidément, les secrets de famille résistent mieux aux bombes que les bonnes manières.</p>', 'Fallout — L\'apocalypse et les mauvaises manières', 'Dans Fallout, série de science-fiction de Prime Video, les abris protègent des radiations, non des ambitions. L\'apocalypse n\'a guère amélioré les manières.', '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(11, 5, 'de', 1, 0, 'fallout-der-weltuntergang-kennt-keine-manieren', 'Fallout – Der Weltuntergang kennt keine Manieren', '<p>Mein geschätzter Leser, die Welt liegt in Trümmern, doch der Drang, anderen Vorschriften zu machen, lebt prächtig weiter. Fallout führt uns aus tadellos gepflegten Bunkern in ein Ödland voller Gefahren, Geheimnisse und einer höchst unbequemen Wahrheit über die Menschheit.</p>', '<p>Mehr als zwei Jahrhunderte nach einem Atomkrieg lebt Lucy MacLean behütet in Vault 33, wo Höflichkeit und Gemeinschaftssinn zum Alltag gehören. Als ihr Vater entführt wird, wagt sie sich an die gefährliche Oberfläche, um ihn zu finden. Dort begegnet sie Maximus, einem jungen Mitglied der Stählernen Bruderschaft, und einem skrupellosen Kopfgeldjäger, den alle nur den Ghoul nennen. Zwischen verfallenen Siedlungen und tödlichen Bedrohungen muss Lucy feststellen, dass gute Absichten allein kein zuverlässiger Schutz sind – und dass hinter freundlichen Fassaden mitunter erschreckende Wahrheiten warten.</p>', '<p>Mein geschätzter Leser, gestatten Sie mir eine etwas unhöfliche Frage: Würde die Menschheit nach ihrer eigenen Vernichtung endlich zur Bescheidenheit finden? Die Prime-Video-Serie <em>Fallout</em> beantwortet sie mit einem ausgesprochen boshaften Lächeln. Ganze Städte liegen in Schutt und Asche, gefährliche Strahlung prägt die Landschaft, und dennoch gibt es erstaunlich viele Menschen, die anderen nur zu gern erklären möchten, wie die Welt gefälligst zu funktionieren hat. Offenbar übersteht die Selbstüberschätzung selbst den Atomkrieg.</p><p>Die Serie spielt im Universum der Videospiele von Bethesda, erzählt jedoch eine eigenständige Geschichte. Das ist ein entscheidender Vorzug. Wer die Spiele kennt, erkennt ihre eigenwillige Mischung aus nostalgischer Zukunftsbegeisterung, bitterem Witz und erschreckender Gewalt sofort wieder. Wer sie nicht kennt, braucht keineswegs beschämt am Eingang stehen zu bleiben. Ein gewisses Misstrauen gegenüber allzu freundlichen Versprechungen genügt für den Anfang vollkommen.</p><p><strong>Eine wohlerzogene Dame betritt das Ödland</strong></p><p>Beginnen wir mit Lucy MacLean, gespielt von Ella Purnell. In Vault 33 wurde ihr beigebracht, dass Freundlichkeit, Hilfsbereitschaft und klare Regeln die Grundlagen jeder anständigen Gemeinschaft bilden. Man möchte ihren Erziehern beinahe gratulieren. Leider begegnet sie außerhalb des Bunkers Personen, die sich bei der Verteilung dieser Tugenden offenbar entschuldigt haben.</p><p>Purnell macht aus Lucy weit mehr als eine naive Heldin. Ihre Höflichkeit wirkt nicht wie Dummheit, sondern wie eine bewusste Haltung. Jede unangenehme Begegnung zwingt sie, ihre Überzeugungen neu zu prüfen. Gerade darin liegt der Reiz ihrer Reise: Wir fragen uns nicht bloß, ob sie überlebt, sondern auch, wie viel von ihrer Menschlichkeit sie bewahren kann. Ich finde, das ist erheblich spannender als die übliche Verwandlung einer freundlichen Figur in eine ungerührte Kampfmaschine.</p><p>Ganz anderer Natur ist der Ghoul, dem Walton Goggins eine geradezu unverschämte Präsenz verleiht. Der entstellte Kopfgeldjäger ist brutal, zynisch und von einer erschreckenden Ruhe. Er benimmt sich wie ein Herr, der sämtliche Regeln der Gesellschaft kennt und beschlossen hat, keine einzige mehr zu beachten. Zu einer Abendgesellschaft würde ich ihn nicht einladen, ohne zuvor das Familiensilber in Sicherheit zu bringen.</p><p>Hinter seinem trockenen Spott deutet sich allerdings eine Vergangenheit an, die der Figur Gewicht gibt. Goggins beherrscht den Wechsel zwischen Bedrohung und schwarzem Humor so mühelos, dass man ihm selbst dann aufmerksam zuhört, wenn man seine Methoden zutiefst missbilligt.</p><p>Maximus, dargestellt von Aaron Moten, bringt eine weitere Sichtweise hinzu. Er gehört zur Stählernen Bruderschaft, deren gewaltige Powerrüstungen und feierliche Ideale zunächst fast ritterlich wirken. Doch unter dem Metall verbergen sich Unsicherheit, Ehrgeiz und das Verlangen nach Anerkennung. Sie kennen das gewiss, mein Leser: Eine eindrucksvolle Uniform ist noch lange kein Beweis für einen edlen Charakter.</p><p><strong>Ausgezeichneter Geschmack, bedauerliche Zustände</strong></p><p>Besondere Aufmerksamkeit verdient die Ausstattung. Die Bunker sind sauber, freundlich beleuchtet und voller liebevoll gestalteter Erinnerungen an den amerikanischen Optimismus der Fünfzigerjahre. Alles scheint auf Sicherheit und Wohlstand ausgerichtet. An der Oberfläche antworten Ruinen, gefährliche Kreaturen und verwüstete Siedlungen mit einer weniger beruhigenden Wirklichkeit.</p><p>Dazu erklingen alte Lieder, deren heitere Melodien mit dem Geschehen häufig auf geradezu unanständige Weise kollidieren. Dieser Gegensatz ist kein bloßer Scherz. Er erinnert uns daran, wie leicht sich unangenehme Wahrheiten hinter freundlichen Bildern und wohlklingenden Parolen verstecken lassen.</p><p><em>Fallout</em> beobachtet dabei nicht nur einzelne Überlebende. Die Serie stellt auch Fragen nach Unternehmensmacht, militärischer Ordnung und der erstaunlichen Fähigkeit von Institutionen, eigene Interessen als Dienst an der Allgemeinheit auszugeben. Glücklicherweise werden diese Gedanken nicht in langatmigen Reden ausgebreitet. Sie entstehen aus der Welt und den Entscheidungen ihrer Bewohner.</p><p>Freilich ist diese Gesellschaft nicht immer von vollendeter Eleganz. Manche Begegnung wirkt allzu bequem eingefädelt, und der schnelle Wechsel zwischen drastischer Gewalt und ausgelassenem Humor kann anstrengend sein. Wer zurückhaltende Science-Fiction bevorzugt, dürfte sich gelegentlich einen leiseren Ton wünschen. Diesen Einwand kann ich verstehen. Nicht jeder Abend muss mit einer Explosion enden, um unterhaltsam zu sein.</p><p><strong>Was vom Menschen übrig bleibt</strong></p><p>Dennoch hält die erste Staffel ihre verschiedenen Handlungsstränge überzeugend zusammen. Lucy, Maximus und der Ghoul verkörpern unterschiedliche Vorstellungen von Anstand, Zugehörigkeit und Überleben. Ihre Gegensätze sind interessanter als jede noch so eindrucksvolle Rüstung. Und gerade weil die Serie ihre Figuren ernst nimmt, wirkt ihr schwarzer Humor selten vollkommen beliebig.</p><p>Am Ende beschäftigt mich deshalb weniger die Frage, wie man in dieser zerstörten Welt am Leben bleibt. Viel aufschlussreicher ist, welche alten Gewohnheiten niemand loswerden möchte. Häuser, Straßen und ganze Staaten mögen verschwunden sein. Eitelkeit, Machtgier und die Kunst, zweifelhafte Ziele als ehrenvolle Pflichten zu verkaufen, erfreuen sich bester Gesundheit. Sagen Sie selbst: Ist das nicht die betrüblichste Form menschlicher Beständigkeit?</p>', NULL, '<p>Nun, mein geschätzter Leser, müssen wir die Höflichkeit gegenüber den Geheimnissen aufgeben. Lucy erfährt, dass ihr Vater Hank vor dem Krieg für Vault-Tec arbeitete und die Zerstörung von Shady Sands veranlasste. Ihre Mutter Rose überlebte, wurde jedoch zu einem wilden Ghoul. Der Kopfgeldjäger war einst der Schauspieler Cooper Howard; seine Frau war an Gesprächen über die Auslösung eines Atomkriegs beteiligt. Während Norm in Vault 31 kryogenisch konservierte Mitarbeiter entdeckt, flieht Hank in Richtung New Vegas. Die alte Welt ist untergegangen. Ihre finstersten Familienangelegenheiten sind es offenbar nicht.</p>', 'Fallout – Der Weltuntergang kennt keine Manieren', 'Fallout, die Science-Fiction-Serie auf Prime Video, zeigt ein Ödland mit schwarzem Humor, dunklen Geheimnissen und erstaunlich gut erhaltener Eitelkeit.', '2026-11-10 15:04:17', '2026-11-10 15:04:17'),
(12, 6, 'en-us', 1, 0, 'sinners-when-the-blues-invites-the-darkness', 'Sinners — When the Blues Invites the Darkness', NULL, NULL, '<p>It is worth observing, dear reader, that Coogler does not ask us to mistake his protagonists for saints. Their dream of building a place for celebration matters precisely because they are flawed men trying to make something worthwhile. The film understands that moral complexity is more engaging than a procession of perfect heroes.</p><p>Young Sammie, played by Miles Caton, brings a different conflict. The son of a preacher, he is drawn to the blues despite his father\'s warnings. His gift carries the force of desire, heritage and spiritual uncertainty. For him, music is not a pastime to be neatly scheduled after one\'s duties. It is a voice that refuses to be dismissed.</p><p><strong>When Music Remembers More Than People</strong></p><p>Listen closely to how Sinners uses sound. Under Ludwig Göransson\'s compositions, the blues becomes a living link between generations. One remarkable sequence folds musical traditions across time into a single exhilarating vision. It is extravagant, certainly, but not empty spectacle: the scene makes cultural memory visible and gives the audience a glimpse of what is worth defending.</p><p>And here lies a rather uncomfortable question. What happens when someone admires another person\'s art so fiercely that admiration becomes a wish to possess it? Coogler lets that temptation haunt the story. The distinction between sharing a song and stealing its spirit gives the film an unease that lasts well beyond any moment of violence.</p><p>The historical setting is essential, not ornamental. Racism determines where characters may go, what they can own and how safely they can gather. The threats outside the juke joint are not all imaginary. This gives the supernatural danger a harsher meaning: it enters a landscape already shaped by power and exploitation.</p><p><strong>A Night with Increasingly Unwelcome Guests</strong></p><p>When the celebration turns toward horror, the film does not politely lower its voice. Coogler embraces blood, frantic action and shifts in tone that can be startling. Some viewers may find the final stretch too crowded with spectacle after the patient opening. I understand the objection. A few revelations might have carried greater force had they been allowed a moment of quiet.</p><p>Still, the actors keep the extravagance grounded. Jordan gives each brother a distinct emotional weight; Caton makes Sammie\'s devotion to music believable; and the supporting characters have desires and loyalties that exist before danger arrives. We care about the gathering because it represents more than one night of entertainment. It is a fleeting space of self-determination.</p><p>What stays with me is not simply the prospect of monsters at the threshold. Sinners asks who has the right to claim a people\'s stories, their voices and the places where they can be themselves. Its horror gives those questions sharp teeth; its music gives them a beating heart.</p><p>So let me leave you with a small confidence, my dear reader. Some predators announce themselves with frightening faces. Others arrive smiling, full of flattering promises, convinced that whatever they desire ought to belong to them. As for me, I would rather remain inside with the musicians.</p>', NULL, '<p>Now, dear reader, the door to discretion must close. Sammie\'s music draws Remmick, a vampire who covets his extraordinary gift. Mary is turned and subsequently bites Stack, turning him into a vampire; Annie dies after being bitten.</p><p>Smoke and Sammie defeat Remmick, but the Ku Klux Klan arrives at dawn. Smoke kills the attackers and dies from his wounds.</p><p>In 1992, the still-undead Stack and Mary visit an elderly Sammie, offering him immortality. He refuses. The music, unlike so many of its listeners, has survived on its own terms.</p>', 'Sinners — When the Blues Invites the Darkness', 'Sinners, Ryan Coogler\'s horror film, blends blues, mystery and supernatural terror in 1932 Mississippi. Discover a haunting tale of music, identity and freedom.', '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(13, 6, 'fr', 1, 0, 'sinners-quand-le-blues-invite-les-tenebres', 'Sinners — Quand le blues invite les ténèbres', '<p>Cher lecteur, deux frères reviennent au Mississippi pour offrir à leur communauté une nuit de musique et de liberté. Mais dans Sinners, le blues réveille bien davantage que les souvenirs, et certains invités feraient mieux de rester dehors.</p>', '<p>En 1932, dans le Mississippi ségrégationniste, les jumeaux Smoke et Stack rentrent chez eux pour ouvrir un établissement où la communauté noire pourra se retrouver autour du blues. Leur jeune cousin Sammie, guitariste talentueux, participe à cette soirée qui promet musique et liberté.</p><p>Mais des visiteurs inattendus rôdent bientôt près du lieu, attirés par quelque chose de plus profond qu\'une simple fête. Avant l\'aube, les deux frères devront affronter une menace que ni leur passé ni leur courage ne les avaient préparés à rencontrer.</p>', '<p>Mon cher lecteur, permettez-moi de commencer par une interrogation : quel malheur pourrait donc gâcher une soirée consacrée au blues ? Dans Sinners, Ryan Coogler nous rappelle que les monstres ne sont pas toujours les premiers à faire régner la terreur. Nous voici dans le Mississippi de 1932, où la ségrégation impose déjà ses humiliations et ses violences. Le fantastique survient dans une société qui n\'avait nul besoin de lui pour apprendre la cruauté.</p><p>Ce qui me séduit dans ce film tient à son refus de choisir entre plusieurs ambitions. Il veut raconter une histoire de famille, célébrer la musique, explorer la foi et nous faire frissonner. Pareille entreprise aurait pu produire un assemblage maladroit. Coogler parvient au contraire à donner au blues une place centrale : il rassemble les êtres, préserve leur mémoire et leur offre, ne serait-ce qu\'une nuit, un espace où respirer librement.</p><p><strong>Deux frères et quelques comptes à régler</strong></p><p>Michael B. Jordan interprète Smoke et Stack, des jumeaux revenus de Chicago avec le projet d\'ouvrir un établissement musical destiné à la communauté noire. Une intention fort louable, conviendrez-vous. Mais n\'allez pas les prendre pour des bienfaiteurs irréprochables. Ils portent le poids de leurs choix passés, et leur désir de bâtir quelque chose de beau ne fait pas disparaître leurs contradictions.</p><p>Jordan leur donne des attitudes distinctes, une énergie différente et cette tension particulière des proches qui s\'aiment sans toujours savoir se comprendre. Le procédé aurait pu n\'être qu\'une prouesse d\'acteur ; il devient la matière même du drame. J\'apprécie particulièrement que le film leur accorde des fautes sans leur retirer toute dignité. La vertu sans nuance est rarement très bonne compagnie.</p><p>À leurs côtés, le jeune Sammie, incarné par Miles Caton, possède un talent qui dépasse le simple plaisir de jouer. Fils d\'un pasteur, il entend les mises en garde contre le blues, mais ne peut renoncer à cette musique. Son dilemme fait dialoguer héritage religieux, désir personnel et aspiration à la liberté. Il y a dans sa manière de jouer quelque chose qui réclame d\'être entendu, même par ceux qui préféreraient le silence.</p><p><strong>Une musique que personne ne devrait posséder</strong></p><p>Écoutez, cher lecteur, la façon dont les morceaux traversent le film. La partition de Ludwig Göransson et les interprétations musicales font du blues un langage chargé de souvenirs. Une séquence particulièrement audacieuse rapproche des traditions et des époques différentes dans un même élan. Ce n\'est pas une coquetterie visuelle : Coogler nous montre que la musique peut relier des générations séparées par le temps.</p><p>Cette idée donne également au récit son inquiétude la plus profonde. Quand l\'admiration pour une culture se transforme-t-elle en désir de la prendre ? Entre partager une chanson et s\'en approprier la voix, la frontière est moins innocente qu\'il n\'y paraît. Le film aborde cette question sans réduire ses personnages à de simples symboles.</p><p>Le contexte historique rend le tout plus troublant encore. Le racisme détermine les lieux où l\'on peut se réunir, les risques que l\'on doit accepter et les libertés que d\'autres tiennent pour acquises. Ainsi, lorsque des menaces surnaturelles approchent du salon, elles ne surgissent pas dans un monde paisible. Elles rencontrent une violence qui a déjà ses habitudes, ses autorités et ses justifications.</p><p><strong>Des invités dont on se passerait volontiers</strong></p><p>Lorsque la fête bascule dans l\'horreur, Sinners abandonne toute prétention à la retenue. Le sang coule, le rythme s\'emballe et l\'humour se mêle parfois à des images brutales. Je dois reconnaître que cette générosité peut fatiguer. La dernière partie précipite certains événements que j\'aurais aimé voir respirer davantage. Les amateurs de mystère délicatement distillé risquent de trouver l\'ensemble fort tapageur.</p><p>Pourtant, cette démesure ne me paraît jamais entièrement gratuite. Les personnages existent avant les monstres : ils ont des liens, des regrets, des rêves modestes ou immenses. La soirée prend de la valeur parce qu\'elle représente une possibilité de joie et de communauté, et non une simple occasion de déclencher un carnage.</p><p>En définitive, Sinners interroge le droit de posséder une voix, une mémoire, une histoire. L\'horreur donne des griffes à cette question ; le blues lui offre sa plus belle résistance. Permettez-moi donc une dernière confidence : je me méfie des créatures qui montrent leurs crocs, certes, mais plus encore des visiteurs souriants persuadés que tout ce qu\'ils admirent leur appartient. Pour ma part, je resterais volontiers près des musiciens.</p>', NULL, '<p>Cher lecteur, abandonnons toute discrétion : le talent de Sammie attire Remmick, un vampire qui convoite sa musique. Mary est transformée et mord Stack, qui devient vampire ; Annie meurt après avoir été mordue.</p><p>Smoke et Sammie éliminent Remmick, mais des membres du Ku Klux Klan surgissent à l\'aube. Smoke les abat avant de succomber à ses blessures.</p><p>En 1992, Stack et Mary, toujours vampires, rendent visite à Sammie devenu vieux musicien. Ils lui proposent l\'immortalité ; il refuse. La nuit a emporté bien des vies, mais elle n\'a pas réussi à confisquer sa voix.</p>', 'Sinners — Quand le blues invite les ténèbres', 'Sinners, film d\'horreur de Ryan Coogler, mêle blues, mystère et effroi surnaturel dans le Mississippi de 1932, entre musique, mémoire, désir et liberté.', '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(14, 6, 'de', 1, 0, 'sinners-wenn-der-blues-die-dunkelheit-ruft', 'Sinners – Wenn der Blues die Dunkelheit ruft', '<p>Mein geschätzter Leser, zwei Brüder kehren nach Mississippi zurück, um einen Abend voller Blues und Freiheit zu ermöglichen. Doch in Sinners lockt die Musik nicht nur Freunde an. Manche Gäste bringen ein Grauen mit, das selbst die Nacht erschreckt.</p>', '<p>Im Mississippi des Jahres 1932 kehren die Zwillinge Smoke und Stack in ihre Heimat zurück. Inmitten der Rassentrennung wollen sie einen Musikclub eröffnen, in dem die Schwarze Gemeinschaft gemeinsam feiern kann. Ihr junger Cousin Sammie, ein begabter Bluesgitarrist, soll den Abend mitgestalten.</p><p>Doch die Musik weckt das Interesse unheimlicher Besucher, deren Absichten weit über eine gewöhnliche Feier hinausgehen. Bevor die Sonne aufgeht, müssen sich die Brüder einer Gefahr stellen, auf die weder ihre Erfahrungen noch ihre Entschlossenheit sie vorbereitet haben.</p>', '<p>Mein geschätzter Leser, darf ich Ihnen eine Frage stellen? Was könnte eine Nacht voller Musik wohl gründlicher verderben als ungebetene Gäste? Ryan Cooglers Sinners hat darauf eine Antwort, die weit über das übliche Grauen hinausgeht. Wir befinden uns im Mississippi des Jahres 1932, wo die Rassentrennung bereits für Schrecken sorgt, die keinerlei übernatürliche Hilfe benötigen. Dennoch hält die Dunkelheit noch weitere Zumutungen bereit.</p><p>Der Film verbindet Horror, Familiendrama, Glauben und Blues zu einer bemerkenswert eigenständigen Erzählung. Dabei erklingt die Musik nicht bloß zwischen zwei aufregenden Szenen. Sie ist der Anlass, aus dem Menschen zusammenkommen, ihre Erfahrungen teilen und sich für einige kostbare Stunden einen Raum der Freiheit schaffen. Wer ausschließlich ein ordentliches Monsterabenteuer erwartet, wird feststellen, dass der Gastgeber einen wesentlich anspruchsvolleren Abend geplant hat.</p><p><strong>Zwei Brüder und keine makellose Vergangenheit</strong></p><p>Michael B. Jordan spielt die Zwillinge Smoke und Stack, die aus Chicago zurückkehren, um für die Schwarze Gemeinschaft einen Musikclub zu eröffnen. Das Vorhaben besitzt einen anständigen Kern. Die beiden Herren selbst würde ich allerdings nicht ohne Weiteres als Musterbürger bezeichnen. Ihre Vergangenheit ist alles andere als vorbildlich, und zwischen ihnen liegen Verletzungen, die sich nicht mit einer freundlichen Umarmung beseitigen lassen.</p><p>Jordan gibt den Brüdern unterschiedliche Temperamente, Bewegungen und Arten, mit Schwierigkeiten umzugehen. Dass ein Schauspieler beide Rollen übernimmt, gerät dadurch nie zum bloßen Kunststück. Wir erleben zwei Menschen, die einander verbunden sind und doch keineswegs dieselben Entscheidungen treffen. Das ist mir erheblich lieber als eine Geschichte, in der Helden nur deshalb Helden sind, weil das Drehbuch es behauptet.</p><p>Der junge Sammie, dargestellt von Miles Caton, trägt einen anderen Konflikt in sich. Sein Vater ist Prediger und betrachtet den Blues mit Argwohn. Sammie hingegen findet in seiner Musik etwas, das sich weder verbieten noch einfach beiseiteschieben lässt. Glaube, familiäre Erwartungen und die Sehnsucht nach Selbstbestimmung stehen sich hier gegenüber. Hören Sie ihm zu, mein Leser: Dieser Junge spielt nicht, um einen Raum zu unterhalten. Er versucht, darin seine eigene Stimme zu finden.</p><p><strong>Wenn eine Melodie ganze Generationen verbindet</strong></p><p>Besonders eindrucksvoll ist der Umgang mit der Musik. Ludwig Göranssons Klangwelt und die Auftritte der Figuren lassen den Blues als Träger von Erinnerung erscheinen. In einer außergewöhnlichen Sequenz begegnen sich musikalische Traditionen verschiedener Zeiten, als hätten Vergangenheit und Zukunft für einen Augenblick denselben Saal betreten. Gewiss ist das eine kühne Inszenierung. Doch sie zeigt, dass Kultur nicht mit dem letzten Ton eines einzelnen Musikers endet.</p><p>Gerade darin verbirgt sich eine unangenehme Frage: Wann wird aus Bewunderung der Wunsch, sich das Talent eines anderen anzueignen? Man kann eine Melodie lieben, ohne ihren Ursprung besitzen zu dürfen. Coogler macht aus dieser Versuchung einen wesentlichen Teil seines Schreckens. Und wenn Sie mich fragen, ist der Gedanke, jemand wolle sich eine fremde Stimme einverleiben, beinahe beunruhigender als jede sichtbare Bedrohung.</p><p>Auch die historische Umgebung ist weit mehr als eine hübsch ausgestattete Kulisse. Rassistische Gewalt und gesellschaftliche Ausgrenzung bestimmen, wo die Menschen arbeiten, feiern und sich sicher fühlen können. Das Unheil, das sich dem Club nähert, trifft auf eine Gemeinschaft, die längst gelernt hat, mit Gefahren zu leben. Der übernatürliche Horror verdrängt diese Wirklichkeit nicht; er lässt sie in einem noch härteren Licht erscheinen.</p><p><strong>Eine Feier mit unerwünschtem Ausgang</strong></p><p>Sobald der Abend seine finstere Wendung nimmt, verzichtet der Film auf höfliche Zurückhaltung. Blutige Auseinandersetzungen, jähe Stimmungswechsel und ein stellenweise geradezu übermütiger Umgang mit dem Grauen bestimmen das Geschehen. Das gelingt nicht immer gleichermaßen gut. Nach dem sorgfältigen Aufbau überstürzen sich manche Ereignisse, und einigen Konflikten hätte eine ruhigere Entwicklung gutgetan. Nicht jeder Zuschauer dürfte diese Maßlosigkeit schätzen. Ich selbst hätte gelegentlich eine Pause vorgezogen, bevor sich das nächste Unglück meldet.</p><p>Dennoch bleibt das Geschehen erstaunlich menschlich. Jordan verleiht seinen Brüdern Gewicht, Caton lässt Sammies Leidenschaft glaubwürdig werden, und die Menschen im Club haben Hoffnungen, Beziehungen und Enttäuschungen, lange bevor Gefahr vor der Tür steht. Das ist entscheidend: Wir fürchten um sie nicht deshalb, weil ein Horrorfilm Opfer benötigt, sondern weil ihre Feier für einen Moment ein Stück selbstbestimmten Lebens verspricht.</p><p>So handelt Sinners letztlich von mehr als Furcht und Überleben. Es geht darum, wem eine Geschichte gehört, wer eine Stimme zum Schweigen bringen möchte und was geschieht, wenn Bewunderung zur Gier wird. Erlauben Sie mir zum Schluss eine vertrauliche Bemerkung, mein geschätzter Leser: Vor einem Ungeheuer mit gefletschten Zähnen würde ich gewiss fliehen. Noch misstrauischer wäre ich jedoch gegenüber einem höflichen Besucher, der lächelnd erklärt, alles Schöne müsse nun ihm gehören.</p>', NULL, '<p>Mein geschätzter Leser, nun ist Schluss mit der Zurückhaltung. Sammies Musik zieht den Vampir Remmick an, der dessen Begabung für sich gewinnen will. Mary wird verwandelt und verwandelt anschließend Stack; Annie stirbt nach einem Biss.</p><p>Smoke und Sammie besiegen Remmick, doch bei Tagesanbruch greifen Mitglieder des Ku-Klux-Klans an. Smoke tötet die Angreifer, erliegt jedoch seinen Verletzungen.</p><p>Im Jahr 1992 besuchen die unverändert jungen Vampire Stack und Mary den alten Musiker Sammie. Sie bieten ihm Unsterblichkeit an, doch er lehnt ab. Seine Musik gehört weiterhin ihm selbst.</p>', 'Sinners – Wenn der Blues die Dunkelheit ruft', 'Sinners, Ryan Cooglers Horrorfilm, vereint Blues, düstere Geheimnisse und übernatürlichen Schrecken im Mississippi von 1932. Eine Geschichte über Freiheit.', '2026-11-10 15:52:37', '2026-11-10 15:52:37'),
(15, 7, 'en-us', 1, 0, 'elden-ring-a-crown-worth-more-than-courage', 'Elden Ring — A Crown Worth More Than Courage', '<p>My dear reader, never accept a crown without first inspecting the kingdom. In Elden Ring, shattered castles, quarrelling demigods and merciless foes await the Tarnished. Every road conceals a secret, and every victory asks whether a throne is worth its price.</p>', '<p>In the Lands Between, the Elden Ring has been shattered, and the demigods who claimed its fragments have plunged the realm into war. A banished Tarnished returns, guided by grace and the promise of becoming Elden Lord.</p><p>Across ruined castles, forgotten caverns and treacherous landscapes, the traveler must challenge powerful enemies and uncover the remnants of the Golden Order. At the foot of the Erdtree lies a destiny that may reshape the kingdom—but reaching it is another matter entirely.</p>', '<p>My dear reader, allow me to offer a precaution before we begin: never agree to wear a crown until you have inspected the kingdom. In Elden Ring, the invitation to become Elden Lord sounds splendid, until one discovers the unfortunate condition of the Lands Between. Castles are crumbling, demigods are feuding, and the local wildlife has a disagreeable habit of attacking strangers. Yet few journeys make such an inhospitable realm quite so difficult to leave behind.</p><p>Released in 2022, FromSoftware\'s action RPG was directed by Hidetaka Miyazaki, with George R. R. Martin contributing to the world\'s mythology. Their creation does not explain itself like a gracious host introducing everyone at dinner. It offers fragments, whispers and the aftermath of old betrayals. Frankly, that restraint suits a ruined royal court rather well.</p><p><strong>A Kingdom for the Curious</strong></p><p>Picture yourself emerging into a vast landscape beneath the glowing Erdtree. Faraway towers suggest promises; a ruined church invites investigation; an innocent-looking road may lead to a distinctly unpleasant encounter. Elden Ring rarely insists that you follow a prescribed itinerary. Instead, it asks a wonderfully dangerous question: what might be waiting over there?</p><p>This is why its open world feels more like a place than a checklist. A detour can reveal a hidden dungeon, an unexpected character or a view that changes your understanding of the land. Torrent, your spectral steed, makes wandering a pleasure, although I advise against confusing the ability to ride somewhere with a guarantee of a warm reception.</p><p>Discovery matters because it feels earned. You may set out for a castle and spend the afternoon exploring beneath the earth instead. You may approach an enemy with absolute confidence and retreat moments later, considerably wiser and rather less proud. The game trusts you to choose your own adventures—and to endure the consequences of your enthusiasm.</p><p><strong>The Delicate Business of Being Defeated</strong></p><p>Combat requires observation, timing and the rare virtue of admitting one\'s mistakes. Reckless attacks are frequently punished. Learning an opponent\'s movements, managing stamina and striking at the right moment produce a satisfaction that easier victories seldom match. Defeat is not a scandal in this kingdom; it is practically part of the curriculum.</p><p>Fortunately, Elden Ring does not demand a single method of success. Swords, colossal weapons, sorceries, incantations, weapon skills and spirit summons accommodate very different temperaments. If a formidable boss refuses to yield, you can explore elsewhere, improve your equipment or reconsider your approach. There is something wonderfully democratic about a world that permits both scholarly magic and an extraordinarily large hammer.</p><p>Let us not pretend, however, that every trial is equally agreeable. Some bosses demand such precise responses that persistence can slide into exhaustion. The camera occasionally struggles during hectic encounters, and the difficulty can feel uneven. Not every reader will wish to spend an entire evening being humbled by the same creature. I understand completely; even patience ought to have visiting hours.</p><p><strong>Secrets with Excellent Discretion</strong></p><p>The story is told through elusive conversations, item descriptions and landscapes scarred by ancient conflict. Marika, the Golden Order and the rival demigods emerge gradually, if you are willing to examine the evidence. A broken monument can say as much as a speech, while a seemingly minor encounter may send you searching for meaning hours later.</p><p>Such storytelling rewards attention, but it can also obscure practical matters. Character quests sometimes advance without clear directions, and an easily missed meeting may leave an entire thread unfinished. Mystery is captivating when it invites curiosity; it is less charming when it conceals the very existence of a door.</p><p>Fortunately, the art direction gives the world a language of its own. Towering fortresses, eerie marshes, impossible skies and magnificent monsters produce dark fantasy with uncommon visual authority. The music knows when to turn a battle into a grand occasion, and when silence is the most eloquent companion.</p><p><strong>What a Crown Cannot Settle</strong></p><p>Even after a victory, Elden Ring leaves reasons to return. Another weapon suggests a different style of combat; an overlooked path may change a familiar region; a character\'s unfinished story may beckon from a distant corner. Its lasting appeal is not simply a matter of size, but of the feeling that this place existed long before we arrived and will not rearrange itself for our convenience.</p><p>And so, my dear reader, a final confidence: after so much danger, the strangest thing may not be earning the right to a crown. It is discovering that we still desire it, even after seeing the kingdom it comes with.</p>', NULL, '<p>My dear reader, we must now dispense with discretion. To enter the Erdtree, the Tarnished must set it ablaze; along the usual path, Melina sacrifices herself to kindle the flame.</p><p>Near the end, Radagon is revealed to be Marika\'s other self. After defeating Radagon, the Tarnished faces the Elden Beast.</p><p>Victory does not dictate a single future: the Ring may be restored under different principles, or the Tarnished may follow Ranni into the Age of Stars.</p><p>The bitterest irony is that winning a throne does not answer who deserves to rule.</p>', 'Elden Ring — A Crown Worth More Than Courage', 'Elden Ring invites you into a dark fantasy action RPG of fierce battles, hidden wonders and an open world where every path tests the price of a crown.', '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(16, 7, 'fr', 1, 0, 'elden-ring-une-couronne-au-prix-de-mille-epreuves', 'Elden Ring — Une couronne au prix de mille épreuves', '<p>Cher lecteur, méfiez-vous des couronnes proposées trop facilement. Dans Elden Ring, les châteaux tombent en ruine, les demi-dieux se disputent les décombres et chaque détour promet un secret. Le pouvoir paraît fort séduisant, jusqu\'au moment de régler l\'addition.</p>', '<p>Dans l\'Entre-terre, le Cercle d\'Elden a été brisé. Les demi-dieux se sont emparés de ses fragments et ont plongé le royaume dans une guerre sans vainqueur. Un Sans-éclat, autrefois banni, revient, guidé par la grâce, dans l\'espoir de devenir Seigneur d\'Elden.</p><p>Des forteresses abandonnées aux profondeurs oubliées, il doit affronter de redoutables adversaires et retrouver les traces de l\'Ordre d\'or. Au pied de l\'Arbre-Monde se dessine un destin capable de changer le royaume, à condition d\'y parvenir vivant.</p>', '<p>Cher lecteur, permettez-moi une recommandation que toute personne raisonnable devrait conserver précieusement : avant d\'accepter une couronne, visitez le royaume. Dans Elden Ring, le titre de Seigneur d\'Elden possède une sonorité admirable. Hélas, l\'Entre-terre offre des forteresses effondrées, des demi-dieux querelleurs et des créatures qui réservent aux visiteurs un accueil déplorable. Le plus étonnant est que l\'on désire malgré tout poursuivre la promenade.</p><p>Paru en 2022, cet action-RPG de FromSoftware, réalisé sous la direction de Hidetaka Miyazaki, s\'appuie sur une mythologie à laquelle George R. R. Martin a contribué. Ici, nul maître de cérémonie ne prend la peine de présenter les grandes familles et leurs anciennes offenses. On découvre leurs drames par fragments, comme un invité arrivé après un scandale dont personne ne souhaite parler. Je dois avouer que ce silence a beaucoup d\'allure.</p><p><strong>Le royaume appartient aux curieux</strong></p><p>Imaginez une plaine immense, un arbre lumineux dominant l\'horizon et des constructions lointaines dont on ignore tout. Une route semble mener vers un château ; une autre disparaît derrière les collines. Le jeu ne vous prend pas constamment par la main. Il vous laisse choisir, avec cette désinvolture délicieuse des hôtes qui savent que chaque porte cache une surprise.</p><p>C\'est là que son monde ouvert se distingue. L\'exploration ne consiste pas seulement à remplir une liste d\'occupations. Un détour peut dévoiler une grotte, un personnage inattendu ou un paysage souterrain d\'une beauté inquiétante. Torrent, votre monture spectrale, facilite ces escapades. Je vous déconseille toutefois de prendre sa vitesse pour un sauf-conduit : certaines rencontres ne respectent guère les usages de l\'hospitalité.</p><p>Vous étiez parti chercher une forteresse ? Vous voilà perdu dans des galeries anciennes. Vous pensiez pouvoir triompher d\'un adversaire d\'un seul assaut ? Quelques secondes suffisent parfois à corriger cette présomption. Dans Elden Ring, la liberté a quelque chose d\'enivrant précisément parce qu\'elle ne nous protège pas de nos propres sottises.</p><p><strong>L\'art de tomber avec dignité</strong></p><p>Les combats exigent de la patience, le sens du rythme et un talent peu répandu : reconnaître ses erreurs. Frapper sans réfléchir conduit souvent à un résultat humiliant. Observer un ennemi, comprendre ses gestes et profiter d\'une ouverture procure, en revanche, une satisfaction que les victoires trop faciles ignorent. L\'échec n\'est pas une disgrâce ; c\'est un professeur particulièrement sévère.</p><p>Heureusement, le jeu ne vous impose pas une seule manière d\'apprendre. Épées, armes colossales, sorcelleries, incantations, compétences d\'armes et invocations offrent d\'innombrables possibilités. Un adversaire insurmontable aujourd\'hui pourra devenir plus abordable après une expédition, quelques améliorations ou un changement de stratégie. Entre une étude savante des arcanes et un marteau d\'une taille inconvenante, chacun trouvera son tempérament.</p><p>Ne feignons pourtant pas de croire que toutes les épreuves sont parfaitement équilibrées. Certains boss réclament une précision épuisante, la caméra peut devenir peu coopérative pendant les affrontements les plus agités et la difficulté connaît des irrégularités. Je ne reprocherai à aucun lecteur de préférer quitter une salle plutôt que de s\'y faire terrasser toute la soirée. La persévérance est une vertu ; l\'entêtement n\'est pas toujours une qualité mondaine.</p><p><strong>Des secrets que nul ne se presse de confier</strong></p><p>Marika, l\'Ordre d\'or et les demi-dieux ne livrent pas leur histoire dans de longs discours bien ordonnés. Les dialogues restent allusifs, les descriptions d\'objets recèlent des indices et les ruines semblent conserver les traces d\'anciens conflits. On avance en assemblant des morceaux de vérité, et cette participation donne au monde une profondeur remarquable.</p><p>Il faut néanmoins accepter une part de frustration. Certaines quêtes progressent sans indication précise, et manquer une rencontre peut laisser une histoire en suspens. Le mystère stimule l\'imagination tant qu\'il offre des pistes ; il agace lorsqu\'il dissimule jusqu\'à l\'existence du chemin à suivre.</p><p>La direction artistique récompense amplement les regards attentifs. Châteaux vertigineux, marais oppressants, ciels irréels et silhouettes monstrueuses composent une fantasy sombre que l\'on reconnaîtrait entre mille. La musique, tantôt majestueuse, tantôt discrète, comprend admirablement quand il convient de faire résonner un affrontement et quand le silence parle mieux.</p><p><strong>La couronne ne résout pas tout</strong></p><p>Longtemps après avoir franchi une étape décisive, on peut revenir pour essayer une autre arme, explorer un passage négligé ou retrouver les fils d\'une histoire interrompue. Cette envie durable ne provient pas seulement de l\'étendue de l\'Entre-terre. Elle naît de la conviction que ce royaume avait ses secrets avant nous et qu\'il n\'entend pas nous les offrir sans effort.</p><p>Entre nous, cher lecteur, voici ma dernière confidence : après tant de dangers, le plus étrange n\'est peut-être pas de mériter une couronne. C\'est de la désirer encore, lorsqu\'on connaît l\'état du royaume qu\'elle oblige à gouverner.</p>', NULL, '<p>Cher lecteur, il nous faut désormais renoncer à la discrétion. Pour pénétrer dans l\'Arbre-Monde, le Sans-éclat doit l\'embraser ; dans le déroulement habituel, Melina se sacrifie pour allumer la flamme.</p><p>Au terme du voyage, Radagon se révèle être l\'autre aspect de Marika. Après l\'avoir vaincu, on affronte la Bête d\'Elden.</p><p>Plusieurs issues demeurent possibles : restaurer le Cercle d\'Elden selon différents principes ou accompagner Ranni vers l\'Âge des étoiles.</p><p>Voilà une ironie digne de ce royaume : obtenir le pouvoir ne dit rien de la manière dont il faudrait l\'exercer.</p>', 'Elden Ring — Une couronne au prix de mille épreuves', 'Elden Ring vous ouvre un monde de dark fantasy, de combats exigeants et de secrets enfouis. Cher lecteur, découvrez ce que vaut vraiment une couronne.', '2026-11-10 16:18:17', '2026-11-10 16:18:17');
INSERT INTO `post_localizations` (`id`, `post_id`, `locale`, `is_active`, `is_featured`, `slug`, `title`, `excerpt`, `synopsis`, `review`, `article_body`, `spoilers`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(17, 7, 'de', 1, 0, 'elden-ring-eine-krone-die-mehr-als-mut-verlangt', 'Elden Ring – Eine Krone, die mehr als Mut verlangt', '<p>Mein geschätzter Leser, prüfen Sie ein Königreich, bevor Sie seine Krone annehmen. In Elden Ring erwarten Sie zerfallene Burgen, streitende Halbgötter und gefährliche Geheimnisse. Jeder Umweg verführt zur Neugier, doch kein Thron wird ohne Preis vergeben.</p>', '<p>Im Zwischenland wurde der Eldenring zerbrochen. Die Halbgötter rissen seine Bruchstücke an sich und stürzten das Reich in einen erbitterten Krieg. Ein einst verbannter Befleckter kehrt zurück, von der Gnade geführt und mit der Aussicht, Eldenfürst zu werden.</p><p>Seine Reise führt durch verfallene Burgen, gefährliche Sümpfe und vergessene Gewölbe. Um den Erdenbaum zu erreichen, muss er mächtige Gegner bezwingen und die Spuren der Goldenen Ordnung entziffern. Ob dieses Reich überhaupt einen neuen Herrscher verdient, bleibt eine andere Frage.</p>', '<p>Mein geschätzter Leser, erlauben Sie mir einen wohlmeinenden Rat: Nehmen Sie niemals eine Krone an, bevor Sie das zugehörige Königreich besichtigt haben. In Elden Ring klingt die Aussicht, Eldenfürst zu werden, ausgesprochen vornehm. Leider befindet sich das Zwischenland in einem beklagenswerten Zustand. Burgen zerfallen, Halbgötter streiten um Macht, und zahlreiche Bewohner betrachten Fremde offenbar als unerwünschte Störung. Dennoch lässt man sich erstaunlich gern auf diese Reise ein.</p><p>Das 2022 veröffentlichte Action-Rollenspiel von FromSoftware entstand unter der Leitung von Hidetaka Miyazaki. George R. R. Martin wirkte an der Mythologie dieser Welt mit. Das Ergebnis ist eine düstere Fantasy, die ihre Geheimnisse nicht bei der ersten Begegnung ausplaudert. Man fühlt sich wie ein Gast, der nach einem gewaltigen Familienskandal auf einem Adelssitz eintrifft und feststellen muss, dass niemand darüber sprechen möchte. Eine gewisse Verschwiegenheit steht dieser Welt ausgezeichnet.</p><p><strong>Ein Reich für neugierige Gemüter</strong></p><p>Stellen Sie sich vor, Sie treten hinaus auf eine weite Ebene. In der Ferne schimmern Türme, und über allem erhebt sich der leuchtende Erdenbaum. Ein Weg führt zu einer Festung, ein anderer verschwindet hinter einem Hügel. Elden Ring schreibt Ihnen selten vor, welche Einladung Sie zuerst annehmen sollen. Stattdessen stellt es die verführerische Frage: Was mag dort drüben liegen?</p><p>Gerade darin liegt seine größte Stärke. Die offene Spielwelt besteht nicht einfach aus Aufgaben, die man pflichtbewusst abhakt. Eine kleine Abweichung kann in einer verborgenen Höhle, einer vergessenen Ruine oder einem unerwarteten Gespräch enden. Auf dem Geisterross Sturmwind lässt sich diese Landschaft herrlich durchqueren. Freilich sollte man die Fähigkeit, irgendwohin zu reiten, nicht mit einem Anspruch auf freundlichen Empfang verwechseln.</p><p>Vielleicht wollten Sie ursprünglich eine Burg aufsuchen und verbringen stattdessen Stunden in unterirdischen Regionen. Vielleicht begegnen Sie einem Gegner mit unerschütterlichem Selbstvertrauen und werden wenige Augenblicke später eines Besseren belehrt. Das Spiel schenkt uns Freiheit, ohne uns vor den Folgen übertriebener Zuversicht zu bewahren. Ich halte das für eine ausgesprochen ehrliche Form der Gastfreundschaft.</p><p><strong>Die Kunst, mit Würde zu scheitern</strong></p><p>Im Kampf zählen Aufmerksamkeit, Geduld und gutes Timing. Wer wahllos zuschlägt, lernt meist schnell, wie unerfreulich Nachlässigkeit sein kann. Wer hingegen Bewegungsmuster erkennt, Ausdauer einteilt und den passenden Augenblick abwartet, erlebt Siege von besonderem Gewicht. Niederlagen sind hier keine seltenen Missgeschicke, sondern ein fester Bestandteil der Ausbildung.</p><p>Zum Glück verlangt Elden Ring nicht, dass alle denselben Weg einschlagen. Schwerter, mächtige Hämmer, Zaubereien, Anrufungen, Waffenkünste und Geisterbeschwörungen ermöglichen sehr unterschiedliche Spielweisen. Ein übermächtiger Gegner lässt sich vielleicht später bezwingen, nachdem man andere Gebiete erkundet, die Ausrüstung verbessert oder die Taktik geändert hat. Zwischen gelehrter Magie und einem erschreckend großen Schwert findet nahezu jeder seine bevorzugte Sprache.</p><p>Doch wir sollten die Mühen nicht verklären. Manche Bosse verlangen eine beinahe erschöpfende Präzision, die Kamera verliert in hektischen Begegnungen gelegentlich den Überblick, und der Schwierigkeitsgrad wirkt nicht immer gleichmäßig. Ich könnte keinem Leser verdenken, wenn er nach dem zehnten vergeblichen Versuch eine Pause einlegt. Beharrlichkeit ist ehrenwert; ein vollkommen ruinierter Abend muss es nicht sein.</p><p><strong>Geheimnisse hinter verschlossenen Türen</strong></p><p>Die Geschichte um Marika, die Goldene Ordnung und die rivalisierenden Halbgötter entfaltet sich in Andeutungen. Figuren sprechen rätselhaft, Gegenstandsbeschreibungen bergen wichtige Hinweise, und verwüstete Orte erzählen von Konflikten, die lange vor unserer Ankunft begannen. Wer aufmerksam liest und beobachtet, setzt allmählich ein faszinierendes Bild zusammen.</p><p>Diese Zurückhaltung hat allerdings ihren Preis. Manche Figurenquests entwickeln sich ohne klare Wegweiser weiter. Eine verpasste Begegnung kann dazu führen, dass ein Handlungsfaden unvermittelt endet. Ein Geheimnis ist reizvoll, wenn es zum Nachforschen einlädt; weniger reizvoll ist es, wenn man nicht einmal erfährt, dass eine Tür existiert.</p><p>Umso eindrucksvoller ist die künstlerische Gestaltung. Gewaltige Festungen, bedrückende Sümpfe, unwirkliche Himmel und furchterregend schöne Wesen verleihen dem Zwischenland eine unverwechselbare Identität. Die Musik erhebt große Kämpfe zu beinahe zeremoniellen Ereignissen, während stille Wege gerade durch ihre Einsamkeit wirken.</p><p><strong>Eine Krone beantwortet keine Fragen</strong></p><p>Selbst nach einem großen Sieg bleibt vieles zu entdecken. Andere Waffen eröffnen neue Strategien, übersehene Wege führen zu unerwarteten Orten, und unbeendete Geschichten laden zur Rückkehr ein. Die anhaltende Faszination erklärt sich nicht bloß aus der Größe der Welt. Sie entsteht aus dem Gefühl, dass dieses Reich lange vor uns bestand und sich gewiss nicht nach unseren Wünschen richten wird.</p><p>Und nun, mein geschätzter Leser, eine letzte vertrauliche Bemerkung: Das Erstaunlichste ist vielleicht nicht, dass wir eine Krone erringen können. Es ist, dass wir sie noch immer begehren, obwohl wir das Königreich inzwischen nur allzu gut kennen.</p>', NULL, '<p>Mein geschätzter Leser, nun müssen wir auf jede Zurückhaltung verzichten. Um den Erdenbaum zu betreten, muss der Befleckte ihn in Brand setzen; auf dem gewöhnlichen Weg opfert sich Melina für die Flamme.</p><p>Am Ende offenbart sich Radagon als ein anderer Aspekt Marikas. Nach dem Sieg über Radagon folgt der Kampf gegen die Eldenbestie.</p><p>Anschließend gibt es verschiedene Möglichkeiten: den Eldenring unter unterschiedlichen Vorzeichen wiederherzustellen oder Ranni in das Zeitalter der Sterne zu begleiten.</p><p>Die eigentliche Ironie bleibt: Eine Krone zu gewinnen beantwortet noch nicht, wie man regieren sollte.</p>', 'Elden Ring – Eine Krone, die mehr als Mut verlangt', 'Elden Ring entführt Sie in düstere Fantasy, erbitterte Kämpfe und eine offene Welt voller Geheimnisse. Mein Leser, entdecken Sie den Preis einer Krone.', '2026-11-10 16:18:17', '2026-11-10 16:18:17'),
(18, 8, 'en-us', 1, 0, 'wednesday-a-most-unwelcome-lesson-in-good-manners', 'Wednesday — A Most Unwelcome Lesson in Good Manners', '<p>My dear reader, at Nevermore Academy, monsters attend class and Wednesday Addams considers a smile an unfortunate weakness. Between sinister murders, family secrets and an unlikely friendship, Netflix\'s gothic mystery makes darkness surprisingly difficult to resist.</p>', '<p>After an incident at her former school, Wednesday Addams is sent to Nevermore Academy, a refuge for students with unusual abilities. There, the fiercely independent teenager investigates disturbing crimes near the town of Jericho while confronting mysterious visions and her family\'s complicated past. Alongside her irrepressibly cheerful roommate Enid, Wednesday discovers that secrets do not stay buried for long. Returning to Nevermore brings fresh threats, unsettled relationships and more mysteries than even she can comfortably control.</p>', '<p>My dear reader, imagine a school where a student might turn into a wolf, another might glimpse the future, and the most alarming creature in the room is a young lady who considers affection a personal insult. Welcome to Nevermore Academy. In Wednesday, Netflix invites us into the Addams family\'s peculiar world through its least accommodating member. I must confess that few heroines have made such determined efforts to discourage their admirers while attracting so many of them.</p><p>Created by Alfred Gough and Miles Millar, with Tim Burton helping shape its visual identity as a director and executive producer, the series places Wednesday Addams at the center of a supernatural mystery. It is not simply a school adventure dressed in black lace. Beneath the sharp remarks and funeral-ready wardrobe lie questions about belonging, trust and the uncomfortable necessity of caring for other people.</p><p><strong>A Young Lady Who Declines to Be Pleasant</strong></p><p>Jenna Ortega\'s performance is the series\' finest instrument. A slight movement of the eyes, a measured pause or a perfectly dry answer can make Wednesday more expressive than characters who spend entire scenes explaining themselves. She is intelligent, stubborn and frequently insufferable. Naturally, that makes her excellent company from a safe distance.</p><p>Yet the series is wise enough to challenge her self-sufficiency. Wednesday believes that attachments interfere with clear judgment, but her investigation repeatedly depends on people she would rather keep at arm\'s length. Her certainty can be impressive; it can also make her cruel. That distinction matters. An interesting heroine need not always be an agreeable one, provided her errors are permitted to have consequences.</p><p>Consider Enid Sinclair, portrayed by Emma Myers. Her appetite for color and companionship appears designed to torment her roommate\'s devotion to gloom. Their contrasting temperaments supply much of the comedy, but the friendship develops into something more substantial. Enid has her own anxieties about fitting in, and Wednesday has much to learn about accepting help without treating it as defeat. If you ask me, dear reader, their exchanges are often more revealing than the threats stalking the school grounds.</p><p><strong>A Gothic Academy with Excellent Secrets</strong></p><p>Nevermore is an inspired setting. Its pointed architecture, shadowy halls and elaborate traditions make the supernatural feel like an ordinary part of the timetable. The neighboring town of Jericho offers a useful contrast: supposedly respectable society is not necessarily more welcoming than a school full of outcasts. One suspects that a few of its residents could benefit from Nevermore\'s lessons in tolerance.</p><p>The production understands the pleasures of gothic fantasy. Costumes, music and carefully composed spaces create an atmosphere that is theatrical without entirely swallowing the people who inhabit it. A disembodied hand can become a persuasive companion; a school dance can become an occasion for splendidly unsettling humor. Wednesday\'s popularity owes much to this recognizable character of its own, rather than to darkness for darkness\'s sake.</p><p>The mysteries supply momentum, although their machinery is sometimes less elegant than their surroundings. Red herrings can feel conspicuous, and certain young-adult conflicts are resolved with more convenience than subtlety. The first season occasionally mistakes the accumulation of suspects for deeper suspense. It remains entertaining, but I would not pretend every revelation deserves a standing ovation.</p><p><strong>When the Family Comes Calling</strong></p><p>The second season wisely opens Nevermore\'s doors wider to the Addams family. Morticia, Gomez and Pugsley play larger roles, giving Wednesday fresh opportunities to discover that family affection is difficult to evade, however efficiently one scowls. The expanded ensemble also brings new dangers and a darker mystery, while allowing the relationship between Wednesday and Enid to carry greater emotional weight.</p><p>Expansion has its cost. At times, so many competing threads demand attention that the central investigation loses some of its sharpness. Still, the willingness to examine Wednesday\'s limitations, especially her need to control every outcome, gives the continuation a purpose beyond repeating the first year\'s formula.</p><p>What endures is a delightful contradiction: a girl determined to be unlike everyone else must learn that independence is not the same as isolation. Her world is full of creatures that polite society calls monstrous, yet its most recognizable cruelties remain entirely human.</p><p>And so, my dear reader, I leave Nevermore with a modest recommendation. Should Wednesday ever offer you friendship, accept it with gratitude, refrain from embracing her, and keep your compliments mercifully brief. That may be the closest thing to good manners she is prepared to tolerate.</p>', NULL, '<p>My dear reader, we must now open the locked cabinet. In the first season, Tyler Galpin is revealed as the murderous Hyde, manipulated by Marilyn Thornhill, whose true identity is Laurel Gates. Wednesday defeats the resurrected Joseph Crackstone, but her troubles are far from finished. In the second season, Thing is revealed to be the severed hand of Isaac Night. Enid transforms into an alpha werewolf to save Wednesday and cannot immediately return to human form. The final revelation is especially unpleasant: the supposedly missing Ophelia is alive, hidden in her mother Hester\'s house, and threatening Wednesday.</p>', 'Wednesday — A Most Unwelcome Lesson in Good Manners', 'Wednesday, Netflix\'s gothic mystery series, pairs dark humor, supernatural secrets and unlikely friendship at Nevermore Academy. Enter if you dare, dear reader.', '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(19, 8, 'fr', 1, 0, 'mercredi-lelegance-delicieuse-des-tenebres', 'Mercredi — L\'élégance délicieuse des ténèbres', '<p>Cher lecteur, à l\'Académie Nevermore, les monstres étudient et Mercredi Addams considère le sourire comme une regrettable faiblesse. Meurtres, secrets de famille et amitié improbable : la série Netflix transforme les ombres en un plaisir singulier.</p>', '<p>Exclue de son ancien établissement, Mercredi Addams rejoint l\'Académie Nevermore, pensionnat réservé à de jeunes êtres dotés de facultés extraordinaires. Peu disposée à se faire des amis, elle enquête sur des morts inquiétantes autour de Jericho, tout en découvrant ses propres visions et des secrets familiaux. Sa colocataire Enid, aussi lumineuse qu\'elle est sombre, complique délicieusement ses projets de solitude. Lorsqu\'elle revient à Nevermore, de nouvelles menaces et d\'anciennes tensions l\'attendent.</p>', '<p>Cher lecteur, imaginez un établissement où les loups-garous passent des examens, où les visions ne dispensent pas des devoirs et où la jeune fille la moins sociable possède le don d\'attirer tous les regards. Bienvenue à Nevermore. La série Mercredi, connue sous son titre original Wednesday, nous présente une héroïne qui considérerait probablement votre invitation à prendre le thé comme une déclaration de guerre. J\'avoue trouver cette franchise presque reposante.</p><p>Créée par Alfred Gough et Miles Millar, avec Tim Burton à la réalisation de plusieurs épisodes et à la production, cette fiction de Netflix explore l\'univers de la famille Addams à travers une enquête surnaturelle. Elle aurait pu se contenter de costumes noirs et de quelques plaisanteries macabres. Elle préfère également parler de solitude, de confiance et de ces sentiments dont certaines personnes se défendent avec autant d\'ardeur que d\'autres les recherchent.</p><p><strong>Une demoiselle qui refuse de sourire</strong></p><p>Jenna Ortega accomplit une prouesse de retenue. Son regard fixe, ses silences calculés et ses réponses tranchantes donnent à Mercredi un caractère immédiatement reconnaissable. La jeune fille possède une intelligence redoutable, une curiosité dangereuse et une opinion assez médiocre de presque tout le monde. Je ne lui confierais pas l\'organisation d\'un bal, mais je lui demanderais volontiers de résoudre une disparition.</p><p>Le charme du personnage ne tient pourtant pas à son infaillibilité. Son désir de tout maîtriser l\'amène à négliger ceux qui lui viennent en aide. Ses jugements peuvent blesser, ses hypothèses se révéler fausses, et son indépendance se transformer en orgueil. Voilà qui rend son parcours intéressant. Une héroïne dont les défauts n\'auraient jamais de conséquences ne serait qu\'une jolie statue dans un couloir gothique.</p><p>Face à elle, Enid Sinclair, interprétée par Emma Myers, apporte des couleurs que Mercredi aurait sans doute fait interdire par décret. Sa joie apparente ne doit pas tromper : Enid connaît elle aussi l\'inquiétude de ne pas correspondre aux attentes d\'autrui. Leur amitié progresse entre malentendus, froissements et gestes sincères. Entre nous, cher lecteur, les instants où ces deux demoiselles tentent de se comprendre valent parfois davantage que les indices d\'un meurtre.</p><p><strong>Une académie où les secrets sont bien élevés</strong></p><p>L\'architecture de Nevermore, ses vitraux, ses couloirs obscurs et ses traditions donnent à l\'histoire une somptueuse allure gothique. La ville voisine de Jericho prétend incarner un monde plus normal. Or, comme chacun sait, les gens qui revendiquent bruyamment leur normalité ne sont pas toujours les plus accueillants. La série s\'amuse de cette contradiction sans ignorer ce qu\'elle révèle sur l\'exclusion.</p><p>Le soin apporté aux costumes, à la musique et à la mise en scène renforce cette identité. Une main vivante peut exprimer davantage de loyauté que bien des personnages dotés d\'un visage complet. Une fête scolaire devient une scène d\'humour délicieusement étrange. Rien n\'est tout à fait raisonnable, mais l\'ensemble conserve une cohérence qui explique une bonne part de son attrait populaire.</p><p>Je dois néanmoins soulever une réserve. La première saison multiplie parfois les fausses pistes avec une insistance qui affaiblit leur subtilité. Quelques tensions adolescentes paraissent plus convenues que les mystères qui les entourent. Le décor est somptueux ; il ne rend pas automatiquement chaque rebondissement ingénieux. Notre chère Mercredi, si prompte à critiquer ses semblables, devrait comprendre cette exigence.</p><p><strong>La famille, ce fâcheux attachement</strong></p><p>La deuxième saison ouvre davantage la porte à Morticia, Gomez et Pugsley. L\'univers s\'élargit, les secrets familiaux prennent du poids et Mercredi doit composer avec des liens qu\'elle préférerait peut-être classer parmi les nuisances. Les nouvelles menaces nourrissent le suspense, tandis que la place accordée à Enid permet aux sentiments de s\'exprimer sans transformer notre héroïne en personne subitement aimable.</p><p>Cette générosité narrative crée parfois un encombrement. Les intrigues se croisent à un rythme si soutenu que l\'enquête principale perd momentanément de son éclat. Pourtant, l\'évolution de Mercredi conserve son intérêt : apprendre que l\'on ne peut ni prévoir tous les dangers ni protéger les autres à leur place constitue une leçon que même les esprits les plus brillants tardent à accepter.</p><p>Ce que j\'emporte de Nevermore, cher lecteur, n\'est pas seulement le souvenir de ses sombres couloirs. C\'est cette idée délicieuse qu\'une personne peut cultiver sa différence sans condamner toute intimité. Mercredi n\'a nul besoin de devenir souriante pour grandir. Il lui suffirait peut-être d\'admettre qu\'une main tendue n\'est pas toujours un piège. Mais ne lui répétez surtout pas que nous avons parlé de tendresse : elle pourrait y voir une offense irréparable.</p>', NULL, '<p>Cher lecteur, refermons la porte avant de révéler les secrets. Dans la première saison, Tyler Galpin est le Hyde meurtrier, manipulé par Marilyn Thornhill, qui se nomme en réalité Laurel Gates. Mercredi affronte aussi Joseph Crackstone, ramené à la vie. Dans la deuxième saison, on découvre que la Chose est la main autrefois tranchée d\'Isaac Night. Enid devient une louve alpha pour sauver son amie et ne peut aussitôt reprendre forme humaine. Enfin, la tante Ophélie, prétendument disparue, est enfermée chez sa mère Hester et menace Mercredi. Charmante réunion de famille, n\'est-ce pas ?</p>', 'Mercredi — L\'élégance délicieuse des ténèbres', 'Mercredi (Wednesday), série gothique Netflix, mêle humour noir, mystères surnaturels et amitié à Nevermore. Cher lecteur, entrez dans la pénombre avec elle.', '2026-11-10 16:48:06', '2026-11-10 16:48:06'),
(20, 8, 'de', 1, 0, 'wednesday-eine-schule-fur-schlechte-manieren', 'Wednesday – Eine Schule für schlechte Manieren', '<p>Mein geschätzter Leser, in der Nevermore Academy sind Werwölfe beinahe gewöhnlich. Ungewöhnlicher ist Wednesday Addams, die jedes Lächeln für einen bedauerlichen Fehler hält. Zwischen Mordfällen, Familiengeheimnissen und einer seltsamen Freundschaft entfaltet sich düsterer Charme.</p>', '<p>Nach einem folgenschweren Vorfall an ihrer bisherigen Schule kommt Wednesday Addams an die Nevermore Academy, ein Internat für Jugendliche mit ungewöhnlichen Fähigkeiten. Dort untersucht sie rätselhafte Todesfälle in der Nähe von Jericho, erlebt unheimliche Visionen und stößt auf Geheimnisse ihrer Familie. Ihre lebensfrohe Mitbewohnerin Enid macht ihr den entschlossenen Rückzug aus jeder Freundschaft nicht gerade leicht. Auch bei ihrer Rückkehr nach Nevermore warten neue Bedrohungen und ungelöste Konflikte auf die junge Ermittlerin.</p>', '<p>Mein geschätzter Leser, stellen Sie sich ein Internat vor, in dem Werwölfe am Unterricht teilnehmen, übersinnliche Visionen zum Alltag gehören und das unheimlichste Wesen weit und breit ein Mädchen ist, das Freundlichkeit für eine lästige Schwäche hält. Willkommen an der Nevermore Academy. In Wednesday begegnen wir einer jungen Dame, die jede Form von Zuneigung mit geradezu bewundernswerter Entschlossenheit abwehrt. Dass sie ausgerechnet dadurch so viel Aufmerksamkeit gewinnt, dürfte sie zutiefst verärgern.</p><p>Die von Alfred Gough und Miles Millar geschaffene Netflix-Serie erzählt eine übernatürliche Kriminalgeschichte aus der Welt der Addams Family. Tim Burton prägt als Regisseur und ausführender Produzent ihre Bildsprache mit. Doch die schwarzen Kleider und düsteren Flure sind nicht der eigentliche Kern. Dahinter geht es um Zugehörigkeit, Vertrauen und die unbequeme Erkenntnis, dass selbst ein außergewöhnlich kluger Mensch andere Menschen brauchen könnte.</p><p><strong>Eine junge Dame von bemerkenswerter Unfreundlichkeit</strong></p><p>Jenna Ortega verleiht Wednesday eine Präzision, die selten große Gesten verlangt. Ein Blick, eine winzige Pause, ein trocken formulierter Satz genügen, und schon versteht man, weshalb ihre Mitmenschen zwischen Bewunderung und Verzweiflung schwanken. Wednesday ist scharfsinnig, eigensinnig und mitunter unerträglich hochmütig. Als Gesprächspartnerin wäre sie vermutlich eine Herausforderung; aus sicherer Entfernung betrachtet ist sie ganz ausgezeichnete Gesellschaft.</p><p>Glücklicherweise wird sie nicht als unfehlbares Genie behandelt. Ihr Misstrauen führt zu vorschnellen Urteilen, ihre Rücksichtslosigkeit verletzt andere, und ihr Wunsch, jede Untersuchung allein zu führen, macht manches erst gefährlich. Das ist wesentlich für die Geschichte. Wer von allen Höflichkeitsregeln befreit sein möchte, sollte nicht gleichzeitig von ihren Folgen verschont bleiben.</p><p>Besonders schön zeigt sich das im Verhältnis zu Enid Sinclair, gespielt von Emma Myers. Enid liebt Farben, Geselligkeit und alles, was Wednesday am liebsten verbieten würde. Doch hinter ihrer Fröhlichkeit steckt die Angst, den Erwartungen anderer nicht zu genügen. Aus dem Gegensatz der beiden entsteht mehr als ein wiederkehrender Scherz. Ihre Freundschaft wächst dort, wo jede lernen muss, die Eigenheiten der anderen auszuhalten. Unter uns, mein Leser: Ein ehrliches Gespräch zwischen diesen beiden ist gelegentlich spannender als eine weitere Spur im Mordfall.</p><p><strong>Ein Internat mit vorzüglichen Geheimnissen</strong></p><p>Nevermore bietet eine eindrucksvolle Bühne. Hohe Fenster, finstere Gänge und sonderbare Schultraditionen lassen das Übernatürliche beinahe alltäglich erscheinen. Im benachbarten Jericho geben sich die vermeintlich normalen Menschen betont vernünftig. Das macht sie jedoch keineswegs automatisch toleranter. Die Serie versteht es, mit dieser Umkehrung gesellschaftliche Vorurteile sichtbar zu machen, ohne ihren Humor aufzugeben.</p><p>Auch Kostüme, Musik und Ausstattung tragen zur unverwechselbaren gotischen Atmosphäre bei. Eine selbstständig umherlaufende Hand kann zum treuesten Vertrauten werden, und ein Schulball besitzt plötzlich eine erfreulich makabre Note. Solche Einfälle erklären den Reiz der Serie besser als jede Behauptung, Schwarz sei grundsätzlich eine besonders interessante Farbe.</p><p>Freilich sind die Ermittlungen nicht immer so raffiniert, wie ihre Umgebung vermuten lässt. Manche falsche Fährte wird allzu auffällig ausgelegt, und einige jugendliche Streitigkeiten wirken vertrauter, als es einer so eigenwilligen Welt guttut. Die erste Staffel hat Tempo und Persönlichkeit, aber nicht jede Wendung besitzt dieselbe Überzeugungskraft. Selbst Wednesday würde ein schwaches Argument gewiss nicht allein deshalb gelten lassen, weil es in einer prächtigen Bibliothek vorgetragen wird.</p><p><strong>Verwandtschaft lässt sich schwer aussperren</strong></p><p>In der zweiten Staffel erhält die Familie Addams mehr Raum. Morticia, Gomez und Pugsley werden stärker in das Geschehen eingebunden, während neue Gefahren die Schule heimsuchen. Das bereichert die Welt und zwingt Wednesday, sich mit familiären Bindungen auseinanderzusetzen, die sich nicht durch eine verschlossene Zimmertür erledigen lassen. Besonders ihre Verbindung zu Enid gewinnt dabei an Bedeutung.</p><p>Der größere Figurenkreis bringt allerdings auch Unruhe. Mehrere Handlungsstränge konkurrieren um Aufmerksamkeit, sodass der zentrale Kriminalfall zeitweise an Schärfe verliert. Doch das fortgesetzte Thema überzeugt: Wednesday muss erkennen, dass Kontrolle nicht dasselbe bedeutet wie Fürsorge. Wer Freunde schützen möchte, kann ihnen nicht jede Entscheidung abnehmen.</p><p>Was bleibt, mein geschätzter Leser, ist eine reizvolle Widersprüchlichkeit. Ausgerechnet ein Mädchen, das seine Andersartigkeit wie ein Wappen trägt, entdeckt langsam den Wert echter Nähe. Die Serie verlangt von ihr kein freundliches Dauerlächeln, und dafür bin ich dankbar. Sollte Wednesday Ihnen jemals die Hand zur Begrüßung reichen, nehmen Sie die Geste ernst. Eine Umarmung hingegen würde ich keinesfalls riskieren.</p>', NULL, '<p>Mein geschätzter Leser, nun werden die Geheimnisse geöffnet. In Staffel eins entpuppt sich Tyler Galpin als mörderischer Hyde, den Marilyn Thornhill lenkt; tatsächlich heißt sie Laurel Gates. Wednesday stellt sich außerdem dem wiedererweckten Joseph Crackstone. In Staffel zwei erfährt die Familie, dass das Eiskalte Händchen einst Isaac Nights abgetrennte Hand war. Enid verwandelt sich zur Rettung Wednesdays in einen Alpha-Werwolf und kann zunächst nicht in Menschengestalt zurückkehren. Schließlich zeigt sich, dass die vermisste Tante Ophelia noch lebt: Sie wird im Haus ihrer Mutter Hester verborgen gehalten und bedroht Wednesday. Eine ausgesprochen ungemütliche Familienangelegenheit, finden Sie nicht?</p>', 'Wednesday – Eine Schule für schlechte Manieren', 'Wednesday, die düstere Netflix-Serie, verbindet schwarzen Humor, rätselhafte Morde und Freundschaft an der Nevermore Academy. Mein Leser, treten Sie näher.', '2026-11-10 16:48:06', '2026-11-10 16:48:06');

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
(10, 2, 'en-us', '9e11e1835aa41e24facfc2cf032574b775e3034a44cc71b6ee86ddc5667ff0f4', '2026-11-06 19:38:39'),
(11, 2, 'en-us', 'dddfad6b31e231e9db291c2a1d44065a0c4819100fe4588e8a6c794a1c6ad1c5', '2026-11-09 21:23:12'),
(12, 3, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', '2026-11-09 21:32:54'),
(13, 2, 'en-us', 'af4fe0c712d171a0af9af5b2237171356fadb915eb4d7b04151b16e845d82a0c', '2026-11-09 21:44:13'),
(14, 3, 'fr', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', '2026-11-09 21:55:22'),
(15, 3, 'de', 'b6290148b52537bfff05ce91a420abd8d960d2da79ddd502cdb58a348356b4a8', '2026-11-09 21:55:53'),
(16, 4, 'en-us', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', '2026-11-09 23:22:01'),
(17, 4, 'fr', 'dc1eeca79d24ea418c5637e8177efd161f252a85e858de0339ded75835a2dfc9', '2026-11-10 00:01:32'),
(18, 6, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', '2026-11-10 15:55:33'),
(19, 5, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', '2026-11-10 16:24:33'),
(20, 4, 'en-us', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', '2026-11-10 16:26:41'),
(21, 7, 'de', '8c08de737af782e9d1c4cd1f242f902a9bd022108a4eb2c38e571194073ca4fd', '2026-11-10 16:38:23');

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
('4t5Xx34jxngCl1sOzliQJ0uEnANLkBVQ0aWTLW3I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTTQzaDFvM09tUGJWRjU1cHE4Tk9tTWJvaEQyUE9WYVp6TVdlajJQYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE5OiJwYXNzd29yZF9oYXNoX2FkbWluIjtzOjY0OiJlYjE0MTJlMzgzOWVlZGFiMDY3ZGYyNGJjNGIxZmY2ZTFlY2ZhYjc4MzhiMWNiYWM1ZjUyNGNjZTRhNTk3YmJjIjtzOjU6ImFkbWluIjthOjA6e319', 1794311637),
('7RCBaZs7yizRFOhyjDDMOqDbNB34wR1SQCy5bwaN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR0I1SmdaaGs5SHI2UVNocFJGU1ZLT3RLWVFhcWhnZXRINHl4ZDlZeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE5OiJwYXNzd29yZF9oYXNoX2FkbWluIjtzOjY0OiJlYjE0MTJlMzgzOWVlZGFiMDY3ZGYyNGJjNGIxZmY2ZTFlY2ZhYjc4MzhiMWNiYWM1ZjUyNGNjZTRhNTk3YmJjIjtzOjU6ImFkbWluIjthOjA6e319', 1794314464),
('zY9KGan4agMxdTfu4Az1bhjmCTzyJJbzGdTjdw3u', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoieWhsRjJFcVJCNFo1MFRwemVlY3ZPYUw5ZlVhU0pZSkdPdVFVUGdwbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9lbiI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxOToicGFzc3dvcmRfaGFzaF9hZG1pbiI7czo2NDoiZWIxNDEyZTM4MzllZWRhYjA2N2RmMjRiYzRiMWZmNmUxZWNmYWI3ODM4YjFjYmFjNWY1MjRjY2U0YTU5N2JiYyI7czo1OiJhZG1pbiI7YToxOntzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTQzMTg0ODg7fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1794318539);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `genre_localizations`
--
ALTER TABLE `genre_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `post_localizations`
--
ALTER TABLE `post_localizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
