<?php
$image = $image ?? '';
$video = $video ?? '';
$category = $category ?? 'WORKOUT';
$title = $title ?? '';
$aspect = $aspect ?? '3/4';
$isVideo = $isVideo ?? false;
$description = $description ?? '';
?>
<div class="masonry-item relative group rounded-xl overflow-hidden glass-panel cursor-pointer <?php echo $isVideo ? 'is-video' : ''; ?>" data-gallery-item data-src="<?php echo e($image); ?>" data-video="<?php echo e($video); ?>" data-filter-item data-filter-category="<?php echo e(strtolower($category)); ?>">
<div class="skeleton-loader absolute inset-0 z-0"></div>
<img class="w-full h-auto object-cover aspect-[<?php echo e($aspect); ?>] transition-transform duration-700 group-hover:scale-105 relative z-10" data-src="<?php echo e($image); ?>" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" alt="<?php echo e($title); ?>" loading="lazy"/>
<?php if ($isVideo): ?>
<div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
<div class="w-16 h-16 rounded-full glass-panel flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</div>
</div>
<?php endif; ?>
<div class="absolute inset-0 gallery-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 <?php echo $isVideo ? 'pointer-events-none' : ''; ?>">
<span class="inline-block px-3 py-1 <?php echo $category === 'GYM'
    ? 'bg-surface-bright/50 border border-white/30 text-on-surface'
    : 'bg-primary/20 border border-primary text-primary'; ?> font-label-caps text-[10px] rounded mb-2 w-fit">
<?php echo e($category); ?>
</span>
<h3 class="font-headline-md text-headline-md text-on-surface"><?php echo e($title); ?></h3>
<?php if (!empty($description)): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 line-clamp-2"><?php echo e($description); ?></p>
<?php endif; ?>
</div>
</div>
