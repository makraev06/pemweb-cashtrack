@extends('layouts.cashtrack')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p class="text-slate-500">
            Ringkasan kondisi keuangan kamu.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Saldo</p>
            <h2 class="text-3xl font-bold text-emerald-700 mt-2">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Pemasukan</p>
            <h2 class="text-3xl font-bold text-blue-700 mt-2">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Pengeluaran</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Aset</p>
            <h2 class="text-3xl font-bold text-amber-600 mt-2">
                Rp {{ number_format($totalAsset, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Pemasukan vs Pengeluaran</h2>
                <p class="text-slate-500">Analisis kinerja operasional bulanan.</p>
            </div>

            <div class="flex items-center gap-5 text-sm font-semibold text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    <span>Pemasukan</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-600"></span>
                    <span>Pengeluaran</span>
                </div>
            </div>
        </div>

        <canvas id="dashboardMonthlyChart" height="90"></canvas>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Transaksi Terbaru</h2>
                    <p class="text-sm text-slate-500">5 transaksi terakhir.</p>
                </div>

                <a href="{{ route('transactions.index') }}" class="text-emerald-700 text-sm font-semibold">
                    Lihat semua
                </a>
            </div>

            <table class="w-full">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="text-left p-4">Tanggal</th>
                        <th class="text-left p-4">Kategori</th>
                        <th class="text-left p-4">Sumber Dana</th>
                        <th class="text-right p-4">Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentTransactions as $transaction)
                        <tr class="border-t">
                            <td class="p-4">
                                {{ $transaction->tanggal->format('d/m/Y') }}
                            </td>

                            <td class="p-4">
                                <div class="font-semibold">
                                    {{ $transaction->category }}
                                </div>
                                <div class="text-sm text-slate-500">
                                    {{ $transaction->keterangan }}
                                </div>
                            </td>

                            <td class="p-4">
                                {{ $transaction->account->account_name ?? '-' }}
                            </td>

                            <td class="p-4 text-right">
                                @if ($transaction->jenis === 'income')
                                    <span class="text-emerald-700 font-bold">
                                        + Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-red-600 font-bold">
                                        - Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold">Sumber Dana</h2>
                <p class="text-sm text-slate-500">Saldo per account.</p>
            </div>

            <div class="divide-y">
                @forelse ($accounts as $account)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <div class="font-semibold">
                                {{ $account->account_name }}
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ strtoupper($account->account_type) }}
                            </div>
                        </div>

                        <div class="font-bold text-right">
                            Rp {{ number_format($account->balance, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        Belum ada sumber dana.
                        <div class="mt-3">
                            <a href="{{ route('accounts.index') }}" class="text-emerald-700 font-semibold">
                                Tambah sumber dana
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const dashboardMonthlyLabels = @json($monthlyLabels);
        const dashboardIncomeData = @json($incomeData);
        const dashboardExpenseData = @json($expenseData);

        const dashboardRupiahFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        });

        new Chart(document.getElementById('dashboardMonthlyChart'), {
            type: 'line',
            data: {
                labels: dashboardMonthlyLabels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: dashboardIncomeData,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: dashboardExpenseData,
                        borderColor: 'rgb(220, 38, 38)',
                        backgroundColor: 'rgba(220, 38, 38, 0.12)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + dashboardRupiahFormatter.format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return dashboardRupiahFormatter.format(value);
                            }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.2)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection