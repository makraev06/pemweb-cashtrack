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
?>

<?php
$pageTitle = 'The Sovereign Ledger - Dashboard';
$activePage = 'dashboard';
$searchPlaceholder = 'Search ledger assets...';

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
                Financial Overview
            </h2>
            <p class="text-on-surface-variant font-body">
                Welcome back, <?php echo $_SESSION['name']; ?>.
            </p>
        </div>
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Balance -->
            <div
                class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-label-sm font-bold text-slate-400 uppercase tracking-widest text-[10px]">Total
                        Saldo</span>
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-slate-400 text-xl">Rp</span>
                    <h3 class="text-4xl font-bold font-headline tracking-tighter">
                        <?php echo number_format($total_balance, 0, ',', '.'); ?>
                    </h3>
                </div>
                <div class="mt-4 flex items-center text-primary text-xs font-bold">
                    <span class="material-symbols-outlined text-[14px] mr-1">trending_up</span>
                    <span>+2.4% from last week</span>
                </div>
            </div>
            <!-- Total Income -->
            <div
                class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-label-sm font-bold text-slate-400 uppercase tracking-widest text-[10px]">Total
                        Pemasukan Hari Ini</span>
                    <div
                        class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary-container">
                        <span class="material-symbols-outlined">arrow_downward</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-slate-400 text-xl">Rp</span>
                    <h3 class="text-4xl font-bold font-headline tracking-tighter">
                        <?php echo number_format($income_today, 0, ',', '.'); ?>
                    </h3>
                </div>
                <div
                    class="mt-4 inline-flex items-center px-2 py-1 bg-primary/10 text-primary rounded-md text-[10px] font-bold uppercase tracking-tight">
                    Optimized Flow
                </div>
            </div>
            <!-- Total Expense -->
            <div
                class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-label-sm font-bold text-slate-400 uppercase tracking-widest text-[10px]">Total
                        Pengeluaran Hari Ini</span>
                    <div class="w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined">arrow_upward</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-slate-400 text-xl">Rp</span>
                    <h3 class="text-4xl font-bold font-headline tracking-tighter">
                        <?php echo number_format($expense_today, 0, ',', '.'); ?>
                    </h3>
                </div>
                <div
                    class="mt-4 inline-flex items-center px-2 py-1 bg-tertiary/10 text-tertiary rounded-md text-[10px] font-bold uppercase tracking-tight">
                    Critical Outflow
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
                            Income vs Expense
                        </h4>
                        <p class="text-xs text-slate-400">
                            Monthly operational performance analytics
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-xs font-semibold text-slate-500">Income</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-tertiary"></span>
                            <span class="text-xs font-semibold text-slate-500">Expense</span>
                        </div>
                    </div>
                </div>
                <!-- Fake Area Chart Visual -->
                <div
                    class="relative w-full h-[320px] bg-surface-container-low/30 rounded-lg overflow-hidden border border-outline-variant/10">
                    <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 800 300">
                        <!-- Income Path -->
                        <path class="fill-primary/5"
                            d="M0,250 Q100,200 200,220 T400,150 T600,100 T800,80 L800,300 L0,300 Z"></path>
                        <path class="text-primary" d="M0,250 Q100,200 200,220 T400,150 T600,100 T800,80" fill="none"
                            stroke="currentColor" stroke-width="3"></path>
                        <!-- Expense Path -->
                        <path class="fill-tertiary/5"
                            d="M0,280 Q150,260 300,270 T450,220 T600,240 T800,200 L800,300 L0,300 Z"></path>
                        <path class="text-tertiary" d="M0,280 Q150,260 300,270 T450,220 T600,240 T800,200" fill="none"
                            stroke="currentColor" stroke-width="3"></path>
                        <!-- Grid Lines -->
                        <line class="text-slate-100" stroke="currentColor" stroke-dasharray="4" x1="0" x2="800" y1="100"
                            y2="100"></line>
                        <line class="text-slate-100" stroke="currentColor" stroke-dasharray="4" x1="0" x2="800" y1="200"
                            y2="200"></line>
                    </svg>
                    <!-- Data Points Labels -->
                    <div
                        class="absolute bottom-4 left-0 w-full flex justify-between px-8 text-[10px] font-bold text-slate-400 tracking-widest uppercase">
                        <span>WK 01</span>
                        <span>WK 02</span>
                        <span>WK 03</span>
                        <span>WK 04</span>
                    </div>
                </div>
            </div>
            <!-- Side Widgets -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Recent Alerts -->
                <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)]">
                    <h4 class="text-sm font-bold font-headline mb-4 uppercase tracking-widest text-slate-400">
                        Critical Alerts
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
                                    Large Withdrawal Detected
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Acme Corp account flagged for Rp250k unusual activity.
                                </p>
                                <p class="text-[10px] text-slate-400 mt-2 font-semibold">
                                    2 MINUTES AGO
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
                                    Identity Verified
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Institutional partner 'Global Bond' KYC completed.
                                </p>
                                <p class="text-[10px] text-slate-400 mt-2 font-semibold">
                                    1 HOUR AGO
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Upcoming Payments -->
                <div
                    class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.02)] border-l-4 border-primary">
                    <h4 class="text-sm font-bold font-headline mb-4 uppercase tracking-widest text-slate-400">
                        Upcoming Obligations
                    </h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">
                                    Vendor Payment
                                </p>
                                <p class="text-sm font-bold text-on-surface">
                                    Amazon AWS Services
                                </p>
                            </div>
                            <span class="text-sm font-bold text-primary">Rp12,400</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">
                                    Tax Provision
                                </p>
                                <p class="text-sm font-bold text-on-surface">
                                    Quarterly Corporate
                                </p>
                            </div>
                            <span class="text-sm font-bold text-primary">Rp450,000</span>
                        </div>
                        <button
                            class="w-full mt-2 py-3 bg-primary/5 text-primary rounded-lg text-xs font-bold hover:bg-primary/10 transition-colors">
                            VIEW FULL CALENDAR
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
            <span class="font-['Inter'] text-xs text-slate-400">© 2024 The Sovereign Ledger. All rights reserved.</span>
        </div>
        <div class="flex gap-6 font-['Inter'] text-xs text-slate-400">
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Version 2.4.1</span>
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Privacy Policy</span>
            <span class="hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">Terms</span>
        </div>
    </footer>
</body>

</html>