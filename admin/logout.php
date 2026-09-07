<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_post_csrf();
// Unset all session variables
$_SESSION = array();
// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, [
        'expires' => time() - 42000,
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
}
// Destroy the session
session_destroy();
// Redirect to login
header('Location: login.php');
exit;
?>
