<?php 
$pageTitle = 'Programs'; 
$pageDescription = 'Discover our specialized training programs for hypertrophy, strength, endurance, and fat loss. Customized fitness plans designed for serious athletes and fitness enthusiasts.';
require __DIR__ . '/includes/head.php';

// Load programs dynamically from database
$programs = db_select('programs', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');

require __DIR__ . '/includes/navigation.php'; 
?>
<main class="flex-grow">
<section class="py-20 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
<div class="text-center max-w-3xl mx-auto space-y-6">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase">Elite <span class="text-primary">Programs</span></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">Forged in intensity, refined by science. Select your path to peak performance. Every program is meticulously designed to push your limits and deliver undeniable results.</p>
</div>
</section>
<section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto pb-32">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<?php if ($programs && count($programs) > 0): ?>
    <?php foreach ($programs as $program): ?>
        <?php
        $programData = [
            'image' => $program['image'],
            'level' => $program['level'],
            'levelColor' => $program['level_color'],
            'duration' => $program['duration'],
            'title' => $program['title'],
            'description' => $program['description'],
            'tags' => json_decode($program['tags'], true) ?: [],
            'features' => json_decode($program['features'], true) ?: [],
            'price' => $program['price'],
            'colSpan' => $program['col_span'],
            'buttonLink' => 'book.php?program=' . $program['id']
        ];
        extract($programData);
        require __DIR__ . '/includes/components/program_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    // Fallback to static data if database is empty
    $programs = [
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWsTxOh3eY6-7JFYJp2RPnc0NW3dt7XiLugI_Z3IgXySOnAd4kJQhIluTH5MrnoLqfjIhJTImawcMwOdNJxFdmYJ1kf0PTqW3BksG76mbJlr9Pek2QZcfEzfqMudciP4ilC9h0R0-EHfMsIbdOQCMephmjDlpoEYDGxrVa7NgC2T2_K7h1qveeZphRi85ZRW6liEC-1fgc9RWj-tD2ThuiOnyRVlc2mDolAYLNS1NWDKI5ueEAXDMSig',
            'level' => 'Beginner', 'levelColor' => 'bg-surface-container-high text-on-surface border-white/10',
            'duration' => '12 Weeks',
            'title' => 'Foundation Protocol',
            'description' => 'Master the mechanics. Build base strength, correct imbalances, and prepare your body for elite loading.',
            'tags' => ['Form', 'Mobility'],
            'features' => ['Fundamental movement patterns', 'Base strength building', 'Injury prevention'],
            'price' => 299.00,
            'colSpan' => '',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBfevM-1h9xKhDjt0j7vcp8JjJo0v3BekaqakilQceDXib7FVwUjXY8pabwNpxkudUnH81vKVfH7uw9l1YxEQiY0mpPkyXPTdjwI_eSl75s2RTjv5z-OnsXNt9cBhwVp_XVxE8IHpOQRcLeFGnPANKvqXW6bav8pCk0KMJumXHSsr1KGlTH8CwAfJ7pwh4_DplEEr1U69up8xHMiBq8w0tnuf8XqUpesnf8AydtvJNCkKaoExAMnsKaqg',
            'level' => 'Intermediate', 'levelColor' => 'bg-surface-container-high text-on-surface border-white/10',
            'duration' => '16 Weeks',
            'title' => 'Hypertrophy Engine',
            'description' => 'Shift into high gear. Structured progressive overload designed to maximize muscle fiber recruitment and sustained growth.',
            'tags' => ['Volume', 'Density', 'Recovery'],
            'features' => ['Progressive overload', 'Muscle fiber recruitment', 'Sustained growth'],
            'price' => 399.00,
            'colSpan' => 2,
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6BHrhIuxwfKPmBbHzNmViMehjRx04sftdjeKcvMerP8w13si0KvRM_nKGiT6SFlWUWc3lwZTkpL2zXSAvjoR2YmEjD7zBoA-aGRhUQhyGYWJ-nfMbt1FaJRMOJJmbBXovkyB6uLYTrxdpEb7ZoxKn87eEM2AJzh9Ph7h5rX8JTfU9HLzPFG2dq_dahEtfHskceMRREcR9hKCEtzOZipmcz5DwsZNDQc7KoiJoH7RudUu03GZsj9ZZ2Q',
            'level' => 'Advanced', 'levelColor' => 'bg-error-container text-on-error-container border-error/20',
            'duration' => '12 Weeks',
            'title' => 'Apex Pinnacle',
            'description' => 'Break plateaus. High-intensity neural priming, complex periodization, and elite peaking strategies.',
            'tags' => [],
            'features' => ['Neural priming', 'Complex periodization', 'Elite peaking'],
            'price' => 499.00,
            'colSpan' => '',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAp_gSs-VTyNFd8zJXbXG4YwYoUNU8SagPaeF-FHv3OMZYU_dgEajswrl4oqgtQr49BWIovGmcSXXHaKj0DeFWHzBdvsBm8vsibbgjXikhZ58IjwQQL2k-FIXskeLKffdKIpgqVYjC0Ryep4ju9aff-y2oTNWG1utfarE0y_gM7xBfqQVNpDuow3VrVeFww7fmufqNjjc8C3dHPijbtBRY-gaw4rgZvfw2144CcJCDQZUPfHFS-6aUiXA',
            'level' => 'All Levels', 'levelColor' => 'bg-surface-container-high text-on-surface border-white/10',
            'duration' => '8 Weeks',
            'title' => 'Metabolic Burn',
            'description' => 'Ignite your metabolism. High-output conditioning paired with strategic resistance training to reveal lean mass.',
            'tags' => [],
            'features' => ['Metabolic conditioning', 'Strategic resistance', 'Lean mass reveal'],
            'price' => 249.00,
            'colSpan' => '',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDuD5ptQ_nlZDihAQQVi0tCq0v025xqJqXNb5X6kKee1joV-nhYTOhcSYsnFpw_L_6jbuL5jkMPSKnu32S7h8ZF6TNpTfLD3PHNi5x9iZOeybpj0f-ooPfoLMU_gVaaLwPjTzvvvLEmBjV-SRBCQJUzPLznK7XWvN5-jPRLWGYpYxGfmF_ccsZFVPERMcy_2chRY6QtcZcL-RTcQejILp39IFkuwFvyMcaQtJoJbbUM3kbrzNRx0p1Q3A',
            'level' => 'Intermediate', 'levelColor' => 'bg-surface-container-high text-on-surface border-white/10',
            'duration' => '10 Weeks',
            'title' => 'Valkyrie Strong',
            'description' => 'Designed for power. Focus on posterior chain development, core stability, and total-body athleticism.',
            'tags' => [],
            'features' => ['Posterior chain development', 'Core stability', 'Total-body athleticism'],
            'price' => 349.00,
            'colSpan' => '',
            'buttonLink' => 'book.php'
        ]
    ];
    foreach ($programs as $p) {
        extract($p);
        require __DIR__ . '/includes/components/program_card.php';
    }
    ?>
<?php endif; ?>
</div>
<div class="mt-16 flex justify-center">
<a href="<?php echo site_url('book.php'); ?>" class="btn-primary font-label-caps text-label-caps px-8 py-4 rounded uppercase text-lg tracking-wider flex items-center gap-2 inline-block">
Start Your Assessment <span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
