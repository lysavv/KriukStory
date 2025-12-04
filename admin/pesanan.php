<?php
include 'auth_check.php';

// Ambil semua pesanan, diurutkan dari yang terbaru
// Join dengan tabel users untuk mendapatkan nama pelanggan
$sql = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC";
$result = mysqli_query($koneksi, $sql);

$statuses = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Pesanan</title>
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
                <h2>Manajemen Pesanan</h2>
                <p>Kelola semua pesanan yang masuk dari pelanggan.</p>
            </div>

            <div id="status-update-response"></div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                            <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            <td>
                                <select class="status-select" data-order-id="<?php echo $order['id']; ?>">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo $status; ?>" <?php echo ($order['status'] == $status) ? 'selected' : ''; ?>>
                                            <?php echo $status; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><a href="detail_pesanan.php?id=<?php echo $order['id']; ?>">Lihat</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const orderId = this.dataset.orderId;
        const newStatus = this.value;
        
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            const responseDiv = document.getElementById('status-update-response');
            if (data.success) {
                responseDiv.className = 'status-message success';
                responseDiv.textContent = 'Status pesanan #' + orderId + ' berhasil diubah menjadi ' + newStatus;
            } else {
                responseDiv.className = 'status-message error';
                responseDiv.textContent = 'Gagal mengubah status: ' + (data.message || 'Error tidak diketahui.');
            }
            // Hilangkan pesan setelah beberapa detik
            setTimeout(() => { responseDiv.textContent = ''; responseDiv.className = ''; }, 3000);
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

</body>
</html>
