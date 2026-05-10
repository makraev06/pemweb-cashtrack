<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $title ?? 'CashTrack' }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        h1,
        h2,
        h3,
        .display-font {
            font-family: "Manrope", sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            display: inline-block;
            vertical-align: middle;
        }

        summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-slate-200 shadow-sm p-4 z-40">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-11 h-11 rounded-lg bg-emerald-700 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>

                <div>
                    <h1 class="text-xl font-bold text-emerald-800">CashTrack</h1>
                    <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">
                        Pencatatan Keuangan
                    </p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('dashboard') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    Dashboard
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('transactions.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    Transactions
                </a>

                <a href="{{ route('accounts.index') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('accounts.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    Sumber Dana
                </a>

                <a href="{{ route('assets.index') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('assets.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Assets
                </a>

                <a href="{{ route('chart.index') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('chart.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">monitoring</span>
                    Chart
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold
       {{ request()->routeIs('profile.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-100 text-slate-700' }}">
                    <span class="material-symbols-outlined">person</span>
                    Profile
                </a>
            </nav>

            <div class="absolute bottom-4 left-4 right-4 text-xs text-slate-400">
                © {{ date('Y') }} CashTrack
            </div>
        </aside>

        {{-- Main Area --}}
        <main class="ml-64 min-h-screen w-full">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-200 px-8 py-4">
                <div class="flex items-center justify-between">
                    {{-- Topbar Left --}}
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">
                            {{ $pageTitle ?? 'CashTrack' }}
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ $pageSubtitle ?? 'Kelola keuanganmu dengan lebih rapi.' }}
                        </p>
                    </div>

                    {{-- Topbar Right --}}
                    <div class="flex items-center gap-4">

                        @php
                            $notificationCount = auth()->check()
                                ? count(\App\Http\Controllers\NotificationController::generateNotifications())
                                : 0;
                        @endphp

                        <a href="{{ route('notifications.index') }}"
                            class="relative w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-100">
                            <span class="material-symbols-outlined text-slate-600">
                                notifications
                            </span>

                            @if ($notificationCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                </span>
                            @endif
                        </a>

                        <details class="relative">
                            <summary
                                class="list-none cursor-pointer w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-100">
                                <span class="material-symbols-outlined text-slate-600">
                                    settings
                                </span>
                            </summary>

                            <div
                                class="absolute right-0 mt-3 w-64 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden">
                                <div class="p-4 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 truncate">
                                                {{ auth()->user()->name }}
                                            </p>

                                            <p class="text-xs text-slate-500 truncate">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 text-sm font-semibold text-slate-700">
                                        <span class="material-symbols-outlined text-base">
                                            person
                                        </span>
                                        Profile
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-sm font-semibold text-red-600">
                                            <span class="material-symbols-outlined text-base">
                                                logout
                                            </span>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </details>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-emerald-100 text-emerald-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>