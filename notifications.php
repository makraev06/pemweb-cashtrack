<?php
$notifications = [
    [
        "title" => "Data baru masuk",
        "message" => "Ada data baru yang perlu diperiksa.",
        "time" => "Baru saja",
        "icon" => "notifications",
        "is_read" => false
    ],
    [
        "title" => "Laporan diperbarui",
        "message" => "Laporan berhasil diperbarui.",
        "time" => "10 menit lalu",
        "icon" => "description",
        "is_read" => false
    ],
    [
        "title" => "Login berhasil",
        "message" => "Akun kamu baru saja digunakan untuk login.",
        "time" => "1 jam lalu",
        "icon" => "login",
        "is_read" => true
    ],
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-slate-50 text-slate-800">

    <main class="max-w-2xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Notifikasi</h1>
                    <p class="text-sm text-slate-500">Daftar aktivitas terbaru kamu</p>
                </div>

                <a href="javascript:history.back()"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-emerald-500 text-emerald-600 text-sm font-semibold hover:bg-emerald-50 transition">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </a>
            </div>

            <?php foreach ($notifications as $notif): ?>
                <div
                    class="p-5 border-b border-slate-100 hover:bg-slate-50 <?= !$notif['is_read'] ? 'bg-emerald-50/60' : ''; ?>">
                    <div class="flex gap-4">

                        <div
                            class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined">
                                <?= htmlspecialchars($notif['icon']); ?>
                            </span>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="font-semibold text-slate-800">
                                    <?= htmlspecialchars($notif['title']); ?>
                                </h2>

                                <?php if (!$notif['is_read']): ?>
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                                        Baru
                                    </span>
                                <?php endif; ?>
                            </div>

                            <p class="text-sm text-slate-600 mt-1">
                                <?= htmlspecialchars($notif['message']); ?>
                            </p>

                            <p class="text-xs text-slate-400 mt-2">
                                <?= htmlspecialchars($notif['time']); ?>
                            </p>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

</body>

</html>