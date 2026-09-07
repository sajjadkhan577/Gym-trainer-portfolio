<?php 
$pageTitle = 'About'; 
$pageDescription = 'Meet the elite coaching team at Apex Elite Performance. Our certified trainers bring decades of experience in athletic performance, strength training, and competitive fitness.';
require __DIR__ . '/includes/head.php'; 
?>
<body class="antialiased min-h-screen flex flex-col">
<?php require __DIR__ . '/includes/navigation.php'; ?>
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<section class="grid grid-cols-1 md:grid-cols-12 gap-gutter mb-[120px] items-center">
<div class="md:col-span-5 relative">
<div class="aspect-[4/5] glass-panel overflow-hidden relative group rounded-xl">
<img class="w-full h-full object-cover grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWrcnfKCyrhyA6d08z21_ixeCrIDPFnWhUZlTtACSJqkzLPypE4fRU_gWhMJus93Y_BGYhbWGRLnAmi4uwa78jLmfg3JKFgkBodq6KztuWLV4Yo6-1-jVNhp5ZbvoxEQ8vtcwOb9eErMdaeXtM-RgJ1GqmmtnA_yatxzHDtz7DIipRSb6o0KNgYFYLJzGOZjxiXTPfFFInlq0mIz7Q3SfU_A_H4SzAH445AsWOZH0nGzFh7nRtLw0lAQ" alt="Coach Portrait"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface-lowest via-transparent to-transparent opacity-60"></div>
</div>
</div>
<div class="md:col-span-7 md:pl-12 flex flex-col justify-center">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-6 text-neon">
Discipline.<br/>Precision.<br/><span class="text-primary">Results.</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-8 max-w-2xl">
I forge elite athletes and dedicated individuals into their absolute peak physical condition. My methodology is rooted in science, tested in the trenches, and refined over a decade of relentless pursuit of excellence. There are no shortcuts, only the work.
</p>
<div class="flex gap-4">
<a href="<?php echo site_url('book.php'); ?>" class="bg-primary-container text-on-primary font-label-caps text-label-caps px-8 py-4 uppercase hover:shadow-[0_0_20px_#22C55E] transition-all duration-300 inline-block">
Join The Ranks
</a>
</div>
</div>
</section>

<section class="mb-[120px]">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-12 border-b border-white/10 pb-4">Core Competencies</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-gutter gap-y-8">
<?php
$skills = [
    ['label' => 'Strength Training', 'percentage' => 95],
    ['label' => 'HIIT & Conditioning', 'percentage' => 90],
    ['label' => 'Performance Nutrition', 'percentage' => 85],
    ['label' => 'Active Recovery', 'percentage' => 98]
];
foreach ($skills as $s) {
    $label = $s['label'];
    $percentage = $s['percentage'];
    require __DIR__ . '/includes/components/progress_bar.php';
}
?>
</div>
</section>

<section class="mb-[120px]">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface uppercase mb-12 border-b border-white/10 pb-4">Credentials</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<?php
$credentials = [
    ['icon' => 'workspace_premium', 'title' => 'CSCS', 'description' => 'Certified Strength and Conditioning Specialist (NSCA)'],
    ['icon' => 'verified', 'title' => 'Precision Nutrition Level 2', 'description' => 'Master Class Certification in Sports Nutrition'],
    ['icon' => 'sports_martial_arts', 'title' => 'FMS Level 2', 'description' => 'Functional Movement Systems Advanced Professional']
];
foreach ($credentials as $c) {
    extract($c);
    require __DIR__ . '/includes/components/credential_card.php';
}
?>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
