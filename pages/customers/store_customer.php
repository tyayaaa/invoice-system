<?php
require __DIR__ . '/../../auth.php';
// Koneksi ke database
include(__DIR__ . "/../../config/config.php");

// Ambil data dari form
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone        = $_POST['phone'] ?? 0;

// Validasi sederhana
if (empty($name) || $phone <= 0) {
    die("Data tidak valid. Pastikan semua kolom telah diisi dengan benar.");
}

// Simpan ke database
$stmt = $mysqli->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $phone);
$stmt->execute();
$stmt->close();

// Redirect kembali ke halaman produk dengan notifikasi sukses (opsional)
header("Location: customers.php?success=1");
exit;
