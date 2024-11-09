-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 03:20 PM
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
  `teacher_id` varchar(10) NOT NULL,
  `subject_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`, `class_code`, `teacher_id`, `subject_id`) VALUES
(1, 'BSIT', '1', 'A', '', '2', '2'),
(2, 'BSIT', '1', 'B', '', '2', '2'),
(6, 'BSIT', '3', 'D', '23f468c6', '1', '3'),
(10, 'BSCRIM', '2', 'D', 'VKfNuQaW', '2', '1,3'),
(11, 'BEED', '1', 'D', 'qto2azGY', '2,13', '1,3'),
(13, 'BSBA', '4', 'A', 'Z8vwg93A', '2', '1,3,4'),
(14, 'BSIT', '4', 'C', '2ghivwbl', '12,2', '1,4,5');

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
  `status` varchar(10) NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, '111942434', 'John', 'Ernest', 'ernest@gmail.com', '200820e3227815ed1756a6b531e7e0d2', '1729778340_GX3qegkWUAATr1p.jpg', '2024-08-14 20:19:27', 'Instructor'),
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
(47, 3, 1, 6, 3),
(48, 3, 1, 3, 3),
(49, 3, 1, 6, 2),
(50, 3, 1, 3, 2),
(55, 3, 3, 7, 0),
(56, 3, 2, 13, 4),
(57, 3, 2, 13, 3),
(58, 3, 2, 13, 1),
(59, 3, 2, 14, 1),
(60, 3, 2, 14, 3),
(61, 3, 12, 14, 5);

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
(19, '45661191', 'Michael', 'Davis', 'michael.davis@example.com', '61937f272034e83c0e80c1cb9f35f7ec', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'Active'),
(21, '22513371', 'Michael', 'Johnson', 'michael.johnson@example.com', '63965404e168c23a0723af1ce84c5d32', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'Active'),
(22, '58865211', 'Emily', 'Johnson', 'emily.johnson@mail.com', '170e27760078ebb175f8e9bfeb9e7c01', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'active'),
(23, '38490038', 'Sarah', 'Martinez', 'sarah.martinez@mail.com', '5875185af781ee32312304bfe011c8d2', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'active'),
(24, '69397243', 'Michael', 'Johnson', 'michael.johnson@mail.com', 'c2bb8041131359eb7ef3cd4ad1c4f1cd', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'active'),
(25, '31595635', 'Jane', 'Brown', 'jane.brown@example.com', '51f7fb9e8aafc81d79a33127305eea33', 13, 'no-image-available.png', '2024-11-06 20:47:55', 'active'),
(50, '45661191', 'Gian', 'Recana', 'michael.davis@example.com', '6ab0ef0ef5c3148cb7cdb03c34d0a682', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(51, '35655754', 'Angel', 'Gabutero', 'chris.brown@mail.com', '5e15fa5b787fb86a681db740e825050b', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(52, '22513371', 'Michael', 'Johnson', 'michael.johnson@example.com', '3228635b89112e2c641f5e5cc44e19fe', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(53, '58865211', 'Emily', 'Johnson', 'emily.johnson@mail.com', '3228635b89112e2c641f5e5cc44e19fe', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(55, '69397243', 'Michael', 'Johnson', 'michael.johnson@mail.com', '3228635b89112e2c641f5e5cc44e19fe', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(56, '31595635', 'Jane', 'Brown', 'jane.brown@example.com', 'ed63fc91500594c3086714f86b3001e4', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'active'),
(57, '2310226', 'Anna', 'Garcia', 'annagarcia@gmail.com', '200820e3227815ed1756a6b531e7e0d2', 14, 'no-image-available.png', '2024-11-08 16:52:57', 'Active'),
(58, '53566720', 'Jane', 'Johnson', 'janson@gmail.com', '200820e3227815ed1756a6b531e7e0d2', 2, 'no-image-available.png', '2024-11-08 16:52:57', 'Active'),
(68, '53566720', 'Jane', 'Johnson', 'jane.johnson@mail.com', 'd41d8cd98f00b204e9800998ecf8427e', 14, 'no-image-available.png', '2024-11-09 11:03:22', 'active'),
(69, '2034321', 'Caryl Jade', 'Cano', 'cano.doe@example.com', '7c6a180b36896a0a8c02787eeafb0e4c', 14, 'no-image-available.png', '2024-11-09 11:05:06', 'active'),
(70, '2543502', 'Ogie', 'Obcial', 'ObcialG@gmail.com', '6cb75f652a9b52798eb6cf2201057c73', 14, 'no-image-available.png', '2024-11-09 11:05:06', 'active'),
(71, '1342103', 'Teves', 'Ted Anthony', 'tedteves@gmail.com', '819b0643d6b89dc9b579fdfc9094f28e', 14, 'no-image-available.png', '2024-11-09 11:05:06', 'active');

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
(0, 'CC-203', 'WEB 1', 'Web1'),
(1, 'SCC-PF201-IT2A', 'OBJECT-ORIENTED PROGRAMMING 1', 'OBJECT-ORIENTED PROGRAMMING 1'),
(2, 'ENG-101', 'English', 'English'),
(3, 'CC-102', 'COMPUTER PROGRAMMING 1', 'COMPUTER PROGRAMMING 1'),
(4, 'MATH-204', 'GENMATH', 'GENMATH'),
(5, 'FIl', 'Filipipino', 'ewrwqe');

-- --------------------------------------------------------

--
-- Table structure for table `subject_teacher`
--

CREATE TABLE `subject_teacher` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `academic_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_teacher`
--

INSERT INTO `subject_teacher` (`id`, `subject_id`, `faculty_id`, `academic_year`) VALUES
(24, 1, 12, 3),
(25, 1, 2, 3),
(35, 4, 12, 3),
(36, 4, 2, 3),
(38, 5, 12, 3),
(39, 5, 2, 3);

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
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`);

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
-- Indexes for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_year` (`academic_year`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `fk_faculty` (`faculty_id`);

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD CONSTRAINT `evaluation_list_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`),
  ADD CONSTRAINT `evaluation_list_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`);

--
-- Constraints for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD CONSTRAINT `subject_teacher_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`),
  ADD CONSTRAINT `subject_teacher_ibfk_2` FOREIGN KEY (`academic_year`) REFERENCES `academic_list` (`id`),
  ADD CONSTRAINT `subject_teacher_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subject_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
