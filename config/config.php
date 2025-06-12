<?php
// DATABASE INFORMATION
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'labpresso');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', '');

// CONNECT TO THE DATABASE
try {
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
    if ($mysqli->connect_error) {
        throw new Exception("Koneksi database gagal: " . $mysqli->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
