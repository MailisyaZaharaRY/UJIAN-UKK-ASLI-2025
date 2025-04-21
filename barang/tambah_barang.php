<?php
include 'db.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses tambah barang
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan amankan input dari user
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok     = $_POST['stok'];
    $harga    = $_POST['harga'];
    $tanggal  = mysqli_real_escape_string($conn, $_POST['tanggal']);

    // Validasi backend
    if (!is_numeric($stok) || $stok < 0) {
        die("❌ Error: Stok harus berupa angka dan tidak boleh negatif.");
    }

    if (!is_numeric($harga) || $harga < 0) {
        die("❌ Error: Harga harus berupa angka dan tidak boleh negatif.");
    }

    // Simpan ke database
    $sql = "INSERT INTO Barang (NamaBarang, KategoriID, Stok, Harga, TanggalMasuk) 
            VALUES ('$nama', '$kategori', '$stok', '$harga', '$tanggal')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=Barang berhasil ditambahkan");
        exit();
    } else {
        echo "❌ Error saat menyimpan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
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
            color: #6c5ce7;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #6c5ce7;
            border: none;
            border-radius: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4 text-center"><i class="bi bi-plus-circle"></i> Tambah Barang</h2>
        <form method="post" novalidate>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                    $result = $conn->query("SELECT ID, NamaKategori FROM Kategori");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['ID']}'>{$row['NamaKategori']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required min="0" step="1">
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required min="0" step="100">
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">💾 Tambah Barang</button>
                <a href="index.php" class="btn btn-secondary">⬅️ Kembali</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
