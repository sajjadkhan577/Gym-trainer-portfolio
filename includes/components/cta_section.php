<?php
$icon = $icon ?? 'bolt';
$title = $title ?? 'Start Your Journey Today';
$subtitle = $subtitle ?? 'Stop settling for average. Commit to excellence and transform your potential into performance.';
$buttonText = $buttonText ?? 'CLAIM YOUR SPOT';
$buttonLink = $buttonLink ?? 'book.php';
?>
<section class="py-[120px] relative overflow-hidden">
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/10 rounded-full blur-[120px] z-0 pointer-events-none"></div>
<div class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop relative z-10 text-center glass-panel p-16 rounded-2xl">
<span class="material-symbols-outlined text-[48px] text-primary mb-6 block"><?php echo e($icon); ?></span>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-6"><?php echo e($title); ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-10"><?php echo e($subtitle); ?></p>
<a href="<?php echo site_url($buttonLink); ?>" class="bg-primary text-on-primary font-label-caps text-label-caps px-10 py-5 rounded hover:shadow-[0_0_30px_theme('colors.primary')] transition-all duration-300 text-lg inline-block">
<?php echo e($buttonText); ?>
</a>
</div>
</section>
