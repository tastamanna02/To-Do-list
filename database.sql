-- ============================================================
-- Minimal To-Do List Application - Database Schema
-- Compatible with MySQL 5.7+ / MariaDB / phpMyAdmin
-- ============================================================

CREATE DATABASE IF NOT EXISTS `todo_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `todo_db`;

-- Tasks Table
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `text` VARCHAR(255) NOT NULL,
  `completed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial Seed Data
INSERT INTO `tasks` (`text`, `completed`) VALUES
('Prepare project presentation', 0),
('Review PHP CRUD logic', 1),
('Complete weekly tasks', 0);
