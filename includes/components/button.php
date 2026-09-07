<?php
$variant = $variant ?? 'primary';
$text = $text ?? 'Click Me';
$link = $link ?? '#';
$size = $size ?? 'md';
$icon = $icon ?? '';

$sizeClasses = [
    'sm' => 'px-4 py-2',
    'md' => 'px-6 py-3',
    'lg' => 'px-8 py-4 text-lg'
];

$variantClasses = [
    'primary' => 'bg-primary text-on-primary hover:shadow-[0_0_20px_theme(\'colors.primary\')]',
    'container' => 'bg-primary-container text-black hover:shadow-[0_0_20px_rgba(34,197,94,0.6)]',
    'ghost' => 'glass-panel text-on-surface hover:border-primary/50 hover:bg-white/5',
    'ghost-primary' => 'bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-on-primary hover:shadow-[0_0_20px_rgba(34,197,94,0.3)]',
    'glass-primary' => 'glass-panel text-primary hover:bg-white/5'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
?>
<a href="<?php echo e($link); ?>" class="<?php echo $variantClass; ?> <?php echo $sizeClass; ?> rounded font-label-caps text-label-caps uppercase transition-all duration-300 active:scale-95 inline-flex items-center gap-2">
<?php if (!empty($icon)): ?>
<span class="material-symbols-outlined text-[18px]"><?php echo e($icon); ?></span>
<?php endif; ?>
<?php echo e($text); ?>
</a>
