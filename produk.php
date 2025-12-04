<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Kami - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'produk';
    include 'header.php'; 
    ?>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Produk Pilihan Kami</h2>
                <p>Temukan camilan favoritmu di sini. Semua renyah, semua istimewa!</p>
            </div>
        </section>

        <section class="product-section-new">
            <div class="container">
                <div class="product-grid-new">
                    <?php
                    include 'koneksi.php';
                    $sql = "SELECT * FROM products ORDER BY name ASC";
                    $result = mysqli_query($koneksi, $sql);

                    // --- TEMPORARY IMAGE MAPPING ---
                    // This is a temporary fix. For a permanent solution, 
                    // update the image paths in your database.
                    $image_map = [
                        'Keripik Singkong Balado' => 'kripik.jpeg',
                        'Pisang Crispy Cokelat' => 'pisang.jpeg',
                        'Keripik Tempe Sagu' => 'tempe.jpeg',
                        'Usus Crispy Original' => 'kripik2.jpeg' 
                    ];
                    // --------------------------------

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            // Use the mapped image if it exists, otherwise use the one from the DB
                            $image_url = isset($image_map[$row['name']]) ? $image_map[$row['name']] : $row['image'];
                    ?>
                            <div class="product-card-new">
                                <div class="product-image">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                    <p class="price">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                                    <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                                    <button class="btn add-to-cart" data-id="<?php echo $row['id']; ?>">+ Keranjang</button>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>Belum ada produk yang tersedia.</p>";
                    }
                    mysqli_close($koneksi);
                    ?>
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

        // Animasi Staggered
        window.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.product-card-new');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 100}ms`;
            });
        });

        // Logika Add to Cart
        const cartButtons = document.querySelectorAll('.add-to-cart');
        const cartCount = document.querySelector('.cart-count');

        function showToast(message) {
            const toast = document.createElement('div');
            toast.textContent = message;
            toast.className = 'toast-notification';
            document.body.appendChild(toast);

            // Animasikan toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100); // delay kecil untuk memicu transisi

            // Sembunyikan dan hapus toast setelah beberapa detik
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500); // cocokkan dengan durasi transisi CSS
            }, 2000); // tampilkan selama 2 detik
        }

        cartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                
                fetch('cart_logic.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=add&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartCount.textContent = data.item_count;
                        showToast('Produk ditambahkan ke keranjang!');
                    } else {
                        alert(data.message || 'Gagal menambahkan produk.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
            });
        });
    </script>

</body>
</html>
