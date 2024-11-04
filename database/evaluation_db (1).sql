-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2024 at 04:30 AM
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
-- Database: `evaluation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` int(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `status`) VALUES
(1, '2019-2020', 1, 0, 0),
(2, '2019-2020', 2, 0, 0),
(3, '2020-2021', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL,
  `class_code` varchar(10) NOT NULL,
  `teacher_id` int(10) NOT NULL,
  `subject_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`, `class_code`, `teacher_id`, `subject_id`) VALUES
(1, 'BSIT', '1', 'A', '', 2, 2),
(2, 'BSIT', '1', 'B', '', 2, 2),
(3, 'BSIT', '1', 'C', 'ewhrgejwr', 1, 2),
(4, 'BSIT', '1', 'D', 'a2a9a696', 13, 2),
(5, 'BSCRIM', '1', 'C', '8d035e48', 13, 2),
(6, 'BSIT', '3', 'D', '23f468c6', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`) VALUES
(5, 'Category 1: Teaching Effectiveness', 0),
(7, 'Category 2: Professionalism and Classroom Management', 1);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`) VALUES
(1, 1, 5),
(1, 6, 4),
(1, 3, 5),
(2, 1, 5),
(2, 6, 5),
(2, 3, 4),
(3, 1, 5),
(3, 6, 5),
(3, 3, 4),
(4, 1, 5),
(4, 6, 5),
(4, 7, 5),
(5, 17, 5),
(5, 16, 5),
(6, 17, 5),
(6, 16, 5),
(7, 17, 5),
(7, 16, 5),
(8, 17, 5),
(8, 16, 5),
(9, 17, 5),
(9, 16, 5),
(10, 17, 5),
(10, 16, 5),
(11, 17, 5),
(11, 16, 5),
(12, 17, 4),
(12, 16, 3),
(13, 17, 4),
(13, 18, 4),
(13, 19, 4),
(13, 20, 4),
(13, 21, 4),
(13, 22, 5),
(13, 23, 5),
(13, 24, 5),
(13, 25, 5),
(13, 26, 5),
(13, 16, 5),
(13, 27, 5),
(13, 28, 5),
(13, 29, 4),
(13, 30, 4),
(13, 31, 5),
(13, 32, 4),
(13, 33, 5),
(13, 34, 4),
(13, 35, 4),
(14, 36, 4),
(14, 37, 5),
(15, 17, 4),
(15, 18, 2),
(15, 19, 2),
(15, 20, 5),
(15, 21, 5),
(15, 22, 3),
(15, 23, 3),
(15, 24, 5),
(15, 25, 5),
(15, 26, 5),
(15, 16, 5),
(15, 27, 5),
(15, 28, 3),
(15, 29, 5),
(15, 30, 5),
(15, 31, 3),
(15, 32, 5),
(15, 33, 3),
(15, 34, 5),
(15, 35, 5),
(16, 17, 4),
(16, 18, 4),
(16, 19, 4),
(16, 20, 5),
(16, 21, 5),
(16, 22, 5),
(16, 23, 5),
(16, 24, 5),
(16, 25, 4),
(16, 26, 5),
(16, 16, 5),
(16, 27, 4),
(16, 28, 5),
(16, 29, 5),
(16, 30, 4),
(16, 31, 5),
(16, 32, 4),
(16, 33, 4),
(16, 34, 5),
(16, 35, 4),
(17, 17, 5),
(17, 18, 4),
(17, 19, 5),
(17, 20, 5),
(17, 21, 5),
(17, 22, 4),
(17, 23, 5),
(17, 24, 4),
(17, 25, 5),
(17, 26, 5),
(17, 16, 5),
(17, 27, 5),
(17, 28, 5),
(17, 29, 5),
(17, 30, 5),
(17, 31, 5),
(17, 32, 5),
(17, 33, 5),
(17, 34, 5),
(17, 35, 5),
(18, 17, 5),
(18, 18, 5),
(18, 19, 5),
(18, 20, 5),
(18, 21, 5),
(18, 22, 5),
(18, 23, 5),
(18, 24, 5),
(18, 25, 5),
(18, 26, 5),
(18, 16, 5),
(18, 27, 5),
(18, 28, 5),
(18, 29, 5),
(18, 30, 5),
(18, 31, 5),
(18, 32, 5),
(18, 33, 5),
(18, 34, 5),
(18, 35, 5),
(0, 17, 5),
(0, 18, 5),
(0, 19, 5),
(0, 20, 5),
(0, 21, 5),
(0, 22, 5),
(0, 23, 5),
(0, 24, 5),
(0, 25, 5),
(0, 26, 5),
(0, 16, 5),
(0, 27, 5),
(0, 28, 5),
(0, 29, 5),
(0, 30, 5),
(0, 31, 5),
(0, 32, 5),
(0, 33, 5),
(0, 34, 5),
(0, 35, 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(0, 3, 6, 3, 3, 1, 47, '2024-10-29 11:25:17'),
(7, 3, 1, 4, 2, 2, 46, '2024-10-21 15:15:36'),
(8, 3, 1, 4, 1, 2, 44, '2024-10-21 15:15:40'),
(9, 3, 1, 4, 1, 13, 47, '2024-10-21 17:15:40'),
(10, 3, 1, 4, 2, 1, 48, '2024-10-21 22:24:18'),
(12, 3, 1, 87, 1, 1, 48, '2024-10-21 22:35:06'),
(13, 3, 1, 4, 2, 3, 49, '2024-10-22 12:53:14'),
(14, 2, 1, 87, 2, 3, 50, '2024-10-22 16:40:03'),
(15, 3, 1, 87, 1, 2, 46, '2024-10-23 11:36:50'),
(17, 3, 1, 5, 1, 2, 46, '2024-10-23 12:23:21'),
(18, 3, 1, 5, 1, 2, 44, '2024-10-23 12:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT '\'\\\'\\\\\\\'\\\\\\\\\\\\\\\'no-image-available.png\\\\\\\\\\\\\\\'\\\\\\\'\\\'\'',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `position` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`, `position`) VALUES
(1, '20140623', 'George', 'Wilson', 'gwilson@sample.com', '200820e3227815ed1756a6b531e7e0d2', '1608011100_avatar.jpg', '2020-12-15 13:45:18', 'Instructor'),
(2, '111942434', 'John', 'Ernest', 'ernest@gmail.com', '200820e3227815ed1756a6b531e7e0d2', '1729778340_GX3qegkWUAATr1p.jpg', '2024-08-14 20:19:27', 'Instructor'),
(3, '24234324', 'henry', 'Sy', 'henrySy@gmail.com', '200820e3227815ed1756a6b531e7e0d2', '1729771080_M4K8IISIS1e5t5yDMDextg - Copy.webp', '2024-08-23 11:17:31', 'Program Ch'),
(12, '12345', 'John', 'Doe', 'john@example.com', '200820e3227815ed1756a6b531e7e0d2', 'no-image-available.png', '2024-10-21 12:45:25', 'Instructor'),
(13, '12346', 'Jane', 'Smith', 'jane@example.com', '200820e3227815ed1756a6b531e7e0d2', '1729778340_th.jfif', '2024-10-21 12:45:25', 'Instructor');

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`, `staff_id`) VALUES
(1, 3, 'Sample Question', 0, 1, 0),
(5, 0, 'Question 101', 0, 1, 0),
(6, 3, 'Sample 101', 1, 1, 0),
(8, 3, '324234', 3, 2, 0),
(10, 3, '213214', 4, 2, 0),
(13, 3, '213213', 5, 2, 0),
(14, 3, 'gdfd', 2, 1, 0),
(15, 3, 'wqeqwe', 6, 1, 0),
(16, 3, 'Is the instructor punctual for class sessions?', 10, 7, 0),
(17, 3, 'How well does the instructor explain complex concepts?', 0, 5, 0),
(18, 3, 'Does the instructor make the course material engaging?', 1, 5, 0),
(19, 3, 'How effectively does the instructor use examples and illustrations?', 2, 5, 0),
(20, 3, 'Is the instructor approachable for questions and assistance?', 3, 5, 0),
(21, 3, 'How well does the instructor encourage student participation?', 4, 5, 0),
(22, 3, 'Does the instructor provide timely feedback on assignments and exams?', 5, 5, 0),
(23, 3, 'How clearly does the instructor outline the course objectives?', 6, 5, 0),
(24, 3, 'Does the instructor manage classroom time effectively?', 7, 5, 0),
(25, 3, 'How well does the instructor demonstrate knowledge of the subject matter?', 8, 5, 0),
(26, 3, 'Does the instructor encourage critical thinking and problem-solving?', 9, 5, 0),
(27, 3, 'Does the instructor treat students with respect and fairness?', 11, 7, 0),
(28, 3, 'How well does the instructor manage disruptions in the classroom?', 12, 7, 0),
(29, 3, 'Does the instructor create an inclusive and welcoming environment?', 13, 7, 0),
(30, 3, 'How effectively does the instructor handle student concerns and complaints?', 14, 7, 0),
(31, 3, 'Does the instructor exhibit enthusiasm and passion for teaching?', 15, 7, 0),
(32, 3, 'Is the instructor organized in presenting lectures and materials?', 16, 7, 0),
(33, 3, 'Does the instructor maintain a professional demeanor at all times?', 17, 7, 0),
(34, 3, 'How well does the instructor adapt to different learning styles?', 18, 7, 0),
(35, 3, 'Does the instructor communicate course policies and expectations clearly?', 19, 7, 0),
(36, 2, 'wqrewqrer', 0, 5, 0),
(37, 2, 'erewrewr', 1, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`) VALUES
(1, 3, 2, 2, 2),
(3, 3, 13, 5, 2),
(45, 3, 2, 1, 2),
(46, 3, 13, 4, 2),
(47, 3, 1, 6, 3),
(48, 3, 1, 3, 3),
(49, 3, 1, 6, 2),
(50, 3, 1, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `staff_list`
--

CREATE TABLE `staff_list` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_list`
--

INSERT INTO `staff_list` (`id`, `staff_id`, `firstname`, `lastname`, `avatar`, `email`, `password`, `created_at`, `updated_at`) VALUES
(2, '3242352345', 'Luka', 'Doncic', 'staff-1724116806.png', 'lukaDoncic@gmail.com', '$2y$10$xlHphrco6.TznqtWIVef7O0HD9.RE1RJmmjbc10WPel0t06MkNH22', '2024-08-20 01:20:06', '2024-08-20 01:20:06'),
(3, '214324345643', 'Kyrie ', 'Irving', 'staff-1724161513.jfif', 'kyrieIrve@gmail.com', '$2y$10$yF44adflYZsDPhJdBfD63.L0dkGjNydqLESvzbN32TrYNfQH.QMYC', '2024-08-20 13:45:13', '2024-08-20 13:45:13'),
(4, '1324325412', 'Lebron', 'James', 'staff-1724382854.jfif', 'lebron@gmail.com', '$2y$10$WhRi0eMiqN45p2QG18WWGeIaLCxrY0jTntmj/pxR4CddiFRGOW5me', '2024-08-23 03:14:14', '2024-08-23 03:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `avatar`, `date_created`, `status`) VALUES
(1, '202101', 'John', 'Doe', 'john.doe@example.com', '816b09aa255516ec745de7b215e2e158', 6, 'no-image-available.png', '2024-10-29 11:05:00', ''),
(2, '202102', 'Jane', 'Smith', 'jane.smith@example.com', '6cb75f652a9b52798eb6cf2201057c73', 6, 'no-image-available.png', '2024-10-29 11:05:00', ''),
(3, '202103', 'Doe', 'Johnson', 'doe.johnson@example.com', '200820e3227815ed1756a6b531e7e0d2', 6, 'no-image-available.png', '2024-10-29 11:05:00', 'Active'),
(4, '202101', 'John bayron', 'Duterte', 'johern.doe@example.com', '7c6a180b36896a0a8c02787eeafb0e4c', 6, 'no-image-available.png', '2024-10-29 11:06:22', ''),
(5, '202102', 'Jonas', 'Smotret', 'rewr@gmail.com', '6cb75f652a9b52798eb6cf2201057c73', 6, 'no-image-available.png', '2024-10-29 11:06:22', ''),
(6, '202103', 'econas', 'Joleron', 'doelsd.johnson@example.com', '819b0643d6b89dc9b579fdfc9094f28e', 6, 'no-image-available.png', '2024-10-29 11:06:22', '');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(0, 'MATH-204', 'GENMATH', 'GENMATH'),
(1, 'SCC-PF201-IT2A', 'OBJECT-ORIENTED PROGRAMMING 1', 'OBJECT-ORIENTED PROGRAMMING 1'),
(2, 'ENG-101', 'English', 'English'),
(3, 'CC-102', 'COMPUTER PROGRAMMING 1', 'COMPUTER PROGRAMMING 1');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Faculty Evaluation System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', '1607135820_avatar.jpg', '2020-11-26 10:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_list`
--
ALTER TABLE `staff_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
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
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
