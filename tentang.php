<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'tentang';
    include 'header.php'; 
    ?>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Cerita Kami</h2>
                <p>Lebih dari sekadar camilan, kami adalah cerita tentang rasa dan keluarga.</p>
            </div>
        </section>

        <section class="about-section-new">
            <div class="container">
                <div class="about-grid-new">
                    <div class="about-image-new">
                        <img src="https://via.placeholder.com/500x400.png?text=Dapur+Kami" alt="Dapur KriukStory">
                    </div>
                    <div class="about-text-new">
                        <h3>Dari Dapur Keluarga, Untuk Anda</h3>
                        <p>KriukStory lahir dari kecintaan kami dalam menciptakan camilan yang renyah dan penuh cita rasa. Berawal dari resep warisan di dapur kecil kami, setiap produk dibuat dengan sentuhan personal dan bahan-bahan alami pilihan, tanpa pengawet.</p>
                        <p>Kami percaya, camilan yang enak bisa membuat hari siapa saja menjadi lebih ceria. Itulah semangat yang kami bawa dalam setiap kemasan KriukStory untuk Anda nikmati.</p>
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
    </script>

</body>
</html>
