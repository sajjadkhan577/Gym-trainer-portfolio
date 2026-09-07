<?php
$image = $image ?? '';
$level = $level ?? 'Beginner';
$levelColor = $levelColor ?? 'bg-surface-container-high text-on-surface border-white/10';
$duration = $duration ?? '12 Weeks';
$title = $title ?? 'Program Name';
$description = $description ?? '';
$tags = $tags ?? [];
$features = $features ?? [];
$price = $price ?? '';
$buttonLink = $buttonLink ?? 'book.php';
$colSpan = $colSpan ?? '';
$colSpanClass = !empty($colSpan) ? "lg:col-span-{$colSpan}" : '';
?>
<div class="glass-panel rounded-xl overflow-hidden relative group h-[520px] flex flex-col justify-end hover-glow transition-all duration-300 <?php echo $colSpanClass; ?>">
<div class="absolute inset-0 bg-cover bg-center z-0 opacity-60 group-hover:scale-105 transition-transform duration-700" style="background-image: url('<?php echo e($image); ?>');" loading="lazy"></div>
<div class="absolute inset-0 card-gradient z-10"></div>
<div class="relative z-20 p-6 space-y-4 md:w-2/3">
<div class="flex justify-between items-center max-w-xs">
<span class="font-label-caps text-label-caps <?php echo e($levelColor); ?> px-3 py-1 rounded-full border"><?php echo e($level); ?></span>
<span class="font-label-caps text-label-caps text-primary flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]">schedule</span>
<?php echo e($duration); ?>
</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase"><?php echo e($title); ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?php echo e($description); ?></p>
<?php if (!empty($tags)): ?>
<div class="flex gap-2 flex-wrap">
<?php foreach ($tags as $tag): ?>
<span class="font-label-caps text-label-caps bg-white/5 text-on-surface px-2 py-1 rounded border border-white/5"><?php echo e($tag); ?></span>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if (!empty($features)): ?>
<div class="space-y-1">
<?php foreach ($features as $feature): ?>
<div class="flex items-center text-xs text-on-surface-variant">
<span class="material-symbols-outlined text-primary mr-1 text-[14px]">check</span>
<?php echo e($feature); ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="flex justify-between items-center pt-2">
<?php if (!empty($price)): ?>
<span class="font-headline-md text-headline-md text-primary">$<?php echo number_format($price, 2); ?></span>
<?php endif; ?>
<a href="<?php echo site_url($buttonLink); ?>" class="bg-primary text-on-primary font-label-caps text-label-caps px-4 py-2 rounded hover:bg-primary/90 transition-all duration-300 text-sm uppercase tracking-wider">
Enroll
</a>
</div>
</div>
</div>
