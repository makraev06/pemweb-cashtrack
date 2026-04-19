<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$activePage = $activePage ?? '';
$userName = $_SESSION['user_name'] ?? 'Alexander Sterling';
$userRole = $userRole ?? ($_SESSION['user_role'] ?? (
    $activePage === 'dashboard' ? 'Senior Comptroller' :
    ($activePage === 'assets' ? 'Chief Asset Manager' : 'Treasury Officer')
));

function navClass($key, $activePage)
{
    return $key === $activePage
        ? 'flex items-center gap-3 p-3 text-emerald-700 dark:text-emerald-400 font-bold bg-white dark:bg-slate-800 shadow-sm rounded-lg translate-x-1 transition-transform font-[\'Manrope\'] tracking-tight'
        : 'flex items-center gap-3 p-3 text-slate-500 dark:text-slate-400 hover:text-emerald-600 hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-colors font-[\'Manrope\'] tracking-tight rounded-lg';
}
?>
<aside
    class="fixed left-0 top-0 h-full flex flex-col p-4 bg-slate-50 dark:bg-slate-900 h-screen w-64 border-r-0 shadow-[12px_0px_32px_rgba(25,28,30,0.04)] z-50">
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">account_balance</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-emerald-800 dark:text-emerald-100 font-['Manrope'] tracking-tight">
                Sovereign Ledger
            </h1>
            <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400">
                Institutional Grade
            </p>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a class="<?= navClass('dashboard', $activePage); ?>" href="dashboard.php">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="<?= navClass('transaction', $activePage); ?>" href="transaction.php">
            <span class="material-symbols-outlined">account_balance_wallet</span>
            <span>Transactions</span>
        </a>
        <a class="<?= navClass('assets', $activePage); ?>" href="assets.php">
            <span class="material-symbols-outlined">account_balance</span>
            <span>Assets</span>
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3 px-2">
            <img alt="User Profile" class="w-10 h-10 rounded-full object-cover grayscale"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfYtprLb6iuOCIeRlfYs1UCStaw5irQW5oDr6hg3NjHy9jqtiC0HyYP9IR-FTpBRX7JjYeXGy6446aDn0wLX9TaS0POtkwExKv6q4kT55HvqLQfwdVNyBFsWBpnyBzP0dGnKjTqF_EXw1GS9WugDKf-mx-c5AJ3P2CADaN-AnlkkX5xRMBsjyIb79ZSYkxQR29FvGW258dVRtRshU-kah0HCP7faNJT5weUE8Iwnf8cK3tkU_dETlOO2tqkrHntXfo7f70Ye2FWhE2" />
            <div class="overflow-hidden">
                <p class="text-sm font-semibold truncate text-on-surface">
                    <?= htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
                </p>
                <p class="text-xs text-slate-400 truncate">
                    <?= htmlspecialchars($_SESSION['user_role'] ?? 'Admin'); ?>
                </p>
            </div>
        </div>
    </div>
</aside>