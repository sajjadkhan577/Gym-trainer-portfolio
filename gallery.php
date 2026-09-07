<?php 
$pageTitle = 'Gallery'; 
$pageDescription = 'Browse our gallery of client transformations, training sessions, and elite fitness achievements. See real results from our dedicated athletes and fitness enthusiasts.';
require __DIR__ . '/includes/head.php';

// Load gallery items dynamically from database
$galleryItems = db_select('gallery', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');

// Get unique categories from database
$categories = ['ALL'];
if ($galleryItems && count($galleryItems) > 0) {
    foreach ($galleryItems as $item) {
        if (!empty($item['category']) && !in_array($item['category'], $categories)) {
            $categories[] = $item['category'];
        }
    }
}

// Handle category filter
$categoryFilter = isset($_GET['category']) ? strtoupper($_GET['category']) : 'ALL';
if ($categoryFilter !== 'ALL' && !empty($galleryItems)) {
    $galleryItems = array_filter($galleryItems, function($item) use ($categoryFilter) {
        return strtoupper($item['category']) === $categoryFilter;
    });
} else {
    // Keep all items when 'ALL' is selected
    $galleryItems = $galleryItems;
}

require __DIR__ . '/includes/navigation.php'; 
?>
<style>
.masonry-item img {
    transition: opacity 0.3s ease;
}
.masonry-item img:not(.loaded) {
    opacity: 0.5;
    filter: blur(10px);
}
.masonry-item img.loaded {
    opacity: 1;
    filter: blur(0);
}
.skeleton-loader {
    background: linear-gradient(90deg, 
        rgba(255,255,255,0.05) 25%, 
        rgba(255,255,255,0.1) 50%, 
        rgba(255,255,255,0.05) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.lightbox-zoom {
    transition: transform 0.3s ease;
    cursor: zoom-in;
}
.lightbox-zoom:hover {
    cursor: zoom-out;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .masonry-grid {
        column-count: 1 !important;
    }
    #lightbox-image {
        max-height: 70vh;
    }
    .material-symbols-outlined {
        font-size: 32px !important;
    }
}

/* Loading state for filter buttons */
[data-filter-button].loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Gallery grid animations */
.masonry-item {
    will-change: transform, opacity;
}

/* Improved touch targets for mobile */
@media (max-width: 768px) {
    #close-lightbox, #close-video {
        top: 16px;
        right: 16px;
    }
    #prev-image, #next-image {
        display: none;
    }
}
</style>
<body class="antialiased min-h-screen flex flex-col">
<main class="flex-grow pt-[104px] pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full" id="gallery-grid">
<section class="mb-16 pt-12 md:pt-24 flex flex-col md:flex-row justify-between items-end gap-8 border-b border-white/10 pb-8">
<div>
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface mb-4">THE VAULT</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">
Visual evidence of high-performance execution. Filter through raw training moments, facility overviews, and the apex lifestyle.
</p>
</div>
<?php
$containerId = '#gallery-grid';
require __DIR__ . '/includes/components/filter_buttons.php';
?>
</section>
<div class="masonry-grid">
<?php if ($galleryItems && count($galleryItems) > 0): ?>
    <?php foreach ($galleryItems as $item): ?>
        <?php
        $galleryData = [
            'image' => $item['image_url'],
            'video' => $item['video_url'],
            'category' => $item['category'],
            'title' => $item['title'],
            'aspect' => $item['aspect_ratio'],
            'isVideo' => $item['is_video'],
            'description' => $item['description']
        ];
        extract($galleryData);
        require __DIR__ . '/includes/components/gallery_item.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    // Fallback to static data if database is empty
    $galleryItems = [
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIoK7FXO2SdI-kb9FFOiEE_rRBhQqB91y3XmFFJvhyugZgIJL4jYIcCebvASDIxRR-f_1eq6Jp3Emxzozz8e0-IBY2XHRHiLExU3qjmEepBMIPIp5iBG3Ve3b3v01S_S4b0ewzG3am6IMDfxo4EwkwsLDbKzadT62_m51T0o1LTG3shWCH9s3EtrzqIRHfoHg81wibvyJL0n3BamRc28cLkCJblX7qEMDeyv4BBB-eeVk5mQSzA68FuA', 'category' => 'WORKOUT', 'title' => 'RAW POWER', 'aspect' => '3/4', 'isVideo' => false],
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgmPNyw1yP9JywI_c-hpWse7A1RjtHbc6_Gb9qBWkJmTRDIzhzPY6TvMTXOEQoraYuDS6yE7AnHVXn9Gu9b2iZOWEfZV_RT5a8GVUequCg9E2y1SiE5MpJO5tbnNekcHFRk_5ykBTTjEN7kiJ1GetBbF3ELHbygP7j3ndGMCLe6IAtnu-o82tmiqq9iipkfhpTt16I02Zo9GM7r80sGTmBL_mwSSUqLSMZZmrftvEuFRfJ7kjS0JBoOA', 'category' => 'GYM', 'title' => 'THE FACILITY', 'aspect' => '1/1', 'isVideo' => false],
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFitCqs_du8jexcKjOIBu0k6IzN39u8vyjRvapgaONQ73dfreZI_4EmPASIRUDVrO72vNF_Xjd_qMONNXvsjewcmie6hPyKqLfB9UjJnMr_jjsMT9hcTZjoQMnKgWVFXYHNygpMwWhJLPRdf5dawNlV5HaGFTDLiu9oRxn4KxrXJB4R8zIkHv4bi4sbnzL6FCJYCvu_Ci2En4vHttxEtg5qjsukFvJAvWyaHlYNBaX9nGZgp-DNo482g', 'category' => 'LIFESTYLE', 'title' => 'SPRINT MECHANICS', 'aspect' => '16/9', 'isVideo' => true],
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBpHjT9cCJ2fvKLUtbiC01OjZjOkI3GB0fuCLtnzqjGcSSxYVYOzwmNa370uwF2wykSz_FdJ5ObINMJdOsCES7NSwylotNKbykb6716Vcf-q02DAPb2IBHVIjwYxNB1Gp_PAumyitGdx6syD7UhnopyA09zenY1Qs0CJyvWMCauZzKQoreLe04oN2jQWzX8XupbW29OTaRAg2w1FXELVhyXXlW8wK-MlE5hEINJGZ3CQVNquKe_r_2iiQ', 'category' => 'WORKOUT', 'title' => 'THE GRIP', 'aspect' => '1/1', 'isVideo' => false],
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBwo1Z8Jsj7SWp4Lm64wE2Ue4efLtsJCa9Ia-4jBcrw8NKh91xZ7VAZgzO3Yrh6xMpq-UQMEf4V6EaCCj4n6pEeqvJNVsI5N5wgDvm2mESBv80YWPLJfhF2IxAc5WM2VIJ_lc0evvQHThBAYg34zQMgNEnIkLiUe1S_9XtaHvJtzeqyit6Fo2_fxvRzHPfyWF6w8pAQVA2-e4XIjMmhs5NJtmJasjujzZa_VuzzHc-AFO_S5BYTAVTABQ', 'category' => 'LIFESTYLE', 'title' => 'COACH PROFILE', 'aspect' => '3/4', 'isVideo' => false],
        ['image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKv-vZATyk9DxRFj5VNwJRer9AFhTiI3pg2naqZ07_e_yzeZzYEuTtRt2Blm66tx1Cie1php8CBg2nyHrAzys6CCxw7Asn3bxevwBR2XriJ_c5GmC1LbdqW8jFyBKEZGbjCDLP0uBe3-HhmwFhEn5hf7TP5NGc1jCOGEA1m8FWONOno6Z1euNW1HDBexAKXLE6X06wfIZBfp33jSfvP2LUuEVLJ-i-WmLgA1RwtBMEqsguI1a42bET7A', 'category' => 'GYM', 'title' => 'RECOVERY ZONE', 'aspect' => '16/9', 'isVideo' => false]
    ];
    foreach ($galleryItems as $g) {
        extract($g);
        require __DIR__ . '/includes/components/gallery_item.php';
    }
    ?>
<?php endif; ?>
</div>
<div class="mt-16 text-center">
<a href="#" class="bg-transparent border-2 border-primary text-primary font-label-caps text-label-caps uppercase px-8 py-4 rounded hover:bg-primary hover:text-on-primary hover:shadow-[0_0_20px_rgba(34,197,94,0.3)] transition-all duration-300 inline-block">
LOAD MORE VISUALS
</a>
</div>
</main>

<!-- Image Lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 bg-black/95 hidden items-center justify-center p-4">
<button id="close-lightbox" class="absolute top-4 right-4 text-white hover:text-primary transition-colors z-50">
<span class="material-symbols-outlined text-4xl">close</span>
</button>
<button id="prev-image" class="absolute left-4 text-white hover:text-primary transition-colors z-50">
<span class="material-symbols-outlined text-4xl">arrow_back</span>
</button>
<button id="next-image" class="absolute right-4 text-white hover:text-primary transition-colors z-50">
<span class="material-symbols-outlined text-4xl">arrow_forward</span>
</button>
<img id="lightbox-image" class="max-w-full max-h-full object-contain lightbox-zoom" src="" alt="Lightbox image">
<div id="lightbox-caption" class="absolute bottom-4 left-0 right-0 text-center text-white font-label-caps text-label-caps bg-black/50 py-2"></div>
</div>

<!-- Video Popup -->
<div id="video-popup" class="fixed inset-0 z-50 bg-black/95 hidden items-center justify-center p-4">
<button id="close-video" class="absolute top-4 right-4 text-white hover:text-primary transition-colors z-50">
<span class="material-symbols-outlined text-4xl">close</span>
</button>
<div class="w-full max-w-5xl aspect-video relative">
<div id="video-loading" class="absolute inset-0 flex items-center justify-center bg-black/50 hidden">
<div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
</div>
<div id="video-error" class="absolute inset-0 flex items-center justify-center bg-black/50 hidden">
<div class="text-center text-white">
<span class="material-symbols-outlined text-6xl mb-4 text-error">error</span>
<p class="font-body-lg">Failed to load video</p>
</div>
</div>
<iframe id="video-frame" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
</div>

<script>
// Intersection Observer for enhanced lazy loading
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                img.classList.add('loaded');
            }
            observer.unobserve(img);
        }
    });
}, {
    rootMargin: '50px 0px',
    threshold: 0.01
});

// Observe all images with data-src
document.querySelectorAll('img[data-src]').forEach(img => {
    imageObserver.observe(img);
});

// Lightbox functionality with enhanced features
const lightbox = document.getElementById('lightbox');
const lightboxImage = document.getElementById('lightbox-image');
const lightboxCaption = document.getElementById('lightbox-caption');
const closeLightbox = document.getElementById('close-lightbox');
const prevImage = document.getElementById('prev-image');
const nextImage = document.getElementById('next-image');

let currentImages = [];
let currentIndex = 0;
let touchStartX = 0;
let touchEndX = 0;
let scale = 1;

// Collect all gallery images
document.querySelectorAll('.masonry-item[data-gallery-item]').forEach((item, index) => {
    const img = item.querySelector('img');
    if (img && !item.classList.contains('is-video')) {
        img.addEventListener('click', function() {
            currentImages = Array.from(document.querySelectorAll('.masonry-item[data-gallery-item]:not(.is-video) img'));
            currentIndex = currentImages.indexOf(img);
            lightboxImage.src = this.src;
            lightboxCaption.textContent = this.alt;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    }
});

closeLightbox.addEventListener('click', function() {
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = '';
    resetZoom();
});

prevImage.addEventListener('click', function() {
    if (currentIndex > 0) {
        currentIndex--;
        lightboxImage.src = currentImages[currentIndex].src;
        lightboxCaption.textContent = currentImages[currentIndex].alt;
        resetZoom();
    }
});

nextImage.addEventListener('click', function() {
    if (currentIndex < currentImages.length - 1) {
        currentIndex++;
        lightboxImage.src = currentImages[currentIndex].src;
        lightboxCaption.textContent = currentImages[currentIndex].alt;
        resetZoom();
    }
});

// Touch gestures for mobile
lightbox.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
}, { passive: true });

lightbox.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
}, { passive: true });

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextImage.click();
        } else {
            prevImage.click();
        }
    }
}

// Zoom functionality
lightboxImage.addEventListener('wheel', (e) => {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -0.1 : 0.1;
    scale = Math.min(Math.max(0.5, scale + delta), 3);
    lightboxImage.style.transform = `scale(${scale})`;
});

function resetZoom() {
    scale = 1;
    lightboxImage.style.transform = 'scale(1)';
}

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        videoPopup.classList.add('hidden');
        videoPopup.classList.remove('flex');
        document.getElementById('video-frame').src = '';
        document.body.style.overflow = '';
        resetZoom();
    }
    if (e.key === 'ArrowLeft') {
        prevImage.click();
    }
    if (e.key === 'ArrowRight') {
        nextImage.click();
    }
});

// Video popup functionality with enhanced features
const videoPopup = document.getElementById('video-popup');
const closeVideo = document.getElementById('close-video');
const videoFrame = document.getElementById('video-frame');
const videoLoading = document.getElementById('video-loading');
const videoError = document.getElementById('video-error');

document.querySelectorAll('.masonry-item.is-video').forEach((item) => {
    item.addEventListener('click', function() {
        const videoUrl = this.dataset.video;
        if (videoUrl) {
            // Show loading state
            videoLoading.classList.remove('hidden');
            videoError.classList.add('hidden');
            videoFrame.style.opacity = '0';
            
            videoPopup.classList.remove('hidden');
            videoPopup.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Load video
            videoFrame.src = videoUrl + '?autoplay=1';
            
            // Handle video load events
            videoFrame.onload = function() {
                videoLoading.classList.add('hidden');
                videoFrame.style.opacity = '1';
            };
            
            videoFrame.onerror = function() {
                videoLoading.classList.add('hidden');
                videoError.classList.remove('hidden');
            };
            
            // Fallback timeout
            setTimeout(() => {
                if (!videoLoading.classList.contains('hidden')) {
                    videoLoading.classList.add('hidden');
                    videoError.classList.remove('hidden');
                }
            }, 5000);
        }
    });
});

closeVideo.addEventListener('click', function() {
    videoPopup.classList.add('hidden');
    videoPopup.classList.remove('flex');
    videoFrame.src = '';
    videoFrame.style.opacity = '1';
    videoLoading.classList.add('hidden');
    videoError.classList.add('hidden');
    document.body.style.overflow = '';
});

// Enhanced category filtering with smooth animations
document.querySelectorAll('[data-filter-button]').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const category = this.dataset.filter;
        
        // Update active state
        document.querySelectorAll('[data-filter-button]').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-on-primary', 'border-primary');
            btn.classList.add('bg-surface-container-high', 'text-on-surface', 'border-white/10');
        });
        this.classList.remove('bg-surface-container-high', 'text-on-surface', 'border-white/10');
        this.classList.add('bg-primary', 'text-on-primary', 'border-primary');
        
        // Update URL without page reload
        const url = new URL(window.location);
        if (category === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.history.pushState({}, '', url);
        
        // Reset pagination for new category
        currentPage = 1;
        currentCategory = category.toUpperCase();
        
        // Show load more button again
        if (loadMoreBtn) {
            loadMoreBtn.style.display = 'inline-block';
            loadMoreBtn.textContent = 'LOAD MORE VISUALS';
        }
        
        // Filter items with animation
        const items = document.querySelectorAll('.masonry-item');
        items.forEach((item, index) => {
            const shouldShow = category === 'ALL' || item.dataset.filterCategory === category.toLowerCase();
            
            if (shouldShow) {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                }, index * 50);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    });
});

// Load More functionality with AJAX
const loadMoreBtn = document.querySelector('a[href="#"]');
let currentPage = 1;
const itemsPerPage = 6;
let currentCategory = 'ALL';

if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        
        // Show loading state
        loadMoreBtn.textContent = 'LOADING...';
        loadMoreBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        try {
            // Get current category from URL or default to ALL
            const urlParams = new URLSearchParams(window.location.search);
            currentCategory = urlParams.get('category') || 'ALL';
            
            // Fetch data from API
            const response = await fetch(`api/gallery_load_more.php?page=${currentPage + 1}&per_page=${itemsPerPage}&category=${currentCategory}`);
            const data = await response.json();
            
            if (data.success && data.html) {
                currentPage++;
                const masonryGrid = document.querySelector('.masonry-grid');
                
                // Create temporary container to parse HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                const newItems = tempDiv.querySelectorAll('.masonry-item');
                
                newItems.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    masonryGrid.appendChild(item);
                    
                    // Re-attach event listeners for new items
                    const img = item.querySelector('img');
                    if (img && !item.classList.contains('is-video')) {
                        // Set up lazy loading for new images
                        if (img.dataset.src) {
                            imageObserver.observe(img);
                        }
                        
                        img.addEventListener('click', function() {
                            currentImages = Array.from(document.querySelectorAll('.masonry-item[data-gallery-item]:not(.is-video) img'));
                            currentIndex = currentImages.indexOf(img);
                            lightboxImage.src = this.src;
                            lightboxCaption.textContent = this.alt;
                            lightbox.classList.remove('hidden');
                            lightbox.classList.add('flex');
                            document.body.style.overflow = 'hidden';
                        });
                    }
                    
                    if (item.classList.contains('is-video')) {
                        item.addEventListener('click', function() {
                            const videoUrl = this.dataset.video;
                            if (videoUrl) {
                                videoLoading.classList.remove('hidden');
                                videoError.classList.add('hidden');
                                videoFrame.style.opacity = '0';
                                
                                videoPopup.classList.remove('hidden');
                                videoPopup.classList.add('flex');
                                document.body.style.overflow = 'hidden';
                                
                                videoFrame.src = videoUrl + '?autoplay=1';
                                
                                videoFrame.onload = function() {
                                    videoLoading.classList.add('hidden');
                                    videoFrame.style.opacity = '1';
                                };
                                
                                videoFrame.onerror = function() {
                                    videoLoading.classList.add('hidden');
                                    videoError.classList.remove('hidden');
                                };
                                
                                setTimeout(() => {
                                    if (!videoLoading.classList.contains('hidden')) {
                                        videoLoading.classList.add('hidden');
                                        videoError.classList.remove('hidden');
                                    }
                                }, 5000);
                            }
                        });
                    }
                    
                    // Animate in
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, index * 100);
                });
                
                // Hide button if no more items
                if (!data.hasMore) {
                    loadMoreBtn.style.display = 'none';
                }
            } else {
                console.error('Failed to load more items:', data.message);
                loadMoreBtn.textContent = 'ERROR - TRY AGAIN';
                setTimeout(() => {
                    loadMoreBtn.textContent = 'LOAD MORE VISUALS';
                }, 2000);
            }
        } catch (error) {
            console.error('Error loading more items:', error);
            loadMoreBtn.textContent = 'ERROR - TRY AGAIN';
            setTimeout(() => {
                loadMoreBtn.textContent = 'LOAD MORE VISUALS';
            }, 2000);
        } finally {
            loadMoreBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
}

// Add transition styles to masonry items
document.querySelectorAll('.masonry-item').forEach(item => {
    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
});
</script>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
