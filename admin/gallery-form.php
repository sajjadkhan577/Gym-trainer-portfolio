<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$item = ['title' => '', 'image_url' => '', 'video_url' => '', 'category' => 'WORKOUT', 'description' => '', 'is_video' => 0, 'status' => 'active'];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch() ?: $item;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title']);
        $image_url = $item['image_url'] ?? '';
        $video_url = trim($_POST['video_url']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $is_video = isset($_POST['is_video']) ? 1 : 0;
        $status = isset($_POST['status']) ? 'active' : 'inactive';
        
        if (!empty($_FILES['image']['name'])) {
            $result = upload_image($_FILES['image'], __DIR__ . '/../uploads/gallery/');
            if ($result['success']) {
                delete_local_media($image_url);
                $image_url = 'uploads/gallery/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }

        // Validation
        if (!isset($error) && empty($title)) {
            $error = 'Title is required.';
        } elseif (!isset($error) && empty($image_url)) {
            $error = 'An image is required.';
        } else {
            $data = [$title, $image_url, $video_url, $category, $description, $is_video, $status];
            if ($id) {
                $pdo->prepare("UPDATE gallery SET title=?, image_url=?, video_url=?, category=?, description=?, is_video=?, status=? WHERE id=?")->execute([...$data, $id]);
                set_toast_message('success', 'Gallery item updated successfully.');
            } else {
                $pdo->prepare("INSERT INTO gallery (title, image_url, video_url, category, description, is_video, status) VALUES (?,?,?,?,?,?,?)")->execute($data);
                set_toast_message('success', 'Gallery item created successfully.');
            }
            echo "<script>window.location.href='gallery.php';</script>"; exit;
        }
    }
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[800px] mx-auto">
<div class="mb-8">
<a href="gallery.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Gallery
</a>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Gallery Item' : 'Add Gallery Item' ?></h2>
</div>
<div class="glass-panel rounded-xl p-6 md:p-8 bg-surface-container/70 backdrop-blur-[30px] border border-white/10">
<?php if (isset($error)): ?>
    <div class="mb-4 p-3 bg-error/10 border border-error/20 text-error rounded-md text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
<div class="space-y-6">
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Title</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($item['title']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Gallery Image</label>
        <?php if (!empty($item['image_url'])): ?><img src="<?= htmlspecialchars(admin_media_url($item['image_url'])) ?>" alt="Current gallery image" class="w-full h-40 object-cover rounded border border-white/10 mb-3"><?php endif; ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" <?= empty($item['image_url']) ? 'required' : '' ?> class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
        <p class="text-on-surface-variant/70 text-xs mt-2">JPG, PNG, WEBP up to 5MB. Leave empty to keep the current image.</p>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Video URL (optional)</label>
        <input type="text" name="video_url" value="<?= htmlspecialchars($item['video_url']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Category</label>
            <select name="category" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
                <?php foreach(['WORKOUT','GYM','LIFESTYLE','NUTRITION'] as $cat): ?>
                <option <?= $item['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Description</label>
            <input type="text" name="description" value="<?= htmlspecialchars($item['description']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
    </div>
    <div class="flex gap-8">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_video" <?= $item['is_video'] ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary">
            <span class="text-on-surface">This is a Video</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="status" <?= $item['status'] == 'active' ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary">
            <span class="text-on-surface">Active</span>
        </label>
    </div>
    <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
        <a href="gallery.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
        <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">Save</button>
    </div>
</div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

