<?php
session_start();

// Cek jika user belum login atau rolenya bukan admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Simpan pesan error
    $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
    // Redirect ke halaman login utama
    header("Location: ../login.php");
    exit();
}

// Sertakan file koneksi database untuk digunakan di halaman admin
include '../koneksi.php';
?>
