-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 09:48 AM
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
-- Database: `anonymous_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `8mdloq`
--

CREATE TABLE `8mdloq` (
  `id` int(11) NOT NULL,
  `user_msg_id` varchar(30) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `post_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `8mdloq`
--

INSERT INTO `8mdloq` (`id`, `user_msg_id`, `message`, `post_time`, `post_date`) VALUES
(1, '8mdloQ', 'Hey alfred, im your biggest fan 😎🔥', '2026-02-03 21:27:47', '2026-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `admin_cookie_table`
--

CREATE TABLE `admin_cookie_table` (
  `id` int(11) NOT NULL,
  `cookie_id` varchar(18) NOT NULL,
  `admin_username` varchar(20) NOT NULL,
  `admin_password` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_details`
--

CREATE TABLE `admin_details` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(20) NOT NULL,
  `admin_password` varchar(10) NOT NULL,
  `admin_profile_image` text NOT NULL,
  `admin_fullname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_details`
--

INSERT INTO `admin_details` (`id`, `admin_username`, `admin_password`, `admin_profile_image`, `admin_fullname`) VALUES
(1, 'admin', 'admin123', 'profile.jpg', 'Mr. Jackson Banks');

-- --------------------------------------------------------

--
-- Table structure for table `cookie_table`
--

CREATE TABLE `cookie_table` (
  `id` int(11) NOT NULL,
  `cookieID` varchar(18) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_pass` varchar(15) NOT NULL,
  `date_rec` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_table`
--

CREATE TABLE `feedback_table` (
  `id` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Email` varchar(120) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `no_mails_sent`
--

CREATE TABLE `no_mails_sent` (
  `id` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Email` varchar(120) NOT NULL,
  `No_of_mail_sent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `no_of_visitors`
--

CREATE TABLE `no_of_visitors` (
  `id` int(11) NOT NULL,
  `total_visitors` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_line`
--

CREATE TABLE `subscription_line` (
  `id` int(11) NOT NULL,
  `user_fullname` text NOT NULL,
  `user_email` varchar(120) NOT NULL,
  `date_subscribed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_line`
--

INSERT INTO `subscription_line` (`id`, `user_fullname`, `user_email`, `date_subscribed`) VALUES
(1, 'James Brown', 'jamesbrown@gmail.com', '2026-02-03 23:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_fullname` text NOT NULL,
  `user_password` varchar(10) NOT NULL,
  `user_email` varchar(120) NOT NULL,
  `users_username` varchar(20) NOT NULL,
  `messageID` varchar(10) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_fullname`, `user_password`, `user_email`, `users_username`, `messageID`, `reg_date`) VALUES
(2, 'Michael Alfred', 'password56', 'alfredmichael819@gmail.com', 'michael56$', '8mdloQ', '2026-02-03 21:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_table`
--

CREATE TABLE `visitor_table` (
  `id` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `date_visited` datetime NOT NULL,
  `user_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_table`
--

INSERT INTO `visitor_table` (`id`, `ip_address`, `date_visited`, `user_status`) VALUES
(1, '::1', '2026-02-03 23:06:37', 'Unblocked');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `8mdloq`
--
ALTER TABLE `8mdloq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_cookie_table`
--
ALTER TABLE `admin_cookie_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookie_table`
--
ALTER TABLE `cookie_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback_table`
--
ALTER TABLE `feedback_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_mails_sent`
--
ALTER TABLE `no_mails_sent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_of_visitors`
--
ALTER TABLE `no_of_visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_line`
--
ALTER TABLE `subscription_line`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_table`
--
ALTER TABLE `visitor_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `8mdloq`
--
ALTER TABLE `8mdloq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_cookie_table`
--
ALTER TABLE `admin_cookie_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_details`
--
ALTER TABLE `admin_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cookie_table`
--
ALTER TABLE `cookie_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback_table`
--
ALTER TABLE `feedback_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `no_mails_sent`
--
ALTER TABLE `no_mails_sent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `no_of_visitors`
--
ALTER TABLE `no_of_visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_line`
--
ALTER TABLE `subscription_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitor_table`
--
ALTER TABLE `visitor_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
