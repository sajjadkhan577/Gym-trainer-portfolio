<?php 
$pageTitle = 'Terms of Service'; 
$pageDescription = 'Terms of Service for Apex Elite Performance - Understand the terms and conditions of using our coaching services.';
require __DIR__ . '/includes/head.php'; 
?>
<body class="antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<section class="mb-[120px]">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-8 text-neon">
Terms of Service
</h1>
<p class="font-body-md text-body-md text-on-surface-variant mb-12">
Last Updated: <?php echo date('F d, Y'); ?>
</p>

<div class="space-y-12">
<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Acceptance of Terms</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
By accessing and using Apex Elite Performance services, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to abide by these terms, please do not use our services.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Services Description</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Apex Elite Performance provides elite fitness coaching, personal training, and athletic performance programs. We reserve the right to modify, suspend, or discontinue any aspect of our services at any time without prior notice.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Client Responsibilities</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
As a client, you agree to:
</p>
<ul class="list-disc list-inside font-body-md text-body-md text-on-surface-variant space-y-2 ml-4">
<li>Provide accurate health and fitness information</li>
<li>Follow safety guidelines during training sessions</li>
<li>Communicate any injuries or health concerns immediately</li>
<li>Respect scheduled appointment times</li>
<li>Pay all fees in a timely manner</li>
</ul>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Payment Terms</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
All fees are non-refundable unless otherwise specified. Cancellations must be made at least 24 hours in advance to avoid being charged for the session. No-shows will be charged in full.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Health Disclaimer</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Our coaching services are not a substitute for medical advice, diagnosis, or treatment. Always consult your physician or other qualified health provider before starting any fitness program.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Limitation of Liability</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Apex Elite Performance shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of your access to or use of our services.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Contact Information</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
For questions about these Terms of Service, please contact:
</p>
<p class="font-body-md text-body-md text-on-surface-variant">
<?php echo e(CONTACT_EMAIL); ?>
</p>
</section>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>