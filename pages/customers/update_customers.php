<?php
require __DIR__ . '/../../auth.php';
include(__DIR__ . "/../../config/config.php");

// Cek apakah form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Validasi sederhana (opsional)
    if (!empty($id) && !empty($name) && !empty($email) && !empty($phone)) {
        // Query update
        $query = "UPDATE customers SET 
                    name = ?, 
                    email = ?, 
                    phone = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $id); // sesuaikan dengan tipe data

        if (mysqli_stmt_execute($stmt)) {
            // Sukses
            header("Location: customers.php?update=success");
            exit;
        } else {
            // Gagal
            header("Location: customers.php?update=fail");
            exit;
        }
    } else {
        // Data tidak valid
        header("Location: customers.php?update=invalid");
        exit;
    }
} else {
    // Akses langsung ditolak
    header("Location: customers.php");
    exit;
}
