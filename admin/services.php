<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();

// Handle active toggle with CSRF protection
if (isset($_GET['toggle_active']) && isset($_GET['id'])) {
    if (!verify_csrf_token($_GET['csrf_token'] ?? '')) {
        set_toast_message('error', 'Invalid security token. Please try again.');
    } else {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("UPDATE services SET status = IF(status='active','inactive','active') WHERE id = :id");
        $stmt->execute([':id' => $id]);
        set_toast_message('success', 'Service status updated successfully.');
    }
    echo "<script>window.location.href='services.php';</script>";
    exit;
}

// Fetch Services
$stmt = $pdo->query("SELECT * FROM services ORDER BY created_at DESC");
$services = $stmt->fetchAll();
?>

<!-- Main Content Area -->
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 px-4 md:px-margin-desktop pb-12 w-full max-w-[1600px] mx-auto">
<!-- Page Header & Action -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
<div>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Services Management</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Configure and organize your elite coaching offerings.</p>
</div>
<a href="service-form.php" class="bg-primary-container text-black font-label-caps text-label-caps px-8 py-4 rounded uppercase tracking-widest flex items-center gap-2 hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">
<span class="material-symbols-outlined">add</span>
    Add Service
</a>
</div>

<!-- Bento Grid Layout for Services -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<?php foreach($services as $index => $service): 
    // Alternate sizes for the bento grid
    $colSpan = ($index % 3 == 0) ? 'md:col-span-8' : 'md:col-span-4';
    $imgUrl = $service['image'] ? admin_media_url($service['image']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDBs-tVPxs_UAA6Pn9fwYYaOGiwqATq0Lg0l95ezPJ5AD7QOwuFCoGuOlE3aj9Nt1DofWApdzvh0qZpv0T_OhwsE8jECLGly_BRG7gTrSIWdkXRYav8VN8rcPMk1ABByJUXecE6uqF-0KgYYYuJQn5U9tIIW5iL67nRv-kcnBMW8M21uZMXyl00TSljH_5y9VltjggRqDI5L-9MaTlj1Gz7FzLuP61D8PmLCfgLBz7geCnU4V0BS0xAzA';
    $imgUrl = htmlspecialchars($imgUrl);
?>
<div class="<?= $colSpan ?> glass-panel rounded-xl overflow-hidden relative group h-[400px]">
    <?php if ($colSpan == 'md:col-span-8'): ?>
        <div class="absolute inset-0 z-0">
            <div class="bg-cover bg-center w-full h-full opacity-60 group-hover:opacity-80 transition-opacity duration-500" style="background-image: url('<?= $imgUrl ?>')"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/80 to-transparent"></div>
        </div>
        <div class="relative z-10 p-6 flex flex-col h-full justify-between">
            <div class="flex justify-between items-start">
                <?php if ($index == 0): ?>
                    <span class="bg-primary/20 text-primary border border-primary/30 px-3 py-1 rounded font-label-caps text-[10px] uppercase tracking-widest backdrop-blur-sm">Most Popular</span>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                <!-- Toggle -->
                <div class="flex items-center">
                    <a href="?toggle_active=1&id=<?= $service['id'] ?>&csrf_token=<?= $csrf_token ?>" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input <?= ($service['status'] ?? 'inactive') === 'active' ? 'checked' : '' ?> class="sr-only toggle-checkbox" type="checkbox" onclick="return false;">
                            <div class="block bg-surface-container-high w-10 h-6 rounded-full border border-white/20 transition-colors toggle-label <?= $service['status'] == 'active' ? 'bg-primary-container border-primary-container' : '' ?>"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 rounded-full transition transform shadow-sm <?= $service['status'] == 'active' ? 'bg-white translate-x-[100%]' : 'bg-on-surface-variant translate-x-0' ?>"></div>
                        </div>
                        <span class="ml-3 font-label-caps text-xs text-on-surface-variant uppercase"><?= $service['status'] == 'active' ? 'Active' : 'Inactive' ?></span>
                    </a>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2"><?= htmlspecialchars($service['title']) ?></h3>
                        <p class="text-on-surface-variant max-w-md line-clamp-2"><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <div class="text-right">
                        <span class="block font-display-xl text-[48px] font-black text-white leading-none">$<?= number_format($service['price'], 2) ?></span>
                    </div>
                </div>
                <div class="flex gap-4 border-t border-white/10 pt-4 mt-4">
                    <a href="service-form.php?id=<?= $service['id'] ?>" class="text-on-surface hover:text-primary transition-colors flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                    </a>
                    <a href="bookings.php?service_id=<?= $service['id'] ?>" class="text-on-surface hover:text-primary transition-colors flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-[18px]">group</span> View Clients
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="h-48 relative overflow-hidden">
            <div class="bg-cover bg-center w-full h-full opacity-60 group-hover:opacity-80 transition-opacity duration-500 group-hover:scale-105" style="background-image: url('<?= $imgUrl ?>')"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-surface to-transparent"></div>
        </div>
        <div class="p-6 flex-1 flex flex-col justify-between relative z-10 -mt-12">
            <div class="flex justify-end mb-2">
                <a href="?toggle_active=1&id=<?= $service['id'] ?>&csrf_token=<?= $csrf_token ?>" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input <?= ($service['status'] ?? 'inactive') === 'active' ? 'checked' : '' ?> class="sr-only toggle-checkbox" type="checkbox" onclick="return false;">
                        <div class="block bg-surface-container-high w-10 h-6 rounded-full border border-white/20 transition-colors toggle-label <?= $service['status'] == 'active' ? 'bg-primary-container border-primary-container' : '' ?>"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 rounded-full transition transform shadow-sm <?= $service['status'] == 'active' ? 'bg-white translate-x-[100%]' : 'bg-on-surface-variant translate-x-0' ?>"></div>
                    </div>
                </a>
            </div>
            <div>
                <h3 class="font-headline-md text-[24px] font-bold text-on-surface mb-2 leading-tight"><?= htmlspecialchars($service['title']) ?></h3>
                <p class="text-on-surface-variant text-sm mb-4 line-clamp-3"><?= htmlspecialchars($service['description']) ?></p>
                <div class="flex justify-between items-center border-t border-white/10 pt-4">
                    <div>
                        <span class="block font-headline-md text-[28px] font-black text-white leading-none">$<?= number_format($service['price'], 2) ?></span>
                    </div>
                    <a href="service-form.php?id=<?= $service['id'] ?>" class="w-10 h-10 rounded-full bg-surface-container border border-white/10 flex items-center justify-center hover:border-primary hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>

</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

