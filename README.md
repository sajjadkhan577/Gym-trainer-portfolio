# Apex Coaching Portfolio

A PHP and MariaDB portfolio website for Apex Coaching. The application includes a public coaching website, content-managed services and programs, testimonials, transformations, blog posts, bookings, contact messages, and a protected admin area.

## Features

- Responsive public portfolio for a fitness coach
- Services, training programs, gallery, blog, testimonials, and transformations
- Booking and contact forms with CSRF protection
- Admin dashboard for managing site content
- MySQL/MariaDB persistence through PDO
- SEO-friendly routes, metadata, sitemap, and custom error pages
- Upload protection and reusable PHP components

## Requirements

- Apache 2.4 with `mod_rewrite` enabled
- PHP 8.0 or newer with PDO MySQL enabled
- MySQL or MariaDB
- XAMPP, or an equivalent PHP development environment

## Local Setup

1. Clone the repository into your web server document root.

   ```text
   C:\xampp\htdocs\Gym-trainer-portfolio
   ```

2. Copy `.env.example` to `.env` and update the values for your machine.

3. Create the database named in `DB_NAME`.

4. Import `database/schema.sql` into that database.

5. Run the application through Apache:

   ```text
   http://localhost/Gym-trainer-portfolio/
   ```

6. Open `/admin/login.php` to access the admin area after creating an administrator in the `admins` table. Do not use a shared or default password.

## Configuration

Runtime configuration is read from `.env`. The committed `.env.example` contains safe development placeholders only. Never commit `.env`, passwords, database dumps, logs, or uploaded user content.

Important settings:

- `SITE_URL`: public base URL for generated links
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`: database connection
- `APP_ENV`: `development` or `production`
- `APP_TIMEZONE`: PHP application timezone

## Project Structure

```text
.
├── admin/                  Admin pages and dashboard
├── api/                    Small HTTP endpoints
├── assets/                 CSS, JavaScript, and static assets
├── config/                 Application configuration
├── database/               Schema, setup scripts, and migrations
├── includes/               Shared layout, helpers, and UI components
├── uploads/                Runtime media and logs (ignored by Git)
├── *.php                   Public page controllers and entry points
├── .env.example            Environment configuration template
├── .htaccess               Apache routing and security rules
└── README.md               Project documentation
```

The `*_*/code.html` directories are retained as design references and are not part of the PHP runtime.

## Development Notes

- Keep secrets and environment-specific values in `.env`.
- Keep uploaded media and logs out of version control.
- Use prepared statements through the database helper layer.
- Use the existing CSRF helpers for all state-changing forms.
- Test clean URLs and form submissions through Apache, not only through a PHP file preview.
- Read [DEPLOYMENT.md](DEPLOYMENT.md) before uploading to shared hosting.
- Review [SECURITY.md](SECURITY.md) and [PRODUCTION_AUDIT.md](PRODUCTION_AUDIT.md) before production use.

## License

This project is private and intended for the repository owner unless a separate license is added.
