<?php
$rating = $rating ?? 5;
$size = $size ?? 'default';
$sizeClass = $size === 'sm' ? 'text-[16px]' : '';
?>
<div class="flex items-center space-x-1 text-primary">
<?php for ($i = 1; $i <= 5; $i++): ?>
<?php if ($i <= $rating): ?>
<span class="material-symbols-outlined <?php echo $sizeClass; ?>" style="font-variation-settings: 'FILL' 1;">star</span>
<?php else: ?>
<span class="material-symbols-outlined <?php echo $sizeClass; ?>" style="font-variation-settings: 'FILL' 0;">star</span>
<?php endif; ?>
<?php endfor; ?>
</div>
