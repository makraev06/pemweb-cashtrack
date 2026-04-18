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
                    "primary-fixed-dim": "#71dba6",
                    "surface-bright": "#f7f9fc",
                    "on-error-container": "#93000a",
                    "on-tertiary-fixed-variant": "#7d2a2a",
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
            "FILL"0,
            "wght"400,
            "GRAD"0,
            "opsz"24;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
    }
    </style>
</head>

<body
    class="bg-surface text-on-surface font-body min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    <!-- Subtle Financial-Themed Background Illustration -->
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
        <img class="w-full h-full object-cover"
            data-alt="Abstract complex network of interconnected lines and data nodes representing global financial digital ledgers in deep emerald and silver tones"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAT7o5dTYlUDBzCRtYCiLHoI6zBCK6yT4ly0V0-esA_CPRoqRs8QSNeEwhIXDwmbLunpX4cvBjIeauqzGLTGEsNRjsQYu7FZ91cX-Ywu1etHXrL1A_tkvQbdSOFv1riIDYpCBdHMuGAvVlpjLiY9Z5rWnlZe1HnNl5dzQCO88sCbQ28Ifjx_wZSKoSTrVZJbq25cLtnD60IirY0O0SpUp9lUdQWvfx8ZKRbiK-khBp0mYwtGG2TQH70NdSa-QBn5takzRhdkj8hHjgt" />
    </div>
    <!-- Background Gradient Glows -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/10 rounded-full blur-[100px] z-0"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-[100px] z-0"></div>
    <main class="relative z-10 w-full max-w-md px-6">
        <!-- Logo Section -->
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-container rounded-xl shadow-lg mb-4">
                <span class="material-symbols-outlined text-white text-3xl"
                    data-icon="account_balance">account_balance</span>
            </div>
            <h1 class="font-headline text-3xl font-bold tracking-tight text-on-surface">
                The Sovereign Ledger
            </h1>
            <p class="font-body text-on-surface-variant mt-2">
                Institutional Grade Financial Management
            </p>
        </div>

        <!-- Error Message -->
        <?php if (isset($_GET['error'])): ?>
        <p style="color:red; text-align:center;">Email atau password salah</p>
        <?php endif; ?>



        <!-- Login Card -->
        <form action="process/login_process.php" method="POST">

            <!-- EMAIL -->
            <div>
                <label class="block font-label text-label-sm font-semibold text-on-surface-variant mb-2">
                    EMAIL
                </label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">
                        mail
                    </span>

                    <!-- INPUT EMAIL -->
                    <input
                        class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg"
                        name="email" type="email" placeholder="Enter your email" required />
                </div>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block font-label text-label-sm font-semibold text-on-surface-variant">
                    PASSWORD
                </label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">
                        lock
                    </span>

                    <!-- INPUT PASSWORD -->
                    <input
                        class="w-full pl-10 pr-12 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-lg"
                        name="password" type="password" placeholder="Enter your password" required />
                </div>
            </div>

            <!-- BUTTON -->
            <button
                class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-semibold rounded-lg"
                type="submit">
                Login
            </button>
        </form>

        <!-- Social Login / Alternative -->
        <div class="mt-8 relative flex items-center justify-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-outline-variant/20"></div>
            </div>
            <span class="relative px-4 bg-white/0 font-label text-label-sm text-outline">OR AUTHORIZE WITH</span>
        </div>
        <div class="mt-6 flex gap-4">
            <button
                class="flex-1 py-2.5 flex items-center justify-center gap-2 bg-surface-container-high rounded-lg hover:bg-surface-dim transition-colors">
                <span class="material-symbols-outlined text-lg" data-icon="key">key</span>
                <span class="text-sm font-medium">SSO</span>
            </button>
            <button
                class="flex-1 py-2.5 flex items-center justify-center gap-2 bg-surface-container-high rounded-lg hover:bg-surface-dim transition-colors">
                <span class="material-symbols-outlined text-lg" data-icon="fingerprint">fingerprint</span>
                <span class="text-sm font-medium">Biometric</span>
            </button>
        </div>
        <!-- Footer Links -->
        <p class="text-center mt-8 text-on-surface-variant text-sm">
            New to the ledger?
            <a class="font-semibold text-primary hover:underline ml-1" href="#">Request an invite</a>
        </p>
        <div class="mt-12 flex justify-center gap-6">
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Privacy Policy</a>
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Terms of
                Service</a>
            <a class="text-xs text-outline hover:text-on-surface-variant transition-colors" href="#">Support</a>
        </div>
    </main>
    <!-- Version Tag -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2">
        <span class="text-[10px] font-label font-bold tracking-widest text-outline uppercase">v2.4.1 SECURE
            ACCESS</span>
    </div>
</body>

</html>