<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $year = date('Y');
        $incomeData = [];
        $expenseData = [];

        // Logika looping 12 bulan dari file lama baginda
        for ($m = 1; $m <= 12; $m++) {
            $incomeData[] = DB::table('income')
                ->whereMonth('date', $m)
                ->whereYear('date', $year)
                ->sum('amount') ?? 0;

            $expenseData[] = DB::table('expense')
                ->whereMonth('date', $m)
                ->whereYear('date', $year)
                ->sum('amount') ?? 0;
        }

        $totalIncome = DB::table('income')->sum('amount') ?? 0;
        $totalExpense = DB::table('expense')->sum('amount') ?? 0;
        $saldo = $totalIncome - $totalExpense;
        $totalMembers = DB::table('members')->where('status', 'aktif')->count();

        $recentTransactions = DB::table('income')
            ->select('date', 'description', 'amount', DB::raw("'Income' as type"))
            ->unionAll(
                DB::table('expense')
                    ->select('date', 'description', 'amount', DB::raw("'Expense' as type"))
            )
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'saldo',
            'totalMembers',
            'recentTransactions',
            'incomeData',
            'expenseData'
        ));
    }
}