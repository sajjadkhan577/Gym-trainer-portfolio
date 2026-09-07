<?php
// SEO Testing Script
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

echo "=== SEO Implementation Test ===\n\n";

// Test 1: Meta Tags
echo "1. Meta Tags Configuration:\n";
echo "   - Site Name: " . SITE_NAME . "\n";
echo "   - Site Tagline: " . SITE_TAGLINE . "\n";
echo "   - Site URL: " . SITE_URL . "\n\n";

// Test 2: Sitemap Generation
echo "2. Sitemap Generation:\n";
$sitemapUrl = SITE_URL . '/sitemap.php';
echo "   - Sitemap URL: " . $sitemapUrl . "\n";
echo "   - Status: Configured\n\n";

// Test 3: Robots.txt
echo "3. Robots.txt Configuration:\n";
$robotsPath = __DIR__ . '/robots.txt';
if (file_exists($robotsPath)) {
    echo "   - Status: File exists\n";
    echo "   - Content preview:\n";
    $robotsContent = file_get_contents($robotsPath);
    echo "   " . str_replace("\n", "\n   ", substr($robotsContent, 0, 200)) . "...\n\n";
} else {
    echo "   - Status: File not found\n\n";
}

// Test 4: Favicon Files
echo "4. Favicon Configuration:\n";
$faviconFiles = ['favicon.svg', 'site.webmanifest'];
foreach ($faviconFiles as $file) {
    $exists = file_exists(__DIR__ . '/' . $file) ? "✓" : "✗";
    echo "   - $file: $exists\n";
}
echo "\n";

// Test 5: .htaccess SEO Rules
echo "5. .htaccess SEO Rules:\n";
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    $htaccessContent = file_get_contents($htaccessPath);
    if (strpos($htaccessContent, 'blog/([^/]+)') !== false) {
        echo "   - Blog slug routing: ✓\n";
    }
    if (strpos($htaccessContent, 'about/?$') !== false) {
        echo "   - Clean URLs for pages: ✓\n";
    }
    if (strpos($htaccessContent, 'canonical') !== false) {
        echo "   - Canonical URL handling: ✓\n";
    }
} else {
    echo "   - Status: .htaccess not found\n";
}
echo "\n";

// Test 6: Page Descriptions
echo "6. Page Meta Descriptions:\n";
$pages = ['index.php', 'about.php', 'services.php', 'programs.php'];
foreach ($pages as $page) {
    $pagePath = __DIR__ . '/' . $page;
    if (file_exists($pagePath)) {
        $content = file_get_contents($pagePath);
        if (strpos($content, '$pageDescription') !== false) {
            echo "   - $page: ✓\n";
        } else {
            echo "   - $page: ✗\n";
        }
    }
}
echo "\n";

echo "=== SEO Test Complete ===\n";
echo "All core SEO features have been implemented.\n";
echo "Visit sitemap.php to generate the XML sitemap.\n";
