-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 04:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `post_id`, `semester`, `photo`, `created_at`, `updated_at`) VALUES
(2, 'Priya Patel', 1, '5th Sem', 'candidates/BHhWcIh3tu6AHUqH095I2NmzfGpeUZjtFfxgG0L5.jpg', '2026-06-05 22:47:43', '2026-06-10 14:10:01'),
(4, 'Ananya Iyer', 2, '3rd Sem', 'candidates/w5xuQtezH1lZYv86uYqkEKKCyjg8ouzCFw3chxlT.jpg', '2026-06-05 22:47:43', '2026-06-10 14:12:54'),
(6, 'Sneha Reddy', 2, '1st Sem', 'candidates/ExHedUrnRJjFg7ePgEOk0WNfsqkde3MOsvECMatL.jpg', '2026-06-05 22:47:43', '2026-06-10 14:11:31'),
(7, 'Kabir Malhotra', 3, '12', 'candidates/334teQ14d0QlIYSWndzEjgBTzOc3lrbVrxl2VhpO.jpg', '2026-06-05 22:47:43', '2026-06-10 14:09:26'),
(8, 'Meera Nair', 3, '12', 'candidates/1Q0tEcSY4Q6TC4lqgq4rlD7Ii1AE4StD2u0a92UA.jpg', '2026-06-05 22:47:43', '2026-06-10 14:10:39'),
(9, 'Rohan Das', 1, '5th Sem', 'candidates/gUeHSHUDw6KreVVoja76funqJzQCzI0vTulILPdp.jpg', '2026-06-05 22:47:43', '2026-06-06 11:00:07'),
(10, 'Ishaan Gupta', 4, '11', 'candidates/UqKmztwAzupF5QhgDzNze6rfPz9z9KYxHl5X8NG7.jpg', '2026-06-05 22:47:43', '2026-06-10 14:08:48'),
(13, 'Benjamin Conrads', 4, '1st Sem', 'candidates/loJilHmik3SS5H5bNIw3a7YMQinKuncXbspzHf0s.jpg', '2026-06-07 15:49:50', '2026-06-07 15:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `election_settings`
--

CREATE TABLE `election_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Students Union Election',
  `voting_open` tinyint(1) NOT NULL DEFAULT 0,
  `results_published` tinyint(1) NOT NULL DEFAULT 0,
  `voting_start` timestamp NULL DEFAULT NULL,
  `voting_end` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `election_settings`
--

INSERT INTO `election_settings` (`id`, `title`, `voting_open`, `results_published`, `voting_start`, `voting_end`, `created_at`, `updated_at`) VALUES
(1, 'Students Union Election 2026', 0, 0, '2026-06-06 04:41:00', '2026-06-07 04:41:00', '2026-06-05 20:53:42', '2026-06-07 16:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_06_000001_add_phone_role_to_users_table', 1),
(5, '2026_06_06_000003_create_students_table', 1),
(6, '2026_06_06_000004_create_posts_table', 1),
(7, '2026_06_06_000005_create_candidates_table', 1),
(8, '2026_06_06_000006_create_votes_table', 1),
(9, '2026_06_06_000007_create_otps_table', 1),
(10, '2026_06_06_000008_create_election_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `otp_code` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `phone`, `otp_code`, `expires_at`, `verified`, `created_at`, `updated_at`) VALUES
(1, '9110000001', '826843', '2026-06-06 04:40:04', 1, '2026-06-05 23:09:27', '2026-06-05 23:10:04'),
(2, '9876543211', '239326', '2026-06-06 11:12:51', 0, '2026-06-06 11:07:51', '2026-06-06 11:07:51'),
(13, '8720932392', '728933', '2026-06-07 11:08:06', 1, '2026-06-07 11:07:44', '2026-06-07 11:08:06'),
(14, '9394825442', '219845', '2026-06-07 15:55:27', 1, '2026-06-07 15:53:52', '2026-06-07 15:55:27'),
(15, '9678278682', '413363', '2026-06-07 16:01:43', 1, '2026-06-07 16:01:10', '2026-06-07 16:01:43'),
(16, '9395340221', '661567', '2026-06-07 16:42:04', 1, '2026-06-07 16:41:28', '2026-06-07 16:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `description`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'President', 'The head of the Student Union, representing the entire student body.', 1, '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(2, 'Vice President', 'Assists the President and leads in their absence.', 2, '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(3, 'Secretary', 'Responsible for record-keeping and communications.', 3, '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(4, 'Joint Secretary', 'Assists the Secretary in official student activities.', 4, '2026-06-05 22:47:43', '2026-06-05 22:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('nufeSne69UPjFI8KPU2wiv9Wy1dPZ2tQQdx172Jz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaWU5MEdOV3pHWFYzVnZjVjRvbWxBQ2Y3OFVPeHBJbFo0YUlxRWJtYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jYW5kaWRhdGVzIjtzOjU6InJvdXRlIjtzOjIyOiJhZG1pbi5jYW5kaWRhdGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781100859),
('T1RyYPtspau3ES1VQPNySVfdw84QSwPmBUSlC4wl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.122.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibHJJSVAzZDhwS0x5YUllUnhmc1hNUzZPc2lTV2piVFhqdGN4Q1FPOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781100148);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `roll_no` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `phone`, `semester`, `class`, `roll_no`, `created_at`, `updated_at`) VALUES
(17, 'Student Twelve 1', '9120000001', NULL, '12', 'R1201', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(18, 'Student Twelve 2', '9120000002', NULL, '12', 'R1202', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(19, 'Student Twelve 3', '9120000003', NULL, '12', 'R1203', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(20, 'Student Twelve 4', '9120000004', NULL, '12', 'R1204', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(21, 'Student Twelve 5', '9120000005', NULL, '12', 'R1205', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(22, 'Student Twelve 6', '9120000006', NULL, '12', 'R1206', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(23, 'Student Twelve 7', '9120000007', NULL, '12', 'R1207', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(24, 'Student Twelve 8', '9120000008', NULL, '12', 'R1208', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(25, 'Student Twelve 9', '9120000009', NULL, '12', 'R1209', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(26, 'Student Twelve 10', '9120000010', NULL, '12', 'R1210', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(43, 'Student ThirdSem 7', '9030000007', '3rd Sem', NULL, 'R0307', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(44, 'Student ThirdSem 8', '9030000008', '3rd Sem', NULL, 'R0308', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(45, 'Student ThirdSem 9', '9030000009', '3rd Sem', NULL, 'R0309', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(50, 'Student FifthSem 4', '9050000004', '5th Sem', NULL, 'R0504', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(51, 'Student FifthSem 5', '9050000005', '5th Sem', NULL, 'R0505', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(52, 'Student FifthSem 6', '9050000006', '5th Sem', NULL, 'R0506', '2026-06-05 22:47:43', '2026-06-05 22:47:43'),
(57, 'Vivian Hess', '+1 (773) 643-4947', NULL, '11', '919', '2026-06-06 10:38:02', '2026-06-06 10:38:02'),
(59, 'Saurojit Karmakar', '9395340221', '5th Sem', NULL, '1110', '2026-06-06 10:57:51', '2026-06-06 11:13:53'),
(60, 'Gita Kr', '8720932392', NULL, '11', '1110', '2026-06-07 11:06:28', '2026-06-07 11:07:37'),
(61, 'Rohit Borah', '9678278682', '5th Sem', NULL, '1', '2026-06-07 15:42:00', '2026-06-07 15:52:13'),
(63, 'Shibu', '9394825442', '5th Sem', NULL, '1', '2026-06-07 15:46:07', '2026-06-07 15:52:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@college.com', '9999999999', 'admin', NULL, '$2y$12$f03sL8F1jH5XM44wz7cIMeHSbziUl/7Zxguvh61Qsf/iQp.20cHp6', NULL, '2026-06-05 20:53:42', '2026-06-05 22:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `student_id`, `post_id`, `candidate_id`, `created_at`, `updated_at`) VALUES
(5, 59, 1, 9, '2026-06-06 11:16:10', '2026-06-06 11:16:10'),
(6, 59, 2, 4, '2026-06-06 11:16:14', '2026-06-06 11:16:14'),
(8, 59, 4, 10, '2026-06-06 11:16:40', '2026-06-06 11:16:40'),
(9, 60, 1, 2, '2026-06-07 11:08:45', '2026-06-07 11:08:45'),
(10, 60, 4, 10, '2026-06-07 11:10:12', '2026-06-07 11:10:12'),
(11, 60, 3, 8, '2026-06-07 11:10:14', '2026-06-07 11:10:14'),
(13, 63, 1, 9, '2026-06-07 15:58:13', '2026-06-07 15:58:13'),
(14, 63, 2, 6, '2026-06-07 15:58:16', '2026-06-07 15:58:16'),
(16, 63, 4, 10, '2026-06-07 15:58:23', '2026-06-07 15:58:23'),
(17, 61, 1, 9, '2026-06-07 16:03:29', '2026-06-07 16:03:29'),
(18, 61, 2, 6, '2026-06-07 16:03:31', '2026-06-07 16:03:31'),
(19, 61, 3, 8, '2026-06-07 16:03:34', '2026-06-07 16:03:34'),
(20, 61, 4, 10, '2026-06-07 16:03:36', '2026-06-07 16:03:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidates_post_id_foreign` (`post_id`);

--
-- Indexes for table `election_settings`
--
ALTER TABLE `election_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_phone_index` (`phone`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_phone_unique` (`phone`),
  ADD UNIQUE KEY `students_class_sem_roll_unique` (`class`,`semester`,`roll_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `votes_student_id_post_id_unique` (`student_id`,`post_id`),
  ADD KEY `votes_post_id_foreign` (`post_id`),
  ADD KEY `votes_candidate_id_foreign` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `election_settings`
--
ALTER TABLE `election_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
