<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('account')
            ->where('user_id', auth()->id());

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('jenis') && in_array($request->jenis, ['income', 'expense'])) {
            $query->where('jenis', $request->jenis);
        }

        $transactions = $query
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $allowedCategories = [
            'income' => ['Gaji', 'Bonus', 'Penjualan', 'Investasi', 'Hadiah', 'Lainnya'],
            'expense' => ['Makanan', 'Transportasi', 'Tagihan', 'Belanja', 'Investasi', 'Pembelian Aset', 'Lainnya'],
        ];

        $validated = $request->validate([
            'jenis' => ['required', 'in:income,expense'],
            'category' => ['required', 'string'],
            'keterangan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'account_id' => ['required', 'integer'],
        ]);

        $jenis = $validated['jenis'];
        $category = $validated['category'];

        if (!in_array($category, $allowedCategories[$jenis], true)) {
            return back()->withInput()->withErrors([
                'category' => 'Kategori transaksi tidak valid.',
            ]);
        }

        $isAssetPurchase = $category === 'Pembelian Aset';

        if ($isAssetPurchase) {
            $request->validate([
                'asset_name' => ['required', 'string', 'max:100'],
                'asset_type' => ['required', 'in:Kendaraan,Elektronik,Properti,Peralatan,Investasi,Lainnya'],
                'asset_value' => ['required', 'numeric', 'min:1'],
            ]);

            $jumlah = (float) $request->asset_value;
        } else {
            $request->validate([
                'jumlah' => ['required', 'numeric', 'min:1'],
            ]);

            $jumlah = (float) $request->jumlah;
        }

        try {
            DB::transaction(function () use ($request, $jenis, $category, $jumlah, $isAssetPurchase) {
                $account = Account::where('user_id', auth()->id())
                    ->where('account_id', $request->account_id)
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    throw new RuntimeException('invalid_account');
                }

                if ($jenis === 'expense' && $account->balance < $jumlah) {
                    throw new RuntimeException('insufficient_balance');
                }

                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'account_id' => $account->account_id,
                    'jenis' => $jenis,
                    'jumlah' => $jumlah,
                    'category' => $category,
                    'keterangan' => $request->keterangan,
                    'tanggal' => $request->tanggal,
                ]);

                $account->balance += $jenis === 'income' ? $jumlah : -$jumlah;
                $account->save();

                if ($isAssetPurchase) {
                    Asset::create([
                        'user_id' => auth()->id(),
                        'nama_aset' => $request->asset_name,
                        'kategori' => $request->asset_type,
                        'nilai' => $jumlah,
                        'deskripsi' => 'Dibuat otomatis dari transaksi: ' . $request->keterangan,
                        'tanggal_perolehan' => $request->tanggal,
                        'transaction_id' => $transaction->id,
                    ]);
                }
            });
        } catch (RuntimeException $error) {
            if ($error->getMessage() === 'insufficient_balance') {
                return back()->withInput()->withErrors([
                    'jumlah' => 'Saldo sumber dana tidak mencukupi.',
                ]);
            }

            return back()->withInput()->withErrors([
                'account_id' => 'Sumber dana tidak valid.',
            ]);
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function export(Request $request)
    {
        $query = Transaction::with('account')
            ->where('user_id', auth()->id());

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('jenis') && in_array($request->jenis, ['income', 'expense'])) {
            $query->where('jenis', $request->jenis);
        }

        $transactions = $query
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $filename = 'laporan-transaksi-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Tanggal',
                'Jenis',
                'Kategori',
                'Keterangan',
                'Sumber Dana',
                'Jumlah',
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->tanggal->format('Y-m-d'),
                    $transaction->jenis === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    $transaction->category,
                    $transaction->keterangan,
                    $transaction->account->account_name ?? '-',
                    $transaction->jumlah,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($transaction) {
            $account = Account::where('user_id', auth()->id())
                ->where('account_id', $transaction->account_id)
                ->lockForUpdate()
                ->first();

            if ($account) {
                $account->balance += $transaction->jenis === 'income'
                    ? -$transaction->jumlah
                    : $transaction->jumlah;

                $account->save();
            }

            $transaction->asset()->update([
                'transaction_id' => null,
            ]);

            $transaction->delete();
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}