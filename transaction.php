<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = (int) $_SESSION['user_id'];

$filter_jenis = $_GET['jenis'] ?? '';
$filter_start = $_GET['start_date'] ?? '';
$filter_end = $_GET['end_date'] ?? '';

if (!in_array($filter_jenis, ['', 'income', 'expense'], true)) {
    $filter_jenis = '';
}

function isValidFilterDate($date)
{
    $parsedDate = DateTime::createFromFormat('Y-m-d', $date);
    return $parsedDate && $parsedDate->format('Y-m-d') === $date;
}

if (!empty($filter_start) && !isValidFilterDate($filter_start)) {
    $filter_start = '';
}

if (!empty($filter_end) && !isValidFilterDate($filter_end)) {
    $filter_end = '';
}

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
if (!in_array($limit, [10, 25, 50])) {
    $limit = 10;
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$conditions = ["user_id = ?"];
$types = "i";
$params = [$user_id];

if (!empty($filter_jenis)) {
    $conditions[] = "jenis = ?";
    $types .= "s";
    $params[] = $filter_jenis;
}

if (!empty($filter_start)) {
    $conditions[] = "tanggal >= ?";
    $types .= "s";
    $params[] = $filter_start;
}

if (!empty($filter_end)) {
    $conditions[] = "tanggal <= ?";
    $types .= "s";
    $params[] = $filter_end;
}

// Calendar overlay data
$calendarMonth = isset($_GET['calendar_month']) ? (int) $_GET['calendar_month'] : date('n');
$calendarYear = isset($_GET['calendar_year']) ? (int) $_GET['calendar_year'] : date('Y');
if ($calendarMonth < 1 || $calendarMonth > 12) {
    $calendarMonth = date('n');
}
if ($calendarYear < 2000 || $calendarYear > 2100) {
    $calendarYear = date('Y');
}
$calendarFirstDay = date('w', strtotime("$calendarYear-$calendarMonth-01"));
$calendarDays = date('t', strtotime("$calendarYear-$calendarMonth-01"));
$calendarPrevMonth = $calendarMonth - 1;
$calendarPrevYear = $calendarYear;
if ($calendarPrevMonth < 1) {
    $calendarPrevMonth = 12;
    $calendarPrevYear--;
}
$calendarNextMonth = $calendarMonth + 1;
$calendarNextYear = $calendarYear;
if ($calendarNextMonth > 12) {
    $calendarNextMonth = 1;
    $calendarNextYear++;
}

$calendarQuery = "
    SELECT
        DATE(tanggal) as date,
        SUM(CASE WHEN jenis = 'income' THEN jumlah ELSE 0 END) as total_income,
        SUM(CASE WHEN jenis = 'expense' THEN jumlah ELSE 0 END) as total_expense
    FROM transactions
    WHERE user_id = ?
    AND MONTH(tanggal) = ?
    AND YEAR(tanggal) = ?
    GROUP BY DATE(tanggal)
";
$calendarStmt = mysqli_prepare($conn, $calendarQuery);
mysqli_stmt_bind_param($calendarStmt, 'iii', $user_id, $calendarMonth, $calendarYear);
mysqli_stmt_execute($calendarStmt);
$calendarResult = mysqli_stmt_get_result($calendarStmt);
$calendarData = [];
while ($row = mysqli_fetch_assoc($calendarResult)) {
    $calendarData[$row['date']] = [
        'income' => $row['total_income'],
        'expense' => $row['total_expense'],
    ];
}

$calendarBaseParams = [
    'calendar_open' => 1,
    'jenis' => $filter_jenis,
    'start_date' => $filter_start,
    'end_date' => $filter_end,
];
$calendarPrevUrl = 'transaction.php?' . http_build_query(array_merge($calendarBaseParams, [
    'calendar_month' => $calendarPrevMonth,
    'calendar_year' => $calendarPrevYear,
]));
$calendarNextUrl = 'transaction.php?' . http_build_query(array_merge($calendarBaseParams, [
    'calendar_month' => $calendarNextMonth,
    'calendar_year' => $calendarNextYear,
]));

/* QUERY COUNT */
$where_sql = implode(" AND ", $conditions);
$count_sql = "SELECT COUNT(*) as total FROM transactions WHERE $where_sql";
$count_stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_bind_param($count_stmt, $types, ...$params);
mysqli_stmt_execute($count_stmt);
$count_query = mysqli_stmt_get_result($count_stmt);
$count_data = mysqli_fetch_assoc($count_query);
$total_data = $count_data['total'] ?? 0;
$total_pages = ceil($total_data / $limit);
$start_data = ($offset + 1);
$show_end = min($offset + $limit, $total_data);

/* QUERY DATA */
$sql = "SELECT * FROM transactions WHERE $where_sql ORDER BY tanggal DESC, id DESC LIMIT ? OFFSET ?";
$data_types = $types . "ii";
$data_params = array_merge($params, [$limit, $offset]);
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $data_types, ...$data_params);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

$activePage = 'transaction';
$searchPlaceholder = 'Cari transaksi...';
?>

<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 p-8 min-h-[calc(100vh-64px-60px)] animate-fade-up">
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
                <button onclick="window.location.href='add_transaction.php'"
                    class="flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-sm btn-hover">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Transaksi
                </button>
            </div>
        </div>
        <!-- Filter & Metrics Section -->
        <div class="grid grid-cols-12 gap-6 mb-8">
            <div
                class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
                    <h3 class="font-['Manrope'] font-semibold text-on-surface">Filter Berdasarkan Tanggal</h3>
                    <button type="button" onclick="openCalendarOverlay()"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-surface px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100 transition-all btn-hover">
                        <span class="material-symbols-outlined text-base">calendar_month</span>
                        Kalender
                    </button>
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

                <div id="calendarOverlay"
                    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm transition-opacity duration-200">
                    <div id="calendarContent"
                        class="w-full max-w-5xl overflow-hidden rounded-[32px] bg-white text-on-surface shadow-2xl dark:bg-slate-950 animate-fade-up">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-500">Kalender Transaksi</p>
                                <h3 class="text-2xl font-semibold">
                                    <?php echo date('F Y', strtotime("$calendarYear-$calendarMonth-01")); ?>
                                </h3>
                            </div>
                            <button type="button" onclick="closeCalendarOverlay()"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo $calendarPrevUrl; ?>"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                                    <span class="material-symbols-outlined">chevron_left</span>
                                </a>
                                <a href="<?php echo $calendarNextUrl; ?>"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </a>
                            </div>
                            <div class="text-sm text-slate-500">Klik tanggal untuk melihat transaksi harian.</div>
                        </div>
                        <div class="p-6">
                            <div
                                class="grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-widest text-slate-500 mb-3">
                                <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day): ?>
                                    <div><?php echo $day; ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="grid grid-cols-7 gap-2">
                                <?php for ($i = 0; $i < $calendarFirstDay; $i++): ?>
                                    <div class="h-24 rounded-3xl bg-surface-container-low"></div>
                                <?php endfor; ?>

                                <?php for ($day = 1; $day <= $calendarDays; $day++):
                                    $date = sprintf('%04d-%02d-%02d', $calendarYear, $calendarMonth, $day);
                                    $data = $calendarData[$date] ?? ['income' => 0, 'expense' => 0];
                                    $hasData = $data['income'] > 0 || $data['expense'] > 0;
                                    $isToday = $date === date('Y-m-d');
                                    ?>
                                    <button type="button"
                                        onclick="window.location.href='transaction.php?start_date=<?php echo $date; ?>&end_date=<?php echo $date; ?>'"
                                        class="h-24 rounded-3xl border border-slate-200 bg-surface-container-low p-3 text-left transition-all duration-200 hover:-translate-y-0.5 hover:bg-surface-container-lowest <?php echo $isToday ? 'ring-2 ring-primary' : ''; ?>">
                                        <div
                                            class="flex items-center justify-between text-sm font-semibold text-on-surface mb-2">
                                            <span><?php echo $day; ?></span>
                                        </div>
                                        <?php if ($data['income'] > 0): ?>
                                            <div class="text-xs font-semibold text-emerald-600">
                                                +Rp<?php echo number_format($data['income'], 0, ',', '.'); ?></div>
                                        <?php endif; ?>
                                        <?php if ($data['expense'] > 0): ?>
                                            <div class="text-xs font-semibold text-red-600">
                                                -Rp<?php echo number_format($data['expense'], 0, ',', '.'); ?></div>
                                        <?php endif; ?>
                                        <?php if (!$hasData): ?>
                                            <div class="text-xs text-slate-400 mt-2">Tidak ada transaksi</div>
                                        <?php endif; ?>
                                    </button>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center gap-4 border-t border-slate-200 bg-surface-container-lowest px-6 py-4 text-sm text-slate-500 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                                Pemasukan
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-600"></span>
                                Pengeluaran
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full border border-primary"></span>
                                Hari ini
                            </div>
                        </div>
                    </div>
                </div>
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
                            <th class="px-6 py-4">Category</th>
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
                                    data-search="<?= strtolower(($row['keterangan'] ?? '') . ' ' . ($row['jenis'] ?? '') . ' ' . ($row['category'] ?? '')) ?>">
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

                                    <!-- CATEGORY -->
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded bg-surface-container-low text-on-surface-variant text-[10px] font-bold uppercase">
                                            <?php echo htmlspecialchars($row['category'] ?? 'Lainnya'); ?>
                                        </span>
                                    </td>

                                    <!-- DESCRIPTION -->
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-on-surface">
                                            <?php echo htmlspecialchars($row['keterangan']); ?>
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
                                <td colspan="7" class="text-center py-6 text-slate-400">
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
        const calendarOverlay = document.getElementById('calendarOverlay');

        function openCalendarOverlay() {
            if (calendarOverlay) {
                calendarOverlay.classList.remove('hidden');
            }
        }

        function closeCalendarOverlay() {
            if (calendarOverlay) {
                calendarOverlay.classList.add('hidden');
            }
        }

        if (calendarOverlay) {
            calendarOverlay.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeCalendarOverlay();
                }
            });
        }

        if (searchInput) {
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
        }

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get('calendar_open') === '1') {
                openCalendarOverlay();
            }
        });
    </script>
</body>

</html>