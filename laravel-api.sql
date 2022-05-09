-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2022 at 10:43 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel-api`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 1, '{\"name\":\"erikson\"}', '{\"name\":\"erikson1\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-24 22:54:18', '2022-04-24 22:54:18'),
(2, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 3, '{\"name\":\"admin\"}', '{\"name\":\"admin3\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-24 22:54:46', '2022-04-24 22:54:46'),
(3, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 1, '{\"name\":\"erikson1\"}', '{\"name\":\"erikson2\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 15:59:12', '2022-04-25 15:59:12'),
(4, 'App\\Models\\User', 1, 'created', 'App\\Models\\Site', 1, '[]', '{\"site_id\":\"1\",\"site_name\":\"2\",\"site_status\":\"3\",\"site_category\":\"4\",\"id\":1}', 'http://localhost/laravel-jwt-auth/backend/api/auth/save', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 18:55:56', '2022-04-25 18:55:56'),
(5, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_name\":\"2\",\"site_status\":\"3\",\"site_category\":\"4\"}', '{\"site_name\":\"site 1\",\"site_status\":\"Active\",\"site_category\":\"Category\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 19:02:16', '2022-04-25 19:02:16'),
(6, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_status\":\"Active\"}', '{\"site_status\":\"Inactive\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 19:25:01', '2022-04-25 19:25:01'),
(7, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_status\":\"Inactive\"}', '{\"site_status\":\"Active\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 19:25:36', '2022-04-25 19:25:36'),
(8, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_category\":\"Category\"}', '{\"site_category\":\"CO\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 21:05:50', '2022-04-25 21:05:50'),
(9, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_name\":\"site 1\"}', '{\"site_name\":\"Site Name 1\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-25 21:06:02', '2022-04-25 21:06:02'),
(10, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_status\":\"Active\",\"site_category\":\"CO\"}', '{\"site_status\":\"Inactive\",\"site_category\":\"NPOB\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-26 16:23:54', '2022-04-26 16:23:54'),
(11, 'App\\Models\\User', 1, 'created', 'App\\Models\\Site', 2, '[]', '{\"site_id\":\"2\",\"site_name\":\"Site 2\",\"site_status\":\"Active\",\"site_category\":\"CS\",\"id\":2}', 'http://localhost/laravel-jwt-auth/backend/api/auth/save', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-26 16:24:15', '2022-04-26 16:24:15'),
(12, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_status\":\"Inactive\"}', '{\"site_status\":\"Active\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-26 21:52:11', '2022-04-26 21:52:11'),
(13, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Site', 1, '{\"site_name\":\"Site Name 1\"}', '{\"site_name\":\"Site 1\"}', 'http://localhost/laravel-jwt-auth/backend/api/auth/update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36', NULL, '2022-04-26 21:52:20', '2022-04-26 21:52:20'),
(14, 'App\\Models\\User', 1, 'created', 'App\\Models\\Site', 3, '[]', '{\"site_id\":\"id1\",\"site_name\":\"sitename1\",\"site_status\":\"site_status1\",\"site_category\":\"site_category\",\"id\":3}', 'http://localhost/laravel-jwt-auth/backend/api/v1/site/save', '::1', 'PostmanRuntime/7.29.0', NULL, '2022-05-04 15:20:30', '2022-05-04 15:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(10) NOT NULL,
  `first_name` varchar(35) NOT NULL,
  `middle_name` varchar(35) DEFAULT NULL,
  `last_name` varchar(35) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `alternate_contact_number` varchar(15) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  `entity_code` varchar(10) DEFAULT NULL,
  `org_unit_code` varchar(30) DEFAULT NULL,
  `leadership_role` varchar(50) DEFAULT NULL,
  `immediate_supervisor` varchar(50) DEFAULT NULL,
  `immediate_head` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `changed_by` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_04_25_065025_create_audits_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `site_id` varchar(11) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `site_status` varchar(30) NOT NULL,
  `site_category` varchar(30) NOT NULL,
  `region` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `city_municipality` varchar(30) DEFAULT NULL,
  `created_by` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `changed_by` varchar(30) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `site_id`, `site_name`, `site_status`, `site_category`, `region`, `province`, `city_municipality`, `created_by`, `created_at`, `changed_by`, `updated_at`) VALUES
(1, '1', 'Site 1', 'Active', 'NPOB', NULL, NULL, NULL, NULL, '2022-04-26 02:55:56', NULL, '2022-04-27 05:52:20'),
(2, '2', 'Site 2', 'Active', 'CS', NULL, NULL, NULL, NULL, '2022-04-27 00:24:15', NULL, '2022-04-27 00:24:15'),
(3, 'id1', 'sitename1', 'site_status1', 'site_category', NULL, NULL, NULL, NULL, '2022-05-04 23:20:29', NULL, '2022-05-04 23:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'erikson2', 'ebsupnet@gmail.com', NULL, '$2y$10$5xt38m.vqhzpgXElJQVZLumumP1CZ7VmG7s6/C1y2BRfJXqfCRgNi', NULL, '2022-04-16 23:15:34', '2022-04-25 15:59:12'),
(2, 'sample2', 'sample2@gmail.com', NULL, '$2y$10$Fhcp14kQApf491wAsRnl9eM9r8L.SwtG86go89Ad0MLLVphB9u3wO', NULL, '2022-04-17 01:10:17', '2022-04-24 22:12:07'),
(3, 'admin3', 'sample3@gmail.com', NULL, '$2y$10$/XfchFome//ml77P6TAgreLbYzpS24CbgjBUCdSWaPuGbpBgHqj1y', NULL, '2022-04-23 22:53:55', '2022-04-24 22:54:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
