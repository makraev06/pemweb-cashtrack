<?php session_start(); ?>

<!doctype html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700&amp;family=Inter:wght@400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
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
                        secondary: "#416652",
                        "secondary-container": "#c0e9cf",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed": "#002113",
                        "inverse-surface": "#2d3133",
                        "on-primary": "#ffffff",
                        "surface-dim": "#d8dadd",
                        "on-primary-fixed": "#002113",
                        "on-tertiary-container": "#ffffff",
                        outline: "#6e7a71",
                        "on-tertiary": "#ffffff",
                        tertiary: "#9b403e",
                        "inverse-on-surface": "#eff1f4",
                        "on-background": "#191c1e",
                        "on-secondary-fixed": "#002113",
                        "surface-tint": "#006c47",
                        "surface-variant": "#e0e3e6",
                        "on-secondary": "#ffffff",
                        primary: "#006b47",
                        "surface-container": "#eceef1",
                        "surface-container-highest": "#e0e3e6",
                        "on-secondary-fixed-variant": "#294e3b",
                        error: "#ba1a1a",
                        "tertiary-fixed": "#ffdad7",
                        surface: "#f7f9fc",
                        background: "#f7f9fc",
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
                        "tertiary-fixed-dim": "#ffb3af",
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem",
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"],
                        label: ["Inter"],
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                "FILL" 0,
                "wght" 400,
                "GRAD" 0,
                "opsz" 24;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
        }

        body {
            transition: opacity 0.35s ease;
        }

        body.opacity-0 {
            opacity: 0;
        }
    </style>
</head>

<body
    class="bg-surface text-on-surface font-body min-h-screen flex flex-col items-center justify-center relative overflow-hidden opacity-0">
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
        <img class="w-full h-full object-cover"
            data-alt="Abstract complex network of interconnected lines and data nodes representing global financial digital ledgers in deep emerald and silver tones"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAT7o5dTYlUDBzCRtYCiLHoI6zBCK6yT4ly0V0-esA_CPRoqRs8QSNeEwhIXDwmbLunpX4cvBjIeauqzGLTGEsNRjsQYu7FZ91cX-Ywu1etHXrL1A_tkvQbdSOFv1riIDYpCBdHMuGAvVlpjLiY9Z5rWnlZe1HnNl5dzQCO88sCbQ28Ifjx_wZSKoSTrVZJbq25cLtnD60IirY0O0SpUp9lUdQWvfx8ZKRbiK-khBp0mYwtGG2TQH70NdSa-QBn5takzRhdkj8hHjgt" />
    </div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/10 rounded-full blur-[100px] z-0"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-[100px] z-0"></div>
    <main class="relative z-10 w-full max-w-md px-6 animate-fade-up">
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-container rounded-xl shadow-lg mb-4">
                <span class="material-symbols-outlined text-white text-3xl"
                    data-icon="account_balance">account_balance</span>
            </div>
            <h1 class="font-headline text-3xl font-bold tracking-tight text-on-surface">
                CashTrack
            </h1>
            <p class="font-body text-on-surface-variant mt-2">
                Daftar akun baru untuk mencatat keuangan Anda.
            </p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div
                class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-center text-sm font-medium text-red-600">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div
                class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-center text-sm font-medium text-emerald-700">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="process/register_process.php" method="POST" class="space-y-4">
            <div>
                <label class="block font-label text-label-sm font-semibold text-on-surface-variant mb-2">NAMA
                    LENGKAP</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">person</span>
                    <input
                        class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary"
                        name="name" type="text" placeholder="Masukkan nama lengkap" required />
                </div>
            </div>

            <div>
                <label class="block font-label text-label-sm font-semibold text-on-surface-variant mb-2">EMAIL</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">mail</span>
                    <input
                        class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary"
                        name="email" type="email" placeholder="Masukkan email" required />
                </div>
            </div>

            <div>
                <label
                    class="block font-label text-label-sm font-semibold text-on-surface-variant mb-2">PASSWORD</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">lock</span>
                    <input
                        class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary"
                        name="password" type="password" placeholder="Minimal 6 karakter" required />
                </div>
            </div>

            <div>
                <label class="block font-label text-label-sm font-semibold text-on-surface-variant mb-2">KONFIRMASI
                    PASSWORD</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">lock</span>
                    <input
                        class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary"
                        name="password_confirmation" type="password" placeholder="Ulangi password" required />
                </div>
            </div>

            <button
                class="w-full mt-2 py-4 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-semibold rounded-lg hover:shadow-lg hover:brightness-110 transition-all duration-200 active:scale-95 focus:outline-none focus:ring-4 focus:ring-primary/20"
                type="submit">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-8 text-on-surface-variant text-sm">
            Sudah punya akun?
            <a class="font-semibold text-primary hover:underline ml-1" href="login.php">Masuk di sini</a>
        </p>
        <div class="mt-12 flex justify-center gap-6">
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Privacy Policy</a>
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Terms of
                Service</a>
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Support</a>
        </div>
    </main>
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2">
        <span class="text-[10px] font-label font-bold tracking-widest text-outline uppercase">v2.4.1 SECURE
            ACCESS</span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.remove('opacity-0');

            var pageLinks = document.querySelectorAll('a[href="login.php"]');
            pageLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.body.classList.add('opacity-0');
                    setTimeout(function () {
                        window.location.href = link.href;
                    }, 250);
                });
            });
        });
    </script>
</body>

</html>