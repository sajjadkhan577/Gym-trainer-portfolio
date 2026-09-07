<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';
?>

<!-- Main Content Area -->
<main class="flex-1 min-h-0 bg-background">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<?php
$pdo = get_db_connection();

// Get Stats
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings");
    $total_bookings = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'pending'");
    $pending_bookings = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'confirmed'");
    $confirmed_bookings = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'completed'");
    $completed_bookings = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
    $unread_messages = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'active'");
    $newsletter_subscribers = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM services WHERE status = 'active'");
    $total_services = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM programs WHERE status = 'active'");
    $total_programs = $stmt->fetchColumn();
    
    // Get recent bookings
    $stmt = $pdo->query("
        SELECT b.*, s.title as service_title,
               b.full_name as client_name, b.preferred_date as booking_date,
               b.preferred_time as booking_time, b.booking_status as status
        FROM bookings b 
        LEFT JOIN services s ON b.service_id = s.id 
        ORDER BY b.created_at DESC 
        LIMIT 5
    ");
    $recent_bookings = $stmt->fetchAll();

    // Get recent messages
    $stmt = $pdo->query("
        SELECT * FROM contact_messages 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $recent_messages = $stmt->fetchAll();

    // Get booking data for chart (last 30 days)
    $stmt = $pdo->query("
        SELECT DATE(created_at) as date, COUNT(*) as count 
        FROM bookings 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $booking_chart_data = $stmt->fetchAll();

} catch(PDOException $e) {
    // Basic error handling for demo
    $total_bookings = $pending_bookings = $confirmed_bookings = $completed_bookings = $unread_messages = 0;
    $newsletter_subscribers = $total_services = $total_programs = 0;
    $recent_bookings = [];
    $recent_messages = [];
    $booking_chart_data = [];
}
?>

<!-- Canvas -->
<div class="flex-1 p-6 md:p-margin-desktop relative z-10 pb-12">
<div class="mb-12">
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Good morning, Coach.</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">Here is your daily performance summary.</p>
</div>
<!-- Stats Row (Bento Grid Style) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-12">
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="absolute top-0 right-0 w-24 h-24 bg-primary/10 rounded-full -mr-12 -mt-12 blur-2xl group-hover:bg-primary/20 transition-all duration-500"></div>
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Total Bookings</span>
<span class="material-symbols-outlined text-primary/50 group-hover:text-primary transition-colors">calendar_month</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($total_bookings) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="absolute top-0 right-0 w-24 h-24 bg-tertiary-container/10 rounded-full -mr-12 -mt-12 blur-2xl group-hover:bg-tertiary-container/20 transition-all duration-500"></div>
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Pending</span>
<span class="material-symbols-outlined text-tertiary-container/50 group-hover:text-tertiary-container transition-colors">hourglass_empty</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($pending_bookings) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="absolute top-0 right-0 w-24 h-24 bg-primary/10 rounded-full -mr-12 -mt-12 blur-2xl group-hover:bg-primary/20 transition-all duration-500"></div>
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Confirmed</span>
<span class="material-symbols-outlined text-primary/50 group-hover:text-primary transition-colors">check_circle</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($confirmed_bookings) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300 border border-primary/30">
<div class="absolute inset-0 bg-primary/5 group-hover:bg-primary/10 transition-colors duration-500"></div>
<div class="relative z-10 flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-primary font-bold uppercase tracking-wider">Unread Messages</span>
<span class="material-symbols-outlined text-primary animate-pulse">mail</span>
</div>
<div class="relative z-10 font-display-xl text-display-xl text-white"><?= number_format($unread_messages) ?></div>
</div>
</div>

<!-- Additional Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter mb-12">
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Completed</span>
<span class="material-symbols-outlined text-blue-400/50 group-hover:text-blue-400 transition-colors">done_all</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($completed_bookings) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Newsletter</span>
<span class="material-symbols-outlined text-purple-400/50 group-hover:text-purple-400 transition-colors">campaign</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($newsletter_subscribers) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Services</span>
<span class="material-symbols-outlined text-green-400/50 group-hover:text-green-400 transition-colors">fitness_center</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($total_services) ?></div>
</div>
<div class="glass-panel rounded-xl p-6 relative overflow-hidden group transition-all duration-300">
<div class="flex justify-between items-start mb-4">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Programs</span>
<span class="material-symbols-outlined text-orange-400/50 group-hover:text-orange-400 transition-colors">emoji_events</span>
</div>
<div class="font-display-xl text-display-xl text-on-surface"><?= number_format($total_programs) ?></div>
</div>
</div>
<!-- Main Content Area: Chart and Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-12">
<!-- Chart Area -->
<div class="lg:col-span-2 glass-panel rounded-xl p-6 h-96 flex flex-col">
<div class="flex justify-between items-center mb-6">
<h3 class="font-headline-md text-headline-md text-on-surface">Booking Trends</h3>
<div class="flex gap-2">
<button class="px-3 py-1 text-xs font-label-caps border border-white/10 rounded text-on-surface-variant hover:text-primary hover:border-primary transition-colors">Wk</button>
<button class="px-3 py-1 text-xs font-label-caps bg-primary/10 border border-primary rounded text-primary">Mo</button>
<button class="px-3 py-1 text-xs font-label-caps border border-white/10 rounded text-on-surface-variant hover:text-primary hover:border-primary transition-colors">Yr</button>
</div>
</div>
<!-- Chart with real data -->
<div class="flex-1 w-full bg-surface-container-low rounded-lg border border-white/5 flex items-center justify-center relative overflow-hidden">
<div class="absolute inset-0 bg-gradient-to-t from-primary/10 to-transparent"></div>
<?php if (!empty($booking_chart_data)): ?>
    <svg class="w-full h-full text-primary" preserveAspectRatio="none" viewBox="0 0 100 100">
    <?php
    $maxCount = max(array_column($booking_chart_data, 'count'));
    $points = [];
    $areaPoints = [];
    foreach ($booking_chart_data as $index => $data) {
        $x = ($index / (count($booking_chart_data) - 1)) * 100;
        $y = 100 - (($data['count'] / $maxCount) * 80); // Scale to 80% height
        $points[] = "$x,$y";
        $areaPoints[] = "$x,$y";
    }
    $linePath = 'M' . implode(' L', $points);
    $areaPath = 'M' . implode(' L', $areaPoints) . ' L100,100 L0,100 Z';
    ?>
    <path class="opacity-50" d="<?= $linePath ?>" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
    <path class="opacity-10" d="<?= $areaPath ?>" fill="currentColor"></path>
    </svg>
    <div class="absolute bottom-4 left-4 right-4 flex justify-between text-xs text-on-surface-variant font-label-caps">
    <?php foreach (array_slice($booking_chart_data, 0, 4) as $date): ?>
        <span><?= date('M j', strtotime($date['date'])) ?></span>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <svg class="w-full h-full text-primary" preserveAspectRatio="none" viewBox="0 0 100 100">
    <path class="opacity-50" d="M0,90 C20,70 40,80 60,30 C80,50 100,10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
    <path class="opacity-10" d="M0,90 C20,70 40,80 60,30 C80,50 100,10 L100,100 L0,100 Z" fill="currentColor"></path>
    </svg>
    <div class="absolute bottom-4 left-4 right-4 flex justify-between text-xs text-on-surface-variant font-label-caps">
    <span class="">W1</span><span class="">W2</span><span class="">W3</span><span class="">W4</span>
    </div>
<?php endif; ?>
</div>
</div>
<!-- Quick Actions Area -->
<div class="glass-panel rounded-xl p-6 flex flex-col">
<h3 class="font-headline-md text-headline-md text-on-surface mb-6">Quick Actions</h3>
<div class="space-y-4 flex-1 flex flex-col justify-center">
<a href="service-form.php" class="w-full py-4 px-6 bg-primary-container text-black font-bold rounded-lg flex items-center justify-between glow-hover transition-all duration-300 transform active:scale-95 group">
<span class="font-body-md uppercase tracking-wider text-sm">Add Service</span>
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</a>
<a href="program-form.php" class="w-full py-4 px-6 bg-surface-container-highest border border-white/10 text-on-surface rounded-lg flex items-center justify-between hover:border-white/30 transition-all duration-300 transform active:scale-95 group">
<span class="font-body-md uppercase tracking-wider text-sm">Add Program</span>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface transition-colors">add</span>
</a>
<a href="blog-form.php" class="w-full py-4 px-6 bg-surface-container-highest border border-white/10 text-on-surface rounded-lg flex items-center justify-between hover:border-white/30 transition-all duration-300 transform active:scale-95 group">
<span class="font-body-md uppercase tracking-wider text-sm">New Blog Post</span>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface transition-colors">edit</span>
</a>
<a href="transformation-form.php" class="w-full py-4 px-6 bg-surface-container-highest border border-white/10 text-on-surface rounded-lg flex items-center justify-between hover:border-white/30 transition-all duration-300 transform active:scale-95 group">
<span class="font-body-md uppercase tracking-wider text-sm">Add Transformation</span>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface transition-colors">photo_library</span>
</a>
<a href="testimonial-form.php" class="w-full py-4 px-6 bg-surface-container-highest border border-white/10 text-on-surface rounded-lg flex items-center justify-between hover:border-white/30 transition-all duration-300 transform active:scale-95 group">
<span class="font-body-md uppercase tracking-wider text-sm">Add Testimonial</span>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface transition-colors">rate_review</span>
</a>
</div>
</div>
</div>
<!-- Recent Bookings Table -->
<div class="glass-panel rounded-xl overflow-hidden">
<div class="p-6 border-b border-white/10 flex justify-between items-center">
<h3 class="font-headline-md text-headline-md text-on-surface">Recent Bookings</h3>
<a href="bookings.php" class="text-sm font-label-caps text-primary hover:text-primary-fixed transition-colors flex items-center gap-1">
                        View All <span class="material-symbols-outlined text-sm">chevron_right</span>
</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/5 bg-surface-container-low/50">
<th class="p-4 font-label-caps text-on-surface-variant">Client</th>
<th class="p-4 font-label-caps text-on-surface-variant">Service</th>
<th class="p-4 font-label-caps text-on-surface-variant">Date</th>
<th class="p-4 font-label-caps text-on-surface-variant">Status</th>
<th class="p-4 font-label-caps text-on-surface-variant text-right">Action</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($recent_bookings)): ?>
    <tr><td colspan="5" class="p-4 text-center text-on-surface-variant">No recent bookings found.</td></tr>
<?php else: foreach($recent_bookings as $b): ?>
<tr class="hover:bg-white/5 transition-colors group">
<td class="p-4 flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-xs font-bold text-on-surface">
<?= htmlspecialchars(substr($b['client_name'], 0, 1)) ?>
</div>
<span class="font-body-md font-medium text-on-surface group-hover:text-white"><?= htmlspecialchars($b['client_name']) ?></span>
</td>
<td class="p-4 font-body-md text-on-surface-variant"><?= htmlspecialchars($b['service_title'] ?? 'N/A') ?></td>
<td class="p-4 font-body-md text-on-surface-variant"><?= htmlspecialchars($b['booking_date'] . ' ' . $b['booking_time']) ?></td>
<td class="p-4">
<?php if ($b['status'] == 'confirmed'): ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">Confirmed</span>
<?php elseif ($b['status'] == 'pending'): ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-tertiary-container/10 text-tertiary-container border border-tertiary-container/20">Pending</span>
<?php elseif ($b['status'] == 'completed'): ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant border border-white/10">Completed</span>
<?php else: ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error border border-error/20"><?= ucfirst($b['status']) ?></span>
<?php endif; ?>
</td>
<td class="p-4 text-right">
<a href="booking-details.php?id=<?= $b['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">more_vert</span>
</a>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
</div>

<!-- Recent Messages Table -->
<div class="glass-panel rounded-xl overflow-hidden mt-8">
<div class="p-6 border-b border-white/10 flex justify-between items-center">
<h3 class="font-headline-md text-headline-md text-on-surface">Recent Messages</h3>
<a href="messages.php" class="text-sm font-label-caps text-primary hover:text-primary-fixed transition-colors flex items-center gap-1">
                        View All <span class="material-symbols-outlined text-sm">chevron_right</span>
</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/5 bg-surface-container-low/50">
<th class="p-4 font-label-caps text-on-surface-variant">Sender</th>
<th class="p-4 font-label-caps text-on-surface-variant">Subject</th>
<th class="p-4 font-label-caps text-on-surface-variant">Date</th>
<th class="p-4 font-label-caps text-on-surface-variant">Status</th>
<th class="p-4 font-label-caps text-on-surface-variant text-right">Action</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5">
<?php if (empty($recent_messages)): ?>
    <tr><td colspan="5" class="p-4 text-center text-on-surface-variant">No recent messages found.</td></tr>
<?php else: foreach($recent_messages as $m): ?>
<tr class="hover:bg-white/5 transition-colors group <?= $m['status'] == 'new' ? 'bg-white/5' : '' ?>">
<td class="p-4 flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-xs font-bold text-on-surface">
<?= htmlspecialchars(substr($m['name'], 0, 1)) ?>
</div>
<span class="font-body-md font-medium <?= $m['status'] == 'new' ? 'text-white' : 'text-on-surface' ?>"><?= htmlspecialchars($m['name']) ?></span>
</td>
<td class="p-4 font-body-md <?= $m['status'] == 'new' ? 'text-white font-medium' : 'text-on-surface-variant' ?>"><?= htmlspecialchars($m['subject']) ?></td>
<td class="p-4 font-body-md text-on-surface-variant"><?= date('M j, Y g:i A', strtotime($m['created_at'])) ?></td>
<td class="p-4">
<?php if ($m['status'] == 'new'): ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">New</span>
<?php elseif ($m['status'] == 'read'): ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Read</span>
<?php else: ?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant border border-white/10"><?= ucfirst($m['status']) ?></span>
<?php endif; ?>
</td>
<td class="p-4 text-right">
<a href="message-details.php?id=<?= $m['id'] ?>" class="text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">more_vert</span>
</a>
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

