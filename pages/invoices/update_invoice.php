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

    $query = "UPDATE invoices 
            SET invoice_date = '$invoice_date', customer_id = $customer_id, status = '$status' 
            WHERE id = $id";

    if (mysqli_query($mysqli, $query)) {
        header("Location: invoices.php?update=success");
        exit;
    } else {
        echo "Error updating invoice: " . mysqli_error($mysqli);
    }
} else {
    echo "Invalid data. Please fill in all required fields.";
}
