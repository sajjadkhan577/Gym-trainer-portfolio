<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/functions.php';

// Check if user is logged in
require_login();
?>
<!DOCTYPE html><html class="dark" lang="en" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Elite Fitness - Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Montserrat:wght@400;600;700;800;900&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "secondary-fixed": "#e5e2e1",
                    "surface-container-highest": "#33343c",
                    "secondary-fixed-dim": "#c8c6c5",
                    "on-background": "#e2e1eb",
                    "on-secondary-fixed": "#1c1b1b",
                    "primary-fixed-dim": "#4ae176",
                    "on-error": "#690005",
                    "on-secondary-fixed-variant": "#474646",
                    "surface-container": "#1e1f26",
                    "tertiary-container": "#acacac",
                    "inverse-surface": "#e2e1eb",
                    "outline-variant": "#3d4a3d",
                    "on-surface": "#e2e1eb",
                    "error": "#ffb4ab",
                    "inverse-on-surface": "#2f3037",
                    "on-secondary-container": "#bab8b7",
                    "surface": "#12131a",
                    "error-container": "#93000a",
                    "primary-fixed": "#6bff8f",
                    "on-tertiary-fixed-variant": "#474747",
                    "surface-container-lowest": "#0c0e14",
                    "background": "#12131a",
                    "on-tertiary-fixed": "#1b1b1b",
                    "on-primary-fixed-variant": "#005321",
                    "inverse-primary": "#006e2f",
                    "surface-dim": "#12131a",
                    "outline": "#869585",
                    "on-surface-variant": "#bccbb9",
                    "on-primary-container": "#004b1e",
                    "tertiary-fixed-dim": "#c6c6c6",
                    "on-tertiary-container": "#404040",
                    "surface-container-low": "#1a1b22",
                    "surface-variant": "#33343c",
                    "on-primary": "#003915",
                    "on-error-container": "#ffdad6",
                    "on-secondary": "#313030",
                    "tertiary-fixed": "#e2e2e2",
                    "secondary-container": "#4a4949",
                    "on-tertiary": "#303030",
                    "secondary": "#c8c6c5",
                    "primary": "#4be277",
                    "tertiary": "#c7c7c7",
                    "surface-tint": "#4ae176",
                    "on-primary-fixed": "#002109",
                    "surface-container-high": "#282a31",
                    "surface-bright": "#383940",
                    "primary-container": "#22c55e"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "base": "8px",
                    "margin-desktop": "64px",
                    "margin-mobile": "20px",
                    "container-max": "1280px",
                    "gutter": "24px"
            },
            "fontFamily": {
                    "headline-lg": ["Montserrat"],
                    "body-lg": ["Inter"],
                    "headline-md": ["Montserrat"],
                    "label-caps": ["Montserrat"],
                    "display-xl": ["Montserrat"],
                    "headline-lg-mobile": ["Montserrat"],
                    "body-md": ["Inter"]
            },
            "fontSize": {
                    "headline-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "800"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["32px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.1em", "fontWeight": "700"}],
                    "display-xl": ["72px", {"lineHeight": "1.1", "letterSpacing": "-0.04em", "fontWeight": "900"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "800"}],
                    "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}]
            }
          }
        }
      }
    </script>
<style>
        body {
            background-color: theme('colors.background');
            color: theme('colors.on-background');
        }
        
        .glass-panel {
            background-color: rgba(30, 31, 38, 0.7); /* surface-container with opacity */
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-panel:hover {
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.05); /* subtle primary glow */
        }
        
        .glow-hover:hover {
            box-shadow: 0 0 20px theme('colors.primary-container');
        }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
</head>
<body class="font-body-md text-body-md flex min-h-screen bg-background">
<?php display_toast_message(); ?>
