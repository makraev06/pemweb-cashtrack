<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = (int) $_SESSION['user_id'];

$selectedMonth = $_GET['month'] ?? date('Y-m');
$parsedMonth = DateTime::createFromFormat('Y-m', $selectedMonth);
if (!$parsedMonth || $parsedMonth->format('Y-m') !== $selectedMonth) {
    $selectedMonth = date('Y-m');
    $parsedMonth = DateTime::createFromFormat('Y-m', $selectedMonth);
}

$chartYear = (int) $parsedMonth->format('Y');
$chartMonth = (int) $parsedMonth->format('m');
$chartLabel = $parsedMonth->format('F Y');

function fetchCategoryTotals($conn, $user_id, $jenis, $chartMonth, $chartYear)
{
    $sql = "SELECT IFNULL(NULLIF(category, ''), 'Lainnya') AS category, SUM(jumlah) AS total
            FROM transactions
            WHERE user_id = ? AND jenis = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
            GROUP BY category
            ORDER BY total DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'isii', $user_id, $jenis, $chartMonth, $chartYear);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = [
            'category' => $row['category'],
            'total' => (float) $row['total'],
        ];
    }
    return $rows;
}

$incomeData = fetchCategoryTotals($conn, $user_id, 'income', $chartMonth, $chartYear);
$expenseData = fetchCategoryTotals($conn, $user_id, 'expense', $chartMonth, $chartYear);

$incomeLabels = array_column($incomeData, 'category');
$incomeTotals = array_column($incomeData, 'total');
$expenseLabels = array_column($expenseData, 'category');
$expenseTotals = array_column($expenseData, 'total');

$chartColors = [
    '#16a34a', '#0ea5e9', '#8b5cf6', '#f97316', '#dc2626',
    '#eab308', '#14b8a6', '#fb7185', '#64748b', '#22c55e',
    '#0f766e', '#7928ca', '#f59e0b', '#0ea5e9', '#ef4444',
];

$activePage = 'chart';
$pageTitle = 'Chart Transaksi CashTrack';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 p-8 min-h-[calc(100vh-64px-60px)] animate-fade-up">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-bold text-outline uppercase tracking-widest mb-2">
                    <span class="hover:text-primary transition-colors cursor-pointer">Buku Besar</span>
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-primary">Grafik Transaksi</span>
                </nav>
                <h2 class="text-[2.75rem] font-bold text-on-surface font-['Manrope'] leading-tight tracking-tight">
                    Grafik Pemasukan & Pengeluaran</h2>
            </div>
            <form method="GET" class="flex items-center gap-3">
                <label class="text-sm font-semibold text-slate-500">Pilih Bulan</label>
                <input type="month" name="month" value="<?php echo htmlspecialchars($selectedMonth); ?>"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <button type="submit"
                    class="bg-gradient-to-br from-primary to-primary-container text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-sm btn-hover">
                    Tampilkan
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Pemasukan</p>
                        <h3 class="text-xl font-semibold text-on-surface">Per Kategori - <?php echo htmlspecialchars($chartLabel); ?></h3>
                    </div>
                    <span class="material-symbols-outlined text-3xl text-primary">pie_chart</span>
                </div>
                <?php if (!empty($incomeLabels)): ?>
                    <div class="relative w-full h-[360px]">
                        <canvas id="incomeChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center text-slate-500">
                        Tidak ada data pemasukan untuk bulan ini.
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Pengeluaran</p>
                        <h3 class="text-xl font-semibold text-on-surface">Per Kategori - <?php echo htmlspecialchars($chartLabel); ?></h3>
                    </div>
                    <span class="material-symbols-outlined text-3xl text-tertiary">pie_chart</span>
                </div>
                <?php if (!empty($expenseLabels)): ?>
                    <div class="relative w-full h-[360px]">
                        <canvas id="expenseChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center text-slate-500">
                        Tidak ada data pengeluaran untuk bulan ini.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <h4 class="text-sm uppercase tracking-widest text-slate-400 font-bold mb-4">Rincian Pemasukan</h4>
                <?php if (!empty($incomeData)): ?>
                    <div class="space-y-3">
                        <?php foreach ($incomeData as $item): ?>
                            <div class="flex items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-4">
                                <div>
                                    <p class="font-semibold text-on-surface"><?php echo htmlspecialchars($item['category']); ?></p>
                                    <p class="text-xs text-slate-500">Persentase: <?php echo round(($item['total'] / array_sum($incomeTotals)) * 100, 1); ?>%</p>
                                </div>
                                <span class="font-semibold text-primary">Rp<?php echo number_format($item['total'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-slate-500">Belum ada pemasukan untuk bulan ini.</p>
                <?php endif; ?>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <h4 class="text-sm uppercase tracking-widest text-slate-400 font-bold mb-4">Rincian Pengeluaran</h4>
                <?php if (!empty($expenseData)): ?>
                    <div class="space-y-3">
                        <?php foreach ($expenseData as $item): ?>
                            <div class="flex items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-4">
                                <div>
                                    <p class="font-semibold text-on-surface"><?php echo htmlspecialchars($item['category']); ?></p>
                                    <p class="text-xs text-slate-500">Persentase: <?php echo round(($item['total'] / array_sum($expenseTotals)) * 100, 1); ?>%</p>
                                </div>
                                <span class="font-semibold text-tertiary">Rp<?php echo number_format($item['total'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-slate-500">Belum ada pengeluaran untuk bulan ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="ml-64 p-6 flex justify-between items-center bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div class="flex gap-6">
            <span class="font-['Inter'] text-xs text-slate-400">© 2026 CashTrack. All rights reserved.</span>
        </div>
        <div class="flex gap-6 font-['Inter'] text-xs text-slate-400">
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Version 2.4.1</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const incomeCanvas = document.getElementById('incomeChart');
        const expenseCanvas = document.getElementById('expenseChart');
        const chartColors = <?php echo json_encode($chartColors); ?>;

        function buildPieConfig(labels, data, colorStart) {
            return {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: labels.map((_, index) => chartColors[index % chartColors.length]),
                        borderWidth: 1,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#334155',
                                boxWidth: 14,
                                padding: 12,
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((sum, item) => sum + item, 0);
                                    const percentage = total ? ((value / total) * 100).toFixed(1) : '0.0';
                                    return context.label + ': Rp' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            };
        }

        if (incomeCanvas) {
            new Chart(incomeCanvas, buildPieConfig(
                <?php echo json_encode($incomeLabels); ?>,
                <?php echo json_encode($incomeTotals); ?>
            ));
        }

        if (expenseCanvas) {
            new Chart(expenseCanvas, buildPieConfig(
                <?php echo json_encode($expenseLabels); ?>,
                <?php echo json_encode($expenseTotals); ?>
            ));
        }
    </script>
</body>
</html>
