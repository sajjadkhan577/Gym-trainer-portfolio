<?php
$eyebrow = $eyebrow ?? '';
$title = $title ?? '';
$subtitle = $subtitle ?? '';
$titleHighlight = $titleHighlight ?? '';
$align = $align ?? 'left';
$border = $border ?? false;
$alignClass = $align === 'center'
    ? 'text-center mx-auto justify-center'
    : 'text-left';
?>
<section class="<?php echo $border ? 'mb-16 pt-12 md:pt-24 flex flex-col md:flex-row justify-between items-end gap-8 border-b border-white/10 pb-8 ' : 'mb-16 '; ?>px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto <?php echo $align === 'center' ? 'py-20 ' : ''; ?>w-full">
<div class="<?php echo $align === 'center' ? 'max-w-3xl mx-auto space-y-6 text-center' : 'max-w-3xl'; ?>">
<?php if (!empty($eyebrow)): ?>
<span class="font-label-caps text-label-caps text-primary tracking-widest uppercase mb-4 block"><?php echo e($eyebrow); ?></span>
<?php endif; ?>
<h1 class="font-headline-lg-mobile md:font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface <?php echo !empty($titleHighlight) ? 'mb-6' : 'mb-4'; ?> uppercase">
<?php if (!empty($titleHighlight)): ?>
    <?php echo e($title); ?> <span class="text-primary"><?php echo e($titleHighlight); ?></span>
<?php else: ?>
    <?php echo e($title); ?>
<?php endif; ?>
</h1>
<?php if (!empty($subtitle)): ?>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl <?php echo $align === 'center' ? 'mx-auto' : ''; ?>"><?php echo e($subtitle); ?></p>
<?php endif; ?>
</div>
<?php if (isset($actions) && !empty($actions)): ?>
<div class="flex flex-wrap gap-4">
<?php echo $actions; ?>
</div>
<?php endif; ?>
</section>
