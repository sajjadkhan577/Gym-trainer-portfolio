<?php
$beforeImage = $beforeImage ?? '';
$afterImage = $afterImage ?? '';
$name = $name ?? '';
$beforeLabel = $beforeLabel ?? 'DAY 01';
$afterLabel = $afterLabel ?? 'DAY 180';
$stats = $stats ?? [];
$quote = $quote ?? '';
$goal = $goal ?? '';
$weightLost = $weightLost ?? '';
$story = $story ?? '';
$duration = $duration ?? '';
$span = $span ?? '';
$featured = $featured ?? false;

$spanClass = !empty($span) ? "lg:col-span-{$span}" : '';
?>
<div class="glass-panel rounded-xl overflow-hidden <?php echo $spanClass; ?> flex flex-col relative group transformation-card">
<?php if ($featured): ?>
<div class="absolute inset-0 bg-gradient-to-t from-surface-lowest to-transparent z-10 pointer-events-none opacity-80"></div>
<?php endif; ?>

<!-- Image Section - same layout for all cards -->
<div class="flex flex-col md:flex-row h-56 md:h-64">
<div class="w-full md:w-1/2 h-1/2 md:h-full relative">
<img class="w-full h-full object-cover grayscale opacity-70 cursor-pointer hover:grayscale-0 transition-all duration-300" src="<?php echo e($beforeImage); ?>" alt="<?php echo e($name); ?> - Before" loading="lazy"/>
<div class="absolute top-3 left-3 z-20 bg-surface-container/80 px-2 py-1 rounded font-label-caps text-label-caps text-on-surface text-xs"><?php echo e($beforeLabel); ?></div>
</div>
<div class="w-full md:w-1/2 h-1/2 md:h-full relative border-t md:border-t-0 md:border-l border-white/5">
<img class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-all duration-300" src="<?php echo e($afterImage); ?>" alt="<?php echo e($name); ?> - After" loading="lazy"/>
<div class="absolute top-3 right-3 z-20 bg-primary/20 border border-primary px-2 py-1 rounded font-label-caps text-label-caps text-primary text-xs"><?php echo e($afterLabel); ?></div>
</div>
</div>
<?php if ($featured): ?>
<div class="absolute bottom-0 left-0 w-full p-4 md:p-8 z-20 bg-gradient-to-t from-surface to-transparent">
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2"><?php echo e($name); ?></h3>
<?php if (!empty($goal)): ?>
<p class="font-label-caps text-label-caps text-primary mb-2"><?php echo e($goal); ?></p>
<?php endif; ?>
<?php if (!empty($stats)): ?>
<div class="flex flex-wrap gap-4 mb-4">
<?php foreach ($stats as $stat): ?>
<div class="flex flex-col">
<span class="font-label-caps text-label-caps text-on-surface-variant"><?php echo e($stat['label']); ?></span>
<span class="font-headline-md text-headline-md <?php echo $stat['highlight'] ? 'text-primary' : 'text-on-surface'; ?>"><?php echo e($stat['value']); ?></span>
</div>
<?php if (next($stats)): ?><div class="w-px bg-white/10"></div><?php endif; ?>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if (!empty($duration)): ?>
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
<span class="font-label-caps text-label-caps text-on-surface-variant"><?php echo e($duration); ?></span>
</div>
<?php endif; ?>
<?php if (!empty($quote)): ?>
<p class="font-body-md text-body-md text-on-surface-variant max-w-lg">"<?php echo e($quote); ?>"</p>
<?php endif; ?>
<?php if (!empty($story)): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2 max-w-lg line-clamp-2"><?php echo e($story); ?></p>
<?php endif; ?>
</div>
<?php else: ?>
<div class="p-4 md:p-6 min-h-[180px] flex flex-col justify-center">
<h3 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface uppercase mb-1 truncate"><?php echo e($name); ?></h3>
<?php if (!empty($goal)): ?>
<p class="font-label-caps text-label-caps text-primary mb-2 truncate"><?php echo e($goal); ?></p>
<?php endif; ?>
<?php if (!empty($stats)): ?>
<div class="flex flex-wrap gap-2 md:gap-4 mb-3">
<?php foreach ($stats as $stat): ?>
<span class="font-label-caps text-label-caps <?php echo $stat['highlight'] ? 'text-primary' : 'text-on-surface'; ?>"><?php echo e($stat['value']); ?> <?php echo e($stat['label']); ?></span>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if (!empty($weightLost)): ?>
<div class="flex items-center gap-2 mb-3">
<span class="material-symbols-outlined text-primary text-[16px]">trending_down</span>
<span class="font-label-caps text-label-caps text-on-surface-variant"><?php echo e($weightLost); ?></span>
</div>
<?php endif; ?>
<?php if (!empty($duration)): ?>
<div class="flex items-center gap-2 mb-3">
<span class="material-symbols-outlined text-primary text-[16px]">schedule</span>
<span class="font-label-caps text-label-caps text-on-surface-variant"><?php echo e($duration); ?></span>
</div>
<?php endif; ?>
<?php if (!empty($quote)): ?>
<p class="font-body-md text-body-md text-on-surface-variant line-clamp-2">"<?php echo e($quote); ?>"</p>
<?php endif; ?>
<?php if (!empty($story)): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2 line-clamp-2"><?php echo e($story); ?></p>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
