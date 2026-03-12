-- CertGen Database Setup

CREATE DATABASE IF NOT EXISTS certgen;
USE certgen;

CREATE TABLE IF NOT EXISTS certificate_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    certificate_type VARCHAR(100) NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    lrn VARCHAR(20) DEFAULT '',
    grade_level VARCHAR(50) DEFAULT '',
    section_track VARCHAR(100) DEFAULT '',
    curriculum VARCHAR(255) DEFAULT '',
    school_year VARCHAR(20) DEFAULT '',
    purpose VARCHAR(255) DEFAULT '',
    date_issued VARCHAR(50) NOT NULL,
    principal_name VARCHAR(100) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT '',
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial default user: admin / admin123
INSERT INTO users (username, password, full_name) 
SELECT 'admin', '$2y$10$gfbYJ6zs8hrywM6y02TuMeC9haoX/xeYp40DW.lfOQTou8Qs33K3a', 'System Administrator'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');
