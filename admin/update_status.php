<?php
include 'auth_check.php'; // Pastikan hanya admin yang bisa akses

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['order_id'], $_POST['status'])) {
        $order_id = (int)$_POST['order_id'];
        $status = mysqli_real_escape_string($koneksi, $_POST['status']);

        // Validasi status
        $allowed_statuses = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
        if (!in_array($status, $allowed_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid.']);
            exit();
        }

        // Query untuk update status
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal update database.']);
        }
        exit();
    }
}

// Jika data tidak lengkap atau metode salah
echo json_encode(['success' => false, 'message' => 'Invalid request.']);
?>
