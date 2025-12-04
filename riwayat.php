<?php
session_start();

// Wajib login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Anda harus login untuk melihat riwayat pesanan.";
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$orders = [];

// Ambil semua order dari user yang login
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($order = mysqli_fetch_assoc($result)) {
    $order_id = $order['id'];
    $order_items = [];

    // Ambil item-item untuk setiap order
    $sql_items = "SELECT p.name as product_name, oi.quantity, oi.price_per_item FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
    $stmt_items = mysqli_prepare($koneksi, $sql_items);
    mysqli_stmt_bind_param($stmt_items, "i", $order_id);
    mysqli_stmt_execute($stmt_items);
    $result_items = mysqli_stmt_get_result($stmt_items);
    
    while ($item = mysqli_fetch_assoc($result_items)) {
        $order_items[] = $item;
    }
    $order['items'] = $order_items;
    $orders[] = $order;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'riwayat';
    include 'header.php'; 
    ?>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Riwayat Pesanan Anda</h2>
                <p>Semua transaksi Anda yang tersimpan rapi.</p>
            </div>
        </section>

        <section class="history-section">
            <div class="container">
                <?php if (empty($orders)): ?>
                    <div class="history-card empty">
                        <p>Anda belum memiliki riwayat pesanan. Yuk, mulai belanja!</p>
                        <a href="produk.php" class="btn">Lihat Produk</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="history-card">
                            <div class="history-header">
                                <div>
                                    <strong>Order ID: #<?php echo $order['id']; ?></strong>
                                    <span><?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></span>
                                </div>
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                            </div>
                            <div class="history-body">
                                <table>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo $item['quantity']; ?>)</td>
                                            <td>Rp <?php echo number_format($item['price_per_item'] * $item['quantity'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <div class="history-footer">
                                <span>Metode Pembayaran: <?php echo htmlspecialchars($order['payment_method']); ?></span>
                                <strong>Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 KriukStory. Dibuat dengan Penuh Rasa.</p>
        </div>
    </footer>

    <script>
        // Navigasi Mobile
        const navToggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('nav');
        navToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
            navToggle.innerHTML = nav.classList.contains('active') ? '&times;' : '&#9776;';
        });
    </script>

</body>
</html>
