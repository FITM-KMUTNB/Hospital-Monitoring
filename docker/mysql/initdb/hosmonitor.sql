-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jan 07, 2019 at 06:46 AM
-- Server version: 10.3.9-MariaDB-1:10.3.9+maria~bionic
-- PHP Version: 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hosmonitor`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` mediumint(8) NOT NULL,
  `space_id` mediumint(8) DEFAULT NULL,
  `zone_id` int(5) DEFAULT 0,
  `name` text NOT NULL,
  `description` text DEFAULT NULL,
  `url_short` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `max` float NOT NULL DEFAULT 0,
  `min` float NOT NULL DEFAULT 0,
  `warning` float NOT NULL DEFAULT 0,
  `temp` float NOT NULL DEFAULT 0,
  `ip` varchar(30) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `edit_time` datetime DEFAULT NULL,
  `sort` int(5) NOT NULL DEFAULT 0,
  `type` varchar(20) DEFAULT NULL,
  `notify` varchar(20) NOT NULL DEFAULT 'disable',
  `status` varchar(20) NOT NULL DEFAULT 'disable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(8) NOT NULL,
  `device_id` int(8) NOT NULL,
  `temp` float NOT NULL,
  `update_time` datetime NOT NULL,
  `time_stamp` int(11) DEFAULT NULL,
  `ip` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` mediumint(11) NOT NULL,
  `user_id` int(8) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notify`
--

CREATE TABLE `notify` (
  `id` mediumint(8) NOT NULL,
  `device_id` mediumint(8) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `count` int(3) NOT NULL DEFAULT 1,
  `update_time` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `signature`
--

CREATE TABLE `signature` (
  `id` int(8) NOT NULL,
  `sign` text NOT NULL,
  `expire` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `active_time` datetime DEFAULT NULL,
  `form` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `space`
--

CREATE TABLE `space` (
  `id` mediumint(8) NOT NULL,
  `owner_id` int(8) NOT NULL,
  `title` text NOT NULL,
  `description` text DEFAULT NULL,
  `line_token` text DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `invite_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `space_permission`
--

CREATE TABLE `space_permission` (
  `id` mediumint(8) NOT NULL,
  `space_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `permission` int(2) NOT NULL DEFAULT 3,
  `create_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(8) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fname` text NOT NULL,
  `lname` text DEFAULT NULL,
  `password` text NOT NULL,
  `salt` text DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `register_time` datetime NOT NULL,
  `visit_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE `zone` (
  `id` int(5) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `space_id` mediumint(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `space_id` (`space_id`),
  ADD KEY `zone_id` (`zone_id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`),
  ADD KEY `update_time` (`update_time`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notify`
--
ALTER TABLE `notify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `signature`
--
ALTER TABLE `signature`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space`
--
ALTER TABLE `space`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_permission`
--
ALTER TABLE `space_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `space_id` (`space_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `space_id` (`space_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notify`
--
ALTER TABLE `notify`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signature`
--
ALTER TABLE `signature`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `space`
--
ALTER TABLE `space`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `space_permission`
--
ALTER TABLE `space_permission`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `zone`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
