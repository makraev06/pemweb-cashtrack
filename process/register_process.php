<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['password_confirmation'] ?? '';

if ($name === '' || $email === '' || $password === '' || $passwordConfirmation === '') {
    header('Location: ../register.php?error=' . urlencode('Semua field wajib diisi.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../register.php?error=' . urlencode('Format email tidak valid.'));
    exit;
}

if (strlen($password) < 6) {
    header('Location: ../register.php?error=' . urlencode('Password minimal 6 karakter.'));
    exit;
}

if ($password !== $passwordConfirmation) {
    header('Location: ../register.php?error=' . urlencode('Konfirmasi password tidak cocok.'));
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$existingUser = mysqli_fetch_assoc($result);

if ($existingUser) {
    header('Location: ../register.php?error=' . urlencode('Email sudah terdaftar.'));
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$profilePhoto = '';

$stmt = mysqli_prepare($conn, 'INSERT INTO users (name, email, password, profile_photo) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $passwordHash, $profilePhoto);

if (mysqli_stmt_execute($stmt)) {
    header('Location: ../login.php?success=' . urlencode('Akun berhasil dibuat. Silakan login ulang.'));
    exit;
}

header('Location: ../register.php?error=' . urlencode('Gagal membuat akun. Silakan coba lagi.'));
exit;
