<?php require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/helpers.php';

// SEO Variables
$metaTitle = isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME;
$metaDescription = isset($pageDescription) ? $pageDescription : SITE_TAGLINE;

// Generate canonical URL handling both .php and clean URLs
$requestUri = $_SERVER['REQUEST_URI'];
// Remove .php extension if present for cleaner canonical URLs
$canonicalPath = preg_replace('/\.php$/', '', $requestUri);
$canonicalUrl = rtrim(SITE_URL, '/') . '/' . ltrim($canonicalPath, '/');

// Social images (using placeholder approach)
$ogImage = 'https://via.placeholder.com/1200x630/12131a/4be277?text=Apex+Elite+Performance';
$twitterImage = 'https://via.placeholder.com/1200x675/12131a/4be277?text=Apex+Elite+Performance';

// Current page info
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Set security headers if not already sent
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?php echo e($metaTitle); ?></title>
<meta name="description" content="<?php echo e($metaDescription); ?>"/>
<meta name="theme-color" content="#12131a"/>
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1"/>
<link rel="canonical" href="<?php echo e($canonicalUrl); ?>"/>

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?php echo e($metaTitle); ?>"/>
<meta property="og:description" content="<?php echo e($metaDescription); ?>"/>
<meta property="og:url" content="<?php echo e($canonicalUrl); ?>"/>
<meta property="og:site_name" content="<?php echo e(SITE_NAME); ?>"/>
<meta property="og:image" content="<?php echo e($ogImage); ?>"/>
<meta property="og:image:width" content="1200"/>
<meta property="og:image:height" content="630"/>
<meta property="og:image:alt" content="<?php echo e(SITE_NAME); ?>"/>

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?php echo e($metaTitle); ?>"/>
<meta name="twitter:description" content="<?php echo e($metaDescription); ?>"/>
<meta name="twitter:image" content="<?php echo e($twitterImage); ?>"/>
<meta name="twitter:site" content="@apexcoaching"/>
<meta name="twitter:creator" content="@apexcoaching"/>

<!-- Additional SEO -->
<meta name="author" content="<?php echo e(COACH_NAME); ?>"/>
<meta name="keywords" content="elite fitness coaching, personal training, athletic performance, strength training, muscle building, fat loss, sports conditioning"/>
<meta name="geo.region" content="US-NY"/>
<meta name="geo.placename" content="New York"/>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="<?php echo asset_url('favicon.svg'); ?>"/>
<link rel="manifest" href="<?php echo asset_url('site.webmanifest'); ?>"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://fonts.gstatic.com"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Montserrat:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo asset_url('css/styles.css'); ?>"/>
<script>
// Schema.org Structured Data
const schemaData = {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "<?php echo e(SITE_NAME); ?>",
    "description": "<?php echo e(SITE_TAGLINE); ?>",
    "url": "<?php echo e(SITE_URL); ?>",
    "telephone": "<?php echo e(CONTACT_PHONE); ?>",
    "email": "<?php echo e(CONTACT_EMAIL); ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo e(ADDRESS); ?>",
        "addressLocality": "New York",
        "addressRegion": "NY",
        "addressCountry": "US"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "40.7128",
        "longitude": "-74.0060"
    },
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
            "Monday",
            "Tuesday", 
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday"
        ],
        "opens": "06:00",
        "closes": "22:00"
    },
    "priceRange": "$$",
    "image": "<?php echo e($ogImage); ?>"
};
document.head.appendChild(Object.assign(document.createElement('script'), {type: 'application/ld+json', textContent: JSON.stringify(schemaData)}));

tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            "colors": {
                "surface-bright": "#383940",
                "on-surface": "#e2e1eb",
                "primary-fixed-dim": "#4ae176",
                "surface": "#12131a",
                "on-background": "#e2e1eb",
                "tertiary-container": "#acacac",
                "primary": "#4be277",
                "tertiary": "#c7c7c7",
                "error-container": "#93000a",
                "surface-container-low": "#1a1b22",
                "on-secondary": "#313030",
                "on-tertiary": "#303030",
                "surface-dim": "#12131a",
                "background": "#12131a",
                "secondary-fixed": "#e5e2e1",
                "surface-container-lowest": "#0c0e14",
                "inverse-on-surface": "#2f3037",
                "on-primary-fixed": "#002109",
                "on-error": "#690005",
                "on-tertiary-container": "#404040",
                "tertiary-fixed": "#e2e2e2",
                "secondary": "#c8c6c5",
                "on-secondary-fixed-variant": "#474646",
                "secondary-fixed-dim": "#c8c6c5",
                "secondary-container": "#4a4949",
                "surface-container-high": "#282a31",
                "surface-variant": "#33343c",
                "on-primary-container": "#004b1e",
                "inverse-surface": "#e2e1eb",
                "surface-container-highest": "#33343c",
                "on-secondary-container": "#bab8b7",
                "on-tertiary-fixed-variant": "#474747",
                "surface-tint": "#4ae176",
                "surface-container": "#1e1f26",
                "tertiary-fixed-dim": "#c6c6c6",
                "primary-fixed": "#6bff8f",
                "error": "#ffb4ab",
                "on-tertiary-fixed": "#1b1b1b",
                "outline": "#869585",
                "on-primary-fixed-variant": "#005321",
                "inverse-primary": "#006e2f",
                "on-primary": "#003915",
                "outline-variant": "#3d4a3d",
                "on-surface-variant": "#bccbb9",
                "on-secondary-fixed": "#1c1b1b",
                "on-error-container": "#ffdad6",
                "primary-container": "#22c55e"
            },
            "borderRadius": {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            "spacing": {
                "container-max": "1280px",
                "gutter": "24px",
                "margin-mobile": "20px",
                "base": "8px",
                "margin-desktop": "64px"
            },
            "fontFamily": {
                "body-md": ["Inter"],
                "label-caps": ["Montserrat"],
                "display-xl": ["Montserrat"],
                "headline-lg": ["Montserrat"],
                "headline-md": ["Montserrat"],
                "headline-lg-mobile": ["Montserrat"],
                "body-lg": ["Inter"]
            },
            "fontSize": {
                "body-md": ["16px", { "lineHeight": "1.5", "fontWeight": "400" }],
                "label-caps": ["12px", { "lineHeight": "1", "letterSpacing": "0.1em", "fontWeight": "700" }],
                "display-xl": ["72px", { "lineHeight": "1.1", "letterSpacing": "-0.04em", "fontWeight": "900" }],
                "headline-lg": ["48px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "800" }],
                "headline-md": ["32px", { "lineHeight": "1.3", "fontWeight": "700" }],
                "headline-lg-mobile": ["32px", { "lineHeight": "1.2", "fontWeight": "800" }],
                "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }]
            }
        }
    }
}
</script>
<style>
body { background-color: theme('colors.background'); color: theme('colors.on-background'); }
html { scroll-behavior: smooth; }
</style>
</head>
<body class="antialiased overflow-x-hidden selection:bg-primary selection:text-on-primary font-body-md">
