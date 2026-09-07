<nav class="w-64 h-screen sticky top-0 flex-shrink-0 bg-surface-container-low border-r border-white/10 backdrop-blur-[30px] shadow-xl z-50 transition-all duration-300 hidden md:flex flex-col py-base overflow-y-auto">
<div class="px-6 mb-12 mt-8">
<h1 class="font-headline-md text-headline-md font-extrabold text-on-surface tracking-tight">APEX COACHING</h1>
<p class="text-on-surface-variant font-label-caps mt-2 tracking-wider opacity-70">Elite Admin Access</p>
</div>
<div class="flex-1 overflow-y-auto">
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$sidebar_coach = get_db_connection()->query("SELECT profile_image FROM coach_info WHERE id = 1")->fetch();
?>
<ul class="flex flex-col space-y-1">
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'index.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="index.php">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span>Dashboard</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'bookings.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="bookings.php">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span>Bookings</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'messages.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="messages.php">
<span class="material-symbols-outlined" data-icon="chat">chat</span>
<span>Messages</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'services.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="services.php">
<span class="material-symbols-outlined" data-icon="fitness_center">fitness_center</span>
<span>Services</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'programs.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="programs.php">
<span class="material-symbols-outlined" data-icon="list_alt">list_alt</span>
<span>Programs</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'transformations.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="transformations.php">
<span class="material-symbols-outlined" data-icon="auto_awesome">auto_awesome</span>
<span>Transformations</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'gallery.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="gallery.php">
<span class="material-symbols-outlined" data-icon="photo_library">photo_library</span>
<span>Gallery</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'testimonials.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="testimonials.php">
<span class="material-symbols-outlined" data-icon="format_quote">format_quote</span>
<span>Testimonials</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'blog.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="blog.php">
<span class="material-symbols-outlined" data-icon="article">article</span>
<span>Blog</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'profile.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="profile.php">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span>Profile</span>
</a>
</li>
<li>
<a class="flex items-center gap-base px-4 py-3 <?= $current_page == 'settings.php' ? 'bg-primary/10 text-primary border-r-4 border-primary transform scale-95' : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5' ?> transition-all duration-300" href="settings.php">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span>Settings</span>
</a>
</li></ul>
</div>
<div class="p-4 mt-auto">
<div class="flex items-center gap-3 glass-panel rounded-lg p-3">
<div class="w-10 h-10 rounded-full bg-surface-variant overflow-hidden border border-white/10">
<?php if (!empty($sidebar_coach['profile_image'])): ?><img alt="Coach Profile Picture" class="w-full h-full object-cover" src="<?= htmlspecialchars(admin_media_url($sidebar_coach['profile_image'])) ?>"><?php else: ?><span class="material-symbols-outlined text-on-surface-variant">person</span><?php endif; ?>
</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Admin</p>
<form method="POST" action="logout.php">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
<button type="submit" class="text-xs text-error hover:underline">Logout</button>
</form>
</div>
</div>
</div>
</nav>
