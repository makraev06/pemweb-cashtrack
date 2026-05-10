<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $selectedMonth = $request->query('month', now()->format('Y-m'));

        $currentDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $startCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $transactions = Transaction::with('account')
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [
                $startCalendar->format('Y-m-d'),
                $endCalendar->format('Y-m-d'),
            ])
            ->orderBy('tanggal')
            ->get();

        $transactionsByDate = $transactions->groupBy(function ($transaction) {
            return $transaction->tanggal->format('Y-m-d');
        });

        $calendarDays = [];

        $date = $startCalendar->copy();

        while ($date <= $endCalendar) {
            $dateKey = $date->format('Y-m-d');
            $dayTransactions = $transactionsByDate->get($dateKey, collect());

            $income = $dayTransactions
                ->where('jenis', 'income')
                ->sum('jumlah');

            $expense = $dayTransactions
                ->where('jenis', 'expense')
                ->sum('jumlah');

            $calendarDays[] = [
                'date' => $date->copy(),
                'date_key' => $dateKey,
                'is_current_month' => $date->month === $currentDate->month,
                'is_today' => $date->isToday(),
                'transactions' => $dayTransactions,
                'income' => $income,
                'expense' => $expense,
                'transaction_count' => $dayTransactions->count(),
            ];

            $date->addDay();
        }

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('jenis', 'income')
            ->whereBetween('tanggal', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d'),
            ])
            ->sum('jumlah');

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('jenis', 'expense')
            ->whereBetween('tanggal', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d'),
            ])
            ->sum('jumlah');

        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        $selectedDateTransactions = Transaction::with('account')
            ->where('user_id', $userId)
            ->whereDate('tanggal', $selectedDate)
            ->latest('id')
            ->get();

        $previousMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        return view('calendar', compact(
            'calendarDays',
            'currentDate',
            'previousMonth',
            'nextMonth',
            'selectedMonth',
            'selectedDate',
            'selectedDateTransactions',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }
}