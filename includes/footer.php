<footer class="w-full relative bottom-0 bg-surface-container-lowest border-t border-white/5">
<div class="grid grid-cols-1 md:grid-cols-4 gap-base px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto">
<div class="md:col-span-2 mb-8 md:mb-0">
<div class="font-headline-md text-headline-md text-on-surface mb-4">
<?php echo e(SITE_NAME); ?>
</div>
<p class="font-body-sm text-body-md text-on-surface-variant max-w-sm mb-6">
Redefining human potential through elite performance coaching and uncompromising standards.
</p>
<div class="font-body-sm text-body-md text-on-surface-variant">
© <?php echo date('Y'); ?> APEX ELITE PERFORMANCE. ALL RIGHTS RESERVED.
</div>
</div>
<?php foreach (FOOTER_LINKS as $column => $links): ?>
<div class="col-span-1">
<h4 class="font-label-caps text-label-caps text-on-surface uppercase mb-4 tracking-widest"><?php echo e($column); ?></h4>
<ul class="space-y-3">
<?php foreach ($links as $label => $url): ?>
<li><a class="font-body-sm text-body-md text-on-surface-variant hover:text-primary-fixed transition-colors cursor-pointer" href="<?php echo e($url); ?>"><?php echo e($label); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endforeach; ?>
</div>
</footer>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
</body>
</html>