# Production Audit

Audit date: 2026-09-07
Scope: PHP application, Apache configuration, database layer, authentication, forms, uploads, repository hygiene, and deployment behavior.

## Current status

**NOT READY FOR PRODUCTION until the remaining items in this report are completed and tested on the target host.** The application is suitable for continued staging validation. No claim of complete penetration-test coverage is made from static review alone.

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

- **State-changing admin actions use GET requests**
  - Files: `admin/blog.php`, `admin/bookings.php`, `admin/gallery.php`, `admin/messages.php`, `admin/programs.php`, `admin/services.php`, `admin/testimonials.php`, `admin/transformations.php`.
  - Risk: CSRF tokens are checked, but GET actions can be triggered by links, crawlers, browser prefetch, or leaked URLs.
  - Required mitigation: convert status, delete, and logout actions to POST forms and reject non-POST state changes.

- **Blog editor content policy is not a rich-HTML sanitizer**
  - File: `admin/blog-form.php` and `blog-post.php`.
  - Current display escapes blog content, so stored HTML is shown as text. If rich HTML is enabled later, add a strict allowlist sanitizer before rendering.

### Medium

- **Admin login throttling is session-based**
  - File: `admin/login.php`.
  - Risk: an attacker can bypass the counter with new sessions or IPs.
  - Required mitigation: add a database or hosting-level IP/email rate limiter before public launch.

- **Demo data is included in `database/schema.sql`**
  - Risk: importing the schema without review can place fictional content and external image URLs into production.
  - Required mitigation: review seed rows and create a production-specific export before importing real hosting data.

- **Runtime error log defaults inside the public project tree**
  - File: `config/config.php`.
  - Apache denies the log, but production hosting should preferably set an error log path outside the document root.

- **Public diagnostic/test scripts remain in the source tree**
  - Files include `database_test.php`, `page_audit.php`, `comprehensive_test.php`, and `seo_test.php`.
  - Required mitigation: remove them from the deployed document root or protect them with administrator-only access.

### Low / Informational

- `X-XSS-Protection` is retained for older clients but is obsolete in modern browsers.
- External CDN fonts, Tailwind, and image URLs require an outbound network connection and should be pinned or self-hosted if the production threat model requires supply-chain control.
- The default local URL remains in `.env.example` and development documentation by design; production `.env` must override it.

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
- Added CSRF protection to coach profile updates and secure POST logout.
- Fixed blog query limit calls to remain compatible with validated query limits.
- Added `database/production-schema.sql` with schema only and no demo rows or credentials.

## Verification performed

- XAMPP PHP syntax checks passed for changed PHP files.
- Apache returned `200` for the homepage and a clean public route.
- Apache returned `403` for configuration, `.env`, and upload log paths.
- Apache returned `404` for an unknown clean route and the correct canonical URL for `/about`.
- Repository audit found no committed `.env` or runtime log.

## Remaining quality gate

Before production: convert GET mutations to POST, remove/protect diagnostic scripts, review schema seed data, set a private production error log, test all forms and uploads on the hosting account, verify HTTPS cookies, and perform a manual authenticated user-flow test with disposable data.
