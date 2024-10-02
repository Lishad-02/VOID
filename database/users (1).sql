-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2024 at 07:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `void`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `dob`, `mobile`, `password`, `created_at`, `otp`, `otp_expiry`) VALUES
(1, 'MD AURINGOZEB LISHAD', 'aurongojeblishad@gmail.com', '2024-09-12', '9998954646', '$2y$10$3WYUXmj7L92dCjdOI7Y8OOlx6xh7wSUgLr/LN9hHT6t0vkl.ZVHF6', '2024-09-29 22:17:44', NULL, NULL),
(3, 'Mugdho Rahman', 'eftekhar.rahman@northsouth.edu', '2024-10-02', '8664477977', '$2y$10$G84yHhxQwJKcRyKvspRfGOUaoUtDQCExbrUJedBWejRJc8K2EIosa', '2024-09-30 20:15:31', '880484', '2024-09-30 22:22:47'),
(5, 'LISHAD', 'aurongojeblishad02@gmail.com', '2024-10-11', '0909090909', '$2y$10$IywXCqiQIR5GodKWZ6tc7.WQmr7pked/uAbuPqc5fKf3GtlryRXJe', '2024-09-30 20:22:52', '211267', '2024-09-30 22:28:35'),
(6, 'ratul', 'rhankon2001@gmail.com', '2024-10-03', '0171905703', '$2y$10$0WwPLSoyG0rXVliP.42IDOMOIB45wRdnENQz7kT04hB3tj577pgt6', '2024-09-30 20:47:36', '628672', '2024-09-30 22:53:23'),
(7, 'adnan', 'reyna@gmail.com', '2024-10-08', '00000001', '$2y$10$i29Yt6ynHyPn3Azt.sTceelRb1IX8N2YlMYQGqkaErrl0JmD0461e', '2024-10-01 05:21:59', NULL, NULL),
(8, 'adnan', 'samihalucky@gmail.com', '2024-10-08', '00000001', '$2y$10$OxT3w76RK.j5jd.DDge26Oq8F1aeknlWATiOmmoo5hD8OCsPZoxPW', '2024-10-01 05:23:56', '370638', '2024-10-01 07:32:56'),
(9, 'devid', 'ankon@gmail.com', '2024-10-09', '9346354322', '$2y$10$flz6b7jVBpPDopYRMZ.zRu44RgZ63FeB36SA8CJv4FSAq2CbB.P5O', '2024-10-01 05:48:36', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
