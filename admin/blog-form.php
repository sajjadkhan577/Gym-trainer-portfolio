<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$post = [
    'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '',
    'image' => '', 'category' => 'TRAINING', 'author' => 'Coach',
    'date' => date('Y-m-d'), 'read_time' => '', 'status' => 'draft'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch() ?: $post;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title']);
        $slug  = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $title));
        $image = $post['image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $result = upload_image($_FILES['image'], __DIR__ . '/../uploads/blog/');
            if ($result['success']) {
                delete_local_media($image);
                $image = 'uploads/blog/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }
        if (isset($error)) {
            $data = null;
        } else {
        $data  = [
            $title, $slug,
            trim($_POST['excerpt']), trim($_POST['content']),
            $image, trim($_POST['category']),
            trim($_POST['author']), trim($_POST['date']),
            trim($_POST['read_time']), $_POST['status'],
        ];
        if ($id) {
            $pdo->prepare("UPDATE blog_posts SET title=?,slug=?,excerpt=?,content=?,image=?,category=?,author=?,date=?,read_time=?,status=? WHERE id=?")->execute([...$data, $id]);
        } else {
            $pdo->prepare("INSERT INTO blog_posts (title,slug,excerpt,content,image,category,author,date,read_time,status) VALUES (?,?,?,?,?,?,?,?,?,?)")->execute($data);
        }
        }
        echo "<script>window.location.href='blog.php';</script>"; exit;
    }
}
?>
<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>
<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[900px] mx-auto">
<div class="mb-8">
<a href="blog.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Blog
</a>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Post' : 'New Post' ?></h2>
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
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Post Title</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($post['title']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Slug (URL)</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($post['slug']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none" placeholder="auto-generated if empty">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Category</label>
            <select name="category" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
                <?php foreach(['TRAINING','NUTRITION','LIFESTYLE','MINDSET'] as $cat): ?>
                <option <?= $post['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Excerpt</label>
        <textarea name="excerpt" rows="2" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($post['excerpt']) ?></textarea>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Full Content</label>
        <textarea name="content" rows="10" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none font-mono text-sm"><?= htmlspecialchars($post['content']) ?></textarea>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Featured Image</label>
        <?php if (!empty($post['image'])): ?><img src="<?= htmlspecialchars(admin_media_url($post['image'])) ?>" alt="Current featured image" class="w-full h-40 object-cover rounded border border-white/10 mb-3"><?php endif; ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
        <p class="text-on-surface-variant/70 text-xs mt-2">JPG, PNG, WEBP up to 5MB. Leave empty to keep the current image.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Author</label>
            <input type="text" name="author" value="<?= htmlspecialchars($post['author']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Publish Date</label>
            <input type="date" name="date" value="<?= htmlspecialchars($post['date']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Read Time</label>
            <input type="text" name="read_time" value="<?= htmlspecialchars($post['read_time']) ?>" placeholder="e.g. 5 min read" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>
    </div>
    <div>
        <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Status</label>
        <select name="status" class="w-full md:w-48 bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= $post['status'] == 'published' ? 'selected' : '' ?>>Published</option>
            <option value="archived" <?= $post['status'] == 'archived' ? 'selected' : '' ?>>Archived</option>
        </select>
    </div>
    <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
        <a href="blog.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
        <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">Save Post</button>
    </div>
</div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

