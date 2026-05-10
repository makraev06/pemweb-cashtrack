<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('jenis', 'income')
            ->sum('jumlah');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('jenis', 'expense')
            ->sum('jumlah');

        $totalBalance = Account::where('user_id', $userId)
            ->sum('balance');

        $totalAsset = Asset::where('user_id', $userId)
            ->sum('nilai');

        $accounts = Account::where('user_id', $userId)
            ->latest()
            ->get();

        $recentTransactions = Transaction::with('account')
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        $monthlyData = Transaction::selectRaw("
                MONTH(tanggal) as month,
                SUM(CASE WHEN jenis = 'income' THEN jumlah ELSE 0 END) as income,
                SUM(CASE WHEN jenis = 'expense' THEN jumlah ELSE 0 END) as expense
            ")
            ->where('user_id', $userId)
            ->whereYear('tanggal', now()->year)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('MONTH(tanggal)'))
            ->get();

        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        $monthlyLabels = [];
        $incomeData = [];
        $expenseData = [];

        foreach ($months as $monthNumber => $monthName) {
            $row = $monthlyData->firstWhere('month', $monthNumber);

            $monthlyLabels[] = $monthName;
            $incomeData[] = $row ? (float) $row->income : 0;
            $expenseData[] = $row ? (float) $row->expense : 0;
        }

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'totalAsset',
            'accounts',
            'recentTransactions',
            'monthlyLabels',
            'incomeData',
            'expenseData'
        ));
    }
}