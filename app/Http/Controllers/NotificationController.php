<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Transaction;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->generateNotifications();

        return view('notifications', compact('notifications'));
    }

    public function api()
    {
        $notifications = $this->generateNotifications();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public static function generateNotifications(): array
    {
        $userId = auth()->id();

        if (!$userId) {
            return [];
        }

        $notifications = [];

        $accounts = Account::where('user_id', $userId)->get();

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('jenis', 'income')
            ->sum('jumlah');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('jenis', 'expense')
            ->sum('jumlah');

        $totalBalance = $accounts->sum('balance');

        $transactionCount = Transaction::where('user_id', $userId)->count();

        if ($accounts->isEmpty()) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Belum ada sumber dana',
                'message' => 'Tambahkan rekening, e-wallet, atau cash agar kamu bisa mencatat transaksi.',
                'action_label' => 'Tambah Sumber Dana',
                'action_url' => route('accounts.index'),
                'time' => 'Sekarang',
            ];
        }

        if ($transactionCount === 0) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Belum ada transaksi',
                'message' => 'Mulai catat pemasukan atau pengeluaran pertamamu.',
                'action_label' => 'Tambah Transaksi',
                'action_url' => route('transactions.create'),
                'time' => 'Sekarang',
            ];
        }

        foreach ($accounts as $account) {
            if ($account->balance <= 50000) {
                $notifications[] = [
                    'type' => 'danger',
                    'title' => 'Saldo rendah',
                    'message' => 'Saldo ' . $account->account_name . ' tersisa Rp ' . number_format($account->balance, 0, ',', '.') . '.',
                    'action_label' => 'Lihat Sumber Dana',
                    'action_url' => route('accounts.index'),
                    'time' => 'Sekarang',
                ];
            }
        }

        if ($totalExpense > $totalIncome && $transactionCount > 0) {
            $notifications[] = [
                'type' => 'danger',
                'title' => 'Pengeluaran melebihi pemasukan',
                'message' => 'Total pengeluaran kamu sudah lebih besar dari total pemasukan.',
                'action_label' => 'Lihat Chart',
                'action_url' => route('chart.index'),
                'time' => 'Sekarang',
            ];
        }

        if ($totalBalance <= 100000 && $accounts->isNotEmpty()) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Total saldo menipis',
                'message' => 'Total saldo semua sumber dana kamu tinggal Rp ' . number_format($totalBalance, 0, ',', '.') . '.',
                'action_label' => 'Cek Dashboard',
                'action_url' => route('dashboard'),
                'time' => 'Sekarang',
            ];
        }

        $largeTransactions = Transaction::where('user_id', $userId)
            ->where('jumlah', '>=', 1000000)
            ->latest('tanggal')
            ->limit(5)
            ->get();

        foreach ($largeTransactions as $transaction) {
            $notifications[] = [
                'type' => $transaction->jenis === 'income' ? 'success' : 'warning',
                'title' => 'Transaksi besar terdeteksi',
                'message' => $transaction->category . ' sebesar Rp ' . number_format($transaction->jumlah, 0, ',', '.') . ' pada ' . $transaction->tanggal->format('d/m/Y') . '.',
                'action_label' => 'Lihat Transaksi',
                'action_url' => route('transactions.index'),
                'time' => $transaction->tanggal->format('d M Y'),
            ];
        }

        $recentAssets = Asset::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentAssets as $asset) {
            $notifications[] = [
                'type' => 'success',
                'title' => 'Aset tercatat',
                'message' => $asset->nama_aset . ' senilai Rp ' . number_format($asset->nilai, 0, ',', '.') . ' sudah masuk daftar aset.',
                'action_label' => 'Lihat Assets',
                'action_url' => route('assets.index'),
                'time' => $asset->created_at->format('d M Y'),
            ];
        }

        return $notifications;
    }
}