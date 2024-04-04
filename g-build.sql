-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2024 at 02:53 PM
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
-- Table structure for table `deadlines`
--

CREATE TABLE `deadlines` (
  `email` varchar(256) NOT NULL,
  `usn` varchar(256) NOT NULL,
  `task` text NOT NULL,
  `deadline_date` date NOT NULL,
  `priority` varchar(256) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deadlines`
--

INSERT INTO `deadlines` (`email`, `usn`, `task`, `deadline_date`, `priority`, `updated_at`, `id`) VALUES
('test@gmail.com', 'TEST', ' Go to bed 2', '2024-04-01', 'low', '2024-04-04 10:07:14', 2),
('test@gmail.com', 'TEST', 'Tata', '2024-04-23', 'medium', '2024-04-04 10:15:11', 4);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `user_email` varchar(256) NOT NULL,
  `user_usn` varchar(256) NOT NULL,
  `category` varchar(256) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `remarks` varchar(256) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`user_email`, `user_usn`, `category`, `amount`, `date`, `remarks`, `updated_at`, `id`) VALUES
('test@gmail.com', 'TEST', 'fees', 200, '2024-04-03', 'Test', '2024-04-04 09:47:44', 3);

-- --------------------------------------------------------

--
-- Table structure for table `gigs`
--

CREATE TABLE `gigs` (
  `id` int(11) NOT NULL,
  `title` varchar(256) DEFAULT 'Not Given',
  `description` text DEFAULT NULL,
  `tags` varchar(256) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(256) DEFAULT NULL,
  `usn` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gigs`
--

INSERT INTO `gigs` (`id`, `title`, `description`, `tags`, `updated_at`, `email`, `usn`) VALUES
(2, 'Hello', 'testing edit', 'test', '2024-04-04 07:23:18', 'test@gmail.com', 'TEST'),
(3, 'Bye', 'testing filter by tags', 'c++, java, php, golang, rust', '2024-04-04 07:27:33', 'test@gmail.com', 'TEST');

-- --------------------------------------------------------

--
-- Table structure for table `gig_reports`
--

CREATE TABLE `gig_reports` (
  `gig_id` int(11) DEFAULT NULL,
  `reporter_email` varchar(256) NOT NULL,
  `title` text NOT NULL,
  `remarks` text NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gig_reports`
--

INSERT INTO `gig_reports` (`gig_id`, `reporter_email`, `title`, `remarks`, `updated_at`, `id`) VALUES
(2, 'test@gmail.com', 'Wtf', 'testing', '2024-04-04 14:51:58', 1),
(2, 'test@gmail.com', 'Report', 'testing', '2024-04-04 14:52:28', 2);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `email` varchar(256) NOT NULL,
  `gig_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`email`, `gig_id`, `message`, `updated_at`, `id`) VALUES
('test@gmail.com', 2, 'Hello', '2024-04-04 13:55:05', 3),
('test@gmail.com', 2, 'Hello', '2024-04-04 14:03:09', 4),
('test@gmail.com', 2, 'Test', '2024-04-04 14:04:58', 5),
('rushilbr@gmail.com', 2, 'Hello', '2024-04-04 14:10:18', 6),
('rushilbr@gmail.com', 2, 'Bye!', '2024-04-04 14:12:55', 7),
('test@gmail.com', 2, 'Yellow!', '2024-04-04 14:15:54', 8),
('test@gmail.com', 2, 'Testing AJAX', '2024-04-04 14:17:19', 9),
('rushilbr@gmail.com', 2, 'Okie so does it work?', '2024-04-04 14:17:30', 10),
('rushilbr@gmail.com', 2, 'Now will it?', '2024-04-04 14:17:40', 11),
('test@gmail.com', 2, 'TEsting ajax v2', '2024-04-04 14:22:35', 12),
('rushilbr@gmail.com', 2, 'Test 3', '2024-04-04 14:22:40', 13),
('test@gmail.com', 2, 'TEst', '2024-04-04 14:23:34', 14),
('test@gmail.com', 2, 'Helllo', '2024-04-04 14:23:54', 15),
('test@gmail.com', 2, 'Bye', '2024-04-04 14:25:46', 16),
('rushilbr@gmail.com', 2, 'Oh ho', '2024-04-04 14:25:52', 17),
('test@gmail.com', 2, 'yeh he', '2024-04-04 14:26:18', 18),
('test@gmail.com', 2, 'Wow!', '2024-04-04 14:26:56', 19),
('test@gmail.com', 2, 'Yeh', '2024-04-04 14:27:13', 20),
('test@gmail.com', 2, 'Yeh', '2024-04-04 14:30:02', 21),
('rushilbr@gmail.com', 2, 'Oh ho', '2024-04-04 14:30:14', 22),
('rushilbr@gmail.com', 2, 'Yeh', '2024-04-04 14:30:27', 23),
('rushilbr@gmail.com', 2, 'testing ajax', '2024-04-04 14:31:39', 24),
('test@gmail.com', 2, 'WTF', '2024-04-04 14:31:45', 25),
('test@gmail.com', 2, 'okei', '2024-04-04 14:33:31', 26),
('rushilbr@gmail.com', 2, 'Okie bye', '2024-04-04 14:34:14', 27),
('test@gmail.com', 2, 'See ya', '2024-04-04 14:34:23', 28),
('test@gmail.com', 2, 'Okei', '2024-04-04 14:39:02', 29),
('rushilbr@gmail.com', 2, 'So', '2024-04-04 14:39:09', 30);

-- --------------------------------------------------------

--
-- Table structure for table `repositories`
--

CREATE TABLE `repositories` (
  `email` varchar(256) DEFAULT NULL,
  `usn` varchar(256) DEFAULT NULL,
  `repo_name` varchar(256) DEFAULT NULL,
  `visibility` varchar(256) DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `repositories`
--

INSERT INTO `repositories` (`email`, `usn`, `repo_name`, `visibility`, `file_path`, `updated_at`, `id`) VALUES
('test@gmail.com', 'TEST', 'Test2', 'private', './uploads/1712228742_farmer.png,./uploads/1712229063_eduminatti_home.png,./uploads/1712231108_4th semester Assignment.pdf', '2024-04-04 17:15:08', 5),
('test@gmail.com', 'TEST', 'Test3', 'public', './uploads/1712228769_email_sender.png,./uploads/1712229131_Kagada_2023_winner_png.png,./uploads/1712229149_kartik_dp.jpg', '2024-04-04 16:42:29', 6),
('rushilbr@gmail.com', '1234', 'Rushil_repo', 'public', './uploads/1712230217_mahesh_1.jpg', '2024-04-04 17:15:22', 7);

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
('test', 'test@gmail.com', 'TEST', 1, 'egd', 20, 20, 100, '2024-04-03 22:40:00', 9, 3),
('test', 'test@gmail.com', 'TEST', 8, 'dbms', 10, 10, 30, '2024-04-04 00:52:50', 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `usn` varchar(256) DEFAULT NULL,
  `token` varchar(256) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(18, 'rushil BR', 'rushilbr@gmail.com', '$2y$10$CE4xCG.5N39cK8UhOP2jY.2D.b/ZJRjXni.VmK.geoF/dYYx6nYT.', '1234', 3, 'ISE', '2024-04-04 08:42:45', 1, 'profile_pics/rushil BR_1712220096.png'),
(19, 'test', 'test@gmail.com', '$2y$10$Qh/dIfhCxnQ.bTLi9.8WpODWeiTQILG0VVnwUSYjur5rweq.l29G.', 'TEST', 3, 'ISE', '2024-04-04 12:05:37', 1, 'profile_pics/test_1712220142.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deadlines`
--
ALTER TABLE `deadlines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gigs`
--
ALTER TABLE `gigs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gig_reports`
--
ALTER TABLE `gig_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repositories`
--
ALTER TABLE `repositories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_scores`
--
ALTER TABLE `test_scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
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
-- AUTO_INCREMENT for table `deadlines`
--
ALTER TABLE `deadlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gigs`
--
ALTER TABLE `gigs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gig_reports`
--
ALTER TABLE `gig_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `repositories`
--
ALTER TABLE `repositories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `test_scores`
--
ALTER TABLE `test_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
