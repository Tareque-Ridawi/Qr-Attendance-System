-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2026 at 01:40 PM
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
-- Database: `qr-code-based-attendance-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `student_id` varchar(30) NOT NULL,
  `status` enum('Present','Absent','','') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `course_id`, `student_id`, `status`, `date`, `time`) VALUES
(1, 'CSE 2500', 'faisal_madkhali', 'Absent', '2025-03-26', '00:00:00'),
(2, 'CSE 2500', 'rakib_hasan', 'Present', '2025-03-26', '09:00:00'),
(3, 'CSE 2500', 'nahid_islam', 'Present', '2025-03-26', '09:05:00'),
(4, 'CSE 2500', 'shamim_hossain', 'Absent', '2025-03-26', '09:10:00'),
(5, 'CSE 2500', 'mahmudul_haque', 'Present', '2025-03-26', '09:15:00'),
(6, 'CSE 2500', 'farzana_rahman', 'Present', '2025-03-26', '09:20:00'),
(7, 'CSE 2500', 'tasnim_jahan', 'Absent', '2025-03-26', '09:25:00'),
(8, 'CSE 2500', 'firoz_kabir', 'Present', '2025-03-26', '09:30:00'),
(9, 'CSE 2500', 'sohel_rana', 'Present', '2025-03-26', '09:35:00'),
(10, 'CSE 2500', 'nargis_akter', 'Absent', '2025-03-26', '09:40:00'),
(11, 'CSE 2500', 'sabbir_ahmed', 'Present', '2025-03-26', '09:45:00'),
(12, 'CSE 2500', 'ataullah_gani', 'Present', '2025-03-26', '09:50:00'),
(13, 'CSE 2500', 'faisal_madkhali', 'Present', '2025-03-29', '09:00:00'),
(14, 'CSE 2500', 'rakib_hasan', 'Absent', '2025-03-29', '09:05:00'),
(15, 'CSE 2500', 'nahid_islam', 'Present', '2025-03-29', '09:10:00'),
(16, 'CSE 2500', 'shamim_hossain', 'Present', '2025-03-29', '09:15:00'),
(17, 'CSE 2500', 'mahmudul_haque', 'Present', '2025-03-29', '09:20:00'),
(18, 'CSE 2500', 'farzana_rahman', 'Absent', '2025-03-29', '09:25:00'),
(19, 'CSE 2500', 'tasnim_jahan', 'Present', '2025-03-29', '09:30:00'),
(20, 'CSE 2500', 'firoz_kabir', 'Present', '2025-03-29', '09:35:00'),
(21, 'CSE 2500', 'sohel_rana', 'Absent', '2025-03-29', '09:40:00'),
(22, 'CSE 2500', 'nargis_akter', 'Present', '2025-03-29', '09:45:00'),
(23, 'CSE 2500', 'sabbir_ahmed', 'Absent', '2025-03-29', '09:50:00'),
(24, 'CSE 2500', 'ataullah_gani', 'Absent', '2025-03-29', '09:55:00'),
(25, 'CSE1105', 'rakib_hasan', 'Present', '2025-04-16', '05:22:14'),
(34, 'CSE 01', 'faisal_madkhali', 'Present', '2025-04-16', '06:19:41'),
(35, 'CSE 01', 'sohel_rana', 'Absent', '2025-04-16', '00:00:00'),
(43, 'CSE 100', 'calvinharris1234', 'Present', '2025-04-25', '11:08:36'),
(44, 'CSE 100', 'alex_john', 'Present', '2025-04-25', '00:00:00'),
(45, 'CSE 100', 'sabbir_ahmed', 'Absent', '2025-04-25', '00:00:00'),
(46, 'CSE1105', 'venjex-m', 'Absent', '2025-04-26', '00:00:00'),
(47, 'CSE1105', 'rakib_hasan', 'Absent', '2025-04-26', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `instructor_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_id`, `course_title`, `instructor_id`) VALUES
(1, 'CSE1105', 'Computer Ethics', 'buck-lee-909'),
(2, 'CSE 2500', 'Ethical Hacking', 'buck-lee-909'),
(7, 'CSE 01', 'gfx design', 'buck-lee-909'),
(8, 'CSE 02', 'Web Design', 'tareque_ridawi'),
(9, 'CSE 100', 'Internet Programming', 'johnmark2345');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `student_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `course_id`, `student_id`) VALUES
(1, 'CSE 2500', 'venjex-m'),
(2, 'CSE1105', 'venjex-m'),
(3, 'CSE 2500', 'faisal_madkhali'),
(6, 'CSE 2500', 'rakib_hasan'),
(7, 'CSE 2500', 'nahid_islam'),
(8, 'CSE 2500', 'shamim_hossain'),
(9, 'CSE 2500', 'mahmudul_haque'),
(10, 'CSE 2500', 'farzana_rahman'),
(11, 'CSE 2500', 'tasnim_jahan'),
(12, 'CSE 2500', 'firoz_kabir'),
(13, 'CSE 2500', 'sohel_rana'),
(14, 'CSE 2500', 'nargis_akter'),
(15, 'CSE 2500', 'sabbir_ahmed'),
(16, 'CSE 2500', 'ataullah_gani'),
(17, 'CSE1105', 'rakib_hasan'),
(19, 'CSE 01', 'faisal_madkhali'),
(20, 'CSE 01', 'sohel_rana'),
(21, 'CSE 100', 'calvinharris1234'),
(23, 'CSE 100', 'alex_john'),
(24, 'CSE 100', 'sabbir_ahmed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `instructor` tinyint(1) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `instructor`, `name`, `email`) VALUES
(1, 'venjex-m', '12344321', 0, 'Venjex M Alt', 'Venjex@mail.com'),
(2, 'buck-lee-909', '12344321', 1, 'Buck Lee', 'bucklee@gmail.com'),
(3, 'faisal_madkhali', '12344321', 0, 'Faisal Al Madkhali', 'faisal.madkhali@hotmail.com'),
(4, 'tareque_ridawi', '12344321', 1, 'Tareque Ridawi', 'tareque.ridawi@gmail.com'),
(5, 'rakib_hasan', '12344321', 0, 'Rakib Hasan', 'rakib.hasan@outlook.com'),
(6, 'nahid_islam', '12344321', 0, 'Nahid Islam', 'nahid.islam@gmail.com'),
(7, 'shamim_hossain', '12344321', 0, 'Shamim Hossain', 'shamim.hossain@yahoo.com'),
(8, 'mahmudul_haque', '12344321', 0, 'Mahmudul Haque', 'mahmudul.haque@outlook.com'),
(9, 'farzana_rahman', '12344321', 0, 'Farzana Rahman', 'farzana.rahman@gmail.com'),
(10, 'tasnim_jahan', '12344321', 0, 'Tasnim Jahan', 'tasnim.jahan@hotmail.com'),
(11, 'firoz_kabir', '12344321', 0, 'Firoz Kabir', 'firoz.kabir@yahoo.com'),
(12, 'sohel_rana', '12344321', 0, 'Sohel Rana', 'sohel.rana@gmail.com'),
(13, 'nargis_akter', '12344321', 0, 'Nargis Akter', 'nargis.akter@outlook.com'),
(14, 'sabbir_ahmed', '12344321', 0, 'Sabbir Ahmed', 'sabbir.ahmed@gmail.com'),
(15, 'ataullah_gani', '12344321', 0, 'Ataullah Gani', 'ataullah.gani@yahoo.com'),
(16, 'alex_john', '12344321', 0, 'Alex John', 'alex@mail.com'),
(17, 'dallas_texas', '12344321', 0, 'Dallas Texas', 'dallas@mail.com'),
(21, 'johnmark2345', '12344321', 1, 'John Mark Junior', 'johnmark2345@gmail.com'),
(22, 'calvinharris1234', '12344321', 0, 'Calvin Harris', 'calvinharris@gmail.com'),
(23, 'khalid_nasser', '12344321', 0, 'Khalid Naser', 'khalid@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name_2` (`user_name`),
  ADD KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_name`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`user_name`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
