<?php 
$pageTitle = 'Contact'; 
$pageDescription = 'Contact Apex Elite Performance for inquiries about our fitness coaching services, programs, and facility. Reach out to our elite team to discuss your training goals.';
require __DIR__ . '/includes/head.php';

// Form processing
$errors = [];
$success = false;

if (is_post_request()) {
    // CSRF protection check
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Security token expired. Please refresh the page and try again.';
    } else {
        // Rate limiting check
    $ip = $_SERVER['REMOTE_ADDR'];
    $recentSubmissions = db_count('contact_messages', 'ip_address = :ip AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)', ['ip' => $ip]);
    
    if ($recentSubmissions >= 5) {
        $errors[] = 'Too many submissions. Please try again later.';
    } else {
        // Honeypot check
        if (!empty($_POST['website']) || !empty($_POST['honeypot'])) {
            // Spam detected - silently process
            $success = true;
        } else {
            // Sanitize and validate inputs
            $name = clean_input($_POST['name'] ?? '');
            $email = clean_input($_POST['email'] ?? '');
            $phone = clean_input($_POST['phone'] ?? '');
            $subject = clean_input($_POST['subject'] ?? '');
            $message = clean_input($_POST['message'] ?? '');
            
            // Validation
            if (empty($name)) {
                $errors[] = 'Name is required.';
            } elseif (strlen($name) < 2) {
                $errors[] = 'Name must be at least 2 characters.';
            } elseif (strlen($name) > 100) {
                $errors[] = 'Name is too long.';
            }
            
            if (empty($email)) {
                $errors[] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
            
            if (!empty($phone) && !preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
                $errors[] = 'Please enter a valid phone number.';
            }
            
            if (empty($subject)) {
                $errors[] = 'Subject is required.';
            }
            
            if (empty($message)) {
                $errors[] = 'Message is required.';
            } elseif (strlen($message) < 10) {
                $errors[] = 'Message must be at least 10 characters.';
            } elseif (strlen($message) > 5000) {
                $errors[] = 'Message is too long.';
            }
            
            // Check for common spam patterns
            $spamKeywords = ['viagra', 'casino', 'lottery', 'free money', 'bitcoin', 'crypto'];
            foreach ($spamKeywords as $keyword) {
                if (stripos($message, $keyword) !== false || stripos($name, $keyword) !== false) {
                    $errors[] = 'Your message appears to be spam.';
                    break;
                }
            }
            
            if (empty($errors)) {
                // Store in database
                $messageData = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message,
                    'ip_address' => $ip,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
                ];
                
                $result = db_insert('contact_messages', $messageData);
                
                if ($result) {
                    $success = true;
                } else {
                    $errors[] = 'Failed to send message. Please try again.';
                }
            }
        }
    }
    }
}

require __DIR__ . '/includes/navigation.php'; 
?>
<body class="font-body-md antialiased min-h-screen flex flex-col">
<main class="flex-grow pt-32 pb-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
<div class="mb-16 md:mb-24 text-center md:text-left">
<h1 class="font-display-xl text-headline-lg-mobile md:text-display-xl text-on-surface uppercase mb-4">Contact <span class="text-primary">Apex</span></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Ready to elevate your performance? Reach out to our elite coaching staff to discuss programs, facilities, or schedule a consultation.</p>
</div>

<?php if ($success): ?>
<div class="glass-panel p-6 rounded-lg mb-8 border border-primary/50 bg-primary/10">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Message Sent Successfully</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Thank you for reaching out. Our team will respond within 24-48 hours.</p>
</div>
</div>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="glass-panel p-6 rounded-lg mb-8 border border-[#ffb4ab]/50 bg-[#ffb4ab]/10">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-[#ffb4ab] text-4xl" style="font-variation-settings: 'FILL' 1;">error</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Please Fix the Following</h3>
<ul class="font-body-md text-body-md text-on-surface-variant space-y-1">
<?php foreach ($errors as $error): ?>
<li><?php echo e($error); ?></li>
<?php endforeach; ?>
</ul>
</div>
</div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-gutter">
<div class="lg:col-span-7 glass-panel p-6 md:p-10 rounded-lg">
<h2 class="font-headline-md text-headline-md text-on-surface mb-8">Send a Message</h2>
<form class="space-y-6" method="POST">
<?php echo csrf_field(); ?>
<!-- Honeypot fields for spam prevention -->
<input type="text" name="website" style="display:none;" autocomplete="off" tabindex="-1">
<input type="text" name="honeypot" style="display:none;" autocomplete="off" tabindex="-1">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block font-label-caps text-label-caps text-primary mb-2 uppercase" for="name">Full Name</label>
<input class="form-input w-full px-0 py-2 font-body-md text-body-md placeholder:text-on-surface-variant/50" id="name" name="name" placeholder="JANE DOE" required type="text" value="<?php echo e($_POST['name'] ?? ''); ?>"/>
</div>
<div>
<label class="block font-label-caps text-label-caps text-primary mb-2 uppercase" for="email">Email Address</label>
<input class="form-input w-full px-0 py-2 font-body-md text-body-md placeholder:text-on-surface-variant/50" id="email" name="email" placeholder="JANE@EXAMPLE.COM" required type="email" value="<?php echo e($_POST['email'] ?? ''); ?>"/>
</div>
</div>
<div>
<label class="block font-label-caps text-label-caps text-primary mb-2 uppercase" for="phone">Phone Number</label>
<input class="form-input w-full px-0 py-2 font-body-md text-body-md placeholder:text-on-surface-variant/50" id="phone" name="phone" placeholder="+1 (555) 867-5309" type="tel" value="<?php echo e($_POST['phone'] ?? ''); ?>"/>
</div>
<div>
<label class="block font-label-caps text-label-caps text-primary mb-2 uppercase" for="subject">Subject</label>
<select class="form-input w-full px-0 py-2 font-body-md text-body-md text-on-surface-variant/50 appearance-none bg-transparent" id="subject" name="subject">
<option class="bg-surface-container-high text-on-surface" value="programs" <?php echo (($_POST['subject'] ?? '') === 'programs') ? 'selected' : ''; ?>>Training Programs</option>
<option class="bg-surface-container-high text-on-surface" value="consultation" <?php echo (($_POST['subject'] ?? '') === 'consultation') ? 'selected' : ''; ?>>Consultation</option>
<option class="bg-surface-container-high text-on-surface" value="facilities" <?php echo (($_POST['subject'] ?? '') === 'facilities') ? 'selected' : ''; ?>>Facility Inquiry</option>
<option class="bg-surface-container-high text-on-surface" value="other" <?php echo (($_POST['subject'] ?? '') === 'other') ? 'selected' : ''; ?>>Other</option>
</select>
</div>
<div>
<label class="block font-label-caps text-label-caps text-primary mb-2 uppercase" for="message">Message</label>
<textarea class="form-input w-full px-0 py-2 font-body-md text-body-md placeholder:text-on-surface-variant/50 resize-none" id="message" name="message" placeholder="HOW CAN WE HELP YOU CRUSH YOUR GOALS?" required rows="5"><?php echo e($_POST['message'] ?? ''); ?></textarea>
</div>
<button class="w-full md:w-auto bg-primary text-on-primary-fixed font-label-caps text-label-caps px-8 py-4 uppercase tracking-wider rounded-DEFAULT glow-hover transition-all duration-300 mt-4" type="submit">
Send Transmission
</button>
</form>
</div>
<div class="lg:col-span-5 space-y-8">
<div class="glass-panel p-6 md:p-8 rounded-lg flex flex-col gap-6">
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
<div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant mb-1">Headquarters</h3>
<p class="font-body-md text-body-md text-on-surface"><?php echo nl2br(e(ADDRESS)); ?></p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">call</span>
<div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant mb-1">Direct Line</h3>
<p class="font-body-md text-body-md text-on-surface"><?php echo e(CONTACT_PHONE); ?></p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">mail</span>
<div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant mb-1">Comms</h3>
<p class="font-body-md text-body-md text-on-surface"><?php echo e(CONTACT_EMAIL); ?></p>
</div>
</div>
</div>
<div class="glass-panel rounded-lg overflow-hidden h-64 relative">
<div class="bg-cover bg-center w-full h-full opacity-60 mix-blend-luminosity hover:mix-blend-normal transition-all duration-500" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAO3vV3ddsmLC6xOCkWc6-lRx8EsihAe9BNZg01nctECcje-l3M8xtOd-UW3FqfVh6qQrrHrowJg_WJVXTRudhI9I-6MLsWX4zdc4biHOYzQjdmxcyyOxtHsqgvLN6hM3XyOSYN_aHz5mJ_3wiUvGMbz-FSlfxFbVlpm-UazzyT44EMXzxhFn5FmLoqfzCtd_0njy0CgUEM2wy37obj6WPtQPFbVB1EXOHxsq67ogKjljiHEwqxMAu13Q');"></div>
<div class="absolute inset-0 border border-white/10 pointer-events-none"></div>
</div>
<div class="glass-panel p-6 md:p-8 rounded-lg flex flex-col sm:flex-row justify-between gap-6">
<div>
<h3 class="font-label-caps text-label-caps text-primary mb-2">Training Hours</h3>
<ul class="font-body-md text-body-md text-on-surface space-y-1">
<?php foreach (TRAINING_HOURS as $days => $hours): ?>
<li><?php echo e($days); ?>: <?php echo e($hours); ?></li>
<?php endforeach; ?>
</ul>
</div>
<div>
<h3 class="font-label-caps text-label-caps text-primary mb-2">Network</h3>
<div class="flex gap-4">
<a class="text-on-surface hover:text-primary transition-colors" href="<?php echo e(SOCIAL_LINKS['website']); ?>"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">public</span></a>
<a class="text-on-surface hover:text-primary transition-colors" href="<?php echo e(SOCIAL_LINKS['instagram']); ?>"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">photo_camera</span></a>
<a class="text-on-surface hover:text-primary transition-colors" href="<?php echo e(SOCIAL_LINKS['youtube']); ?>"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">play_circle</span></a>
</div>
</div>
</div>
</div>
</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
