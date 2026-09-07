<?php
$icon = $icon ?? 'workspace_premium';
$title = $title ?? 'Credential';
$description = $description ?? '';
?>
<div class="glass-panel p-8 rounded-xl flex flex-col gap-4 hover:border-primary/50 transition-colors duration-300">
<span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;"><?php echo e($icon); ?></span>
<h3 class="font-headline-md text-headline-md text-on-surface"><?php echo e($title); ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?php echo e($description); ?></p>
</div>
