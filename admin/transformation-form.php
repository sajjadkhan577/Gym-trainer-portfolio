<?php
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-sidebar.php';

$pdo = get_db_connection();
$csrf_token = generate_csrf_token();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$transformation = [
    'client_name' => '',
    'before_image' => '',
    'after_image' => '',
    'before_label' => 'Before',
    'after_label' => 'After',
    'quote' => '',
    'goal' => '',
    'weight_lost' => '',
    'story' => '',
    'duration' => '',
    'category' => 'general',
    'status' => 'active'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM transformations WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $transformation = $stmt->fetch() ?: $transformation;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $client_name = trim($_POST['client_name']);
        $before_image = $transformation['before_image'] ?? '';
        $after_image = $transformation['after_image'] ?? '';
        if (!empty($_FILES['before_image']['name'])) {
            $result = upload_image($_FILES['before_image'], __DIR__ . '/../uploads/transformations/');
            if ($result['success']) {
                delete_local_media($before_image);
                $before_image = 'uploads/transformations/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }
        if (!empty($_FILES['after_image']['name'])) {
            $result = upload_image($_FILES['after_image'], __DIR__ . '/../uploads/transformations/');
            if ($result['success']) {
                delete_local_media($after_image);
                $after_image = 'uploads/transformations/' . $result['filename'];
            } else {
                $error = $result['error'];
            }
        }
        $before_label = trim($_POST['before_label']);
        $after_label = trim($_POST['after_label']);
        $quote = trim($_POST['quote']);
        $goal = trim($_POST['goal']);
        $weight_lost = trim($_POST['weight_lost']);
        $story = trim($_POST['story']);
        $duration = trim($_POST['duration']);
        $category = trim($_POST['category']);
        $status = isset($_POST['status']) ? 'active' : 'inactive';
        
        // Validation
        if (!isset($error) && empty($client_name)) {
            $error = 'Client name is required.';
        } elseif (!isset($error) && (empty($before_image) || empty($after_image))) {
            $error = 'Both before and after images are required.';
        } else {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE transformations SET client_name=?, before_image=?, after_image=?, before_label=?, after_label=?, quote=?, goal=?, weight_lost=?, story=?, duration=?, category=?, status=? WHERE id=?");
                $stmt->execute([$client_name, $before_image, $after_image, $before_label, $after_label, $quote, $goal, $weight_lost, $story, $duration, $category, $status, $id]);
                set_toast_message('success', 'Transformation updated successfully.');
            } else {
                $stmt = $pdo->prepare("INSERT INTO transformations (client_name, before_image, after_image, before_label, after_label, quote, goal, weight_lost, story, duration, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$client_name, $before_image, $after_image, $before_label, $after_label, $quote, $goal, $weight_lost, $story, $duration, $category, $status]);
                set_toast_message('success', 'Transformation created successfully.');
            }
            
            echo "<script>window.location.href='transformations.php';</script>";
            exit;
        }
    }
}
?>

<main class="flex-1 min-h-0">
<?php require_once __DIR__ . '/includes/admin-topbar.php'; ?>

<div class="pt-6 pb-20 px-6 md:px-margin-desktop max-w-[800px] mx-auto">
<div class="mb-8">
<a href="transformations.php" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 text-sm font-label-caps uppercase tracking-wider mb-4">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Transformations
</a>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2"><?= $id ? 'Edit Transformation' : 'New Transformation' ?></h2>
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
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Client Name</label>
            <input type="text" name="client_name" required value="<?= htmlspecialchars($transformation['client_name']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Before Image</label>
                <?php if (!empty($transformation['before_image'])): ?><img src="<?= htmlspecialchars(admin_media_url($transformation['before_image'])) ?>" alt="Current before image" class="w-full h-40 object-cover rounded border border-white/10 mb-3"><?php endif; ?>
                <input type="file" name="before_image" accept="image/jpeg,image/png,image/webp" <?= empty($transformation['before_image']) ? 'required' : '' ?> class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
                <p class="text-on-surface-variant/70 text-xs mt-2">JPG, PNG, WEBP up to 5MB.</p>
            </div>
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">After Image</label>
                <?php if (!empty($transformation['after_image'])): ?><img src="<?= htmlspecialchars(admin_media_url($transformation['after_image'])) ?>" alt="Current after image" class="w-full h-40 object-cover rounded border border-white/10 mb-3"><?php endif; ?>
                <input type="file" name="after_image" accept="image/jpeg,image/png,image/webp" <?= empty($transformation['after_image']) ? 'required' : '' ?> class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface">
                <p class="text-on-surface-variant/70 text-xs mt-2">JPG, PNG, WEBP up to 5MB.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Before Label (e.g., DAY 1)</label>
                <input type="text" name="before_label" value="<?= htmlspecialchars($transformation['before_label']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">After Label (e.g., DAY 90)</label>
                <input type="text" name="after_label" value="<?= htmlspecialchars($transformation['after_label']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Category (e.g., Weight Loss)</label>
                <input type="text" name="category" value="<?= htmlspecialchars($transformation['category']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Duration (e.g., 12 Weeks)</label>
                <input type="text" name="duration" value="<?= htmlspecialchars($transformation['duration']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Goal Achieved</label>
                <input type="text" name="goal" value="<?= htmlspecialchars($transformation['goal']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
            <div>
                <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Weight Lost (e.g., -20 lbs)</label>
                <input type="text" name="weight_lost" value="<?= htmlspecialchars($transformation['weight_lost']) ?>" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Quote / Testimonial excerpt</label>
            <textarea name="quote" rows="2" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($transformation['quote']) ?></textarea>
        </div>

        <div>
            <label class="block text-on-surface-variant font-label-caps uppercase tracking-wider text-xs mb-2">Full Story</label>
            <textarea name="story" rows="4" class="w-full bg-surface-container-high border-none rounded py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary focus:outline-none"><?= htmlspecialchars($transformation['story']) ?></textarea>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="status" id="status" <?= $transformation['status'] == 'active' ? 'checked' : '' ?> class="w-5 h-5 bg-surface-container-high border-white/20 rounded text-primary focus:ring-primary focus:ring-offset-surface">
            <label for="status" class="text-on-surface">Transformation is Active</label>
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-end gap-4">
            <a href="transformations.php" class="px-6 py-3 rounded text-on-surface hover:bg-white/5 transition-colors font-label-caps uppercase tracking-wider">Cancel</a>
            <button type="submit" class="bg-primary-container text-black font-label-caps px-8 py-3 rounded uppercase tracking-widest hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">
                Save Transformation
            </button>
        </div>
    </div>
</form>
</div>
</div>
</main>
<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

