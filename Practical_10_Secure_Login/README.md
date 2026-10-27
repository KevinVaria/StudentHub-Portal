# Practical 10 — Secure Login/Logout

Uses `password_verify()`, session ID regeneration, a 30-minute inactivity timeout, and role-based redirects. Login uses email and the shared `students` table. Promote an account only after registration: `UPDATE students SET role='admin' WHERE email='your-email@example.com';`
