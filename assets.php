<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = (int) $_SESSION['user_id'];

$stmt_assets = mysqli_prepare($conn, "
    SELECT * FROM assets
    WHERE user_id = ?
    ORDER BY id DESC
");
mysqli_stmt_bind_param($stmt_assets, "i", $user_id);
mysqli_stmt_execute($stmt_assets);
$query_assets = mysqli_stmt_get_result($stmt_assets);

$stmt_total = mysqli_prepare($conn, "
    SELECT SUM(nilai) as total_asset, COUNT(*) as total_item
    FROM assets
    WHERE user_id = ?
");
mysqli_stmt_bind_param($stmt_total, "i", $user_id);
mysqli_stmt_execute($stmt_total);
$query_total = mysqli_stmt_get_result($stmt_total);

$data_total = mysqli_fetch_assoc($query_total);

$total_asset = $data_total['total_asset'] ?? 0;
$total_item = $data_total['total_item'] ?? 0;

$activePage = 'assets';
$searchPlaceholder = 'Cari Assets...';
$topbarTitle = 'Assets';
$hideSearch = true;
?>
<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 space-y-8 px-4 py-6 sm:px-6 lg:px-8 animate-fade-up">
        <section class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <div
                class="xl:col-span-2 relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-8 text-white shadow-lg animate-fade-up">
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <span class="text-primary-fixed text-xs font-bold uppercase tracking-widest opacity-80">
                            Ringkasan Portofolio
                        </span>
                        <h1 class="mt-1 font-['Manrope'] text-[2.75rem] font-bold leading-tight">
                            Rp
                            <?php echo number_format($total_asset, 0, ',', '.'); ?>
                        </h1>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-xs opacity-70">Perubahan 24j</span>
                            <span class="flex items-center gap-1 text-lg font-bold">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                                +2.45%
                            </span>
                        </div>

                        <div class="h-8 w-px bg-white/20"></div>

                        <div class="flex flex-col">
                            <span class="text-xs opacity-70">Aset Aktif</span>
                            <span class="text-lg font-bold">
                                <?php echo $total_item; ?> Aset
                            </span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute right-8 top-8 opacity-20">
                    <span class="material-symbols-outlined text-[120px]">account_balance</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container-low p-6 flex flex-col justify-center animate-fade-up-delay-1">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-tighter text-on-surface-variant">
                    Aksi Cepat
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    <a href="add_asset.php"
                        class="group flex flex-col items-center gap-2 rounded-lg bg-surface-container-lowest p-4 shadow-sm transition-all hover:bg-white">
                        <span
                            class="material-symbols-outlined text-primary transition-transform group-hover:scale-110 icon-hover">
                            add_card
                        </span>
                        <span class="text-xs font-medium">Tambah Aset</span>
                    </a>

                    <button type="button"
                        class="group flex flex-col items-center gap-2 rounded-lg bg-surface-container-lowest p-4 shadow-sm transition-all hover:bg-white">
                        <span
                            class="material-symbols-outlined text-primary transition-transform group-hover:scale-110 icon-hover">
                            ios_share
                        </span>
                        <span class="text-xs font-medium">Eksport Data</span>
                    </button>
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-['Manrope'] text-2xl font-bold text-on-surface">Kepemilikan Aset</h2>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-slate-400">Urutkan:</span>
                        <select
                            class="cursor-pointer border-none bg-transparent text-xs font-bold text-primary focus:ring-0">
                            <option>Nilai (Tinggi-Rendah)</option>
                            <option>Category</option>
                            <option>Alphabetical</option>
                        </select>
                    </div>
                </div>

                <div class="relative w-full max-w-xl focus-within:ring-2 focus-within:ring-emerald-500/20 rounded-lg">
                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <input id="searchAset"
                        class="w-full bg-surface-container-lowest border-none rounded-lg pl-10 py-2 text-sm shadow-sm focus:ring-0 placeholder:text-slate-400 input-focus"
                        placeholder="Cari aset yang dimiliki..." type="text" />
                </div>
            </div>

            <?php if (mysqli_num_rows($query_assets) > 0): ?>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <?php while ($row = mysqli_fetch_assoc($query_assets)): ?>
                        <div class="asset-item rounded-xl bg-surface-container-lowest p-6 shadow"
                            data-search="<?php echo htmlspecialchars(strtolower(($row['nama_aset'] ?? '') . ' ' . ($row['kategori'] ?? '') . ' ' . ($row['deskripsi'] ?? '') . ' ' . ($row['tanggal_perolehan'] ?? '') . ' ' . ($row['nilai'] ?? ''))); ?>">
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
                                Rp
                                <?php echo number_format($row['nilai'], 0, ',', '.'); ?>
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

                <div id="assetSearchEmpty"
                    class="hidden rounded-xl bg-surface-container-lowest p-10 text-center shadow">
                    <p class="text-slate-400">Aset tidak ditemukan.</p>
                </div>

                <div class="flex justify-center border-t border-outline-variant/10 bg-slate-50 px-6 py-4">
                    <button type="button" class="text-xs font-bold text-primary hover:underline">
                        Lihat Semua Aset (
                        <?php echo $total_item; ?>)
                    </button>
                </div>
            <?php else: ?>
                <div class="rounded-xl bg-surface-container-lowest p-10 text-center shadow">
                    <p class="text-slate-400">Belum ada aset.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="ml-64 border-t border-slate-100 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <p class="font-['Inter'] text-xs text-slate-400">
                © 2026 The CashTrack. All rights reserved.
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
    <script>
        const assetSearchInput = document.getElementById('searchAset');
        const assetItems = document.querySelectorAll('.asset-item');
        const assetSearchEmpty = document.getElementById('assetSearchEmpty');

        if (assetSearchInput) {
            assetSearchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase();
                let visibleCount = 0;

                assetItems.forEach(item => {
                    const text = item.getAttribute('data-search') || '';
                    const isVisible = text.includes(keyword);

                    item.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        visibleCount++;
                    }
                });

                if (assetSearchEmpty) {
                    assetSearchEmpty.classList.toggle('hidden', visibleCount > 0);
                }
            });
        }
    </script>
</body>

</html>
