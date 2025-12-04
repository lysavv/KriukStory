<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'kontak';
    include 'header.php'; 
    ?>

    <main>
        <section class="page-title">
            <div class="container">
                <h2>Hubungi Kami</h2>
                <p>Ada pertanyaan, saran, atau sekadar ingin menyapa? Kami siap mendengarkan!</p>
            </div>
        </section>

        <section class="contact-section-new">
            <div class="container">
                <div class="contact-grid-new">
                    <div class="contact-info-new">
                        <h3>Info Kontak</h3>
                        <p><strong>WhatsApp:</strong> <a href="https://wa.me/6281234567890">+62 812-3456-7890</a></p>
                        <p><strong>Email:</strong> <a href="mailto:info@kriukstory.com">info@kriukstory.com</a></p>
                        <p><strong>Alamat:</strong><br>Jl. Cemilan Enak No. 123<br>Kota Renyah, 54321</p>
                    </div>
                    <div class="contact-form-new">
                        <h3>Kirim Pesan Langsung</h3>
                        <form id="contactForm" action="proses_kontak.php" method="POST" class="form-new">
                            <?php
                            if (isset($_SESSION['contact_message'])) {
                                echo '<div class="status-message">' . htmlspecialchars($_SESSION['contact_message']) . '</div>';
                                unset($_SESSION['contact_message']);
                            }
                            ?>
                            <div class="form-group-new">
                                <label for="nama">Nama Anda</label>
                                <input type="text" id="nama" name="nama" required>
                            </div>
                            <div class="form-group-new">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group-new">
                                <label for="pesan">Pesan Anda</label>
                                <textarea id="pesan" name="pesan" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn">Kirim Pesan</button>
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
    </script>

</body>
</html>
