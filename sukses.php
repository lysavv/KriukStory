<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="container">
            <a href="index.php" class="logo">KriukStory</a>
            <button class="nav-toggle" aria-label="buka navigasi">
                &#9776;
            </button>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="tentang.php">Tentang</a></li>
                    <li><a href="produk.php">Produk</a></li>
                    <li><a href="pemesanan.php">Pemesanan</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                </ul>
            </nav>
            <div class="header-action">
                 <a href="pemesanan.php" class="btn">Pesan Sekarang</a>
            </div>
        </div>
    </header>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Pesanan Diterima!</h2>
                <div class="success-message">
                    <?php
                        if (isset($_SESSION['message'])) {
                            echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
                            // Hapus session message agar tidak muncul lagi
                            unset($_SESSION['message']);
                        } else {
                            echo "<p>Terima kasih telah memesan!</p>";
                        }
                    ?>
                    <a href="produk.php" class="btn btn-secondary">Kembali ke Halaman Produk</a>
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
    </script>

</body>
</html>
