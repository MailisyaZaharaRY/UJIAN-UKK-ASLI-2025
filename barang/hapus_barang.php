<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus barang dari database
    $sql = "DELETE FROM Barang WHERE ID = $id";
    $conn->query($sql);
}

// Redirect ke halaman utama setelah penghapusan
header("Location: index.php");
?>
