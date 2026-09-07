<?php
$image = $image ?? '';
$title = $title ?? 'Service';
$description = $description ?? '';
$features = $features ?? [];
$benefits = $benefits ?? [];
$duration = $duration ?? '';
$price = $price ?? '';
$buttonText = $buttonText ?? 'Book Now';
$buttonLink = $buttonLink ?? 'book.php';
?>
<div class="glass-panel rounded-xl overflow-hidden flex flex-col group transition-all duration-300 hover:-translate-y-2 glow-hover">
<div class="h-48 relative overflow-hidden">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="lazy"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface via-transparent to-transparent"></div>
</div>
<div class="p-6 flex-grow flex flex-col">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2"><?php echo e($title); ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4 flex-grow"><?php echo e($description); ?></p>
<?php if (!empty($features)): ?>
<ul class="mb-4 space-y-2">
<?php foreach ($features as $feature): ?>
<li class="flex items-center text-sm text-tertiary">
<span class="material-symbols-outlined text-primary mr-2 text-[18px]">check_circle</span>
<?php echo e($feature); ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php if (!empty($benefits)): ?>
<div class="mb-4 space-y-2">
<p class="text-xs font-label-caps text-label-caps text-primary uppercase tracking-wider mb-2">Benefits</p>
<ul class="space-y-1">
<?php foreach ($benefits as $benefit): ?>
<li class="flex items-center text-xs text-on-surface-variant">
<span class="material-symbols-outlined text-primary mr-2 text-[14px]">arrow_right</span>
<?php echo e($benefit); ?>
</li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<?php if (!empty($duration) || !empty($price)): ?>
<div class="mb-4 flex justify-between items-center text-sm">
<?php if (!empty($duration)): ?>
<span class="text-on-surface-variant flex items-center">
<span class="material-symbols-outlined text-primary mr-1 text-[16px]">schedule</span>
<?php echo e($duration); ?>
</span>
<?php endif; ?>
<?php if (!empty($price)): ?>
<span class="text-primary font-headline-md text-headline-md">$<?php echo number_format($price, 2); ?></span>
<?php endif; ?>
</div>
<?php endif; ?>
<a href="<?php echo site_url($buttonLink); ?>" class="w-full py-3 bg-surface-container-high border border-primary text-primary rounded-DEFAULT font-label-caps text-label-caps uppercase hover:bg-primary hover:text-black transition-all duration-300 text-center inline-block">
<?php echo e($buttonText); ?>
</a>
</div>
</div>
