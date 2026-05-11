<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {

        header("Location: ../login.php?error=Email belum terdaftar");
        exit;

    }

    if (!password_verify($password, $user['password'])) {

        header("Location: ../login.php?error=Password salah");
        exit;

    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['profile_photo'] = $user['profile_photo'] ?? '';
    $_SESSION['show_splash'] = true;

    header("Location: ../index.php");
    exit;

} else {
    header("Location: ../login.php");
    exit;
}
