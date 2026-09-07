<?php 
$pageTitle = 'Careers'; 
$pageDescription = 'Join the Apex Elite Performance team. View current career opportunities and become part of our elite coaching staff.';
require __DIR__ . '/includes/head.php'; 
?>
<body class="antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<section class="mb-[120px]">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-8 text-neon">
Join The Elite
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mb-12">
We're always looking for exceptional coaches and support staff who share our passion for transforming lives through elite performance training. If you're committed to excellence and want to make a real impact, we want to hear from you.
</p>

<section class="mb-16">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-8 border-b border-white/10 pb-4">Current Openings</h2>
<div class="space-y-6">
<section class="glass-panel p-8 rounded-xl">
<div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Elite Performance Coach</h3>
<span class="font-label-caps text-label-caps text-primary uppercase">Full-Time</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Join our team of elite coaches working with professional athletes and dedicated clients. Must have CSCS certification and minimum 3 years experience in performance training.
</p>
<div class="flex flex-wrap gap-2 mb-4">
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">CSCS Required</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">3+ Years Experience</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Performance Focus</span>
</div>
<a href="mailto:<?php echo e(CONTACT_EMAIL); ?>?subject=Elite Performance Coach Application" class="text-primary hover:text-primary-fixed font-label-caps text-label-caps uppercase transition-colors">
Apply Now →
</a>
</section>

<section class="glass-panel p-8 rounded-xl">
<div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Nutrition Coach</h3>
<span class="font-label-caps text-label-caps text-primary uppercase">Part-Time</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Support our clients with precision nutrition guidance. Must have Precision Nutrition Level 1 certification or equivalent, with experience in sports nutrition.
</p>
<div class="flex flex-wrap gap-2 mb-4">
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">PN Level 1 Required</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Sports Nutrition</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Flexible Hours</span>
</div>
<a href="mailto:<?php echo e(CONTACT_EMAIL); ?>?subject=Nutrition Coach Application" class="text-primary hover:text-primary-fixed font-label-caps text-label-caps uppercase transition-colors">
Apply Now →
</a>
</section>

<section class="glass-panel p-8 rounded-xl">
<div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Client Success Coordinator</h3>
<span class="font-label-caps text-label-caps text-primary uppercase">Full-Time</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">
Manage client relationships, coordinate schedules, and ensure exceptional service delivery. Must have excellent communication skills and experience in client-facing roles.
</p>
<div class="flex flex-wrap gap-2 mb-4">
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Client Management</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Communication</span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface text-sm rounded">Organization</span>
</div>
<a href="mailto:<?php echo e(CONTACT_EMAIL); ?>?subject=Client Success Coordinator Application" class="text-primary hover:text-primary-fixed font-label-caps text-label-caps uppercase transition-colors">
Apply Now →
</a>
</section>
</div>
</section>

<section class="mb-16">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-8 border-b border-white/10 pb-4">Why Work With Us</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<section class="glass-panel p-6 rounded-xl">
<div class="material-symbols-outlined text-4xl text-primary mb-4">workspace_premium</div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-3">Elite Environment</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
Work alongside world-class coaches and support staff in a high-performance environment dedicated to excellence.
</p>
</section>

<section class="glass-panel p-6 rounded-xl">
<div class="material-symbols-outlined text-4xl text-primary mb-4">trending_up</div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-3">Growth Opportunities</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
Continuous education, certification support, and clear pathways for advancement within our organization.
</p>
</section>

<section class="glass-panel p-6 rounded-xl">
<div class="material-symbols-outlined text-4xl text-primary mb-4">groups</div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-3">Impact</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
Make a real difference in people's lives every day, helping clients achieve goals they never thought possible.
</p>
</section>
</div>
</section>

<section>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-8 border-b border-white/10 pb-4">Application Process</h2>
<div class="space-y-4">
<div class="flex items-start gap-4">
<div class="w-8 h-8 bg-primary text-on-primary rounded-full flex items-center justify-center font-label-caps text-label-caps flex-shrink-0">1</div>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Submit Application</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Send your resume and cover letter to <?php echo e(CONTACT_EMAIL); ?></p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-8 h-8 bg-primary text-on-primary rounded-full flex items-center justify-center font-label-caps text-label-caps flex-shrink-0">2</div>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Initial Screening</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Phone interview to discuss your experience and alignment with our values</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-8 h-8 bg-primary text-on-primary rounded-full flex items-center justify-center font-label-caps text-label-caps flex-shrink-0">3</div>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Practical Assessment</h3>
<p class="font-body-md text-body-md text-on-surface-variant">In-person or virtual coaching demonstration and skills assessment</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="w-8 h-8 bg-primary text-on-primary rounded-full flex items-center justify-center font-label-caps text-label-caps flex-shrink-0">4</div>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface uppercase mb-2">Final Interview</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Meet the team and discuss role specifics, compensation, and start date</p>
</div>
</div>
</div>
</section>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>