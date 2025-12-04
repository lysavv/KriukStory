<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi Input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = "Semua field wajib diisi.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email tidak valid.";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error_message'] = "Password minimal harus 6 karakter.";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Konfirmasi password tidak cocok.";
        header("Location: register.php");
        exit();
    }

    // Cek apakah username atau email sudah ada
    $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt_check = mysqli_prepare($koneksi, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['error_message'] = "Username atau email sudah terdaftar.";
        header("Location: register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru ke database
    $sql_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan. Silakan coba lagi.";
        header("Location: register.php");
        exit();
    }

} else {
    header("Location: register.php");
    exit();
}
?>
