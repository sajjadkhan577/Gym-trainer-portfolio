<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } elseif (!empty($_SESSION['login_locked_until']) && $_SESSION['login_locked_until'] > time()) {
        $error = 'Too many login attempts. Please try again later.';
    } else {
        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Query admins table for authentication
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email AND status = 'active'");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Update last login time
            $updateStmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $admin['id']]);
            
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name'];
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
            session_regenerate_id(true);
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['login_locked_until'] = time() + 900;
            }
        }
    }
}
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Apex Coaching - Admin Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Montserrat:wght@700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                    }
                }
            }
        }
    </script>
<style>
        .glass-card {
            background: rgba(18, 19, 26, 0.7);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glow-effect {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(34,197,94,0.15) 0%, rgba(18,19,26,0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            pointer-events: none;
        }
        .input-glass {
            background: rgba(255, 255, 255, 0.03);
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            outline: none;
            border-bottom-color: #22C55E;
            background: rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }
        .btn-neon {
            background-color: #22C55E;
            color: #000000;
            transition: all 0.3s ease;
        }
        .btn-neon:hover {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex items-center justify-center relative overflow-hidden font-body-md">
<!-- Background Texture/Image -->
<div class="absolute inset-0 bg-cover bg-center opacity-30 z-0" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBgtvbqRQXTRqeJQKilWaOjV2kUSPRChFGFc6W68edR72zQgTdF5feW8O7Le8Hs-n9BxSKU3-GX4dVD0XZIhed0ggNA-Vp2_31VrUeJs-EnitkcEM5b_O5UntNtAo26DWx0f0PGvOjC5fpkWFwbFUBqSzlQWqeI0AdwmJvNX5gm4VMWnprQjZ9c8iOmVCXgkaSNINa4-jD4e68ASoFf11g3OXiSeCsQNTlqzji9UvtRFM2GaaygACs3ow')"></div>
<div class="absolute inset-0 bg-surface/80 z-0"></div> <!-- Dark overlay -->
<!-- Neon Glow Behind Card -->
<div class="glow-effect"></div>
<main class="w-full max-w-md px-margin-mobile relative z-10">
<div class="glass-card rounded-xl p-8 md:p-12 shadow-2xl relative">
<!-- Brand -->
<div class="text-center mb-10">
<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 border border-primary/30 mb-4">
<span class="material-symbols-outlined text-primary text-[32px]">fitness_center</span>
</div>
<h1 class="font-headline-md text-headline-md text-on-surface uppercase tracking-tight">APEX COACHING</h1>
<p class="font-body-md text-body-md text-on-surface-variant mt-2">Elite Admin Access</p>
</div>

<?php if ($error): ?>
<div class="mb-4 p-3 bg-error/10 border border-error/20 text-error rounded-md text-sm text-center">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Form -->
<form action="login.php" class="space-y-6" method="POST">
<input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
<div>
<label class="block font-label-caps text-label-caps text-on-surface-variant mb-2" for="email">Email Address</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-0 top-3 text-on-surface-variant">mail</span>
<input class="input-glass w-full pl-8 pr-4 py-3 font-body-md text-body-md text-on-surface placeholder-on-surface-variant/50 focus:ring-0" id="email" name="email" placeholder="admin@apexcoaching.com" required="" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
</div>
</div>
<div>
<label class="block font-label-caps text-label-caps text-on-surface-variant mb-2" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-0 top-3 text-on-surface-variant">lock</span>
<input class="input-glass w-full pl-8 pr-10 py-3 font-body-md text-body-md text-on-surface placeholder-on-surface-variant/50 focus:ring-0" id="password" name="password" placeholder="••••••••" required="" type="password"/>
</div>
</div>
<div class="flex items-center justify-between mt-6">
<div class="flex items-center">
<input class="h-4 w-4 rounded border-on-surface-variant/30 bg-surface/50 text-primary focus:ring-primary focus:ring-offset-surface" id="remember-me" name="remember-me" type="checkbox"/>
<label class="ml-2 block font-body-md text-body-md text-on-surface-variant" for="remember-me">
                            Remember me
                        </label>
</div>
</div>
<div class="pt-6">
<button class="btn-neon w-full flex justify-center py-4 px-4 rounded-lg font-label-caps text-label-caps focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface uppercase tracking-widest font-bold" type="submit">
                        Authenticate
                        <span class="material-symbols-outlined ml-2 text-[16px]">arrow_forward</span>
</button>
</div>
</form>
</div>
<!-- Footer info -->
<div class="text-center mt-8">
<p class="font-body-md text-body-md text-on-surface-variant/50 text-sm">
                © <?= date('Y') ?> Apex Coaching. Authorized personnel only.
            </p>
</div>
</main>
</body></html>
