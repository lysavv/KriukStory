<?php

// Konfigurasi Database
$db_host = 'localhost';
$db_user = 'root'; // User default XAMPP
$db_pass = ''; // Password default XAMPP (kosong)
$db_name = 'kriukstory_db'; // Nama database yang dibuat dari file .sql

// Membuat Koneksi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set karakter set
mysqli_set_charset($koneksi, "utf8mb4");

?>
