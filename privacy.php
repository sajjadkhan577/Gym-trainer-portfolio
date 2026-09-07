<?php 
$pageTitle = 'Privacy Policy'; 
$pageDescription = 'Privacy Policy for Apex Elite Performance - Learn how we protect your personal information and data.';
require __DIR__ . '/includes/head.php'; 
?>
<body class="antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<section class="mb-[120px]">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-8 text-neon">
Privacy Policy
</h1>
<p class="font-body-md text-body-md text-on-surface-variant mb-12">
Last Updated: <?php echo date('F d, Y'); ?>
</p>

<div class="space-y-12">
<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Information We Collect</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
At Apex Elite Performance, we collect information you provide directly to us, including:
</p>
<ul class="list-disc list-inside font-body-md text-body-md text-on-surface-variant space-y-2 ml-4">
<li>Name and contact information</li>
<li>Health and fitness data</li>
<li>Training preferences and goals</li>
<li>Payment information</li>
<li>Communication preferences</li>
</ul>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">How We Use Your Information</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
We use the information we collect to:
</p>
<ul class="list-disc list-inside font-body-md text-body-md text-on-surface-variant space-y-2 ml-4">
<li>Provide and improve our coaching services</li>
<li>Process transactions and send related information</li>
<li>Send technical notices and support messages</li>
<li>Respond to comments and questions</li>
<li>Monitor and analyze trends usage and activities</li>
</ul>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Data Security</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.
</p>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Your Rights</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
You have the right to:
</p>
<ul class="list-disc list-inside font-body-md text-body-md text-on-surface-variant space-y-2 ml-4">
<li>Access your personal data</li>
<li>Correct inaccurate data</li>
<li>Request deletion of your data</li>
<li>Opt-out of marketing communications</li>
</ul>
</section>

<section>
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-6 border-b border-white/10 pb-4">Contact Us</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
If you have questions about this Privacy Policy, please contact us at:
</p>
<p class="font-body-md text-body-md text-on-surface-variant">
<?php echo e(CONTACT_EMAIL); ?>
</p>
</section>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>