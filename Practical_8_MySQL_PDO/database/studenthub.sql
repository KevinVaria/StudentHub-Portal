CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;

CREATE TABLE students (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 mobile VARCHAR(15) NOT NULL,
 password_hash VARCHAR(255) NOT NULL,
 course VARCHAR(50) NOT NULL,
 year INT NOT NULL,
 gender VARCHAR(20) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 category VARCHAR(50) NOT NULL,
 event_date DATE NOT NULL,
 location VARCHAR(150) NOT NULL,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registrations (
 id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 event_id INT NOT NULL,
 registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(student_id,event_id),
 FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE,
 FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE
);

INSERT INTO events(title,category,event_date,location,description) VALUES
('Tech Fest 2026','Technology','2026-10-15','Auditorium','Innovation and coding event.'),
('Sports Day','Sports','2026-10-20','Main Ground','Annual college sports meet.'),
('Cultural Night','Cultural','2026-10-25','Open Air Theatre','Music, dance and cultural performances.'),
('Hackathon','Technology','2026-11-02','Lab 3','Build a solution in a team.'),
('Career Seminar','Career','2026-11-08','Seminar Hall','Meet industry speakers.');
