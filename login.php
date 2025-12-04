<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KriukStory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php 
    $active_page = 'login';
    include 'header.php'; 
    ?>

    <main>
        <section class="auth-section">
            <div class="container">
                <div class="auth-form-container">
                    <h2>Login ke Akun Anda</h2>
                    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>

                    <form action="proses_login.php" method="POST" class="form-new">
                        <?php
                        if (isset($_SESSION['error_message'])) {
                            echo '<div class="status-message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                            unset($_SESSION['error_message']);
                        }
                        if (isset($_SESSION['success_message'])) {
                            echo '<div class="status-message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                            unset($_SESSION['success_message']);
                        }
                        ?>
                        <div class="form-group-new">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group-new">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn">Login</button>
                    </form>
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
