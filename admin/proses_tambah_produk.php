<?php
include 'auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dasar
    if (!isset($_POST['name'], $_POST['description'], $_POST['price']) || !isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        die("Data tidak lengkap atau file gambar gagal di-upload.");
    }

    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $price = (float)$_POST['price'];

    // Proses upload gambar
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $file_name = time() . '-' . basename($_FILES["image"]["name"]);
    $target_file = $upload_dir . $file_name;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi file gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        die("File bukan gambar.");
    }
    if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        die("Hanya format JPG, JPEG, PNG & GIF yang diizinkan.");
    }

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = 'uploads/' . $file_name;

        // Buat query INSERT
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $image_path);

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Produk baru berhasil ditambahkan.";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan produk ke database.";
        }
    } else {
        $_SESSION['error_message'] = "Gagal meng-upload file gambar.";
    }

    header("Location: index.php");
    exit();

} else {
    header("Location: tambah_produk.php");
    exit();
}
?>
