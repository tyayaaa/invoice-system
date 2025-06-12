<?php
require __DIR__ . '/../../auth.php';
include(__DIR__ . "/../../config/config.php");

// Cek apakah ada ID yang dikirim lewat URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus produk berdasarkan ID
    $query = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        // Redirect dengan notifikasi sukses
        header("Location: users.php?delete=success");
        exit;
    } else {
        // Redirect dengan notifikasi error
        header("Location: users.php?delete=error");
        exit;
    }
} else {
    // Jika tidak ada ID, kembali tanpa melakukan apa-apa
    header("Location: users.php");
    exit;
}
