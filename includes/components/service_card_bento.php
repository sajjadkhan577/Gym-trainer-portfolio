<?php
$image = $image ?? '';
$icon = $icon ?? 'fitness_center';
$title = $title ?? 'Service Title';
$description = $description ?? '';
$colSpan = $colSpan ?? '';
$height = $height ?? '400px';
$colSpanClass = !empty($colSpan) ? "md:col-span-{$colSpan}" : '';
?>
<div class="group relative rounded-xl overflow-hidden glass-panel <?php echo $colSpanClass; ?> h-[<?php echo e($height); ?>]">
<div class="absolute inset-0 z-0 transition-transform duration-700 group-hover:scale-105">
<div class="w-full h-full bg-cover bg-center" style="<?php if (!empty($image)): ?>background-image: url('<?php echo e($image); ?>');<?php endif; ?>"></div>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent z-10"></div>
<div class="absolute inset-0 z-20 p-8 flex flex-col justify-end">
<div class="bg-primary/20 w-12 h-12 rounded flex items-center justify-center mb-4 border border-primary/30">
<span class="material-symbols-outlined text-primary"><?php echo e($icon); ?></span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2"><?php echo e($title); ?></h3>
<?php if (!empty($description)): ?>
<p class="font-body-md text-body-md text-on-surface-variant line-clamp-2"><?php echo e($description); ?></p>
<?php endif; ?>
</div>
</div>
