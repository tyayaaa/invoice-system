<?php
require __DIR__ . '/../../auth.php';
include(__DIR__ . "/../../config/config.php");

// Cek apakah form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $product_price = $_POST['product_price'];
    $qty = $_POST['qty'];

    // Validasi sederhana (opsional, bisa ditambah)
    if ($id && $product_name && $product_desc && is_numeric($product_price) && is_numeric($qty)) {
        // Query update
        $query = "UPDATE products SET 
                    product_name = ?, 
                    product_desc = ?, 
                    product_price = ?, 
                    qty = ? 
                WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ssdii", $product_name, $product_desc, $product_price, $qty, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Sukses
            header("Location: products.php?update=success");
            exit;
        } else {
            // Gagal
            header("Location: products.php?update=fail");
            exit;
        }
    } else {
        // Data tidak valid
        header("Location: products.php?update=invalid");
        exit;
    }
} else {
    // Akses langsung ditolak
    header("Location: products.php");
    exit;
}
