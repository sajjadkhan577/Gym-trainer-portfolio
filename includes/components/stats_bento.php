<?php
$stats = $stats ?? [
    ['value' => '10+', 'label' => 'Years Exp.'],
    ['value' => '500+', 'label' => 'Happy Clients'],
    ['value' => '100+', 'label' => 'Programs'],
    ['value' => '98%', 'label' => 'Success Rate']
];
?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<?php foreach ($stats as $stat): ?>
<div class="glass-panel p-4 rounded-lg flex flex-col">
<span class="font-headline-md text-headline-md text-primary mb-1"><?php echo e($stat['value']); ?></span>
<span class="font-label-caps text-label-caps text-on-surface-variant"><?php echo e($stat['label']); ?></span>
</div>
<?php endforeach; ?>
</div>
