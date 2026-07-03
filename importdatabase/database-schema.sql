-- Admin account
-- Email: admin@gmail.com
-- Password: 000000
-- Student account
-- Email: student@email.com
-- Password: 00000000

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2026 at 09:38 AM
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
-- Database: `openenglish`
--
CREATE DATABASE openenglish;

USE openenglish;
-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 nếu là đáp án đúng, 0 nếu là đáp án sai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 2001, 'A. How do you do?', 0),
(2, 2001, 'B. What\'s up, bro?', 1),
(3, 2001, 'C. Good morning, sir.', 0),
(4, 2001, 'D. Nice to meet you.', 0),
(5, 2002, 'A. I\'m fine, thank you.', 0),
(6, 2002, 'B. How do you do?', 1),
(7, 2002, 'C. I am 20 years old.', 0),
(8, 2002, 'D. Nice to meet you too.', 0),
(9, 2003, 'A. See you later.', 0),
(10, 2003, 'B. Catch you later.', 0),
(11, 2003, 'C. Take care.', 0),
(12, 2003, 'D. Farewell.', 1),
(13, 2004, 'A. You\'re welcome.', 1),
(14, 2004, 'B. Never mind.', 0),
(15, 2004, 'C. You\'re friendly.', 0),
(16, 2004, 'D. Same to you.', 0),
(17, 2005, 'A. How are you?', 0),
(18, 2005, 'B. What do you do?', 1),
(19, 2005, 'C. Where are you from?', 0),
(20, 2005, 'D. What is your hobby?', 0),
(21, 2006, 'A. Thông tin được nói đầu tiên (Monday).', 0),
(22, 2006, 'B. Thông tin được sửa lại phía sau (Tuesday).', 1),
(23, 2006, 'C. Cả hai ngày đều sai.', 0),
(24, 2006, 'D. Người nghe tự chọn một trong hai ngày.', 0),
(25, 2007, 'A. Các mạo từ như a, an, the.', 0),
(26, 2007, 'B. Các từ mang tính phủ định, tần suất hoặc động từ chính (not, only, internal, change).', 1),
(27, 2007, 'C. Tên các nhân vật xuất hiện trong câu hỏi.', 0),
(28, 2007, 'D. Giới từ chỉ vị trí (in, on, at).', 0),
(29, 2008, 'A. Đó chắc chắn là đáp án đúng.', 0),
(30, 2008, 'B. Đó thường là bẫy (Distractor) và từ ngữ đã bị thay đổi ngữ cảnh.', 1),
(31, 2008, 'C. Đoạn băng bị lỗi kỹ thuật.', 0),
(32, 2008, 'D. Câu hỏi đó được tính điểm tối đa.', 0),
(33, 2009, 'A. Skimming (Đọc lướt).', 0),
(34, 2009, 'B. Scanning (Đọc quét).', 0),
(35, 2009, 'C. Prediction (Dự đoán/Định hướng thông tin).', 1),
(36, 2009, 'D. Paraphrasing (Dịch nghĩa xuôi).', 0),
(37, 2010, 'A. Đọc kỹ câu hỏi, gạch chân từ khóa và dịch nghĩa các lựa chọn để tìm từ đồng nghĩa.', 1),
(38, 2010, 'B. Ngồi nghỉ ngơi để tập trung tinh thần.', 0),
(39, 2010, 'C. Viết sẵn các từ ngẫu nhiên ra nháp.', 0),
(40, 2010, 'D. Đọc lướt qua toàn bộ các Section khác.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `thumbnail_url`) VALUES
(1, 'Tiếng Anh Giao Tiếp Cho Người Mới Bắt Đầu', 'Khóa học giúp bạn làm quen với các tình huống giao tiếp cơ bản hàng ngày, phát âm chuẩn và phản xạ tự nhiên.', '/frontend/assets/image/course-thumbnail/communication.webp'),
(2, 'Luyện Thi Cấp Tốc IELTS Listening & Reading', 'Tập trung nâng cao kỹ năng Nghe và Đọc với các chiến thuật làm bài thực tế nhằm tối ưu hóa điểm số.', '/frontend/assets/image/course-thumbnail/ielts.webp');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL COMMENT 'Đường dẫn file pdf/docx trong thư mục uploads'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `unit_id`, `title`, `file_url`) VALUES
(701, 101, 'Tài liệu tổng hợp mẫu câu giới thiệu bản thân (PDF)', '../frontend/upload/pdf/unit1.pdf'),
(702, 102, 'Bản đồ từ vựng chủ đề Hỏi đường (PDF)', '../frontend/upload/pdf/unit2.pdf'),
(703, 103, 'Hội thoại mẫu: Tình huống mua sắm tại chợ bản xứ', '../frontend/upload/pdf/unit3.pdf'),
(704, 104, 'Danh sách từ vựng các món ăn & Cách đặt bàn', 'https://s2.q4cdn.com/175719177/files/doc_presentations/Placeholder-PDF.pdf'),
(705, 105, 'Bài mẫu viết về chủ đề My Hobby', 'https://s2.q4cdn.com/175719177/files/doc_presentations/Placeholder-PDF.pdf'),
(706, 201, 'Bài tập thực hành Nghe Multiple Choice kèm Transcripts', '../frontend/upload/pdf/ielts_unit1.pdf'),
(707, 202, 'Tổng hợp các từ dễ viết sai chính tả trong IELTS Listening', '../frontend/upload/pdf/ielts_unit2.pdf'),
(708, 203, 'Đoạn văn ngắn luyện kỹ năng đọc quét thông tin nhanh', 'https://s2.q4cdn.com/175719177/files/doc_presentations/Placeholder-PDF.pdf'),
(709, 204, 'Bộ bài tập phân tích True/False/Not Given có giải thích chi tiết', 'https://s2.q4cdn.com/175719177/files/doc_presentations/Placeholder-PDF.pdf'),
(710, 205, 'Chiến thuật gạch Key Words cho dạng Matching Headings', 'https://s2.q4cdn.com/175719177/files/doc_presentations/Placeholder-PDF.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course_progress` int(11) NOT NULL DEFAULT 0 COMMENT 'Giá trị phần trăm từ 0 đến 100',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`) VALUES
(2001, 901, 'Khi gặp một người bạn thân lâu ngày không gặp, câu chào nào sau đây mang tính tự nhiên và thân mật nhất?'),
(2002, 901, 'Hoàn thành đoạn hội thoại sau:\n\"A: How do you do?\"\n\"B: ______________\"'),
(2003, 901, 'Cụm từ nào sau đây ĐỒNG NGHĨA và có thể thay thế cho \"Goodbye\" trong ngữ cảnh trang trọng?'),
(2004, 901, 'Khi ai đó nói \"Thank you so much for your help!\", câu trả lời nào sau đây là lịch sự và phổ biến nhất?'),
(2005, 901, 'Chọn câu hỏi phù hợp cho câu trả lời sau:\n\"- _________________?\"\n\"- I\'m an accountant.\"'),
(2006, 902, 'Trong phần thi IELTS Listening, nếu người nói sửa lại thông tin ngay sau khi vừa phát biểu (Ví dụ: \"We will meet on Monday... oh wait, sorry, I mean Tuesday\"), đáp án chính xác thường rơi vào trường hợp nào?'),
(2007, 902, 'Từ khóa (Key words) nào là QUAN TRỌNG NHẤT cần gạch chân trong câu hỏi trắc nghiệm để tránh bị lạc hướng khi nghe?'),
(2008, 902, 'Khi các lựa chọn A, B, C trong bài nghe sử dụng chính xác 100% các từ ngữ (same words) xuất hiện trong đoạn ghi âm, điều này thường có nghĩa là gì?'),
(2009, 902, 'Kỹ năng nào giúp bạn đoán trước được loại thông tin (danh từ, động từ, con số, tên riêng) cần điền hoặc cần nghe trước khi băng chạy?'),
(2010, 902, 'Để chuẩn bị tốt nhất cho dạng bài Multiple Choice, thí sinh nên làm gì trong khoảng thời gian trống trước khi phần nghe bắt đầu?');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `unit_id`, `title`) VALUES
(901, 101, 'Bài tập trắc nghiệm: Chào hỏi & Phản xạ (Unit 1 - Giao tiếp)'),
(902, 201, 'Mini-test: Luyện tập nghe Multiple Choice (Unit 1 - IELTS)');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'Admin hoặc Student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_index` int(11) NOT NULL COMMENT 'Thứ tự bài học trong khóa học'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `course_id`, `title`, `order_index`) VALUES
(101, 1, 'Unit 1: Chào hỏi và Giới thiệu bản thân', 1),
(102, 1, 'Unit 2: Hỏi đường và Cách di chuyển', 2),
(103, 1, 'Unit 3: Mua sắm và Mặc cả giá cả', 3),
(104, 1, 'Unit 4: Gọi món tại Nhà hàng', 4),
(105, 1, 'Unit 5: Trò chuyện về Sở thích', 5),
(201, 2, 'Unit 1: Chiến thuật Multiple Choice trong Listening', 1),
(202, 2, 'Unit 2: Xử lý dạng bài Form Completion', 2),
(203, 2, 'Unit 3: Kỹ năng Skimming & Scanning trong Reading', 3),
(204, 2, 'Unit 4: Chinh phục dạng bài True/False/Not Given', 4),
(205, 2, 'Unit 5: Bí quyết làm bài Matching Headings', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin2', 'admin@gmail.com', '$2y$10$Kz2hrU1Bud3d2W0MeRF5MO.H2L3Ku3XfzqWh6YuUIQomBi.fRsYZq', '2026-06-22 01:44:43', '2026-07-01 01:38:08'),
(2, 2, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '$2y$10$cOiEL1Pq/.uBfngVYdfWQuJ2DXa/HWv09vVp.BbC3Vpnzgrx8WEnC', '2026-06-29 10:44:00', '2026-06-30 10:04:14'),
(3, 2, 'Trần Thị Bích', 'tranthibich@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(4, 2, 'Lê Hoàng Nam', 'lehoangnam@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(5, 2, 'Phạm Minh Tuấn', 'phamminhtuan@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(6, 2, 'Hoàng Thu Trang', 'hoangthutrang@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(7, 2, 'Vũ Minh Đức', 'vuminhduc@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(8, 2, 'Đặng Phương Thảo', 'dangphuongthao@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(9, 2, 'Bùi Anh Tú', 'buianhtu@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(10, 2, 'Đỗ Thị Diễm', 'dothidiem@gmail.com', '$2y$10$mC3BvIe7IEXfD8G4bX4gXeZ1rB6Z.B9k.N9Z2r9K1z7nZ6zG2XyKi', '2026-06-22 01:44:43', '2026-06-22 01:44:43'),
(11, 2, 'Test User', 'test@example.com', '$2y$10$QIMLKVpF86TgInlaXxlDDOlZK6gpnkAfKNQAjHsPHX.bsXlilSWfm', '2026-06-29 10:58:49', '2026-06-29 10:58:49'),
(12, 2, 'abcxyzmnfdn', 'abcxyz@email.com', '$2y$10$pySnzKCzXVOaFNm64NF96eGlBU/yG0yTHlclO0OnylGew9EuInfZS', '2026-06-29 11:01:02', '2026-06-29 11:01:02'),
(13, 1, 'asdfghjkl', 'qwerty@email.com', '$2y$10$HPkSiFsUvEbt7UgTdIjtXuG9eKxEiEPKsHoKUmIBCfbLOfDg0DxdK', '2026-07-01 02:17:42', '2026-07-01 02:39:57'),
(14, 2, 'student', 'student@email.com', '$2y$10$A6k.G5kjUKExkDGwuyJcLOnisJ6aoBIMIxP/jNMtADROrUJB3U0kO', '2026-07-01 04:42:33', '2026-07-03 07:32:50'),
(15, 2, 'Trần Thị B', 'tranthib@email.com', '$2y$10$FeCJR.WZ8Sf9DL0bTDMEaOp6lCJZtWH/M2Pj2xa7vsGeUnvB4fMiC', '2026-07-02 05:11:30', '2026-07-03 07:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_attempts`
--

CREATE TABLE `user_quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` float NOT NULL COMMENT 'Điểm số bài làm',
  `is_passed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 nếu đạt điểm qua môn, dùng để xét tiến trình',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_quiz_attempts`
--

INSERT INTO `user_quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `is_passed`, `created_at`) VALUES
(1, 1, 901, 2, 0, '2026-07-01 03:40:30'),
(2, 1, 901, 2, 0, '2026-07-01 03:43:33'),
(3, 1, 901, 2, 0, '2026-07-01 03:43:44'),
(4, 1, 901, 2, 0, '2026-07-01 03:46:10'),
(5, 1, 901, 6, 1, '2026-07-01 03:48:00'),
(6, 13, 902, 4, 0, '2026-07-01 03:55:06'),
(7, 13, 902, 2, 0, '2026-07-01 03:57:19'),
(8, 13, 902, 2.5, 0, '2026-07-01 03:57:44'),
(9, 13, 902, 2, 0, '2026-07-01 03:58:11'),
(10, 1, 902, 2, 0, '2026-07-01 07:47:52'),
(11, 1, 902, 10, 1, '2026-07-01 11:14:13'),
(12, 1, 901, 8, 1, '2026-07-01 11:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_video_progress`
--

CREATE TABLE `user_video_progress` (
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `is_watched` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 nếu đã xem hết bài giảng',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_video_progress`
--

INSERT INTO `user_video_progress` (`user_id`, `video_id`, `is_watched`, `updated_at`) VALUES
(1, 504, 1, '2026-07-02 05:08:43'),
(1, 505, 1, '2026-07-01 11:13:12'),
(1, 506, 1, '2026-07-01 11:13:57'),
(1, 507, 1, '2026-07-01 11:51:21'),
(1, 508, 1, '2026-07-02 05:09:22'),
(1, 509, 1, '2026-07-01 11:32:53'),
(1, 510, 1, '2026-07-01 11:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Thời lượng video tính bằng giây'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `unit_id`, `title`, `video_url`, `duration`) VALUES
(501, 101, 'Unit 1: Chào hỏi và Giới thiệu bản thân', '../frontend/upload/video/unit1.mp4', 600),
(502, 102, 'Unit 2: Hỏi đường và Cách di chuyển', '../frontend/upload/video/unit2.mp4', 750),
(503, 103, 'Unit 3: Mua sắm và Mặc cả giá cả', '../frontend/upload/video/unit3.mp4', 900),
(504, 104, 'Unit 4: Gọi món tại Nhà hàng', 'https://www.w3schools.com/html/mov_bbb.mp4', 680),
(505, 105, 'Unit 5: Trò chuyện về Sở thích', 'https://www.w3schools.com/html/mov_bbb.mp4', 820),
(506, 201, 'Unit 1: Chiến thuật Multiple Choice trong Listening', '../frontend/upload/video/ielts_unit1.mp4', 1200),
(507, 202, 'Unit 2: Xử lý dạng bài Form Completion', '../frontend/upload/video/ielts_unit2.mp4', 1050),
(508, 203, 'Unit 3: Kỹ năng Skimming & Scanning trong Reading', 'https://www.w3schools.com/html/mov_bbb.mp4', 1400),
(509, 204, 'Unit 4: Chinh phục dạng bài True/False/Not Given', 'https://www.w3schools.com/html/mov_bbb.mp4', 1500),
(510, 205, 'Unit 5: Bí quyết làm bài Matching Headings', 'https://www.w3schools.com/html/mov_bbb.mp4', 1320);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_answers_questions` (`question_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documents_units` (`unit_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_user_course` (`user_id`,`course_id`),
  ADD KEY `fk_enrollments_courses` (`course_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_questions_quizzes` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_unit_quiz` (`unit_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_units_courses` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_email` (`email`),
  ADD KEY `fk_users_roles` (`role_id`);

--
-- Indexes for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uqa_users` (`user_id`),
  ADD KEY `fk_uqa_quizzes` (`quiz_id`);

--
-- Indexes for table `user_video_progress`
--
ALTER TABLE `user_video_progress`
  ADD PRIMARY KEY (`user_id`,`video_id`),
  ADD KEY `fk_uvp_videos` (`video_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_unit_video` (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=711;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2011;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=903;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `fk_answers_questions` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enrollments_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_quizzes` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quizzes_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `fk_units_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  ADD CONSTRAINT `fk_uqa_quizzes` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uqa_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_video_progress`
--
ALTER TABLE `user_video_progress`
  ADD CONSTRAINT `fk_uvp_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uvp_videos` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_videos_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
