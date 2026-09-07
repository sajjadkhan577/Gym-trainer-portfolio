<?php
$label = $label ?? 'Progress';
$percentage = $percentage ?? 0;
$showLabel = $showLabel ?? true;
?>
<div class="flex flex-col gap-2">
<div class="flex justify-between font-label-caps text-label-caps text-on-surface uppercase">
<span><?php echo e($label); ?></span>
<span class="text-primary"><?php echo e($percentage); ?>%</span>
</div>
<div class="h-2 w-full bg-surface-container-high rounded-full overflow-hidden glass-panel" data-progress="<?php echo e($percentage); ?>">
<div class="h-full bg-primary progress-bar-fill rounded-full" style="width: 0%;"></div>
</div>
</div>
