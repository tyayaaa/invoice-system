<?php
require __DIR__ . '/../../auth.php';
// Koneksi ke database
include(__DIR__ . "/../../config/config.php");

// Ambil data dari form
$id       = $_POST['id'] ?? '';
$name     = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi dasar
if (empty($id) || empty($name) || empty($username)) {
    die("Data tidak valid. Pastikan semua kolom penting telah diisi.");
}

// Update dengan atau tanpa password
if (!empty($password)) {
    // Jika password diisi, hash dan update
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $username, $email, $phone, $hashedPassword, $id);
} else {
    // Jika password tidak diisi, jangan ubah password
    $stmt = $mysqli->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $username, $email, $phone, $id);
}

$stmt->execute();
$stmt->close();

// Redirect kembali ke halaman users
header("Location: users.php?update=success");
exit;
