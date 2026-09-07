# Production Audit

Audit date: 2026-09-07
Scope: PHP application, Apache configuration, database layer, authentication, forms, uploads, repository hygiene, and deployment behavior.

## Current status

**NOT READY FOR PRODUCTION until the historical credential is rotated and the manual hosting tests are completed.** No claim of complete penetration-test coverage is made from static review alone.

## Findings

### High

- **Historical default credential text was committed**
  - Location: an earlier Git commit of `database/migrations/run_migration.php` contained the default admin password text.
  - Risk: anyone with repository access could recover the old credential or infer deployment history.
  - Required mitigation: rotate any administrator credential that ever used that password. If the repository is ever made public, rewrite Git history with an approved secret-removal process and force-push coordination.

- **Raw SQL fragments in shared query conditions**
  - Files: `database/database.php`, callers that provide `where` expressions.
  - Risk: the abstraction still accepts raw trusted SQL conditions. Current callers were reviewed and use fixed application expressions, but future user-controlled clauses must never be passed to these methods.
  - Mitigation: table, column, ordering, and limit identifiers are centrally validated; continue using placeholders for every value.

- **Blog editor content policy is not a rich-HTML sanitizer**
  - File: `admin/blog-form.php` and `blog-post.php`.
  - Current display escapes blog content, so stored HTML is shown as text. If rich HTML is enabled later, add a strict allowlist sanitizer before rendering.

### Medium

- **Demo data is included in `database/schema.sql`**
  - Risk: importing the schema without review can place fictional content and external image URLs into production.
  - Required mitigation: review seed rows and create a production-specific export before importing real hosting data.

- **Runtime error log defaults inside the public project tree**
  - File: `config/config.php`.
  - Apache denies the log, but production hosting should preferably set an error log path outside the document root.

- Public diagnostic and test scripts have been removed from the production document root.

### Low / Informational

- `X-XSS-Protection` is retained for older clients but is obsolete in modern browsers.
- External CDN fonts, Tailwind, and image URLs require an outbound network connection and should be pinned or self-hosted if the production threat model requires supply-chain control.
- Localhost values remain only in `.env.example` and local development documentation; production `.env` and `robots.txt` must be configured with the real HTTPS domain.

## Fixes applied in this audit

- Added strict session cookie settings, strict session mode, and production-aware Secure cookies.
- Disabled startup errors and ensured production display errors are off.
- Removed detailed database error output from user-facing responses.
- Added database table, column, ordering, and limit validation.
- Added upload MIME validation, image validation, random filenames, upload checks, and upload path checks.
- Added Apache protection for `.env`, Git metadata, logs, SQL/config artifacts, and executable upload extensions.
- Removed the old default admin-password output.
- Fixed admin password changes to update the authenticated database user and require CSRF.
- Added login failure throttling and session regeneration on successful login.
- Added database-backed rolling login throttling by normalized email and IP address.
- Converted admin state changes to POST-only forms with server-side CSRF and ID validation.
- Removed the message-detail GET status mutation.
- Added CSRF protection to coach profile updates and secure POST logout.
- Fixed blog query limit calls to remain compatible with validated query limits.
- Added `database/production-schema.sql` with schema only and no demo rows or credentials.
- Removed public diagnostic scripts and stale links to them.

## Verification performed

- XAMPP PHP syntax checks passed for changed PHP files.
- Apache returned `200` for the homepage and a clean public route.
- Apache returned `403` for configuration, `.env`, and upload log paths.
- Apache returned `404` for an unknown clean route and the correct canonical URL for `/about`.
- Direct unauthenticated access to `admin/index.php` returned `302` to `login.php`.
- Static audit found no remaining admin mutation endpoints accepting action or CSRF values from GET.
- Static audit found no remaining public diagnostic-script references.
- Repository audit found no committed `.env` or runtime log.

## Remaining quality gate

Before production: rotate any administrator credential affected by the historical exposure, import the login-attempts migration if upgrading an existing database, review schema seed data, set a private production error log, test all forms and uploads on the hosting account, verify HTTPS cookies, and perform a manual authenticated user-flow test with disposable data.
