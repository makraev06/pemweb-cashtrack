<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter bulan dan tahun (Default bulan/tahun sekarang)
        $selectedMonth = $request->query('month', date('n'));
        $selectedYear = $request->query('year', date('Y'));

        // Query data members dan status kas mereka (Dulu di bagian bawah kas.php)
        $members = DB::table('members')
            ->leftJoin('kas', function ($join) use ($selectedMonth, $selectedYear) {
                $join->on('members.id', '=', 'kas.member_id')
                    ->where('kas.month', '=', $selectedMonth)
                    ->where('kas.year', '=', $selectedYear);
            })
            ->where('members.status', 'aktif')
            ->select('members.*', 'kas.amount', 'kas.status as kas_status')
            ->get();

        return view('kas', compact('members', 'selectedMonth', 'selectedYear'));
    }

    public function bayar(Request $request)
    {
        $amount = 10000;
        $date = date("Y-m-d");

        // 1. Insert ke tabel income (Logika asli baginda)
        $incomeId = DB::table('income')->insertGetId([
            'description' => "Kas Bulanan - Member ID {$request->member_id} ({$request->month}/{$request->year})",
            'amount' => $amount,
            'date' => $date
        ]);

        // 2. Update atau Insert ke tabel kas
        DB::table('kas')->updateOrInsert(
            ['member_id' => $request->member_id, 'month' => $request->month, 'year' => $request->year],
            ['amount' => $amount, 'status' => 'lunas', 'income_id' => $incomeId]
        );

        return redirect()->back();
    }

    public function batal(Request $request)
    {
        // 1. Ambil data kas untuk cari income_id
        $kas = DB::table('kas')
            ->where('member_id', $request->member_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($kas && $kas->income_id) {
            // 2. Hapus dari income
            DB::table('income')->where('id', $kas->income_id)->delete();
        }

        // 3. Update status kas kembali ke 'belum'
        DB::table('kas')
            ->where('member_id', $request->member_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->update(['status' => 'belum', 'amount' => null, 'income_id' => null]);

        return redirect()->back();
    }
}