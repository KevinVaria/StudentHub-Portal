CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;
CREATE TABLE IF NOT EXISTS students (
 student_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 mobile VARCHAR(15) NOT NULL,
 password_hash VARCHAR(255) NOT NULL,
 password VARCHAR(255) NULL,
 course VARCHAR(50) NOT NULL,
 year INT NOT NULL,
 gender VARCHAR(30) NOT NULL DEFAULT 'Prefer not to say',
 role VARCHAR(20) NOT NULL DEFAULT 'student',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS events (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 category VARCHAR(50) NOT NULL,
 event_date DATE NOT NULL,
 location VARCHAR(150) NOT NULL,
 description TEXT,
 poster VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS registrations (
 id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 event_id INT NOT NULL,
 registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_registration (student_id,event_id),
 CONSTRAINT fk_reg_student FOREIGN KEY(student_id) REFERENCES students(student_id) ON DELETE CASCADE,
 CONSTRAINT fk_reg_event FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS audit_logs (
 id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NULL,
 action VARCHAR(80) NOT NULL,
 details VARCHAR(255) NOT NULL DEFAULT '',
 ip_address VARCHAR(45) NOT NULL DEFAULT '',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_audit_created (created_at)
) ENGINE=InnoDB;
INSERT INTO events(title,category,event_date,location,description)
SELECT 'Tech Fest 2026','Technology','2026-10-15','Auditorium','Innovation and coding event.'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Tech Fest 2026');
INSERT INTO events(title,category,event_date,location,description)
SELECT 'Sports Day','Sports','2026-10-20','Main Ground','Annual college sports meet.'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Sports Day');
