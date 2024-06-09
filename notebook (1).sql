-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 06:16 AM
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
-- Database: `notebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `title`, `content`, `created_at`) VALUES
(2, 1, 'CSS Tutorial', 'CSS is the language we use to style an HTML document.\r\n\r\nCSS describes how HTML elements should be displayed.\r\n\r\nThis tutorial will teach you CSS from basic to advanced.', '2024-06-09 01:47:11'),
(3, 1, 'Java Tutorial', 'Learn Java\r\nJava is a popular programming language.\r\n\r\nJava is used to develop mobile apps, web apps, desktop apps, games and much more.\r\n\r\n', '2024-06-09 01:47:51'),
(4, 1, 'PHP Tutorial', 'Learn PHP\r\nPHP is a server scripting language, and a powerful tool for making dynamic and interactive Web pages.\r\n\r\nPHP is a widely-used, free, and efficient alternative to competitors such as Microsoft\'s ASP. Nice', '2024-06-09 01:48:10'),
(5, 1, 'JavaScript Tutorial', 'JavaScript is the world\'s most popular programming language.\r\n\r\nJavaScript is the programming language of the Web.\r\n\r\nJavaScript is easy to learn.\r\n\r\nThis tutorial will teach you JavaScript from basic to advanced.', '2024-06-09 01:48:31'),
(6, 1, 'HTML Tutorial', 'HTML is the standard markup language for Web pages.\r\n\r\nWith HTML you can create your own Website.\r\n\r\nHTML is easy to learn - You will enjoy it!', '2024-06-09 01:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Marc Joseph Cagalitan', 'cramhpesojcagalitan@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 01:40:18'),
(2, 'CITE', 'cite@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin', '2024-06-09 01:49:33'),
(10, 'Khient Carabueana', 'carabuena@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 04:13:35'),
(11, 'Brian Tecling', 'brian@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 04:14:24'),
(12, 'Gen Bryle Bernal', 'bernal@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 04:14:58'),
(13, 'Marcel Rubin', 'rubin@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 04:15:09'),
(14, 'Cyril Perez', 'perez@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'student', '2024-06-09 04:15:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
