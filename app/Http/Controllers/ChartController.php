<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $currentDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $incomeCategoryData = Transaction::selectRaw('category, SUM(jumlah) as total')
            ->where('user_id', $userId)
            ->where('jenis', 'income')
            ->whereBetween('tanggal', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d'),
            ])
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $expenseCategoryData = Transaction::selectRaw('category, SUM(jumlah) as total')
            ->where('user_id', $userId)
            ->where('jenis', 'expense')
            ->whereBetween('tanggal', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d'),
            ])
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalIncome = $incomeCategoryData->sum('total');
        $totalExpense = $expenseCategoryData->sum('total');

        $incomeBreakdown = $incomeCategoryData->map(function ($item) use ($totalIncome) {
            return [
                'category' => $item->category,
                'total' => (float) $item->total,
                'percentage' => $totalIncome > 0
                    ? round(($item->total / $totalIncome) * 100, 1)
                    : 0,
            ];
        });

        $expenseBreakdown = $expenseCategoryData->map(function ($item) use ($totalExpense) {
            return [
                'category' => $item->category,
                'total' => (float) $item->total,
                'percentage' => $totalExpense > 0
                    ? round(($item->total / $totalExpense) * 100, 1)
                    : 0,
            ];
        });

        $incomeLabels = $incomeBreakdown->pluck('category')->values();
        $incomeTotals = $incomeBreakdown->pluck('total')->values();

        $expenseLabels = $expenseBreakdown->pluck('category')->values();
        $expenseTotals = $expenseBreakdown->pluck('total')->values();

        $previousMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        return view('chart', compact(
            'selectedMonth',
            'currentDate',
            'previousMonth',
            'nextMonth',
            'totalIncome',
            'totalExpense',
            'incomeBreakdown',
            'expenseBreakdown',
            'incomeLabels',
            'incomeTotals',
            'expenseLabels',
            'expenseTotals'
        ));
    }
}