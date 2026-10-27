-- BACK UP your database first. Run these statements only if the named columns/tables do not already exist.
-- This is for the existing Practical 8 database where students already uses student_id.
ALTER TABLE students ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'student';
ALTER TABLE events ADD COLUMN poster VARCHAR(255) NULL;
CREATE TABLE IF NOT EXISTS audit_logs (
 id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NULL,
 action VARCHAR(80) NOT NULL,
 details VARCHAR(255) NOT NULL DEFAULT '',
 ip_address VARCHAR(45) NOT NULL DEFAULT '',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_audit_created (created_at)
) ENGINE=InnoDB;
-- Promote your own registered account after checking the email:
-- UPDATE students SET role='admin' WHERE email='your-email@example.com';
