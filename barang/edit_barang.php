<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("ID barang tidak ditemukan.");
}

$id = $_GET['id'];
$query = "SELECT * FROM Barang WHERE ID = $id";
$result = $conn->query($query);
$barang = $result->fetch_assoc();

$kategoriQuery = "SELECT * FROM Kategori";
$kategoriResult = $conn->query($kategoriQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $tanggal = $_POST['tanggal'];

    $update = "UPDATE Barang SET 
                NamaBarang = '$nama', 
                KategoriID = '$kategori', 
                Stok = '$stok', 
                Harga = '$harga', 
                TanggalMasuk = '$tanggal' 
               WHERE ID = $id";

    if ($conn->query($update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7ff;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        h2 {
            color: #0984e3;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0984e3;
            border: none;
            border-radius: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4 text-center"><i class="bi bi-pencil-square"></i> Edit Barang</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama" class="form-control" value="<?= $barang['NamaBarang'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <?php while ($row = $kategoriResult->fetch_assoc()) : ?>
                        <option value="<?= $row['ID'] ?>" <?= $row['ID'] == $barang['KategoriID'] ? 'selected' : '' ?>>
                            <?= $row['NamaKategori'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $barang['Stok'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= $barang['Harga'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal" class="form-control" value="<?= $barang['TanggalMasuk'] ?>" required>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">⬅️ Kembali</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>