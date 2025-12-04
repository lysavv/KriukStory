<?php
include 'auth_check.php'; // Cek otentikasi admin

// Ambil semua produk dari database
$result = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Produk</title>
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
                <div>
                    <h2>Manajemen Produk</h2>
                    <p>Di sini Anda bisa mengubah detail produk yang tampil di website.</p>
                </div>
                <a href="tambah_produk.php" class="btn-add">Tambah Produk Baru</a>
            </div>
            
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="status-message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="" width="80"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="edit_produk.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
