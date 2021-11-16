-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2021 at 02:52 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbprofile`
--

CREATE TABLE `tbprofile` (
  `user_id` bigint(20) NOT NULL,
  `user_name` text,
  `email` text,
  `encpass` text,
  `id` text,
  `image` text,
  `user_type` text,
  `code` text,
  `status` bigint(20) NOT NULL DEFAULT '0',
  `created_on` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbprofile`
--

INSERT INTO `tbprofile` (`user_id`, `user_name`, `email`, `encpass`, `id`, `image`, `user_type`, `code`, `status`, `created_on`) VALUES
(33, 'Anika ', 'anika@gmail.com', '202cb962ac59075b964b07152d234b70', 'Anu', '../../contents/images/9594anika.jpg', 'teacher', '998737', 1, '2021-11-12 22:32:38'),
(34, 'Arman Hozyn', 'arman@gmail.com', '202cb962ac59075b964b07152d234b70', 'Arman Hozyn', NULL, 'student', '549728', 1, '2021-11-12 22:36:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbprofile`
--
ALTER TABLE `tbprofile`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbprofile`
--
ALTER TABLE `tbprofile`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
