<?php 
$pageTitle = 'Transformations'; 
$pageDescription = 'View incredible client transformations and before/after results from Apex Elite Performance. See how our elite coaching programs have helped athletes achieve their fitness goals.';
require __DIR__ . '/includes/head.php';

// Load transformations dynamically from database
$transformations = db_select('transformations', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');

// Get featured transformation
$featuredTransform = null;
$otherTransformations = [];

// Force static data for testing
$transformations = null;

// Handle category filter
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
if ($categoryFilter !== 'all' && !empty($otherTransformations)) {
    $otherTransformations = array_filter($otherTransformations, function($t) use ($categoryFilter) {
        return $t['category'] === $categoryFilter;
    });
}

// Get unique categories
$categories = ['all' => 'All'];
if ($transformations && count($transformations) > 0) {
    foreach ($transformations as $transform) {
        if (!empty($transform['category'])) {
            $categoryKey = $transform['category'];
            $categoryLabel = ucfirst(str_replace('-', ' ', $categoryKey));
            $categories[$categoryKey] = $categoryLabel;
        }
    }
}

require __DIR__ . '/includes/navigation.php'; 
?>
<header class="pt-32 pb-16 md:pt-48 md:pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto relative">
<div class="relative z-10 max-w-3xl">
<h1 class="font-display-xl text-display-xl text-on-surface mb-6 uppercase">
Proven <span class="text-primary text-glow">Results</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">
Witness the culmination of disciplined training and elite coaching. These are the physical and mental transformations of athletes who pushed beyond their limits.
</p>
</div>
</header>
<main class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto pb-32">
<!-- Category Filter -->
<div class="mb-12 flex flex-wrap gap-3">
<?php foreach ($categories as $key => $label): ?>
<a href="<?php echo site_url('transformations.php?category=' . $key); ?>" 
   class="font-label-caps text-label-caps px-4 py-2 rounded-full border transition-all duration-300 <?php echo $categoryFilter === $key ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-high text-on-surface border-white/10 hover:border-primary/50'; ?>">
<?php echo e($label); ?>
</a>
<?php endforeach; ?>
</div>

<!-- All Transformations Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-24">
<?php
// Combine featured and other transformations into one array
$allTransformations = [];
if ($featuredTransform) {
    $featuredTransform['featured'] = true;
    $allTransformations[] = $featuredTransform;
}
foreach ($otherTransformations as $transform) {
    $transform['featured'] = false;
    $allTransformations[] = $transform;
}

// If no transformations, use static data
if (empty($allTransformations)) {
    $allTransformations = [
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=1600&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=1600&q=90',
            'client_name' => 'Marcus Vance',
            'before_label' => 'Day 01',
            'after_label' => 'Day 180',
            'stats' => json_encode([
                ['label' => 'FAT LOSS', 'value' => '-24 lbs', 'highlight' => true],
                ['label' => 'MUSCLE GAIN', 'value' => '+8 lbs', 'highlight' => false]
            ]),
            'quote' => 'The Apex program completely rewired my approach to training. It wasn\'t just about the physical changes, but the mental fortitude built during those dark, early morning sessions.',
            'goal' => 'Overall Fitness',
            'weight_lost' => '-24 lbs',
            'story' => 'As a coach, I needed to practice what I preach.',
            'duration' => '6 months',
            'featured' => true
        ],
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=1200&q=90',
            'client_name' => 'Sarah Jenkins',
            'before_label' => 'Week 1',
            'after_label' => 'Week 16',
            'stats' => json_encode([
                ['label' => 'FAT LOSS', 'value' => '-18 lbs', 'highlight' => true],
                ['label' => 'PATH', 'value' => 'Functional Strength', 'highlight' => false]
            ]),
            'quote' => 'Precision coaching and no-BS accountability made the difference.',
            'goal' => 'Weight Loss',
            'weight_lost' => '-18 lbs',
            'story' => 'Precision coaching and no-BS accountability made the difference. I tried everything before, but the structured approach finally got me results.',
            'duration' => '4 months',
            'featured' => false
        ],
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=1200&q=90',
            'client_name' => 'David Chen',
            'before_label' => 'Week 1',
            'after_label' => 'Week 20',
            'stats' => json_encode([
                ['label' => 'LEAN MASS', 'value' => '+15 lbs', 'highlight' => true],
                ['label' => 'PATH', 'value' => 'Hypertrophy', 'highlight' => false]
            ]),
            'quote' => 'The systematic approach to nutrition and progressive overload transformed my physique.',
            'goal' => 'Muscle Building',
            'weight_lost' => '+15 lbs',
            'story' => 'The systematic approach to nutrition and progressive overload transformed my physique. I gained 15 lbs of lean mass while maintaining low body fat.',
            'duration' => '5 months',
            'featured' => false
        ],
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?w=1200&q=90',
            'client_name' => 'Emily Rodriguez',
            'before_label' => 'Week 1',
            'after_label' => 'Week 12',
            'stats' => json_encode([
                ['label' => 'ENDURANCE', 'value' => '+40%', 'highlight' => true],
                ['label' => 'STRENGTH', 'value' => '+25%', 'highlight' => false]
            ]),
            'quote' => 'The endurance training completely changed my athletic performance.',
            'goal' => 'Athletic Performance',
            'weight_lost' => '+40% endurance',
            'story' => 'The endurance training completely changed my athletic performance. I can now push harder and longer than ever before.',
            'duration' => '3 months',
            'featured' => false
        ]
    ];
}

// Display all transformations in the grid
foreach ($allTransformations as $transform) {
    $transformData = [
        'beforeImage' => $transform['before_image'] ?? $transform['beforeImage'],
        'afterImage' => $transform['after_image'] ?? $transform['afterImage'],
        'name' => $transform['client_name'] ?? $transform['name'],
        'beforeLabel' => $transform['before_label'] ?? $transform['beforeLabel'],
        'afterLabel' => $transform['after_label'] ?? $transform['afterLabel'],
        'stats' => is_string($transform['stats']) ? json_decode($transform['stats'], true) : ($transform['stats'] ?? []),
        'quote' => $transform['quote'] ?? '',
        'goal' => $transform['goal'] ?? '',
        'weightLost' => $transform['weight_lost'] ?? $transform['weightLost'] ?? '',
        'story' => $transform['story'] ?? '',
        'duration' => $transform['duration'] ?? '',
        'featured' => $transform['featured'] ?? false,
        'span' => ''
    ];
    extract($transformData);
    require __DIR__ . '/includes/components/transformation_card.php';
}
?>
</div>

<!-- Average Client Results Section -->
<div class="glass-panel rounded-xl p-6 md:p-8 mb-12">
<div class="flex flex-col md:flex-row items-start gap-8">
<div class="flex-shrink-0">
<span class="material-symbols-outlined text-primary text-4xl md:text-5xl" style="font-variation-settings: 'FILL' 1;">monitoring</span>
</div>
<div class="flex-grow">
<h4 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-2 uppercase">Average Client Results</h4>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Data aggregated from our last 100 dedicated program graduates.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<div class="flex justify-between font-label-caps text-label-caps text-on-surface mb-2">
<span>BODY FAT REDUCTION</span>
<span class="text-primary">-12%</span>
</div>
<div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden" data-progress="75">
<div class="h-full bg-primary rounded-full shadow-[0_0_10px_rgba(75,226,119,0.5)]" style="width: 0%;"></div>
</div>
</div>
<div>
<div class="flex justify-between font-label-caps text-label-caps text-on-surface mb-2">
<span>STRENGTH INCREASE</span>
<span class="text-primary">+35%</span>
</div>
<div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden" data-progress="85">
<div class="h-full bg-primary rounded-full shadow-[0_0_10px_rgba(75,226,119,0.5)]" style="width: 0%;"></div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Other Transformations Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
<?php if (!empty($otherTransformations)): ?>
    <?php foreach ($otherTransformations as $transform): ?>
        <?php
        $transformData = [
            'beforeImage' => $transform['before_image'],
            'afterImage' => $transform['after_image'],
            'name' => $transform['client_name'],
            'beforeLabel' => $transform['before_label'],
            'afterLabel' => $transform['after_label'],
            'stats' => json_decode($transform['stats'], true) ?: [],
            'quote' => $transform['quote'],
            'goal' => $transform['goal'],
            'weightLost' => $transform['weight_lost'],
            'story' => $transform['story'],
            'duration' => $transform['duration'],
            'featured' => $transform['featured'],
            'span' => $transform['span']
        ];
        extract($transformData);
        require __DIR__ . '/includes/components/transformation_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    // Fallback to static data if no other transformations
    $otherTransformations = [
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=1200&q=90',
            'name' => 'Sarah Jenkins',
            'beforeLabel' => 'Week 1',
            'afterLabel' => 'Week 16',
            'stats' => [
                ['label' => 'FAT LOSS', 'value' => '-18 lbs', 'highlight' => true],
                ['label' => 'PATH', 'value' => 'Functional Strength', 'highlight' => false]
            ],
            'quote' => 'Precision coaching and no-BS accountability made the difference.',
            'goal' => 'Weight Loss',
            'weightLost' => '-18 lbs',
            'story' => 'Precision coaching and no-BS accountability made the difference. I tried everything before, but the structured approach finally got me results.',
            'duration' => '4 months',
            'featured' => false,
            'span' => 4
        ],
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=1200&q=90',
            'name' => 'David Chen',
            'beforeLabel' => 'Week 1',
            'afterLabel' => 'Week 20',
            'stats' => [
                ['label' => 'LEAN MASS', 'value' => '+15 lbs', 'highlight' => true],
                ['label' => 'PATH', 'value' => 'Hypertrophy', 'highlight' => false]
            ],
            'quote' => 'The systematic approach to nutrition and progressive overload transformed my physique.',
            'goal' => 'Muscle Building',
            'weightLost' => '+15 lbs',
            'story' => 'The systematic approach to nutrition and progressive overload transformed my physique. I gained 15 lbs of lean mass while maintaining low body fat.',
            'duration' => '5 months',
            'featured' => false,
            'span' => 4
        ],
        [
            'beforeImage' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1200&q=90',
            'afterImage' => 'https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?w=1200&q=90',
            'name' => 'Emily Rodriguez',
            'beforeLabel' => 'Week 1',
            'afterLabel' => 'Week 12',
            'stats' => [
                ['label' => 'ENDURANCE', 'value' => '+40%', 'highlight' => true],
                ['label' => 'STRENGTH', 'value' => '+25%', 'highlight' => false]
            ],
            'quote' => 'The endurance training completely changed my athletic performance.',
            'goal' => 'Athletic Performance',
            'weightLost' => '+40% endurance',
            'story' => 'The endurance training completely changed my athletic performance. I can now push harder and longer than ever before.',
            'duration' => '3 months',
            'featured' => false,
            'span' => 4
        ]
    ];
    foreach ($otherTransformations as $transform) {
        extract($transform);
        require __DIR__ . '/includes/components/transformation_card.php';
    }
    ?>
<?php endif; ?>
</div>
</main>

<!-- Image Lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 bg-black/95 hidden items-center justify-center p-4">
<button id="close-lightbox" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
<span class="material-symbols-outlined text-4xl">close</span>
</button>
<button id="prev-image" class="absolute left-4 text-white hover:text-primary transition-colors">
<span class="material-symbols-outlined text-4xl">arrow_back</span>
</button>
<button id="next-image" class="absolute right-4 text-white hover:text-primary transition-colors">
<span class="material-symbols-outlined text-4xl">arrow_forward</span>
</button>
<img id="lightbox-image" class="max-w-full max-h-full object-contain" src="" alt="Lightbox image">
<div id="lightbox-caption" class="absolute bottom-4 left-0 right-0 text-center text-white font-label-caps text-label-caps"></div>
</div>

<script>
// Lightbox functionality
const lightbox = document.getElementById('lightbox');
const lightboxImage = document.getElementById('lightbox-image');
const lightboxCaption = document.getElementById('lightbox-caption');
const closeLightbox = document.getElementById('close-lightbox');
const prevImage = document.getElementById('prev-image');
const nextImage = document.getElementById('next-image');

let currentImages = [];
let currentIndex = 0;

// Collect all transformation images
document.querySelectorAll('.transformation-card img').forEach((img, index) => {
    img.addEventListener('click', function() {
        currentImages = Array.from(document.querySelectorAll('.transformation-card img'));
        currentIndex = currentImages.indexOf(img);
        lightboxImage.src = this.src;
        lightboxCaption.textContent = this.alt;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
    });
});

closeLightbox.addEventListener('click', function() {
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
});

prevImage.addEventListener('click', function() {
    if (currentIndex > 0) {
        currentIndex--;
        lightboxImage.src = currentImages[currentIndex].src;
        lightboxCaption.textContent = currentImages[currentIndex].alt;
    }
});

nextImage.addEventListener('click', function() {
    if (currentIndex < currentImages.length - 1) {
        currentIndex++;
        lightboxImage.src = currentImages[currentIndex].src;
        lightboxCaption.textContent = currentImages[currentIndex].alt;
    }
});

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
    }
    if (e.key === 'ArrowLeft') {
        prevImage.click();
    }
    if (e.key === 'ArrowRight') {
        nextImage.click();
    }
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
