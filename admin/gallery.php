<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();

// Handle status updates or deletes with CSRF protection
if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!verify_csrf_token($_GET['csrf_token'] ?? '')) {
        set_toast_message('error', 'Invalid security token. Please try again.');
    } else {
        $id = (int)$_GET['id'];
        $action = $_GET['action'];
        
        if ($action == 'toggle_status') {
            $stmt = $pdo->prepare("UPDATE gallery SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Gallery item status updated successfully.');
        } else if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Gallery item deleted successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$items = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-container-max mx-auto">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
<div>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Gallery</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage your photo and video gallery.</p>
</div>
<a href="gallery-form.php" class="bg-primary-container text-black font-label-caps text-label-caps px-6 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.4)] transition-shadow duration-300 flex items-center gap-2 w-fit">
<span class="material-symbols-outlined text-sm">add</span> Add Media
</a>
</div>

<!-- Responsive Card Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
<?php if (empty($items)): ?>
<p class="col-span-4 text-center text-on-surface-variant py-12">No gallery items found.</p>
<?php else: foreach($items as $item): ?>
<div class="group relative glass-panel rounded-xl overflow-hidden border border-white/10">
    <div class="relative aspect-square overflow-hidden bg-surface-container">
        <img src="<?= htmlspecialchars(admin_media_url($item['image_url'])) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <?php if ($item['is_video']): ?>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-4xl drop-shadow-lg" style="font-variation-settings:'FILL' 1;">play_circle</span>
        </div>
        <?php endif; ?>
        <!-- Overlay on hover -->
        <div class="absolute inset-0 bg-surface/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
            <a href="gallery-form.php?id=<?= $item['id'] ?>" class="p-2 rounded-full bg-white/10 hover:bg-primary/20 text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined">edit</span>
            </a>
            <a href="?action=delete&id=<?= $item['id'] ?>&csrf_token=<?= $csrf_token ?>" onclick="return confirm('Delete this item?');" class="p-2 rounded-full bg-white/10 hover:bg-error/20 text-white hover:text-error transition-colors">
                <span class="material-symbols-outlined">delete</span>
            </a>
        </div>
    </div>
    <div class="p-3">
        <div class="flex justify-between items-center">
            <span class="text-xs text-on-surface-variant uppercase tracking-wider font-label-caps"><?= htmlspecialchars($item['category']) ?></span>
            <a href="?action=toggle_status&id=<?= $item['id'] ?>&csrf_token=<?= $csrf_token ?>" class="text-xs <?= $item['status'] == 'active' ? 'text-primary' : 'text-on-surface-variant' ?> hover:opacity-70 transition-opacity">
                <?= $item['status'] == 'active' ? '● Active' : '○ Inactive' ?>
            </a>
        </div>
        <p class="text-on-surface text-sm font-medium mt-1 truncate"><?= htmlspecialchars($item['title']) ?></p>
    </div>
</div>
<?php endforeach; endif; ?>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

