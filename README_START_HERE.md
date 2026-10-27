# StudentHub — Practicals 3–14

This ZIP contains the StudentHub practical folders from Practical 3 through Practical 14, with the Practical 9–14 PHP/MySQLi work aligned to the existing `students.student_id` schema and admin roles.

## Important before running
1. Back up your existing `studenthub` database and project folder.
2. For a **new empty database**, import `database/studenthub.sql` through phpMyAdmin.
3. If you already have the Practical 8 database and want to preserve your records, **do not import the fresh schema over it**. Inspect `database/upgrade_existing.sql` and run only the statements for columns/tables that do not already exist. In particular, add `students.role`, `events.poster`, and `audit_logs` only if missing.
4. Edit root `config.php` if your MySQL host/user/password differs from the default XAMPP settings.
5. Place the `StudentHub_Practicals` folder inside `htdocs` (or your PHP server's web root).
6. Register a normal account at `Practical_9_Secure_Registration/register.php`.
7. In phpMyAdmin, promote your own account using: `UPDATE students SET role='admin' WHERE email='your-email@example.com';` Replace the email. Do not make all users admins.
8. Sign in at `Practical_10_Secure_Login/login.php`. Students go to the user dashboard; admins go to Practical 14.

## Folder overview
- Practical 3: responsive CSS pages
- Practical 4: JavaScript interactivity
- Practical 5: registration validation
- Practical 6: Fetch JSON search/filter demo
- Practical 7: PHP CSV/JSON registration exercise
- Practical 8: PDO registration/database exercise (kept separate)
- Practical 9: secure registration using password hashing
- Practical 10: secure login/logout, sessions, role redirects
- Practical 11: admin-only student CRUD
- Practical 12: admin-only event CRUD and poster upload
- Practical 13: JSON API and Fetch-based event CRUD
- Practical 14: admin dashboard and audit log

## Existing database compatibility
The shared PHP practicals 9–14 use the existing `students` columns `student_id`, `name`, `email`, `mobile`, `password_hash`, `course`, `year`, `gender`, `created_at`, plus `role`. Event records use `events.id`. This avoids replacing the student table or renaming your existing student IDs.

The Practical 8 PDO folder is intentionally separate and its original files are preserved for that practical. Its standalone SQL file may describe a different schema than an already existing database, so follow your instructor's existing database and the upgrade notes above.

## Security note
This is a classroom practical starter. Use a unique admin password and keep your local development server private. Before public deployment, add CSRF protection to all state-changing forms and API requests, stricter session cookie settings, and production-grade error logging.
