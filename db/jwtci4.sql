-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2021 at 09:13 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jwtci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `retainer_fee` int(100) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `name`, `email`, `retainer_fee`, `updated_at`, `created_at`) VALUES
(1, 'Ms. Reba Hamill', 'leonard76@schinner.com', 22152787, NULL, '2021-01-21 23:15:54'),
(2, 'Miss Thalia Parker', 'brianne.damore@hintz.com', 40105591, NULL, '2021-01-21 23:15:54'),
(3, 'June Hackett', 'aleen.miller@gmail.com', 68524960, NULL, '2021-01-21 23:15:54'),
(4, 'Prof. Makayla Fritsch III', 'yvon@kessler.org', 37312906, NULL, '2021-01-21 23:15:54'),
(5, 'Sharon Bergnaum DVM', 'wilderman.samara@hotmail.com', 38957812, NULL, '2021-01-21 23:15:54'),
(6, 'Leatha Gleason', 'heath93@pagac.com', 15166650, NULL, '2021-01-21 23:15:55'),
(7, 'Prof. Nicolette Crist', 'rbaumbach@fadel.com', 80099158, NULL, '2021-01-21 23:15:55'),
(8, 'Sadye O\'Keefe', 'shanahan.candice@yahoo.com', 30485927, NULL, '2021-01-21 23:15:55'),
(9, 'Lurline Flatley', 'stehr.kayli@kiehn.com', 77401423, NULL, '2021-01-21 23:15:55'),
(10, 'Charles Jacobi', 'ratke.josianne@gmail.com', 74484634, NULL, '2021-01-21 23:15:55'),
(13, 'Test', 'test71@gmail.com', 8896541, NULL, '2021-02-13 13:25:09'),
(17, 'Test', 'Anish171@gmail.com', 88478952, NULL, '2021-02-13 13:34:14'),
(23, 'Test', 'Apyteh17@gmail.com', 8847894, NULL, '2021-02-13 13:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` text NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2021-01-21-173634', 'App\\Database\\Migrations\\AddClient', 'default', 'App', 1611251127, 1),
(2, '2021-01-21-173649', 'App\\Database\\Migrations\\AddUser', 'default', 'App', 1611251127, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `updated_at`, `created_at`) VALUES
(1, 'muzaffer', 'muzaffer@gmail.com', '$2y$10$ivEusZv1gjMvjfJ9EXMry.vEDBATu5pb2..usBoYx9ahOLWybl/0e', NULL, '2021-01-21 23:46:47'),
(2, 'Mdmuzaffer', 'muzaffer12@gmail.com', '$2y$10$FOC9GFI4NtC.f2HKylWKPeaMN0dYUMTy4szzGDgt1zu5RlpqnokGe', NULL, '2021-01-22 00:18:04'),
(3, 'Muzaffer', 'muzaffer121@gmail.com', '$2y$10$F2OahOBrFSVxvkHENqjXj.a9bXvIgd7K2NxvPxImEMi6ADj2EnLSq', NULL, '2021-02-13 11:12:19'),
(4, 'Muzaffer', 'muzaffer10@gmail.com', '$2y$10$D5j73xod.eSO48zT6zKQ..6yqk8ffvmlvDDNxKVoecocvJQekgXWi', NULL, '2021-02-13 11:17:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `retainer_fee` (`retainer_fee`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
