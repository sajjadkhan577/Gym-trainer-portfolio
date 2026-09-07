<?php 
$pageTitle = 'Book a Session'; 
$pageDescription = 'Book your training session at Apex Elite Performance. Schedule your personal training, group coaching, or performance assessment with our elite fitness coaches.';
require __DIR__ . '/includes/head.php';

// Handle service parameter from URL
$selectedService = null;
if (isset($_GET['service']) && is_numeric($_GET['service'])) {
    $selectedService = db_fetch_one("SELECT * FROM services WHERE id = :id AND status = 'active'", ['id' => $_GET['service']]);
}

// Handle program parameter from URL
$selectedProgram = null;
if (isset($_GET['program']) && is_numeric($_GET['program'])) {
    $selectedProgram = db_fetch_one("SELECT * FROM programs WHERE id = :id AND status = 'active'", ['id' => $_GET['program']]);
}

// Load all available services and programs for dropdowns
$allServices = db_select('services', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');
$allPrograms = db_select('programs', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');

// Form processing
$errors = [];
$success = false;
$bookingDetails = null;

if (is_post_request()) {
    // CSRF protection check
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Security token expired. Please refresh the page and try again.';
    } else {
        // Honeypot check
        if (!empty($_POST['website']) || !empty($_POST['honeypot'])) {
        // Spam detected - silently process
        $success = true;
    } else {
        // Sanitize and validate inputs
        $fullName = clean_input($_POST['full_name'] ?? '');
        $email = clean_input($_POST['email'] ?? '');
        $phone = clean_input($_POST['phone'] ?? '');
        $preferredDate = clean_input($_POST['preferred_date'] ?? '');
        $preferredTime = clean_input($_POST['preferred_time'] ?? '');
        $fitnessGoal = clean_input($_POST['fitness_goal'] ?? '');
        $currentFitnessLevel = clean_input($_POST['current_fitness_level'] ?? '');
        $message = clean_input($_POST['message'] ?? '');
        $serviceId = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
        $programId = !empty($_POST['program_id']) ? (int)$_POST['program_id'] : null;
        
        // Validation
        if (empty($fullName)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullName) < 2) {
            $errors[] = 'Name must be at least 2 characters.';
        } elseif (strlen($fullName) > 100) {
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
        
        if (empty($preferredDate)) {
            $errors[] = 'Preferred date is required.';
        } elseif (!strtotime($preferredDate)) {
            $errors[] = 'Please enter a valid date.';
        } elseif (strtotime($preferredDate) < strtotime('today')) {
            $errors[] = 'You cannot book a date in the past.';
        }
        
        if (empty($preferredTime)) {
            $errors[] = 'Preferred time is required.';
        }
        
        if (empty($fitnessGoal)) {
            $errors[] = 'Fitness goal is required.';
        }
        
        if (empty($currentFitnessLevel)) {
            $errors[] = 'Current fitness level is required.';
        }
        
        if (empty($serviceId) && empty($programId)) {
            $errors[] = 'Please select a service or program.';
        }
        
        if (!empty($message) && strlen($message) > 2000) {
            $errors[] = 'Message is too long.';
        }
        
        // Check for duplicate booking (same email, date, and time within 24 hours)
        if (empty($errors)) {
            $duplicateCheck = db_count('bookings', 
                'email = :email AND preferred_date = :date AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)',
                ['email' => $email, 'date' => $preferredDate]);
            
            if ($duplicateCheck > 0) {
                $errors[] = 'You already have a booking for this date. Please choose a different date or time.';
            }
        }
        
        if (empty($errors)) {
            // Store in database
            $bookingData = [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'preferred_date' => $preferredDate,
                'preferred_time' => $preferredTime,
                'fitness_goal' => $fitnessGoal,
                'service_id' => $serviceId,
                'program_id' => $programId,
                'current_fitness_level' => $currentFitnessLevel,
                'message' => $message,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ];
            
            $result = db_insert('bookings', $bookingData);
            
            if ($result) {
                $success = true;
                $bookingDetails = [
                    'name' => $fullName,
                    'date' => format_date($preferredDate, 'F d, Y'),
                    'time' => date('g:i A', strtotime($preferredTime)),
                    'goal' => $fitnessGoal,
                    'service' => $selectedService ? $selectedService['title'] : null,
                    'program' => $selectedProgram ? $selectedProgram['title'] : null
                ];
            } else {
                $errors[] = 'Failed to process booking. Please try again.';
            }
        }
    }
    }
}

require __DIR__ . '/includes/navigation.php'; 
?>
<body class="bg-background text-on-background antialiased min-h-screen flex flex-col selection:bg-primary-container selection:text-black">
<main class="flex-grow flex items-center justify-center py-20 px-margin-mobile md:px-margin-desktop relative">
<div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
<div class="absolute top-[-10%] right-[-5%] w-[40vw] h-[40vw] rounded-full bg-primary-container/10 blur-[120px]"></div>
<div class="absolute bottom-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-surface-bright/20 blur-[150px]"></div>
</div>
<div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 relative z-10">
<div class="md:col-span-5 flex flex-col justify-center">
<a class="font-headline-md text-headline-md font-black text-on-surface tracking-tighter mb-8 inline-block hover:text-primary transition-colors" href="<?php echo site_url('index.php'); ?>">
<?php echo e(SITE_NAME); ?>
</a>
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-6 leading-tight">
COMMIT TO <br/><span class="text-primary-container">PROGRESS</span>.
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-12">
<?php if ($selectedService): ?>
You're booking: <span class="text-primary font-bold"><?php echo e($selectedService['title']); ?></span> - $<?php echo number_format($selectedService['price'], 2); ?>
<?php elseif ($selectedProgram): ?>
You're enrolling in: <span class="text-primary font-bold"><?php echo e($selectedProgram['title']); ?></span> - $<?php echo number_format($selectedProgram['price'], 2); ?>
<?php else: ?>
Secure your session. Our elite trainers are ready to push your limits. Fill out the details to configure your optimal training protocol.
<?php endif; ?>
</p>
<div class="hidden md:flex flex-col gap-6">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary-container text-2xl">location_on</span>
<div>
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">The Facility</p>
<p class="font-body-md text-body-md text-on-surface">124 Elite Way, Iron District</p>
</div>
</div>
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary-container text-2xl">mail</span>
<div>
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">Contact</p>
<p class="font-body-md text-body-md text-on-surface">train@apexcoaching.com</p>
</div>
</div>
</div>
</div>
<div class="md:col-span-7 glass-panel rounded-xl p-6 md:p-10 shadow-2xl">
<?php if ($success): ?>
<div class="glass-panel p-6 rounded-lg mb-8 border border-primary/50 bg-primary/10">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Booking Confirmed!</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Thank you <?php echo e($fullName); ?>! Your booking has been received.</p>
<div class="mt-4 p-4 bg-surface-container rounded-lg">
<p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Date:</strong> <?php echo e($bookingDetails['date'] ?? ''); ?></p>
<p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Time:</strong> <?php echo e($bookingDetails['time'] ?? ''); ?></p>
<p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Goal:</strong> <?php echo e($bookingDetails['goal'] ?? ''); ?></p>
<?php if ($bookingDetails['service']): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Service:</strong> <?php echo e($bookingDetails['service']); ?></p>
<?php endif; ?>
<?php if ($bookingDetails['program']): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Program:</strong> <?php echo e($bookingDetails['program']); ?></p>
<?php endif; ?>
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

<?php if (!$success): ?>
<form action="" class="space-y-8" method="POST">
<?php echo csrf_field(); ?>
<!-- Honeypot fields for spam prevention -->
<input type="text" name="website" style="display:none;" autocomplete="off" tabindex="-1">
<input type="text" name="honeypot" style="display:none;" autocomplete="off" tabindex="-1">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="full_name">Full Name</label>
<input class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t" id="full_name" name="full_name" placeholder="John Doe" required type="text" value="<?php echo e($_POST['full_name'] ?? ''); ?>"/>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="phone">Phone Number</label>
<input class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t" id="phone" name="phone" placeholder="+1 (555) 000-0000" type="tel" value="<?php echo e($_POST['phone'] ?? ''); ?>"/>
</div>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="email">Email Address</label>
<input class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t" id="email" name="email" placeholder="john@example.com" required type="email" value="<?php echo e($_POST['email'] ?? ''); ?>"/>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="preferred_date">Preferred Date</label>
<div class="relative">
<input class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t" id="preferred_date" name="preferred_date" style="color-scheme: dark;" type="date" value="<?php echo e($_POST['preferred_date'] ?? ''); ?>" min="<?php echo date('Y-m-d'); ?>"/>
<span class="material-symbols-outlined absolute right-3 top-3.5 text-on-surface-variant pointer-events-none">calendar_month</span>
</div>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="preferred_time">Preferred Time</label>
<div class="relative">
<input class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t" id="preferred_time" name="preferred_time" style="color-scheme: dark;" type="time" value="<?php echo e($_POST['preferred_time'] ?? ''); ?>"/>
<span class="material-symbols-outlined absolute right-3 top-3.5 text-on-surface-variant pointer-events-none">schedule</span>
</div>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="fitness_goal">Primary Goal</label>
<select class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t appearance-none" id="fitness_goal" name="fitness_goal">
<option disabled selected value="">Select Goal</option>
<option class="bg-surface text-on-surface" value="hypertrophy" <?php echo (($_POST['fitness_goal'] ?? '') === 'hypertrophy') ? 'selected' : ''; ?>>Hypertrophy / Muscle Gain</option>
<option class="bg-surface text-on-surface" value="strength" <?php echo (($_POST['fitness_goal'] ?? '') === 'strength') ? 'selected' : ''; ?>>Pure Strength</option>
<option class="bg-surface text-on-surface" value="endurance" <?php echo (($_POST['fitness_goal'] ?? '') === 'endurance') ? 'selected' : ''; ?>>Athletic Endurance</option>
<option class="bg-surface text-on-surface" value="fat_loss" <?php echo (($_POST['fitness_goal'] ?? '') === 'fat_loss') ? 'selected' : ''; ?>>Fat Loss / Conditioning</option>
</select>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="current_fitness_level">Current Fitness Level</label>
<select class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t appearance-none" id="current_fitness_level" name="current_fitness_level">
<option disabled selected value="">Select Level</option>
<option class="bg-surface text-on-surface" value="beginner" <?php echo (($_POST['current_fitness_level'] ?? '') === 'beginner') ? 'selected' : ''; ?>>Beginner</option>
<option class="bg-surface text-on-surface" value="intermediate" <?php echo (($_POST['current_fitness_level'] ?? '') === 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
<option class="bg-surface text-on-surface" value="advanced" <?php echo (($_POST['current_fitness_level'] ?? '') === 'advanced') ? 'selected' : ''; ?>>Advanced</option>
<option class="bg-surface text-on-surface" value="elite" <?php echo (($_POST['current_fitness_level'] ?? '') === 'elite') ? 'selected' : ''; ?>>Elite</option>
</select>
</div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="service_id">Select Service (Optional)</label>
<select class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t appearance-none" id="service_id" name="service_id">
<option value="">No Service</option>
<?php if ($allServices && count($allServices) > 0): ?>
    <?php foreach ($allServices as $service): ?>
        <option class="bg-surface text-on-surface" value="<?php echo $service['id']; ?>" <?php echo (($selectedService['id'] ?? '') == $service['id'] || ($_POST['service_id'] ?? '') == $service['id']) ? 'selected' : ''; ?>>
            <?php echo e($service['title']); ?> - $<?php echo number_format($service['price'], 2); ?>
        </option>
    <?php endforeach; ?>
<?php endif; ?>
</select>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="program_id">Select Program (Optional)</label>
<select class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t appearance-none" id="program_id" name="program_id">
<option value="">No Program</option>
<?php if ($allPrograms && count($allPrograms) > 0): ?>
    <?php foreach ($allPrograms as $program): ?>
        <option class="bg-surface text-on-surface" value="<?php echo $program['id']; ?>" <?php echo (($selectedProgram['id'] ?? '') == $program['id'] || ($_POST['program_id'] ?? '') == $program['id']) ? 'selected' : ''; ?>>
            <?php echo e($program['title']); ?> - $<?php echo number_format($program['price'], 2); ?>
        </option>
    <?php endforeach; ?>
<?php endif; ?>
</select>
</div>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-on-surface-variant mb-2" for="message">Additional Details (Injuries, Focus Areas)</label>
<textarea class="input-glass font-body-md text-body-md w-full px-4 py-3 rounded-t resize-none" id="message" name="message" placeholder="Let us know anything specific..." rows="3"><?php echo e($_POST['message'] ?? ''); ?></textarea>
</div>
<div class="pt-4 flex items-center justify-between">
<a class="font-label-caps text-label-caps text-on-surface-variant hover:text-white transition-colors flex items-center gap-2" href="javascript:history.back()">
<span class="material-symbols-outlined text-sm">arrow_back</span>
CANCEL
</a>
<button class="bg-primary-container text-black font-label-caps text-label-caps px-8 py-4 rounded font-bold uppercase tracking-widest transition-all neon-glow flex items-center gap-2 group" type="submit">
CONFIRM BOOKING
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</div>
</form>
<?php endif; ?>
</div>
</div>
</main>

<script>
// Handle mutual exclusion between service and program selection
const serviceSelect = document.getElementById('service_id');
const programSelect = document.getElementById('program_id');

if (serviceSelect && programSelect) {
    serviceSelect.addEventListener('change', function() {
        if (this.value !== '') {
            programSelect.value = '';
        }
    });

    programSelect.addEventListener('change', function() {
        if (this.value !== '') {
            serviceSelect.value = '';
        }
    });
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
