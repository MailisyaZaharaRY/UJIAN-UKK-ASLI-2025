<?php
$host = "localhost";
$user = "root";
$pass = ""; // kosongkan kalau pakai XAMPP default
$db   = "gudang_barang";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
