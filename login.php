<?php
session_start();
require './config/config.php';

// Ambil input dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input tidak kosong
if (empty($username) || empty($password)) {
    echo "Username dan Password wajib diisi!";
    exit;
}

// Query ke database untuk mencari user
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password (jika disimpan dengan hash)
    if (password_verify($password, $user['password'])) {
        // Login berhasil, simpan session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect ke halaman dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Password salah!";
    }
} else {
    echo "User tidak ditemukan!";
}
