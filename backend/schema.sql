-- Enhanced Database Schema for Ushering System with Councils and Authentication
-- Run this to create/update all tables

-- Councils Table
CREATE TABLE IF NOT EXISTS `councils` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL UNIQUE,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users Table (for authentication)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('VP', 'Head', 'Instructor') NOT NULL,
  `council_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_council` (`council_id`),
  KEY `idx_role` (`role`),
  FOREIGN KEY (`council_id`) REFERENCES `councils`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registration Table (updated with council_id)
CREATE TABLE IF NOT EXISTS `registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(20) NOT NULL,
  `college` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL,
  `preferences` text,
  `council_id` int(11) DEFAULT NULL,
  `rating` enum('Pending', 'Acceptance', 'B', 'Rejection') DEFAULT 'Pending',
  `notes` text,
  `interview_time` datetime DEFAULT NULL,
  `interviewed_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rating` (`rating`),
  KEY `idx_level` (`level`),
  KEY `idx_email` (`email`),
  KEY `idx_council` (`council_id`),
  FOREIGN KEY (`council_id`) REFERENCES `councils`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sessions Table (for authentication)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL UNIQUE,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_token` (`token`),
  KEY `idx_user` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add council_id column to existing registration table if it doesn't exist
ALTER TABLE `registration` 
ADD COLUMN IF NOT EXISTS `council_id` int(11) DEFAULT NULL AFTER `preferences`,
ADD COLUMN IF NOT EXISTS `interview_time` datetime DEFAULT NULL AFTER `notes`,
ADD COLUMN IF NOT EXISTS `interviewed_by` varchar(100) DEFAULT NULL AFTER `interview_time`,
ADD KEY IF NOT EXISTS `idx_council` (`council_id`);

-- Insert sample councils
INSERT INTO `councils` (`name`, `description`) VALUES
('Technical Council', 'Handles all technical activities and development'),
('Marketing Council', 'Manages marketing and promotional activities'),
('Events Council', 'Organizes and manages all events')
ON DUPLICATE KEY UPDATE `name` = `name`;

-- Insert sample users (password is 'password123' hashed with bcrypt)
-- Note: In production, use proper password hashing
INSERT INTO `users` (`username`, `email`, `password`, `role`, `council_id`) VALUES
('vp_tech', 'vp@tech.council', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'VP', 1),
('head_tech', 'head@tech.council', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Head', 1),
('instructor_tech', 'instructor@tech.council', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Instructor', 1)
ON DUPLICATE KEY UPDATE `username` = `username`;
