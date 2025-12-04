<?php
include 'auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Ambil data order utama
$sql_order = "SELECT o.*, u.username, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?";
$stmt_order = mysqli_prepare($koneksi, $sql_order);
mysqli_stmt_bind_param($stmt_order, "i", $order_id);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Ambil item-item pesanan
$sql_items = "SELECT p.name, oi.quantity, oi.price_per_item FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt_items = mysqli_prepare($koneksi, $sql_items);
mysqli_stmt_bind_param($stmt_items, "i", $order_id);
mysqli_stmt_execute($stmt_items);
$result_items = mysqli_stmt_get_result($stmt_items);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Detail Pesanan #<?php echo $order['id']; ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="index.php">Manajemen Produk</a></li>
                <li><a href="pesanan.php" class="active">Manajemen Pesanan</a></li>
                <li><a href="../index.php" target="_blank">Lihat Website</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h2>Detail Pesanan #<?php echo $order['id']; ?></h2>
                <a href="pesanan.php">&larr; Kembali ke semua pesanan</a>
            </div>

            <div class="order-details-grid">
                <div class="order-info-card">
                    <h4>Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Alamat Pengiriman:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>
                <div class="order-info-card">
                    <h4>Informasi Pesanan</h4>
                    <p><strong>Tanggal:</strong> <?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></p>
                    <p><strong>Metode Pengiriman:</strong> <?php echo htmlspecialchars($order['delivery_method']); ?></p>
                    <p><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                    <p><strong>Status:</strong> <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></p>
                </div>
            </div>

            <div class="table-container" style="margin-top: 2rem;">
                <h4>Item yang Dipesan</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = mysqli_fetch_assoc($result_items)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rp <?php echo number_format($item['price_per_item'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($item['price_per_item'] * $item['quantity'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Total Pesanan</td>
                            <td style="font-weight: bold;">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</body>
</html>
