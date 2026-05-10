@extends('layouts.cashtrack')

@php
    $pageTitle = 'Notifications';
    $pageSubtitle = 'Peringatan dan informasi penting dari aktivitas keuanganmu.';
@endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Notifications</h1>
        <p class="text-slate-500">
            Notifikasi otomatis berdasarkan saldo, transaksi, dan aset.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Notifikasi</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ count($notifications) }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Peringatan Serius</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                {{ collect($notifications)->where('type', 'danger')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Info & Reminder</p>
            <h2 class="text-3xl font-bold text-blue-700 mt-2">
                {{ collect($notifications)->whereIn('type', ['info', 'success', 'warning'])->count() }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Daftar Notifikasi</h2>
            <p class="text-sm text-slate-500">
                Semua notifikasi dibuat otomatis dari data terbaru.
            </p>
        </div>

        <div class="divide-y">
            @forelse ($notifications as $notification)
                @php
                    $styles = [
                        'success' => [
                            'icon' => 'check_circle',
                            'iconClass' => 'bg-emerald-100 text-emerald-700',
                            'titleClass' => 'text-emerald-800',
                        ],
                        'info' => [
                            'icon' => 'info',
                            'iconClass' => 'bg-blue-100 text-blue-700',
                            'titleClass' => 'text-blue-800',
                        ],
                        'warning' => [
                            'icon' => 'warning',
                            'iconClass' => 'bg-amber-100 text-amber-700',
                            'titleClass' => 'text-amber-800',
                        ],
                        'danger' => [
                            'icon' => 'error',
                            'iconClass' => 'bg-red-100 text-red-700',
                            'titleClass' => 'text-red-800',
                        ],
                    ];

                    $style = $styles[$notification['type']] ?? $styles['info'];
                @endphp

                <div class="p-6 flex gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $style['iconClass'] }}">
                        <span class="material-symbols-outlined">
                            {{ $style['icon'] }}
                        </span>
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between gap-4">
                            <div>
                                <h3 class="font-bold {{ $style['titleClass'] }}">
                                    {{ $notification['title'] }}
                                </h3>

                                <p class="text-slate-600 mt-1">
                                    {{ $notification['message'] }}
                                </p>
                            </div>

                            <div class="text-sm text-slate-400 whitespace-nowrap">
                                {{ $notification['time'] }}
                            </div>
                        </div>

                        @if (!empty($notification['action_label']) && !empty($notification['action_url']))
                            <div class="mt-3">
                                <a href="{{ $notification['action_url'] }}"
                                    class="text-sm font-semibold text-emerald-700 hover:underline">
                                    {{ $notification['action_label'] }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div
                        class="mx-auto w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-4xl">
                            notifications
                        </span>
                    </div>

                    <h3 class="text-xl font-bold">
                        Tidak ada notifikasi
                    </h3>

                    <p class="text-slate-500 mt-2">
                        Kondisi keuangan kamu belum memunculkan peringatan apa pun.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
@endsection