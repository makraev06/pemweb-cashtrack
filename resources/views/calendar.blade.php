@extends('layouts.cashtrack')

@php
    $pageTitle = 'Calendar';
    $pageSubtitle = 'Lihat aktivitas transaksi berdasarkan tanggal.';
@endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Calendar</h1>
        <p class="text-slate-500">
            Pantau pemasukan dan pengeluaran harian.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Pemasukan Bulan Ini</p>
            <h2 class="text-3xl font-bold text-emerald-700 mt-2">
                Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Pengeluaran Bulan Ini</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Selisih Bulan Ini</p>
            <h2
                class="text-3xl font-bold {{ ($monthlyIncome - $monthlyExpense) >= 0 ? 'text-emerald-700' : 'text-red-600' }} mt-2">
                Rp {{ number_format($monthlyIncome - $monthlyExpense, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold">
                        {{ $currentDate->translatedFormat('F Y') }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        Klik tanggal untuk melihat detail transaksi.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('calendar.index', ['month' => $previousMonth]) }}"
                        class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-semibold">
                        Sebelumnya
                    </a>

                    <a href="{{ route('calendar.index', ['month' => now()->format('Y-m'), 'date' => now()->format('Y-m-d')]) }}"
                        class="px-4 py-2 rounded-lg bg-emerald-700 text-white hover:bg-emerald-800 font-semibold">
                        Hari Ini
                    </a>

                    <a href="{{ route('calendar.index', ['month' => $nextMonth]) }}"
                        class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-semibold">
                        Berikutnya
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-7 bg-slate-100 text-sm font-bold text-slate-600">
                <div class="p-4 text-center">Sen</div>
                <div class="p-4 text-center">Sel</div>
                <div class="p-4 text-center">Rab</div>
                <div class="p-4 text-center">Kam</div>
                <div class="p-4 text-center">Jum</div>
                <div class="p-4 text-center">Sab</div>
                <div class="p-4 text-center">Min</div>
            </div>

            <div class="grid grid-cols-7">
                @foreach ($calendarDays as $day)
                    <a href="{{ route('calendar.index', ['month' => $selectedMonth, 'date' => $day['date_key']]) }}" class="min-h-32 border-r border-b p-3 hover:bg-emerald-50 transition
                               {{ !$day['is_current_month'] ? 'bg-slate-50 text-slate-300' : 'bg-white' }}
                               {{ $selectedDate === $day['date_key'] ? 'ring-2 ring-emerald-600 ring-inset' : '' }}">

                        <div class="flex justify-between items-start mb-2">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full font-bold
                                        {{ $day['is_today'] ? 'bg-emerald-700 text-white' : '' }}">
                                {{ $day['date']->day }}
                            </span>

                            @if ($day['transaction_count'] > 0)
                                <span class="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded-full">
                                    {{ $day['transaction_count'] }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1 text-xs">
                            @if ($day['income'] > 0)
                                <div class="text-emerald-700 font-semibold truncate">
                                    + Rp {{ number_format($day['income'], 0, ',', '.') }}
                                </div>
                            @endif

                            @if ($day['expense'] > 0)
                                <div class="text-red-600 font-semibold truncate">
                                    - Rp {{ number_format($day['expense'], 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold">
                    Detail Tanggal
                </h2>
                <p class="text-sm text-slate-500">
                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="divide-y">
                @forelse ($selectedDateTransactions as $transaction)
                    <div class="p-5">
                        <div class="flex justify-between gap-4">
                            <div>
                                <h3 class="font-bold">
                                    {{ $transaction->category }}
                                </h3>

                                <p class="text-sm text-slate-500">
                                    {{ $transaction->keterangan }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $transaction->account->account_name ?? '-' }}
                                </p>
                            </div>

                            <div class="text-right font-bold whitespace-nowrap">
                                @if ($transaction->jenis === 'income')
                                    <span class="text-emerald-700">
                                        + Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-red-600">
                                        - Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        Tidak ada transaksi di tanggal ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection