<?php 
// Default active page to empty
if (!isset($active_page)) {
    $active_page = '';
}
?>
<header>
    <div class="container">
        <a href="index.php" class="logo">KriukStory</a>
        <button class="nav-toggle" aria-label="buka navigasi">&#9776;</button>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menu untuk user yang sudah login -->
                    <li><a href="index.php" class="<?php echo ($active_page == 'index') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="produk.php" class="<?php echo ($active_page == 'produk') ? 'active' : ''; ?>">Produk</a></li>
                    <li><a href="riwayat.php" class="<?php echo ($active_page == 'riwayat') ? 'active' : ''; ?>">Riwayat Pesanan</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- Menu untuk user publik -->
                    <li><a href="index.php" class="<?php echo ($active_page == 'index') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="produk.php" class="<?php echo ($active_page == 'produk') ? 'active' : ''; ?>">Produk</a></li>
                    <li><a href="login.php" class="<?php echo ($active_page == 'login') ? 'active' : ''; ?>">Login</a></li>
                    <li><a href="register.php" class="<?php echo ($active_page == 'register') ? 'active' : ''; ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="header-action">
             <a href="checkout.php" class="cart-icon">
                🛒
                <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
             </a>
        </div>
    </div>
</header>
