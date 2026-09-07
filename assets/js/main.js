document.addEventListener('DOMContentLoaded', function() {
    var mobileMenuBtn = document.querySelector('[data-mobile-menu-toggle]');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            var menu = document.querySelector('[data-mobile-menu]');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    }

    var scrollElements = document.querySelectorAll('[data-animate-on-scroll]');
    if (scrollElements.length > 0 && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });

        scrollElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    var progressBars = document.querySelectorAll('[data-progress]');
    if (progressBars.length > 0) {
        progressBars.forEach(function(bar) {
            var value = bar.getAttribute('data-progress');
            var fill = bar.querySelector('.progress-bar-fill') || bar;
            if (fill) {
                setTimeout(function() {
                    fill.style.width = value + '%';
                }, 200);
            }
        });
    }

    var galleryItems = document.querySelectorAll('[data-gallery-item]');
    var lightbox = document.querySelector('[data-lightbox]');
    if (galleryItems.length > 0 && lightbox) {
        galleryItems.forEach(function(item) {
            item.addEventListener('click', function() {
                var imgSrc = item.getAttribute('data-src') || item.querySelector('img')?.getAttribute('src');
                var lightboxImg = lightbox.querySelector('[data-lightbox-image]');
                if (imgSrc && lightboxImg) {
                    lightboxImg.setAttribute('src', imgSrc);
                }
                lightbox.classList.remove('hidden');
            });
        });

        var closeBtn = lightbox.querySelector('[data-lightbox-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                lightbox.classList.add('hidden');
            });
        }

        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.classList.add('hidden');
            }
        });
    }

    var smoothLinks = document.querySelectorAll('a[href^="#"]');
    smoothLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            var href = link.getAttribute('href');
            if (href && href.length > 1) {
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    var loadMoreBtns = document.querySelectorAll('[data-load-more]');
    loadMoreBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetId = btn.getAttribute('data-load-more');
            var hiddenItems = document.querySelectorAll(targetId + ' [data-hidden-item]');
            hiddenItems.forEach(function(item, index) {
                if (index < 6) {
                    item.removeAttribute('data-hidden-item');
                    item.classList.remove('hidden');
                }
            });
        });
    });

    var filterBtns = document.querySelectorAll('[data-filter-btn]');
    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var category = btn.getAttribute('data-filter');
            var containerId = btn.getAttribute('data-filter-container');
            var items = document.querySelectorAll(containerId + ' [data-filter-item]');

            document.querySelectorAll('[data-filter-btn]').forEach(function(b) {
                b.classList.remove('border-primary', 'text-primary', 'bg-primary/10');
                b.classList.add('border-white/20', 'text-on-surface');
            });
            btn.classList.remove('border-white/20', 'text-on-surface');
            btn.classList.add('border-primary', 'text-primary', 'bg-primary/10');

            items.forEach(function(item) {
                var itemCategory = item.getAttribute('data-filter-category');
                if (category === 'all' || itemCategory === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });

    var forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var required = form.querySelectorAll('[required]');
            var valid = true;
            required.forEach(function(field) {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderBottomColor = '#ffb4ab';
                }
            });
            if (!valid) {
                e.preventDefault();
            }
        });
    });
});
