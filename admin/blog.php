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
            $stmt = $pdo->prepare("UPDATE blog_posts SET status = IF(status='published', 'draft', 'published') WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Blog post status updated successfully.');
        } else if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Blog post deleted successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='blog.php';</script>";
    exit;
}

$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll();
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-container-max mx-auto">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
<div>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Blog Posts</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage your fitness content and articles.</p>
</div>
<a href="blog-form.php" class="bg-primary-container text-black font-label-caps text-label-caps px-6 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.4)] transition-shadow duration-300 flex items-center gap-2 w-fit">
<span class="material-symbols-outlined text-sm">edit</span> New Post
</a>
</div>

<div class="glass-panel rounded-xl overflow-hidden bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/10 bg-surface-container-low/50">
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Post</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Category</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Date</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($posts)): ?>
    <tr><td colspan="5" class="py-5 px-6 text-center text-on-surface-variant">No posts found.</td></tr>
<?php else: foreach($posts as $p): ?>
<tr class="hover:bg-white/5 transition-colors duration-200 group/row">
<td class="py-5 px-6">
<div class="flex items-center gap-4">
    <?php if ($p['image']): ?>
    <img src="<?= htmlspecialchars(admin_media_url($p['image'])) ?>" class="w-12 h-12 rounded object-cover border border-white/10" alt="">
    <?php else: ?>
    <div class="w-12 h-12 rounded bg-surface-container-high border border-white/10 flex items-center justify-center">
        <span class="material-symbols-outlined text-on-surface-variant">article</span>
    </div>
    <?php endif; ?>
    <div>
        <p class="font-medium text-on-surface"><?= htmlspecialchars($p['title']) ?></p>
        <p class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 max-w-[300px]"><?= htmlspecialchars($p['excerpt']) ?></p>
    </div>
</div>
</td>
<td class="py-5 px-6 align-middle">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-surface-bright text-on-surface-variant border border-white/10 uppercase"><?= htmlspecialchars($p['category']) ?></span>
</td>
<td class="py-5 px-6 align-middle font-body-md text-on-surface-variant text-sm"><?= htmlspecialchars($p['date'] ? date('M j, Y', strtotime($p['date'])) : '—') ?></td>
<td class="py-5 px-6 align-middle">
<a href="?action=toggle_status&id=<?= $p['id'] ?>&csrf_token=<?= $csrf_token ?>" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
<?php if ($p['status'] == 'published'): ?>
<div class="w-2 h-2 rounded-full bg-primary-container shadow-[0_0_8px_rgba(34,197,94,0.8)]"></div>
<span class="text-sm text-primary-fixed">Published</span>
<?php else: ?>
<div class="w-2 h-2 rounded-full bg-surface-bright border border-white/20"></div>
<span class="text-sm text-on-surface-variant">Draft</span>
<?php endif; ?>
</a>
</td>
<td class="py-5 px-6 align-middle text-right">
<div class="flex justify-end gap-3 opacity-0 group-hover/row:opacity-100 transition-opacity duration-200">
<a href="blog-form.php?id=<?= $p['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</a>
<a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= $csrf_token ?>" onclick="return confirm('Delete this post?');" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
<span class="material-symbols-outlined text-[20px]">delete</span>
</a>
</div>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

