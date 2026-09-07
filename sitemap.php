<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

// Static pages with priority and change frequency
$staticPages = [
    ['url' => 'index.php', 'priority' => '1.0', 'changefreq' => 'daily'],
    ['url' => 'about.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['url' => 'services.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['url' => 'programs.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['url' => 'gallery.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['url' => 'blog.php', 'priority' => '0.8', 'changefreq' => 'daily'],
    ['url' => 'testimonials.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['url' => 'transformations.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['url' => 'contact.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['url' => 'book.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
];

foreach ($staticPages as $page) {
    $url = site_url($page['url']);
    $lastmod = date('Y-m-d');
    
    echo '<url>';
    echo '<loc>' . e($url) . '</loc>';
    echo '<lastmod>' . $lastmod . '</lastmod>';
    echo '<changefreq>' . $page['changefreq'] . '</changefreq>';
    echo '<priority>' . $page['priority'] . '</priority>';
    echo '</url>';
}

// Dynamic blog posts
try {
    $blogPosts = db_select('blog_posts', ['id', 'slug', 'updated_at'], 'status = "published"', [], 'updated_at DESC', 0);
    
    foreach ($blogPosts as $post) {
        $url = site_url('blog/' . $post['slug'] . '/');
        $lastmod = date('Y-m-d', strtotime($post['updated_at']));
        
        echo '<url>';
        echo '<loc>' . e($url) . '</loc>';
        echo '<lastmod>' . $lastmod . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.7</priority>';
        echo '</url>';
    }
} catch (Exception $e) {
    // Skip blog posts if database error
}

echo '</urlset>';
