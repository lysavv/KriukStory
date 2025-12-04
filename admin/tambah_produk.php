<?php
include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Produk Baru</title>
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
                <h2>Tambah Produk Baru</h2>
                <p>Isi detail produk yang akan ditambahkan ke toko.</p>
            </div>

            <div class="form-container-admin">
                <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data" class="form-new">
                    
                    <div class="form-group-new">
                        <label for="name">Nama Produk</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group-new">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>

                    <div class="form-group-new">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" required>
                    </div>

                    <div class="form-group-new">
                        <label for="image">Upload Foto Produk</label>
                        <input type="file" id="image" name="image" required>
                        <small>Pastikan foto memiliki rasio 1:1 (persegi) untuk tampilan terbaik.</small>
                    </div>

                    <button type="submit" class="btn">Tambah Produk</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
