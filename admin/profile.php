<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();

// Load coach info (always id=1)
$coach = $pdo->query("SELECT * FROM coach_info WHERE id = 1")->fetch();
if (!$coach) {
    // Create default row if missing
    $pdo->query("INSERT INTO coach_info (name, title, bio, tagline) VALUES ('Coach Name', 'Elite Performance Coach', 'Your bio here.', 'Your tagline here.')");
    $coach = $pdo->query("SELECT * FROM coach_info WHERE id = 1")->fetch();
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    }
    if (isset($error)) {
        $success = false;
    } else {
    $profile_image = $coach['profile_image'] ?? '';
    $featured_image = $coach['featured_image'] ?? '';
    if (!empty($_FILES['profile_image']['name'])) {
        $result = upload_image($_FILES['profile_image'], __DIR__ . '/../uploads/profile/');
        if ($result['success']) {
            delete_local_media($profile_image);
            $profile_image = 'uploads/profile/' . $result['filename'];
        } else {
            $error = $result['error'];
        }
    }
    if (!empty($_FILES['featured_image']['name'])) {
        $result = upload_image($_FILES['featured_image'], __DIR__ . '/../uploads/profile/');
        if ($result['success']) {
            delete_local_media($featured_image);
            $featured_image = 'uploads/profile/' . $result['filename'];
        } else {
            $error = $result['error'];
        }
    }
    if (isset($error)) {
        $success = false;
    } else {
    $stmt = $pdo->prepare("UPDATE coach_info SET name=?, title=?, bio=?, tagline=?, profile_image=?, featured_image=?, experience_years=? WHERE id=1");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['title']),
        trim($_POST['bio']),
        trim($_POST['tagline']),
        $profile_image,
        $featured_image,
        (int)$_POST['experience_years'],
    ]);
    $success = true;
    $coach = $pdo->query("SELECT * FROM coach_info WHERE id = 1")->fetch();
    }
    }
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[900px] mx-auto">
<div class="mb-8">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Coach Profile</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Update your public-facing coach information.</p>
</div>

<?php if ($success): ?>
<div class="mb-6 p-4 bg-primary/10 border border-primary/30 rounded-lg text-primary font-label-caps uppercase tracking-wider text-sm flex items-center gap-2">
    <span class="material-symbols-outlined">check_circle</span> Profile updated successfully!
</div>
<?php endif; ?>
<?php if (isset($error)): ?>
<div class="mb-6 p-4 bg-error/10 border border-error/30 rounded-lg text-error text-sm"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="glass-panel rounded-xl p-6 md:p-8 bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
<div class="space-y-6">
    <div class="flex items-center gap-6 pb-6 border-b border-white/10">
        <?php if ($coach['profile_image']): ?>
        <img src="<?= htmlspecialchars(admin_media_url($coach['profile_image'])) ?>" class="w-20 h-20 rounded-full object-cover border-2 border-primary" alt="Profile">
        <?php else: ?>
        <div class="w-20 h-20 rounded-full bg-surface-container-high border-2 border-white/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant">person</span>
        </div>
        <?php endif; ?>
        <div>
            <p class="text-on-surface font-bold text-lg"><?= htmlspecialchars($coach['name']) ?></p>
            <p class="text-on-surface-variant text-sm"><?= htmlspecialchars($coach['title']) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Full Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($coach['name']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Title / Role</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($coach['title']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
    </div>

    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Tagline</label>
        <input type="text" name="tagline" value="<?= htmlspecialchars($coach['tagline']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Your coaching philosophy in one line">
    </div>

    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Bio</label>
        <textarea name="bio" rows="5" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($coach['bio']) ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Profile Image</label>
            <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Featured / Hero Image</label>
            <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
        </div>
    </div>
    <p class="text-on-surface-variant/70 text-xs">JPG, PNG, WEBP up to 5MB. Leave empty to keep the current image.</p>

    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Years of Experience</label>
        <input type="number" name="experience_years" value="<?= (int)($coach['experience_years'] ?? 0) ?>" class="w-full md:w-32 bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>

    <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
        <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">Save Profile</button>
    </div>
</div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

