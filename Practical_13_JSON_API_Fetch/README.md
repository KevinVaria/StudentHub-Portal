# Practical 13 — JSON API + Fetch CRUD
`api/events.php` returns JSON, uses prepared statements, checks login/role, and supports GET (list), POST (add/update), and DELETE. The page uses Fetch API and DOM text nodes to update the table without a full page reload. This demo assumes same-origin requests; add CSRF tokens before production use.


Database schema compatibility: this version matches the existing `studenthub.events` table with `event_id` as the primary key and `venue` as the location column. API responses alias these to `id` and `location` for the existing front-end JavaScript. No database changes are required.
