<?php
include(__DIR__ . "/../../config/config.php");

// Ambil data dari form
$invoice_id     = $_POST['invoice_id'] ?? null;
$invoice_date   = $_POST['invoice_date'] ?? null;
$status         = $_POST['report-type'] ?? null;
$customer_id    = $_POST['customer_id'] ?? null;
$product_names  = $_POST['product_name'] ?? [];
$product_qtys   = $_POST['product_qty'] ?? [];
$product_prices = $_POST['product_price'] ?? [];

// Validasi data penting
if (!$invoice_id || !$invoice_date || !$status || !$customer_id || empty($product_names)) {
    die("Data tidak lengkap.");
}

// Hitung ulang subtotal di server
$subtotal = 0;
$product_subtotals = [];

for ($i = 0; $i < count($product_names); $i++) {
    $qty = (int)$product_qtys[$i];
    $price = (float)$product_prices[$i];
    $sub = $qty * $price;
    $subtotal += $sub;
    $product_subtotals[] = $sub;
}

// START TRANSACTION
$mysqli->begin_transaction();

try {
    // Cek stok semua produk terlebih dahulu
    $checkStockStmt = $mysqli->prepare("SELECT qty FROM products WHERE product_name = ?");

    $product_in_products = [];

    for ($i = 0; $i < count($product_names); $i++) {
        $name = $product_names[$i];
        $qty = (int)$product_qtys[$i];

        $checkStockStmt->bind_param("s", $name);
        $checkStockStmt->execute();
        $checkStockStmt->bind_result($available_qty);

        if ($checkStockStmt->fetch()) {
            // Produk ada di tabel products
            if ($available_qty < $qty) {
                throw new Exception("Stok untuk produk '$name' tidak mencukupi. Tersedia: $available_qty, diminta: $qty");
            }
            $product_in_products[$name] = true;
        } else {
            // Produk manual, tidak ada di tabel products
            $product_in_products[$name] = false;
        }

        $checkStockStmt->reset();
    }
    $checkStockStmt->close();


    // Simpan invoice utama
    $stmt = $mysqli->prepare("INSERT INTO invoices (invoice_id, customer_id, invoice_date, subtotal, status) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Gagal prepare invoice: " . $mysqli->error);
    }
    $stmt->bind_param("sisds", $invoice_id, $customer_id, $invoice_date, $subtotal, $status);
    $stmt->execute();
    $invoice_db_id = $stmt->insert_id;
    $stmt->close();

    // Siapkan statement insert item & update stok
    $insertItemStmt = $mysqli->prepare("INSERT INTO invoice_items (invoice_id, product_name, qty, price, subtotal) VALUES (?, ?, ?, ?, ?)");
    $updateStockStmt = $mysqli->prepare("UPDATE products SET qty = qty - ? WHERE product_name = ?");

    for ($i = 0; $i < count($product_names); $i++) {
        $name = $product_names[$i];
        $qty = (int)$product_qtys[$i];
        $price = (float)$product_prices[$i];
        $sub = $product_subtotals[$i];

        // Simpan item invoice
        $insertItemStmt->bind_param("isidd", $invoice_db_id, $name, $qty, $price, $sub);
        $insertItemStmt->execute();

        if (!empty($product_in_products[$name])) {
            // Hanya kurangi stok jika produk ada di tabel products
            $updateStockStmt->bind_param("is", $qty, $name);
            $updateStockStmt->execute();
        }
    }

    $insertItemStmt->close();
    $updateStockStmt->close();

    // Jika semua berhasil, commit
    $mysqli->commit();

    // Redirect ke halaman invoices dengan pesan sukses
    header("Location: invoices.php?success=1");
    exit;
} catch (Exception $e) {
    $mysqli->rollback();
    die("Transaksi gagal: " . $e->getMessage());
}
