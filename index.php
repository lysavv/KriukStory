<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KriukStory - Renyah & Ceria</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font akan di-load dari style.css -->
</head>
<body>

    <?php 
    $active_page = 'index';
    include 'header.php'; 
    ?>

    <main>
        <section class="hero-new">
            <div class="container">
                <div class="hero-text">
                    <h1>Renyahnya Bikin Nagih!</h1>
                    <p>Camilan homemade kualitas premium, dibuat dengan bahan-bahan segar pilihan dan resep rahasia yang bikin harimu makin asyik.</p>
                    <a href="produk.php" class="btn btn-secondary">Lihat Semua Produk</a>
                </div>
                <div class="hero-slider">
                    <div class="slider-track">
                        <div class="slide"><img src="pisang.jpeg" alt="Keripik Pisang"></div>
                        <div class="slide"><img src="tempe.jpeg" alt="Keripik Tempe"></div>
                        <div class="slide"><img src="kripik2.jpeg" alt="Keripik Balado"></div>
                        <div class="slide"><img src="kripik.jpeg" alt="Keripik Singkong"></div>
                        <!-- Duplikat slide untuk loop yang mulus -->
                        <div class="slide"><img src="pisang.jpeg" alt="Keripik Pisang"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Keunggulan Section Baru -->
        <section class="unggulan-new">
            <div class="container">
                <h2>Kenapa Pilih KriukStory?</h2>
                <div class="unggulan-grid">
                    <div class="unggulan-item">
                        <h3>🏡 100% Homemade</h3>
                        <p>Setiap gigitan adalah hasil karya cinta dari dapur kami, menjamin rasa otentik.</p>
                    </div>
                    <div class="unggulan-item">
                        <h3>🌿 Bahan Terbaik</h3>
                        <p>Kami hanya memakai bahan alami dan segar tanpa pengawet buatan.</p>
                    </div>
                    <div class="unggulan-item">
                        <h3>🚚 Pengiriman Aman</h3>
                        <p>Pesananmu kami kemas dengan aman dan siap meluncur ke depan pintumu.</p>
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
        const navToggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('nav');

        navToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
            if (nav.classList.contains('active')) {
                navToggle.innerHTML = '&times;'; // Close Icon
            } else {
                navToggle.innerHTML = '&#9776;'; // Hamburger Icon
            }
        });
    </script>

</body>
</html>
