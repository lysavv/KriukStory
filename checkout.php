<?php
session_start();

// Proteksi halaman: Wajib login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Anda harus login untuk mengakses halaman checkout.";
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

// Jika keranjang kosong, tidak ada yang bisa di-checkout
if (empty($_SESSION['cart'])) {
    header('Location: produk.php');
    exit();
}

// Ambil detail produk dari database berdasarkan ID di keranjang
$cart_products = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    // Buat placeholder untuk query SQL
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));

    $sql = "SELECT id, name, price, image FROM products WHERE id IN ($placeholders)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$product_ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products_in_db = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products_in_db[$row['id']] = $row;
    }

    // Hitung total dan siapkan data untuk ditampilkan
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($products_in_db[$product_id])) {
            $product = $products_in_db[$product_id];
            $subtotal = $product['price'] * $quantity;
            $total_price += $subtotal;
            $cart_products[] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'checkout';
    include 'header.php'; 
    ?>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Keranjang Belanja & Checkout</h2>
                <p>Periksa kembali pesanan Anda sebelum menyelesaikan transaksi.</p>
            </div>
        </section>

        <section class="checkout-section">
            <div class="container">
                <div class="checkout-grid">
                    <div class="cart-details">
                        <h3>Detail Pesanan</h3>
                        <?php if (empty($cart_products)): ?>
                            <p>Keranjang Anda kosong.</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_products as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="product-info-cell">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                                        <span>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" data-id="<?php echo $item['id']; ?>">
                                            </td>
                                            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                            <td><button class="remove-item" data-id="<?php echo $item['id']; ?>">&times;</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="cart-total">
                                <strong>Total:</strong>
                                <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="customer-details">
                        <h3>Data Penerima</h3>
                        <form id="checkoutForm" action="proses_pemesanan.php" method="POST" class="form-new">
                            <div class="form-group-new">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" required>
                            </div>
                            <div class="form-group-new">
                                <label for="whatsapp">Nomor WhatsApp</label>
                                <input type="tel" id="whatsapp" name="whatsapp" required placeholder="cth: 081234567890">
                            </div>
                             <div class="form-group-new">
                                <label>Metode Pengiriman</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="delivery_method" value="Di Antar" checked> Di Antar</label>
                                    <label><input type="radio" name="delivery_method" value="Di Ambil"> Di Ambil Sendiri</label>
                                </div>
                            </div>
                            <div class="form-group-new">
                                <label for="kota">Kota/Kabupaten</label>
                                <select id="kota" name="kota">
                                    <option value="Banjarnegara">Banjarnegara</option>
                                    <option value="Wonosobo">Wonosobo</option>
                                    <option value="Purbalingga">Purbalingga</option>
                                    <option value="Lainnya">Luar Kota Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group-new">
                                <label for="alamat">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="4" required></textarea>
                            </div>

                            <div id="payment-info" class="payment-info-box">
                                <!-- Info pembayaran akan muncul di sini -->
                            </div>

                            <button type="submit" class="btn">Selesaikan Pesanan</button>
                        </form>
                    </div>
                </div>
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

        // Logika Halaman Checkout
        document.addEventListener('DOMContentLoaded', () => {
            const kotaSelect = document.getElementById('kota');
            const paymentInfoBox = document.getElementById('payment-info');

            function updatePaymentInfo() {
                const selectedCity = kotaSelect.value;
                let infoText = '';

                if (selectedCity === 'Lainnya') {
                    infoText = '<strong>Pembayaran via Transfer Bank.</strong><br>Silakan selesaikan pesanan, lalu hubungi Admin via WhatsApp untuk mendapatkan nomor rekening dan konfirmasi ongkir.';
                } else {
                    infoText = '<strong>Pembayaran di Tempat (Cash on Delivery).</strong><br>Anda bisa membayar dengan uang tunai saat pesanan tiba.';
                }
                paymentInfoBox.innerHTML = infoText;
            }

            updatePaymentInfo();
            kotaSelect.addEventListener('change', updatePaymentInfo);

            // Fungsi untuk mengirim data ke cart_logic
            function updateCart(action, productId, quantity = 1) {
                fetch('cart_logic.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=${action}&product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Muat ulang halaman untuk melihat perubahan
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal memproses permintaan.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            // Event listener untuk input kuantitas
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', (e) => {
                    const productId = e.target.getAttribute('data-id');
                    const quantity = e.target.value;
                    updateCart('update', productId, quantity);
                });
            });

            // Event listener untuk tombol hapus
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', (e) => {
                    if (confirm('Yakin ingin menghapus item ini dari keranjang?')) {
                        const productId = e.target.getAttribute('data-id');
                        updateCart('remove', productId);
                    }
                });
            });
        });
    </script>

</body>
</html>
