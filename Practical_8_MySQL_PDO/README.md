# StudentHub - Practical 8
MySQL schema + ER model + PHP PDO + prepared statements.

## Requirements
- XAMPP/WAMP/Laragon
- Apache
- MySQL

## Setup
1. Start Apache and MySQL.
2. Open phpMyAdmin.
3. Import `database/studenthub.sql`.
4. Put this folder in your web root.
5. Edit `config.php` if your MySQL username/password differs.
6. Open `http://localhost/Practical_8_MySQL_PDO/`

The registration form writes to the `students` table using PDO prepared statements.
Events are loaded from MySQL through `api/events.php`.
