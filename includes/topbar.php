<?php
$searchPlaceholder = $searchPlaceholder ?? 'Search...';
$showLogoutButton = $showLogoutButton ?? false;
?>
<header
    class="sticky top-0 z-40 flex justify-between items-center px-8 h-16 ml-64 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800">
    <div class="flex items-center flex-1 max-w-xl">
        <div class="relative w-full focus-within:ring-2 focus-within:ring-emerald-500/20 rounded-lg">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input
                id="searchTransaksi"
                class="w-full bg-surface-container-low border-none rounded-lg pl-10 py-2 text-sm focus:ring-0 placeholder:text-slate-400"
                placeholder="<?= htmlspecialchars($searchPlaceholder); ?>" type="text" />
        </div>
    </div>

    <div class="flex items-center gap-4 ml-6">
        <a href="notifications.php"
            class="p-2 text-slate-500 hover:text-emerald-500 transition-colors relative inline-block">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
        </a>

        <?php if ($showLogoutButton): ?>
            <a href="process/logout.php"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50 hover:text-emerald-600">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Logout
            </a>
        <?php else: ?>
            <div class="relative group">
                <button class="p-2 text-slate-500 hover:text-emerald-500 transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </button>

                <div
                    class="absolute right-0 mt-3 w-44 bg-white border border-slate-200 rounded-xl shadow-lg opacity-0 invisible translate-y-2 scale-95 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:scale-100 transition-all duration-200 origin-top-right">
                    <a href="#" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Profile
                    </a>

                    <div class="border-t border-slate-200 my-1"></div>

                    <a href="process/logout.php"
                        class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-600">
                        Logout
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>