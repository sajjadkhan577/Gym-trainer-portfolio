<?php 
$pageTitle = 'Testimonials'; 
$pageDescription = 'Read success stories and testimonials from our clients at Apex Elite Performance. Real athletes achieving real results through our elite fitness coaching programs.';
require __DIR__ . '/includes/head.php';

// Load testimonials dynamically from database
$testimonials = db_select('testimonials', '*', 'status = :status', ['status' => 'active'], 'created_at DESC');

require __DIR__ . '/includes/navigation.php'; 
?>
<style>
.testimonials-slider {
    position: relative;
    margin: 0 -24px; /* Negative margin to compensate for slide padding */
}

.testimonials-track {
    display: flex;
    transition: transform 0.5s ease-in-out;
    gap: 0; /* Remove gap between slides */
}

.testimonials-slide {
    transition: all 0.3s ease;
    padding: 0 24px; /* Consistent padding on each side */
}

.slider-dot {
    transition: all 0.3s ease;
}

.slider-dot:hover {
    transform: scale(1.2);
}

.slider-dot.bg-primary {
    transform: scale(1.2);
}

/* Ensure testimonials maintain their glass card styling */
.testimonials-slide .glass-card {
    height: 100%;
    min-height: 380px;
    margin: 0; /* Remove any default margins */
    display: flex;
    flex-direction: column;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .testimonials-slider {
        margin: 0 -16px; /* Smaller negative margin on mobile */
    }
    
    .testimonials-slide {
        width: 100% !important;
        padding: 0 16px; /* Reduced padding on mobile */
    }
}
</style>
<body class="bg-background text-on-surface font-body-md antialiased overflow-x-hidden">
<main class="pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
<header class="mb-16 text-center md:text-left">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface mb-4">THE ELITE STANDARD</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Real results from high-performance athletes who demand nothing but the absolute best. No excuses, just relentless progress.</p>
</header>

<div class="relative">
<!-- Testimonials Slider -->
<div class="testimonials-slider overflow-hidden">
<div class="testimonials-track flex transition-transform duration-500 ease-in-out">
<?php if ($testimonials && count($testimonials) > 0): ?>
    <?php foreach ($testimonials as $t): ?>
        <div class="testimonials-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3">
        <?php
        $testimonialData = [
            'quote' => $t['quote'],
            'author_name' => $t['author_name'],
            'author_role' => $t['author_role'],
            'author_location' => $t['author_location'],
            'author_image' => $t['author_image'],
            'rating' => $t['rating']
        ];
        extract($testimonialData);
        require __DIR__ . '/includes/components/testimonial_card.php';
        ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-on-surface-variant text-center py-8">No testimonials available at this time.</p>
<?php endif; ?>
</div>
</div>

<!-- Slider Navigation -->
<?php if ($testimonials && count($testimonials) > 0): ?>
<div class="flex justify-center items-center mt-8 gap-4">
<button id="prev-slide" class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed">
<span class="material-symbols-outlined">arrow_back</span>
</button>
<div class="flex gap-2" id="slider-dots">
<?php foreach ($testimonials as $index => $t): ?>
<button class="slider-dot w-3 h-3 rounded-full bg-surface-variant hover:bg-primary transition-all <?php echo $index === 0 ? 'bg-primary' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
<?php endforeach; ?>
</div>
<button id="next-slide" class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed">
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>
<?php endif; ?>
</div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.testimonials-track');
    const slides = document.querySelectorAll('.testimonials-slide');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');
    const dots = document.querySelectorAll('.slider-dot');
    
    if (!track || slides.length === 0) return;
    
    let currentIndex = 0;
    let slidesPerView = getSlidesPerView();
    let totalSlides = slides.length;
    let maxIndex = Math.max(0, totalSlides - slidesPerView);
    
    function getSlidesPerView() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }
    
    function updateSlider() {
        slidesPerView = getSlidesPerView();
        maxIndex = Math.max(0, totalSlides - slidesPerView);
        
        if (currentIndex > maxIndex) {
            currentIndex = maxIndex;
        }
        
        // Calculate slide width percentage including padding
        const slideWidthPercentage = 100 / slidesPerView;
        const translateX = -(currentIndex * slideWidthPercentage);
        track.style.transform = `translateX(${translateX}%)`;
        
        // Update dots - only show dots for actual slide positions
        const dotCount = Math.max(1, totalSlides - slidesPerView + 1);
        dots.forEach((dot, index) => {
            if (index < dotCount) {
                dot.style.display = 'block';
                dot.classList.toggle('bg-primary', index === currentIndex);
                dot.classList.toggle('bg-surface-variant', index !== currentIndex);
            } else {
                dot.style.display = 'none';
            }
        });
        
        // Update buttons
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= maxIndex;
    }
    
    function goToSlide(index) {
        currentIndex = Math.max(0, Math.min(index, maxIndex));
        updateSlider();
    }
    
    prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
    nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });
    
    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                goToSlide(currentIndex + 1);
            } else {
                goToSlide(currentIndex - 1);
            }
        }
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            goToSlide(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            goToSlide(currentIndex + 1);
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', updateSlider);
    
    // Auto-play (optional)
    let autoPlayInterval;
    function startAutoPlay() {
        autoPlayInterval = setInterval(() => {
            if (currentIndex >= maxIndex) {
                goToSlide(0);
            } else {
                goToSlide(currentIndex + 1);
            }
        }, 5000);
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    // Start auto-play
    startAutoPlay();
    
    // Pause on hover
    track.addEventListener('mouseenter', stopAutoPlay);
    track.addEventListener('mouseleave', startAutoPlay);
    
    // Initial update
    updateSlider();
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>