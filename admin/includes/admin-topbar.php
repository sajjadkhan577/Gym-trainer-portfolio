<header class="sticky top-0 w-full h-16 z-40 bg-surface/70 dark:bg-surface/70 backdrop-blur-[20px] border-b border-white/10 flex justify-between items-center px-6 md:px-margin-desktop text-primary dark:text-primary transition-all duration-300">
<div class="flex items-center flex-1 gap-4">
    <!-- Hamburger for mobile -->
    <button class="md:hidden p-2 text-on-surface-variant hover:text-primary transition-colors" onclick="document.querySelector('nav').classList.toggle('hidden')">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <div class="relative w-full max-w-md group">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
        <input type="text" placeholder="Search dashboard..." class="w-full bg-surface-container-low border border-white/10 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:border-primary transition-all">
    </div>
</div>
<div class="flex items-center gap-4">
    <a href="messages.php" class="p-2 text-on-surface-variant hover:text-primary transition-colors hover:opacity-80 rounded-full relative">
        <span class="material-symbols-outlined">notifications</span>
        <?php
        try {
            $pdo = get_db_connection();
            $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
            $unread_count = $stmt->fetchColumn();
            if ($unread_count > 0) {
                echo '<span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full"></span>';
            }
        } catch(PDOException $e) {}
        ?>
    </a>
    <div class="flex items-center gap-2">
        <span class="text-sm text-on-surface-variant hidden md:block"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
        <a href="logout.php" class="p-2 text-on-surface-variant hover:text-error transition-colors hover:opacity-80 rounded-full" title="Logout">
            <span class="material-symbols-outlined">logout</span>
        </a>
    </div>
</div>
</header>
