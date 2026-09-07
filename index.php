<?php 
$pageTitle = 'Home'; 
$pageDescription = 'Apex Elite Performance - Transform your body and mind with elite fitness coaching, personal training, and athletic performance programs designed for serious athletes and fitness enthusiasts.';
require __DIR__ . '/includes/head.php';

// Load coach information dynamically
$coachInfo = db_fetch_one("SELECT * FROM coach_info WHERE status = 'active' LIMIT 1");

// Load featured services dynamically
$featuredServices = db_select('services', '*', 'featured = :featured AND status = :status', ['featured' => 1, 'status' => 'active'], 'sort_order ASC', '2');

// Load featured programs dynamically
$featuredPrograms = db_select('programs', '*', 'featured = :featured AND status = :status', ['featured' => 1, 'status' => 'active'], 'sort_order ASC', '3');

// Load latest transformations dynamically
$latestTransformations = db_select('transformations', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC', '3');

// Load testimonials dynamically
$testimonials = db_select('testimonials', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC', '4');

// Load latest blogs dynamically
$latestBlogs = db_select('blog_posts', '*', 'status = :status', ['status' => 'published'], 'date DESC', '4');

// Load statistics dynamically
$statistics = db_select('statistics', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC', '4');

// Handle newsletter subscription
$newsletterMessage = '';
$newsletterMessageType = '';

if (is_post_request() && isset($_POST['newsletter_email'])) {
    $email = clean_input($_POST['newsletter_email']);
    
    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check for duplicate email
        $existing = db_fetch_one("SELECT id FROM newsletter_subscribers WHERE email = :email AND status != 'unsubscribed'", ['email' => $email]);
        
        if (!$existing) {
            // Insert new subscriber
            $subscriberId = db_insert('newsletter_subscribers', [
                'email' => $email,
                'name' => isset($_POST['newsletter_name']) ? clean_input($_POST['newsletter_name']) : '',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            if ($subscriberId) {
                $newsletterMessage = 'Successfully subscribed to our newsletter!';
                $newsletterMessageType = 'success';
            } else {
                $newsletterMessage = 'An error occurred. Please try again.';
                $newsletterMessageType = 'error';
            }
        } else {
            $newsletterMessage = 'You are already subscribed to our newsletter.';
            $newsletterMessageType = 'info';
        }
    } else {
        $newsletterMessage = 'Please enter a valid email address.';
        $newsletterMessageType = 'error';
    }
}

require __DIR__ . '/includes/navigation.php'; 
?>
<main>
<section class="relative min-h-screen flex items-center pt-24 pb-12">
<div class="absolute inset-0 z-0">
<?php if ($coachInfo && !empty($coachInfo['featured_image'])): ?>
<div class="w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo e($coachInfo['featured_image']); ?>');" loading="lazy"></div>
<?php else: ?>
<div class="w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBBy2sxCDmXIZMiF5RqaSfuuGPx3GZexQEdcHBTxCDklQZGyjMmds5i95QhksA5NQXTi_bPGjJEpanAlNfh9Pm4mBVEoL8EwsMfpd0_VCMVCYSFoL3SvJWbPI7joifQ1tOAeivTBCohwNP5UjJaahcKMylTfZP3zYR-IW_Zue21gcx3PoKaNHXGHrqWTW54O3CCK0hl0TwEF287MPheL3aY2b6c6i1TAd1nnPwN2TERa7tSnmcURn8u0Q');" loading="lazy"></div>
<?php endif; ?>
<div class="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-transparent"></div>
<div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
</div>
<div class="relative z-10 w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<div class="lg:col-span-8 flex flex-col justify-center">
<span class="font-label-caps text-label-caps text-primary tracking-widest uppercase mb-4 block">Elite Training Academy</span>
<h1 class="font-headline-lg-mobile text-headline-lg-mobile md:font-display-xl md:text-display-xl text-on-surface mb-6 uppercase">
Elevate Your <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-fixed">Performance.</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-10 border-l-2 border-primary pl-4">
<?php echo $coachInfo ? e($coachInfo['bio']) : 'Professional Training &amp; Elite Coaching by Marcus Vance. Unlock your true potential through rigorous, science-backed methodology and uncompromising accountability.'; ?>
</p>
<div class="flex flex-col sm:flex-row gap-4 mb-16">
<a href="<?php echo site_url('book.php'); ?>" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-4 rounded hover:shadow-[0_0_20px_theme('colors.primary')] transition-all duration-300 inline-block text-center">
Book a Session
</a>
<a href="<?php echo site_url('services.php'); ?>" class="glass-panel text-on-surface font-label-caps text-label-caps px-8 py-4 rounded hover:border-primary/50 hover:bg-white/5 transition-all duration-300 inline-block text-center">
View Services
</a>
</div>
<?php 
// Use dynamic statistics if available, otherwise use default
if ($statistics && count($statistics) > 0) {
    $statsData = [];
    foreach ($statistics as $stat) {
        $statsData[] = [
            'label' => $stat['label'],
            'value' => $stat['value'],
            'icon' => $stat['icon'] ?? 'trending_up'
        ];
    }
    // Pass data to stats component
    $stats = $statsData;
    require __DIR__ . '/includes/components/stats_bento.php';
} else {
    require __DIR__ . '/includes/components/stats_bento.php';
}
?>
</div>
</div>
</section>

<section class="py-[120px] bg-surface-container-low relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
<div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Elite Capabilities</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Comprehensive training protocols designed for maximum output and enduring resilience.</p>
</div>
<a href="<?php echo site_url('services.php'); ?>" class="glass-panel text-primary font-label-caps text-label-caps px-6 py-3 rounded flex items-center gap-2 hover:bg-white/5 transition-all duration-300">
All Services <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<?php if ($featuredServices && count($featuredServices) > 0): ?>
    <?php foreach ($featuredServices as $index => $service): ?>
        <?php
        $features = json_decode($service['features'], true) ?: [];
        $serviceData = [
            'image' => $service['image'],
            'icon' => $service['icon'],
            'title' => $service['title'],
            'description' => $service['description'],
            'features' => $features,
            'colSpan' => $index === 0 ? 2 : '',
            'height' => '400px'
        ];
        extract($serviceData);
        require __DIR__ . '/includes/components/service_card_bento.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    $service1 = [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC_DdF1wIdij7vDMTXvHPPbWYm8qn52duh7cehUwvpjb07qrYaBeX9e80kA454gTy_Rnh61mNQgVzlpHXkwTJxye3HXZxkKdHknRKbMBOjalOfwrV7AeUMY8yNdkyqV_tqA2Qn-zCvcYpX4k08DHW0AcaM5sAoebAifWTdB0Rm0ztp0nX4fk4sSbyd0_U_zVbzbAQmAlYNFJHpXKFsups1ksdoMH1bPeswSgEixyQ2jZZ-cqVFCwNPY1w',
        'icon' => 'fitness_center',
        'title' => 'Strength & Conditioning',
        'description' => 'Build raw power, functional strength, and explosive energy through periodized programming.',
        'colSpan' => 2,
        'height' => '400px'
    ];
    require __DIR__ . '/includes/components/service_card_bento.php';

    $service2 = [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDK3huKDgq0q16WjHl2GZbrzS_OCd4hj5Z8NumwQ8rFL9jZrQWBiXSSzlfRTbnh4BmOSfktGYMmoOgVx9CzH5Poi8feBiv-QVyebESyAKb_blUEr_IwWwoqtBAGvaLTTzao3ek8jZcu_YS7-VhUp6PWywZ-YhM23zWSZOkWN1Lc5IoDWIWTAo0dT6oCIVrUDYsKhQKgSeZk5Si-3kCnoBHgR16DYWMS27r9Wh1r0d-DFBjbHmtQDVdTKQ',
        'icon' => 'sprint',
        'title' => 'Speed & Agility',
        'description' => 'Enhance acceleration, deceleration, and multi-directional movement.',
        'colSpan' => '',
        'height' => '400px'
    ];
    require __DIR__ . '/includes/components/service_card_bento.php';
    ?>
<?php endif; ?>
</div>
</div>
</section>

<!-- Featured Programs Section -->
<section class="py-[120px] bg-background relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
<div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Elite Programs</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Forged in intensity, refined by science. Select your path to peak performance.</p>
</div>
<a href="<?php echo site_url('programs.php'); ?>" class="glass-panel text-primary font-label-caps text-label-caps px-6 py-3 rounded flex items-center gap-2 hover:bg-white/5 transition-all duration-300">
All Programs <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<?php if ($featuredPrograms && count($featuredPrograms) > 0): ?>
    <?php foreach ($featuredPrograms as $program): ?>
        <?php
        $programData = [
            'image' => $program['image'],
            'level' => $program['level'],
            'levelColor' => $program['level_color'],
            'duration' => $program['duration'],
            'title' => $program['title'],
            'description' => $program['description'],
            'tags' => json_decode($program['tags'], true) ?: [],
            'colSpan' => $program['col_span']
        ];
        extract($programData);
        require __DIR__ . '/includes/components/program_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    $program1 = [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWsTxOh3eY6-7JFYJp2RPnc0NW3dt7XiLugI_Z3IgXySOnAd4kJQhIluTH5MrnoLqfjIhJTImawcMwOdNJxFdmYJ1kf0PTqW3BksG76mbJlr9Pek2QZcfEzfqMudciP4ilC9h0R0-EHfMsIbdOQCMephmjDlpoEYDGxrVa7NgC2T2_K7h1qveeZphRi85ZRW6liEC-1fgc9RWj-tD2ThuiOnyRVlc2mDolAYLNS1NWDKI5ueEAXDMSig',
        'level' => 'Beginner', 'levelColor' => 'bg-surface-container-high text-on-surface border-white/10',
        'duration' => '12 Weeks',
        'title' => 'Foundation Protocol',
        'description' => 'Master the mechanics. Build base strength, correct imbalances, and prepare your body for elite loading.',
        'tags' => ['Form', 'Mobility'],
        'colSpan' => ''
    ];
    require __DIR__ . '/includes/components/program_card.php';
    ?>
<?php endif; ?>
</div>
</div>
</section>

<!-- Transformations Section -->
<section class="py-[120px] bg-surface-container-low relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
<div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Proven Results</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Witness the culmination of disciplined training and elite coaching.</p>
</div>
<a href="<?php echo site_url('transformations.php'); ?>" class="glass-panel text-primary font-label-caps text-label-caps px-6 py-3 rounded flex items-center gap-2 hover:bg-white/5 transition-all duration-300">
All Transformations <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<?php if ($latestTransformations && count($latestTransformations) > 0): ?>
    <?php foreach ($latestTransformations as $transform): ?>
        <?php
        $transformData = [
            'beforeImage' => $transform['before_image'],
            'afterImage' => $transform['after_image'],
            'name' => $transform['client_name'],
            'beforeLabel' => $transform['before_label'],
            'afterLabel' => $transform['after_label'],
            'stats' => json_decode($transform['stats'], true) ?: [],
            'quote' => $transform['quote'],
            'featured' => $transform['featured'],
            'span' => $transform['span']
        ];
        extract($transformData);
        require __DIR__ . '/includes/components/transformation_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    $featuredTransform = [
        'beforeImage' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-BcrYZWgsGvZKrZhlrxUoA1FdfXpntv6AeLChLNY0osh2zMVD30tXgNI3vXOV9bhO9nFO7T-E9p0cn06tnbru322Jm30sjii25ev8s8YTpK-j7di_KgXKR0clqe8_k4Qj4DohrgDNVQgMkZ6aqWLuMXeEthfHr1vmAAASUCeSi0zC18JH2ZEkvXFEX9u4Njai6yrjUR9yuSTAO_El3yT-wHQfcTtpTz41iOjbTALne__MHCuuUDYHgw',
        'afterImage' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCC19wHCTdawKlxxEzXUVjO72guBgNrzB7QSz1NogAQVrSo6MrP8AbSul398Sj9cdgoHNl3hwJIc28WtRajMmBKPLDQsqhH-XnqdjUvG11DLQu1tyWR9m7s8BBMZh73BS_t-uudbFpAakeM6qqsEyIjguSrN2P5eLmW8Lj87v4Ty6JXHhr-mlEb9iPIlwx5uQUGbbHcD2pDCSIbUd6uyZ6bV-suh3tLMAP9AZjODG9ohUIDIG3QzCZwdw',
        'name' => 'Marcus Vance',
        'beforeLabel' => 'DAY 01',
        'afterLabel' => 'DAY 180',
        'stats' => [
            ['label' => 'FAT LOSS', 'value' => '-24 lbs', 'highlight' => true],
            ['label' => 'MUSCLE GAIN', 'value' => '+8 lbs', 'highlight' => false]
        ],
        'quote' => 'The Apex program completely rewired my approach to training.',
        'featured' => true,
        'span' => 8
    ];
    require __DIR__ . '/includes/components/transformation_card.php';
    ?>
<?php endif; ?>
</div>
</div>
</section>

<!-- Testimonials Section -->
<section class="py-[120px] bg-background relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
<div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Client Success</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Real results from high-performance athletes who demand nothing but the absolute best.</p>
</div>
<a href="<?php echo site_url('testimonials.php'); ?>" class="glass-panel text-primary font-label-caps text-label-caps px-6 py-3 rounded flex items-center gap-2 hover:bg-white/5 transition-all duration-300">
All Testimonials <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[minmax(300px,_auto)]">
<?php if ($testimonials && count($testimonials) > 0): ?>
    <?php foreach ($testimonials as $testimonial): ?>
        <?php
        $testimonialData = [
            'featured' => $testimonial['featured'],
            'quote' => $testimonial['quote'],
            'authorName' => $testimonial['author_name'],
            'authorRole' => $testimonial['author_role'],
            'authorImage' => $testimonial['author_image'],
            'withImage' => $testimonial['with_image'],
            'sideImage' => $testimonial['side_image'],
            'spanCols' => $testimonial['span_cols']
        ];
        extract($testimonialData);
        if (empty($spanCols)) $spanCols = '';
        if (empty($withImage)) $withImage = false;
        if (empty($sideImage)) $sideImage = '';
        require __DIR__ . '/includes/components/testimonial_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    $testimonial1 = [
        'featured' => true,
        'quote' => 'Apex Coaching completely rebuilt my physical foundation. The intensity is unmatched, but the programming is pure precision.',
        'authorName' => 'MARCUS V.',
        'authorRole' => 'Professional MMA Fighter',
        'authorImage' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGYelBuZHvS7qQz-l9_y7DMdpY0ccwq8fzug6Cz5Kkjyol7YfR21XHUJ9pigbUwxwzzWNdScmFlz-fZ2eebVaMOAVW9jiHjwnaZGs0cL76trA_ju-ZtAtTa-XtWO75DHfhWgVwy4TBIU06J-f4Bjycsv4mYg3JgqezV6xwz_oK7GjR704_LuX13UpHE3L_8Ttqg9DbJ0Vc__zfBhMUwq02Xmu4q4s4Per7SfImVTqKbIjF57CAwQAq-Q'
    ];
    extract($testimonial1);
    $spanCols = '';
    $withImage = false;
    $sideImage = '';
    require __DIR__ . '/includes/components/testimonial_card.php';
    ?>
<?php endif; ?>
</div>
</div>
</section>

<!-- Latest Blog Section -->
<section class="py-[120px] bg-surface-container-low relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
<div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Latest Insights</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Expert knowledge and strategies for high-performance athletes.</p>
</div>
<a href="<?php echo site_url('blog.php'); ?>" class="glass-panel text-primary font-label-caps text-label-caps px-6 py-3 rounded flex items-center gap-2 hover:bg-white/5 transition-all duration-300">
All Articles <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<?php if ($latestBlogs && count($latestBlogs) > 0): ?>
    <?php 
    $featuredPost = [
        'image' => $latestBlogs[0]['image'],
        'category' => $latestBlogs[0]['category'],
        'title' => $latestBlogs[0]['title'],
        'excerpt' => $latestBlogs[0]['excerpt'],
        'date' => date('M d, Y', strtotime($latestBlogs[0]['date'])),
        'readTime' => $latestBlogs[0]['read_time'],
        'featured' => $latestBlogs[0]['featured']
    ];
    extract($featuredPost);
    require __DIR__ . '/includes/components/blog_card.php';
    ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24">
    <?php foreach (array_slice($latestBlogs, 1) as $post): ?>
        <?php
        $postData = [
            'image' => $post['image'],
            'category' => $post['category'],
            'title' => $post['title'],
            'excerpt' => $post['excerpt'],
            'date' => date('M d, Y', strtotime($post['date'])),
            'readTime' => $post['read_time'],
            'featured' => false
        ];
        extract($postData);
        require __DIR__ . '/includes/components/blog_card.php';
        ?>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php
    $featuredPost = [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVniC35zxG3Phyn4P9Q0BbLkcXx3nqv96or_l-NYxvjjSGPk3i2RnSgAOmaxeHPKmLuFiJXFLGpqVwfHSMhOXYumzP_IGjq9R60l1Y2qqjOQ0rBK32hlulz6PC_XS7mD7ILajQ_JyDQxIG4QdBQqUytXGLUV6JoQQ4NtlyjjFNNfUrCm3InqTm2fdQWjrMxCV9SbSwOVbDrTEGAQaSsfIblpsL8xUIgeQtoxxPNFvaF2mWDkscNOXq1w',
        'category' => 'TRAINING',
        'title' => 'Overcoming the Plateau: Advanced Periodization Techniques',
        'excerpt' => 'When linear progression stops, your strategy must evolve.',
        'date' => 'Oct 12, 2024',
        'readTime' => '8 min read',
        'featured' => true
    ];
    require __DIR__ . '/includes/components/blog_card.php';
    ?>
<?php endif; ?>
</div>
</section>

<!-- Newsletter Section -->
<section id="newsletter" class="py-[120px] bg-background relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="glass-panel rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-center">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg text-on-surface uppercase mb-4">Join The Elite</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-8 max-w-2xl mx-auto">Get exclusive training insights, nutrition protocols, and performance strategies delivered directly to your inbox.</p>
<?php if ($newsletterMessage): ?>
    <div class="mb-6 p-4 rounded <?php echo $newsletterMessageType === 'success' ? 'bg-primary-container text-on-primary-container' : ($newsletterMessageType === 'error' ? 'bg-error-container text-on-error-container' : 'bg-surface-container-high text-on-surface'); ?>">
        <?php echo e($newsletterMessage); ?>
    </div>
<?php endif; ?>
<form method="POST" action="<?php echo site_url('index.php'); ?>#newsletter" class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
    <input type="text" name="newsletter_name" placeholder="Your name" class="flex-1 bg-surface-container-high text-on-surface px-6 py-4 rounded border border-white/10 focus:border-primary focus:outline-none transition-all duration-300">
    <input type="email" name="newsletter_email" placeholder="Your email" required class="flex-1 bg-surface-container-high text-on-surface px-6 py-4 rounded border border-white/10 focus:border-primary focus:outline-none transition-all duration-300">
    <button type="submit" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-4 rounded hover:shadow-[0_0_20px_theme('colors.primary')] transition-all duration-300 whitespace-nowrap">
        Subscribe
    </button>
</form>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-4">No spam, ever. Unsubscribe anytime.</p>
</div>
</div>
</section>

<?php require __DIR__ . '/includes/components/cta_section.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
