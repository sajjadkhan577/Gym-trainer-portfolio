<?php
$categories = $categories ?? ['ALL', 'NUTRITION', 'TRAINING', 'LIFESTYLE'];
$containerId = $containerId ?? '#filter-grid';
$currentCategory = isset($_GET['category']) ? strtoupper($_GET['category']) : 'ALL';
?>
<div class="flex flex-wrap gap-4" data-filter-container="<?php echo e($containerId); ?>">
<?php foreach ($categories as $idx => $cat): ?>
<a href="<?php echo site_url('blog.php?category=' . strtolower($cat)); ?>" 
   class="px-6 py-2 rounded-full font-label-caps text-label-caps transition-all <?php echo strtoupper($cat) === $currentCategory
    ? 'border-primary text-primary bg-primary/10'
    : 'border-white/20 text-on-surface bg-surface-container/50 hover:border-primary hover:text-primary';
?>" data-filter-button data-filter="<?php echo e(strtolower($cat)); ?>" data-filter-container="<?php echo e($containerId); ?>">
<?php echo e($cat); ?>
</a>
<?php endforeach; ?>
</div>
