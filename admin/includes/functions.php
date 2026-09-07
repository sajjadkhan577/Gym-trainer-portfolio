<?php
function get_db_connection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    return $pdo;
}

function require_login() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

function require_post_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Invalid request.');
    }
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function set_toast_message($type, $message) {
    $_SESSION['toast'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_toast_message() {
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']);
        return $toast;
    }
    return null;
}

function display_toast_message() {
    $toast = get_toast_message();
    if ($toast) {
        $bgColor = $toast['type'] === 'success' ? 'bg-primary/10 border-primary/30 text-primary' : 
                   ($toast['type'] === 'error' ? 'bg-error/10 border-error/30 text-error' : 'bg-surface-container/10 border-white/20 text-on-surface');
        echo '<div class="fixed top-20 right-4 z-50 glass-panel rounded-lg p-4 border ' . $bgColor . ' flex items-center gap-3 shadow-lg animate-slide-in">';
        echo '<span class="material-symbols-outlined">' . ($toast['type'] === 'success' ? 'check_circle' : ($toast['type'] === 'error' ? 'error' : 'info')) . '</span>';
        echo '<span class="font-body-md">' . htmlspecialchars($toast['message']) . '</span>';
        echo '</div>';
    }
}

function format_date($datetime) {
    return date('M d, Y h:i A', strtotime($datetime));
}

function upload_image($file, $target_dir) {
    $allowed_types = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        return ['success' => false, 'error' => 'Upload failed with error code ' . $code];
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'error' => 'Invalid upload.'];
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed_types[$mime])) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and WEBP are allowed.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File size exceeds the 5MB limit.'];
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false || $imageInfo[0] < 1 || $imageInfo[1] < 1) {
        return ['success' => false, 'error' => 'Uploaded file is not a valid image.'];
    }

    $ext = $allowed_types[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;

    $uploadsRoot = realpath(__DIR__ . '/../../uploads');
    $resolvedTarget = realpath($target_dir);
    if ($uploadsRoot === false || ($resolvedTarget !== false && strpos($resolvedTarget, $uploadsRoot) !== 0)) {
        return ['success' => false, 'error' => 'Invalid upload directory.'];
    }
    
    // Ensure dir exists
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            return ['success' => false, 'error' => 'Failed to create upload directory.'];
        }
    }
    
    $target_file = rtrim($target_dir, '/') . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $filename];
    }

    return ['success' => false, 'error' => 'Failed to save uploaded file.'];
}

function admin_media_url($path) {
    if (empty($path)) {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
}

function delete_local_media($path) {
    if (empty($path) || preg_match('#^https?://#i', $path)) {
        return;
    }
    $file = realpath(__DIR__ . '/../../' . ltrim($path, '/'));
    $uploads = realpath(__DIR__ . '/../../uploads');
    if ($file && $uploads && strpos($file, $uploads . DIRECTORY_SEPARATOR) === 0 && is_file($file)) {
        @unlink($file);
    }
}
?>
