# Practical 12 — Event CRUD + Poster Upload
Admin-only add/view/update/delete. Upload validation checks PHP upload status, a 2 MB limit, and server-detected MIME type (`finfo`); the server generates a random filename. The uploads directory blocks PHP execution with `.htaccess` on Apache.
