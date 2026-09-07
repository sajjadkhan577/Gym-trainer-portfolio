<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$item = ['author_name'=>'','author_role'=>'','author_image'=>'','quote'=>'','rating'=>5,'status'=>'active'];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch() ?: $item;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $author_name = trim($_POST['author_name']);
        $author_role = trim($_POST['author_role']);
        $author_image = $item['author_image'] ?? '';
        if (!empty($_FILES['author_image']['name'])) {
            $result = upload_image($_FILES['author_image'], __DIR__ . '/../uploads/testimonials/');
            if ($result['success']) {
                delete_local_media($author_image);
                $author_image = 'uploads/testimonials/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }
        $quote = trim($_POST['quote']);
        $rating = (int)$_POST['rating'];
        $status = isset($_POST['status']) ? 'active' : 'inactive';
        
        // Validation
        if (!isset($error) && empty($author_name)) {
            $error = 'Author name is required.';
        } elseif (!isset($error) && empty($quote)) {
            $error = 'Quote is required.';
        } elseif (!isset($error) && ($rating < 1 || $rating > 5)) {
            $error = 'Rating must be between 1 and 5.';
        } else {
            $data = [$author_name, $author_role, $author_image, $quote, $rating, $status];
            if ($id) {
                $pdo->prepare("UPDATE testimonials SET author_name=?,author_role=?,author_image=?,quote=?,rating=?,status=? WHERE id=?")->execute([...$data, $id]);
                set_toast_message('success', 'Testimonial updated successfully.');
            } else {
                $pdo->prepare("INSERT INTO testimonials (author_name,author_role,author_image,quote,rating,status) VALUES (?,?,?,?,?,?)")->execute($data);
                set_toast_message('success', 'Testimonial created successfully.');
            }
            echo "<script>window.location.href='testimonials.php';</script>"; exit;
        }
    }
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[800px] mx-auto">
<div class="mb-8">
<a href="testimonials.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Testimonials
</a>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Testimonial' : 'Add Testimonial' ?></h2>
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Author Name</label>
            <input type="text" name="author_name" required value="<?= htmlspecialchars($item['author_name']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Author Role / Title</label>
            <input type="text" name="author_role" value="<?= htmlspecialchars($item['author_role']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Author Image</label>
        <?php if (!empty($item['author_image'])): ?><img src="<?= htmlspecialchars(admin_media_url($item['author_image'])) ?>" alt="Current author image" class="w-16 h-16 rounded-full object-cover border border-white/10 mb-3"><?php endif; ?>
        <input type="file" name="author_image" accept="image/jpeg,image/png,image/webp" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
        <p class="text-on-surface-variant/70 text-xs mt-2">JPG, PNG, WEBP up to 5MB. Leave empty to keep the current image.</p>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Quote / Testimonial</label>
        <textarea name="quote" rows="5" required class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($item['quote']) ?></textarea>
    </div>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Rating (1–5)</label>
            <select name="rating" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
                <?php for($i=5;$i>=1;$i--): ?>
                <option value="<?= $i ?>" <?= $item['rating'] == $i ? 'selected' : '' ?>><?= $i ?> Stars</option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-3 cursor-pointer pb-3">
                <input type="checkbox" name="status" <?= $item['status'] == 'active' ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary">
                <span class="text-on-surface">Active</span>
            </label>
        </div>
    </div>
    <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
        <a href="testimonials.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
        <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">Save</button>
    </div>
</div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

