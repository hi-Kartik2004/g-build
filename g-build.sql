-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2024 at 09:11 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g-build`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `usn` varchar(256) NOT NULL,
  `sem` int(11) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `first_working_date` date DEFAULT NULL,
  `last_working_date` date NOT NULL,
  `current_attendance` int(11) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `class_days` varchar(256) NOT NULL,
  `attended_dates` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `name`, `email`, `usn`, `sem`, `subject`, `first_working_date`, `last_working_date`, `current_attendance`, `updated_at`, `class_days`, `attended_dates`) VALUES
(7, 'test', 'test@gmail.com', 'TEST', 1, 'dsa', '2024-04-10', '2024-04-24', 4, '2024-04-04 00:36:17', 'monday,tuesday', '2024-04-15,2024-04-22,2024-04-16,2024-04-23'),
(8, 'test', 'test@gmail.com', 'TEST', 1, 'egd', '2024-04-01', '2024-04-30', 5, '2024-04-04 00:36:20', 'monday', '2024-04-01,2024-04-08,2024-04-15,2024-04-22,2024-04-29'),
(9, 'test', 'test@gmail.com', 'TEST', 1, 'maths', '2024-04-05', '2024-04-26', 4, '2024-04-04 00:36:24', 'friday', '2024-04-05,2024-04-12,2024-04-19,2024-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `test_scores`
--

CREATE TABLE `test_scores` (
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `usn` varchar(256) NOT NULL,
  `sem` int(11) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `ia1` int(11) DEFAULT NULL,
  `ia2` int(11) DEFAULT NULL,
  `semEnd` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_scores`
--

INSERT INTO `test_scores` (`name`, `email`, `usn`, `sem`, `subject`, `ia1`, `ia2`, `semEnd`, `updated_at`, `id`, `credits`) VALUES
('test', 'test@gmail.com', 'TEST', 2, 'maths-2', 25, 25, 60, '2024-04-03 22:16:39', 4, 1),
('test', 'test@gmail.com', 'TEST', 3, 'dsa', 20, 20, 60, '2024-04-03 22:15:12', 5, 3),
('test', 'test@gmail.com', 'TEST', 1, 'electronics', 20, 20, 40, '2024-04-03 18:50:53', 6, 3),
('test', 'test@gmail.com', 'TEST', 1, 'egd', 20, 20, 100, '2024-04-03 22:40:00', 9, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `usn` varchar(256) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `branch` varchar(256) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isLoggedIn` int(11) DEFAULT 0,
  `profile_pic` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `usn`, `year`, `branch`, `timestamp`, `isLoggedIn`, `profile_pic`) VALUES
(16, 'prajwal K C', 'prajwalkpl@gmail.com', '$2y$10$ohrop0Ahu76DD.Rn131QI.Y0cdJ9gJZg0eJOHoRpuln1sXpeSqx2e', 'U03NM', 3, 'ISE', '2024-04-02 20:26:45', 0, ''),
(18, 'rushil BR', 'rushilbr@gmail.com', '$2y$10$CE4xCG.5N39cK8UhOP2jY.2D.b/ZJRjXni.VmK.geoF/dYYx6nYT.', '1234', 3, 'ISE', '2024-04-03 10:56:57', 1, NULL),
(19, 'test', 'test@gmail.com', '$2y$10$Qh/dIfhCxnQ.bTLi9.8WpODWeiTQILG0VVnwUSYjur5rweq.l29G.', 'TEST', 3, 'ISE', '2024-04-03 11:49:46', 1, 'profile_pics/test_1712143018.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_scores`
--
ALTER TABLE `test_scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `test_scores`
--
ALTER TABLE `test_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
