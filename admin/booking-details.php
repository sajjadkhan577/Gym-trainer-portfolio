<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo "<script>window.location.href='bookings.php';</script>";
    exit;
}

$stmt = $pdo->prepare("
    SELECT b.*, s.title as service_title, p.title as program_title,
           b.full_name as client_name, b.preferred_date as booking_date,
           b.preferred_time as booking_time, b.booking_status as status,
           b.current_fitness_level as fitness_level
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id 
    LEFT JOIN programs p ON b.program_id = p.id 
    WHERE b.id = :id
");
$stmt->execute([':id' => $id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "<script>window.location.href='bookings.php';</script>";
    exit;
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 px-4 md:px-margin-desktop pb-12 w-full max-w-[1200px] mx-auto">
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
<div>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Booking Details</h1>
<p class="text-on-surface-variant font-body-lg text-body-lg">View complete information for this booking.</p>
</div>
<div class="flex gap-4">
<a href="bookings.php" class="btn-ghost px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
    BACK TO LIST
</a>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="glass-panel rounded-lg p-6">
        <h3 class="font-headline-md text-xl text-on-surface mb-4 border-b border-white/10 pb-2">Client Information</h3>
        <div class="space-y-4">
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Name</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['client_name']) ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Email</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['email']) ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Phone</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['phone']) ?></span></div>
        </div>
    </div>
    
    <div class="glass-panel rounded-lg p-6">
        <h3 class="font-headline-md text-xl text-on-surface mb-4 border-b border-white/10 pb-2">Booking Details</h3>
        <div class="space-y-4">
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Service</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['service_title'] ?? 'N/A') ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Program</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['program_title'] ?? 'N/A') ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Date & Time</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['booking_date'] . ' ' . $booking['booking_time']) ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Status</span><span class="text-primary font-bold uppercase"><?= htmlspecialchars($booking['status']) ?></span></div>
        </div>
    </div>
    
    <div class="glass-panel rounded-lg p-6 md:col-span-2">
        <h3 class="font-headline-md text-xl text-on-surface mb-4 border-b border-white/10 pb-2">Additional Information</h3>
        <div class="space-y-4">
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Fitness Goal</span><p class="text-on-surface"><?= nl2br(htmlspecialchars($booking['fitness_goal'] ?? 'N/A')) ?></p></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Fitness Level</span><span class="text-on-surface font-medium"><?= htmlspecialchars($booking['fitness_level'] ?? 'N/A') ?></span></div>
            <div><span class="text-on-surface-variant text-sm block uppercase tracking-wider">Message</span><p class="text-on-surface bg-surface-container-low p-4 rounded mt-2"><?= nl2br(htmlspecialchars($booking['message'] ?? 'No message provided.')) ?></p></div>
        </div>
        
        <div class="mt-8 flex gap-4">
            <?php if ($booking['status'] == 'pending'): ?>
            <form method="POST" action="bookings.php" class="inline"><input type="hidden" name="action" value="confirmed"><input type="hidden" name="id" value="<?= (int)$booking['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="btn-primary px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps">Confirm Booking</button></form>
            <form method="POST" action="bookings.php" class="inline"><input type="hidden" name="action" value="cancelled"><input type="hidden" name="id" value="<?= (int)$booking['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="bg-error/20 text-error border border-error px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps">Reject</button></form>
            <?php endif; ?>
            <?php if ($booking['status'] == 'confirmed'): ?>
            <form method="POST" action="bookings.php" class="inline"><input type="hidden" name="action" value="completed"><input type="hidden" name="id" value="<?= (int)$booking['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="bg-blue-500/20 text-blue-400 border border-blue-500 px-6 py-2 rounded-DEFAULT font-label-caps text-label-caps">Mark Completed</button></form>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

