@extends('layouts.cashtrack')

@php
    $pageTitle = 'Transactions';
    $pageSubtitle = 'Kelola pemasukan dan pengeluaran.';
@endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Transactions</h1>
        <p class="text-slate-500">
            Kelola dan filter data transaksi keuanganmu.
        </p>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow p-7 mb-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold">
                Filter Berdasarkan Tanggal
            </h2>

            <a href="{{ route('calendar.index') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 font-bold hover:bg-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-base">
                    calendar_month
                </span>
                Kalender
            </a>
        </div>

        <form method="GET" action="{{ route('transactions.index') }}"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 items-end">

            <div>
                <label class="block text-xs font-bold tracking-widest text-slate-400 uppercase mb-2">
                    Dari Tanggal
                </label>

                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full rounded-xl border-slate-200 shadow-sm px-4 py-3 text-slate-700 focus:border-emerald-600 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold tracking-widest text-slate-400 uppercase mb-2">
                    Sampai Tanggal
                </label>

                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full rounded-xl border-slate-200 shadow-sm px-4 py-3 text-slate-700 focus:border-emerald-600 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold tracking-widest text-slate-400 uppercase mb-2">
                    Jenis
                </label>

                <select name="jenis"
                    class="w-full rounded-xl border-slate-200 shadow-sm px-4 py-3 text-slate-700 focus:border-emerald-600 focus:ring-emerald-600">
                    <option value="">Semua</option>
                    <option value="income" @selected(request('jenis') === 'income')>
                        Pemasukan
                    </option>
                    <option value="expense" @selected(request('jenis') === 'expense')>
                        Pengeluaran
                    </option>
                </select>
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-emerald-700 text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-800 shadow-sm">
                    Terapkan Filter
                </button>
            </div>

            <div>
                <a href="{{ route('transactions.export', request()->query()) }}"
                    class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-bold hover:bg-slate-800 shadow-sm">
                    <span class="material-symbols-outlined text-base">
                        download
                    </span>
                    Download Laporan
                </a>
            </div>
        </form>

        @if (request('start_date') || request('end_date') || request('jenis'))
            <div class="mt-5">
                <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-red-600 hover:underline">
                    Reset filter
                </a>
            </div>
        @endif
    </div>

    {{-- Header Table --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">Daftar Transaksi</h2>
            <p class="text-slate-500">
                Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }}
                dari {{ $transactions->total() }} transaksi.
            </p>
        </div>

        <a href="{{ route('transactions.create') }}"
            class="bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-emerald-800">
            Tambah Transaksi
        </a>
    </div>

    {{-- Transaction Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left p-4">Tanggal</th>
                    <th class="text-left p-4">Jenis</th>
                    <th class="text-left p-4">Kategori</th>
                    <th class="text-left p-4">Keterangan</th>
                    <th class="text-left p-4">Sumber Dana</th>
                    <th class="text-right p-4">Jumlah</th>
                    <th class="text-right p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="border-t">
                        <td class="p-4">
                            {{ $transaction->tanggal->format('d/m/Y') }}
                        </td>

                        <td class="p-4">
                            @if ($transaction->jenis === 'income')
                                <span class="text-emerald-700 font-semibold">
                                    Pemasukan
                                </span>
                            @else
                                <span class="text-red-600 font-semibold">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>

                        <td class="p-4">
                            {{ $transaction->category }}
                        </td>

                        <td class="p-4">
                            {{ $transaction->keterangan }}
                        </td>

                        <td class="p-4">
                            {{ $transaction->account->account_name ?? '-' }}
                        </td>

                        <td class="p-4 text-right font-semibold">
                            @if ($transaction->jenis === 'income')
                                <span class="text-emerald-700">
                                    + Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-red-600">
                                    - Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>

                        <td class="p-4 text-right">
                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                                onsubmit="return confirm('Hapus transaksi ini? Saldo akan dikembalikan.')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" title="Hapus transaksi"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                    <span class="material-symbols-outlined text-xl">
                                        delete
                                    </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500">
                            Tidak ada transaksi sesuai filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t bg-white">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection