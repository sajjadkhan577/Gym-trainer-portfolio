<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$program = [
    'title' => '',
    'description' => '',
    'image' => '',
    'level' => 'Beginner',
    'level_color' => 'bg-surface-container-high text-on-surface border-white/10',
    'duration' => '',
    'price' => 0.00,
    'status' => 'active'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $program = $stmt->fetch() ?: $program;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $level = trim($_POST['level']);
        $duration = trim($_POST['duration']);
        $price = (float)$_POST['price'];
        $status = isset($_POST['status']) ? 'active' : 'inactive';
        $image = $program['image'] ?? '';

        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = __DIR__ . '/../uploads/programs/';
            $result = upload_image($_FILES['image'], $upload_dir);
            if ($result['success']) {
                delete_local_media($image);
                $image = 'uploads/programs/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }
        
        if (!isset($error)) {
            if (empty($title)) {
                $error = 'Program title is required.';
            } elseif (empty($description)) {
                $error = 'Program description is required.';
            } elseif (empty($level)) {
                $error = 'Difficulty level is required.';
            } elseif (empty($duration)) {
                $error = 'Duration is required.';
            } elseif ($price < 0) {
                $error = 'Price must be a positive number.';
            } else {
                $level_color = 'bg-surface-container-high text-on-surface border-white/10';
                if (strtolower($level) == 'intermediate') $level_color = 'bg-surface-bright text-on-surface-variant border border-white/10';
                if (strtolower($level) == 'advanced') $level_color = 'bg-primary-container/10 text-primary border border-primary-container/30';
                if (strtolower($level) == 'elite' || strtolower($level) == 'expert') $level_color = 'bg-error-container text-on-error-container border-error/20';

                if ($id) {
                    $stmt = $pdo->prepare("UPDATE programs SET title=?, description=?, image=?, level=?, level_color=?, duration=?, price=?, status=? WHERE id=?");
                    $stmt->execute([$title, $description, $image, $level, $level_color, $duration, $price, $status, $id]);
                    set_toast_message('success', 'Program updated successfully.');
                } else {
                    $stmt = $pdo->prepare("INSERT INTO programs (title, description, image, level, level_color, duration, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $image, $level, $level_color, $duration, $price, $status]);
                    set_toast_message('success', 'Program created successfully.');
                }
                echo "<script>window.location.href='programs.php';</script>";
                exit;
            }
        }
    }
}
?>

<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[800px] mx-auto">
<div class="mb-8">
<a href="programs.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Programs
</a>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Program' : 'New Program' ?></h2>
</div>

<div class="glass-panel rounded-xl p-6 md:p-8 bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<?php if (isset($error)): ?>
    <div class="mb-4 p-3 bg-error/10 border border-error/20 text-error rounded-md text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="space-y-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Program Title</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($program['title']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Description</label>
            <textarea name="description" rows="4" required class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($program['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Difficulty Level</label>
                <input type="text" name="level" required value="<?= htmlspecialchars($program['level']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Beginner, Advanced, etc.">
            </div>
            
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Duration</label>
                <input type="text" name="duration" required value="<?= htmlspecialchars($program['duration']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none" placeholder="e.g. 12 Weeks">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Price ($)</label>
                <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($program['price']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Program Image</label>
            <?php if (!empty($program['image'])): ?>
            <div class="mb-3 relative group w-full h-40 rounded overflow-hidden border border-white/10">
                <img src="<?= htmlspecialchars(admin_media_url($program['image'])) ?>" alt="Current" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>
            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-lg cursor-pointer hover:border-primary/50 transition-colors bg-surface-container-high">
                <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-2">upload</span>
                <span class="text-on-surface-variant text-sm"><?= !empty($program['image']) ? 'Click to replace image' : 'Click to upload image' ?></span>
                <span class="text-on-surface-variant/50 text-xs mt-1">JPG, PNG, WEBP up to 5MB</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(this, 'preview_program')">
            </label>
            <div id="preview_program" class="mt-3 hidden">
                <img src="" alt="Preview" class="w-full h-40 object-cover rounded border border-white/10">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="status" id="status" <?= $program['status'] == 'active' ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary focus:ring-offset-surface">
            <label for="status" class="text-on-surface">Program is Active</label>
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
            <a href="programs.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
            <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">
                Save Program
            </button>
        </div>
    </div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

