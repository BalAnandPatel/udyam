-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 03, 2026 at 11:56 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u564757906_giplmlm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2025-09-13 18:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `base_amount` decimal(10,2) DEFAULT NULL,
  `gst_amount` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `transaction_charge` decimal(10,2) DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `user_id`, `name`, `base_amount`, `gst_amount`, `amount`, `payment_method`, `description`, `date`, `transaction_charge`) VALUES
(1843, 2532, 'Udyami Bazar 36', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:10:43', 1.40),
(1842, 2531, 'Udyami Bazar 35', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:09:22', 1.40),
(1841, 2530, 'Udyami Bazar 34', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:07:31', 1.40),
(1840, 2529, 'Udyami Bazar 33', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:06:17', 1.40),
(1839, 2528, 'Udyami Bazar 32', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:03:31', 1.40),
(1838, 2527, 'Udyami Bazar 31', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:02:01', 1.40),
(1837, 2526, 'Udyami Bazar 30', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-03 11:00:18', 1.40),
(1836, 2525, 'Udyami Bazar 27', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:44:18', 1.40),
(1835, 2524, 'Udyami Bazar 27', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:41:08', 1.40),
(1834, 2523, 'Udyami Bazar 26', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:38:28', 1.40),
(1833, 2522, 'Udyami Bazar 25', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:24:14', 1.40),
(1832, 2521, 'Udyami Bazar 24', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:22:44', 1.40),
(1831, 2520, 'Udyami Bazar 23', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:18:07', 1.40),
(1830, 2519, 'Udyami Bazar 22', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:16:31', 1.40),
(1829, 2518, 'Udyami Bazar 21', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:14:20', 1.40),
(1828, 2517, 'Udyami Bazar 20', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:02:50', 1.40),
(1827, 2516, 'Udyami Bazar 19', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 08:01:29', 1.40),
(1826, 2515, 'Udyami Bazar 17', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:54:59', 1.40),
(1825, 2514, 'Udyami Bazar 16', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:53:39', 1.40),
(1824, 2513, 'Udyami Bazar 15', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:52:11', 1.40),
(1823, 2512, 'Udyami Bazar 14', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:50:33', 1.40),
(1822, 2511, 'Udyami Bazar 13', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:49:24', 1.40),
(1821, 2510, 'Udyami Bazar 12', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:47:57', 1.40),
(1820, 2509, 'Udyami Bazar 11', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:46:29', 1.40),
(1819, 2507, 'Udyami Bazar 10', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:42:33', 1.40),
(1818, 2506, 'Udyami Bazar 9', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:40:34', 1.40),
(1817, 2505, 'Udyami Bazar 8', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:38:46', 1.40),
(1816, 2504, 'Udyami Bazar 7', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:37:46', 1.40),
(1815, 2503, 'Udyami Bazar 6', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:36:33', 1.40),
(1814, 2502, 'Udyami Bazar 5', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:35:24', 1.40),
(1813, 2501, 'Udyami Bazar 4', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:34:01', 1.40),
(1812, 2500, 'Udyami Bazar 3', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:32:36', 1.40),
(1811, 2499, 'Udyami Bazar 2', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 07:30:35', 1.40),
(1810, 2498, 'Udyami Bazar 1', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-02 04:38:14', 1.40),
(1809, 2497, 'Udyami Bazar', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-01 18:20:05', 1.40),
(1844, 2533, 'Udyami Bazar 36', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:16:01', 1.40),
(1845, 2534, 'Udyami Bazar 37', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:17:00', 1.40),
(1846, 2535, 'Udyami Bazar 38', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:17:48', 1.40),
(1847, 2536, 'Udyami Bazar 39', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:18:37', 1.40),
(1848, 2537, 'Udyami Bazar 40', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:19:17', 1.40),
(1849, 2538, 'Udyami Bazar 41', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:20:04', 1.40),
(1850, 2539, 'Udyami Bazar 42', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:20:54', 1.40),
(1851, 2540, 'Udyami Bazar 43', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:21:47', 1.40),
(1852, 2541, 'Udyami Bazar 45', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:24:18', 1.40),
(1853, 2542, 'Udyami Bazar 46', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:27:11', 1.40),
(1854, 2543, 'Udyami Bazar 47', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:28:44', 1.40),
(1855, 2544, 'Udyami Bazar 48', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:29:47', 1.40),
(1856, 2545, 'Udyami Bazar 49', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:30:32', 1.40),
(1857, 2546, 'Udyami Bazar 50', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:31:29', 1.40),
(1858, 2547, 'Udyami Bazar 51', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:34:17', 1.40),
(1859, 2548, 'Udyami Bazar 52', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:35:10', 1.40),
(1860, 2549, 'Udyami Bazar 53', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:36:04', 1.40),
(1861, 2550, 'Udyami Bazar 54', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:37:09', 1.40),
(1862, 2551, 'Udyami Bazar 55', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:37:52', 1.40),
(1863, 2552, 'Udyami Bazar 56', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:38:48', 1.40),
(1864, 2553, 'Udyami Bazar 57', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:40:43', 1.40),
(1865, 2554, 'Udyami Bazar 58', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:41:39', 1.40),
(1866, 2555, 'Udyami Bazar 59', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:42:34', 1.40),
(1867, 2556, 'Udyami Bazar 60', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:43:40', 1.40),
(1868, 2557, 'Udyami Bazar 61', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:51:57', 1.40),
(1869, 2558, 'Udyami Bazar 62', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:53:00', 1.40),
(1870, 2559, 'Udyami Bazar 63 karmyogi', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:54:16', 1.40),
(1871, 2560, 'udyami Bazar 64 G S L Farma', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:56:14', 1.40),
(1872, 2561, 'Udyami Bazar 65 GILTecno', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-04 17:57:59', 1.40),
(1873, 2562, 'Sushil Kumar Srivastava', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-05 10:12:20', 1.40),
(1874, 2563, 'Samudra Lal Kushwaha', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-06 09:57:34', 1.40),
(1875, 2564, 'Pankaj Arora', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-06 11:45:02', 1.40),
(1876, 2565, 'Gudiya Devi', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-08 10:41:56', 1.40),
(1877, 2566, 'Vijay Kumar maurya', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-10 13:19:58', 1.40),
(1878, 2567, 'Chakrapani Tripathi', 1270.00, 228.60, 1500.00, 'Voucher PIN', 'Joining Fee for new account activation (Inclusive of 18% GST)', '2026-01-14 06:26:14', 1.40);

-- --------------------------------------------------------

--
-- Table structure for table `business_plans`
--

CREATE TABLE `business_plans` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('watch','referral','level','admin_credit') NOT NULL,
  `level` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('released','pending') DEFAULT 'released',
  `meta` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `earnings`
--

INSERT INTO `earnings` (`id`, `user_id`, `type`, `level`, `amount`, `status`, `meta`, `created_at`) VALUES
(5572, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2497', '2026-01-01 18:20:05'),
(5573, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2498', '2026-01-02 04:38:14'),
(5574, 1, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 04:38:14'),
(5575, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2499', '2026-01-02 07:30:35'),
(5576, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2500', '2026-01-02 07:32:36'),
(5577, 2497, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:32:36'),
(5578, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2501', '2026-01-02 07:34:00'),
(5579, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2502', '2026-01-02 07:35:24'),
(5580, 2498, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:35:24'),
(5581, 1, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 07:35:24'),
(5582, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2503', '2026-01-02 07:36:33'),
(5583, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2504', '2026-01-02 07:37:45'),
(5584, 2499, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:37:45'),
(5585, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2505', '2026-01-02 07:38:45'),
(5586, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2506', '2026-01-02 07:40:34'),
(5587, 2500, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:40:34'),
(5588, 2497, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 07:40:34'),
(5589, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2507', '2026-01-02 07:42:32'),
(5590, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2508', '2026-01-02 07:45:00'),
(5591, 2501, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:45:00'),
(5592, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2509', '2026-01-02 07:46:29'),
(5593, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2510', '2026-01-02 07:47:57'),
(5594, 2502, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:47:57'),
(5595, 2498, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 07:47:57'),
(5596, 1, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-02 07:47:57'),
(5597, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2511', '2026-01-02 07:49:24'),
(5598, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2512', '2026-01-02 07:50:33'),
(5599, 2503, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:50:33'),
(5600, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2513', '2026-01-02 07:52:11'),
(5601, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2514', '2026-01-02 07:53:39'),
(5602, 2504, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 07:53:39'),
(5603, 2499, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 07:53:39'),
(5604, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2515', '2026-01-02 07:54:59'),
(5605, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2516', '2026-01-02 08:01:29'),
(5606, 2505, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 08:01:29'),
(5607, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2517', '2026-01-02 08:02:50'),
(5608, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2518', '2026-01-02 08:14:19'),
(5609, 2506, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 08:14:19'),
(5610, 2500, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 08:14:19'),
(5611, 2497, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-02 08:14:19'),
(5612, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2519', '2026-01-02 08:16:31'),
(5613, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2520', '2026-01-02 08:18:07'),
(5614, 2507, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 08:18:07'),
(5615, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2521', '2026-01-02 08:22:43'),
(5616, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2522', '2026-01-02 08:24:13'),
(5617, 2508, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 08:24:13'),
(5618, 2501, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-02 08:24:13'),
(5619, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2523', '2026-01-02 08:38:28'),
(5620, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2524', '2026-01-02 08:41:08'),
(5621, 2509, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-02 08:41:08'),
(5622, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2525', '2026-01-02 08:44:18'),
(5623, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2526', '2026-01-03 11:00:18'),
(5624, 2510, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-03 11:00:18'),
(5625, 2502, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-03 11:00:18'),
(5626, 2498, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-03 11:00:18'),
(5627, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2527', '2026-01-03 11:02:00'),
(5628, 1, 'level', NULL, 406.40, 'released', 'Level 5 income completed with 32 members', '2026-01-03 11:02:00'),
(5629, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2528', '2026-01-03 11:03:31'),
(5630, 2511, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-03 11:03:31'),
(5631, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2529', '2026-01-03 11:06:17'),
(5632, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2530', '2026-01-03 11:07:31'),
(5633, 2512, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-03 11:07:31'),
(5634, 2503, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-03 11:07:31'),
(5635, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2531', '2026-01-03 11:09:22'),
(5636, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2532', '2026-01-03 11:10:43'),
(5637, 2513, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-03 11:10:43'),
(5638, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2533', '2026-01-04 17:16:01'),
(5639, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2534', '2026-01-04 17:17:00'),
(5640, 2514, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:17:00'),
(5641, 2504, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:17:00'),
(5642, 2499, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-04 17:17:00'),
(5643, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2535', '2026-01-04 17:17:48'),
(5644, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2536', '2026-01-04 17:18:37'),
(5645, 2515, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:18:37'),
(5646, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2537', '2026-01-04 17:19:17'),
(5647, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2538', '2026-01-04 17:20:04'),
(5648, 2516, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:20:04'),
(5649, 2505, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:20:04'),
(5650, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2539', '2026-01-04 17:20:54'),
(5651, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2540', '2026-01-04 17:21:47'),
(5652, 2517, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:21:47'),
(5653, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2541', '2026-01-04 17:24:18'),
(5654, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2542', '2026-01-04 17:27:11'),
(5655, 2518, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:27:11'),
(5656, 2506, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:27:11'),
(5657, 2500, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-04 17:27:11'),
(5658, 2497, 'level', NULL, 203.20, 'released', 'Level 4 income completed with 16 members', '2026-01-04 17:27:11'),
(5659, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2543', '2026-01-04 17:28:44'),
(5660, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2544', '2026-01-04 17:29:47'),
(5661, 2519, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:29:47'),
(5662, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2545', '2026-01-04 17:30:32'),
(5663, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2546', '2026-01-04 17:31:28'),
(5664, 2520, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:31:28'),
(5665, 2507, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:31:28'),
(5666, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2547', '2026-01-04 17:34:17'),
(5667, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2548', '2026-01-04 17:35:09'),
(5668, 2521, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:35:09'),
(5669, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2549', '2026-01-04 17:36:04'),
(5670, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2550', '2026-01-04 17:37:09'),
(5671, 2522, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:37:09'),
(5672, 2508, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:37:09'),
(5673, 2501, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-04 17:37:09'),
(5674, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2551', '2026-01-04 17:37:52'),
(5675, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2552', '2026-01-04 17:38:47'),
(5676, 2523, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:38:47'),
(5677, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2553', '2026-01-04 17:40:42'),
(5678, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2554', '2026-01-04 17:41:38'),
(5679, 2524, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:41:38'),
(5680, 2509, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:41:38'),
(5681, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2555', '2026-01-04 17:42:34'),
(5682, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2556', '2026-01-04 17:43:39'),
(5683, 2525, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:43:39'),
(5684, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2557', '2026-01-04 17:51:57'),
(5685, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2558', '2026-01-04 17:53:00'),
(5686, 2526, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:53:00'),
(5687, 2510, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-04 17:53:00'),
(5688, 2502, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-04 17:53:00'),
(5689, 2498, 'level', NULL, 203.20, 'released', 'Level 4 income completed with 16 members', '2026-01-04 17:53:00'),
(5690, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2559', '2026-01-04 17:54:16'),
(5691, 1, 'level', NULL, 812.80, 'released', 'Level 6 income completed with 64 members', '2026-01-04 17:54:16'),
(5692, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2560', '2026-01-04 17:56:14'),
(5693, 2527, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-04 17:56:14'),
(5694, 1, '', NULL, 127.00, 'released', 'Direct Referral from User 2561', '2026-01-04 17:57:59'),
(5695, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2562', '2026-01-05 10:12:19'),
(5696, 2528, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-05 10:12:19'),
(5697, 2511, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-05 10:12:19'),
(5698, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2563', '2026-01-06 09:57:34'),
(5699, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2564', '2026-01-06 11:45:02'),
(5700, 2529, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-06 11:45:02'),
(5701, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2565', '2026-01-08 10:41:56'),
(5702, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2566', '2026-01-10 13:19:58'),
(5703, 2530, 'level', NULL, 25.40, 'released', 'Level 1 income completed with 2 members', '2026-01-10 13:19:58'),
(5704, 2512, 'level', NULL, 50.80, 'released', 'Level 2 income completed with 4 members', '2026-01-10 13:19:58'),
(5705, 2503, 'level', NULL, 101.60, 'released', 'Level 3 income completed with 8 members', '2026-01-10 13:19:58'),
(5706, 2559, '', NULL, 127.00, 'released', 'Direct Referral from User 2567', '2026-01-14 06:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `caption`, `upload_date`) VALUES
(1, '../uploads/gallery/1760528605_1000003513.jpg', 'What is this ', '2025-10-15 11:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_slider`
--

CREATE TABLE `homepage_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc`
--

CREATE TABLE `kyc` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `account_holder` varchar(150) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `id_proof_type` varchar(50) DEFAULT NULL,
  `id_proof_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kyc`
--

INSERT INTO `kyc` (`id`, `user_id`, `full_name`, `bank_name`, `account_number`, `ifsc_code`, `account_holder`, `photo`, `id_proof_type`, `id_proof_file`, `status`, `created_at`) VALUES
(2, 2567, 'Chakrapani Tripathi ', 'Union Bank of India ', '569702010071085', 'UBIN0556971', 'Chakrapani Tripathi ', 'uploads/kyc/1768372698_IMG-20250918-WA0002.jpg', 'Aadhaar', 'uploads/kyc/1768372698_IMG-20260114-WA0003.jpg', 'Pending', '2026-01-14 06:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `legal_documents`
--

CREATE TABLE `legal_documents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `legal_documents`
--

INSERT INTO `legal_documents` (`id`, `name`, `photo`, `description`, `upload_date`) VALUES
(5, 'UDYAMI BAZAR UDYAM REGISTRATION CERTIFICATE', '../uploads/legal/1767519137_UDYAMI BAZAR UDYM REGISTETION (1).pdf', 'UDYAMI BAZAR UDYAM REGISTRATION CERTIFICATE ', '2026-01-04 09:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(11) NOT NULL,
  `method_type` enum('BANK','UPI','QR','WALLET') NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `account_holder` varchar(100) DEFAULT NULL,
  `upi_id` varchar(100) DEFAULT NULL,
  `qr_image` varchar(255) DEFAULT NULL,
  `wallet_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `method_type`, `bank_name`, `account_number`, `ifsc_code`, `account_holder`, `upi_id`, `qr_image`, `wallet_address`, `created_at`) VALUES
(21, 'WALLET', NULL, NULL, NULL, NULL, NULL, NULL, '0x4c4457e6C0d55fffe2A126B25170cBC92F512047', '2026-01-11 10:20:31'),
(20, 'UPI', NULL, NULL, NULL, NULL, '9454611685@indianbk', NULL, NULL, '2026-01-11 10:07:40'),
(16, 'QR', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string ', '<br /><b>Deprecated</b>:  htmlspecialchars(): Pass', '<br /><b>Deprecated<', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string ', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string ', '../uploads/qr/1768126617_IMG-20260111-WA0008.jpg', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\udyamibazar\\admin\\edit_payment.php</b> on line <b>113</b><br />', '2026-01-03 17:46:46'),
(22, 'BANK', 'INDIAN BANK', '7941962901', 'IDIB000C160', 'A R M ENTERPRISE ', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string ', NULL, '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>/home/u564757906/domains/udyamibazar.com/public_html/admin/edit_payment.php</b> on line <b>113</b><br />', '2026-01-11 10:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'T Shirt', 'Smart \r\n', 1500.00, '1760011687_all.jpg', '2025-10-02 18:31:29'),
(6, 'Tour Package ', 'Advance booking Token money use in future purchases tour Package ', 1500.00, '1767092378_1000006319.jpg', '2025-12-30 10:59:38'),
(5, 'GOGGLE ', 'Beutiful ', 2999.00, '1767090771_1000006313.jpg', '2025-12-30 10:32:51'),
(7, 'Business education Package ', 'Products & machinery processing & manufacturing education ', 1500.00, '', '2025-12-30 11:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `reward_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `bio` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `sponsor_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `position` enum('left','right') DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `plain_password` varchar(100) DEFAULT NULL,
  `pin` varchar(32) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `referrer_id` int(11) DEFAULT NULL,
  `wallet_balance` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `product_id` int(11) DEFAULT NULL,
  `delivery_type` enum('Booking','Instant') DEFAULT 'Instant'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `sponsor_id`, `parent_id`, `position`, `name`, `email`, `mobile`, `password`, `plain_password`, `pin`, `is_active`, `referrer_id`, `wallet_balance`, `created_at`, `product_id`, `delivery_type`) VALUES
(1, 1, 1, 'left', 'Root User', 'root@gmail.com', '0000000000', 'ff9830c42660c1dd1942844f8069b74a', 'root123', 'GI340FF291', 1, NULL, 9652.00, '2025-10-06 01:30:09', 7, 'Booking'),
(2497, 1, 1, 'left', 'Udyami Bazar', 'udyamibazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'ED340FF291', 1, NULL, 381.00, '2026-01-01 18:20:05', 7, 'Booking'),
(2498, 1, 1, 'right', 'Udyami Bazar 1', 'udyamibazaar29@gmail.co', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '863450BC43', 1, NULL, 381.00, '2026-01-02 04:38:14', 7, 'Booking'),
(2499, 1, 2497, 'left', 'Udyami Bazar 2', 'udyamibazaar29@gmail.c', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'B3EA212FB3', 1, NULL, 177.80, '2026-01-02 07:30:35', 7, 'Booking'),
(2500, 1, 2497, 'right', 'Udyami Bazar 3', 'udyamibazaar29@gmail.cam', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '90E8CFE144', 1, NULL, 177.80, '2026-01-02 07:32:36', 7, 'Booking'),
(2501, 1, 2498, 'left', 'Udyami Bazar 4', 'udyamibazaar29@gmail.con', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '11C3819B01', 1, NULL, 177.80, '2026-01-02 07:34:00', 7, 'Booking'),
(2502, 1, 2498, 'right', 'Udyami Bazar 5', 'udyamibazaar29@gmail.can', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'DA483141C8', 1, NULL, 177.80, '2026-01-02 07:35:24', 7, 'Booking'),
(2503, 1, 2499, 'left', 'Udyami Bazar 6', 'udyamibazar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'A5D2CCD590', 1, NULL, 177.80, '2026-01-02 07:36:33', 7, 'Booking'),
(2504, 1, 2499, 'right', 'Udyami Bazar 7', 'udyambazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'C3FEEBCBE4', 1, NULL, 76.20, '2026-01-02 07:37:45', 7, 'Booking'),
(2505, 1, 2500, 'left', 'Udyami Bazar 8', 'udymibazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '1CF8D7ADE3', 1, NULL, 76.20, '2026-01-02 07:38:45', 7, 'Booking'),
(2506, 1, 2500, 'right', 'Udyami Bazar 9', 'udyamibazaar29@gail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D8E38F5B41', 1, NULL, 76.20, '2026-01-02 07:40:34', 7, 'Booking'),
(2507, 1, 2501, 'left', 'Udyami Bazar 10', 'udyamibazaar29@gmil.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'E50BA78A63', 1, NULL, 76.20, '2026-01-02 07:42:32', 7, 'Booking'),
(2508, 1, 2501, 'right', 'Udyami Bazar 11', 'udyamibazaar9@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '6B76BB47AC', 1, NULL, 76.20, '2026-01-02 07:45:00', 7, 'Booking'),
(2509, 1, 2502, 'left', 'Udyami Bazar 12', 'udyamibazaa29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '8366F0E7B7', 1, NULL, 76.20, '2026-01-02 07:46:29', 7, 'Booking'),
(2510, 1, 2502, 'right', 'Udyami Bazar 13', 'udyamibazaar2@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '052DCD0458', 1, NULL, 76.20, '2026-01-02 07:47:57', 7, 'Booking'),
(2511, 1, 2503, 'left', 'Udyami Bazar 14', 'udyamibazaar@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'ADDEB62B63', 1, NULL, 76.20, '2026-01-02 07:49:24', 7, 'Booking'),
(2512, 1, 2503, 'right', 'Udyami Bazar 15', 'udyamibaaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'F01C0DE443', 1, NULL, 76.20, '2026-01-02 07:50:33', 7, 'Booking'),
(2513, 1, 2504, 'left', 'Udyami Bazar 16', 'udyamibzaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '8FC5DBDDE7', 1, NULL, 25.40, '2026-01-02 07:52:11', 7, 'Booking'),
(2514, 1, 2504, 'right', 'Udyami Bazar 17', 'udyamibazaar29@gmail.j', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '167E703161', 1, NULL, 25.40, '2026-01-02 07:53:39', 7, 'Booking'),
(2515, 1, 2505, 'left', 'Udyami Bazar 18', 'udyamibaar29@gmail.com', '9454611685', 'bfcadd0b4e59b1da042dd7fdb12e5ebb', 'Udyami @1', '74C04EABED', 1, NULL, 25.40, '2026-01-02 07:54:59', 7, 'Booking'),
(2516, 1, 2505, 'right', 'Udyami Bazar 19', 'udyabazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D8584077AF', 1, NULL, 25.40, '2026-01-02 08:01:29', 7, 'Booking'),
(2517, 1, 2506, 'left', 'Udyami Bazar 20', 'udmibazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '504032755B', 1, NULL, 25.40, '2026-01-02 08:02:50', 7, 'Booking'),
(2518, 1, 2506, 'right', 'Udyami Bazar 21', 'udyamiazaar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '38A1083675', 1, NULL, 25.40, '2026-01-02 08:14:19', 7, 'Booking'),
(2519, 1, 2507, 'left', 'Udyami Bazar 22', 'udyamibazaar29@gmail.kj', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'ACDA163682', 1, NULL, 25.40, '2026-01-02 08:16:31', 7, 'Booking'),
(2520, 1, 2507, 'right', 'Udyami Bazar 23', 'udyamibar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'A1EB343152', 1, NULL, 25.40, '2026-01-02 08:18:07', 7, 'Booking'),
(2521, 1, 2508, 'left', 'Udyami Bazar 24', 'udymibazaar29@gmail.hh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '4DB9720035', 1, NULL, 25.40, '2026-01-02 08:22:43', 7, 'Booking'),
(2522, 1, 2508, 'right', 'Udyami Bazar 25', 'udyamiar29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '93572A87CF', 1, NULL, 25.40, '2026-01-02 08:24:13', 7, 'Booking'),
(2523, 1, 2509, 'left', 'Udyami Bazar 26', 'udyamibazaar29@gil.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D2D5BC0BB6', 1, NULL, 25.40, '2026-01-02 08:38:28', 7, 'Booking'),
(2524, 1, 2509, 'right', 'Udyami Bazar 27', 'udyamibah29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '0342314430', 1, NULL, 25.40, '2026-01-02 08:41:08', 7, 'Booking'),
(2525, 1, 2510, 'left', 'Udyami Bazar 28', 'udyamjaar29@gil.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'A92D1BEEF2', 1, NULL, 25.40, '2026-01-02 08:44:18', 7, 'Booking'),
(2526, 1, 2510, 'right', 'Udyami Bazar 29', 'udyamibazaar29@gmail.mnk', '9454611685', '0d5ab2435fc8a87a8d67ba1c3a932695', '9454611685', 'AC58C9D0E7', 1, NULL, 25.40, '2026-01-03 11:00:18', 7, 'Booking'),
(2527, 1, 2511, 'left', 'Udyami Bazar 30', 'udyamibazaar29@mnail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'ED18D14A8A', 1, NULL, 25.40, '2026-01-03 11:02:00', 7, 'Booking'),
(2528, 1, 2511, 'right', 'Udyami Bazar 31', 'udyamibazaam29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '13DD5B10FA', 1, NULL, 25.40, '2026-01-03 11:03:31', 7, 'Booking'),
(2529, 1, 2512, 'left', 'Udyami Bazar 32', 'udyamibazamnbr29@gmail.com', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'A78C935517', 1, NULL, 25.40, '2026-01-03 11:06:17', 7, 'Booking'),
(2530, 1, 2512, 'right', 'Udyami Bazar 33', 'udyamibazaar29@gmail.comn', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'E53122047F', 1, NULL, 25.40, '2026-01-03 11:07:31', 7, 'Booking'),
(2531, 1, 2513, 'left', 'Udyami Bazar 34', 'udyamibazaar29@gmail.comh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '2A79931278', 1, NULL, 0.00, '2026-01-03 11:09:22', 7, 'Booking'),
(2532, 1, 2513, 'right', 'Udyami Bazar 35', 'udyamibazaar29@gmail.comhg', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'FDEDF0DED6', 1, NULL, 0.00, '2026-01-03 11:10:43', 7, 'Booking'),
(2533, 1, 2514, 'left', 'Udyami Bazar 36', 'udymibazaar29@gmjhg', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'CA6DC49390', 1, NULL, 0.00, '2026-01-04 17:16:01', 7, 'Booking'),
(2534, 1, 2514, 'right', 'Udyami Bazar 37', 'udymibazaar29@gmail.hkht', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '0272695D69', 1, NULL, 0.00, '2026-01-04 17:17:00', 7, 'Booking'),
(2535, 1, 2515, 'left', 'Udyami Bazar 38', 'udymibazaar29@gmarhv', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '38B6D0BEA7', 1, NULL, 0.00, '2026-01-04 17:17:48', 7, 'Booking'),
(2536, 1, 2515, 'right', 'Udyami Bazar 39', 'udymibazaar29@gmadk', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '917BBBA0D0', 1, NULL, 0.00, '2026-01-04 17:18:37', 7, 'Booking'),
(2537, 1, 2516, 'left', 'Udyami Bazar 40', 'udymibazaar29@gvnj', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '763A4DCCCF', 1, NULL, 0.00, '2026-01-04 17:19:17', 7, 'Booking'),
(2538, 1, 2516, 'right', 'Udyami Bazar 41', 'udymibazaar29@gmalay', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '4B70974DA8', 1, NULL, 0.00, '2026-01-04 17:20:04', 7, 'Booking'),
(2539, 1, 2517, 'left', 'Udyami Bazar 42', 'udymibaza@gjj', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '58ADD640FD', 1, NULL, 0.00, '2026-01-04 17:20:54', 7, 'Booking'),
(2540, 1, 2517, 'right', 'Udyami Bazar 43', 'udymibazaar29@gmail.hhnjkjgdg', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'C228BE5365', 1, NULL, 0.00, '2026-01-04 17:21:47', 7, 'Booking'),
(2541, 1, 2518, 'left', 'Udyami Bazar 44', 'udymibazaar29@gmail.comutfv', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '0AD8BEE4EF', 1, NULL, 0.00, '2026-01-04 17:24:18', 7, 'Booking'),
(2542, 1, 2518, 'right', 'Udyami Bazar 46', 'udyamjaar29@gil.comhgdf', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'E020566C78', 1, NULL, 0.00, '2026-01-04 17:27:11', 7, 'Booking'),
(2543, 1, 2519, 'left', 'Udyami Bazar 47', 'udyamibzaar29@gmail.cobbs', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '48E3F1EDE0', 1, NULL, 0.00, '2026-01-04 17:28:44', 7, 'Booking'),
(2544, 1, 2519, 'right', 'Udyami Bazar 48', 'udyamibzaar29@gmail.covlh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'BBC09754CF', 1, NULL, 0.00, '2026-01-04 17:29:47', 7, 'Booking'),
(2545, 1, 2520, 'left', 'Udyami Bazar 49', 'udyamibzaar29@gmail.cocw', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '145E6E09A0', 1, NULL, 0.00, '2026-01-04 17:30:32', 7, 'Booking'),
(2546, 1, 2520, 'right', 'Udyami Bazar 50', 'udyamibzaar29@gmail.comag', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D2CEBB20E3', 1, NULL, 0.00, '2026-01-04 17:31:28', 7, 'Booking'),
(2547, 1, 2521, 'left', 'Udyami Bazar 51', 'udyamibzaar29@gmail.colbb', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '2349AF18E8', 1, NULL, 0.00, '2026-01-04 17:34:17', 7, 'Booking'),
(2548, 1, 2521, 'right', 'Udyami Bazar 52', 'udyamibzaar29@gmail.coldh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '0703CB3DC8', 1, NULL, 0.00, '2026-01-04 17:35:09', 7, 'Booking'),
(2549, 1, 2522, 'left', 'Udyami Bazar 53', 'udyamibzaar29@gmail.cobt', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'E09340A586', 1, NULL, 0.00, '2026-01-04 17:36:04', 7, 'Booking'),
(2550, 1, 2522, 'right', 'Udyami Bazar 54', 'udyamibzaar29@gmail.cobngi', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '2E8871B6D0', 1, NULL, 0.00, '2026-01-04 17:37:09', 7, 'Booking'),
(2551, 1, 2523, 'left', 'Udyami Bazar 55', 'udyamibzaar29@gmail.cobsj', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '5BA66CEABF', 1, NULL, 0.00, '2026-01-04 17:37:52', 7, 'Booking'),
(2552, 1, 2523, 'right', 'Udyami Bazar 56', 'udyamibzaar29@gmail.colsbh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '3B8B964652', 1, NULL, 0.00, '2026-01-04 17:38:47', 7, 'Booking'),
(2553, 1, 2524, 'left', 'Udyami Bazar 57', 'udyamibzaar29@gmail.cochm', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '2EF68CBA65', 1, NULL, 0.00, '2026-01-04 17:40:42', 7, 'Booking'),
(2554, 1, 2524, 'right', 'Udyami Bazar 58', 'udyamibzaar29@gmail.cogmvh', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '208AB71D8A', 1, NULL, 0.00, '2026-01-04 17:41:38', 7, 'Booking'),
(2555, 1, 2525, 'left', 'Udyami Bazar 59', 'udyamibzaar29@gmail.cgknb', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D4256DA987', 1, NULL, 0.00, '2026-01-04 17:42:34', 7, 'Booking'),
(2556, 1, 2525, 'right', 'Udyami Bazar 60', 'udyamibzaar29@gmail.colhz', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '203F3ACE9D', 1, NULL, 0.00, '2026-01-04 17:43:39', 7, 'Booking'),
(2557, 1, 2526, 'left', 'Udyami Bazar 61', 'udyamibzaar29@gmail.cogmk', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '1B2949C3A5', 1, NULL, 0.00, '2026-01-04 17:51:57', 7, 'Booking'),
(2558, 1, 2526, 'right', 'Udyami Bazar 62', 'udyamibzaar29@gmail.colshv', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '7CEFC1B2F6', 1, NULL, 0.00, '2026-01-04 17:53:00', 7, 'Booking'),
(2559, 1, 2527, 'left', 'Udyami Bazar 63 karmyogi', 'udyamibzaar29@gmail.colkhp', '9454611685', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', 'D796E9B773', 1, NULL, 762.00, '2026-01-04 17:54:16', 7, 'Booking'),
(2560, 1, 2527, 'right', 'udyami Bazar 64 G S L Farma', 'gyanchandraprajapati593@gmail.com', '840000000', '559749c8586278cd1bb5a90a513ccd69', 'Gyan@1', '315E556A2D', 1, NULL, 0.00, '2026-01-04 17:56:14', 7, 'Booking'),
(2561, 1, 2528, 'left', 'Udyami Bazar 65 GILTecno', 'udyamibzaar29@gmail.cophs', '900000//', '4edda1b731c15c9fdd69e1d4c51af0d2', 'Udyami@1', '8900308DA2', 1, NULL, 0.00, '2026-01-04 17:57:59', 7, 'Booking'),
(2562, 2559, 2528, 'right', 'Sushil Kumar Srivastava', 'sushilseema71@gmail.com', '7991778971', '46e8b6212419768c2fb3bf27c646b94d', 'Sushil@123', '533E538CA8', 1, NULL, 0.00, '2026-01-05 10:12:19', 7, 'Booking'),
(2563, 2559, 2529, 'left', 'Samudra Lal Kushwaha', 'skiswah742450@gmail.com', '7897637447', '4746de33c9fa4afcc28ea346c960c128', 'Samudra@1', '0B3BB90941', 1, NULL, 0.00, '2026-01-06 09:57:33', 7, 'Booking'),
(2564, 2559, 2529, 'right', 'Pankaj Arora', 'pankajarora1963@gmail.com', '9451541068', '6d287e7dde53c941940ffa3e9cd76529', 'Arora@1963', '9860EF6166', 1, NULL, 0.00, '2026-01-06 11:45:02', 7, 'Booking'),
(2565, 2559, 2530, 'left', 'Gudiya Devi', 'gudiyabind352@gmail.com', '9129148459', '7a62da90857179d08682fde1870691ed', 'Gudiya@1', 'A92BC22BD2', 1, NULL, 0.00, '2026-01-08 10:41:56', 7, 'Booking'),
(2566, 2559, 2530, 'right', 'Vijay Kumar maurya', 'vm457032@gmail.com', '9936499172', '540b6a574ab58c1e85ea344275197ff6', 'Vijay@2379', 'F7E38CBFBE', 1, NULL, 0.00, '2026-01-10 13:19:58', 7, 'Booking'),
(2567, 2559, 2531, 'left', 'Chakrapani Tripathi', 'panichakratripathi@gmail.com', '8564958223', 'ac2c4657146d8cfabb3df229e4af71a3', 'Tripathi@1', '59E756DDB3', 1, NULL, 0.00, '2026-01-14 06:26:14', 7, 'Booking');

-- --------------------------------------------------------

--
-- Table structure for table `user_video_assign`
--

CREATE TABLE `user_video_assign` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `assigned_at` datetime DEFAULT current_timestamp(),
  `status` enum('assigned','watched','expired') DEFAULT 'assigned'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `embed_url` varchar(255) DEFAULT NULL,
  `earn_percent` decimal(5,2) NOT NULL DEFAULT 1.00,
  `earn_amount` decimal(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `description`, `file_path`, `embed_url`, `earn_percent`, `earn_amount`, `created_by`, `created_at`, `status`) VALUES
(9, 'Karmyogi Spice', 'test', '', 'https://www.youtube.com/watch?v=gVnpmhLkmoQ', 0.10, 1.27, 1, '2025-10-14 11:42:32', 'active'),
(10, 'K b c Add', 'Test', '', 'https://www.youtube.com/watch?v=gVnpmhLkmoQ', 0.50, 6.35, 1, '2025-10-24 11:42:26', 'active'),
(11, 'Karmyogi test ', 'Test post ', '', 'https://www.youtube.com/watch?v=gVnpmhLkmoQ', 0.02, 0.25, 1, '2025-10-30 07:22:55', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `video_views`
--

CREATE TABLE `video_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `earned_amount` decimal(10,2) DEFAULT 0.00,
  `watched_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_watch`
--

CREATE TABLE `video_watch` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `watched_at` datetime DEFAULT current_timestamp(),
  `earned_amount` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `status` enum('unused','used','expired') DEFAULT 'unused',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `amount`, `owner_id`, `status`, `created_at`, `used_at`) VALUES
(1, 'FD4910B802', 1270.00, 1, 'used', '2025-10-02 18:29:43', '2025-10-02 18:32:16'),
(7652, '58ADD640FD', 1270.00, 2539, 'used', '2026-01-01 18:16:58', '2026-01-04 17:20:54'),
(7651, 'C228BE5365', 1270.00, 2540, 'used', '2026-01-01 18:16:58', '2026-01-04 17:21:47'),
(7650, '0AD8BEE4EF', 1270.00, 2541, 'used', '2026-01-01 18:16:58', '2026-01-04 17:24:18'),
(7649, 'E020566C78', 1270.00, 2542, 'used', '2026-01-01 18:16:58', '2026-01-04 17:27:11'),
(7648, '48E3F1EDE0', 1270.00, 2543, 'used', '2026-01-01 18:16:58', '2026-01-04 17:28:44'),
(7647, 'BBC09754CF', 1270.00, 2544, 'used', '2026-01-01 18:16:58', '2026-01-04 17:29:47'),
(7646, '145E6E09A0', 1270.00, 2545, 'used', '2026-01-01 18:16:58', '2026-01-04 17:30:32'),
(7645, 'D2CEBB20E3', 1270.00, 2546, 'used', '2026-01-01 18:16:58', '2026-01-04 17:31:28'),
(7644, '4B70974DA8', 1270.00, 2538, 'used', '2026-01-01 18:16:58', '2026-01-04 17:20:04'),
(7643, '763A4DCCCF', 1270.00, 2537, 'used', '2026-01-01 18:16:58', '2026-01-04 17:19:17'),
(7642, '917BBBA0D0', 1270.00, 2536, 'used', '2026-01-01 18:16:58', '2026-01-04 17:18:37'),
(7641, '38B6D0BEA7', 1270.00, 2535, 'used', '2026-01-01 18:16:58', '2026-01-04 17:17:48'),
(7640, '0272695D69', 1270.00, 2534, 'used', '2026-01-01 18:16:58', '2026-01-04 17:17:00'),
(7639, 'CA6DC49390', 1270.00, 2533, 'used', '2026-01-01 18:16:58', '2026-01-04 17:16:01'),
(7638, 'FDEDF0DED6', 1270.00, 2532, 'used', '2026-01-01 18:16:58', '2026-01-03 11:10:43'),
(7637, '2A79931278', 1270.00, 2531, 'used', '2026-01-01 18:16:58', '2026-01-03 11:09:22'),
(7636, 'E53122047F', 1270.00, 2530, 'used', '2026-01-01 18:16:58', '2026-01-03 11:07:31'),
(7635, 'A78C935517', 1270.00, 2529, 'used', '2026-01-01 18:16:58', '2026-01-03 11:06:17'),
(7634, '13DD5B10FA', 1270.00, 2528, 'used', '2026-01-01 18:16:58', '2026-01-03 11:03:31'),
(7633, 'ED18D14A8A', 1270.00, 2527, 'used', '2026-01-01 18:16:58', '2026-01-03 11:02:00'),
(7632, 'AC58C9D0E7', 1270.00, 2526, 'used', '2026-01-01 18:16:58', '2026-01-03 11:00:18'),
(7631, 'A92D1BEEF2', 1270.00, 2525, 'used', '2026-01-01 18:16:58', '2026-01-02 08:44:18'),
(7630, '0342314430', 1270.00, 2524, 'used', '2026-01-01 18:16:58', '2026-01-02 08:41:08'),
(7629, 'D2D5BC0BB6', 1270.00, 2523, 'used', '2026-01-01 18:16:58', '2026-01-02 08:38:28'),
(7628, '93572A87CF', 1270.00, 2522, 'used', '2026-01-01 18:16:58', '2026-01-02 08:24:13'),
(7627, '4DB9720035', 1270.00, 2521, 'used', '2026-01-01 18:16:58', '2026-01-02 08:22:43'),
(7626, 'A1EB343152', 1270.00, 2520, 'used', '2026-01-01 18:16:58', '2026-01-02 08:18:07'),
(7625, 'ACDA163682', 1270.00, 2519, 'used', '2026-01-01 18:16:58', '2026-01-02 08:16:31'),
(7624, '38A1083675', 1270.00, 2518, 'used', '2026-01-01 18:16:58', '2026-01-02 08:14:19'),
(7623, '504032755B', 1270.00, 2517, 'used', '2026-01-01 18:16:58', '2026-01-02 08:02:50'),
(7622, 'D8584077AF', 1270.00, 2516, 'used', '2026-01-01 18:16:58', '2026-01-02 08:01:29'),
(7621, '74C04EABED', 1270.00, 2515, 'used', '2026-01-01 18:16:58', '2026-01-02 07:54:59'),
(7620, '167E703161', 1270.00, 2514, 'used', '2026-01-01 18:16:58', '2026-01-02 07:53:39'),
(7619, '8FC5DBDDE7', 1270.00, 2513, 'used', '2026-01-01 18:16:58', '2026-01-02 07:52:11'),
(7618, 'F01C0DE443', 1270.00, 2512, 'used', '2026-01-01 18:16:58', '2026-01-02 07:50:33'),
(7617, 'ADDEB62B63', 1270.00, 2511, 'used', '2026-01-01 18:16:58', '2026-01-02 07:49:24'),
(7616, '052DCD0458', 1270.00, 2510, 'used', '2026-01-01 18:16:58', '2026-01-02 07:47:57'),
(7615, '8366F0E7B7', 1270.00, 2509, 'used', '2026-01-01 18:16:58', '2026-01-02 07:46:29'),
(7614, '6B76BB47AC', 1270.00, 2508, 'used', '2026-01-01 18:16:58', '2026-01-02 07:45:00'),
(7613, 'E50BA78A63', 1270.00, 2507, 'used', '2026-01-01 18:16:58', '2026-01-02 07:42:32'),
(7612, 'D8E38F5B41', 1270.00, 2506, 'used', '2026-01-01 18:16:58', '2026-01-02 07:40:34'),
(7611, '1CF8D7ADE3', 1270.00, 2505, 'used', '2026-01-01 18:16:58', '2026-01-02 07:38:45'),
(7610, 'C3FEEBCBE4', 1270.00, 2504, 'used', '2026-01-01 18:16:58', '2026-01-02 07:37:45'),
(7609, 'A5D2CCD590', 1270.00, 2503, 'used', '2026-01-01 18:16:58', '2026-01-02 07:36:33'),
(7608, 'DA483141C8', 1270.00, 2502, 'used', '2026-01-01 18:16:58', '2026-01-02 07:35:24'),
(7607, '11C3819B01', 1270.00, 2501, 'used', '2026-01-01 18:16:58', '2026-01-02 07:34:00'),
(7606, '90E8CFE144', 1270.00, 2500, 'used', '2026-01-01 18:16:58', '2026-01-02 07:32:36'),
(7605, 'B3EA212FB3', 1270.00, 2499, 'used', '2026-01-01 18:16:58', '2026-01-02 07:30:35'),
(7604, '863450BC43', 1270.00, 2498, 'used', '2026-01-01 18:16:58', '2026-01-02 04:38:14'),
(7603, 'ED340FF291', 1270.00, 2497, 'used', '2026-01-01 18:16:58', '2026-01-01 18:20:05'),
(7653, 'E06BD7108A', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7654, '18179FF907', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7655, 'ED2EC2241A', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7656, '6F340D5FD2', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7657, '4291D56A77', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7658, 'CAE4789DB4', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7659, 'F4DFEEFE59', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7660, '15D8D53C91', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7661, '7BF2F9CCD1', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7662, 'D49B77EBFD', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7663, 'B2094B4F7A', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7664, '280BE522E7', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7665, 'A3CF0F7410', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7666, 'F05B18B1C4', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7667, 'B2E690F501', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7668, 'B044E06AD6', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7669, '4D50A0EF34', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7670, '26F34A03B7', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7671, 'CADAB68C07', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7672, '3D621D740D', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7673, '3A3A582CBD', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7674, 'E552DD6E47', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7675, '47FC96058B', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7676, '11DEAE1ECD', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7677, '2DDABAF51F', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7678, '90147C2796', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7679, 'BC957C5C27', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7680, 'DB1C701C9B', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7681, '61AA672F9E', 1270.00, 1, 'unused', '2026-01-04 17:31:58', NULL),
(7682, '59E756DDB3', 1270.00, 2567, 'used', '2026-01-04 17:31:58', '2026-01-14 06:26:14'),
(7683, 'F7E38CBFBE', 1270.00, 2566, 'used', '2026-01-04 17:31:58', '2026-01-10 13:19:58'),
(7684, 'A92BC22BD2', 1270.00, 2565, 'used', '2026-01-04 17:31:58', '2026-01-08 10:41:56'),
(7685, '9860EF6166', 1270.00, 2564, 'used', '2026-01-04 17:31:58', '2026-01-06 11:45:02'),
(7686, '0B3BB90941', 1270.00, 2563, 'used', '2026-01-04 17:31:58', '2026-01-06 09:57:33'),
(7687, 'E09340A586', 1270.00, 2549, 'used', '2026-01-04 17:31:58', '2026-01-04 17:36:04'),
(7688, '0703CB3DC8', 1270.00, 2548, 'used', '2026-01-04 17:31:58', '2026-01-04 17:35:09'),
(7689, '533E538CA8', 1270.00, 2562, 'used', '2026-01-04 17:31:58', '2026-01-05 10:12:19'),
(7690, '8900308DA2', 1270.00, 2561, 'used', '2026-01-04 17:31:58', '2026-01-04 17:57:59'),
(7691, '315E556A2D', 1270.00, 2560, 'used', '2026-01-04 17:31:58', '2026-01-04 17:56:14'),
(7692, 'D796E9B773', 1270.00, 2559, 'used', '2026-01-04 17:31:58', '2026-01-04 17:54:16'),
(7693, '7CEFC1B2F6', 1270.00, 2558, 'used', '2026-01-04 17:31:58', '2026-01-04 17:53:00'),
(7694, '1B2949C3A5', 1270.00, 2557, 'used', '2026-01-04 17:31:58', '2026-01-04 17:51:57'),
(7695, '203F3ACE9D', 1270.00, 2556, 'used', '2026-01-04 17:31:58', '2026-01-04 17:43:39'),
(7696, 'D4256DA987', 1270.00, 2555, 'used', '2026-01-04 17:31:58', '2026-01-04 17:42:34'),
(7697, '208AB71D8A', 1270.00, 2554, 'used', '2026-01-04 17:31:58', '2026-01-04 17:41:38'),
(7698, '2EF68CBA65', 1270.00, 2553, 'used', '2026-01-04 17:31:58', '2026-01-04 17:40:42'),
(7699, '3B8B964652', 1270.00, 2552, 'used', '2026-01-04 17:31:58', '2026-01-04 17:38:47'),
(7700, '5BA66CEABF', 1270.00, 2551, 'used', '2026-01-04 17:31:58', '2026-01-04 17:37:52'),
(7701, '2E8871B6D0', 1270.00, 2550, 'used', '2026-01-04 17:31:58', '2026-01-04 17:37:09'),
(7702, '2349AF18E8', 1270.00, 2547, 'used', '2026-01-04 17:31:58', '2026-01-04 17:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_purchase_requests`
--

CREATE TABLE `voucher_purchase_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `txn_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` datetime DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `voucher_purchase_requests`
--

INSERT INTO `voucher_purchase_requests` (`id`, `user_id`, `quantity`, `amount`, `payment_mode`, `txn_id`, `status`, `requested_at`, `approved_at`, `approved_by`) VALUES
(16, 1, 50, 75000.00, 'Cash', 'A', 'approved', '2026-01-01 18:15:49', '2026-01-01 18:16:58', 1),
(17, 1, 50, 75000.00, 'Cash', 'T', 'approved', '2026-01-04 17:26:02', '2026-01-04 17:31:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `last_updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `watch_history`
--

CREATE TABLE `watch_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `video_id` int(10) UNSIGNED NOT NULL,
  `watched_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `admin_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tds` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `requested_at` datetime DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `business_plans`
--
ALTER TABLE `business_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kyc`
--
ALTER TABLE `kyc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legal_documents`
--
ALTER TABLE `legal_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `sponsor_id` (`sponsor_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `user_video_assign`
--
ALTER TABLE `user_video_assign`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_video_unique` (`user_id`,`video_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_idx` (`status`);

--
-- Indexes for table `video_views`
--
ALTER TABLE `video_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`video_id`);

--
-- Indexes for table `video_watch`
--
ALTER TABLE `video_watch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk_voucher_owner` (`owner_id`);

--
-- Indexes for table `voucher_purchase_requests`
--
ALTER TABLE `voucher_purchase_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_video` (`user_id`,`video_id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1879;

--
-- AUTO_INCREMENT for table `business_plans`
--
ALTER TABLE `business_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5707;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kyc`
--
ALTER TABLE `kyc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `legal_documents`
--
ALTER TABLE `legal_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2568;

--
-- AUTO_INCREMENT for table `user_video_assign`
--
ALTER TABLE `user_video_assign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `video_views`
--
ALTER TABLE `video_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `video_watch`
--
ALTER TABLE `video_watch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7703;

--
-- AUTO_INCREMENT for table `voucher_purchase_requests`
--
ALTER TABLE `voucher_purchase_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `watch_history`
--
ALTER TABLE `watch_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
