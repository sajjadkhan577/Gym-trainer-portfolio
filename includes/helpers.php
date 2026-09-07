<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/database.php';

if (!function_exists('site_url')) {
    function site_url($path = '') {
        return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        return rtrim(ASSETS_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_url')) {
    function upload_url($path = '') {
        return rtrim(UPLOAD_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('is_active_page')) {
    function is_active_page($page) {
        $currentFile = basename($_SERVER['PHP_SELF']);
        return $currentFile === $page;
    }
}

if (!function_exists('set_flash')) {
    function set_flash($key, $message, $type = 'info') {
        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }
}

if (!function_exists('has_flash')) {
    function has_flash($key) {
        return isset($_SESSION['flash'][$key]);
    }
}

if (!function_exists('get_flash')) {
    function get_flash($key) {
        if (has_flash($key)) {
            $flash = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $flash;
        }
        return null;
    }
}

if (!function_exists('slugify')) {
    function slugify($text, $divider = '-') {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'F d, Y') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('truncate')) {
    function truncate($text, $chars = 100, $append = '...') {
        if (strlen($text) <= $chars) return $text;
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        return $text . $append;
    }
}

if (!function_exists('is_post_request')) {
    function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

if (!function_exists('is_get_request')) {
    function is_get_request() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 5242880) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No file uploaded or upload error.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_types)) {
            return ['success' => false, 'message' => 'Invalid file type.'];
        }

        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'File too large.'];
        }

        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destination = UPLOAD_DIR . $filename;

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $filename, 'url' => upload_url($filename)];
        }

        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}

if (!function_exists('db')) {
    function db() {
        return Database::getInstance();
    }
}

if (!function_exists('db_select')) {
    function db_select($table, $columns = '*', $where = '', $params = [], $orderBy = '', $limit = '') {
        return db()->select($table, $columns, $where, $params, $orderBy, $limit);
    }
}

if (!function_exists('db_insert')) {
    function db_insert($table, $data) {
        return db()->insert($table, $data);
    }
}

if (!function_exists('db_update')) {
    function db_update($table, $data, $where, $whereParams = []) {
        return db()->update($table, $data, $where, $whereParams);
    }
}

if (!function_exists('db_delete')) {
    function db_delete($table, $where, $whereParams = []) {
        return db()->delete($table, $where, $whereParams);
    }
}

if (!function_exists('db_count')) {
    function db_count($table, $where = '', $params = []) {
        return db()->count($table, $where, $params);
    }
}

if (!function_exists('db_paginate')) {
    function db_paginate($table, $page = 1, $perPage = 10, $columns = '*', $where = '', $params = [], $orderBy = '') {
        return db()->paginate($table, $page, $perPage, $columns, $where, $params, $orderBy);
    }
}

if (!function_exists('db_filter')) {
    function db_filter($table, $filters = [], $columns = '*', $orderBy = '', $limit = '') {
        return db()->filter($table, $filters, $columns, $orderBy, $limit);
    }
}

if (!function_exists('db_search')) {
    function db_search($table, $columns, $searchTerm, $where = '', $params = [], $orderBy = '', $limit = '') {
        return db()->search($table, $columns, $searchTerm, $where, $params, $orderBy, $limit);
    }
}

if (!function_exists('db_query')) {
    function db_query($sql, $params = []) {
        return db()->query($sql, $params);
    }
}

if (!function_exists('db_fetch_all')) {
    function db_fetch_all($sql, $params = []) {
        return db()->fetchAll($sql, $params);
    }
}

if (!function_exists('db_fetch_one')) {
    function db_fetch_one($sql, $params = []) {
        return db()->fetchOne($sql, $params);
    }
}

if (!function_exists('db_transaction')) {
    function db_transaction($callback) {
        return db()->executeTransaction($callback);
    }
}

// CSRF Protection Functions
if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        $token = generate_csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
    }
}

// Enhanced XSS Protection
if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        if (is_array($data)) {
            return array_map('sanitize_input', $data);
        }
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return $data;
    }
}

// Enhanced Content Security Policy
if (!function_exists('set_security_headers')) {
    function set_security_headers() {
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';");
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

// Error Handling
if (!function_exists('handle_error')) {
    function handle_error($errno, $errstr, $errfile, $errline) {
        if (ENVIRONMENT === 'development') {
            echo "<div style='background: #fff3cd; color: #856404; padding: 15px; margin: 20px; border: 1px solid #ffeeba; border-radius: 4px;'>";
            echo "<strong>Error:</strong> [$errno] $errstr<br>";
            echo "<strong>File:</strong> $errfile<br>";
            echo "<strong>Line:</strong> $errline";
            echo "</div>";
        } else {
            error_log("Error: [$errno] $errstr in $errfile on line $errline");
        }
        return false;
    }
}

if (!function_exists('handle_exception')) {
    function handle_exception($exception) {
        if (ENVIRONMENT === 'development') {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
            echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
            echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
            echo "<strong>Line:</strong> " . $exception->getLine();
            echo "</div>";
        } else {
            error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
            redirect('500.php');
        }
    }
}

// Set error handlers
if (ENVIRONMENT === 'development') {
    set_error_handler('handle_error');
    set_exception_handler('handle_exception');
}
