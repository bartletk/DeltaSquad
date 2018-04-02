-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2018 at 03:46 PM
-- Server version: 5.6.39
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nursing`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(6) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `sub_of` int(6) UNSIGNED NOT NULL DEFAULT '1',
  `sequence` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `restricted` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `description` text,
  `color` varchar(30) DEFAULT NULL,
  `background` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `sub_of`, `sequence`, `restricted`, `description`, `color`, `background`) VALUES
(1, 'Nursing Building', 0, 1, 0, 'Top Level Category', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_number` smallint(4) NOT NULL,
  `prefix` varchar(4) NOT NULL DEFAULT 'NURS',
  `title` varchar(50) NOT NULL,
  `semester` enum('1','2','3','4','5') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_number`, `prefix`, `title`, `semester`) VALUES
(1001, 'NURS', '(TestData) SQL Insertion in Seniors ', '1'),
(2000, 'NURS', 'NURSING CONCEPTS', '2'),
(2001, 'NURS', '(TestData) Testing for warts', '2'),
(2002, 'NURS', 'TRANSITIONS IN NURSING', '2'),
(2004, 'NURS', 'HEALTH ASSESSMENT', '2'),
(2005, 'NURS', '(TestData) Itching an Itch', '2'),
(2009, 'NURS', 'FUND PROF NURSING PRACTICE', '2'),
(2011, 'NURS', 'INTRO GERONTOLOGICAL NURSING', '2'),
(2013, 'NURS', 'COMPUTING FOR NURSES', '2'),
(2020, 'NURS', 'PROFESSIONAL NURSING CONCEPTS', '2'),
(2080, 'NURS', 'BASIC PRIN OF PHARMACOLOGY', '2'),
(3001, 'NURS', '(TestData) SQL Insertion in Minors', '3'),
(3009, 'NURS', 'ADULT HEALTH NURSING 1', '3'),
(3010, 'NURS', 'MENTAL HEALTH NURSING', '3'),
(3011, 'NURS', 'NURSING SYNTHESIS 1', '3'),
(3012, 'NURS', 'ADULT HEALTH 1 THEORY', '3'),
(3013, 'NURS', 'ADULT HEALTH 1 PRACTICUM A', '3'),
(3014, 'NURS', 'ADULT HEALTH 1 PRACTICUM B', '3'),
(3020, 'NURS', '(TestData) Covering it up with Newspaper', '3'),
(3028, 'NURS', 'ADULT HEALTH NURSING 2', '3'),
(3029, 'NURS', 'MATERNAL CHILD HEALTH NURSING', '3'),
(3030, 'NURS', 'NURSING SYNTHESIS 2', '3'),
(4002, 'NURS', 'NURSING SYNTHESIS 3', '4'),
(4020, 'NURS', '(TestData) Why Am I Doing This', '4'),
(4026, 'NURS', 'RESEARCH', '4'),
(4066, 'NURS', 'NURSING MANAGEMENT', '4'),
(4067, 'NURS', 'PUBLIC HEALTH NURSING', '4'),
(4076, 'NURS', 'NURSING MANAGEMENT RN', '4'),
(4077, 'NURS', 'NURSING MANAGEMENT RN PRACTICUM', '4'),
(4078, 'NURS', 'PUBLIC HEALTH NURSING RN', '4'),
(4079, 'NURS', 'PUBLIC HEALTH NURSING RN PRAC', '4'),
(5002, 'NURS', 'RESEARCH EBP', '5'),
(5004, 'NURS', 'PERSONNEL & ORG MGMT', '5'),
(5007, 'NURS', 'ADV PHYSICAL ASSESSMENT', '5'),
(5008, 'NURS', 'ADVANCED PHARMACOLOGY', '5'),
(5009, 'NURS', 'HC ECONOMIC & FINANCE', '5'),
(5202, 'NURS', 'AGNP I', '5'),
(5999, 'NURS', '(TestData) Holy Snail I Am Done!', '5');

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE `dates` (
  `event_id` int(8) UNSIGNED DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deadline`
--

CREATE TABLE `deadline` (
  `id` int(11) NOT NULL,
  `open` date NOT NULL,
  `close` date NOT NULL,
  `type` enum('schedule','semester') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deadline`
--

INSERT INTO `deadline` (`id`, `open`, `close`, `type`) VALUES
(1, '2017-12-18', '2018-01-21', 'schedule'),
(13, '2018-01-22', '2018-05-18', 'semester');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `attendees` int(11) NOT NULL DEFAULT '1',
  `type` enum('class','clinical','exam','event') NOT NULL DEFAULT 'class',
  `crn` int(6) NOT NULL,
  `CWID` int(9) NOT NULL,
  `room_number` varchar(32) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `series` smallint(6) NOT NULL,
  `dateStart` datetime NOT NULL,
  `dateEnd` datetime NOT NULL,
  `timeCreated` datetime NOT NULL,
  `status` enum('approved','pending','rejected','changed','resubmit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `title`, `attendees`, `type`, `crn`, `CWID`, `room_number`, `notes`, `series`, `dateStart`, `dateEnd`, `timeCreated`, `status`) VALUES
(20, 'Sample Event 1', 1, 'exam', 43672, 222222222, '2', 'This one\'s a doozy', 1, '2018-03-15 13:00:00', '2018-03-15 13:00:00', '2018-03-03 12:02:28', 'approved'),
(21, 'test', 1, 'exam', 43672, 222222222, '2', 'I just want this to work so I can go to sleep', 1, '2018-03-19 12:00:00', '2018-03-19 14:00:00', '2018-03-03 12:42:37', 'approved'),
(22, 'test', 1, 'event', 43672, 222222222, '2', 'None to be stated', 2, '2018-03-09 12:39:00', '2018-03-09 17:30:00', '2018-03-03 14:41:23', 'approved'),
(23, 'LRC', 1, 'class', 43672, 222222222, '2', 'None to be stated', 3, '2018-03-06 12:30:00', '2018-03-06 16:15:00', '2018-03-03 15:05:34', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(6) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `sub_of` int(6) UNSIGNED NOT NULL DEFAULT '1',
  `sequence` int(2) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `sub_of`, `sequence`) VALUES
(1, 'Nursing Building', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `link_id` int(6) UNSIGNED NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `address1` varchar(40) DEFAULT NULL,
  `address2` varchar(40) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`link_id`, `company`, `address1`, `address2`, `city`, `state`, `zip`, `phone`, `fax`, `email`, `url`, `contact`, `description`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UserTo` tinytext NOT NULL,
  `UserFrom` tinytext NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `status` text NOT NULL,
  `SentDate` text NOT NULL,
  `mail_id` int(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mail`
--

INSERT INTO `mail` (`Deleted`, `UserTo`, `UserFrom`, `Subject`, `Message`, `status`, `SentDate`, `mail_id`) VALUES
(0, 'admin', 'admin', 'test', 'test', 'unread', '02/26/2018 at 10:32.01 am', 10);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(6) UNSIGNED NOT NULL,
  `link_name` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `active` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sequence` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `script` varchar(60) DEFAULT NULL,
  `year` int(2) UNSIGNED DEFAULT NULL,
  `month` int(2) UNSIGNED DEFAULT NULL,
  `week` int(2) UNSIGNED DEFAULT NULL,
  `day` int(2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `link_name`, `name`, `active`, `sequence`, `script`, `year`, `month`, `week`, `day`) VALUES
(1, 'Year', 'Year', 1, 1, 'year.php', 0, 2, 3, 4),
(2, 'Month', 'Month', 1, 2, 'grid.php', 0, 2, 3, 4),
(3, 'Week', 'Week', 1, 3, 'week.php', 0, 2, 3, 4),
(4, 'Day', 'Day', 1, 4, 'day.php', 0, 2, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `personal_schedule`
--

CREATE TABLE `personal_schedule` (
  `CWID` int(9) NOT NULL,
  `crn` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `capacity` smallint(6) NOT NULL,
  `room_number` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`capacity`, `room_number`, `description`) VALUES
(400, '101', 'Highest Room, Tallest Tower'),
(314, '159', 'Pi'),
(0, '2', ' '),
(30, '30', 'Non-Descript'),
(1, '42', 'BabyPopOutStation'),
(32767, 'Offsite', 'Any offsite location. Please include details in notes.');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `crn` int(6) NOT NULL,
  `course_number` smallint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`crn`, `course_number`) VALUES
(43935, 1001),
(43922, 2002),
(61752, 2002),
(43928, 2004),
(43929, 2004),
(43930, 2004),
(43931, 2004),
(43932, 2004),
(44187, 2004),
(60978, 2004),
(63659, 2004),
(63660, 2004),
(63662, 2004),
(63663, 2004),
(63665, 2004),
(63667, 2004),
(43672, 2009),
(43673, 2009),
(43675, 2009),
(43677, 2009),
(43933, 2009),
(43934, 2009),
(63458, 2009),
(63459, 2009),
(63460, 2009),
(63461, 2009),
(63462, 2009),
(63668, 2009),
(63669, 2009),
(43678, 2011),
(43679, 2011),
(43923, 2011),
(63479, 2011),
(63480, 2011),
(63481, 2011),
(63673, 2011);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) CHARACTER SET latin2 NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL,
  `valid` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `hash_generated` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `CWID` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `user_id`, `userlevel`, `email`, `timestamp`, `valid`, `name`, `hash`, `hash_generated`, `id`, `CWID`) VALUES
('admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'a86a22340ef0a3bde06326c9ab2048e0', 9, 'admin@admin.com', 1519605786, 0, 'admin', '2134e8c9a9244993583f06d4dc7c426d', 1520176226, 1, 111111111),
('teacher1', '5f4dcc3b5aa765d61d8327deb882cf99', '67276c639d572e3657db3a4c2591512e', 5, 'teacher1@teacher.com', 1519693251, 0, 'tea cher', '4d04152b922aee38b6b4aabe88a252b1', 1520033048, 2, 0),
('TestUser', '5f4dcc3b5aa765d61d8327deb882cf99', 'b0084076b1ecf9353f2a7ca63bb87e4d', 1, 'testuser@test.com', 1520118690, 0, 'TestUser', '0', 0, 3, 222222222),
('sched', '5f4dcc3b5aa765d61d8327deb882cf99', 'df04bf5b61151fbbc0a5443fb33f12da', 1, 'sched@sched.com', 1520379929, 0, 'Schedule Notifications', '0', 0, 4, 1111);

-- --------------------------------------------------------

--
-- Table structure for table `users_to_categories`
--

CREATE TABLE `users_to_categories` (
  `user_id` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `moderate` int(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_to_categories`
--

INSERT INTO `users_to_categories` (`user_id`, `category_id`, `moderate`, `id`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_to_groups`
--

CREATE TABLE `users_to_groups` (
  `user_id` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `group_id` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `moderate` int(1) NOT NULL DEFAULT '0',
  `subscribe` int(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_to_groups`
--

INSERT INTO `users_to_groups` (`user_id`, `group_id`, `moderate`, `subscribe`, `id`) VALUES
(1, 1, 1, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_number`);

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deadline`
--
ALTER TABLE `deadline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `crn` (`crn`),
  ADD KEY `room_number` (`room_number`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `personal_schedule`
--
ALTER TABLE `personal_schedule`
  ADD PRIMARY KEY (`CWID`,`crn`),
  ADD KEY `crn` (`crn`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_number`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`crn`),
  ADD KEY `course_number` (`course_number`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `CWID` (`CWID`);

--
-- Indexes for table `users_to_categories`
--
ALTER TABLE `users_to_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_to_groups`
--
ALTER TABLE `users_to_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deadline`
--
ALTER TABLE `deadline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `link_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
  MODIFY `mail_id` int(80) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_to_categories`
--
ALTER TABLE `users_to_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_to_groups`
--
ALTER TABLE `users_to_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `section` (`crn`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`room_number`) REFERENCES `room` (`room_number`);

--
-- Constraints for table `personal_schedule`
--
ALTER TABLE `personal_schedule`
  ADD CONSTRAINT `personal_schedule_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `event` (`crn`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course_number`) REFERENCES `course` (`course_number`),
  ADD CONSTRAINT `section_ibfk_2` FOREIGN KEY (`course_number`) REFERENCES `course` (`course_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
