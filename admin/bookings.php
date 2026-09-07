<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();

// Handle status updates with CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_toast_message('error', 'Invalid security token. Please try again.');
    } else {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $action = $_POST['action'];
        $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        
        if ($id && in_array($action, $allowed_statuses, true)) {
            $stmt = $pdo->prepare("UPDATE bookings SET booking_status = :status WHERE id = :id");
            $stmt->execute([':status' => $action, ':id' => $id]);
            set_toast_message('success', 'Booking status updated successfully.');
        } else {
            set_toast_message('error', 'Invalid action.');
        }
    }
    echo "<script>window.location.href='bookings.php';</script>";
    exit;
}

// Handle filters and pagination
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$service_id = $_GET['service_id'] ?? '';
$date = $_GET['date'] ?? '';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$query = "SELECT b.*, s.title as service_title FROM bookings b LEFT JOIN services s ON b.service_id = s.id WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (b.full_name LIKE :search OR b.email LIKE :search)";
    $params[':search'] = "%$search%";
}
if ($status && $status !== 'All Statuses') {
    $query .= " AND b.booking_status = :status";
    $params[':status'] = strtolower($status);
}
if ($service_id && $service_id !== 'All Services') {
    $query .= " AND b.service_id = :service_id";
    $params[':service_id'] = $service_id;
}
if ($date) {
    $query .= " AND b.preferred_date = :date";
    $params[':date'] = $date;
}

// Count total for pagination
$countQuery = str_replace("SELECT b.*, s.title as service_title, b.full_name as client_name, b.preferred_date as booking_date, b.preferred_time as booking_time, b.booking_status as status, b.current_fitness_level as fitness_level", "SELECT COUNT(*)", $query);
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

$query .= " ORDER BY b.created_at DESC LIMIT $limit OFFSET $offset";
// Alias real column names to friendly names
$query = str_replace("SELECT b.*, s.title as service_title",
    "SELECT b.*, s.title as service_title, b.full_name as client_name, b.preferred_date as booking_date, b.preferred_time as booking_time, b.booking_status as status, b.current_fitness_level as fitness_level",
    $query);
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Get services for filter
$services = $pdo->query("SELECT id, title FROM services ORDER BY title")->fetchAll();

?>
<!-- Main Content Area -->
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<!-- Page Content -->
<div class="pt-6 px-4 md:px-margin-desktop pb-12 w-full max-w-[1600px] mx-auto">
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
<div>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Bookings Management</h1>
<p class="text-on-surface-variant font-body-lg text-body-lg">Review and manage client sessions.</p>
</div>
</div>

<!-- Filters & Search -->
<div class="glass-panel rounded-lg p-6 mb-8">
<form method="GET" action="bookings.php" class="flex flex-col lg:flex-row gap-6 justify-between items-end">
<div class="w-full lg:w-1/3 relative mb-4 lg:mb-0">
<span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-on-surface-variant">search</span>
<input name="search" value="<?= htmlspecialchars($search) ?>" class="w-full bg-surface-container-high border-none rounded-DEFAULT py-3 pl-10 pr-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none transition-shadow text-sm" placeholder="Search by client or email..." type="text">
</div>
<div class="flex flex-wrap gap-4 w-full lg:w-auto">
<div class="flex flex-col gap-2">
<label class="font-label-caps text-label-caps text-on-surface-variant">Date</label>
<input name="date" value="<?= htmlspecialchars($date) ?>" class="bg-surface-container border border-white/10 rounded-DEFAULT px-4 py-2 text-on-surface text-sm focus:border-primary focus:ring-1 focus:ring-primary" type="date">
</div>
<div class="flex flex-col gap-2">
<label class="font-label-caps text-label-caps text-on-surface-variant">Status</label>
<select name="status" class="bg-surface-container border border-white/10 rounded-DEFAULT px-4 py-2 text-on-surface text-sm focus:border-primary focus:ring-1 focus:ring-primary">
<option>All Statuses</option>
<option <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
<option <?= $status == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
<option <?= $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
<option <?= $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
</select>
</div>
<div class="flex flex-col gap-2">
<label class="font-label-caps text-label-caps text-on-surface-variant">Service</label>
<select name="service_id" class="bg-surface-container border border-white/10 rounded-DEFAULT px-4 py-2 text-on-surface text-sm focus:border-primary focus:ring-1 focus:ring-primary">
<option value="">All Services</option>
<?php foreach($services as $s): ?>
<option value="<?= $s['id'] ?>" <?= $service_id == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['title']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="flex flex-col justify-end">
    <button type="submit" class="bg-primary/10 text-primary border border-primary px-4 py-2 rounded-DEFAULT font-label-caps hover:bg-primary hover:text-black transition-colors h-full">Filter</button>
</div>
</div>
</form>
</div>

<!-- Data Table -->
<div class="glass-panel rounded-lg overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-white/10 bg-surface-container-low text-on-surface-variant font-label-caps text-label-caps">
<th class="py-4 px-6 font-semibold tracking-wider">Client Details</th>
<th class="py-4 px-6 font-semibold tracking-wider">Service</th>
<th class="py-4 px-6 font-semibold tracking-wider">Date &amp; Time</th>
<th class="py-4 px-6 font-semibold tracking-wider">Status</th>
<th class="py-4 px-6 font-semibold tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="text-sm divide-y divide-white/5">
<?php if (empty($bookings)): ?>
    <tr><td colspan="5" class="py-4 px-6 text-center text-on-surface-variant">No bookings found.</td></tr>
<?php else: foreach($bookings as $b): ?>
<tr class="table-row-hover transition-colors">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high flex-shrink-0 flex items-center justify-center text-lg font-bold text-primary">
<?= htmlspecialchars(substr($b['client_name'], 0, 1)) ?>
</div>
<div>
<div class="font-medium text-on-surface"><?= htmlspecialchars($b['client_name']) ?></div>
<div class="text-on-surface-variant text-xs"><?= htmlspecialchars($b['email']) ?></div>
</div>
</div>
</td>
<td class="py-4 px-6 text-on-surface"><?= htmlspecialchars($b['service_title'] ?? 'Custom') ?></td>
<td class="py-4 px-6">
<div class="text-on-surface"><?= htmlspecialchars($b['booking_date']) ?></div>
<div class="text-on-surface-variant text-xs"><?= htmlspecialchars($b['booking_time']) ?></div>
</td>
<td class="py-4 px-6">
<?php
$statusClass = 'bg-surface-variant text-on-surface-variant';
if ($b['status'] == 'pending') $statusClass = 'bg-tertiary-container/20 text-tertiary-container';
if ($b['status'] == 'confirmed') $statusClass = 'bg-primary/20 text-primary';
if ($b['status'] == 'cancelled' || $b['status'] == 'rejected') $statusClass = 'bg-error/20 text-error';
if ($b['status'] == 'completed') $statusClass = 'bg-blue-500/20 text-blue-400';
?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-white/10 uppercase <?= $statusClass ?>">
    <?= htmlspecialchars($b['status']) ?>
</span>
</td>
<td class="py-4 px-6 text-right">
<div class="flex justify-end gap-2">
<?php if ($b['status'] == 'pending'): ?>
<form method="POST" class="inline"><input type="hidden" name="action" value="confirmed"><input type="hidden" name="id" value="<?= (int)$b['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="p-2 rounded hover:bg-white/10 text-primary transition-colors" title="Confirm">
<span class="material-symbols-outlined text-[20px]">check_circle</span>
</button></form>
<form method="POST" class="inline"><input type="hidden" name="action" value="cancelled"><input type="hidden" name="id" value="<?= (int)$b['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="p-2 rounded hover:bg-white/10 text-error transition-colors" title="Reject">
<span class="material-symbols-outlined text-[20px]">cancel</span>
</button></form>
<?php endif; ?>
<?php if ($b['status'] == 'confirmed'): ?>
<form method="POST" class="inline"><input type="hidden" name="action" value="completed"><input type="hidden" name="id" value="<?= (int)$b['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="p-2 rounded hover:bg-white/10 text-blue-400 transition-colors" title="Mark Completed">
<span class="material-symbols-outlined text-[20px]">done_all</span>
</button></form>
<?php endif; ?>
<a href="booking-details.php?id=<?= $b['id'] ?>" class="p-2 rounded hover:bg-white/10 text-on-surface-variant transition-colors" title="View Details">
<span class="material-symbols-outlined text-[20px]">visibility</span>
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
<div class="px-6 py-4 border-t border-white/10 bg-surface-container-low flex items-center justify-between">
<div class="text-sm text-on-surface-variant">
    Showing <span class="font-medium text-on-surface"><?= $offset + 1 ?></span> to <span class="font-medium text-on-surface"><?= min($offset + $limit, $total_records) ?></span> of <span class="font-medium text-on-surface"><?= $total_records ?></span> results
</div>
<div class="flex gap-2">
<a href="?page=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="px-3 py-1 rounded bg-surface-container border border-white/10 text-on-surface-variant hover:bg-white/5 <?= $page <= 1 ? 'pointer-events-none opacity-50' : '' ?>">Prev</a>
<a href="?page=<?= min($total_pages, $page + 1) ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="px-3 py-1 rounded bg-surface-container border border-white/10 text-on-surface-variant hover:bg-white/5 <?= $page >= $total_pages ? 'pointer-events-none opacity-50' : '' ?>">Next</a>
</div>
</div>
<?php endif; ?>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

