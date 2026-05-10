<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - CashTrack</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

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
        h3 {
            font-family: "Manrope", sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        {{-- Left Branding --}}
        <div class="hidden lg:flex bg-emerald-800 text-white p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute w-96 h-96 rounded-full bg-white -top-20 -left-20"></div>
                <div class="absolute w-96 h-96 rounded-full bg-white bottom-10 right-10"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white text-emerald-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">
                            account_balance
                        </span>
                    </div>

                    <div>
                        <h1 class="text-3xl font-extrabold">CashTrack</h1>
                        <p class="text-sm tracking-widest uppercase text-emerald-100 font-bold">
                            Pencatatan Keuangan
                        </p>
                    </div>
                </div>

                <div>
                    <h2 class="text-5xl font-extrabold leading-tight mb-5">
                        Kelola uangmu dengan lebih rapi.
                    </h2>

                    <p class="text-lg text-emerald-100 max-w-xl">
                        Catat pemasukan, pengeluaran, aset, dan sumber dana dalam satu dashboard yang mudah dipahami.
                    </p>

                    <div class="grid grid-cols-3 gap-4 mt-10 max-w-xl">
                        <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                            <p class="text-3xl font-bold">+</p>
                            <p class="text-sm text-emerald-100">Pemasukan</p>
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                            <p class="text-3xl font-bold">−</p>
                            <p class="text-sm text-emerald-100">Pengeluaran</p>
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                            <p class="text-3xl font-bold">₿</p>
                            <p class="text-sm text-emerald-100">Aset</p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-emerald-100">
                    © {{ date('Y') }} CashTrack. All rights reserved.
                </p>
            </div>
        </div>

        {{-- Right Form --}}
        <div class="flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">
                            account_balance
                        </span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-extrabold text-emerald-800">CashTrack</h1>
                        <p class="text-xs tracking-widest uppercase text-slate-400 font-bold">
                            Pencatatan Keuangan
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
                    <div class="mb-8">
                        <h2 class="text-3xl font-extrabold text-slate-900">
                            Selamat datang
                        </h2>
                        <p class="text-slate-500 mt-2">
                            Masuk untuk melanjutkan ke dashboard CashTrack.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-5 rounded-xl bg-emerald-50 text-emerald-700 p-4 text-sm font-semibold">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl bg-red-50 text-red-700 p-4 text-sm">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                                Email
                            </label>

                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="nama@email.com"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm font-bold text-slate-700">
                                    Password
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm font-semibold text-emerald-700 hover:underline">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="Masukkan password"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600">
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember"
                                class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                            Ingat saya
                        </label>

                        <button type="submit"
                            class="w-full bg-emerald-700 text-white py-3 rounded-xl font-bold hover:bg-emerald-800 transition">
                            Masuk
                        </button>
                    </form>

                    <p class="text-center text-sm text-slate-500 mt-6">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>