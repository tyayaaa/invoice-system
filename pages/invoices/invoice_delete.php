<?php
include(__DIR__ . "/../../config/config.php");

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']);

    // Hapus item invoice dulu
    $delete_items = "DELETE FROM invoice_items WHERE invoice_id = $invoice_id";
    mysqli_query($mysqli, $delete_items);

    // Hapus invoice utama
    $delete_invoice = "DELETE FROM invoices WHERE id = $invoice_id";

    if (mysqli_query($mysqli, $delete_invoice)) {
        header("Location: invoices.php?delete=success");
        exit;
    } else {
        echo "Gagal menghapus invoice: " . mysqli_error($mysqli);
    }
} else {
    echo "ID tidak ditemukan.";
}
