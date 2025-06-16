<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . "/../../config/config.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID tidak diberikan']);
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    echo json_encode(['error' => 'Invoice tidak ditemukan']);
    exit;
}

// Ambil detail produk juga berdasarkan ID invoice (bukan invoice_id string)
$stmt2 = $mysqli->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmt2->bind_param("i", $invoice['id']);  // bind integer, bukan string
$stmt2->execute();
$result2 = $stmt2->get_result();

$items = [];
while ($row = $result2->fetch_assoc()) {
    $items[] = $row;
}

// Gabungkan data
$invoice['items'] = $items;

header('Content-Type: application/json');
echo json_encode($invoice);
