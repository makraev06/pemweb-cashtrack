<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['show_splash'])) {
    header("Location: dashboard.php");
    exit;
}

$userName = $_SESSION['name'] ?? 'User';
unset($_SESSION['show_splash']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading | Sovereign Ledger</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <script>
        setTimeout(function () {
            window.location.replace("dashboard.php");
        }, 1600);
    </script>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --bg: #071611;
            --bg-soft: #0c2119;
            --card: rgba(255, 255, 255, 0.06);
            --border: rgba(255, 255, 255, 0.10);
            --text: #f8fafc;
            --muted: rgba(255, 255, 255, 0.68);
            --primary: #10b981;
            --primary-soft: rgba(16, 185, 129, 0.14);
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(16, 185, 129, 0.16), transparent 28%),
                linear-gradient(135deg, var(--bg), var(--bg-soft));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .panel {
            width: 100%;
            max-width: 520px;
            border-radius: 28px;
            background: var(--card);
            border: 1px solid var(--border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.28);
            padding: 32px;
            animation: fadeUp .5s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, #10b981, #34d399);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.25);
        }

        .brand-icon .material-symbols-outlined {
            color: white;
            font-size: 28px;
        }

        .brand-text h1 {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand-text p {
            margin: 4px 0 0;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-soft);
            color: #d1fae5;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .headline {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(2rem, 5vw, 2.8rem);
            line-height: 1.05;
            letter-spacing: -0.04em;
        }

        .headline span {
            color: #d1fae5;
        }

        .desc {
            margin: 14px 0 26px;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
        }

        .progress-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.82);
            margin-bottom: 10px;
        }

        .progress {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            overflow: hidden;
        }

        .progress-fill {
            width: 78%;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #10b981, #6ee7b7);
            animation: loadBar 1.2s ease forwards;
        }

        .footer-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            color: #d1fae5;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes loadBar {
            from {
                width: 0%;
            }

            to {
                width: 78%;
            }
        }
    </style>
</head>

<body>
    <div class="panel">
        <div class="brand">
            <div class="brand-icon">
                <span class="material-symbols-outlined">account_balance</span>
            </div>
            <div class="brand-text">
                <h1>Sovereign Ledger</h1>
                <p>Financial Control Center</p>
            </div>
        </div>

        <div class="badge">
            <span class="material-symbols-outlined" style="font-size:16px;">verified_user</span>
            Secure Login
        </div>

        <h2 class="headline">
            Welcome back, <span><?= htmlspecialchars($userName); ?></span>
        </h2>

        <p class="desc">
            Menyiapkan dashboard dan memuat data keuangan Anda.
        </p>

        <div class="progress-label">
            <span>Preparing workspace</span>
            <span>78%</span>
        </div>

        <div class="progress">
            <div class="progress-fill"></div>
        </div>

        <div class="footer-row">
            <div class="spinner"></div>
            <span>Redirecting to dashboard...</span>
        </div>
    </div>
</body>

</html>