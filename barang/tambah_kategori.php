<?php
include 'db.php';

// Proses tambah kategori
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kategori = $_POST['nama_kategori'];

    $sql = "INSERT INTO kategori (NamaKategori) VALUES ('$nama_kategori')";
    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman utama dengan pesan sukses
        header("Location: index.php?success=Kategori berhasil ditambahkan");
        exit();
    } else {
        // Redirect ke halaman utama dengan pesan error
        header("Location: index.php?success=Gagal menambahkan kategori");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7ff;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 60px;
            max-width: 600px;
        }
        .genz-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #6c5ce7;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-primary {
            border-radius: 20px;
        }
        .btn-secondary {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="genz-heading"><i class="bi bi-tags-fill"></i> Tambah Kategori</div>
    <div class="card p-4 shadow-sm">
        <form method="POST">
            <div class="mb-3">
                <label for="kategori" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="kategori" name="nama_kategori" required>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah</button>
                <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
