<?php
require __DIR__ . '/../../auth.php';
// Koneksi ke database
include(__DIR__ . "/../../config/config.php");

// Ambil data dari form
$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$email        = $_POST['email'] ?? '';
$phone          = $_POST['phone'] ?? '';
$password          = $_POST['password'] ?? '';

// Validasi sederhana
if (empty($name) || empty($username) || empty($password)) {
    die("Data tidak valid. Pastikan semua kolom telah diisi dengan benar.");
}

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Simpan ke database
$stmt = $mysqli->prepare("INSERT INTO users (name, username, email, phone, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $username, $email, $phone, $password);
$stmt->execute();
$stmt->close();

// Redirect kembali ke halaman produk dengan notifikasi sukses (opsional)
header("Location: users.php?success=1");
exit;
