-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2016 at 06:02 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `synergy`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `userid` int(11) NOT NULL,
  `fixemup` int(11) NOT NULL DEFAULT '0',
  `engineerofthefuture` int(11) NOT NULL DEFAULT '0',
  `techyhunt` int(11) NOT NULL DEFAULT '0',
  `junkyardwars` int(11) NOT NULL DEFAULT '0',
  `paperpresentation` int(11) NOT NULL DEFAULT '0',
  `waterrocketry` int(11) NOT NULL DEFAULT '0',
  `sanrachana` int(11) NOT NULL DEFAULT '0',
  `paperplane` int(11) NOT NULL,
  `selfpropellingvehicle` int(11) NOT NULL DEFAULT '0',
  `cadmodelling` int(11) NOT NULL DEFAULT '0',
  `mcquiz` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`userid`, `fixemup`, `engineerofthefuture`, `techyhunt`, `junkyardwars`, `paperpresentation`, `waterrocketry`, `sanrachana`, `paperplane`, `selfpropellingvehicle`, `cadmodelling`, `mcquiz`) VALUES
(9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `college` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) DEFAULT NULL,
  `fbid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `college`, `email`, `password`, `fbid`) VALUES
(9, 'Rizwan Hakkim', 'NITT', '111113070@nitt.edu', 'asdfasdf', NULL),
(10, 'Ajish Philip', 'NIT Trichy', 'ajish.philip@outlook.com', 'ajishajish', NULL),
(11, 'a a', 'a', 'a@a', 'asdfasdf', NULL),
(12, 'Rizwan Hakkim', '', 'rizwan.hkm@gmail.com', '', '965490480195194'),
(13, 'sd asd', 'asdfasd', 'cv@asdf', 'asdfasdf', NULL),
(14, 'asdf asdf', 'asdfasdf', 'zxcv@asdfasdf', 'asdfasdf', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
