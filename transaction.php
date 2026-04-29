<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = $_SESSION['user_id'];

$filter_jenis = $_GET['jenis'] ?? '';
$filter_start = $_GET['start_date'] ?? '';
$filter_end = $_GET['end_date'] ?? '';

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
if (!in_array($limit, [10, 25, 50])) {
    $limit = 10;
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

/* QUERY COUNT */
$count_sql = "SELECT COUNT(*) as total FROM transactions WHERE user_id = $user_id";

if (!empty($filter_jenis)) {
    $count_sql .= " AND jenis = '$filter_jenis'";
}

if (!empty($filter_start)) {
    $count_sql .= " AND tanggal >= '$filter_start'";
}

if (!empty($filter_end)) {
    $count_sql .= " AND tanggal <= '$filter_end'";
}

$count_query = mysqli_query($conn, $count_sql);
$count_data = mysqli_fetch_assoc($count_query);
$total_data = $count_data['total'] ?? 0;
$total_pages = ceil($total_data / $limit);
$start_data = ($offset + 1);
$show_end = min($offset + $limit, $total_data);

/* QUERY DATA */
$sql = "SELECT * FROM transactions WHERE user_id = $user_id";

if (!empty($filter_jenis)) {
    $sql .= " AND jenis = '$filter_jenis'";
}

if (!empty($filter_start)) {
    $sql .= " AND tanggal >= '$filter_start'";
}

if (!empty($filter_end)) {
    $sql .= " AND tanggal <= '$filter_end'";
}

$sql .= " ORDER BY tanggal DESC, id DESC LIMIT $limit OFFSET $offset";

$query = mysqli_query($conn, $sql);

$activePage = 'transaction';
$searchPlaceholder = 'Cari transaksi...';
?>

<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 p-8 min-h-[calc(100vh-64px-60px)]">
        <!-- Header Actions Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-bold text-outline uppercase tracking-widest mb-2">
                    <span class="hover:text-primary transition-colors cursor-pointer">Buku Besar</span>
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-primary">Histori Transaksi</span>
                </nav>
                <h2 class="text-[2.75rem] font-bold text-on-surface font-['Manrope'] leading-tight tracking-tight">
                    Histori Transaksi</h2>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">add</span>
                    <a href="add_transaction.php">Tambah Transaksi</a>
                </button>
            </div>
        </div>
        <!-- Filter & Metrics Section -->
        <div class="grid grid-cols-12 gap-6 mb-8">
            <div
                class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-['Manrope'] font-semibold text-on-surface">Filter Berdasarkan Tanggal</h3>
                </div>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="<?php echo htmlspecialchars($filter_start); ?>"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="<?php echo htmlspecialchars($filter_end); ?>"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Jenis
                        </label>
                        <select name="jenis"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Semua</option>
                            <option value="income" <?php echo $filter_jenis === 'income' ? 'selected' : ''; ?>>Income
                            </option>
                            <option value="expense" <?php echo $filter_jenis === 'expense' ? 'selected' : ''; ?>>Expense
                            </option>
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-sm">Terapkan
                            Filter
                        </button>
                    </div>

                    <button type="submit" formaction="process/export_transaction.php"
                        class="mt-5 bg-slate-900 text-white py-3 px-8 rounded-lg font-semibold text-sm hover:bg-slate-800 transition-all inline-flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        Download Laporan
                    </button>

                </form>
            </div>
            <div
                class="col-span-12 lg:col-span-4 bg-primary text-white rounded-xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-primary-fixed uppercase tracking-widest opacity-80 mb-1">total
                        Volume Periode</p>
                    <h4 class="text-3xl font-bold font-['Manrope'] tracking-tight">Rp282.901.120</h4>
                </div>
                <div class="flex items-end justify-between relative z-10">
                    <div
                        class="flex items-center gap-1 text-primary-fixed text-xs font-semibold bg-white/10 px-2 py-1 rounded">
                        <span class="material-symbols-outlined text-sm">trending_up</span>
                        +12.4%
                    </div>
                    <span
                        class="material-symbols-outlined text-6xl opacity-10 absolute -right-2 -bottom-2">bar_chart</span>
                </div>
                <!-- Glass texture effect -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-3xl rounded-full -mr-16 -mt-16"></div>
            </div>
        </div>
        <!-- Transactions Table Container -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="p-6 flex items-center justify-between bg-surface-container-low/50">
                <h3 class="font-['Manrope'] font-semibold text-on-surface">Catatan Transaksi Terbaru</h3>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-outline font-medium">
                        Menampilkan <?php echo $start_data; ?>-<?php echo $show_end; ?> dari <?php echo $total_data; ?>
                    </span>
                    <div class="flex items-center gap-1">

                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&jenis=<?php echo $filter_jenis; ?>&start_date=<?php echo $filter_start; ?>&end_date=<?php echo $filter_end; ?>"
                                class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition">

                                <span class="material-symbols-outlined">chevron_left</span>

                            </a>
                        <?php endif; ?>


                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&jenis=<?php echo $filter_jenis; ?>&start_date=<?php echo $filter_start; ?>&end_date=<?php echo $filter_end; ?>"
                                class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition">

                                <span class="material-symbols-outlined">chevron_right</span>

                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-surface-container-low">
                        <tr class="text-[10px] font-bold text-outline uppercase tracking-widest">
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Account</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <?php if (mysqli_num_rows($query) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($query)): ?>

                                <tr class="group hover:bg-surface-container-highest transition-colors cursor-pointer relative transaksi-item"
                                    data-search="<?= strtolower($row['keterangan'] . ' ' . $row['jenis']) ?>">
                                    <!-- DATE -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-on-surface">
                                                <?php echo date('M d, Y', strtotime($row['tanggal'])); ?>
                                            </span>
                                        </div>
                                        <div class="hidden group-hover:block absolute left-0 top-0 bottom-0 w-1 bg-primary">
                                        </div>
                                    </td>

                                    <!-- TYPE -->
                                    <td class="px-6 py-4">
                                        <?php if ($row['jenis'] == 'income'): ?>
                                            <span
                                                class="px-2 py-1 rounded bg-secondary-container text-on-secondary-container text-[10px] font-bold uppercase">
                                                Dana Masuk
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="px-2 py-1 rounded bg-error-container text-on-error-container text-[10px] font-bold uppercase">
                                                Dana Keluar
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- DESCRIPTION -->
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-on-surface">
                                            <?php echo $row['keterangan']; ?>
                                        </p>
                                    </td>

                                    <!-- ACCOUNT (sementara dummy dulu) -->
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-semibold text-secondary uppercase">
                                            -
                                        </span>
                                    </td>

                                    <!-- AMOUNT -->
                                    <td class="px-6 py-4 text-right">
                                        <?php if ($row['jenis'] == 'income'): ?>
                                            <span class="text-sm font-bold text-primary">
                                                +Rp<?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-sm font-bold text-tertiary">
                                                -Rp<?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- ACTION -->
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-outline hover:text-primary">
                                            <span class="material-symbols-outlined text-xl">more_vert</span>
                                        </button>
                                    </td>

                                </tr>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div
                class="p-6 bg-surface-container-low/30 border-t border-outline-variant/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($filter_start); ?>">
                    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($filter_end); ?>">
                    <input type="hidden" name="jenis" value="<?php echo htmlspecialchars($filter_jenis); ?>">

                    <span class="text-xs text-outline">Items per page:</span>
                    <select name="limit" onchange="this.form.submit()"
                        class="bg-transparent border-none text-xs font-bold focus:ring-0 cursor-pointer">
                        <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                    </select>
                </form>
                <div class="flex items-center gap-1">
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-outline-variant/20 text-xs font-bold text-primary shadow-sm">1</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high text-xs font-bold text-outline">2</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high text-xs font-bold text-outline">3</button>
                    <span class="px-2 text-outline">...</span>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high text-xs font-bold text-outline">245</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Shell -->
    <footer
        class="ml-64 p-6 flex justify-between items-center bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-6">
            <span class="font-['Inter'] text-xs text-slate-400">© 2024 The CashTrack. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <a class="font-['Inter'] text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                    href="#">Privacy Policy</a>
                <a class="font-['Inter'] text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                    href="#">Terms</a>
            </div>
        </div>
        <div>
            <span
                class="font-['Inter'] text-xs text-slate-400 uppercase tracking-widest font-semibold opacity-50">Version
                2.4.1</span>
        </div>
    </footer>
    <script>
        const searchInput = document.getElementById('searchTransaksi');
        const items = document.querySelectorAll('.transaksi-item');

        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();

            items.forEach(item => {
                const text = item.getAttribute('data-search');

                if (text.includes(keyword)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>