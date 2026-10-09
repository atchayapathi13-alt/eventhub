# EVENTHUB (core build)
1. Start Apache + MySQL in XAMPP. 2. Copy this folder to `htdocs/eventhub`.
3. phpMyAdmin → Import `database/eventhub.sql` (creates `eventhub_db` + demo data).
4. Edit `config/database.php` if your MySQL user/password differ (or set DB_HOST/DB_NAME/DB_USER/DB_PASS env vars).
5. Open http://localhost/eventhub/ (custom port: http://localhost:8080/eventhub/).
6. Register an account, search, book, view QR tickets, cancel.
Admin: not included in this build. To promote a user: `UPDATE users SET role='admin' WHERE email='you@example.com';` (run in phpMyAdmin; never hardcode admin passwords).
Payments are simulated (demo). QR uses qrcodejs from cdnjs (needs internet).
