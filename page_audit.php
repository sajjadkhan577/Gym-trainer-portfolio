<?php
// Page Audit Script
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

echo "=== PAGE AUDIT ===\n\n";

$pages = [
    'index.php' => 'Home Page',
    'about.php' => 'About Page',
    'services.php' => 'Services Page',
    'programs.php' => 'Programs Page',
    'gallery.php' => 'Gallery Page',
    'blog.php' => 'Blog Page',
    'testimonials.php' => 'Testimonials Page',
    'transformations.php' => 'Transformations Page',
    'contact.php' => 'Contact Page',
    'book.php' => 'Booking Page',
    '404.php' => '404 Error Page',
    '500.php' => '500 Error Page'
];

echo "1. File Existence Check:\n";
foreach ($pages as $file => $name) {
    $exists = file_exists(__DIR__ . '/' . $file) ? "✓" : "✗";
    echo "   - $name ($file): $exists\n";
}
echo "\n";

echo "2. Critical File Check:\n";
$criticalFiles = [
    'includes/head.php' => 'Head Component',
    'includes/navigation.php' => 'Navigation',
    'includes/footer.php' => 'Footer',
    'includes/helpers.php' => 'Helper Functions',
    'database/database.php' => 'Database Class',
    'config/config.php' => 'Configuration',
    '.htaccess' => 'Apache Configuration'
];

foreach ($criticalFiles as $file => $name) {
    $exists = file_exists(__DIR__ . '/' . $file) ? "✓" : "✗";
    echo "   - $name ($file): $exists\n";
}
echo "\n";

echo "3. Asset Files Check:\n";
$assetFiles = [
    'assets/css/styles.css' => 'Main CSS',
    'assets/js/main.js' => 'Main JavaScript',
    'favicon.svg' => 'Favicon',
    'site.webmanifest' => 'Web Manifest'
];

foreach ($assetFiles as $file => $name) {
    $exists = file_exists(__DIR__ . '/' . $file) ? "✓" : "✗";
    echo "   - $name ($file): $exists\n";
}
echo "\n";

echo "4. Component Files Check:\n";
$componentFiles = [
    'includes/components/gallery_item.php',
    'includes/components/filter_buttons.php',
    'includes/components/testimonial_card.php',
    'includes/components/star_rating.php',
    'includes/components/blog_card.php'
];

foreach ($componentFiles as $file) {
    $name = basename($file);
    $exists = file_exists(__DIR__ . '/' . $file) ? "✓" : "✗";
    echo "   - $name: $exists\n";
}
echo "\n";

echo "5. PHP Syntax Check:\n";
$allFiles = array_merge(array_keys($pages), array_keys($criticalFiles), ['includes/components/gallery_item.php', 'includes/components/blog_card.php']);
$syntaxErrors = [];

foreach ($allFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        $output = [];
        $returnCode = 0;
        $phpPath = 'C:\\xampp\\php\\php.exe';
        $command = "\"$phpPath\" -l \"$filePath\" 2>&1";
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            $syntaxErrors[$file] = implode("\n", $output);
            echo "   - $file: ✗\n";
        } else {
            echo "   - $file: ✓\n";
        }
    }
}

if (!empty($syntaxErrors)) {
    echo "\nSyntax Errors Found:\n";
    foreach ($syntaxErrors as $file => $error) {
        echo "   - $file: $error\n";
    }
} else {
    echo "\n✓ No PHP syntax errors found.\n";
}
echo "\n";

echo "6. Code Quality Check:\n";
$issues = [];

// Check for TODO/FIXME (but skip form placeholders and database placeholders)
foreach ($allFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath) && $file !== 'page_audit.php') {
        $content = file_get_contents($filePath);
        
        // Check for actual TODO/FIXME comments, not legitimate placeholders
        if (preg_match('/TODO:|FIXME:|\/\/TODO|\/\/FIXME/i', $content)) {
            $issues[$file][] = 'Contains TODO/FIXME comments';
        }
        
        // Check for console.log (in JavaScript context)
        if (preg_match('/console\.log\(/', $content) && !strpos($file, '.php')) {
            $issues[$file][] = 'Contains console.log';
        }
    }
}

if (!empty($issues)) {
    echo "   Code Quality Issues Found:\n";
    foreach ($issues as $file => $fileIssues) {
        echo "   - $file: " . implode(', ', $fileIssues) . "\n";
    }
} else {
    echo "   ✓ No code quality issues found.\n";
}
echo "\n";

echo "=== PAGE AUDIT COMPLETE ===\n";
