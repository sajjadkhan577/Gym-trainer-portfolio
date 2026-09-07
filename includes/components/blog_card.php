<?php
$image = $image ?? '';
$category = $category ?? 'TRAINING';
$title = $title ?? 'Blog Post Title';
$excerpt = $excerpt ?? '';
$date = $date ?? '';
$readTime = $readTime ?? '';
$featured = $featured ?? false;
$slug = $slug ?? '';
$postUrl = !empty($slug) ? site_url('blog/' . $slug . '/') : '#';
?>
<article class="<?php echo $featured
    ? 'relative w-full h-[600px] rounded-2xl overflow-hidden mb-24 group cursor-pointer'
    : 'glass-panel rounded-xl overflow-hidden flex flex-col group cursor-pointer hover:border-white/30 transition-all duration-300'; ?>">
<a href="<?php echo e($postUrl); ?>" class="block h-full">
<div class="<?php echo $featured
    ? 'absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105'
    : 'relative h-64 overflow-hidden'; ?>">
<?php if ($featured): ?>
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style="background-image: url('<?php echo e($image); ?>');"></div>
<?php else: ?>
<img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"/>
<?php endif; ?>
</div>
<?php if ($featured): ?>
<div class="absolute inset-0 card-gradient flex flex-col justify-end p-8 md:p-12">
<div class="glass-panel w-fit px-4 py-1 rounded-full mb-6 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-primary"></span>
<span class="font-label-caps text-label-caps text-on-surface tracking-widest"><?php echo e($category); ?></span>
</div>
<h2 class="font-headline-md text-headline-md text-on-surface mb-4 max-w-3xl group-hover:text-primary transition-colors duration-300"><?php echo e($title); ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-8"><?php echo e($excerpt); ?></p>
<div class="flex items-center gap-4 text-on-surface-variant font-body-md text-body-md">
<?php if (!empty($date)): ?><span><?php echo e($date); ?></span><?php endif; ?>
<?php if (!empty($date) && !empty($readTime)): ?><span>•</span><?php endif; ?>
<?php if (!empty($readTime)): ?><span><?php echo e($readTime); ?></span><?php endif; ?>
</div>
</div>
<?php else: ?>
<div class="p-6 flex flex-col flex-grow">
<span class="font-label-caps text-label-caps text-primary mb-3 block"><?php echo e($category); ?></span>
<h3 class="font-headline-md text-[24px] text-on-surface mb-3 leading-tight group-hover:text-primary transition-colors"><?php echo e($title); ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-6 flex-grow"><?php echo e($excerpt); ?></p>
<div class="flex justify-between items-center text-sm text-on-surface-variant">
<?php if (!empty($date)): ?><span><?php echo e($date); ?></span><?php endif; ?>
<span class="material-symbols-outlined group-hover:translate-x-2 transition-transform text-primary">arrow_forward</span>
</div>
</div>
<?php endif; ?>
</a>
</article>
