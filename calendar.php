<?php
include 'includes/auth_check.php';
include 'config/database.php';

$user_id = $_SESSION['user_id'];

// Get current month and year, or from GET params
$currentMonth = isset($_GET['month']) ? (int) $_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

// Validate month and year
if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = date('n');
}
if ($currentYear < 2000 || $currentYear > 2100) {
    $currentYear = date('Y');
}

// Get first and last day of the month
$firstDayOfMonth = date('w', strtotime("$currentYear-$currentMonth-01"));
$daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));

// Get previous and next month
$prevMonth = $currentMonth - 1;
$prevYear = $currentYear;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $currentMonth + 1;
$nextYear = $currentYear;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// Query to get daily income and expense for the month
$query = "
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

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'iii', $user_id, $currentMonth, $currentYear);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$dailyData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $dailyData[$row['date']] = [
        'income' => $row['total_income'],
        'expense' => $row['total_expense']
    ];
}

$pageTitle = 'Calendar - ' . date('F Y', strtotime("$currentYear-$currentMonth-01"));
?>
<!DOCTYPE html>
<html class="light" lang="en">
<?php include 'includes/head.php'; ?>

<body class="bg-surface text-on-surface">
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <main class="ml-64 p-8 min-h-screen animate-fade-up">
        <!-- Header Section -->
        <div class="mb-10">
            <nav class="flex items-center gap-2 text-[10px] font-bold text-outline uppercase tracking-widest mb-2">
                <span class="hover:text-primary transition-colors cursor-pointer">Buku Besar</span>
                <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                <span class="text-primary">Calendar</span>
            </nav>
            <h2 class="text-3xl font-bold tracking-tight text-on-surface mb-1">
                Calendar Overview
            </h2>
            <p class="text-on-surface-variant font-body">
                View your daily income and expenses for
                <?php echo date('F Y', strtotime("$currentYear-$currentMonth-01")); ?>.
            </p>
        </div>

        <!-- Calendar Navigation -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-on-surface">
                <?php echo date('F Y', strtotime("$currentYear-$currentMonth-01")); ?>
            </h3>
            <div class="flex items-center gap-2">
                <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>"
                    class="p-2 rounded-lg bg-surface-container-low hover:bg-surface-container-lowest transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>"
                    class="p-2 rounded-lg bg-surface-container-low hover:bg-surface-container-lowest transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
            <div class="grid grid-cols-7 gap-2 mb-4">
                <?php
                $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach ($daysOfWeek as $day) {
                    echo "<div class='text-center text-xs font-bold text-slate-400 uppercase tracking-widest py-2'>$day</div>";
                }
                ?>
            </div>

            <div class="grid grid-cols-7 gap-2">
                <?php
                // Empty cells for days before the first day of the month
                for ($i = 0; $i < $firstDayOfMonth; $i++) {
                    echo "<div class='h-24 bg-surface-container-low rounded-lg'></div>";
                }

                // Days of the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $isToday = $date === date('Y-m-d');
                    $data = $dailyData[$date] ?? ['income' => 0, 'expense' => 0];

                    $income = $data['income'];
                    $expense = $data['expense'];

                    $hasData = $income > 0 || $expense > 0;

                    echo "<div class='h-24 bg-surface-container-low rounded-lg p-3 hover:bg-surface-container-lowest transition-colors cursor-pointer " . ($isToday ? 'ring-2 ring-primary' : '') . "' onclick=\"window.location.href='transaction.php?start_date=$date&end_date=$date'\">";
                    echo "<div class='text-sm font-semibold text-on-surface mb-1'>$day</div>";
                    if ($hasData) {
                        if ($income > 0) {
                            echo "<div class='text-xs text-green-600 font-medium'>+" . number_format($income, 0, ',', '.') . "</div>";
                        }
                        if ($expense > 0) {
                            echo "<div class='text-xs text-red-600 font-medium'>-" . number_format($expense, 0, ',', '.') . "</div>";
                        }
                    }
                    echo "</div>";
                }

                // Empty cells for remaining days
                $totalCells = $firstDayOfMonth + $daysInMonth;
                $remainingCells = 42 - $totalCells; // 6 weeks * 7 days
                for ($i = 0; $i < $remainingCells; $i++) {
                    echo "<div class='h-24 bg-surface-container-low rounded-lg'></div>";
                }
                ?>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 flex items-center gap-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded"></div>
                <span class="text-on-surface-variant">Income</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded"></div>
                <span class="text-on-surface-variant">Expense</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 border-2 border-primary rounded"></div>
                <span class="text-on-surface-variant">Today</span>
            </div>
        </div>
    </main>
</body>

</html>