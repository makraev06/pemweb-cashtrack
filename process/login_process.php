<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // ambil user dari database
    $stmt = mysqli_prepare($conn, "SELECT id, name, email, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password === $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../dashboard.php");
        exit;

    } else {
        header("Location: ../login.php?error=1");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}