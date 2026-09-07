<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$service = [
    'title' => '',
    'description' => '',
    'icon' => '',
    'image' => '',
    'price' => 0.00,
    'status' => 'active'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $service = $stmt->fetch() ?: $service;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $icon = trim($_POST['icon']);
        $price = (float)$_POST['price'];
        $status = isset($_POST['status_active']) ? 'active' : 'inactive';
        // Keep existing image by default
        $image = $service['image'] ?? '';

        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = __DIR__ . '/../uploads/services/';
            $result = upload_image($_FILES['image'], $upload_dir);
            if ($result['success']) {
                // Delete old image if exists
                delete_local_media($image);
                $image = 'uploads/services/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }

        // Validation
        if (!isset($error)) {
            if (empty($title)) {
                $error = 'Service title is required.';
            } elseif (empty($description)) {
                $error = 'Service description is required.';
            } elseif ($price < 0) {
                $error = 'Price must be a positive number.';
            } else {
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ?, image = ?, price = ?, status = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $icon, $image, $price, $status, $id]);
                    set_toast_message('success', 'Service updated successfully.');
                } else {
                    $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, image, price, status) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $icon, $image, $price, $status]);
                    set_toast_message('success', 'Service created successfully.');
                }
                echo "<script>window.location.href='services.php';</script>";
                exit;
            }
        }
    }
}

?>

<!-- Main Content Area -->
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 px-4 md:px-margin-desktop pb-12 w-full max-w-[800px] mx-auto">
<!-- Page Header -->
<div class="mb-8">
<a href="services.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Services
</a>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Service' : 'Add New Service' ?></h2>
</div>

<div class="glass-panel rounded-xl p-6 md:p-8">
<?php if (isset($error)): ?>
    <div class="mb-4 p-3 bg-error/10 border border-error/20 text-error rounded-md text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="space-y-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Service Title</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($service['title']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Description</label>
            <textarea name="description" rows="4" required class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($service['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Price ($)</label>
                <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($service['price']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
            
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Material Icon Name</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($service['icon']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none" placeholder="e.g. fitness_center">
            </div>
        </div>

        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Background Image</label>
            <?php if (!empty($service['image'])): ?>
            <div class="mb-3 relative group w-full h-40 rounded overflow-hidden border border-white/10">
                <img src="<?= htmlspecialchars(admin_media_url($service['image'])) ?>" alt="Current Image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-white text-xs font-label-caps uppercase tracking-wider">Current Image</span>
                </div>
            </div>
            <?php endif; ?>
            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-lg cursor-pointer hover:border-primary/50 transition-colors bg-surface-container-high">
                <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-2">upload</span>
                <span class="text-on-surface-variant text-sm"><?= !empty($service['image']) ? 'Click to replace image' : 'Click to upload image' ?></span>
                <span class="text-on-surface-variant/50 text-xs mt-1">JPG, PNG, WEBP up to 5MB</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(this, 'preview_service')">
            </label>
            <div id="preview_service" class="mt-3 hidden">
                <img src="" alt="Preview" class="w-full h-40 object-cover rounded border border-white/10">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="status_active" id="status_active" <?= ($service['status'] ?? 'active') == 'active' ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary focus:ring-offset-surface">
            <label for="status_active" class="text-on-surface">Service is Active</label>
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
            <a href="services.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
            <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">
                Save Service
            </button>
        </div>
    </div>
</form>
</div>

</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

