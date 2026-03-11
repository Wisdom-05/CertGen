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

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lrn VARCHAR(20) DEFAULT '',
    student_name VARCHAR(255) NOT NULL,
    current_grade VARCHAR(50) DEFAULT '',
    current_section VARCHAR(100) DEFAULT '',
    prev_school VARCHAR(255) DEFAULT '',
    curriculum VARCHAR(255) DEFAULT 'Standard',
    date_received VARCHAR(100) DEFAULT '',
    receiver VARCHAR(255) DEFAULT '',
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
