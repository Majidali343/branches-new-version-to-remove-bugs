-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 01:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `branches2-3137317134`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `serial_id` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `location`, `serial_id`, `start_date`, `group_id`, `created_at`, `updated_at`) VALUES
(2, 'hsbs', 'hshs', 'bxbs', 'c81Q0cPzfu1727954961', '2024-10-10 00:00:00', 2, '2024-10-03 10:29:21', '2024-10-03 10:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE `event_attendees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `creator_id` varchar(20) NOT NULL,
  `serial_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `profile_img`, `description`, `creator_id`, `serial_id`, `created_at`, `updated_at`) VALUES
(1, 'test', 'Add Nursery Activity Diagram.drawio.png', 'this is test group', '1', 'NO4vUrnAzA1727940074', '2024-10-03 06:21:14', '2024-10-03 06:21:14'),
(2, 'Rajab groups', '1000102049.jpg', 'jsjskksks', '3', 'D1sR3z1AxP1727942009', '2024-10-03 06:53:29', '2024-10-03 06:53:29'),
(3, 'Islamabad', '1000102952.jpg', 'hshsjdj', '9', 'aYIVl5crxK1728024757', '2024-10-04 05:52:37', '2024-10-04 05:52:37'),
(4, 'Friend Zone', '1000046915.jpg', 'ysusbsjsbsknstxskbs', '8', 'VQRZhmzLqd1728024891', '2024-10-04 05:54:51', '2024-10-04 05:54:51'),
(5, 'kazim', '1000106076.jpg', 'kazim groups', '15', 'wXEnQ19uyI1728367111', '2024-10-08 04:58:31', '2024-10-08 04:58:31'),
(6, 'Weber', '8f66f45c-ab95-4a78-a2e1-78b7b9dc4e46-1_all_16894.jpg', 'Beverly\'s clan', '17', 'YKiVP8HA4c1728841210', '2024-10-13 16:40:10', '2024-10-13 16:40:10'),
(7, 'Faizan Ali', '1000107935.jpg', 'hdhdjdjdj', '19', 'Na4abiVRyS1729236281', '2024-10-18 06:24:41', '2024-10-18 06:24:41'),
(8, 'hello', '1000108503.jpg', 'nemrkr', '19', 'VyCmWKVcI61729247424', '2024-10-18 09:30:24', '2024-10-18 09:30:24'),
(9, 'Hy', '1000107944.jpg', 'hjdjdjdj', '19', 'Wr9Pxj5fjR1729248783', '2024-10-18 09:53:03', '2024-10-18 09:53:03'),
(10, 'sanaaaa', '1000107719.jpg', 'hshsjdk', '19', '8tF3KO10bl1729252801', '2024-10-18 11:00:01', '2024-10-18 11:00:01'),
(11, 'plzz join', 'IMG-20241017-WA0031.jpg', 'pleae join', '23', 'UPkYitQY0A1729262048', '2024-10-18 13:34:08', '2024-10-18 13:34:08'),
(12, 'bano', '1000108731.jpg', 'bano ali ...', '24', '8vj4K0gHFS1729488705', '2024-10-21 04:31:45', '2024-10-21 04:31:45'),
(13, 'alkram brand', '1000108664.jpg', 'yuik', '24', 'MArasR03NB1729491974', '2024-10-21 05:26:14', '2024-10-21 05:26:14'),
(14, 'moeez', '1000108728.jpg', 'ghuii', '30', 'UPGY1lkFnA1729493533', '2024-10-21 05:52:13', '2024-10-21 05:52:13'),
(15, 'rafay', '1000108722.jpg', 'vdbsnsn', '35', 'ElMENWe2kS1729512974', '2024-10-21 11:16:14', '2024-10-21 11:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `group_household`
--

CREATE TABLE `group_household` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `household_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `groupHousehold` int(10) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 => Pending, 1 => Approved, 2 => Rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_household`
--

INSERT INTO `group_household` (`id`, `household_id`, `group_id`, `groupHousehold`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0, 0, '2024-10-03 06:23:56', '2024-10-03 06:23:56'),
(2, 3, 1, 0, 1, '2024-10-03 06:48:13', '2024-10-03 06:48:39'),
(3, 4, 2, 0, 1, '2024-10-03 06:56:55', '2024-10-03 06:57:22'),
(4, 6, 2, 0, 1, '2024-10-04 05:50:23', '2024-10-18 05:46:01'),
(5, 7, 4, 0, 1, '2024-10-04 05:55:17', '2024-10-04 05:55:49'),
(7, 12, 2, 0, 1, '2024-10-08 10:06:09', '2024-10-08 10:29:14'),
(8, 14, 2, 0, 1, '2024-10-18 05:07:20', '2024-10-18 09:16:16'),
(9, 21, 13, 0, 1, '2024-10-21 05:32:07', '2024-10-21 05:33:49'),
(10, 20, 14, 0, 1, '2024-10-21 06:53:53', '2024-10-21 08:47:57'),
(11, 23, 15, 0, 0, '2024-10-21 11:20:34', '2024-10-21 11:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `group_user`
--

CREATE TABLE `group_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 => Pending, 1 => Approved, 2 => Rejected, 3 => Age_Approval',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_user`
--

INSERT INTO `group_user` (`id`, `user_id`, `group_id`, `is_admin`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2024-10-03 06:21:14', '2024-10-03 06:21:14'),
(2, 3, 2, 1, 1, '2024-10-03 06:53:29', '2024-10-03 06:53:29'),
(3, 9, 3, 1, 1, '2024-10-04 05:52:37', '2024-10-04 05:52:37'),
(4, 8, 4, 1, 1, '2024-10-04 05:54:51', '2024-10-04 05:54:51'),
(5, 10, 2, 0, 1, '2024-10-04 08:26:19', '2024-10-05 04:28:04'),
(6, 11, 2, 0, 3, '2024-10-05 04:16:24', '2024-10-05 04:16:24'),
(7, 15, 5, 1, 1, '2024-10-08 04:58:31', '2024-10-08 04:58:31'),
(12, 16, 2, 0, 1, '2024-10-08 10:29:14', '2024-10-08 10:29:14'),
(13, 17, 6, 1, 1, '2024-10-13 16:40:10', '2024-10-13 16:40:10'),
(15, 8, 2, 1, 1, '2024-10-18 05:46:01', '2024-10-18 06:16:58'),
(16, 19, 7, 1, 1, '2024-10-18 06:24:41', '2024-10-18 06:24:41'),
(35, 18, 2, 0, 1, '2024-10-18 09:16:16', '2024-10-18 09:16:16'),
(36, 20, 7, 0, 1, '2024-10-18 09:22:27', '2024-10-18 09:37:23'),
(37, 19, 8, 1, 1, '2024-10-18 09:30:24', '2024-10-18 09:30:24'),
(38, 19, 9, 1, 1, '2024-10-18 09:53:03', '2024-10-18 09:53:03'),
(39, 19, 10, 1, 1, '2024-10-18 11:00:01', '2024-10-18 11:00:01'),
(40, 23, 11, 1, 1, '2024-10-18 13:34:08', '2024-10-18 13:34:08'),
(42, 24, 12, 1, 1, '2024-10-21 04:31:45', '2024-10-21 04:31:45'),
(43, 25, 12, 0, 1, '2024-10-21 04:34:30', '2024-10-21 04:38:27'),
(45, 24, 13, 1, 1, '2024-10-21 05:26:14', '2024-10-21 05:26:14'),
(47, 28, 13, 0, 1, '2024-10-21 05:33:49', '2024-10-21 05:33:49'),
(48, 29, 14, 1, 1, '2024-10-21 05:42:29', '2024-10-21 11:25:05'),
(49, 30, 14, 1, 1, '2024-10-21 05:52:13', '2024-10-21 05:52:13'),
(50, 24, 14, 0, 1, '2024-10-21 06:32:48', '2024-10-21 06:53:02'),
(51, 31, 14, 0, 1, '2024-10-21 08:47:01', '2024-10-21 08:47:31'),
(52, 27, 14, 1, 1, '2024-10-21 08:47:57', '2024-10-21 08:55:37'),
(53, 35, 15, 1, 1, '2024-10-21 11:16:14', '2024-10-21 11:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `household_id` varchar(255) DEFAULT NULL,
  `household_bio` text NOT NULL,
  `serial_id` varchar(255) DEFAULT NULL,
  `premium_expiry` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`id`, `name`, `profile_img`, `city`, `state`, `country`, `zip`, `address`, `household_id`, `household_bio`, `serial_id`, `premium_expiry`, `created_at`, `updated_at`) VALUES
(1, 'bilal new', 'Add Nursery Activity Diagram.drawio.png', 'testcity', 'test state', 'paskitan', '3423', 'bilal new', '23', 'this is my house hold', 'zAiTjRVbAi1727940067', '2024-10-03 06:21:07', '2024-10-03 06:21:07', '2024-10-03 06:21:07'),
(2, 'majid', 'IMG-20241001-WA0027.jpg', 'bsbsbs', 'Connecticut', 'jsjsjs', '9595959', 'hsbbsbsbs', 'majid', 'hdhdhd', 'K01bEeLXJY1727940229', '2024-10-03 06:23:49', '2024-10-03 06:23:49', '2024-10-03 06:23:49'),
(3, 'rajab Ali', '1000102952.jpg', 'wah', 'Alabama', 'usa', '216494', 'street79', 'Rajab18', 'hello it\'s my household you can send request to join me', 'tPupM3PtXV1727941633', '2025-10-03 06:47:13', '2024-10-03 06:47:13', '2024-10-03 06:47:13'),
(4, 'raha Khan', '1000098702.jpg', 'wah', 'Alabama', 'usa', '6465959', 'stret79', 'raha 13', 'hello it\'s raha House', 'YnuT1hAnTZ1727942197', '2024-10-03 06:56:37', '2024-10-03 06:56:37', '2024-10-03 06:56:37'),
(5, 'zafar', 'IMG-20241002-WA0038.jpg', 'wah', 'Alabama', 'usa', '57979', 'steet689', 'zafar57', 'hello', 'PHcX7TSXBZ1727951115', '2024-10-03 09:25:15', '2024-10-03 09:25:15', '2024-10-03 09:25:15'),
(6, 'Rana Tigrina', '1000047499.jpg', 'zisbsh', 'Georgia', 'jaisb', '346499', 'sgisbsjsnsk', '001', 'hsisbsnsn', 'CbURKtiKnh1728024491', '2024-10-04 05:48:11', '2024-10-04 05:48:11', '2024-10-04 05:48:11'),
(7, 'maiza naveed', '1000105137.jpg', 'Maryland', 'Alabama', 'usa', '9595995', 'street89', 'maiza57', 'hello', 'rmSXNpipaZ1728024491', '2024-10-04 05:48:11', '2024-10-04 05:48:11', '2024-10-04 05:48:11'),
(8, 'zhzhz', '20241004_120238.jpg', 'rnnz', 'Connecticut', 'gzhz', '9595', 'bsbs', 'tahir', 'tsysyd', 'BisOSSjci61728033873', '2025-11-04 09:24:33', '2024-10-04 08:24:33', '2024-10-04 08:24:33'),
(9, 'abc houehold', '20241004_120238.jpg', 'wah', 'Delaware', 'hsbbbs', '6499494', 'bzbzbs', 'abc12', 'vdvbsiebejd', 'CpsVOWGqya1728123854', '2024-10-05 09:24:14', '2024-10-05 09:24:14', '2024-10-05 09:24:14'),
(10, 'wahab', '1000106348.jpg', 'wah', 'Alabama', 'usa', '95959', 'hello bsnsm', '68', 'bdnndnd', 'S1MH9Kqkqy1728366961', '2024-10-08 04:56:01', '2024-10-08 04:56:01', '2024-10-08 04:56:01'),
(11, 'kazim', '1000105803.jpg', 'wah', 'Alabama', 'usa', '64656', 'streer7829', '729', 'helo jsjs', 'CoXsIQAUeD1728367084', '2024-10-08 04:58:04', '2024-10-08 04:58:04', '2024-10-08 04:58:04'),
(12, 'sari gadi', '42ebfcae-912d-4afa-9ef6-99cb37fcf7042836507086105847026.jpg', 'taxila', 'New Jersey', 'pakist a', '64948', 'glaxit', 'ghr', 'lal ha lal', 'cgK6wAFa5V1728385539', '2024-10-08 10:05:39', '2024-10-08 10:05:39', '2024-10-08 10:05:39'),
(13, 'Greg and Sheila Potter', '1000003525.jpg', 'Hyde Park', 'Utah', 'USA', '84318', '233 w 260 n', 'Potter\'S', 'Greg and Sheila', 'GryDQKwcKc1728840995', '2024-10-13 16:36:35', '2024-10-13 16:36:35', '2024-10-13 16:36:35'),
(14, 'mnbv', 'IMG-20241017-WA0046.jpg', 'ksnsn', 'Delaware', 'pakista was', '9494', 'mnbv', 'mnbv', 'jdidbdjdbd', 'jJWElgtVfy1729231621', '2024-10-18 05:07:01', '2024-10-18 05:07:01', '2024-10-18 05:07:01'),
(15, 'Faizan Ali', '1000108050.jpg', 'wah', 'Alabama', 'usa', '959', 'street57', '12', 'bshdhjd', '27QkJL4xDN1729236091', '2024-10-18 06:21:31', '2024-10-18 06:21:31', '2024-10-18 06:21:31'),
(16, 'household owner', 'Screenshot_20241018-153517_UBL Digital.jpg', 'snsns', 'Florida', 'pakista', '64964', 'owner address', 'a household owner', 'usbsshzbs', 'IHiXZP8fsp1729261789', '2024-10-18 13:29:49', '2024-10-18 13:29:49', '2024-10-18 13:29:49'),
(17, 'hzhshs', '1822a404-5791-4ef3-8ba5-659eb43975a352840663984869838.jpg', 'ksksjs', 'Florida', 'osjsjs', '469494', 'z nzz', 'bsjsb', 'ushshs', 'YchtyWaF1W1729262007', '2024-10-18 13:33:27', '2024-10-18 13:33:27', '2024-10-18 13:33:27'),
(18, 'bano alo', '1000108737.jpg', 'wah', 'Alabama', 'usa', '136161', 'street 47', '16', 'ywuuwiwii', 'iRdlPlmcMT1729488656', '2024-10-21 04:30:56', '2024-10-21 04:30:56', '2024-10-21 04:30:56'),
(19, 'burhan', '1000108728.jpg', 'waj', 'Alabama', 'ywsa', '6464646', 'gqjajk', '57', 'hahahah', 'gahYWDiA3x1729488806', '2024-10-21 04:33:26', '2024-10-21 04:33:26', '2024-10-21 04:33:26'),
(20, 'Micheal', '1000107932.jpg', 'wah', 'Alabama', 'usa', '679797', 'stret8#))', 'Micheal', 'hshsjdj', '7okLEAm4EX1729490647', '2024-10-21 05:04:07', '2024-10-21 05:04:07', '2024-10-21 05:04:07'),
(21, 'fahad', '1000108737.jpg', 'gnbn', 'Alabama', 'wah', '69999', 'vnnn', '58', 'bbbb', '80naPqEXZ91729492228', '2024-10-21 05:30:28', '2024-10-21 05:30:28', '2024-10-21 05:30:28'),
(22, 'Steve', '1000107935.jpg', 'qah', 'Alabama', 'usa', '676', 'sghshsjs', '728', 'zbbxbx', 'FDfPYflR5h1729492674', '2024-10-21 05:37:54', '2024-10-21 05:37:54', '2024-10-21 05:37:54'),
(23, 'moeez', '1000098953.jpg', 'wah', 'Alabama', 'wjjm', '9999', 'street,uoo', '58', 'ggb', 'dYNZYTSbC11729493504', '2024-10-21 05:51:44', '2024-10-21 05:51:44', '2024-10-21 05:51:44'),
(24, 'raza', '1000108917.jpg', 'wah', 'Alabama', 'uwa', '95956', 'street782', 'raA', 'dbbdbs', 'IHcUOZc1SL1729503675', '2024-10-21 08:41:15', '2024-10-21 08:41:15', '2024-10-21 08:41:15'),
(25, 'rafay', '1000108734.jpg', 'wah', 'Alabama', 'usa', '64646', '1dhjdjd', 'rafay 79', 'vsbsbbsb', 'fw5KOTsupC1729512951', '2024-10-21 11:15:51', '2024-10-21 11:15:51', '2024-10-21 11:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `household_approvals`
--

CREATE TABLE `household_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_approvals`
--

INSERT INTO `household_approvals` (`id`, `user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(11, 26, 12, '2024-10-21 04:58:36', '2024-10-21 04:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `household_household`
--

CREATE TABLE `household_household` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requested_household_id` bigint(20) UNSIGNED NOT NULL,
  `household_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 => Pending, 1 => Approved, 2 => Rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_household`
--

INSERT INTO `household_household` (`id`, `requested_household_id`, `household_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 7, 0, '2024-10-04 05:56:32', '2024-10-04 05:56:32'),
(2, 12, 4, 0, '2024-10-08 10:30:56', '2024-10-08 10:30:56'),
(3, 12, 2, 0, '2024-10-08 10:31:14', '2024-10-08 10:31:14');

-- --------------------------------------------------------

--
-- Table structure for table `household_user`
--

CREATE TABLE `household_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `household_id` bigint(20) UNSIGNED NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 => Pending, 1 => Approved, 2 => Rejected, 3 => Cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_user`
--

INSERT INTO `household_user` (`id`, `user_id`, `household_id`, `is_admin`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 1, 1, '2024-10-03 06:21:07', '2024-10-03 06:21:07', 1),
(2, 2, 2, 1, '2024-10-03 06:23:49', '2024-10-03 06:23:49', 1),
(3, 3, 3, 1, '2024-10-03 06:47:13', '2024-10-03 06:47:13', 1),
(4, 4, 3, 0, '2024-10-03 06:50:28', '2024-10-03 06:51:05', 1),
(5, 5, 4, 1, '2024-10-03 06:56:37', '2024-10-03 06:56:37', 1),
(6, 6, 4, 0, '2024-10-03 07:01:20', '2024-10-03 07:01:20', 0),
(7, 7, 5, 1, '2024-10-03 09:25:15', '2024-10-03 09:25:15', 1),
(8, 8, 6, 1, '2024-10-04 05:48:11', '2024-10-04 05:48:11', 1),
(9, 9, 7, 1, '2024-10-04 05:48:11', '2024-10-04 05:48:11', 1),
(10, 10, 8, 1, '2024-10-04 08:24:33', '2024-10-04 08:24:33', 1),
(11, 11, 8, 0, '2024-10-05 04:10:34', '2024-10-05 04:14:45', 1),
(12, 12, 8, 0, '2024-10-05 09:02:52', '2024-10-05 09:03:51', 1),
(13, 13, 9, 1, '2024-10-05 09:24:14', '2024-10-05 09:24:14', 1),
(14, 14, 10, 1, '2024-10-08 04:56:01', '2024-10-08 04:56:01', 1),
(15, 15, 11, 1, '2024-10-08 04:58:04', '2024-10-08 04:58:04', 1),
(16, 16, 12, 1, '2024-10-08 10:05:39', '2024-10-08 10:05:39', 1),
(17, 17, 13, 1, '2024-10-13 16:36:35', '2024-10-13 16:36:35', 1),
(18, 18, 14, 1, '2024-10-18 05:07:01', '2024-10-18 05:07:01', 1),
(19, 19, 15, 1, '2024-10-18 06:21:31', '2024-10-18 06:21:31', 1),
(20, 20, 15, 0, '2024-10-18 06:23:06', '2024-10-18 06:23:39', 1),
(21, 22, 16, 1, '2024-10-18 13:29:49', '2024-10-18 13:29:49', 1),
(22, 23, 17, 1, '2024-10-18 13:33:27', '2024-10-18 13:33:27', 1),
(23, 21, 16, 0, '2024-10-18 13:35:04', '2024-10-18 13:35:36', 1),
(24, 24, 18, 1, '2024-10-21 04:30:56', '2024-10-21 04:30:56', 1),
(25, 25, 19, 1, '2024-10-21 04:33:26', '2024-10-21 04:33:26', 1),
(26, 26, 18, 0, '2024-10-21 04:54:16', '2024-10-21 04:54:55', 1),
(27, 27, 20, 1, '2024-10-21 05:04:07', '2024-10-21 05:04:07', 1),
(28, 28, 21, 1, '2024-10-21 05:30:28', '2024-10-21 05:30:28', 1),
(29, 29, 22, 1, '2024-10-21 05:37:54', '2024-10-21 05:37:54', 1),
(30, 30, 23, 1, '2024-10-21 05:51:44', '2024-10-21 05:51:44', 1),
(31, 31, 24, 1, '2024-10-21 08:41:15', '2024-10-21 08:41:15', 1),
(32, 33, 24, 0, '2024-10-21 09:08:26', '2024-10-21 09:09:03', 1),
(33, 34, 20, 0, '2024-10-21 10:44:13', '2024-10-21 10:44:53', 1),
(34, 35, 25, 1, '2024-10-21 11:15:51', '2024-10-21 11:15:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state`, `country`, `created_at`, `updated_at`) VALUES
(1, 'Alabama', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(2, 'Alaska', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(3, 'Arizona', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(4, 'Arkansas', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(5, 'California', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(6, 'Colorado', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(7, 'Connecticut', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(8, 'Delaware', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(9, 'Florida', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(10, 'Georgia', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(11, 'Hawaii', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(12, 'Idaho', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(13, 'Illinois', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(14, 'Indiana', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(15, 'Iowa', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(16, 'Kansas', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(17, 'Kentucky', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(18, 'Louisiana', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(19, 'Maine', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(20, 'Maryland', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(21, 'Massachusetts', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(22, 'Michigan', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(23, 'Minnesota', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(24, 'Mississippi', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(25, 'Missouri', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(26, 'Montana', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(27, 'Nebraska', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(28, 'Nevada', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(29, 'New Hampshire', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(30, 'New Jersey', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(31, 'New Mexico', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(32, 'New York', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(33, 'North Carolina', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(34, 'North Dakota', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(35, 'Ohio', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(36, 'Oklahoma', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(37, 'Oregon', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(38, 'Pennsylvania', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(39, 'Rhode Island', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(40, 'South Carolina', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(41, 'South Dakota', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(42, 'Tennessee', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(43, 'Texas', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(44, 'Utah', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(45, 'Vermont', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(46, 'Virginia', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(47, 'Washington', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(48, 'West Virginia', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(49, 'Wisconsin', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49'),
(50, 'Wyoming', 'USA', '2024-07-02 07:35:49', '2024-07-02 07:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plan` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `started_at` date NOT NULL,
  `ended_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `created_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 => Pending, 1 => In_Progress, 2 => Completed',
  `cost` decimal(10,2) DEFAULT NULL,
  `complete_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `non_account_member` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `email_verified_at`, `password`, `profile_img`, `dob`, `address`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `gender`, `country_code`, `phone`, `non_account_member`) VALUES
(1, 'lukmangpt', NULL, 'lukman@gmail.com', NULL, '$2y$12$GPjKm7VRa7BkMiX0oVOoleFOuyDD1WUn.6WOAn/5F4CJBwelXXblq', NULL, NULL, NULL, NULL, '2024-10-03 06:19:38', '2024-10-03 06:25:14', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(2, 'majid333', NULL, 'majid@gmail.com', NULL, '$2y$12$sOMf6h4yspa4R9rINb1do.5kXR4oJBxx/.wb/ICQUfe4FczMw9PAi', NULL, NULL, NULL, NULL, '2024-10-03 06:22:50', '2024-10-03 06:23:05', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(3, 'Rajab', NULL, 'rajab@gmail.com', NULL, '$2y$12$EAoERQ6H7PWOuuq.n3gJQesK9Lg37vJTFLxrnd/OasqT6/aL8fREO', NULL, NULL, NULL, NULL, '2024-10-03 06:45:10', '2024-10-21 09:58:44', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', NULL, NULL, NULL, 0),
(4, 'Muhammad taha', NULL, 'taha@gmail.com', NULL, '$2y$12$iB1lVQXLIVrELDr7d59na.Y93DLSvZH1TUCYKaQ3S8JNsXBcWN9oa', NULL, NULL, NULL, NULL, '2024-10-03 06:49:51', '2024-10-03 06:53:40', 'fslvjZtRReqxdKtjI28dE_:APA91bG7wTq5Hi3Q40Ss1f2qrXrtSTDCsZO0_SE0Byf3renSKFIZomotf333FBkEJW3CYaUV4f8z-AeBkl5uDgRUAxtMFO-50h7AZtlCwSUSoUnfYQK-yO6j2qlAOB8nTQNwSXzKQ__R', NULL, NULL, NULL, 0),
(5, 'raha Khan', NULL, 'raha@gmail.com', NULL, '$2y$12$/ZQAzjyk85FaFW0JTCol6ezmE4lHO0lh.o2QxK3jp4E6v.4.8SPAW', NULL, NULL, NULL, NULL, '2024-10-03 06:55:24', '2024-10-03 06:55:33', 'fslvjZtRReqxdKtjI28dE_:APA91bG7wTq5Hi3Q40Ss1f2qrXrtSTDCsZO0_SE0Byf3renSKFIZomotf333FBkEJW3CYaUV4f8z-AeBkl5uDgRUAxtMFO-50h7AZtlCwSUSoUnfYQK-yO6j2qlAOB8nTQNwSXzKQ__R', NULL, NULL, NULL, 0),
(6, 'Muhammad Ali', NULL, 'ali@gmail.com', NULL, '$2y$12$L1nZoUfiHf8Me9cXyA.nROsCfFA2ahkfemaQpQavHG42ANY55ZaZO', NULL, NULL, NULL, NULL, '2024-10-03 07:00:14', '2024-10-03 07:00:30', 'fslvjZtRReqxdKtjI28dE_:APA91bG7wTq5Hi3Q40Ss1f2qrXrtSTDCsZO0_SE0Byf3renSKFIZomotf333FBkEJW3CYaUV4f8z-AeBkl5uDgRUAxtMFO-50h7AZtlCwSUSoUnfYQK-yO6j2qlAOB8nTQNwSXzKQ__R', NULL, NULL, NULL, 0),
(7, 'muhamad zafar', NULL, 'zafar@gmail.com', NULL, '$2y$12$S0/7mZ6FRV9ziZ/9toeDt.L7fc5N2.XWc5TpyAzB.SgGX0K5u1aa6', NULL, NULL, NULL, NULL, '2024-10-03 09:24:15', '2024-10-03 09:24:27', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(8, 'Faraz123', 'Faraz Ahmad', 'fazzy@gmail.com', NULL, '$2y$12$hpKb8R/jZ3I11sTPxg2ihOSvc3MqKa6EAX1uySqNNwP5s76izP98q', '1000025358.jpg', '2000-10-12', NULL, NULL, '2024-10-04 05:46:39', '2024-10-04 05:48:55', 'f0CQxzhZQQenD3MxdzTSqh:APA91bENkmKIegjVN6bjIlmyGglSDFxpXDZLMYJTzTrUK-ZGNJno7GfOgiwb0yLn3lZzIR8nQQddvVcA8H7RWa2qE4QsWxAaq2mlL5TV5RLyHG6b6Xe0yPIlCsAsa4hI8t6fp5OLEgPB', 'Male', '+92', '(312) 345 6789', 0),
(9, 'maiza Naveed', 'maiza 🥳🥳', 'maiza@gmail.com', NULL, '$2y$12$WQsUXZZVIDfnvOpDO.4o7O2JPErs2UqWjFy0.O8DhWb57qBXhEZqa', '1000041314.jpg', '2024-10-04', NULL, NULL, '2024-10-04 05:47:13', '2024-10-04 05:51:55', 'fslvjZtRReqxdKtjI28dE_:APA91bG7wTq5Hi3Q40Ss1f2qrXrtSTDCsZO0_SE0Byf3renSKFIZomotf333FBkEJW3CYaUV4f8z-AeBkl5uDgRUAxtMFO-50h7AZtlCwSUSoUnfYQK-yO6j2qlAOB8nTQNwSXzKQ__R', 'Female', '+1', '(796) 595 9292', 0),
(10, 'tahir333', 'uhsbs', 'tahir@gmail.com', NULL, '$2y$12$JOlUxTEQKoBdepdBecj.7uRUYQkGElDWYcbnVn2pSESTdHExV7FNK', NULL, '2024-10-02', NULL, NULL, '2024-10-04 08:23:41', '2024-10-05 09:03:11', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', 'Male', '+1', '(649) 794 9494', 0),
(11, 'underage', 'under age', 'underage@gmail.com', NULL, '$2y$12$FQqM4SvooJbD95oebVnOVulC8HqmKhz9FNwGAfFF0vYoe5wCX4P76', NULL, '2021-07-15', NULL, NULL, '2024-10-05 04:09:37', '2024-10-05 04:16:09', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', 'Female', '+1', '(989) 898 9898', 0),
(12, 'grouptest', NULL, 'grouptest@gmail.com', NULL, '$2y$12$bc5VBxY8NCAXysJt8GzFX.h0Bk5d9elaOikj9onhIrCSFqDCK3DPS', NULL, NULL, NULL, NULL, '2024-10-05 09:02:15', '2024-10-05 09:04:07', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(13, 'abctest', NULL, 'abc@gmail.com', NULL, '$2y$12$KuM2/1vMK4r5mWaWjggsYe55BRQZAfS1lBTbLn2QVy9daxlICjCdS', NULL, NULL, NULL, NULL, '2024-10-05 09:22:54', '2024-10-05 09:23:07', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(14, 'Muhammad wahab', NULL, 'wahab@gmail.com', NULL, '$2y$12$b4/GUVeafUEa0fJE9Q4Xd.wiQkGmUO4C41Hk5OsbP8OkN1sAtSbK6', NULL, NULL, NULL, NULL, '2024-10-08 04:55:03', '2024-10-08 04:55:12', 'fkS-WfjqRp-EbA8FHjzaUD:APA91bHMIsPXi2GN6leqtnsXIpubO7O3tIFbR6OcDRbYQbPVeK2MBuAhhyfu9ERIMA3DHpt_CJV3-ozj19bLFWt2pBQXWacLfDhqJj2FCQC0bwSMHVdX4LV--bGUqD4AZISB16D-aJJS', NULL, NULL, NULL, 0),
(15, 'fajar kazim', NULL, 'kazim@gmail.com', NULL, '$2y$12$pHQyhf7xQAdtd1PXME4jN.LazoNg23GZUj6VPCpPdvMIL8VrOPq8e', NULL, NULL, NULL, NULL, '2024-10-08 04:56:49', '2024-10-08 04:57:22', 'fkS-WfjqRp-EbA8FHjzaUD:APA91bHMIsPXi2GN6leqtnsXIpubO7O3tIFbR6OcDRbYQbPVeK2MBuAhhyfu9ERIMA3DHpt_CJV3-ozj19bLFWt2pBQXWacLfDhqJj2FCQC0bwSMHVdX4LV--bGUqD4AZISB16D-aJJS', NULL, NULL, NULL, 0),
(16, 'yasir', NULL, 'lal@gmail.com', NULL, '$2y$12$9V.WZLKucE5zEKy9T3G0JOomKzHqJpmzc/pbaW0D9AKdO8FeZvlnq', NULL, NULL, NULL, NULL, '2024-10-08 10:04:12', '2024-10-08 10:30:31', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(17, 'sheilap916', 'Sheila Potter', 'sheilap916@outlook.com', NULL, '$2y$12$6H8q5j5bW0emZYQbpwRKSOEThVaDWPA2721xAM6B0SR5x6rxXR46G', '8f66f45c-ab95-4a78-a2e1-78b7b9dc4e46-1_all_16893.jpg', '1974-09-16', NULL, NULL, '2024-10-13 16:31:47', '2024-10-13 16:33:31', 'czB7ahuGSaamV3bUwP4wDg:APA91bG2geJZIuXggJD27h8rZtEdlyx4Cvkt-AXpyEWmm2aoiPVLDtq97TrtLOpZ95wrkEGlG0H4DqviPP_wnJsbd18S-d5qIr7r0HNpeUAG4ZL3oGIgyaeS8wELyR8B2L5z6Bf8hryZ', 'Female', '+1', '(801) 865 4507', 0),
(18, 'mnbvcxz', 'mnbx', 'mnbvcxz@gmail.com', NULL, '$2y$12$1bRVO5KyCLm4DrmOPycmC.RL8gxmv.Z/ip75Z0CfV9.CpHCBziEA2', NULL, NULL, NULL, NULL, '2024-10-18 05:05:54', '2024-10-18 05:08:16', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(19, 'sana Khan', NULL, 'sana@gmail.com', NULL, '$2y$12$s3nD.6UppoeWexIC.fjXWOs/jV9QVGV4Gna.x9HH5HMA7K2ZjS73O', NULL, NULL, NULL, NULL, '2024-10-18 06:19:11', '2024-10-21 03:39:40', 'fVEXlwcBS7mSIVfsjrfg9-:APA91bElnq1qfnvsAJIAddQ22B-A1STGcb1ER6RlwWtYBmTgL6qZ6Y8EMKmGz6YkMq77y-lR9U-jeiz_fAIQ--Xss7DbNOSobyD_qZMGk3OsJ2h2PZ6l-qtZ7ZDx_bbc_zh3_Bs_Mbgc', NULL, NULL, NULL, 0),
(20, 'freeha', 'freeha', 'freeha@gmail.com', NULL, '$2y$12$W2bO6uuwa8j1ExvCN1zLpeh9/2FEaNuAzuDUtrOmBE3SG.LValr1.', NULL, '2024-10-09', NULL, NULL, '2024-10-18 06:22:44', '2024-10-18 11:03:55', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Female', '+1', '(686) 868 9599', 0),
(21, 'achild', 'usidid', 'achild@gmail.com', NULL, '$2y$12$acSDkec1T6EUvEKC2.2J/.NS0srK4yFrhNVa3GZzCYVgAIohwpT/y', NULL, '2024-10-01', NULL, NULL, '2024-10-18 13:27:53', '2024-10-18 13:36:46', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', 'Female', '+1', '(986) 868 6896', 0),
(22, 'aowner', NULL, 'aowner@gmail.com', NULL, '$2y$12$oX407RmAGZsN6NbYY7rRVO0k16xUTNZxzGSoMg3MtB3f8tXtzam1e', NULL, NULL, NULL, NULL, '2024-10-18 13:28:21', '2024-10-18 13:35:28', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(23, 'agroup', NULL, 'agroup@gmail.com', NULL, '$2y$12$tyo9zUJbRtjyZJxqKpJ0Ve76E7X8FAvUVRZbCeuHajTAhpwSCaxNe', NULL, NULL, NULL, NULL, '2024-10-18 13:30:47', '2024-10-18 13:42:59', 'eEQjM6GPSg-zmRMUVAMOK3:APA91bE0aZvKS3G0nRIIUw1s2Q7cOJ9_fyDkzicPe8yTNmCPMShQ7dZZro6aEaxTGFpL613vvhpEeiIIdzDELrIDpe_ca7CjR7mn9k3fizQHL-1VaMwa8UlQaK-vfKeTVC4ppm_w-COU', NULL, NULL, NULL, 0),
(24, 'bano ali', 'bano', 'bano@gmail.com', NULL, '$2y$12$BG1OG7lGVscPp6AiYwjHbesMsO13yE1AWuCaBa5Hq9Pt2M7rm3hwK', '1000107705.jpg', '2024-10-21', NULL, NULL, '2024-10-21 04:29:46', '2024-10-21 10:00:12', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Female', '+1', '(595) 9', 0),
(25, 'burhan', 'burhan', 'burhan@gmail.com', NULL, '$2y$12$krIvJckTk5N7OSV5Ns26r.TK.VIkHCJN/Fd6Zb5g2JlUha2r8gLbe', '1000108728.jpg', '2024-10-21', NULL, NULL, '2024-10-21 04:32:37', '2024-10-21 04:37:19', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(866) 699 6699', 0),
(26, 'harry', 'harry', 'harry@gmail.com', NULL, '$2y$12$5oZGIhfDoTddYRjfiF4O7O7j9.uqsZhS5kDvwOvByFAKdPvKsdtF.', '1000107929.jpg', '2024-10-21', NULL, NULL, '2024-10-21 04:53:41', '2024-10-21 05:00:01', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(236) 900 9900', 0),
(27, 'Michael', 'mocheal', 'michael@gmail.com', NULL, '$2y$12$WYoGDp8t9cThNxTicjlPsOhl3vpQgbAWPRXuiRi04K9XyvsMcGdaa', '1000108737.jpg', '2021-09-15', NULL, NULL, '2024-10-21 05:03:12', '2024-10-21 10:48:13', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(876) 797 9979', 0),
(28, 'fahad', 'fahad ali', 'fahad@gmail.com', NULL, '$2y$12$cVHGxlfi1WWA0BNxFyYbVeNynDeDo3qG4b4yySxd7r8a7zKCOIhu6', '1000107929.jpg', '2024-10-21', NULL, NULL, '2024-10-21 05:29:18', '2024-10-21 05:31:49', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(999) 999 99', 0),
(29, 'Steve', 'stebe', 'steve@gmail.com', NULL, '$2y$12$P10pFK5hnVPlspJSDATuNOmtNCWGXVHQedWS./OOOUdbuaBqPAEzK', '1000107947.jpg', '2024-06-05', NULL, NULL, '2024-10-21 05:36:15', '2024-10-21 06:07:56', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(899) 999 99', 0),
(30, 'moeez', NULL, 'moeez@gmail.com', NULL, '$2y$12$TeWCO2vwtb4yynWJMKmJsuHQJKr0n6ruB3kH1VVogtx13B94CVJ9G', NULL, NULL, NULL, NULL, '2024-10-21 05:50:09', '2024-10-21 11:18:09', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', NULL, NULL, NULL, 0),
(31, 'Raza Ali', 'raa', 'raza@gmail.com', NULL, '$2y$12$wIBPJjFaaBQ.5gKt5nC29euHGmMr7bGD5Xvsm7GkUHxLcShqMBaJ2', '1000108737.jpg', '2024-10-21', NULL, NULL, '2024-10-21 08:40:19', '2024-10-21 09:08:49', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', 'Male', '+1', '(622', 0),
(32, 'irfa.', NULL, 'irfa.glaxit@gmail.com', NULL, '$2y$12$1vBTMZOzxgK4PMxAbL2zUOdliGpyhrlfdtVSAsRa5XKRoLF9MYknS', NULL, NULL, NULL, NULL, '2024-10-21 08:58:27', '2024-10-21 08:58:27', NULL, NULL, NULL, NULL, 0),
(33, 'arjun', NULL, 'arjun@gmail.com', NULL, '$2y$12$1XsqDZ818Tilpd11RhZlAetbA1Wz4CBHltxtfwsO3nnDzXWzzJd..', NULL, NULL, NULL, NULL, '2024-10-21 09:00:14', '2024-10-21 09:00:25', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', NULL, NULL, NULL, 0),
(34, 'Anaya', NULL, 'Anaya@gmail.com', NULL, '$2y$12$NXFxGhfaVLKQ.hzCb0yNt.Bz0w.vsjgzivtrsNUWlnOvuf48ZOsEi', NULL, NULL, NULL, NULL, '2024-10-21 10:43:35', '2024-10-21 10:43:58', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', NULL, NULL, NULL, 0),
(35, 'rafay', NULL, 'rafa@gmail.com', NULL, '$2y$12$7dX4A2EX2B3PQOvNPyTB.epLd2hmhHVKILuYU76sAlPEa9le9DXMW', NULL, NULL, NULL, NULL, '2024-10-21 11:14:54', '2024-10-21 11:15:03', 'dzJEZ3msRESCHZFKpI20ic:APA91bECQ6fHw1pis_p1l1wJLAoup4TF4CVj6iKICvX6alykgrkPNCXsAVCL7Z98TmSXFCaQKJEkZYu840zkGiZmhb31PknRexqUlfHZcbHZ61p_UojK0EYwIBNHjd9BjQhjFoQedlyX', NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_group_id_foreign` (`group_id`);

--
-- Indexes for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_attendees_user_id_foreign` (`user_id`),
  ADD KEY `event_attendees_event_id_foreign` (`event_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_household`
--
ALTER TABLE `group_household`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_household_household_id_foreign` (`household_id`),
  ADD KEY `group_household_group_id_foreign` (`group_id`);

--
-- Indexes for table `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_user_user_id_foreign` (`user_id`),
  ADD KEY `group_user_group_id_foreign` (`group_id`);

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `household_approvals`
--
ALTER TABLE `household_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `household_approvals_user_id_foreign` (`user_id`),
  ADD KEY `household_approvals_group_id_foreign` (`group_id`);

--
-- Indexes for table `household_household`
--
ALTER TABLE `household_household`
  ADD PRIMARY KEY (`id`),
  ADD KEY `household_household_requested_household_id_foreign` (`requested_household_id`),
  ADD KEY `household_household_household_id_foreign` (`household_id`);

--
-- Indexes for table `household_user`
--
ALTER TABLE `household_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `household_user_user_id_foreign` (`user_id`),
  ADD KEY `household_user_household_id_foreign` (`household_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_event_id_foreign` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_attendees`
--
ALTER TABLE `event_attendees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `group_household`
--
ALTER TABLE `group_household`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_user`
--
ALTER TABLE `group_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `household_approvals`
--
ALTER TABLE `household_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `household_household`
--
ALTER TABLE `household_household`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `household_user`
--
ALTER TABLE `household_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `event_attendees_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_attendees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_household`
--
ALTER TABLE `group_household`
  ADD CONSTRAINT `group_household_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `group_household_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`);

--
-- Constraints for table `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `group_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `household_approvals`
--
ALTER TABLE `household_approvals`
  ADD CONSTRAINT `household_approvals_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `household_approvals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `household_household`
--
ALTER TABLE `household_household`
  ADD CONSTRAINT `household_household_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`),
  ADD CONSTRAINT `household_household_requested_household_id_foreign` FOREIGN KEY (`requested_household_id`) REFERENCES `households` (`id`);

--
-- Constraints for table `household_user`
--
ALTER TABLE `household_user`
  ADD CONSTRAINT `household_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
