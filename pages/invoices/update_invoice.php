<?php
include(__DIR__ . "/../../config/config.php");

// Validasi input wajib
if (
    isset($_POST['id'], $_POST['invoice_id'], $_POST['invoice_date'], $_POST['customer_id'], $_POST['status']) &&
    !empty($_POST['id']) && !empty($_POST['invoice_date']) && !empty($_POST['customer_id']) && !empty($_POST['status'])
) {
    $id = intval($_POST['id']);
    $invoice_id = mysqli_real_escape_string($mysqli, $_POST['invoice_id']); // optional: kalau readonly
    $invoice_date = mysqli_real_escape_string($mysqli, $_POST['invoice_date']);
    $customer_id = intval($_POST['customer_id']);
    $status = mysqli_real_escape_string($mysqli, $_POST['status']);

    // Update invoice utama
    $query = "UPDATE invoices SET invoice_date = '$invoice_date', customer_id = $customer_id, status = '$status' WHERE id = $id";
    mysqli_query($mysqli, $query);

    // Hapus item lama dulu
    mysqli_query($mysqli, "DELETE FROM invoice_items WHERE invoice_id = $id");

    // Tambah item baru
    if (isset($_POST['product_name'], $_POST['product_qty'], $_POST['product_price'])) {
        $names = $_POST['product_name'];
        $qtys = $_POST['product_qty'];
        $prices = $_POST['product_price'];

        for ($i = 0; $i < count($names); $i++) {
            $name = mysqli_real_escape_string($mysqli, $names[$i]);
            $qty = intval($qtys[$i]);
            $price = floatval($prices[$i]);
            $subtotal = $qty * $price;

            $insert = "INSERT INTO invoice_items (invoice_id, product_name, qty, price, subtotal) 
                VALUES ($id, '$name', $qty, $price, $subtotal)";
            mysqli_query($mysqli, $insert);
        }
    }

    header("Location: invoices.php?update=success");
    exit;
}
