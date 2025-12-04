<?php
include 'auth_check.php';

// Cek apakah ID produk ada
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Ambil data produk dari database
$stmt = mysqli_prepare($koneksi, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="index.php" class="active">Manajemen Produk</a></li>
                <li><a href="pesanan.php">Manajemen Pesanan</a></li>
                <li><a href="../index.php" target="_blank">Lihat Website</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h2>Edit Produk</h2>
                <p>Mengubah detail untuk: <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
            </div>

            <div class="form-container-admin">
                <form action="proses_edit_produk.php" method="POST" enctype="multipart/form-data" class="form-new">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="form-group-new">
                        <label for="name">Nama Produk</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <div class="form-group-new">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div class="form-group-new">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>

                    <div class="form-group-new">
                        <label>Foto Saat Ini</label>
                        <div><img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="" width="150"></div>
                    </div>

                    <div class="form-group-new">
                        <label for="image">Upload Foto Baru (Opsional)</label>
                        <input type="file" id="image" name="image">
                        <small>Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>

                    <button type="submit" class="btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
