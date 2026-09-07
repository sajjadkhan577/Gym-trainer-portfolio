# Security Notes

## Security baseline

The application uses PDO prepared statements for values, CSRF tokens for forms and admin actions, `password_hash()`/`password_verify()` for administrator passwords, server-side output escaping, protected upload directories, and Apache rules that deny sensitive files.

## Deployment rules

- Use HTTPS and set `APP_ENV=production`.
- Store credentials only in the server-side `.env` file or a file outside the document root.
- Never commit `.env`, logs, backups, database exports with private data, or uploaded media.
- Use a dedicated database user with only the permissions required by the application.
- Use a unique administrator password and rotate it if it has ever been shared.
- Keep PHP and the hosting platform patched.
- Review logs privately and remove test scripts before production deployment.

## Reporting

Do not publish credentials, client information, or exploit details in a public issue. Preserve the request, URL, timestamp, and server log reference and report the issue privately to the repository owner.
