<?php
session_start();
include 'koneksi.php';

// 1. VALIDASI: Pastikan user sudah login dan keranjang tidak kosong
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    $_SESSION['error_message'] = "Anda harus login untuk menyelesaikan pesanan.";
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_SESSION['cart'])) {
    // Jika form tidak di-submit atau keranjang kosong, redirect ke produk
    header('Location: produk.php');
    exit();
}

// 2. AMBIL DATA: Ambil data dari form dan session
$user_id = $_SESSION['user_id'];
$delivery_method = mysqli_real_escape_string($koneksi, $_POST['delivery_method']);
$kota = mysqli_real_escape_string($koneksi, $_POST['kota']);
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$full_address = $alamat . ", " . $kota;

// Tentukan metode pembayaran berdasarkan kota
$payment_method = ($kota === 'Lainnya') ? 'Transfer Manual (via WA)' : 'Bayar di Tempat (Cash)';

// Validasi data form
if (empty($delivery_method) || empty($kota) || empty($alamat)) {
    $_SESSION['checkout_message'] = "Error: Harap lengkapi semua data pengiriman.";
    header("Location: checkout.php");
    exit();
}

// 3. PROSES DATABASE (TRANSAKSI)
mysqli_begin_transaction($koneksi);

try {
    // Ambil detail produk dari DB untuk harga final
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $sql_products = "SELECT id, price FROM products WHERE id IN ($placeholders)";
    $stmt_products = mysqli_prepare($koneksi, $sql_products);
    mysqli_stmt_bind_param($stmt_products, $types, ...$product_ids);
    mysqli_stmt_execute($stmt_products);
    $result_products = mysqli_stmt_get_result($stmt_products);
    
    $products_in_db = [];
    while ($row = mysqli_fetch_assoc($result_products)) {
        $products_in_db[$row['id']] = $row;
    }

    // Hitung total harga final
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($products_in_db[$product_id])) {
            $total_amount += $products_in_db[$product_id]['price'] * $quantity;
        }
    }

    // Masukkan data ke tabel `orders`
    $sql_order = "INSERT INTO orders (user_id, shipping_address, delivery_method, payment_method, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt_order = mysqli_prepare($koneksi, $sql_order);
    mysqli_stmt_bind_param($stmt_order, "isssd", $user_id, $full_address, $delivery_method, $payment_method, $total_amount);
    mysqli_stmt_execute($stmt_order);
    $order_id = mysqli_insert_id($koneksi);

    // Masukkan setiap item ke `order_items`
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)";
    $stmt_items = mysqli_prepare($koneksi, $sql_items);
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($products_in_db[$product_id])) {
            $price_per_item = $products_in_db[$product_id]['price'];
            mysqli_stmt_bind_param($stmt_items, "iiid", $order_id, $product_id, $quantity, $price_per_item);
            mysqli_stmt_execute($stmt_items);
        }
    }

    // Jika semua berhasil, commit
    mysqli_commit($koneksi);

    // 4. BERSIHKAN KERANJANG & REDIRECT
    unset($_SESSION['cart']);

    $_SESSION['message'] = "Pesanan Anda dengan ID #{$order_id} telah berhasil kami terima!";
    if ($kota === 'Lainnya') {
         $_SESSION['message'] .= "<br><strong>Segera hubungi Admin via WhatsApp untuk detail transfer dan ongkir.</strong>";
    }
    header("Location: sukses.php");
    exit();

} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($koneksi);
    $_SESSION['checkout_message'] = "Terjadi kesalahan database. Silakan coba lagi.";
    header("Location: checkout.php");
    exit();
}
?>
