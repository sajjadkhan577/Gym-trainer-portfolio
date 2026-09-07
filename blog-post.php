<?php 
require __DIR__ . '/includes/helpers.php';

// Get the slug from URL (handle both clean URLs and query parameters)
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// Also try to get slug from request URI for clean URLs
if (empty($slug)) {
    $requestUri = $_SERVER['REQUEST_URI'];
    if (preg_match('/blog\/([^\/]+)\/?$/', $requestUri, $matches)) {
        $slug = $matches[1];
    }
}

if (empty($slug)) {
    redirect('blog.php');
}

// Get the blog post
$post = db_fetch_one('SELECT * FROM blog_posts WHERE slug = :slug AND status = :status', ['slug' => $slug, 'status' => 'published']);

if (!$post) {
    redirect('blog.php');
}

// Increment view count
db_update('blog_posts', ['views' => $post['views'] + 1], 'id = :id', ['id' => $post['id']]);

// Get related posts (same category, excluding current post)
$relatedPosts = db_select('blog_posts', '*', 'category = :category AND id != :id AND status = :status', 
    ['category' => $post['category'], 'id' => $post['id'], 'status' => 'published'], 
    'date DESC LIMIT 3');

// Get recent posts (excluding current post)
$recentPosts = db_select('blog_posts', '*', 'id != :id AND status = :status', 
    ['id' => $post['id'], 'status' => 'published'], 
    'date DESC LIMIT 4');

// Set page meta tags
$pageTitle = $post['meta_title'] ?? $post['title'];
$pageDescription = $post['meta_description'] ?? $post['excerpt'];

require __DIR__ . '/includes/head.php'; 
require __DIR__ . '/includes/navigation.php'; 
?>
<style>
.blog-content {
    font-size: 1.125rem;
    line-height: 1.8;
}

.blog-content h2 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #e2e1eb;
}

.blog-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #e2e1eb;
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content ul, .blog-content ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content strong {
    color: #4be277;
    font-weight: 600;
}

.blog-content a {
    color: #4be277;
    text-decoration: underline;
}

.blog-content a:hover {
    color: #6bff8f;
}

.blog-content blockquote {
    border-left: 4px solid #4be277;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #bccbb9;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<body class="bg-background text-on-surface font-body-md antialiased pt-[88px] selection:bg-primary/30 selection:text-primary min-h-screen flex flex-col">

<!-- Article Header -->
<header class="relative h-[70vh] min-h-[500px] overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo e($post['image']); ?>');"></div>
<div class="absolute inset-0 bg-gradient-to-t from-background via-background/80 to-transparent"></div>
<div class="relative h-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex flex-col justify-end pb-16">
<div class="max-w-4xl">
<div class="glass-panel w-fit px-4 py-2 rounded-full mb-6 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-primary"></span>
<span class="font-label-caps text-label-caps text-on-surface tracking-widest"><?php echo e(strtoupper($post['category'])); ?></span>
</div>
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface mb-6 leading-tight"><?php echo e($post['title']); ?></h1>
<div class="flex items-center gap-6 text-on-surface-variant font-body-md text-body-md">
<span class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm">calendar_today</span>
<?php echo format_date($post['date'], 'F d, Y'); ?>
</span>
<span class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm">schedule</span>
<?php echo e($post['read_time']); ?>
</span>
<span class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm">person</span>
<?php echo e($post['author']); ?>
</span>
</div>
</div>
</div>
</header>

<!-- Article Content -->
<main class="flex-grow max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-16">
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
<!-- Main Content -->
<article class="lg:col-span-2">
<div class="blog-content text-on-surface leading-relaxed">
<?php echo nl2br(e($post['content'])); ?>
</div>

<!-- Share Section -->
<div class="mt-12 pt-8 border-t border-white/10">
<h3 class="font-label-caps text-label-caps text-on-surface mb-4">SHARE THIS ARTICLE</h3>
<div class="flex gap-4">
<a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode(site_url('blog/' . $post['slug'] . '/')); ?>" 
   target="_blank" rel="noopener" 
   class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all">
<span class="material-symbols-outlined">share</span>
</a>
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(site_url('blog/' . $post['slug'] . '/')); ?>" 
   target="_blank" rel="noopener" 
   class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all">
<span class="material-symbols-outlined">public</span>
</a>
<a href="mailto:?subject=<?php echo urlencode($post['title']); ?>&body=<?php echo urlencode('Check out this article: ' . site_url('blog/' . $post['slug'] . '/')); ?>" 
   class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all">
<span class="material-symbols-outlined">mail</span>
</a>
</div>
</div>
</article>

<!-- Sidebar -->
<aside class="lg:col-span-1">
<!-- Recent Posts -->
<?php if ($recentPosts && count($recentPosts) > 0): ?>
<div class="glass-panel rounded-xl p-6 mb-8">
<h3 class="font-label-caps text-label-caps text-on-surface mb-6">RECENT POSTS</h3>
<div class="space-y-4">
<?php foreach ($recentPosts as $recent): ?>
<a href="<?php echo site_url('blog/' . $recent['slug'] . '/'); ?>" 
   class="block group">
<div class="flex gap-4">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<div class="w-full h-full bg-cover bg-center transition-transform duration-300 group-hover:scale-110" 
     style="background-image: url('<?php echo e($recent['image']); ?>');"></div>
</div>
<div class="flex-grow">
<h4 class="font-headline-md text-[18px] text-on-surface mb-2 leading-tight group-hover:text-primary transition-colors line-clamp-2"><?php echo e($recent['title']); ?></h4>
<p class="font-body-sm text-body-sm text-on-surface-variant"><?php echo format_date($recent['date'], 'M d, Y'); ?></p>
</div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<!-- Related Posts -->
<?php if ($relatedPosts && count($relatedPosts) > 0): ?>
<div class="glass-panel rounded-xl p-6">
<h3 class="font-label-caps text-label-caps text-on-surface mb-6">RELATED POSTS</h3>
<div class="space-y-4">
<?php foreach ($relatedPosts as $related): ?>
<a href="<?php echo site_url('blog/' . $related['slug'] . '/'); ?>" 
   class="block group">
<div class="flex gap-4">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<div class="w-full h-full bg-cover bg-center transition-transform duration-300 group-hover:scale-110" 
     style="background-image: url('<?php echo e($related['image']); ?>');"></div>
</div>
<div class="flex-grow">
<h4 class="font-headline-md text-[18px] text-on-surface mb-2 leading-tight group-hover:text-primary transition-colors line-clamp-2"><?php echo e($related['title']); ?></h4>
<p class="font-body-sm text-body-sm text-on-surface-variant"><?php echo format_date($related['date'], 'M d, Y'); ?></p>
</div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</aside>
</div>
</main>

<!-- Back to Blog -->
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop pb-16">
<a href="<?php echo site_url('blog.php'); ?>" 
   class="inline-flex items-center gap-2 text-on-surface hover:text-primary transition-colors font-label-caps text-label-caps">
<span class="material-symbols-outlined">arrow_back</span>
BACK TO BLOG
</a>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>