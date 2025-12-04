<?php
session_start();
include 'koneksi.php';

// Fungsi untuk menambahkan produk ke keranjang
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Jika produk sudah ada di keranjang, tambahkan jumlahnya
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Jika belum ada, tambahkan sebagai item baru
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Fungsi untuk mendapatkan jumlah total item di keranjang
function getCartItemCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}

// Logika utama untuk menangani aksi
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add' && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Validasi sederhana
        $sql = "SELECT id FROM products WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            addToCart($product_id, $quantity);
            echo json_encode(['success' => true, 'item_count' => getCartItemCount()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
        }
        exit;
    }

    // Aksi untuk mengubah jumlah item
    else if ($action === 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $quantity;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kuantitas tidak valid.']);
        }
        exit;
    }

    // Aksi untuk menghapus item
    else if ($action === 'remove' && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ada di keranjang.']);
        }
        exit;
    }
}

// Jika tidak ada aksi POST, mungkin bisa digunakan untuk debug atau keperluan lain
// Untuk saat ini, kita tidak melakukan apa-apa
?>
