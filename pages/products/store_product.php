<?php
require __DIR__ . '/../../auth.php';
// Koneksi ke database
include(__DIR__ . "/../../config/config.php");

// Ambil data dari form
$product_name = $_POST['product_name'] ?? '';
$product_desc = $_POST['product_desc'] ?? '';
$price        = $_POST['price'] ?? 0;
$qty          = $_POST['qty'] ?? 0;

// Validasi sederhana
if (empty($product_name) || $price <= 0 || $qty < 0) {
    die("Data tidak valid. Pastikan semua kolom telah diisi dengan benar.");
}

// Simpan ke database
$stmt = $mysqli->prepare("INSERT INTO products (product_name, product_desc, product_price, qty) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdi", $product_name, $product_desc, $price, $qty);
$stmt->execute();
$stmt->close();

// Redirect kembali ke halaman produk dengan notifikasi sukses (opsional)
header("Location: products.php?success=1");
exit;
