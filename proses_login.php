<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Validasi
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email dan password wajib diisi.";
        header("Location: login.php");
        exit();
    }

    // Cari user berdasarkan email
    $sql = "SELECT id, username, password, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Password benar, buat session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan berdasarkan role
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // Password salah
            $_SESSION['error_message'] = "Email atau password salah.";
            header("Location: login.php");
            exit();
        }
    } else {
        // User tidak ditemukan
        $_SESSION['error_message'] = "Email atau password salah.";
        header("Location: login.php");
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}
?>
