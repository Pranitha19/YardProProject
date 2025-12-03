-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 05:12 AM
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
-- Database: `yardpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Admin', 'admin@yardpro.com', '$2y$10$6ZszjrYlF9BPtsNvpYyrtOOsk5UYsMtNBZ4yNSzJyzdmiW3D.vMOi', '2025-11-24 03:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `center_id` int(10) UNSIGNED NOT NULL,
  `service_name` varchar(120) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Requested','Assigned','InProgress','Completed','Cancelled') NOT NULL DEFAULT 'Requested',
  `scheduled_for` datetime DEFAULT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `center_id`, `service_name`, `price`, `status`, `scheduled_for`, `employee_id`, `booking_date`, `booking_time`, `created_at`) VALUES
(1, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-10-28', '10:00:00', '2025-10-28 14:47:18'),
(2, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-10-31', '10:00:00', '2025-10-28 14:48:46'),
(3, 1, 2, 'lawn care service', 100.00, 'Cancelled', NULL, NULL, '2025-10-31', '12:00:00', '2025-10-28 15:01:45'),
(4, 1, 2, 'lawn care service', 100.00, 'Cancelled', NULL, NULL, '2025-11-07', '10:00:00', '2025-10-28 15:03:36'),
(5, 1, 2, 'lawn care service', 100.00, 'Cancelled', NULL, NULL, '2025-10-30', '10:00:00', '2025-10-28 15:17:12'),
(6, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-10-29', '10:00:00', '2025-10-28 16:51:36'),
(7, 1, 2, 'lawn care service', 100.00, 'Requested', NULL, NULL, '2025-10-29', '10:00:00', '2025-10-28 18:43:07'),
(8, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-11-08', '13:00:00', '2025-10-28 21:40:23'),
(9, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-10-31', '12:00:00', '2025-10-29 03:11:09'),
(10, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-10-31', '10:00:00', '2025-10-29 03:28:38'),
(11, 1, 1, 'Landscaping service', 150.00, 'Cancelled', NULL, NULL, '2025-11-29', '14:00:00', '2025-11-27 03:43:39'),
(12, 1, 1, 'Landscaping service', 150.00, 'Cancelled', NULL, NULL, '2026-01-10', '10:00:00', '2025-11-27 03:48:45'),
(13, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-12-05', '10:00:00', '2025-11-27 04:50:32'),
(14, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2025-11-27', '10:00:00', '2025-11-27 07:26:04'),
(15, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 2, '2025-11-27', '10:00:00', '2025-11-27 07:26:48'),
(16, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 2, '2025-11-27', '14:00:00', '2025-11-27 08:04:27'),
(17, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 2, '2025-11-27', '12:00:00', '2025-11-27 08:11:25'),
(21, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-11-27', '14:00:00', '2025-11-27 20:27:17'),
(22, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-11-28', '14:00:00', '2025-11-27 21:04:12'),
(24, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-11-28', '16:00:00', '2025-11-27 21:54:38'),
(25, 1, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-11-28', '16:00:00', '2025-11-27 22:03:19'),
(26, 7, 1, 'Landscaping service', 150.00, 'Completed', NULL, 2, '2025-12-05', '10:00:00', '2025-11-28 04:28:31'),
(27, 8, 1, 'Landscaping service', 150.00, 'Completed', NULL, 1, '2025-12-27', '12:00:00', '2025-12-02 05:06:07'),
(28, 10, 1, 'Landscaping service', 150.00, 'Cancelled', NULL, NULL, '2026-01-10', '10:00:00', '2025-12-02 22:21:07'),
(29, 10, 1, 'Landscaping service', 150.00, 'Cancelled', NULL, NULL, '2026-01-09', '10:00:00', '2025-12-02 22:31:12'),
(30, 1, 1, 'Landscaping service', 150.00, 'Requested', NULL, NULL, '2026-01-31', '10:00:00', '2025-12-03 03:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `email`, `password_hash`, `phone_no`, `address`, `created_at`) VALUES
(1, 'srilakshmi', 'sri@gmail.com', '$2y$10$kJwhq8U7af9VCJlRrmmxAuzD.vpQrPClrbAlYjJ2lmmRpqU01LAj2', '9199999999', '1234 abc street', '2025-11-27 05:11:06'),
(2, 'deliveryboy3', 'deliveryboy3@gmail.com', '$2y$10$L04GYmtAWqjMKmgbQCGPtOpUO4PGtcHfSM.5nAq/pq0EWk4TA.Uji', '6666666666', '1234 abc stree', '2025-12-02 21:54:17'),
(3, 'reddy pranitha', 'reddypranitha760@gmail.com', '$2y$10$2scmHHyH/fS8/3VWgla4W.LSDSyMR488Ok25PunAE2x6ByOKdTHjy', '8888888888', '123 abc street', '2025-12-03 04:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `card_type` varchar(20) DEFAULT NULL,
  `card_holder` varchar(120) DEFAULT NULL,
  `card_number` varchar(25) NOT NULL,
  `cvv` char(3) NOT NULL,
  `expiry_date` varchar(10) NOT NULL,
  `paid_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `amount`, `card_type`, `card_holder`, `card_number`, `cvv`, `expiry_date`, `paid_at`) VALUES
(1, 1, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '11/25', '2025-10-28 09:47:18'),
(2, 2, 150.00, 'Debit', 'anuhya', '2222222222222222', '111', '12/25', '2025-10-28 09:48:46'),
(3, 3, 100.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/25', '2025-10-28 10:01:45'),
(4, 4, 100.00, 'Credit', 'anuhya', '1111111111111111', '111', '01/25', '2025-10-28 10:03:36'),
(6, 6, 150.00, 'Credit', 'anuhya', '2222222222222222', '222', '01/25', '2025-10-28 11:51:36'),
(7, 7, 100.00, 'Debit', 'anuhya', '0000000000000000', '111', '01/25', '2025-10-28 13:43:07'),
(8, 8, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/25', '2025-10-28 16:40:23'),
(9, 9, 150.00, 'Debit', 'anuhya', '1111111111111111', '123', '12/25', '2025-10-28 22:11:09'),
(10, 10, 150.00, 'Debit', 'anuhya', '3333333333333333', '123', '12/25', '2025-10-28 22:28:38'),
(11, 11, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '12/26', '2025-11-26 21:43:39'),
(12, 12, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '10/29', '2025-11-26 21:48:45'),
(13, 13, 150.00, 'Debit', 'anmuhya', '1111111111111111', '111', '11/34', '2025-11-26 22:50:32'),
(14, 14, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/35', '2025-11-27 01:26:04'),
(15, 15, 150.00, 'Debit', 'anmuhya', '0000000000000000', '000', '01/35', '2025-11-27 01:26:48'),
(16, 16, 150.00, 'MasterCard', 'anuhya', '0000000000000000', '000', '01/35', '2025-11-27 02:04:27'),
(17, 17, 150.00, 'Credit', 'anuhya', '1111111111111111', '111', '01/35', '2025-11-27 02:11:25'),
(21, 21, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/35', '2025-11-27 14:27:17'),
(22, 22, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/35', '2025-11-27 15:04:12'),
(24, 24, 150.00, 'MasterCard', 'dd', '1111111111111111', '111', '01/35', '2025-11-27 15:54:38'),
(25, 25, 150.00, 'Credit', 'anmuhya', '1111111111111111', '111', '01/35', '2025-11-27 16:03:19'),
(26, 26, 150.00, 'Debit', 'anuhya', '1111111111111111', '111', '01/35', '2025-11-27 22:28:31'),
(27, 27, 150.00, 'MasterCard', 'sham', '2222222222222222', '222', '01/35', '2025-12-01 23:06:07'),
(28, 28, 150.00, 'Credit', 'anmuhya', '1111111111111111', '111', '01/35', '2025-12-02 16:21:07'),
(29, 29, 150.00, 'MasterCard', 'anuhya', '5555555555555555', '777', '01/35', '2025-12-02 16:31:12'),
(30, 30, 150.00, 'MasterCard', 'anuhya', '1111111111111111', '111', '01/35', '2025-12-02 21:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `service_centers`
--

CREATE TABLE `service_centers` (
  `center_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timings_note` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `base_price` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_centers`
--

INSERT INTO `service_centers` (`center_id`, `name`, `email`, `phone_no`, `description`, `timings_note`, `address`, `base_price`, `created_at`, `image_url`) VALUES
(1, 'Landscaping service', 'landscape@gmail.com', '2222222222', '.............', 'Mon - Fri(10AM - 6PM)\r\nsat(11AM-5PM)', 'Overland park, kansas', 150.00, '2025-10-27 01:21:32', 'data:image/jpeg;base64,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'),
(2, 'lawn care service', 'lawncare@gmail.com', '1111111111', 'Always on the cutting edge', 'Mon - Fri(10AM - 6PM)\r\nsat(11AM-5PM)', 'overland park, kansas', 100.00, '2025-10-27 00:49:17', 'data:image/jpeg;base64,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'),
(3, 'green garden', 'greengarden@gmail.com', '9879879876', 'best at our service', '10 AM - 6 PM', 'Overland park, kansas', 150.00, '2025-10-29 02:52:05', 'https://d3fpcon17n0eur.cloudfront.net/lawn-mowing-guide-1.jpg'),
(4, 'GreenLeaf Lawn Care', 'contact@greenleaflawn.com', '+1-555-789-3210', 'Professional lawn mowing, trimming, and seasonal care for residential and commercial clients.', NULL, '123 Metcalf Ave, Overland Park, KS, 66223', 79.99, '2025-10-28 07:20:16', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'),
(5, 'GoGreenLawn', 'gogreen@yardpro.com', '6128324517', 'Specializing in modern backyard design, patio setups, and sustainable plant installations', NULL, 'Domain Dr, Austin,TX', 100.00, '2025-10-28 07:50:46', 'https://images.pexels.com/photos/212324/pexels-photo-212324.jpeg'),
(6, 'EcoScape Lawn & Garden', 'support@ecoscapelawn.com', '+1-555-321-9876', 'Eco-friendly lawn mowing, weed control, and landscape maintenance using electric equipment.', NULL, '789 Evergreen Terrace, Denver, Colorado 73301', 65.00, '2025-10-29 06:06:02', 'https://d3fpcon17n0eur.cloudfront.net/lawn-mowing-guide-1.jpg'),
(7, 'SunnySide LawnCare', 'info@sunnysideyards.com', '555-555-2468', 'Affordable weekly yard maintenance, hedge trimming, and garden design for suburban homes.', NULL, '456 Oakridge Drive, St.Louis, Missouri 75074', 89.50, '2025-10-29 06:14:03', 'https://images.pexels.com/photos/5027619/pexels-photo-5027619.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `timings`
--

CREATE TABLE `timings` (
  `timings_id` int(10) UNSIGNED NOT NULL,
  `center_id` int(10) UNSIGNED NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timings`
--

INSERT INTO `timings` (`timings_id`, `center_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(15, 3, 1, '10:00:00', '17:00:00'),
(16, 3, 2, '10:00:00', '17:00:00'),
(17, 3, 3, '10:00:00', '17:00:00'),
(18, 3, 4, '10:00:00', '17:00:00'),
(19, 3, 5, NULL, NULL),
(20, 3, 6, NULL, NULL),
(21, 3, 7, NULL, NULL),
(29, 1, 1, '10:00:00', '18:00:00'),
(30, 1, 2, '10:00:00', '18:00:00'),
(31, 1, 3, '10:00:00', '18:00:00'),
(32, 1, 4, '10:00:00', '18:00:00'),
(33, 1, 5, '10:00:00', '18:00:00'),
(34, 1, 6, '10:00:00', '17:00:00'),
(35, 1, 7, '10:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone_no`, `address`, `created_at`) VALUES
(1, 'anuhya', 'M', 'anum@gmail.com', '$2y$10$HdgUwrUoTsviEUshj3qIhuAp76UREtkCAs1gH4N//Xsl584tQ78E6', '9199999997', '1234 abc street', '2025-10-26 02:16:36'),
(2, 'kiran', 'd', 'kiran@gmail.com', '$2y$10$p7KgLI9/EcV0AjrG6MJTKeTG0ygUE5P4SLJthxVx1yhIMwBmbYQu6', '9188888889', 'foster st, Overland Park Kansas', '2025-10-26 05:51:26'),
(3, 'hema', 'd', 'hemad@gmail.com', '$2y$10$YSMeA5P9oGCT8uZx5c8H2OqGApzopdLuiTMEY1HrLbNfrDRIM5qki', '9999999999', 'overlandpark', '2025-10-29 02:02:03'),
(4, 'hema', 'dd', 'hemadd@gmail.com', '$2y$10$mBHzBbZNdZKtPNtMGJgC.Op5mVw16fBx0KaiNJq1M4rXKPNm5u40S', '9999999999', 'overlandpark', '2025-10-29 03:26:54'),
(7, 'kiran', 'M', 'kiranm@gmail.com', '$2y$10$2Uys0us3Qe40D6pVAZyure4J2A6qLYZXw2qSX.Wu28W96EBR8G81.', '9199997777', '1234 abc stree', '2025-11-28 04:27:30'),
(8, 'sham', 'm', 'sham@gmail.com', '$2y$10$2Cadmj1r2vUl2CocMbffmOoJTQx2x2hgaTJiUXkY1eeksQhO9Mt4O', '9188888887', '1234 FosterSt', '2025-12-02 05:05:29'),
(9, 'sham', 'n', 'shamn@gmail.com', '$2y$10$s8ACAIMjOdjBdbZqXMGp9OpZTtKcE2bSAbx.7CH2VCRZdOW8wYpiC', '9188888888', '1234 FosterSt', '2025-12-02 05:28:30'),
(10, 'sham', 'mn', 'shammn@gmail.com', '$2y$10$b8dUOFVGn9t6TvXbYV90wu2tsDdhD1yxziFZokU5XOvNxp1lDDtae', '9188888888', '1234 FosterSt', '2025-12-02 22:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_visits`
--

CREATE TABLE `user_visits` (
  `visit_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `visit_count` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `last_visit_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visitor_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_visits`
--

INSERT INTO `user_visits` (`visit_id`, `user_id`, `visit_count`, `last_visit_at`, `visitor_id`) VALUES
(3, 1, 104, '2025-12-03 04:01:27', 'eec9f5cc229a5395db5f6c80bcf1c43a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_booking_user` (`user_id`),
  ADD KEY `fk_booking_center` (`center_id`),
  ADD KEY `fk_booking_employee` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payment_booking` (`booking_id`);

--
-- Indexes for table `service_centers`
--
ALTER TABLE `service_centers`
  ADD PRIMARY KEY (`center_id`);

--
-- Indexes for table `timings`
--
ALTER TABLE `timings`
  ADD PRIMARY KEY (`timings_id`),
  ADD KEY `fk_timings_center` (`center_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_visits`
--
ALTER TABLE `user_visits`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `fk_uv_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `service_centers`
--
ALTER TABLE `service_centers`
  MODIFY `center_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `timings`
--
ALTER TABLE `timings`
  MODIFY `timings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_visits`
--
ALTER TABLE `user_visits`
  MODIFY `visit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_center` FOREIGN KEY (`center_id`) REFERENCES `service_centers` (`center_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timings`
--
ALTER TABLE `timings`
  ADD CONSTRAINT `fk_timings_center` FOREIGN KEY (`center_id`) REFERENCES `service_centers` (`center_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_visits`
--
ALTER TABLE `user_visits`
  ADD CONSTRAINT `fk_uv_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
