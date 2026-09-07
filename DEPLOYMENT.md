# Production Deployment

This application is designed for standard Apache shared hosting with PHP and MySQL/MariaDB. It does not require Node.js, Python, Docker, SSH, or root access.

## Requirements

- PHP 8.2 or newer
- Apache 2.4 with `mod_rewrite` and `.htaccess` enabled
- MySQL 5.7+/8.x or MariaDB 10.4+
- PHP extensions: PDO, pdo_mysql, fileinfo, mbstring, JSON, session, and GD or another image metadata provider
- HTTPS certificate enabled for the domain

## Upload

1. Create the database and database user in the hosting control panel.
2. Upload the project files into the domain document root.
3. Do not upload `.env`, `uploads/error.log`, local backups, or the `.git` directory.
4. Copy `.env.example` to `.env` on the server and set production values.
5. Set `SITE_URL` to the HTTPS production URL, for example `https://example.com`.
6. Set `APP_ENV=production` and a strong database password.

## Database

Import `database/schema.sql` into the new database using phpMyAdmin. The file contains demo content intended for initial setup; review and replace it before importing private or real client data. Never import real production data into a public repository.

Run `database/migrations/create_admins_table.sql` after the schema if the `admins` table is not already present. Create the first administrator using a locally generated `password_hash()` value and a unique password. The repository contains no default admin password.

## Permissions and uploads

- `uploads/` and its subdirectories must be writable by PHP, typically `755` or the hosting platform's required equivalent.
- Keep configuration, database, and include directories non-writable by the web process where possible.
- The included upload `.htaccess` blocks executable uploads. Confirm the host honors `.htaccess` and test an attempted `.php` upload.

## HTTPS and PHP settings

Enable HTTPS before using the admin panel. Production sessions are marked Secure when `APP_ENV=production`. Confirm the host has `display_errors=Off`, `display_startup_errors=Off`, and private error logging enabled.

## Post-deployment tests

- Open the homepage, every public navigation route, sitemap, and robots file.
- Confirm `/config/config.php`, `/.env`, `/database/schema.sql`, and `/uploads/error.log` are denied.
- Confirm direct access to `/admin/index.php` redirects to login.
- Test invalid login, successful login, logout, password change, and session regeneration.
- Submit a booking, contact message, and newsletter subscription with safe test data.
- Verify records in the database and review them in the admin panel.
- Test image uploads with JPG, PNG, WEBP, a renamed text file, and a `.php` file.
- Remove test records and rotate any credentials used during testing.
