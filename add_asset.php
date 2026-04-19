<?php include 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Add Asset | The Sovereign Ledger</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary-container": "#456a56",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#005235",
                        "surface-container-high": "#e6e8eb",
                        "on-surface-variant": "#3e4942",
                        "primary-container": "#00875a",
                        "secondary": "#416652",
                        "secondary-container": "#c0e9cf",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed-dim": "#71dba6",
                        "surface-bright": "#f7f9fc",
                        "on-error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#7d2a2a",
                        "inverse-surface": "#2d3133",
                        "on-primary": "#ffffff",
                        "surface-dim": "#d8dadd",
                        "on-primary-fixed": "#002113",
                        "on-tertiary-container": "#ffffff",
                        "outline": "#6e7a71",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#9b403e",
                        "inverse-on-surface": "#eff1f4",
                        "on-background": "#191c1e",
                        "on-secondary-fixed": "#002113",
                        "surface-tint": "#006c47",
                        "surface-variant": "#e0e3e6",
                        "on-secondary": "#ffffff",
                        "primary": "#006b47",
                        "surface-container": "#eceef1",
                        "surface-container-highest": "#e0e3e6",
                        "on-secondary-fixed-variant": "#294e3b",
                        "error": "#ba1a1a",
                        "tertiary-fixed": "#ffdad7",
                        "surface": "#f7f9fc",
                        "background": "#f7f9fc",
                        "outline-variant": "#bdcac0",
                        "surface-container-low": "#f2f4f7",
                        "on-tertiary-fixed": "#410005",
                        "on-primary-container": "#ffffff",
                        "error-container": "#ffdad6",
                        "secondary-fixed-dim": "#a7d0b7",
                        "on-surface": "#191c1e",
                        "tertiary-container": "#ba5855",
                        "primary-fixed": "#8df7c1",
                        "secondary-fixed": "#c2ecd2",
                        "inverse-primary": "#71dba6",
                        "tertiary-fixed-dim": "#ffb3af"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"],
                        label: ["Inter"]
                    }
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-surface text-on-surface antialiased">
    <!-- SideNavBar -->
    <aside
        class="fixed left-0 top-0 h-full flex flex-col p-4 bg-slate-50 dark:bg-slate-900 h-screen w-64 border-r-0 shadow-[12px_0px_32px_rgba(25,28,30,0.04)] z-50">
        <div class="mb-8 px-2 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined"
                    style="font-variation-settings: 'FILL' 1;">account_balance</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-emerald-800 dark:text-emerald-100 tracking-tight">
                    Sovereign Ledger
                </h1>
                <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Institutional Grade</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-emerald-600 font-semibold tracking-tight hover:bg-slate-200/50 transition-colors rounded-lg group"
                href="dashboard.php">
                <span class="material-symbols-outlined group-hover:scale-110 transition-transform">dashboard</span>
                <span>Dashboard</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-emerald-600 font-semibold tracking-tight hover:bg-slate-200/50 transition-colors rounded-lg group"
                href="transaction.php">
                <span
                    class="material-symbols-outlined group-hover:scale-110 transition-transform">account_balance_wallet</span>
                <span>Transactions</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-emerald-700 font-bold bg-white shadow-sm rounded-lg translate-x-1 transition-transform tracking-tight"
                href="assets.php">
                <span class="material-symbols-outlined"
                    style="font-variation-settings: 'FILL' 1;">account_balance</span>
                <span>Assets</span>
            </a>
        </nav>

        <div class="mt-auto pt-4 border-t border-slate-200/50">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-500">person</span>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate"><?php echo $_SESSION['user_name']; ?></p>
                    <p class="text-xs text-slate-400 truncate">Authenticated User</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- TopNavBar -->
    <header
        class="sticky top-0 z-40 flex justify-between items-center px-8 h-16 ml-64 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-full max-w-md group">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-emerald-600 transition-colors">search</span>
                <input
                    class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-emerald-500/20 placeholder:text-slate-400 transition-all"
                    placeholder="Search asset records..." type="text" />
            </div>
        </div>

        <div class="flex items-center gap-6">
            <button class="relative text-slate-500 hover:text-emerald-500 transition-colors">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-emerald-600 rounded-full border-2 border-white"></span>
            </button>

            <div class="relative group">
                <button
                    class="p-2 rounded-lg text-slate-500 hover:text-emerald-500 hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </button>

                <div
                    class="absolute right-0 mt-3 w-44 bg-white border border-slate-200 rounded-xl shadow-lg opacity-0 invisible translate-y-2 scale-95 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:scale-100 transition-all duration-200 origin-top-right">
                    <a href="#" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Profile
                    </a>
                    <div class="border-t border-slate-200 my-1"></div>
                    <a href="process/logout.php"
                        class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-600">
                        Logout
                    </a>
                </div>
            </div>

            <div class="h-8 w-[1px] bg-slate-200"></div>

            <div class="flex items-center gap-3">
                <span class="text-lg font-bold text-slate-900">The Sovereign Ledger</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 min-h-[calc(100vh-128px)] p-8">
        <div class="max-w-5xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-10">
                <nav class="flex items-center gap-2 text-xs font-bold tracking-widest text-slate-400 uppercase mb-3">
                    <a class="hover:text-emerald-600" href="dashboard.php">Ledger</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <a class="hover:text-emerald-600" href="assets.php">Assets</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-emerald-700">New Entry</span>
                </nav>
                <h2 class="text-4xl font-bold text-on-background tracking-tight">Add Asset</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Form -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_12px_32px_rgba(25,28,30,0.04)]">
                        <form action="process/asset_store.php" method="POST" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-widest">
                                        Asset Name
                                    </label>
                                    <input name="nama_aset"
                                        class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-emerald-500/10 transition-all"
                                        placeholder="e.g. Laptop Office, Emas, Tabungan BCA" type="text" required />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-widest">
                                        Category
                                    </label>
                                    <input name="kategori"
                                        class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-emerald-500/10 transition-all"
                                        placeholder="e.g. elektronik, investasi, properti" type="text" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-widest">
                                        Asset Value
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                                        <input name="nilai"
                                            class="w-full bg-surface-container-low border-none rounded-lg py-3 pl-10 pr-4 focus:ring-2 focus:ring-emerald-500/10 transition-all font-['Manrope'] font-bold text-xl"
                                            placeholder="0" type="number" required />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-widest">
                                        Acquisition Date
                                    </label>
                                    <input name="tanggal_perolehan"
                                        class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-emerald-500/10 transition-all"
                                        type="date" required />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-widest">
                                    Description
                                </label>
                                <textarea name="deskripsi"
                                    class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-emerald-500/10 transition-all resize-none"
                                    placeholder="Short note about this asset..." rows="4"></textarea>
                            </div>

                            <div class="pt-6 flex items-center justify-end gap-4">
                                <a href="assets.php"
                                    class="px-8 py-3 rounded-lg text-emerald-700 font-bold hover:bg-surface-container-high transition-all">
                                    Cancel
                                </a>
                                <button
                                    class="px-10 py-3 bg-gradient-to-br from-primary to-primary-container text-white font-bold rounded-lg shadow-lg hover:shadow-emerald-900/10 active:scale-[0.98] transition-all"
                                    type="submit">
                                    Save Asset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Panel -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-emerald-900 text-white p-6 rounded-xl relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-white/10 transition-colors">
                        </div>
                        <p class="text-[0.6875rem] font-bold uppercase tracking-widest text-emerald-400 mb-6">
                            Asset Preview
                        </p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <span class="text-sm opacity-70">Estimated Value</span>
                                <span class="text-2xl font-bold font-['Manrope']">Rp0</span>
                            </div>
                            <div class="h-[1px] bg-white/10"></div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="opacity-70">Category</span>
                                    <span class="font-medium">Unassigned</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="opacity-70">Status</span>
                                    <span class="font-medium text-emerald-300">Ready to save</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-xl space-y-4">
                        <div class="flex items-center gap-3 text-emerald-700">
                            <span class="material-symbols-outlined">verified_user</span>
                            <h3 class="font-bold">Asset Guidance</h3>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Record each asset with a clear category and realistic valuation. This helps portfolio
                            summaries,
                            allocation analysis, and future reporting stay accurate.
                        </p>
                    </div>

                    <div class="rounded-xl overflow-hidden h-40 relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 to-emerald-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                            <p class="text-white text-sm font-medium leading-tight">
                                "A well-tracked asset is a well-protected value."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="ml-64 p-6 flex justify-between items-center bg-slate-50 border-t border-slate-100">
        <div class="flex items-center gap-4">
            <span class="text-xs text-slate-400">© 2024 The Sovereign Ledger. All rights reserved.</span>
        </div>
        <div class="flex items-center gap-6">
            <a class="text-xs text-slate-400 hover:text-slate-600 transition-colors" href="#">Privacy Policy</a>
            <a class="text-xs text-slate-400 hover:text-slate-600 transition-colors" href="#">Terms</a>
            <span class="text-xs text-slate-400 px-3 py-1 bg-slate-200/50 rounded-full">Version 2.4.1</span>
        </div>
    </footer>
</body>

</html>