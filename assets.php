<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = $_SESSION['user_id'];

$query_assets = mysqli_query($conn, "
    SELECT * FROM assets
    WHERE user_id = $user_id
    ORDER BY id DESC
");

$query_total = mysqli_query($conn, "
    SELECT SUM(nilai) as total_asset, COUNT(*) as total_item
    FROM assets
    WHERE user_id = $user_id
");

$data_total = mysqli_fetch_assoc($query_total);

$total_asset = $data_total['total_asset'] ?? 0;
$total_item = $data_total['total_item'] ?? 0;

$pageTitle = 'Assets | The Sovereign Ledger';
$activePage = 'assets';
$searchPlaceholder = 'Search assets...';
$topbarTitle = 'Assets';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="space-y-8 px-4 py-6 sm:px-6 lg:ml-72 lg:px-8">
        <section class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <div
                class="xl:col-span-2 relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-8 text-white shadow-lg">
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <span class="text-primary-fixed text-xs font-bold uppercase tracking-widest opacity-80">
                            Portfolio Summary
                        </span>
                        <h1 class="mt-1 font-['Manrope'] text-[2.75rem] font-bold leading-tight">
                            Rp<?php echo number_format($total_asset, 0, ',', '.'); ?>
                        </h1>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-xs opacity-70">24h Change</span>
                            <span class="flex items-center gap-1 text-lg font-bold">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                                +2.45%
                            </span>
                        </div>

                        <div class="h-8 w-px bg-white/20"></div>

                        <div class="flex flex-col">
                            <span class="text-xs opacity-70">Active Positions</span>
                            <span class="text-lg font-bold"><?php echo $total_item; ?> Aset</span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute right-8 top-8 opacity-20">
                    <span class="material-symbols-outlined text-[120px]">account_balance</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container-low p-6 flex flex-col justify-center">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-tighter text-on-surface-variant">
                    Quick Actions
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    <a href="add_asset.php"
                        class="group flex flex-col items-center gap-2 rounded-lg bg-surface-container-lowest p-4 shadow-sm transition-all hover:bg-white">
                        <span class="material-symbols-outlined text-primary transition-transform group-hover:scale-110">
                            add_card
                        </span>
                        <span class="text-xs font-medium">Add Asset</span>
                    </a>

                    <button type="button"
                        class="group flex flex-col items-center gap-2 rounded-lg bg-surface-container-lowest p-4 shadow-sm transition-all hover:bg-white">
                        <span class="material-symbols-outlined text-primary transition-transform group-hover:scale-110">
                            ios_share
                        </span>
                        <span class="text-xs font-medium">Export Data</span>
                    </button>
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-['Manrope'] text-2xl font-bold text-on-surface">Asset Holdings</h2>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-slate-400">Sort by:</span>
                    <select
                        class="cursor-pointer border-none bg-transparent text-xs font-bold text-primary focus:ring-0">
                        <option>Value (High-Low)</option>
                        <option>Category</option>
                        <option>Alphabetical</option>
                    </select>
                </div>
            </div>

            <?php if (mysqli_num_rows($query_assets) > 0): ?>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <?php while ($row = mysqli_fetch_assoc($query_assets)): ?>
                        <div class="rounded-xl bg-surface-container-lowest p-6 shadow">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold">
                                        <?php echo htmlspecialchars($row['nama_aset']); ?>
                                    </h3>
                                    <span class="mt-2 inline-block rounded px-2 py-1 text-xs bg-primary/10 text-primary">
                                        <?php echo htmlspecialchars($row['kategori']); ?>
                                    </span>
                                </div>
                            </div>

                            <p class="mt-4 text-2xl font-bold text-primary">
                                Rp<?php echo number_format($row['nilai'], 0, ',', '.'); ?>
                            </p>

                            <p class="mt-2 text-sm text-slate-400">
                                <?php echo htmlspecialchars($row['deskripsi']); ?>
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                <?php echo htmlspecialchars($row['tanggal_perolehan']); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="flex justify-center border-t border-outline-variant/10 bg-slate-50 px-6 py-4">
                    <button type="button" class="text-xs font-bold text-primary hover:underline">
                        View All Assets (<?php echo $total_item; ?>)
                    </button>
                </div>
            <?php else: ?>
                <div class="rounded-xl bg-surface-container-lowest p-10 text-center shadow">
                    <p class="text-slate-400">Belum ada asset.</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-xl bg-surface-container-lowest p-6 shadow-sm">
                <h3 class="mb-6 font-['Manrope'] text-lg font-bold">Asset Allocation</h3>

                <div class="flex items-center gap-8">
                    <div
                        class="relative flex h-32 w-32 items-center justify-center rounded-full border-[12px] border-primary-container">
                        <div
                            class="absolute inset-0 -rotate-45 rounded-full border-[12px] border-emerald-100 border-r-transparent border-t-transparent">
                        </div>
                        <span class="text-xs font-bold text-primary">Balanced</span>
                    </div>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full bg-primary"></div>
                                <span class="text-xs text-slate-500">Real Estate</span>
                            </div>
                            <span class="text-xs font-bold">45%</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full bg-secondary"></div>
                                <span class="text-xs text-slate-500">Equities</span>
                            </div>
                            <span class="text-xs font-bold">30%</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full bg-emerald-200"></div>
                                <span class="text-xs text-slate-500">Other</span>
                            </div>
                            <span class="text-xs font-bold">25%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-6 shadow-sm">
                <div class="relative z-10">
                    <h3 class="mb-2 font-['Manrope'] text-lg font-bold">Portfolio Insights</h3>
                    <p class="mb-6 text-sm leading-relaxed text-slate-500">
                        Your real estate holdings are performing 12% above benchmark this quarter.
                        Consider rebalancing equity positions.
                    </p>

                    <button type="button"
                        class="flex items-center gap-2 rounded-md bg-primary px-5 py-2 text-xs font-bold text-white transition-opacity hover:opacity-90">
                        View Full Report
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>

                <div class="absolute bottom-0 right-0 translate-x-4 translate-y-4 opacity-5">
                    <span class="material-symbols-outlined text-[160px]">insights</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-100 bg-slate-50 p-6 lg:ml-72 dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <p class="font-['Inter'] text-xs text-slate-400">
                © 2024 The Sovereign Ledger. All rights reserved.
            </p>

            <div class="flex gap-4">
                <a class="font-['Inter'] text-xs text-slate-400 transition-colors hover:text-slate-600 dark:hover:text-slate-200"
                    href="#">
                    Version 2.4.1
                </a>
                <a class="font-['Inter'] text-xs text-slate-400 transition-colors hover:text-slate-600 dark:hover:text-slate-200"
                    href="#">
                    Privacy Policy
                </a>
                <a class="font-['Inter'] text-xs text-slate-400 transition-colors hover:text-slate-600 dark:hover:text-slate-200"
                    href="#">
                    Terms
                </a>
            </div>
        </div>
    </footer>
</body>

</html>