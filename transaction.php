<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT * FROM transactions
    WHERE user_id = $user_id
    ORDER BY tanggal DESC, id DESC
");
?>

<?php
$pageTitle = 'Transactions | The Sovereign Ledger';
$activePage = 'transaction';
$searchPlaceholder = 'Search transactions...';

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
                    <span class="hover:text-primary transition-colors cursor-pointer">Ledger</span>
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-primary">Transaction History</span>
                </nav>
                <h2 class="text-[2.75rem] font-bold text-on-surface font-['Manrope'] leading-tight tracking-tight">
                    Transaction History</h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex rounded-md overflow-hidden bg-surface-container-high p-1">
                    <button
                        class="px-4 py-2 text-xs font-semibold text-on-secondary-container hover:bg-white rounded transition-all">.xlsx</button>
                    <button
                        class="px-4 py-2 text-xs font-semibold text-on-secondary-container hover:bg-white rounded transition-all">.csv</button>
                </div>
                <button
                    class="flex items-center gap-2 bg-surface-container-high text-primary font-semibold py-2.5 px-5 rounded-lg hover:bg-surface-container-highest transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Download Financial Report
                </button>
                <button
                    class="flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">add</span>
                    <a href="add_transaction.php">Add Transaction</a>
                </button>
            </div>
        </div>
        <!-- Filter & Metrics Section -->
        <div class="grid grid-cols-12 gap-6 mb-8">
            <div
                class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-['Manrope'] font-semibold text-on-surface">Filter By Date Range</h3>
                    <div class="flex items-center gap-2 text-primary text-sm font-medium cursor-pointer">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Last 30 Days
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-outline uppercase mb-1 px-1">Start Date</label>
                        <input
                            class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-emerald-500/20 transition-all"
                            type="date" value="2024-03-01" />
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-outline uppercase mb-1 px-1">End Date</label>
                        <input
                            class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-emerald-500/20 transition-all"
                            type="date" value="2024-03-31" />
                    </div>
                    <button
                        class="mt-5 bg-secondary text-white py-3 px-8 rounded-lg font-semibold text-sm hover:opacity-90 transition-all self-end">Apply
                        Filter</button>
                </div>
            </div>
            <div
                class="col-span-12 lg:col-span-4 bg-primary text-white rounded-xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-primary-fixed uppercase tracking-widest opacity-80 mb-1">Total
                        Period Volume</p>
                    <h4 class="text-3xl font-bold font-['Manrope'] tracking-tight">$482,901.20</h4>
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
                <h3 class="font-['Manrope'] font-semibold text-on-surface">Recent Ledger Entries</h3>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-outline font-medium">Displaying 1-10 of 2,451</span>
                    <div class="flex gap-1">
                        <button class="p-1 rounded hover:bg-surface-container-high text-outline"><span
                                class="material-symbols-outlined">chevron_left</span></button>
                        <button class="p-1 rounded hover:bg-surface-container-high text-outline"><span
                                class="material-symbols-outlined">chevron_right</span></button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-surface-container-low text-[10px] font-bold text-outline uppercase tracking-widest">
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Account</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <?php if (mysqli_num_rows($query) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($query)): ?>

                                <tr class="group hover:bg-surface-container-highest transition-colors cursor-pointer relative">

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
                                                Inbound
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="px-2 py-1 rounded bg-error-container text-on-error-container text-[10px] font-bold uppercase">
                                                Outbound
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
                <div class="flex items-center gap-2">
                    <span class="text-xs text-outline">Items per page:</span>
                    <select class="bg-transparent border-none text-xs font-bold focus:ring-0 cursor-pointer">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
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
            <span class="font-['Inter'] text-xs text-slate-400">© 2024 The Sovereign Ledger. All rights reserved.</span>
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
</body>

</html>