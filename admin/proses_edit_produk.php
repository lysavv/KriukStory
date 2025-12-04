<?php
include 'auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dasar
    if (!isset($_POST['product_id'], $_POST['name'], $_POST['description'], $_POST['price'])) {
        die("Data tidak lengkap.");
    }

    $product_id = (int)$_POST['product_id'];
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $price = (float)$_POST['price'];

    $new_image_path = null;

    // Cek jika ada file gambar baru yang di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/'; // Folder untuk menyimpan gambar, pastikan folder ini ada
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
            $new_image_path = 'uploads/' . $file_name;
        } else {
            die("Gagal meng-upload file.");
        }
    }

    // Buat query UPDATE
    if ($new_image_path) {
        // Jika ada gambar baru, update semua termasuk path gambar
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsi", $name, $description, $price, $new_image_path, $product_id);
    } else {
        // Jika tidak ada gambar baru, update selain gambar
        $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi", $name, $description, $price, $product_id);
    }

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Produk berhasil diperbarui.";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui produk.";
    }

    header("Location: index.php");
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>
