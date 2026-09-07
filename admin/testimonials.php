<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();

// Handle status updates or deletes with CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_toast_message('error', 'Invalid security token. Please try again.');
    } else {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $action = $_POST['action'];
        if (!$id || !in_array($action, ['toggle_status', 'delete'], true)) {
            set_toast_message('error', 'Invalid request.');
            header('Location: testimonials.php');
            exit;
        }
        
        if ($action == 'toggle_status') {
            $stmt = $pdo->prepare("UPDATE testimonials SET status = IF(status='active', 'inactive', 'active') WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Testimonial status updated successfully.');
        } else if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_toast_message('success', 'Testimonial deleted successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='testimonials.php';</script>";
    exit;
}

$items = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-container-max mx-auto">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
<div>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Testimonials</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage client reviews and success quotes.</p>
</div>
<a href="testimonial-form.php" class="bg-primary-container text-black font-label-caps text-label-caps px-6 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.4)] transition-shadow duration-300 flex items-center gap-2 w-fit">
<span class="material-symbols-outlined text-sm">add</span> Add Testimonial
</a>
</div>

<div class="glass-panel rounded-xl overflow-hidden bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/10 bg-surface-container-low/50">
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Author</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Quote</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Rating</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($items)): ?>
    <tr><td colspan="5" class="py-5 px-6 text-center text-on-surface-variant">No testimonials found.</td></tr>
<?php else: foreach($items as $t): ?>
<tr class="hover:bg-white/5 transition-colors duration-200 group/row">
<td class="py-5 px-6">
<div class="flex items-center gap-3">
    <?php if ($t['author_image']): ?>
    <img src="<?= htmlspecialchars(admin_media_url($t['author_image'])) ?>" class="w-10 h-10 rounded-full object-cover border border-white/10" alt="">
    <?php else: ?>
    <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary font-bold">
        <?= htmlspecialchars(substr($t['author_name'], 0, 1)) ?>
    </div>
    <?php endif; ?>
    <div>
        <p class="font-medium text-on-surface"><?= htmlspecialchars($t['author_name']) ?></p>
        <p class="text-xs text-on-surface-variant"><?= htmlspecialchars($t['author_role']) ?></p>
    </div>
</div>
</td>
<td class="py-5 px-6 align-middle text-on-surface-variant text-sm max-w-xs">
    <p class="line-clamp-2"><?= htmlspecialchars($t['quote']) ?></p>
</td>
<td class="py-5 px-6 align-middle">
    <div class="flex gap-0.5 text-yellow-400 text-sm">
        <?php for($i = 1; $i <= 5; $i++): ?>
        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' <?= $i <= $t['rating'] ? '1' : '0' ?>">star</span>
        <?php endfor; ?>
    </div>
</td>
<td class="py-5 px-6 align-middle">
<form method="POST" class="inline"><input type="hidden" name="action" value="toggle_status"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
<?php if ($t['status'] == 'active'): ?>
<div class="w-2 h-2 rounded-full bg-primary-container shadow-[0_0_8px_rgba(34,197,94,0.8)]"></div>
<span class="text-sm text-primary-fixed">Active</span>
<?php else: ?>
<div class="w-2 h-2 rounded-full bg-surface-bright border border-white/20"></div>
<span class="text-sm text-on-surface-variant">Inactive</span>
<?php endif; ?>
</button></form>
</td>
<td class="py-5 px-6 align-middle text-right">
<div class="flex justify-end gap-3 opacity-0 group-hover/row:opacity-100 transition-opacity duration-200">
<a href="testimonial-form.php?id=<?= $t['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</a>
<form method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
<span class="material-symbols-outlined text-[20px]">delete</span>
</button></form>
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

