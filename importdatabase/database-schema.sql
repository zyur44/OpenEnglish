-- =========================================================================
-- FILE: database_schema.sql
-- ĐẶC TẢ CƠ SỞ DỮ LIỆU WEBSITE HỌC TIẾNG ANH ONLINE (XAMPP / MYSQL)
-- =========================================================================

-- 1. Khởi tạo Database (Nếu chưa có)
CREATE DATABASE IF NOT EXISTS `english_learning_db`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `english_learning_db`;

-- Tắt kiểm tra khóa ngoại tạm thời để tránh lỗi thứ tự tạo bảng
SET FOREIGN_KEY_CHECKS = 0;

-- =========================================================================
-- NHÓM 1: QUẢN LÝ TÀI KHOẢN & PHÂN QUYỀN (Admin & Student)
-- =========================================================================

-- Bảng `roles` (Vai trò)
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL COMMENT 'Admin hoặc Student',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `users` (Tài khoản người dùng)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `role_id` INT NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_email` (`email`),
    CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================================
-- NHÓM 2: CẤU TRÚC KHÓA HỌC & NỘI DUNG (Courses, Units, Videos, Documents)
-- =========================================================================

-- Bảng `courses` (Khóa học)
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `thumbnail_url` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `units` (Chương học)
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `course_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `order_index` INT NOT NULL COMMENT 'Thứ tự bài học trong khóa học',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_units_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `videos` (Video bài giảng - Mỗi Unit có 1 video chính)
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `unit_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `video_url` VARCHAR(255) NOT NULL,
    `duration` INT NOT NULL COMMENT 'Thời lượng video tính bằng giây',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_unit_video` (`unit_id`),
    CONSTRAINT `fk_videos_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `documents` (Tài liệu đính kèm bài học)
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `unit_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `file_url` VARCHAR(255) NOT NULL COMMENT 'Đường dẫn file pdf/docx trong thư mục uploads',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_documents_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================================
-- NHÓM 3: BÀI TẬP TRẮC NGHIỆM 4 ĐÁP ÁN (Quizzes, Questions, Answers)
-- =========================================================================

-- Bảng `quizzes` (Bài kiểm tra tổng hợp của Unit)
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `unit_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_unit_quiz` (`unit_id`),
    CONSTRAINT `fk_quizzes_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `questions` (Câu hỏi trắc nghiệm)
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `quiz_id` INT NOT NULL,
    `question_text` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_questions_quizzes` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `answers` (4 lựa chọn đáp án A, B, C, D cho câu hỏi)
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `question_id` INT NOT NULL,
    `answer_text` TEXT NOT NULL,
    `is_correct` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '1 nếu là đáp án đúng, 0 nếu là đáp án sai',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_answers_questions` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================================
-- NHÓM 4: QUẢN LÝ TIẾN TRÌNH HỌC TẬP (Enrollments & Progress)
-- =========================================================================

-- Bảng `enrollments` (Lưu danh sách khóa học học sinh tham gia & Tiến trình tổng)
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `course_progress` INT NOT NULL DEFAULT 0 COMMENT 'Giá trị phần trăm từ 0 đến 100',
    `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_user_course` (`user_id`, `course_id`),
    CONSTRAINT `fk_enrollments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_enrollments_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `user_video_progress` (Theo dõi xem hết video chưa)
DROP TABLE IF EXISTS `user_video_progress`;
CREATE TABLE `user_video_progress` (
    `user_id` INT NOT NULL,
    `video_id` INT NOT NULL,
    `is_watched` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '1 nếu đã xem hết bài giảng',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `video_id`),
    CONSTRAINT `fk_uvp_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_uvp_videos` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng `user_quiz_attempts` (Lịch sử làm bài tập trắc nghiệm của học sinh)
DROP TABLE IF EXISTS `user_quiz_attempts`;
CREATE TABLE `user_quiz_attempts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `quiz_id` INT NOT NULL,
    `score` FLOAT NOT NULL COMMENT 'Điểm số bài làm',
    `is_passed` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '1 nếu đạt điểm qua môn, dùng để xét tiến trình',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_uqa_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_uqa_quizzes` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mở lại kiểm tra khóa ngoại sau khi tạo bảng xong
SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- KHỞI TẠO CÁC VAI TRÒ MẶC ĐỊNH
-- =========================================================================
INSERT INTO `roles` (`id`, `name`) VALUES (1, 'Admin'), (2, 'Student');