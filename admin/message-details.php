<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo "<script>window.location.href='messages.php';</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = :id");
$stmt->execute([':id' => $id]);
$message = $stmt->fetch();

if (!$message) {
    echo "<script>window.location.href='messages.php';</script>";
    exit;
}

?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 px-4 md:px-margin-desktop pb-12 w-full max-w-[800px] mx-auto">
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
<div>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Message Details</h1>
<p class="text-on-surface-variant font-body-lg text-body-lg">View inquiry from <?= htmlspecialchars($message['name']) ?>.</p>
</div>
<div class="flex gap-4">
<a href="messages.php" class="btn-ghost px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
    BACK TO LIST
</a>
</div>
</div>

<div class="glass-panel rounded-lg p-6 md:p-8">
    <div class="flex justify-between items-start border-b border-white/10 pb-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full overflow-hidden bg-surface-container-high flex items-center justify-center text-primary font-bold text-2xl">
                <?= htmlspecialchars(substr($message['name'], 0, 1)) ?>
            </div>
            <div>
                <h3 class="font-headline-md text-xl text-on-surface"><?= htmlspecialchars($message['name']) ?></h3>
                <div class="text-on-surface-variant text-sm flex items-center gap-4 mt-1">
                    <span><a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="hover:text-primary"><?= htmlspecialchars($message['email']) ?></a></span>
                    <?php if($message['phone']): ?>
                        <span>•</span>
                        <span><a href="tel:<?= htmlspecialchars($message['phone']) ?>" class="hover:text-primary"><?= htmlspecialchars($message['phone']) ?></a></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-on-surface-variant text-sm mb-2"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($message['created_at']))) ?></div>
            <?php
            $statusClass = 'bg-surface-variant text-on-surface-variant border-white/10';
            if ($message['status'] == 'read') $statusClass = 'bg-blue-500/10 text-blue-400 border-blue-500/30';
            if ($message['status'] == 'replied') $statusClass = 'bg-tertiary-container/10 text-tertiary-fixed border-tertiary-container/20';
            ?>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border uppercase tracking-wider <?= $statusClass ?>">
            <?= htmlspecialchars($message['status']) ?>
            </span>
        </div>
    </div>
    
    <div class="space-y-4">
        <div>
            <span class="text-on-surface-variant text-sm block uppercase tracking-wider mb-2">Subject</span>
            <h4 class="text-on-surface font-medium text-lg"><?= htmlspecialchars($message['subject']) ?></h4>
        </div>
        <div>
            <span class="text-on-surface-variant text-sm block uppercase tracking-wider mb-2">Message</span>
            <div class="text-on-surface bg-surface-container-low p-6 rounded-lg leading-relaxed whitespace-pre-wrap font-body-md"><?= htmlspecialchars($message['message']) ?></div>
        </div>
    </div>
    
    <div class="mt-8 flex gap-4 pt-6 border-t border-white/10">
        <a href="mailto:<?= htmlspecialchars($message['email']) ?>" target="_blank" class="btn-primary px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">reply</span> Reply via Email
        </a>
        <?php if ($message['status'] != 'replied'): ?>
        <form method="POST" action="messages.php" class="inline"><input type="hidden" name="action" value="replied"><input type="hidden" name="id" value="<?= (int)$message['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="btn-ghost px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps">
            Mark as Replied
        </button></form>
        <?php endif; ?>
    </div>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

