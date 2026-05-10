@extends('layouts.cashtrack')

@php
    $pageTitle = 'Chart';
    $pageSubtitle = 'Visualisasi pemasukan dan pengeluaran per kategori.';
@endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Chart</h1>
        <p class="text-slate-500">
            Ringkasan kategori pemasukan dan pengeluaran bulan {{ $currentDate->translatedFormat('F Y') }}.
        </p>
    </div>

    {{-- Filter Bulan --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Pilih Periode</h2>
                <p class="text-sm text-slate-500">
                    Ubah bulan untuk melihat komposisi kategori.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('chart.index', ['month' => $previousMonth]) }}"
                    class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-semibold">
                    Sebelumnya
                </a>

                <form method="GET" action="{{ route('chart.index') }}" class="flex items-center gap-3">
                    <input type="month" name="month" value="{{ $selectedMonth }}" class="rounded-lg border-slate-300">
                    <button class="bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-emerald-800">
                        Tampilkan
                    </button>
                </form>

                <a href="{{ route('chart.index', ['month' => $nextMonth]) }}"
                    class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-semibold">
                    Berikutnya
                </a>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">Total Pemasukan</p>
            <h2 class="text-3xl font-bold text-emerald-700 mt-2">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">Total Pengeluaran</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    {{-- Chart Cards --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        {{-- Pemasukan --}}
        <div class="bg-white rounded-xl shadow p-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">Pemasukan</p>
                    <h2 class="text-2xl font-bold">
                        Per Kategori - {{ $currentDate->translatedFormat('F Y') }}
                    </h2>
                </div>

                <div
                    class="w-10 h-10 rounded-full border-2 border-emerald-700 text-emerald-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">pie_chart</span>
                </div>
            </div>

            <div class="h-[360px]">
                @if ($incomeBreakdown->isEmpty())
                    <div class="h-full flex items-center justify-center text-slate-400">
                        Belum ada data pemasukan.
                    </div>
                @else
                    <canvas id="incomeChart"></canvas>
                @endif
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div class="bg-white rounded-xl shadow p-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">Pengeluaran</p>
                    <h2 class="text-2xl font-bold">
                        Per Kategori - {{ $currentDate->translatedFormat('F Y') }}
                    </h2>
                </div>

                <div class="w-10 h-10 rounded-full border-2 border-red-500 text-red-500 flex items-center justify-center">
                    <span class="material-symbols-outlined">pie_chart</span>
                </div>
            </div>

            <div class="h-[360px]">
                @if ($expenseBreakdown->isEmpty())
                    <div class="h-full flex items-center justify-center text-slate-400">
                        Belum ada data pengeluaran.
                    </div>
                @else
                    <canvas id="expenseChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    {{-- Rincian --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- Rincian Pemasukan --}}
        <div class="bg-white rounded-xl shadow p-8">
            <h2 class="text-xl font-bold tracking-widest text-slate-400 uppercase mb-6">
                Rincian Pemasukan
            </h2>

            <div class="space-y-4">
                @forelse ($incomeBreakdown as $item)
                    <div class="border border-slate-200 rounded-[28px] p-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ $item['category'] }}
                            </h3>
                            <p class="text-slate-500 mt-1">
                                Persentase: {{ rtrim(rtrim(number_format($item['percentage'], 1, '.', ''), '0'), '.') }}%
                            </p>
                        </div>

                        <div class="text-emerald-700 text-2xl font-bold">
                            Rp{{ number_format($item['total'], 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div class="text-slate-400">
                        Belum ada rincian pemasukan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Rincian Pengeluaran --}}
        <div class="bg-white rounded-xl shadow p-8">
            <h2 class="text-xl font-bold tracking-widest text-slate-400 uppercase mb-6">
                Rincian Pengeluaran
            </h2>

            <div class="space-y-4">
                @forelse ($expenseBreakdown as $item)
                    <div class="border border-slate-200 rounded-[28px] p-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ $item['category'] }}
                            </h3>
                            <p class="text-slate-500 mt-1">
                                Persentase: {{ rtrim(rtrim(number_format($item['percentage'], 1, '.', ''), '0'), '.') }}%
                            </p>
                        </div>

                        <div class="text-red-500 text-2xl font-bold">
                            Rp{{ number_format($item['total'], 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div class="text-slate-400">
                        Belum ada rincian pengeluaran.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const incomeLabels = @json($incomeLabels);
        const incomeTotals = @json($incomeTotals);

        const expenseLabels = @json($expenseLabels);
        const expenseTotals = @json($expenseTotals);

        const incomeColors = [
            '#16a34a',
            '#22c55e',
            '#4ade80',
            '#86efac',
            '#15803d',
            '#65a30d',
            '#10b981'
        ];

        const expenseColors = [
            '#16a34a',
            '#0ea5e9',
            '#8b5cf6',
            '#f97316',
            '#ef4444',
            '#eab308',
            '#14b8a6'
        ];

        if (document.getElementById('incomeChart')) {
            new Chart(document.getElementById('incomeChart'), {
                type: 'doughnut',
                data: {
                    labels: incomeLabels,
                    datasets: [{
                        data: incomeTotals,
                        backgroundColor: incomeColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '0%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 16,
                                padding: 18,
                                color: '#475569',
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        }

        if (document.getElementById('expenseChart')) {
            new Chart(document.getElementById('expenseChart'), {
                type: 'doughnut',
                data: {
                    labels: expenseLabels,
                    datasets: [{
                        data: expenseTotals,
                        backgroundColor: expenseColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '0%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 16,
                                padding: 18,
                                color: '#475569',
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection