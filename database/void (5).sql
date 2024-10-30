-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 10:14 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
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
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `dob`, `mobile`, `password`, `created_at`, `otp`, `otp_expiry`) VALUES
(1, 'Admin User', 'admin@example.com', '1980-05-01', '1234567890', '$2y$10$XXXXXXXXXXXXXXXXXXXXX', '2024-09-30 18:00:00', NULL, NULL),
(2, 'lishad', 'admin@gmail.com', '1980-05-01', '1234567890', 'aaaa', '2024-09-30 18:00:00', NULL, NULL),
(3, 'LISHAD', 'admin02@gmail.com', '2024-10-02', '24353434231', '$2y$10$8xt29MXpUGW9kxabL.Z7jeCu9BtY8CVY4T8oXdEXPmxT1D9npLAm.', '2024-10-01 06:50:31', NULL, NULL),
(4, 'adnan', 'adnanarafat007@gmail.com', '2024-10-22', '4737473498383', '$2y$10$50VzLJH3zEzubWTp98RBTeqV7UIJRApsDbXfMk385cjCr6epz2.aG', '2024-10-08 09:15:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `total_copies` int(11) NOT NULL CHECK (`total_copies` >= 0),
  `available_copies` int(11) NOT NULL CHECK (`available_copies` >= 0 and `available_copies` <= `total_copies`),
  `genre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `total_copies`, `available_copies`, `genre`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 1, 0, 'Drama'),
(2, '333', 'George Orwell', 1, 0, 'Drama'),
(3, 'To Kill a Mockingbird', 'Harper Lee', 1, 0, 'Historical Fiction'),
(4, 'The Catcher in the Rye', 'J.D. Salinger', 1, 0, 'Classic Literature'),
(5, 'Pride and Prejudice', 'Jane Austen', 1, 1, 'Drama'),
(6, 'Moby Dick', 'Herman Melville', 1, 1, 'Adventure'),
(7, 'The Hobbit', 'J.R.R. Tolkien', 1, 0, 'Fantasy'),
(8, 'War and Peace', 'Leo Tolstoy', 1, 1, 'Realism'),
(9, 'Crime and Punishment', 'Fyodor Dostoevsky', 1, 1, 'Realism'),
(11, '1984', 'George Orwell', 1, 0, 'Realism'),
(12, 'The Picture of Dorian Gray', 'Oscar Wilde', 1, 1, 'Gothic'),
(13, 'Brave New World', 'Aldous Huxley', 1, 1, 'Dystopian'),
(14, 'The Lord of the Rings', 'J.R.R. Tolkien', 1, 1, 'Epic Fantasy'),
(15, 'Fahrenheit 451', 'Ray Bradbury', 1, 1, 'Dystopian'),
(16, 'The Catcher in the Rye', 'J.D. Salinger', 1, 0, 'Classic Fiction'),
(17, 'The Great Expectations', 'Charles Dickens', 1, 0, 'Drama'),
(18, 'The Adventures of Huckleberry Finn', 'Mark Twain', 1, 1, 'Adventure'),
(19, 'Animal Farm', 'George Orwell', 1, 1, 'Political Fiction'),
(20, 'The Scarlet Letter', 'Nathaniel Hawthorne', 1, 1, 'Classic'),
(21, 'Frankenstein', 'Mary Shelley', 2, 2, 'War'),
(22, 'Don Quixote', 'Miguel de Cervantes', 2, 2, 'Classic Literature'),
(23, 'The Odyssey', 'Homer', 2, 2, 'Romance'),
(24, 'Dracula', 'Bram Stoker', 2, 2, 'Gothic Fiction'),
(25, 'The Brothers Karamazov', 'Fyodor Dostoevsky', 2, 2, 'Philosophical Fiction'),
(26, 'Jane Eyre', 'Charlotte Brontë', 2, 2, 'Romance'),
(27, 'Wuthering Heights', 'Emily Brontë', 2, 2, 'Gothic Fiction'),
(28, 'The Iliad', 'Homer', 2, 2, 'Romance'),
(29, 'Heart of Darkness', 'Joseph Conrad', 2, 2, 'Novella'),
(30, 'Les Misérables', 'Victor Hugo', 2, 2, 'Historical Fiction'),
(31, 'Anna Karenina', 'Leo Tolstoy', 2, 2, 'Romance'),
(32, 'Madame Bovary', 'Gustave Flaubert', 2, 1, 'Realism'),
(33, 'The Stranger', 'Albert Camus', 2, 2, 'Philosophical Fiction'),
(34, 'Catch-22', 'Joseph Heller', 2, 2, 'War '),
(35, 'The Grapes of Wrath', 'John Steinbeck', 2, 2, 'Realism'),
(36, 'Invisible Man', 'Ralph Ellison', 2, 2, 'Social Commentary'),
(37, 'Slaughterhouse-Five', 'Kurt Vonnegut', 2, 2, 'Satire'),
(38, 'The Metamorphosis', 'Franz Kafka', 2, 2, 'Existentialism'),
(39, 'A Tale of Two Cities', 'Charles Dickens', 3, 3, 'Historical Fiction'),
(40, 'Ulysses', 'James Joyce', 5, 5, 'Modernist Literature');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `borrow_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `status` enum('borrowed','returned') DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`borrow_id`, `id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`) VALUES
(1, 13, 1, '2024-10-16 23:20:23', '2024-10-23 23:20:23', '2024-10-24 18:17:37', 'borrowed'),
(2, 13, 4, '2024-10-16 23:27:28', '2024-10-23 23:27:28', '2024-10-24 18:17:43', 'borrowed'),
(3, 13, 9, '2024-10-16 23:29:06', '2024-10-23 23:29:06', '2024-10-24 18:17:50', 'borrowed'),
(4, 13, 7, '2024-10-16 23:33:34', '2024-10-23 23:33:34', '2024-10-24 18:17:47', 'borrowed'),
(5, 13, 8, '2024-10-16 23:37:39', '2024-10-25 23:37:39', '2024-10-24 18:17:48', 'borrowed'),
(6, 13, 5, '2024-10-17 00:00:48', '2024-10-26 00:00:48', '2024-10-22 11:10:56', 'borrowed'),
(7, 13, 6, '2024-10-17 04:08:16', '2024-10-26 04:08:16', '2024-10-24 18:17:46', 'borrowed'),
(8, 13, 1, '2024-10-18 11:51:39', '2024-10-27 11:51:39', '2024-10-24 18:17:37', 'borrowed'),
(9, 13, 4, '2024-10-18 12:02:35', '2024-10-27 12:02:35', '2024-10-24 18:17:43', 'borrowed'),
(10, 13, 9, '2024-10-18 12:08:09', '2024-10-27 12:08:09', '2024-10-24 18:17:50', 'borrowed'),
(11, 13, 16, '2024-10-18 12:39:54', '2024-10-27 12:39:54', '2024-10-24 18:17:59', 'borrowed'),
(12, 13, 9, '2024-10-18 12:41:05', '2024-10-27 12:41:05', '2024-10-24 18:17:50', 'borrowed'),
(13, 13, 18, '2024-10-21 23:49:21', '2024-10-30 23:49:21', '2024-10-24 18:18:02', 'borrowed'),
(14, 15, 17, '2024-10-22 02:42:05', '2024-10-31 02:42:05', '2024-10-21 22:44:08', 'borrowed'),
(15, 13, 17, '2024-10-22 02:45:43', '2024-10-31 02:45:43', '2024-10-24 18:18:01', 'borrowed'),
(16, 13, 12, '2024-10-22 03:06:04', '2024-10-31 03:06:04', '2024-10-24 18:17:54', 'borrowed'),
(17, 13, 12, '2024-10-22 03:10:12', '2024-10-31 03:10:12', '2024-10-24 18:17:54', 'borrowed'),
(18, 13, 1, '2024-10-22 03:40:14', '2024-10-31 03:40:14', '2024-10-24 18:17:37', 'borrowed'),
(19, 13, 1, '2024-10-22 03:40:59', '2024-10-31 03:40:59', '2024-10-24 18:17:37', 'borrowed'),
(20, 13, 14, '2024-10-22 15:12:56', '2024-10-31 15:12:56', '2024-10-24 18:17:57', 'borrowed'),
(21, 13, 1, '2024-10-22 15:34:28', '2024-10-31 15:34:28', '2024-10-24 18:17:37', 'borrowed'),
(22, 13, 4, '2024-10-22 15:35:46', '2024-10-31 15:35:46', '2024-10-24 18:17:43', 'borrowed'),
(23, 13, 1, '2024-10-22 15:55:12', '2024-10-31 15:55:12', '2024-10-24 18:17:37', 'borrowed'),
(24, 13, 2, '2024-10-24 18:57:25', '2024-11-02 18:57:25', '2024-10-24 18:17:39', 'borrowed'),
(25, 15, 35, '2024-10-24 20:22:46', '2024-11-02 20:22:46', '2024-10-25 21:53:34', 'borrowed'),
(26, 15, 2, '2024-10-25 23:53:58', '2024-11-03 23:53:58', '2024-10-25 21:52:40', 'borrowed'),
(27, 15, 9, '2024-10-26 00:02:21', '2024-11-04 00:02:21', '2024-10-25 21:53:07', 'borrowed'),
(28, 15, 8, '2024-10-26 00:02:59', '2024-11-04 00:02:59', '2024-10-25 21:53:06', 'borrowed'),
(29, 16, 4, '2024-10-26 00:29:17', '2024-11-04 00:29:17', NULL, 'borrowed'),
(30, 16, 11, '2024-10-26 00:30:11', '2024-11-04 00:30:11', NULL, 'borrowed'),
(31, 16, 32, '2024-10-26 00:30:44', '2024-11-04 00:30:44', NULL, 'borrowed'),
(32, 15, 25, '2024-10-26 00:50:21', '2024-11-04 00:50:21', '2024-10-25 21:53:24', 'borrowed'),
(33, 13, 1, '2024-10-29 12:46:07', '2024-11-07 12:46:07', NULL, 'borrowed'),
(34, 17, 16, '2024-10-29 15:17:05', '2024-11-07 15:17:05', NULL, 'borrowed'),
(35, 17, 7, '2024-10-29 15:17:53', '2024-11-07 15:17:53', NULL, 'borrowed'),
(36, 17, 17, '2024-10-29 15:18:14', '2024-11-07 15:18:14', NULL, 'borrowed'),
(37, 17, 2, '2024-10-29 15:18:59', '2024-11-07 15:18:59', NULL, 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reservation_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `search_term` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `search_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `search_history`
--

INSERT INTO `search_history` (`id`, `user_email`, `search_term`, `genre`, `search_time`) VALUES
(1, 'aurongojeblishad02@gmail.com', '1984', 'Dystopian', '2024-10-24 12:52:57'),
(2, 'aurongojeblishad02@gmail.com', '333', 'Science Fiction', '2024-10-24 12:57:16'),
(3, 'aurongozeblishad@gmail.com', 'moby dick', 'Adventure', '2024-10-24 13:01:31'),
(4, 'aurongozeblishad@gmail.com', 'war', 'War', '2024-10-24 13:02:01'),
(5, 'aurongozeblishad@gmail.com', 'war', 'War', '2024-10-24 13:04:53'),
(6, 'aurongozeblishad@gmail.com', 'fyo', 'Crime Fiction', '2024-10-24 13:51:58'),
(7, 'aurongozeblishad@gmail.com', 'fyo', 'Philosophical Fiction', '2024-10-24 13:51:58'),
(8, 'aurongozeblishad@gmail.com', 'The Grapes of Wrath', 'Realism', '2024-10-24 14:22:23'),
(9, 'aurongozeblishad@gmail.com', 'The Grapes of Wrath', 'Realism', '2024-10-24 14:22:43'),
(10, 'aurongojeblishad02@gmail.com', 'The Stranger', 'Philosophical Fiction', '2024-10-24 16:15:36'),
(11, 'aurongozeblishad@gmail.com', '333', 'Drama', '2024-10-25 17:53:45'),
(12, 'aurongozeblishad@gmail.com', 'madame', 'Realism', '2024-10-25 17:59:51'),
(13, 'aurongozeblishad@gmail.com', 'cri', 'Realism', '2024-10-25 18:02:19'),
(14, 'aurongozeblishad@gmail.com', 'wa', 'Realism', '2024-10-25 18:02:56'),
(15, 'aurongozeblishad@gmail.com', 'wa', 'Adventure', '2024-10-25 18:02:56'),
(16, 'aurongojeblishad@gmail.com', 'Rye', 'Classic Literature', '2024-10-25 18:28:51'),
(17, 'aurongojeblishad@gmail.com', 'Rye', 'Classic Fiction', '2024-10-25 18:28:51'),
(18, 'aurongojeblishad@gmail.com', 'Rye', 'Classic Literature', '2024-10-25 18:29:13'),
(19, 'aurongojeblishad@gmail.com', 'Rye', 'Classic Fiction', '2024-10-25 18:29:13'),
(20, 'aurongojeblishad@gmail.com', 'crime', 'Realism', '2024-10-25 18:29:46'),
(21, 'aurongojeblishad@gmail.com', '1984', 'Realism', '2024-10-25 18:30:06'),
(22, 'aurongojeblishad@gmail.com', 'madame', 'Realism', '2024-10-25 18:30:42'),
(23, 'aurongojeblishad@gmail.com', 'the', 'Drama', '2024-10-25 18:44:44'),
(24, 'aurongojeblishad@gmail.com', 'the', 'Classic Literature', '2024-10-25 18:44:44'),
(25, 'aurongojeblishad@gmail.com', 'the', 'Fantasy', '2024-10-25 18:44:44'),
(26, 'aurongojeblishad@gmail.com', 'the', 'Gothic', '2024-10-25 18:44:44'),
(27, 'aurongojeblishad@gmail.com', 'the', 'Epic Fantasy', '2024-10-25 18:44:44'),
(28, 'aurongojeblishad@gmail.com', 'the', 'Classic Fiction', '2024-10-25 18:44:44'),
(29, 'aurongojeblishad@gmail.com', 'the', 'Drama', '2024-10-25 18:44:44'),
(30, 'aurongojeblishad@gmail.com', 'the', 'Adventure', '2024-10-25 18:44:44'),
(31, 'aurongojeblishad@gmail.com', 'the', 'Classic', '2024-10-25 18:44:44'),
(32, 'aurongojeblishad@gmail.com', 'the', 'Romance', '2024-10-25 18:44:44'),
(33, 'aurongojeblishad@gmail.com', 'the', 'Philosophical Fiction', '2024-10-25 18:44:44'),
(34, 'aurongojeblishad@gmail.com', 'the', 'Gothic Fiction', '2024-10-25 18:44:44'),
(35, 'aurongojeblishad@gmail.com', 'the', 'Romance', '2024-10-25 18:44:44'),
(36, 'aurongojeblishad@gmail.com', 'the', 'Philosophical Fiction', '2024-10-25 18:44:44'),
(37, 'aurongojeblishad@gmail.com', 'the', 'Realism', '2024-10-25 18:44:44'),
(38, 'aurongojeblishad@gmail.com', 'the', 'Existentialism', '2024-10-25 18:44:44'),
(39, 'aurongojeblishad02@gmail.com', 'Fahrenheit', 'Dystopian', '2024-10-25 18:46:17'),
(40, 'aurongozeblishad@gmail.com', 'brothers', 'Philosophical Fiction', '2024-10-25 18:50:19'),
(41, 'aurongozeblishad@gmail.com', 'brothers', 'Philosophical Fiction', '2024-10-25 19:52:14'),
(42, 'aurongojeblishad02@gmail.com', 'the', 'Drama', '2024-10-29 06:46:03'),
(43, 'aurongojeblishad02@gmail.com', 'the', 'Classic Literature', '2024-10-29 06:46:03'),
(44, 'aurongojeblishad02@gmail.com', 'the', 'Fantasy', '2024-10-29 06:46:03'),
(45, 'aurongojeblishad02@gmail.com', 'the', 'Gothic', '2024-10-29 06:46:03'),
(46, 'aurongojeblishad02@gmail.com', 'the', 'Epic Fantasy', '2024-10-29 06:46:03'),
(47, 'aurongojeblishad02@gmail.com', 'the', 'Classic Fiction', '2024-10-29 06:46:03'),
(48, 'aurongojeblishad02@gmail.com', 'the', 'Drama', '2024-10-29 06:46:03'),
(49, 'aurongojeblishad02@gmail.com', 'the', 'Adventure', '2024-10-29 06:46:03'),
(50, 'aurongojeblishad02@gmail.com', 'the', 'Classic', '2024-10-29 06:46:03'),
(51, 'aurongojeblishad02@gmail.com', 'the', 'Romance', '2024-10-29 06:46:03'),
(52, 'aurongojeblishad02@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 06:46:03'),
(53, 'aurongojeblishad02@gmail.com', 'the', 'Gothic Fiction', '2024-10-29 06:46:03'),
(54, 'aurongojeblishad02@gmail.com', 'the', 'Romance', '2024-10-29 06:46:03'),
(55, 'aurongojeblishad02@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 06:46:03'),
(56, 'aurongojeblishad02@gmail.com', 'the', 'Realism', '2024-10-29 06:46:03'),
(57, 'aurongojeblishad02@gmail.com', 'the', 'Existentialism', '2024-10-29 06:46:03'),
(58, 'adnanarafat007@gmail.com', 'catcher', 'Classic Literature', '2024-10-29 09:17:00'),
(59, 'adnanarafat007@gmail.com', 'catcher', 'Classic Fiction', '2024-10-29 09:17:00'),
(60, 'adnanarafat007@gmail.com', '1984', 'Realism', '2024-10-29 09:17:34'),
(61, 'adnanarafat007@gmail.com', 'the', 'Drama', '2024-10-29 09:17:49'),
(62, 'adnanarafat007@gmail.com', 'the', 'Classic Literature', '2024-10-29 09:17:49'),
(63, 'adnanarafat007@gmail.com', 'the', 'Fantasy', '2024-10-29 09:17:49'),
(64, 'adnanarafat007@gmail.com', 'the', 'Gothic', '2024-10-29 09:17:49'),
(65, 'adnanarafat007@gmail.com', 'the', 'Epic Fantasy', '2024-10-29 09:17:49'),
(66, 'adnanarafat007@gmail.com', 'the', 'Classic Fiction', '2024-10-29 09:17:49'),
(67, 'adnanarafat007@gmail.com', 'the', 'Drama', '2024-10-29 09:17:49'),
(68, 'adnanarafat007@gmail.com', 'the', 'Adventure', '2024-10-29 09:17:49'),
(69, 'adnanarafat007@gmail.com', 'the', 'Classic', '2024-10-29 09:17:49'),
(70, 'adnanarafat007@gmail.com', 'the', 'Romance', '2024-10-29 09:17:49'),
(71, 'adnanarafat007@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 09:17:49'),
(72, 'adnanarafat007@gmail.com', 'the', 'Gothic Fiction', '2024-10-29 09:17:49'),
(73, 'adnanarafat007@gmail.com', 'the', 'Romance', '2024-10-29 09:17:49'),
(74, 'adnanarafat007@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 09:17:49'),
(75, 'adnanarafat007@gmail.com', 'the', 'Realism', '2024-10-29 09:17:49'),
(76, 'adnanarafat007@gmail.com', 'the', 'Existentialism', '2024-10-29 09:17:49'),
(77, 'adnanarafat007@gmail.com', 'the', 'Drama', '2024-10-29 09:18:06'),
(78, 'adnanarafat007@gmail.com', 'the', 'Classic Literature', '2024-10-29 09:18:06'),
(79, 'adnanarafat007@gmail.com', 'the', 'Fantasy', '2024-10-29 09:18:06'),
(80, 'adnanarafat007@gmail.com', 'the', 'Gothic', '2024-10-29 09:18:06'),
(81, 'adnanarafat007@gmail.com', 'the', 'Epic Fantasy', '2024-10-29 09:18:06'),
(82, 'adnanarafat007@gmail.com', 'the', 'Classic Fiction', '2024-10-29 09:18:06'),
(83, 'adnanarafat007@gmail.com', 'the', 'Drama', '2024-10-29 09:18:06'),
(84, 'adnanarafat007@gmail.com', 'the', 'Adventure', '2024-10-29 09:18:06'),
(85, 'adnanarafat007@gmail.com', 'the', 'Classic', '2024-10-29 09:18:06'),
(86, 'adnanarafat007@gmail.com', 'the', 'Romance', '2024-10-29 09:18:06'),
(87, 'adnanarafat007@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 09:18:06'),
(88, 'adnanarafat007@gmail.com', 'the', 'Gothic Fiction', '2024-10-29 09:18:06'),
(89, 'adnanarafat007@gmail.com', 'the', 'Romance', '2024-10-29 09:18:06'),
(90, 'adnanarafat007@gmail.com', 'the', 'Philosophical Fiction', '2024-10-29 09:18:06'),
(91, 'adnanarafat007@gmail.com', 'the', 'Realism', '2024-10-29 09:18:06'),
(92, 'adnanarafat007@gmail.com', 'the', 'Existentialism', '2024-10-29 09:18:06'),
(93, 'adnanarafat007@gmail.com', '333', 'Drama', '2024-10-29 09:18:55');

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
(3, 'Mugdho Rahman', 'eftekhar.rahman@northsouth.edu', '2024-10-02', '8664477977', '$2y$10$G84yHhxQwJKcRyKvspRfGOUaoUtDQCExbrUJedBWejRJc8K2EIosa', '2024-09-30 20:15:31', '880484', '2024-09-30 22:22:47'),
(6, 'ratul', 'rhankon2001@gmail.com', '2024-10-03', '0171905703', '$2y$10$0WwPLSoyG0rXVliP.42IDOMOIB45wRdnENQz7kT04hB3tj577pgt6', '2024-09-30 20:47:36', '628672', '2024-09-30 22:53:23'),
(7, 'adnan', 'reyna@gmail.com', '2024-10-08', '00000001', '$2y$10$i29Yt6ynHyPn3Azt.sTceelRb1IX8N2YlMYQGqkaErrl0JmD0461e', '2024-10-01 05:21:59', NULL, NULL),
(8, 'adnan', 'samihalucky@gmail.com', '2024-10-08', '00000001', '$2y$10$OxT3w76RK.j5jd.DDge26Oq8F1aeknlWATiOmmoo5hD8OCsPZoxPW', '2024-10-01 05:23:56', '370638', '2024-10-01 07:32:56'),
(9, 'devid', 'ankon@gmail.com', '2024-10-09', '9346354322', '$2y$10$flz6b7jVBpPDopYRMZ.zRu44RgZ63FeB36SA8CJv4FSAq2CbB.P5O', '2024-10-01 05:48:36', NULL, NULL),
(10, 'minda', 'drlipon007@gmail.com', '2024-10-02', '00000001', '$2y$10$LRrvU91b27vozejQbJBSMuLS1wo.8o47JPOVAFlWqqQEp1dDDYoHy', '2024-10-01 06:02:30', NULL, NULL),
(11, 'shahriar', 'shahariar@gmail.com', '2024-10-19', '8473746309483', '$2y$10$UqtIE0BA6U9uSr8g52mup.3ovC2zWrKAQfo9eCFVgES1.L2QMzLVa', '2024-10-01 09:25:30', NULL, NULL),
(13, 'LISHAD', 'aurongojeblishad02@gmail.com', '2002-04-20', '01870603476', '$2y$10$roZENCgQi4kTs2fVMv9Ez.lWGZOHmbNumlXQQDi4IByh8hhTwU3zq', '2024-10-02 20:55:34', '190669', '2024-10-29 07:49:04'),
(15, 'aurongojeb', 'aurongozeblishad@gmail.com', '2024-10-04', '99989', '$2y$10$dO/uJal4QIDRW5TizlNmk.Hq4VkozfzhO5ZrtTuGW9TpgoAz9YHPC', '2024-10-21 20:41:13', '742650', '2024-10-25 20:51:56'),
(16, 'AL', 'aurongojeblishad@gmail.com', '2023-11-07', '043497384734', '$2y$10$3D6cSNyvPvm4S6OjjPO6n.3ATMjSzHXFl6fiZkzBDV4GVHvLL6AK2', '2024-10-25 18:26:50', '325466', '2024-10-29 10:24:36'),
(17, 'arafat', 'adnanarafat007@gmail.com', '2024-10-16', '66456347678', '$2y$10$.dZFqLTIGbu5EEzd7UuIL.N/PRFLANuSzkc2b4u5x8wggZV6.beq2', '2024-10-29 09:16:02', '202644', '2024-10-29 10:21:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `id` (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `id` (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
