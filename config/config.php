<?php

$envFile = dirname(__DIR__) . '/.env';
$env = is_file($envFile) ? parse_ini_file($envFile, false, INI_SCANNER_RAW) : [];
$env = is_array($env) ? $env : [];

$envValue = static function ($key, $default = '') use ($env) {
    $value = $env[$key] ?? getenv($key);
    return ($value === false || $value === null || $value === '') ? $default : $value;
};

define('SITE_NAME', 'APEX COACHING');
define('SITE_URL', $envValue('SITE_URL', 'http://localhost/Gym-trainer-portfolio'));
define('SITE_TAGLINE', 'ELITE PERFORMANCE TRAINING');

define('DB_HOST', $envValue('DB_HOST', '127.0.0.1'));
define('DB_PORT', $envValue('DB_PORT', '3306'));
define('DB_NAME', $envValue('DB_NAME', 'elite_fitness'));
define('DB_USER', $envValue('DB_USER', 'root'));
define('DB_PASS', $envValue('DB_PASS'));
define('DB_CHARSET', $envValue('DB_CHARSET', 'utf8mb4'));
define('DB_TYPE', $envValue('DB_TYPE', 'mysql'));

define('ENVIRONMENT', $envValue('APP_ENV', 'development'));

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

define('ASSETS_URL', SITE_URL . '/assets/');

define('COACH_NAME', 'Marcus Vance');
define('CONTACT_EMAIL', 'elite@apexcoaching.com');
define('CONTACT_PHONE', '+1 (555) 867-5309');
define('ADDRESS', '123 Iron Forge Blvd, District 7, NY 10001');

define('TRAINING_HOURS', [
    'Mon-Fri' => '05:00 - 22:00',
    'Sat' => '06:00 - 20:00',
    'Sun' => '08:00 - 16:00'
]);

define('SOCIAL_LINKS', [
    'website' => '#',
    'instagram' => '#',
    'youtube' => '#'
]);

define('NAV_LINKS', [
    'Home' => 'index.php',
    'About' => 'about.php',
    'Services' => 'services.php',
    'Programs' => 'programs.php',
    'Blog' => 'blog.php'
]);

define('FOOTER_LINKS', [
    'Legal' => [
        'Privacy Policy' => 'privacy.php',
        'Terms of Service' => 'terms.php'
    ],
    'Connect' => [
        'FAQ' => 'faq.php',
        'Careers' => 'careers.php'
    ]
]);

date_default_timezone_set($envValue('APP_TIMEZONE', 'America/New_York'));

error_reporting(E_ALL);
ini_set('display_errors', ENVIRONMENT === 'development' ? '1' : '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', 1);
ini_set('error_log', $envValue('APP_LOG_FILE', __DIR__ . '/../uploads/error.log'));

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $envValue('APP_ENV', 'development') === 'production',
        'samesite' => 'Lax',
        'path' => '/'
    ]);
    session_start();
}
