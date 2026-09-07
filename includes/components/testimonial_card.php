<?php
$quote = $quote ?? '';
$author_name = $author_name ?? '';
$author_role = $author_role ?? '';
$author_location = $author_location ?? '';
$author_image = $author_image ?? '';
$rating = $rating ?? 5;
// Remove featured, span_cols, with_image, side_image variables as we want uniform cards
?>
<div class="glass-card neon-glow rounded-xl transition-all duration-300 flex flex-col group p-6 h-full">
<?php require __DIR__ . '/star_rating.php'; ?>

<div class="flex-grow flex items-center">
<p class="font-body-lg text-body-lg text-on-surface mb-0 leading-relaxed">"<?php echo e($quote); ?>"</p>
</div>
<div class="flex items-center mt-4">
<?php if (!empty($author_image)): ?>
<img class="w-12 h-12 rounded-full object-cover border-2 border-surface-variant mr-4 flex-shrink-0" src="<?php echo e($author_image); ?>" alt="<?php echo e($author_name); ?>"/>
<?php endif; ?>
<div class="min-w-0">
<h4 class="font-label-caps text-label-caps text-on-surface truncate"><?php echo e($author_name); ?></h4>
<p class="font-body-md text-body-md text-on-surface-variant text-sm truncate"><?php echo e($author_role); ?></p>
<?php if (!empty($author_location)): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-1 flex items-center">
<span class="material-symbols-outlined text-[14px] mr-1">location_on</span>
<?php echo e($author_location); ?>
</p>
<?php endif; ?>
</div>
</div>
</div>
