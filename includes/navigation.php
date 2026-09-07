<nav class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-md border-b border-white/10 shadow-xl shadow-primary/5">
<div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop py-4 max-w-container-max mx-auto">
<a class="font-headline-md text-headline-md font-black text-on-surface tracking-tighter active:scale-95 transition-transform" href="<?php echo site_url('index.php'); ?>"><?php echo e(SITE_NAME); ?></a>
<div class="hidden md:flex items-center gap-8">
<?php foreach (NAV_LINKS as $label => $url): ?>
    <?php $isActive = is_active_page($url); ?>
    <a class="<?php echo $isActive
        ? 'text-primary font-bold border-b-2 border-primary pb-1 '
        : 'text-on-surface/80 hover:text-primary transition-colors ';
    ?>font-body-md text-body-md uppercase tracking-wider hover:bg-white/5 transition-all duration-300 px-3 py-2 rounded" href="<?php echo site_url($url); ?>"><?php echo e($label); ?></a>
<?php endforeach; ?>
</div>
<div class="hidden md:block">
<a href="<?php echo site_url('book.php'); ?>" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 rounded hover:shadow-[0_0_20px_theme('colors.primary')] transition-all duration-300 active:scale-95 inline-block">Book Now</a>
</div>
<button class="md:hidden text-on-surface" data-mobile-menu-toggle>
<span class="material-symbols-outlined">menu</span>
</button>
</div>
<div class="md:hidden hidden bg-surface/95 backdrop-blur-md border-t border-white/10" data-mobile-menu>
<div class="flex flex-col px-margin-mobile py-4 gap-2">
<?php foreach (NAV_LINKS as $label => $url): ?>
    <?php $isActive = is_active_page($url); ?>
    <a class="<?php echo $isActive ? 'text-primary font-bold ' : 'text-on-surface/80 '; ?>font-body-md text-body-md uppercase tracking-wider py-2 px-3 rounded hover:bg-white/5" href="<?php echo site_url($url); ?>"><?php echo e($label); ?></a>
<?php endforeach; ?>
<a href="<?php echo site_url('book.php'); ?>" class="mt-2 bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 rounded text-center">Book Now</a>
</div>
</div>
</nav>
