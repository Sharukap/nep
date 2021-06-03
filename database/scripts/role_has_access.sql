-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2021 at 01:41 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nep`
--

-- --------------------------------------------------------

--
-- Table structure for table `role_has_access`
--

CREATE TABLE `role_has_access` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `access_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_access`
--

INSERT INTO `role_has_access` (`id`, `created_at`, `updated_at`, `deleted_at`, `role_id`, `access_id`) VALUES
(1, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 1),
(2, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 2),
(3, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 3),
(4, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 4),
(5, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 6),
(6, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 7),
(7, '2021-04-02 06:01:07', '2021-04-02 06:01:07', NULL, 2, 8),
(8, '2021-04-03 08:10:53', '2021-04-03 08:10:53', NULL, 3, 1),
(9, '2021-04-03 08:10:53', '2021-04-03 08:10:53', NULL, 3, 2),
(10, '2021-04-03 08:10:53', '2021-04-03 08:10:53', NULL, 3, 3),
(11, '2021-04-03 08:10:53', '2021-04-03 08:10:53', NULL, 3, 4),
(12, '2021-04-03 08:10:54', '2021-04-03 08:10:54', NULL, 3, 6),
(13, '2021-04-03 08:10:54', '2021-04-03 08:10:54', NULL, 3, 7),
(14, '2021-04-03 08:10:54', '2021-04-03 08:10:54', NULL, 3, 8),
(15, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 1),
(16, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 2),
(17, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 3),
(18, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 4),
(19, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 6),
(20, '2021-04-03 08:11:03', '2021-04-03 08:11:03', NULL, 4, 7),
(21, '2021-04-03 08:11:04', '2021-04-03 08:11:04', NULL, 4, 8),
(22, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 1),
(23, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 2),
(24, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 3),
(25, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 4),
(26, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 6),
(27, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 7),
(28, '2021-04-03 08:11:14', '2021-04-03 08:11:14', NULL, 5, 8),
(29, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 1),
(30, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 2),
(31, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 3),
(32, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 4),
(33, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 6),
(34, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 7),
(35, '2021-04-03 08:11:23', '2021-04-03 08:11:23', NULL, 6, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `role_has_access`
--
ALTER TABLE `role_has_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_has_access_role_id_foreign` (`role_id`),
  ADD KEY `role_has_access_access_id_foreign` (`access_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `role_has_access`
--
ALTER TABLE `role_has_access`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_has_access`
--
ALTER TABLE `role_has_access`
  ADD CONSTRAINT `role_has_access_access_id_foreign` FOREIGN KEY (`access_id`) REFERENCES `access` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_access_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
