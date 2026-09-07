<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();

$success_msg = '';
$error_msg   = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password' && verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $current  = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $admin = $pdo->prepare('SELECT password FROM admins WHERE id = :id AND status = \'active\'');
        $admin->execute(['id' => $_SESSION['admin_id'] ?? 0]);
        $admin = $admin->fetch();
        $stored_hash = $admin['password'] ?? '';

        if (!password_verify($current, $stored_hash)) {
            $error_msg = 'Current password is incorrect.';
        } elseif (strlen($new_pass) < 8) {
            $error_msg = 'New password must be at least 8 characters.';
        } elseif ($new_pass !== $confirm) {
            $error_msg = 'New passwords do not match.';
        } else {
            $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
            $update = $pdo->prepare('UPDATE admins SET password = :password WHERE id = :id');
            $update->execute(['password' => $new_hash, 'id' => $_SESSION['admin_id']]);
            $success_msg = 'Password changed successfully.';
        }
    } elseif ($_POST['action'] === 'change_password') {
        $error_msg = 'Invalid request.';
    }
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[800px] mx-auto">
<div class="mb-8">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Settings</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage admin account and site preferences.</p>
</div>

<?php if ($success_msg): ?>
<div class="mb-6 p-4 bg-primary/10 border border-primary/30 rounded-lg text-primary text-sm flex items-start gap-2">
    <span class="material-symbols-outlined mt-0.5">check_circle</span> <?= $success_msg ?>
</div>
<?php elseif ($error_msg): ?>
<div class="mb-6 p-4 bg-error/10 border border-error/30 rounded-lg text-error text-sm flex items-center gap-2">
    <span class="material-symbols-outlined">error</span> <?= htmlspecialchars($error_msg) ?>
</div>
<?php endif; ?>

<!-- Password Change -->
<div class="glass-panel rounded-xl p-6 md:p-8 bg-surface-container/70 backdrop-blur-[30px] border border-white/10 mb-8">
<h3 class="font-headline-md text-xl text-on-surface mb-6 border-b border-white/10 pb-4">Change Password</h3>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="action" value="change_password">
<div class="space-y-5">
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Current Password</label>
        <input type="password" name="current_password" required class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">New Password</label>
        <input type="password" name="new_password" required minlength="8" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Confirm New Password</label>
        <input type="password" name="confirm_password" required minlength="8" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div class="pt-4">
        <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">Update Password</button>
    </div>
</div>
</form>
</div>

<!-- Admin Info panel -->
<div class="glass-panel rounded-xl p-6 md:p-8 bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<h3 class="font-headline-md text-xl text-on-surface mb-4">Session Info</h3>
<div class="space-y-3 text-sm">
    <div class="flex justify-between items-center py-2 border-b border-white/5">
        <span class="text-on-surface-variant font-label-caps uppercase tracking-wider">Admin Username</span>
        <span class="text-on-surface font-medium"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-white/5">
        <span class="text-on-surface-variant font-label-caps uppercase tracking-wider">Session Started</span>
        <span class="text-on-surface font-medium"><?= date('M j, Y g:i A') ?></span>
    </div>
    <div class="flex justify-between items-center py-2">
        <span class="text-on-surface-variant font-label-caps uppercase tracking-wider">PHP Version</span>
        <span class="text-on-surface font-medium"><?= PHP_VERSION ?></span>
    </div>
</div>
<div class="mt-6 pt-4 border-t border-white/10">
    <form method="POST" action="logout.php" class="inline">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-error/20 text-error border border-error/30 rounded font-label-caps uppercase tracking-wider hover:bg-error/30 transition-colors text-sm">
        <span class="material-symbols-outlined text-[18px]">logout</span> Logout
    </button>
    </form>
</div>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

