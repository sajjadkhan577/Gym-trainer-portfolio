<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!verify_csrf_token($_GET['csrf_token'] ?? '')) {
        set_toast_message('error', 'Invalid security token. Please try again.');
    } else {
        $id = (int)$_GET['id'];
        $action = $_GET['action'];
        $allowed_statuses = ['read', 'replied', 'archived'];
        
        if (in_array($action, $allowed_statuses)) {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $action, ':id' => $id]);
            set_toast_message('success', 'Message status updated successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='messages.php';</script>";
    exit;
}

// Fetch Messages
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_records = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$total_pages = ceil($total_records / $limit);

$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!-- Main Content Area -->
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<!-- Page Content -->
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-container-max mx-auto">
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
<div>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Messages</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage client inquiries and contact forms.</p>
</div>
</div>

<div class="glass-panel rounded-xl overflow-hidden shadow-2xl relative group bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/10 bg-surface-container-low/50">
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Sender</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Subject</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Date</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="py-4 px-6 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($messages)): ?>
    <tr><td colspan="5" class="py-5 px-6 text-center text-on-surface-variant">No messages found.</td></tr>
<?php else: foreach($messages as $m): ?>
<tr class="hover:bg-white/5 transition-colors duration-200 group/row <?= $m['status'] == 'new' ? 'bg-white/5' : '' ?>">
<td class="py-5 px-6">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high flex items-center justify-center text-primary font-bold text-lg">
<?= htmlspecialchars(substr($m['name'], 0, 1)) ?>
</div>
<div>
<p class="font-body-md font-semibold <?= $m['status'] == 'new' ? 'text-white' : 'text-on-surface' ?>"><?= htmlspecialchars($m['name']) ?></p>
<p class="font-body-md text-xs text-on-surface-variant mt-0.5"><?= htmlspecialchars($m['email']) ?></p>
</div>
</div>
</td>
<td class="py-5 px-6 align-middle font-body-md <?= $m['status'] == 'new' ? 'text-white font-medium' : 'text-on-surface-variant' ?>"><?= htmlspecialchars($m['subject']) ?></td>
<td class="py-5 px-6 align-middle font-body-md text-on-surface-variant"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($m['created_at']))) ?></td>
<td class="py-5 px-6 align-middle">
<?php
$statusClass = 'bg-surface-variant text-on-surface-variant border-white/10';
if ($m['status'] == 'new') $statusClass = 'bg-primary-container/10 text-primary border-primary-container/30';
if ($m['status'] == 'read') $statusClass = 'bg-blue-500/10 text-blue-400 border-blue-500/30';
if ($m['status'] == 'replied') $statusClass = 'bg-tertiary-container/10 text-tertiary-fixed border-tertiary-container/20';
?>
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border uppercase tracking-wider <?= $statusClass ?>">
<?= htmlspecialchars($m['status']) ?>
</span>
</td>
<td class="py-5 px-6 align-middle text-right">
<div class="flex justify-end gap-3 opacity-0 group-hover/row:opacity-100 transition-opacity duration-200">
<?php if ($m['status'] == 'new'): ?>
<a href="?action=read&id=<?= $m['id'] ?>&csrf_token=<?= $csrf_token ?>" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Mark Read">
<span class="material-symbols-outlined text-[20px]">mark_email_read</span>
</a>
<?php endif; ?>
<a href="message-details.php?id=<?= $m['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="View">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</a>
<a href="?action=archived&id=<?= $m['id'] ?>&csrf_token=<?= $csrf_token ?>" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Archive">
<span class="material-symbols-outlined text-[20px]">archive</span>
</a>
</div>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<div class="px-6 py-4 border-t border-white/10 flex items-center justify-between bg-surface-container-low/30">
<span class="font-body-md text-sm text-on-surface-variant">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $total_records) ?> of <?= $total_records ?> messages</span>
<div class="flex gap-2">
<a href="?page=<?= max(1, $page - 1) ?>" class="px-3 py-1 rounded border border-white/10 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors <?= $page <= 1 ? 'pointer-events-none opacity-50' : '' ?>">Prev</a>
<a href="?page=<?= min($total_pages, $page + 1) ?>" class="px-3 py-1 rounded border border-white/10 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors <?= $page >= $total_pages ? 'pointer-events-none opacity-50' : '' ?>">Next</a>
</div>
</div>
<?php endif; ?>

</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

