<?php 
$pageTitle = 'Blog'; 
$pageDescription = 'Read our latest fitness articles, training tips, nutrition advice, and performance insights from our elite coaching team at Apex Elite Performance.';
require __DIR__ . '/includes/head.php';

// Get pagination parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Get search and category filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? strtoupper($_GET['category']) : 'ALL';

// Build query conditions
$where = 'status = :status';
$params = ['status' => 'published'];

if ($category !== 'ALL') {
    $where .= ' AND category = :category';
    $params['category'] = $category;
}

if (!empty($search)) {
    $where .= ' AND (title LIKE :search OR excerpt LIKE :search OR content LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

// Get total count for pagination
$totalPosts = db_count('blog_posts', $where, $params);
$totalPages = ceil($totalPosts / $perPage);

// Get posts for current page
$posts = db_select('blog_posts', '*', $where, $params, 'date DESC, sort_order ASC', $offset . ', ' . $perPage);

// Get all unique categories
$allCategories = db_query('SELECT DISTINCT category FROM blog_posts WHERE status = :status ORDER BY category', ['status' => 'published']);
$categories = ['ALL'];
foreach ($allCategories as $cat) {
    $categories[] = strtoupper($cat['category']);
}

// Get featured post for top section
$featuredPost = db_fetch_one('SELECT * FROM blog_posts WHERE featured = :featured AND status = :status ORDER BY date DESC LIMIT 1', ['featured' => true, 'status' => 'published']);

require __DIR__ . '/includes/navigation.php'; 
?>
<body class="bg-background text-on-surface font-body-md antialiased pt-[88px] selection:bg-primary/30 selection:text-primary min-h-screen flex flex-col">
<main class="flex-grow w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12 md:py-24" id="blog-grid">
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-8">
<div class="max-w-2xl">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-4 text-on-surface">THE INTEL</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">Insights, strategies, and science for high-performance athletes.</p>
</div>
<?php
$containerId = '#blog-grid';
require __DIR__ . '/includes/components/filter_buttons.php';
?>
</div>

<!-- Search Bar -->
<div class="mb-12">
<form method="GET" action="" class="relative max-w-2xl">
<input type="text" name="search" placeholder="Search articles..." value="<?php echo e($search); ?>" 
       class="w-full glass-panel rounded-xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary transition-all">
<button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">search</span>
</button>
<?php if ($category !== 'ALL'): ?>
<input type="hidden" name="category" value="<?php echo strtolower($category); ?>">
<?php endif; ?>
</form>
</div>

<?php if ($featuredPost && $page === 1 && empty($search) && $category === 'ALL'): ?>
<!-- Featured Post -->
<?php
$featuredData = [
    'image' => $featuredPost['image'],
    'category' => strtoupper($featuredPost['category']),
    'title' => $featuredPost['title'],
    'excerpt' => $featuredPost['excerpt'],
    'date' => format_date($featuredPost['date'], 'M d, Y'),
    'readTime' => $featuredPost['read_time'],
    'featured' => true,
    'slug' => $featuredPost['slug']
];
extract($featuredData);
require __DIR__ . '/includes/components/blog_card.php';
?>
<?php endif; ?>

<!-- Posts Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24">
<?php if ($posts && count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <?php
        $postData = [
            'image' => $post['image'],
            'category' => strtoupper($post['category']),
            'title' => $post['title'],
            'excerpt' => $post['excerpt'],
            'date' => format_date($post['date'], 'M d, Y'),
            'readTime' => $post['read_time'],
            'featured' => false,
            'slug' => $post['slug']
        ];
        extract($postData);
        require __DIR__ . '/includes/components/blog_card.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-span-full text-center py-12">
    <p class="text-on-surface-variant text-lg">No articles found matching your criteria.</p>
    <a href="<?php echo site_url('blog.php'); ?>" class="inline-block mt-4 text-primary hover:underline">Clear filters</a>
    </div>
<?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="flex justify-center items-center gap-4">
<?php if ($page > 1): ?>
    <a href="<?php echo site_url('blog.php?page=' . ($page - 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . ($category !== 'ALL' ? '&category=' . strtolower($category) : '')); ?>" 
       class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all">
    <span class="material-symbols-outlined">arrow_back</span>
    </a>
<?php else: ?>
    <button disabled class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface-variant opacity-30 cursor-not-allowed">
    <span class="material-symbols-outlined">arrow_back</span>
    </button>
<?php endif; ?>

<div class="flex items-center gap-2">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i === $page): ?>
        <span class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-caps text-label-caps"><?php echo $i; ?></span>
    <?php else: ?>
        <a href="<?php echo site_url('blog.php?page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . ($category !== 'ALL' ? '&category=' . strtolower($category) : '')); ?>" 
           class="w-10 h-10 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all font-label-caps text-label-caps">
        <?php echo $i; ?>
        </a>
    <?php endif; ?>
<?php endfor; ?>
</div>

<?php if ($page < $totalPages): ?>
    <a href="<?php echo site_url('blog.php?page=' . ($page + 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . ($category !== 'ALL' ? '&category=' . strtolower($category) : '')); ?>" 
       class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-all">
    <span class="material-symbols-outlined">arrow_forward</span>
    </a>
<?php else: ?>
    <button disabled class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface-variant opacity-30 cursor-not-allowed">
    <span class="material-symbols-outlined">arrow_forward</span>
    </button>
<?php endif; ?>
</div>
<?php endif; ?>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>