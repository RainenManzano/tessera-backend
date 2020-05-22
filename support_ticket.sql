-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2019 at 03:33 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `support_ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `activation`
--

CREATE TABLE `activation` (
  `Activation_Id` bigint(20) NOT NULL,
  `Verification_Code` varchar(15) NOT NULL,
  `Is_Activated` char(1) NOT NULL DEFAULT '0',
  `User_Id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activation`
--

INSERT INTO `activation` (`Activation_Id`, `Verification_Code`, `Is_Activated`, `User_Id`) VALUES
(10, '1234567890', '1', 45),
(11, '24680', '1', 18),
(12, '0789405408', '1', 46),
(13, '8745074465', '1', 47),
(20, '8111810567', '1', 54),
(22, '0100147105', '1', 55),
(24, '2624532445', '1', 56),
(25, '3422340736', '1', 57),
(26, '3664896633', '1', 58),
(27, '2224856943', '1', 59),
(28, '6689660475', '1', 60),
(29, '2108094101', '1', 61),
(30, '1555492751', '1', 62),
(46, '3334992154', '1', 78),
(47, '5104551414', '1', 79),
(50, '2633875529', '1', 82);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Category_Id` bigint(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Category_Id`, `Name`, `Description`) VALUES
(13, 'Hardware', 'Hardware category'),
(14, 'Software', 'Software Category'),
(20, 'Network', 'Network Category');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_Id` bigint(20) NOT NULL,
  `Comment` text NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `User_Id` bigint(20) NOT NULL,
  `Ticket_Id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_Id`, `Comment`, `DateCreated`, `User_Id`, `Ticket_Id`) VALUES
(5, 'Please leave a comment for the unit number', '2019-10-01 18:53:32', 62, 11),
(6, '#9614830', '2019-10-01 19:11:02', 56, 11),
(7, 'something', '2019-10-01 19:12:30', 56, 11),
(8, 'How much ram will be installed in my PC?', '2019-10-02 20:50:48', 45, 51),
(9, 'Please indicate your reason for reassigning this ticket.', '2019-10-16 05:19:09', 55, 79);

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `Id` bigint(20) NOT NULL,
  `Event_Name` varchar(400) NOT NULL,
  `Value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`Id`, `Event_Name`, `Value`) VALUES
(5, 'registerType', '3');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `Department_Id` bigint(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` varchar(80) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`Department_Id`, `Name`, `Description`) VALUES
(0, 'Admin', 'Available for only admins of the system'),
(1, 'IT', 'Information Technology Department'),
(60, 'Sales', 'Sales Department'),
(64, 'Marketing', ' Marketing Department'),
(65, 'HR', ' Human Resources'),
(66, 'Accounting', ' Accounting Department'),
(70, 'Buying', ' Buying Department'),
(71, 'R and D', ' R & D Department'),
(73, 'Production', ' Production Department'),
(74, 'Finance', 'FInance money');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `Issue_Id` bigint(20) NOT NULL,
  `Issue` varchar(80) NOT NULL,
  `Description` text NOT NULL,
  `Category_Id` bigint(20) DEFAULT NULL,
  `Priority_Id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`Issue_Id`, `Issue`, `Description`, `Category_Id`, `Priority_Id`) VALUES
(1, 'PC Overheating', 'A heating PC slows down the whole system and leads to frequent crashes.', 13, 2),
(2, 'Dysfunctional USB Port', ' If your USB port stops working', 13, 1),
(3, 'PC keeps disconnecting from WiFi', ' If your Wi-Fi is working fine but your PC keeps disconnecting from it', 13, 1),
(4, 'PC beeps', ' The PC motherboard is smart enough to detect problems and sounds beeps in different rhythms to tell you', 13, 5),
(5, 'PC Fans not working', ' If you notice one or more fans in your PC arenâ€™t working', 13, 2),
(6, 'PC not using a portion of RAM', ' You may have 4GB of RAM, but your PC only uses 2GB when you check it from the Task Manager', 13, 2),
(7, 'PC crashes before loading the OS', ' If your PC only shows manufacturer logo and then crashes right before it was supposed to load the operating system', 13, 2),
(8, 'PC isnâ€™t powering on', ' If your PC is not powering on at all â€“ not even a single light in it', 13, 2),
(9, 'Noisy PC', ' If you hear a lot of extra noise while using the PC', 13, 2),
(10, 'Blue Screen of Death', 'If an error screen displayed on a Windows computer system after a fatal system error', 13, 2),
(11, 'Keyboard issues', 'If your keyboard is making noise and wonâ€™t type repeated words properly,', 13, 2),
(12, 'Inadequate software performance', ' This refers to slow system response times and transaction throughput rates.', 14, 5),
(13, 'Software that is difficult to use', ' This problem relates to a lack of understanding of how humans interact with computers and is also the result of a history of modifications that are not planned and coordinated to account for ease of use', 14, 1),
(14, 'No longer supported by the vendor', ' This occurs when a vendor ceases to support a particular software product. This can occur due to the vendor\'s decision to no longer support a product, due to the vendor going out of business, or the vendor selling the product to another vendor.', 14, 1),
(15, 'Firewall connection count exceeded', ' New connections via the firewall fail Business applications exhibit intermittent failure at high firewall loads VPNs begin to fail', 20, 5),
(16, 'Link hog', ' Slower application response, impacting user productivity', 20, 1),
(17, 'Interface traffic congestion', ' Unpredictable application performance, impacting user productivity', 20, 1),
(18, 'Link problems & stability', ' Physical or DataLink errors cause slow or intermittent application performance Link or interface stability can impact routing and spanning tree', 20, 2),
(19, 'Incorrect serial bandwidth setting', ' Causes routing protocols to make non-optimum routing decisions', 20, 3),
(20, 'Route flaps', '  Poor application performance as packets take the wrong or inefficient paths in the network', 20, 3),
(21, 'No Connection', ' ', 20, 1),
(22, 'burning', ' ', 13, 3);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `Message_Id` bigint(20) NOT NULL,
  `From_User` bigint(20) NOT NULL,
  `To_User` bigint(20) NOT NULL,
  `Message` text NOT NULL,
  `Date_Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`Message_Id`, `From_User`, `To_User`, `Message`, `Date_Created`) VALUES
(10, 62, 54, 'test1', '2019-08-30 01:01:29'),
(11, 54, 62, 'test2', '2019-08-30 01:01:30'),
(12, 62, 54, 'test3', '2019-08-30 01:01:31'),
(13, 54, 62, 'test4', '2019-08-30 01:01:32'),
(14, 62, 54, 'test5', '2019-08-30 01:01:33'),
(15, 54, 62, 'test6', '2019-08-30 01:01:34'),
(16, 62, 54, 'test7', '2019-08-30 01:01:35'),
(17, 54, 62, 'test8', '2019-08-30 01:01:36'),
(18, 62, 54, 'test9', '2019-08-30 01:01:37'),
(19, 54, 62, 'test10', '2019-08-30 01:01:38'),
(20, 62, 79, 'Hi', '2019-10-15 03:07:08'),
(21, 79, 62, 'Hello\n\n', '2019-10-15 09:58:44'),
(23, 62, 54, 'hey\n', '2019-10-15 10:26:18'),
(24, 62, 79, 'I would like to ask your PC Unit number\n', '2019-10-15 12:21:30'),
(25, 54, 62, 'What do you want?\n', '2019-10-15 12:22:08'),
(26, 62, 54, 'Want to ask your Department\n', '2019-10-15 12:23:49'),
(27, 79, 62, 'It\'s #826194\n', '2019-10-15 12:26:36'),
(28, 62, 78, 'Hi Ma\n', '2019-10-15 14:24:32'),
(29, 18, 59, 'oy\n', '2019-10-15 15:47:19'),
(30, 59, 18, 'oy ka din\n', '2019-10-15 15:48:12'),
(31, 18, 59, 'reply\n', '2019-10-15 15:48:27'),
(32, 55, 82, 'Hello\n', '2019-10-16 13:45:38'),
(33, 55, 82, 'asl\n', '2019-10-16 13:45:45'),
(34, 82, 55, 'asl\n', '2019-10-16 13:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `Notification_Id` bigint(20) NOT NULL,
  `User_To_Notify` bigint(20) NOT NULL,
  `Notification_Message` text,
  `Checked` char(1) NOT NULL DEFAULT '0',
  `Url_String` varchar(100) DEFAULT NULL,
  `Query_Params` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`Notification_Id`, `User_To_Notify`, `Notification_Message`, `Checked`, `Url_String`, `Query_Params`) VALUES
(15, 61, 'A new ticket has been added', '1', '/sts/tickets/79', 'view-supported-tickets'),
(16, 78, 'Ticket #79 status have changed into Open', '1', '/sts/tickets/79', 'view-issued-tickets'),
(17, 78, 'Ticket #79 status have changed into Resolved', '1', '/sts/tickets/79', 'view-issued-tickets'),
(18, 61, 'Ticket #79 has been reopened.', '1', '/sts/tickets/79', 'view-supported-tickets'),
(19, 78, 'Ticket #79 status have changed into Assigned', '1', '/sts/tickets/79', 'view-issued-tickets'),
(20, 78, 'Ticket #79 status have changed into Open', '1', '/sts/tickets/79', 'view-issued-tickets'),
(21, 78, 'Ticket #79 status have changed into Assigned', '1', '/sts/tickets/79', 'view-issued-tickets'),
(22, 59, 'A new ticket has been added', '1', '/sts/tickets/80', 'view-supported-tickets'),
(23, 47, 'A new ticket has been added', '1', '/sts/tickets/81', 'view-supported-tickets'),
(24, 47, 'Ticket #81 status have changed into Closed', '1', '/sts/tickets/81', 'view-supported-tickets'),
(25, 47, 'A new ticket has been added', '1', '/sts/tickets/82', 'view-supported-tickets'),
(26, 58, 'Ticket #64 status have changed into Resolved', '0', '/sts/tickets/64', 'view-issued-tickets'),
(27, 78, 'Ticket #79 status have changed into Open', '1', '/sts/tickets/79', 'view-issued-tickets'),
(28, 78, 'Ticket #79 status have changed into Resolved', '1', '/sts/tickets/79', 'view-issued-tickets'),
(29, 61, 'Ticket #79 has been reopened.', '1', '/sts/tickets/79', 'view-supported-tickets'),
(30, 78, 'Ticket #79 status have changed into Assigned', '1', '/sts/tickets/79', 'view-issued-tickets'),
(31, 78, 'Ticket #79 status have changed into Open', '1', '/sts/tickets/79', 'view-issued-tickets'),
(32, 78, 'Ticket #79 status have changed into Assigned', '1', '/sts/tickets/79', 'view-issued-tickets'),
(33, 55, 'A new ticket has been added', '1', '/sts/tickets/79', 'view-supported-tickets'),
(34, 61, 'A new ticket has been added', '1', '/sts/tickets/79', 'view-supported-tickets'),
(35, 62, 'A new ticket has been added', '1', '/sts/tickets/79', 'view-supported-tickets'),
(36, 54, 'Ticket #50 status have changed into Assigned', '0', '/sts/tickets/50', 'view-issued-tickets'),
(44, 18, 'Justine Hernandez has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(45, 55, 'Justine Hernandez has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(46, 18, 'Justine Hernandez has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(47, 55, 'Justine Hernandez has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(48, 55, 'A new ticket has been added', '1', '/sts/tickets/50', 'view-supported-tickets'),
(49, 55, 'Your request for reassignment have been approved', '1', '/sts/tickets/50', 'view-supported-tickets'),
(50, 18, 'Rainen Scheenler Manzano has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(51, 55, 'Rainen Scheenler Manzano has requested for reassignment', '1', '/sts/tickets/50', 'view-all-tickets'),
(52, 62, 'A new ticket has been added', '1', '/sts/tickets/50', 'view-supported-tickets'),
(53, 62, 'Your request for reassignment have been approved', '1', '/sts/tickets/50', 'view-supported-tickets'),
(54, 47, 'A new ticket has been added', '1', '/sts/tickets/83', 'view-supported-tickets'),
(55, 18, 'Princess Nicole Nacianceno has requested for reassignment', '1', '/sts/tickets/83', 'view-all-tickets'),
(56, 55, 'Princess Nicole Nacianceno has requested for reassignment', '1', '/sts/tickets/83', 'view-all-tickets'),
(59, 18, 'Morris Bongosia has requested for reassignment', '1', '/sts/tickets/83', 'view-all-tickets'),
(60, 55, 'Morris Bongosia has requested for reassignment', '1', '/sts/tickets/83', 'view-all-tickets'),
(61, 46, 'Your request for reassignment have been approved', '1', '/sts/tickets/83', 'view-supported-tickets'),
(62, 55, 'A new ticket has been added', '1', '/sts/tickets/83', 'view-supported-tickets'),
(63, 57, 'Ticket #83 status have changed into Open', '0', '/sts/tickets/83', 'view-issued-tickets'),
(64, 78, 'Ticket #79 status have changed into Open', '1', '/sts/tickets/79', 'view-issued-tickets'),
(65, 78, 'Ticket #71 status have changed into Resolved', '1', '/sts/tickets/71', 'view-issued-tickets'),
(66, 59, 'Ticket #71 status have changed into Closed', '1', '/sts/tickets/71', 'view-supported-tickets'),
(67, 57, 'Ticket #83 status have changed into Assigned', '0', '/sts/tickets/83', 'view-issued-tickets'),
(68, 57, 'Ticket #83 status have changed into Open', '0', '/sts/tickets/83', 'view-issued-tickets'),
(69, 57, 'Ticket #83 status have changed into Assigned', '0', '/sts/tickets/83', 'view-issued-tickets'),
(70, 57, 'Ticket #83 status have changed into Open', '0', '/sts/tickets/83', 'view-issued-tickets'),
(71, 57, 'Ticket #83 status have changed into Resolved', '0', '/sts/tickets/83', 'view-issued-tickets'),
(72, 55, 'Ticket #83 has been reopened.', '1', '/sts/tickets/83', 'view-supported-tickets'),
(73, 57, 'Ticket #83 status have changed into Resolved', '0', '/sts/tickets/83', 'view-issued-tickets'),
(74, 55, 'Ticket #83 status have changed into Closed', '1', '/sts/tickets/83', 'view-supported-tickets'),
(75, 55, 'Ticket #83 has been reopened.', '1', '/sts/tickets/83', 'view-supported-tickets'),
(76, 57, 'Ticket #83 status have changed into Resolved', '0', '/sts/tickets/83', 'view-issued-tickets'),
(77, 55, 'Ticket #83 status have changed into Closed', '1', '/sts/tickets/83', 'view-supported-tickets'),
(78, 61, 'A new ticket has been added', '0', '/sts/tickets/84', 'view-supported-tickets'),
(79, 18, 'Justine Hernandez has requested for reassignment', '0', '/sts/tickets/84', 'view-all-tickets'),
(80, 55, 'Justine Hernandez has requested for reassignment', '1', '/sts/tickets/84', 'view-all-tickets'),
(81, 61, 'Your request for reassignment have been approved', '0', '/sts/tickets/84', 'view-supported-tickets'),
(82, 62, 'A new ticket has been added', '0', '/sts/tickets/84', 'view-supported-tickets'),
(83, 18, 'Abigail Datu has requested for reassignment', '0', '/sts/tickets/84', 'view-all-tickets'),
(84, 55, 'Abigail Datu has requested for reassignment', '1', '/sts/tickets/84', 'view-all-tickets'),
(85, 62, 'Your request for reassignment have been approved', '0', '/sts/tickets/84', 'view-supported-tickets'),
(86, 55, 'A new ticket has been added', '1', '/sts/tickets/84', 'view-supported-tickets'),
(87, 18, 'Rainen Scheenler Manzano has requested for reassignment', '0', '/sts/tickets/84', 'view-all-tickets'),
(88, 55, 'Rainen Scheenler Manzano has requested for reassignment', '1', '/sts/tickets/84', 'view-all-tickets'),
(89, 55, 'Your request for reassignment have been approved', '1', '/sts/tickets/84', 'view-supported-tickets'),
(90, 62, 'A new ticket has been added', '0', '/sts/tickets/84', 'view-supported-tickets'),
(91, 55, 'A new ticket has been added', '1', '/sts/tickets/85', 'view-supported-tickets'),
(92, 47, 'A new ticket has been added', '0', '/sts/tickets/86', 'view-supported-tickets'),
(93, 82, 'Ticket #85 status have changed into Open', '0', '/sts/tickets/85', 'view-issued-tickets'),
(94, 82, 'Ticket #85 status have changed into Resolved', '1', '/sts/tickets/85', 'view-issued-tickets'),
(95, 55, 'Ticket #85 has been reopened.', '0', '/sts/tickets/85', 'view-supported-tickets'),
(96, 82, 'Ticket #85 status have changed into Resolved', '0', '/sts/tickets/85', 'view-issued-tickets'),
(97, 55, 'Ticket #85 status have changed into Closed', '0', '/sts/tickets/85', 'view-supported-tickets'),
(98, 18, 'Princess Nicole Nacianceno has requested for reassignment', '0', '/sts/tickets/86', 'view-all-tickets'),
(99, 55, 'Princess Nicole Nacianceno has requested for reassignment', '0', '/sts/tickets/86', 'view-all-tickets'),
(100, 47, 'Your request for reassignment have been approved', '0', '/sts/tickets/86', 'view-supported-tickets'),
(101, 55, 'A new ticket has been added', '0', '/sts/tickets/86', 'view-supported-tickets');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `Position_Id` bigint(20) NOT NULL,
  `Position_Name` varchar(50) NOT NULL,
  `Position_Desc` varchar(100) DEFAULT NULL,
  `Department_Id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`Position_Id`, `Position_Name`, `Position_Desc`, `Department_Id`) VALUES
(0, 'Administrator', 'This position is for only the adminstrators of the system', 0),
(1, 'IT Manager', 'Manager', 1),
(4, 'Mid Programmer', 'With 2 yrs experience', 1),
(5, 'Team Leader', 'Leads a group of people', 60),
(6, 'Operations Manager', 'leader for operation ', 71),
(7, 'Quality Control Manager', 'Deals in food products', 70),
(8, 'Accountant', 'Responsible for monthly income statements and balance', 66),
(10, 'Receptionist', 'Handles phone calls, greet visitors, handles mail', 73),
(11, 'Marketing Manager', 'Handle all aspects related to promoting and selling the product', 64),
(12, 'Purchasing Manager', 'Duties of this position may be filled by either or both the general manager', 70),
(13, 'Professional Staff', 'Firm\'s professional staff resources', 65),
(14, 'Junior Programmer', '', 1),
(15, 'Senior Programmer', ' ', 1),
(16, 'Technical Supports', ' ', 1),
(17, 'Finance Manager', 'Manager', 74);

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `Priority_Id` bigint(20) NOT NULL,
  `Level` varchar(5) NOT NULL,
  `Days` varchar(5) NOT NULL,
  `Hours` varchar(5) NOT NULL,
  `Minutes` varchar(5) NOT NULL,
  `Label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`Priority_Id`, `Level`, `Days`, `Hours`, `Minutes`, `Label`) VALUES
(1, '4', '0', '8', '0', 'High'),
(2, '2', '0', '3', '0', 'Low'),
(3, '5', '1', '0', '0', 'Very high'),
(4, '1', '0', '1', '0', 'Very low'),
(5, '3', '0', '4', '0', 'Medium');

-- --------------------------------------------------------

--
-- Table structure for table `support_preferences`
--

CREATE TABLE `support_preferences` (
  `Id` bigint(20) NOT NULL,
  `Support_Id` bigint(20) NOT NULL,
  `Category_Id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support_preferences`
--

INSERT INTO `support_preferences` (`Id`, `Support_Id`, `Category_Id`) VALUES
(26, 55, 13),
(28, 55, 20),
(29, 59, 13),
(30, 60, 13),
(31, 61, 20),
(32, 62, 20),
(35, 46, 14),
(36, 47, 14),
(37, 55, 14),
(38, 61, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `Ticket_Id` bigint(20) NOT NULL,
  `Issue` bigint(20) DEFAULT NULL,
  `Description` text NOT NULL,
  `CreatedBy` bigint(20) NOT NULL,
  `SupportedBy` bigint(20) DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  `DateClosed` datetime DEFAULT NULL,
  `DateModified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Solution` text,
  `Status` varchar(200) NOT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Is_Reassign` char(1) NOT NULL DEFAULT '0',
  `Reassignment_Reason` varchar(100) DEFAULT NULL,
  `Reopen_Reason` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`Ticket_Id`, `Issue`, `Description`, `CreatedBy`, `SupportedBy`, `DateCreated`, `DateClosed`, `DateModified`, `Solution`, `Status`, `Rating`, `Is_Reassign`, `Reassignment_Reason`, `Reopen_Reason`) VALUES
(7, 15, '', 58, 61, '2018-08-24 00:00:00', NULL, '2019-09-24 18:33:23', NULL, 'Open', NULL, '0', NULL, NULL),
(11, 16, '', 56, 62, '2019-08-21 13:18:00', '2019-08-21 17:11:00', '2019-09-25 12:09:36', 'Reset the router', 'Resolved', NULL, '0', NULL, NULL),
(24, 3, '', 45, 59, '2019-08-13 13:30:00', '2019-08-13 20:00:00', '2019-09-25 12:08:26', 'Solution', 'Closed', 3, '0', NULL, NULL),
(29, 2, '', 57, 59, '2019-08-14 10:01:00', NULL, '2019-09-24 18:33:40', NULL, 'Assigned', NULL, '0', NULL, NULL),
(30, 13, '', 56, 46, '2019-08-14 10:01:00', '2019-08-14 12:07:00', '2019-09-25 12:12:07', 'Explained well to the employee', 'Resolved', NULL, '0', NULL, NULL),
(46, 21, 'The wifi icon disappears', 54, 62, '2019-08-22 09:09:00', '2019-08-23 19:21:15', '2019-09-25 12:02:39', 'Disable and enable the device', 'Closed', 4, '0', NULL, NULL),
(47, 1, 'PC suddenly shut down and the computer is making a lot of heat', 54, 55, '2019-08-23 12:00:00', '2019-08-23 13:12:00', '2019-09-25 12:08:34', 'Changed the hard drive', 'Closed', 5, '0', NULL, NULL),
(48, 2, 'USB port not working', 54, 60, '2019-08-21 10:38:00', NULL, '2019-09-25 11:57:45', NULL, 'Assigned', NULL, '0', NULL, NULL),
(50, 15, '', 54, 62, '2019-08-25 11:15:00', NULL, '2019-10-16 07:05:40', NULL, 'Assigned', NULL, '0', 'Needed permission', NULL),
(51, 12, 'PC is slow', 45, 47, '2019-08-22 15:48:00', NULL, '2019-10-16 00:28:45', 'Disable some background applications running', 'Resolved', NULL, '0', NULL, NULL),
(52, 4, 'My computer is making a sound', 56, 59, '2019-08-21 17:14:00', '2019-08-21 19:18:00', '2019-09-25 12:12:26', 'Replaced the hard drive', 'Resolved', NULL, '0', NULL, NULL),
(54, 18, '', 57, 62, '2019-08-23 10:25:00', NULL, '2019-10-16 00:27:47', 'Fixed the wires', 'Resolved', 0, '0', NULL, NULL),
(56, 1, '', 69, 60, '2019-08-25 13:57:55', '2019-08-25 14:25:00', '2019-09-25 12:08:42', NULL, 'Closed', 3, '0', NULL, NULL),
(58, 1, '', 56, 55, '2019-09-30 03:38:00', NULL, '2019-09-30 03:38:00', NULL, 'Assigned', NULL, '0', NULL, NULL),
(61, 3, '', 54, 60, '2019-09-30 03:40:25', NULL, '2019-09-30 03:40:25', NULL, 'Assigned', NULL, '0', NULL, NULL),
(62, 6, '', 45, 59, '2019-09-30 03:40:52', NULL, '2019-10-04 11:42:03', NULL, 'Assigned', NULL, '0', NULL, NULL),
(63, 7, '', 57, 60, '2019-09-30 03:41:27', NULL, '2019-10-04 11:42:44', NULL, 'Assigned', NULL, '0', 'not avail', NULL),
(64, 10, '', 58, 55, '2019-09-30 03:42:00', NULL, '2019-10-16 00:25:08', 'Replaced system unit', 'Resolved', NULL, '0', NULL, NULL),
(65, 9, 'PC making sounds', 54, 59, '2019-10-02 14:09:00', '2019-10-03 23:16:10', '2019-10-03 23:16:10', 'Cleaned the system unit', 'Closed', 4, '0', 'Reassignment Reason', NULL),
(70, 14, 'Microsoft word needs license to work', 54, 55, '2019-10-03 23:20:26', '2019-10-04 02:32:13', '2019-10-04 02:32:13', 'Added license and also checked after restarting the PC', 'Closed', 4, '0', NULL, NULL),
(71, 4, 'My PC makes a beep noise. My PC is also not powering on', 78, 59, '2019-10-04 02:37:48', '2019-10-16 10:31:56', '2019-10-16 10:31:56', 'Cleaned the system unit', 'Closed', 4, '0', NULL, NULL),
(72, 21, 'Cannot connect to the internet', 78, 61, '2019-10-04 02:57:59', '2019-10-04 03:32:29', '2019-10-15 23:45:34', 'Restarted the PC', 'Closed', 4, '0', NULL, NULL),
(79, 19, '', 78, 62, '2019-10-07 17:40:20', NULL, '2019-10-16 10:30:42', NULL, 'Open', NULL, '0', NULL, 'The issue is still there'),
(83, 12, '', 57, 55, '2019-10-16 07:06:58', '2019-10-16 13:10:14', '2019-10-16 13:10:14', 'Added RAM', 'Closed', 3, '0', NULL, NULL),
(84, 20, '', 57, 62, '2019-10-16 13:10:21', NULL, '2019-10-16 13:12:20', NULL, 'Assigned', NULL, '0', 'Should receive permission first', NULL),
(85, 22, 'Burned CD', 82, 55, '2019-10-16 13:44:00', '2019-10-16 13:52:31', '2019-10-16 13:52:31', 'watered', 'Closed', 3, '0', NULL, NULL),
(86, 13, 'Photoshop', 82, 55, '2019-10-16 13:44:37', NULL, '2019-10-16 13:55:01', NULL, 'Assigned', NULL, '0', 'Too many ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trails`
--

CREATE TABLE `trails` (
  `Trail_Id` bigint(20) NOT NULL,
  `Ticket_Id` bigint(20) NOT NULL,
  `Trail_Log` text NOT NULL,
  `Date_Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trails`
--

INSERT INTO `trails` (`Trail_Id`, `Ticket_Id`, `Trail_Log`, `Date_Created`) VALUES
(5, 83, 'Rainen Scheenler Manzano has changed the status into Assigned', '2019-10-16 13:06:59'),
(6, 83, 'Rainen Scheenler Manzano has changed the status into Open', '2019-10-16 13:07:06'),
(7, 83, 'Rainen Scheenler Manzano has changed the status into Resolved', '2019-10-16 13:07:25'),
(8, 83, 'Rainen Scheenler Manzano has changed the status into Resolved', '2019-10-16 13:07:57'),
(9, 83, 'Forrest Saunders has changed the status into Closed', '2019-10-16 13:08:07'),
(10, 83, 'Forrest Saunders has changed the status into Open', '2019-10-16 13:09:54'),
(11, 83, 'Rainen Scheenler Manzano has changed the status into Resolved', '2019-10-16 13:10:04'),
(12, 83, 'Forrest Saunders has changed the status into Closed', '2019-10-16 13:10:14'),
(13, 84, 'Forrest Saunders has added the ticket', '2019-10-16 13:10:21'),
(14, 84, 'Justine Hernandez request has been approved', '2019-10-16 13:11:25'),
(15, 84, 'Ticket has been transferred to Abigail Datu', '2019-10-16 13:11:26'),
(16, 84, 'Abigail Datu request has been approved', '2019-10-16 13:12:03'),
(17, 84, 'Ticket has been transferred to Rainen Scheenler Manzano', '2019-10-16 13:12:04'),
(18, 84, 'Rainen Scheenler Manzano request has been approved', '2019-10-16 13:12:20'),
(19, 84, 'Ticket has been transferred to Abigail Datu', '2019-10-16 13:12:20'),
(20, 85, 'John Dustin Santos has added the ticket', '2019-10-16 13:44:01'),
(21, 86, 'John Dustin Santos has added the ticket', '2019-10-16 13:44:38'),
(22, 85, 'Rainen Scheenler Manzano has changed the status into Open', '2019-10-16 13:49:13'),
(23, 85, 'Rainen Scheenler Manzano has changed the status into Resolved', '2019-10-16 13:51:16'),
(24, 85, 'John Dustin Santos has changed the status into Open', '2019-10-16 13:51:58'),
(25, 85, 'Rainen Scheenler Manzano has changed the status into Resolved', '2019-10-16 13:52:12'),
(26, 85, 'John Dustin Santos has changed the status into Closed', '2019-10-16 13:52:31'),
(27, 86, 'Princess Nicole Nacianceno request has been approved', '2019-10-16 13:55:01'),
(28, 86, 'Ticket has been transferred to Rainen Scheenler Manzano', '2019-10-16 13:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_Id` bigint(20) NOT NULL,
  `Employee_Id` varchar(30) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Firstname` varchar(50) DEFAULT NULL,
  `Middlename` varchar(50) NOT NULL,
  `Department_Id` bigint(20) NOT NULL,
  `Position_Id` bigint(20) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Pwd` varchar(30) NOT NULL,
  `Company_Email` varchar(50) NOT NULL,
  `Role` char(1) DEFAULT NULL,
  `Img_Name` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_Id`, `Employee_Id`, `Lastname`, `Firstname`, `Middlename`, `Department_Id`, `Position_Id`, `Username`, `Pwd`, `Company_Email`, `Role`, `Img_Name`) VALUES
(18, '000-000-000', ' Admin', ' ', ' ', 0, 0, 'admin', 'admin', '', '0', '800px_COLOURBOX250667841570953657264.jpg'),
(45, '842-854-284', 'Boyer', 'Pascale', 'Fitzgerald Pittman', 73, 12, 'pascale', 'pascale', 'qicodib@mailinator.net', '3', '220px-Pierre-Person1569754743439.jpg'),
(46, '492-582-396', 'Bongosia', 'Morris', '', 1, 4, 'franz', 'franz', 'pinekecah@mailinator.com', '2', '67388124_2350701451687669_6010133769506258944_n1569754160771.jpg'),
(47, '816-380-372', 'Nacianceno', 'Princess Nicole', 'Domingo', 1, 4, 'nicole', 'nicole', 'hotyne@mailinator.com', '2', '69334502_2756069587787178_6348273890430550016_n1569754475040.jpg'),
(54, '395-328-143', 'Hamilton', 'Britanney', 'Lars', 60, 5, 'britanney', 'britanney', 'buve@mailinator.net', '3', 'screen-shot-2019-02-19-at-40014-pmpng1569754760307.jpg'),
(55, '847-285-184', 'Manzano', 'Rainen Scheenler', 'Delica', 1, 1, 'rainenmanzano', 'rainenmanzano019', 'rainenmano@gmail.com', '1', '30725224_1919625318056231_6142428451669278720_n1569754532118.jpg'),
(56, '831-385-174', 'Knight', 'Jackie', 'Curran Austin', 65, 13, 'jackie', 'jackie', 'keho@mailinator.com', '3', '1_gBQxShAkxBp_YPb14CN0Nw1569754832660.jpeg'),
(57, '183-850-283', 'Saunders', 'Forrest', 'Karyn Rodgers', 73, 10, 'forrest', 'forrest', 'fytoj@mailinator.net', '3', 'Krystal_Jung_at_Jeju_K-Pop_Festival,_in_October_20151569754885512.jpg'),
(58, '732-184-482', 'Howard', 'Teagan', 'Jenna Lott', 66, 8, 'teagan', 'teagan', 'qovuva@mailinator.net', '3', 'GettyImages-8010640201569754939626.jpg'),
(59, '837-495-394', 'La madrid', 'Kevin', '', 1, 15, 'kevin', 'kevin', 'kevinlamadrid@gmail.com', '2', '61720878_1299264286888100_1870570794141089792_n1569754575759.jpg'),
(60, '823-284-394', 'Clacevillas', 'Kim Bryan', '', 1, 16, 'kim', 'kim', 'kim@gmail.com', '2', '59661021_2761569030581654_6336547929632800768_n1569754611923.jpg'),
(61, '934-273-184', 'Hernandez', 'Justine', 'Borja', 1, 16, 'justine', 'justine', 'justinehernandez@gmail.com', '2', '39142040_1027045930783197_2919774147129114624_n1569754659949.jpg'),
(62, '193-128-482', 'Datu', 'Abigail', 'Vargas', 1, 14, 'abigail', 'abigail', 'abigaildatu@gmail.com', '2', '69973574_2450469301698956_4785258613365014528_n1569754696929.jpg'),
(78, '174-372-683', 'Manzano', 'Mylen', 'Delica', 73, 10, 'mylen', 'mylen', 'sorisaheze@mailinator.net', '3', 'WP_20140126_08_18_58_Pro1390970175000.jpg'),
(79, 'sample', 'Shouyo', 'Hinata', '', 64, 11, 'sample', 'sample', 'sample@gmail.com', '3', 'hinatashouyou1571020774613.jpg'),
(82, '821-423', 'Santos', 'John Dustin', 'null', 71, 6, 'dustin', 'dustin', 'dustchin@gmail.com', '3', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation`
--
ALTER TABLE `activation`
  ADD PRIMARY KEY (`Activation_Id`),
  ADD KEY `fk_activation_id` (`User_Id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Category_Id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_Id`),
  ADD KEY `comment_user` (`User_Id`),
  ADD KEY `comment_ticket` (`Ticket_Id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`Department_Id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`Issue_Id`),
  ADD KEY `fk_issues_category` (`Category_Id`),
  ADD KEY `fk_issues_priority` (`Priority_Id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`Message_Id`),
  ADD KEY `fk_from` (`From_User`),
  ADD KEY `fk_to` (`To_User`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`Notification_Id`),
  ADD KEY `fk_notification` (`User_To_Notify`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`Position_Id`),
  ADD KEY `fk_department` (`Department_Id`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`Priority_Id`);

--
-- Indexes for table `support_preferences`
--
ALTER TABLE `support_preferences`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_support` (`Support_Id`),
  ADD KEY `fk_category` (`Category_Id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`Ticket_Id`),
  ADD KEY `fk_createdBy` (`CreatedBy`),
  ADD KEY `fk_supportedBy` (`SupportedBy`),
  ADD KEY `fk_ticket_issue` (`Issue`);

--
-- Indexes for table `trails`
--
ALTER TABLE `trails`
  ADD PRIMARY KEY (`Trail_Id`),
  ADD KEY `fkey_trail` (`Ticket_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_Id`),
  ADD KEY `fk_departments` (`Department_Id`),
  ADD KEY `Employee_Id` (`Employee_Id`),
  ADD KEY `fk_position` (`Position_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activation`
--
ALTER TABLE `activation`
  MODIFY `Activation_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Category_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `Department_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `Issue_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `Message_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `Notification_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `Position_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `Priority_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_preferences`
--
ALTER TABLE `support_preferences`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `Ticket_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `trails`
--
ALTER TABLE `trails`
  MODIFY `Trail_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activation`
--
ALTER TABLE `activation`
  ADD CONSTRAINT `fk_activation_id` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_ticket` FOREIGN KEY (`Ticket_Id`) REFERENCES `tickets` (`Ticket_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `fk_issues_category` FOREIGN KEY (`Category_Id`) REFERENCES `categories` (`Category_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_issues_priority` FOREIGN KEY (`Priority_Id`) REFERENCES `priorities` (`Priority_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_from` FOREIGN KEY (`From_User`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_to` FOREIGN KEY (`To_User`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification` FOREIGN KEY (`User_To_Notify`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`Department_Id`) REFERENCES `departments` (`Department_Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `support_preferences`
--
ALTER TABLE `support_preferences`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`Category_Id`) REFERENCES `categories` (`Category_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_support` FOREIGN KEY (`Support_Id`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_supported` FOREIGN KEY (`SupportedBy`) REFERENCES `users` (`User_Id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticket_issue` FOREIGN KEY (`Issue`) REFERENCES `issues` (`Issue_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trails`
--
ALTER TABLE `trails`
  ADD CONSTRAINT `fkey_trail` FOREIGN KEY (`Ticket_Id`) REFERENCES `tickets` (`Ticket_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_position` FOREIGN KEY (`Position_Id`) REFERENCES `positions` (`Position_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Position_Id`) REFERENCES `positions` (`Position_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
