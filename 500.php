<?php 
$pageTitle = '500 - Server Error'; 
$pageDescription = 'An unexpected error occurred on our server. Our team has been notified and is working to resolve the issue.';
require __DIR__ . '/includes/head.php'; 
require __DIR__ . '/includes/helpers.php';

// Set proper HTTP status code
http_response_code(500);
?>
<body class="font-body-md antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow flex items-center justify-center pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<div class="text-center max-w-2xl">
<div class="mb-8">
<span class="material-symbols-outlined text-error text-8xl" style="font-variation-settings: 'FILL' 1;">report_problem</span>
</div>
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-6">500</h1>
<h2 class="font-headline-md text-headline-md text-on-surface mb-4">Server Error</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-8">An unexpected error occurred on our server. Our team has been notified and is working to resolve the issue as quickly as possible.</p>
<div class="flex flex-col sm:flex-row gap-4 justify-center">
<a href="<?php echo site_url('index.php'); ?>" class="bg-primary text-on-primary-fixed font-label-caps text-label-caps px-8 py-4 uppercase tracking-wider rounded-DEFAULT glow-hover transition-all duration-300">
Return Home
</a>
<a href="<?php echo site_url('contact.php'); ?>" class="glass-panel text-on-surface font-label-caps text-label-caps px-8 py-4 uppercase tracking-wider rounded-DEFAULT hover:border-white/30 transition-all duration-300">
Contact Support
</a>
</div>
</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>