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
            $stmt = $pdo->prepare("UPDATE programs SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Program status updated successfully.');
        } else if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM programs WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Program deleted successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='programs.php';</script>";
    exit;
}

$stmt = $pdo->query("SELECT * FROM programs ORDER BY created_at DESC");
$programs = $stmt->fetchAll();
?>

<!-- Main Content Area -->
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<!-- Page Content -->
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-container-max mx-auto">
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
<div>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Training Programs</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage and publish your elite training protocols.</p>
</div>
<a href="program-form.php" class="bg-primary-container text-black font-label-caps text-label-caps px-6 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.4)] transition-shadow duration-300 flex items-center gap-2 w-fit">
<span class="material-symbols-outlined text-sm">add</span>
    New Program
</a>
</div>

<!-- Data Table Canvas (Glassmorphism) -->
<div class="glass-panel rounded-xl overflow-hidden shadow-2xl relative group bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/10 bg-surface-container-low/50">
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Program Name</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Difficulty</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Duration</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Price</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($programs)): ?>
    <tr><td colspan="6" class="py-5 px-6 text-center text-on-surface-variant">No programs found.</td></tr>
<?php else: foreach($programs as $p): ?>
<tr class="hover:bg-white/5 transition-colors duration-200 group/row">
<td class="py-5 px-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded bg-surface-container-high overflow-hidden border border-white/10 flex items-center justify-center">
<?php if ($p['image']): ?>
<img class="w-full h-full object-cover" src="<?= htmlspecialchars(admin_media_url($p['image'])) ?>">
<?php else: ?>
<span class="material-symbols-outlined text-on-surface-variant text-3xl">image_not_supported</span>
<?php endif; ?>
</div>
<div>
<p class="font-body-lg text-body-md md:text-body-lg font-semibold text-on-surface"><?= htmlspecialchars($p['title']) ?></p>
<p class="font-body-md text-sm text-on-surface-variant mt-0.5 line-clamp-1 max-w-[200px]"><?= htmlspecialchars($p['description']) ?></p>
</div>
</div>
</td>
<td class="py-5 px-6 align-middle">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= htmlspecialchars($p['level_color']) ?>">
<?= htmlspecialchars($p['level']) ?>
</span>
</td>
<td class="py-5 px-6 align-middle font-body-md text-on-surface-variant"><?= htmlspecialchars($p['duration']) ?></td>
<td class="py-5 px-6 align-middle font-body-md font-medium text-on-surface">$<?= number_format($p['price'], 2) ?></td>
<td class="py-5 px-6 align-middle">
<a href="?action=toggle_status&id=<?= $p['id'] ?>&csrf_token=<?= $csrf_token ?>" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
<?php if ($p['status'] == 'active'): ?>
<div class="w-2 h-2 rounded-full bg-primary-container shadow-[0_0_8px_rgba(34,197,94,0.8)]"></div>
<span class="font-body-md text-sm text-primary-fixed">Active</span>
<?php else: ?>
<div class="w-2 h-2 rounded-full bg-surface-bright border border-white/20"></div>
<span class="font-body-md text-sm text-on-surface-variant">Inactive</span>
<?php endif; ?>
</a>
</td>
<td class="py-5 px-6 align-middle text-right">
<div class="flex justify-end gap-3 opacity-0 group-hover/row:opacity-100 transition-opacity duration-200">
<a href="program-form.php?id=<?= $p['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</a>
<a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= $csrf_token ?>" onclick="return confirm('Are you sure you want to delete this program?');" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
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

