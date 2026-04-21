<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = $_SESSION['user_id'];

// total income
$query_income = mysqli_query($conn, "
    SELECT SUM(jumlah) as total_income 
    FROM transactions 
    WHERE user_id = $user_id AND jenis = 'income'
");
$data_income = mysqli_fetch_assoc($query_income);
$total_income = $data_income['total_income'] ?? 0;

// total expense
$query_expense = mysqli_query($conn, "
    SELECT SUM(jumlah) as total_expense 
    FROM transactions 
    WHERE user_id = $user_id AND jenis = 'expense'
");
$data_expense = mysqli_fetch_assoc($query_expense);
$total_expense = $data_expense['total_expense'] ?? 0;

// total balance
$total_balance = $total_income - $total_expense;


// income hari ini
$query_income_today = mysqli_query($conn, "
    SELECT SUM(jumlah) as income_today 
    FROM transactions 
    WHERE user_id = $user_id 
    AND jenis = 'income' 
    AND DATE(tanggal) = CURDATE()
");
$data_income_today = mysqli_fetch_assoc($query_income_today);
$income_today = $data_income_today['income_today'] ?? 0;

// expense hari ini
$query_expense_today = mysqli_query($conn, "
    SELECT SUM(jumlah) as expense_today 
    FROM transactions 
    WHERE user_id = $user_id 
    AND jenis = 'expense' 
    AND DATE(tanggal) = CURDATE()
");
$data_expense_today = mysqli_fetch_assoc($query_expense_today);
$expense_today = $data_expense_today['expense_today'] ?? 0;

// income bulan ini
$query_income_month = mysqli_query($conn, "
    SELECT SUM(jumlah) as income_month
    FROM transactions
    WHERE user_id = $user_id
    AND jenis = 'income'
    AND MONTH(tanggal) = MONTH(CURDATE())
    AND YEAR(tanggal) = YEAR(CURDATE())
");
$data_income_month = mysqli_fetch_assoc($query_income_month);
$income_month = $data_income_month['income_month'] ?? 0;

// expense bulan ini
$query_expense_month = mysqli_query($conn, "
    SELECT SUM(jumlah) as expense_month
    FROM transactions
    WHERE user_id = $user_id
    AND jenis = 'expense'
    AND MONTH(tanggal) = MONTH(CURDATE())
    AND YEAR(tanggal) = YEAR(CURDATE())
");
$data_expense_month = mysqli_fetch_assoc($query_expense_month);
$expense_month = $data_expense_month['expense_month'] ?? 0;

// cash flow bulan ini
$cashflow_month = $income_month - $expense_month;

// income bulan lalu
$query_income_last_month = mysqli_query($conn, "
    SELECT SUM(jumlah) as income_last_month
    FROM transactions
    WHERE user_id = $user_id
    AND jenis = 'income'
    AND MONTH(tanggal) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
    AND YEAR(tanggal) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
");
$data_income_last_month = mysqli_fetch_assoc($query_income_last_month);
$income_last_month = $data_income_last_month['income_last_month'] ?? 0;

// expense bulan lalu
$query_expense_last_month = mysqli_query($conn, "
    SELECT SUM(jumlah) as expense_last_month
    FROM transactions
    WHERE user_id = $user_id
    AND jenis = 'expense'
    AND MONTH(tanggal) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
    AND YEAR(tanggal) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
");
$data_expense_last_month = mysqli_fetch_assoc($query_expense_last_month);
$expense_last_month = $data_expense_last_month['expense_last_month'] ?? 0;

// persentase income vs bulan lalu
if ($income_last_month > 0) {
    $income_change = (($income_month - $income_last_month) / $income_last_month) * 100;
} else {
    $income_change = $income_month > 0 ? 100 : 0;
}

// persentase expense vs bulan lalu
if ($expense_last_month > 0) {
    $expense_change = (($expense_month - $expense_last_month) / $expense_last_month) * 100;
} else {
    $expense_change = $expense_month > 0 ? 100 : 0;
}

// total aset
$query_total_assets = mysqli_query($conn, "
    SELECT SUM(nilai) as total_assets
    FROM assets
    WHERE user_id = $user_id
");
$data_total_assets = mysqli_fetch_assoc($query_total_assets);
$total_assets = $data_total_assets['total_assets'] ?? 0;

// ambil data income & expense per bulan (12 bulan terakhir)
$query_chart = mysqli_query($conn, "
    SELECT 
        MONTH(tanggal) as bulan,
        YEAR(tanggal) as tahun,
        SUM(CASE WHEN jenis = 'income' THEN jumlah ELSE 0 END) as total_income,
        SUM(CASE WHEN jenis = 'expense' THEN jumlah ELSE 0 END) as total_expense
    FROM transactions
    WHERE user_id = $user_id
    AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY tahun, bulan
    ORDER BY tahun ASC, bulan ASC
");

// siapkan array untuk chart
$labels = [];
$data_income = [];
$data_expense = [];

while ($row = mysqli_fetch_assoc($query_chart)) {
    $labels[] = date('M', mktime(0, 0, 0, $row['bulan'], 1)); // Jan, Feb, dst
    $data_income[] = $row['total_income'];
    $data_expense[] = $row['total_expense'];
}

?>

<?php
$activePage = 'dashboard';
$searchPlaceholder = 'Cari Buku Besar Aset...';

?>
<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 p-8 min-h-screen">
        <!-- Header Section -->
        <div class="mb-10">
            <h2 class="text-3xl font-bold tracking-tight text-on-surface mb-1">
                Gambaran Keuangan
            </h2>
            <p class="text-on-surface-variant font-body">
                Selamat Datang, <?php echo $_SESSION['name']; ?>.
            </p>
        </div>
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <!-- Total Balance -->
            <div
                class="bg-surface-container-lowest rounded-[24px] p-6 border border-outline-variant/20 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <span class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Overview</span>
                        <h4 class="text-base font-bold text-on-surface mt-2">Total Balance</h4>
                    </div>
                    <div
                        class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-bold font-headline tracking-tight">
                        Rp<?php echo number_format($total_balance, 0, ',', '.'); ?>
                    </span>
                </div>

                <div class="mt-5">
                    <span class="text-xs text-slate-400 font-medium">Saldo bersih dari seluruh transaksi</span>
                </div>
            </div>

            <!-- Income Bulan Ini -->
            <div
                class="bg-green-50 rounded-[24px] p-6 border border-green-200 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <span class="text-green-600 uppercase tracking-widest text-[10px] font-bold">Bulan Ini</span>
                        <h4 class="text-base font-bold text-on-surface mt-2">Income</h4>
                    </div>
                    <div
                        class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">south_west</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-bold font-headline tracking-tight">
                        Rp<?php echo number_format($income_month, 0, ',', '.'); ?>
                    </span>
                </div>

                <div class="mt-5 flex items-center gap-2">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold <?php echo $income_change >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                        <?php echo $income_change >= 0 ? '↑' : '↓'; ?>
                        <?php echo number_format(abs($income_change), 1); ?>%
                    </span>
                    <span class="text-xs text-slate-400 font-medium">vs last month</span>
                </div>
            </div>

            <!-- Expense Bulan Ini -->
            <div
                class="bg-red-50 rounded-[24px] p-6 border border-red-200 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <span class="text-red-600 uppercase tracking-widest text-[10px] font-bold">Bulan Ini</span>
                        <h4 class="text-base font-bold text-on-surface mt-2">Expense</h4>
                    </div>
                    <div
                        class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">north_east</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-bold font-headline tracking-tight">
                        Rp<?php echo number_format($expense_month, 0, ',', '.'); ?>
                    </span>
                </div>

                <div class="mt-5 flex items-center gap-2">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold <?php echo $expense_change <= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                        <?php echo $expense_change <= 0 ? '↓' : '↑'; ?>
                        <?php echo number_format(abs($expense_change), 1); ?>%
                    </span>
                    <span class="text-xs text-slate-400 font-medium">vs last month</span>
                </div>
            </div>

            <!-- Total Assets -->
            <div
                class="bg-surface-container-lowest rounded-[24px] p-6 border border-outline-variant/20 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <span class="text-secondary uppercase tracking-widest text-[10px] font-bold">Portfolio</span>
                        <h4 class="text-base font-bold text-on-surface mt-2">Total Assets</h4>
                    </div>
                    <div
                        class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">savings</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-bold font-headline tracking-tight">
                        Rp<?php echo number_format($total_assets, 0, ',', '.'); ?>
                    </span>
                </div>

                <div class="mt-5">
                    <span class="text-xs text-slate-400 font-medium">Total nilai aset yang tercatat</span>
                </div>
            </div>
        </div>
        <!-- Main Chart & Widgets Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Income vs Expense Chart -->
            <div
                class="lg:col-span-8 bg-surface-container-lowest rounded-xl p-8 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] h-full">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h4 class="text-xl font-bold font-headline text-on-surface">
                            Pemasukan vs Pengeluaran
                        </h4>
                        <p class="text-xs text-slate-400">
                            Analisis Kinerja Operasional Bulanan
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-xs font-semibold text-slate-500">Pemasukan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-tertiary"></span>
                            <span class="text-xs font-semibold text-slate-500">Pengeluaran</span>
                        </div>
                    </div>
                </div>
                <!-- Fake Area Chart Visual -->
                <div
                    class="relative w-full h-[320px] bg-surface-container-low/30 rounded-lg overflow-hidden border border-outline-variant/10">
                    <canvas id="financeChart" height="100"></canvas>
                </div>
            </div>
            <!-- Side Widgets -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Recent Alerts -->
                <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)]">
                    <h4 class="text-sm font-bold font-headline mb-4 uppercase tracking-widest text-slate-400">
                        Peringatan Penting
                    </h4>
                    <div class="space-y-4">
                        <div class="flex gap-4 p-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                            <div
                                class="w-8 h-8 rounded-full bg-error-container text-error flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]"
                                    style="font-variation-settings: &quot;FILL&quot; 1">warning</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface leading-tight">
                                    Transaksi Penarikan Besar Terdeteksi
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Akun Acme corp ditandai karena aktivitas mencurigakan sebesar Rp2.000.000
                                </p>
                                <p class="text-[10px] text-slate-400 mt-2 font-semibold">
                                    2 MENIT LALU
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                            <div
                                class="w-8 h-8 rounded-full bg-secondary-container text-secondary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]"
                                    style="font-variation-settings: &quot;FILL&quot; 1">shield</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface leading-tight">
                                    Identitas Terverifikasi
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    KYC Mitra institusional "Nasi Padang Keluarga" telah selesai.
                                </p>
                                <p class="text-[10px] text-slate-400 mt-2 font-semibold">
                                    1 JAM LALU
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Upcoming Payments -->
                <div
                    class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] border-l-4 border-primary">
                    <h4 class="text-sm font-bold font-headline mb-4 uppercase tracking-widest text-slate-400">
                        Tagihan Mendatang
                    </h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">
                                    Pembayaran Vendor
                                </p>
                                <p class="text-sm font-bold text-on-surface">
                                    Berlangganan Spotify
                                </p>
                            </div>
                            <span class="text-sm font-bold text-primary">Rp27,500</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">
                                    Alokasi Pajak
                                </p>
                                <p class="text-sm font-bold text-on-surface">
                                    Pajak Perusahaan
                                </p>
                            </div>
                            <span class="text-sm font-bold text-primary">Rp450,000</span>
                        </div>
                        <button
                            class="w-full mt-2 py-3 bg-secondary/5 text-primary rounded-lg text-xs font-bold hover:bg-primary/10 transition-colors">
                            LIHAT KALENDER LENGKAP
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Shell -->
    <footer
        class="ml-64 p-6 flex justify-between items-center bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div class="flex gap-6">
            <span class="font-['Inter'] text-xs text-slate-400">© 2024 CashTrack. All rights reserved.</span>
        </div>
        <div class="flex gap-6 font-['Inter'] text-xs text-slate-400">
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Version 2.4.1</span>
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Privacy Policy</span>
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Terms</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    const ctx = document.getElementById('financeChart').getContext('2d');

    const financeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                    label: 'Income',
                    data: <?php echo json_encode($data_income); ?>,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Expense',
                    data: <?php echo json_encode($data_expense); ?>,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

</body>

</html>