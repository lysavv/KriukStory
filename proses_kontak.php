<?php
// Memulai session untuk menyimpan status pesan
session_start();

// Sertakan file koneksi
include 'koneksi.php';

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil dan bersihkan data dari form
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($pesan) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_message'] = "Error: Harap isi semua field dengan data yang valid.";
        header("Location: kontak.php");
        exit();
    }

    // Masukkan data ke tabel `messages`
    $sql = "INSERT INTO messages (sender_name, sender_email, message) VALUES (?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);
    
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil
        $_SESSION['contact_message'] = "Pesan Anda telah berhasil terkirim. Terima kasih!";
    } else {
        // Jika gagal
        $_SESSION['contact_message'] = "Error: Gagal mengirim pesan Anda. Silakan coba lagi.";
    }

    // Tutup statement dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);

    // Redirect kembali ke halaman kontak
    header("Location: kontak.php");
    exit();

} else {
    // Jika halaman diakses tanpa submit form, kembalikan ke halaman utama
    header("Location: index.php");
    exit();
}
?>
