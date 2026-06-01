-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2026 at 06:15 AM
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
-- Database: `sams_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `max_score` int(11) DEFAULT 100,
  `date_given` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(20) DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `status`) VALUES
(1, 'C-12345', '2025-05-01', 'Present'),
(2, 'C-12345', '2025-05-02', 'Present'),
(3, 'C-12345', '2025-05-03', 'Present'),
(4, 'C-12345', '2025-05-04', 'Present'),
(5, 'C-12345', '2025-05-05', 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `class_schedule`
--

CREATE TABLE `class_schedule` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_from` varchar(20) NOT NULL,
  `time_to` varchar(20) NOT NULL,
  `room` varchar(50) NOT NULL,
  `school_year` varchar(20) NOT NULL DEFAULT '2025-2026',
  `semester` varchar(20) NOT NULL DEFAULT '1st Semester'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_schedule`
--

INSERT INTO `class_schedule` (`id`, `student_id`, `subject_code`, `subject_name`, `day`, `time_from`, `time_to`, `room`, `school_year`, `semester`) VALUES
(1, 'STU-001', 'MATH101', 'College Algebra', 'Monday', '07:30', '09:00', 'Room 204', '2025-2026', '1st Semester'),
(2, 'STU-001', 'ENG101', 'English Communication', 'Wednesday', '10:30', '12:00', 'Main Building', '2025-2026', '1st Semester'),
(3, 'STU-001', 'CS101', 'Introduction to Computing', 'Friday', '13:30', '15:00', 'Computer Lab', '2025-2026', '1st Semester');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `status` enum('Enrolled','Not Enrolled') DEFAULT 'Not Enrolled',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `full_name`, `course`, `status`, `date_added`) VALUES
(3, 'C-12345', 'Joseph Louis B. Ramo', 'BSIS', 'Enrolled', '2026-05-28 01:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `student_assessments`
--

CREATE TABLE `student_assessments` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `units` int(11) NOT NULL,
  `school_year` varchar(20) NOT NULL DEFAULT '2025-2026',
  `semester` varchar(20) NOT NULL DEFAULT '1st Semester'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `units` int(11) NOT NULL,
  `course` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`, `units`, `course`) VALUES
(1, 'IT 101', 'Introduction to Computing', 3, 'BSIS'),
(2, 'IT 102', 'Computer Programming', 3, 'BSIS'),
(3, 'MATH 101', 'College Algebra', 3, 'BSIS'),
(4, 'ENG 101', 'English Communication', 3, 'BSIS'),
(5, 'NSTP 1', 'National Service Training Program', 3, 'BSIS'),
(6, 'PE 1', 'Physical Education 1', 3, 'BSIS');

-- --------------------------------------------------------

--
-- Table structure for table `subjects_list`
--

CREATE TABLE `subjects_list` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects_list`
--

INSERT INTO `subjects_list` (`id`, `subject_code`, `subject_name`, `units`) VALUES
(1, 'MATH101', 'College Algebra', 3),
(2, 'SCI101', 'General Science', 3),
(3, 'ENG101', 'English Communication', 3),
(4, 'HIST101', 'Philippine History', 3),
(5, 'CS101', 'Introduction to Computing', 3);

-- --------------------------------------------------------

--
-- Table structure for table `system_users`
--

CREATE TABLE `system_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `status` enum('Active','Frozen') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `teacher_id` varchar(50) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `subject_handled` varchar(255) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_id`, `full_name`, `subject_handled`, `schedule`, `status`) VALUES
(1, '123123123', 'Joseph Louis B. Ramo', 'programing', '10:30', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `user_type` enum('Student','Teacher','Admin') NOT NULL DEFAULT 'Student',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `section` varchar(20) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `subject_handled` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `student_id`, `user_type`, `username`, `password`, `fullname`, `created_at`, `firstname`, `middlename`, `lastname`, `course`, `year_level`, `section`, `department`, `subject_handled`, `email`) VALUES
('', '', 'Admin', 'jols122005', '$2y$10$uWXzNNy0HGU.opKF0bTIoe0BcJscOX8dgDnGvZyE5DOCp0DJa4QxG', 'Joseph brua ramo', '2026-05-21 19:18:58', 'Joseph', 'brua', 'ramo', '', '', '', '', '', 'ramo@gmail.com'),
('', 'FAC-1779363593', 'Teacher', 'jaybe1234', '$2y$10$GjS04/7Q4ndI9e82xS6oseTL2y9fDFZueVcRnfMxpLo9Wv0laohTK', 'josephe bruaa ramos', '2026-05-21 19:39:53', 'josephe', 'bruaa', 'ramos', 'FACULTY', '', '', 'Bsis dept', 'programing', 'ramojoseph78@gmail.com'),
('', 'asdasd', 'Student', '123123123', '$2y$10$6W7gmOIZAc5bwzkYSZW0q.1nsK89/m6vNv9QdlTqJ4mQvBUBvKqGW', 'asdsadas dsad asdasdasd', '2026-05-21 19:56:37', 'asdsadas', 'dsad', 'asdasdasd', 'BSIS', '1st Year', 'asdasdasdasd', '', '', 'asdasdasd@gmail.com'),
('', 'FAC-1779364684', 'Teacher', 'haha', '$2y$10$2C5CLSLkmBaGIFegVSLO9ewdB8YHwwg5e8DaZoUczDt4dNoyqZwxm', 'hahaha ano anoba', '2026-05-21 19:58:04', 'hahaha', 'ano', 'anoba', 'FACULTY', '', '', 'isdept', 'mama', 'haha@mgail.com'),
('', 'C-123123', 'Student', 'ramo123', '$2y$10$8fZfHNdwA93QtLg3N9K.4.PI9svfs6v/Fq9VOeO6voH/SYhNOCg1W', 'Joseph brua ramo', '2026-05-22 14:56:11', 'Joseph', 'brua', 'ramo', 'BSIS', '2nd Year', 'BSIS-2', '', '', 'ramojoseph87@gmail.com'),
('', 'ADM-1779461443', 'Admin', 'mark', '$2y$10$5PBFFchaJN.3fhT7Yj6OP.E3n6M79GXC30jI1XhIYgbExJMyYaJP2', 'Mark Rosalles Tan', '2026-05-22 22:50:43', 'mark', 'rosalles', 'tan', 'ADMIN', '', '', 'IT department', '', 'mark@gmail.com'),
('', 'C-12322', 'Student', 'jam123', '$2y$10$ClBfTB8/U6BzLv0stbTJf.0T09tCoDhcoEqE3FfjjEVggp2D1M6oi', 'Jam Reyes Cruz', '2026-05-22 23:05:28', 'Jam', 'Reyes', 'Cruz', 'BSBA', '2nd Year', 'BSBA-2B', '', '', 'jam@gmail.com'),
('', 'C-21232', 'Student', 'louis123', '$2y$10$6vF.44YMqJWe.LHxq7D7ueugePB5TMgKJvfS2qhINsSErWwD4LFv.', 'Joseph Louis Brua Ramo', '2026-05-23 02:38:47', 'Joseph Louis', 'Brua', 'Ramo', 'BSIS', '2nd Year', '2a', '', '', 'josephlouis@gmail.com'),
('', 'C-12345', 'Student', 'marlie', '$2y$10$42m2MyEndS0rqD5lmtPPPOEbw1SazthMR5/3p0irY7HGg5yMuwPWy', 'Marlie Alimboyong Sarsaba', '2026-05-23 15:44:06', 'Marlie', 'Alimboyong', 'Sarsaba', 'BSIS', '2nd Year', 'A', '', '', 'sarsabamarlie31@gmaill.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `class_schedule`
--
ALTER TABLE `class_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `student_assessments`
--
ALTER TABLE `student_assessments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `subjects_list`
--
ALTER TABLE `subjects_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_schedule`
--
ALTER TABLE `class_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_assessments`
--
ALTER TABLE `student_assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects_list`
--
ALTER TABLE `subjects_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
