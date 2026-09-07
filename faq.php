<?php 
$pageTitle = 'FAQ'; 
$pageDescription = 'Frequently Asked Questions about Apex Elite Performance coaching services, programs, and policies.';
require __DIR__ . '/includes/head.php'; 
?>
<body class="antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<section class="mb-[120px]">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-8 text-neon">
Frequently Asked Questions
</h1>

<div class="space-y-8">
<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">What makes Apex Elite Performance different?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
We combine cutting-edge sports science with proven training methodologies used by elite athletes. Our programs are customized to your specific goals, backed by data-driven progress tracking, and delivered with uncompromising attention to form and technique.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">Do I need prior training experience?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
No. We work with clients of all fitness levels, from complete beginners to professional athletes. Our Foundation Protocol is designed specifically for those new to structured training, while our advanced programs cater to experienced athletes seeking peak performance.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">How long are your training programs?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
Our programs typically range from 8 to 24 weeks, depending on your goals and current fitness level. We recommend starting with a 12-week commitment to see meaningful results and establish sustainable habits.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">What is your cancellation policy?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
We require 24-hour notice for session cancellations. Late cancellations or no-shows will be charged the full session rate. For program cancellations, please refer to your specific program agreement or contact us directly.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">Do you offer nutrition coaching?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
Yes. Our Precision Nutrition Level 2 certified coaches provide comprehensive nutrition guidance tailored to your training goals, including meal planning, supplement recommendations, and ongoing accountability.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">Can I train with you if I have injuries?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
Absolutely. Our coaches are FMS Level 2 certified and experienced in working with clients recovering from injuries. We'll modify your program to work around limitations while helping you rebuild strength and mobility safely.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">What payment methods do you accept?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
We accept all major credit cards, debit cards, and bank transfers. Payment plans are available for longer programs. Contact us for corporate wellness program pricing.
</p>
</section>

<section class="glass-panel p-8 rounded-xl">
<h2 class="font-headline-md text-headline-md text-on-surface uppercase mb-4">How do I get started?</h2>
<p class="font-body-md text-body-md text-on-surface-variant">
The first step is to book a consultation session through our booking page. During this session, we'll assess your current fitness level, discuss your goals, and recommend the best program for your needs.
</p>
</section>
</div>

<div class="mt-12 text-center">
<p class="font-body-md text-body-md text-on-surface-variant mb-6">
Still have questions? Contact us directly.
</p>
<a href="<?php echo site_url('contact.php'); ?>" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-4 rounded hover:shadow-[0_0_20px_theme('colors.primary')] transition-all duration-300 inline-block">
Contact Us
</a>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>