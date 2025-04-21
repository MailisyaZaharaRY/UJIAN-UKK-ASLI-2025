<?php
include 'db.php';

// Ambil semua kategori untuk dropdown
$kategoriResult = $conn->query("SELECT * FROM Kategori");
$kategoriList = [];
if ($kategoriResult && $kategoriResult->num_rows > 0) {
    while ($row = $kategoriResult->fetch_assoc()) {
        $kategoriList[] = $row;
    }
}

// Pagination
$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Pencarian dan Filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$selectedKategori = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : '';

// Query barang
$sql = "SELECT Barang.*, Kategori.NamaKategori FROM Barang 
        LEFT JOIN Kategori ON Barang.KategoriID = Kategori.ID 
        WHERE (Barang.NamaBarang LIKE '%$search%' OR Kategori.NamaKategori LIKE '%$search%')";

if (!empty($selectedKategori)) {
    $sql .= " AND Barang.KategoriID = '$selectedKategori'";
}

$sql .= " LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);

// Hitung total data
$sqlCount = "SELECT COUNT(*) AS total FROM Barang 
             LEFT JOIN Kategori ON Barang.KategoriID = Kategori.ID 
             WHERE (Barang.NamaBarang LIKE '%$search%' OR Kategori.NamaKategori LIKE '%$search%')";

if (!empty($selectedKategori)) {
    $sqlCount .= " AND Barang.KategoriID = '$selectedKategori'";
}

$countResult = $conn->query($sqlCount);
$totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalRows / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7ff;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 40px;
        }
        .table th {
            background-color: #6c5ce7;
            color: white;
        }
        .btn-primary, .btn-success {
            border-radius: 20px;
        }
        .btn-warning, .btn-danger {
            border-radius: 10px;
        }
        .genz-heading {
            font-size: 2.2rem;
            font-weight: bold;
            color: #6c5ce7;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .genz-heading i {
            color: #fd79a8;
        }
        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-bar select,
        .search-bar input {
            min-width: 200px;
        }
        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.85rem;
            }
            .search-bar {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="genz-heading mb-4">
        <i class="bi bi-box-seam"></i> Daftar Barang
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div class="alert alert-<?= $_GET['status'] == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian & Filter -->
    <form method="GET" class="search-bar mb-4 d-flex flex-wrap align-items-center gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari Barang atau Kategori..." value="<?= htmlspecialchars($search) ?>">

        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategoriList as $k): ?>
                <option value="<?= $k['ID'] ?>" <?= ($selectedKategori == $k['ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['NamaKategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
    </form>

    <!-- Tombol Aksi -->
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="tambah_barang.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Barang</a>
        <a href="tambah_kategori.php" class="btn btn-success"><i class="bi bi-tags-fill"></i> Tambah Kategori</a>
    </div>

    <!-- Tabel Daftar Barang -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['NamaBarang']) ?></td>
                            <td><?= htmlspecialchars($row['NamaKategori']) ?></td>
                            <td><?= $row['Stok'] ?></td>
                            <td>Rp <?= number_format($row['Harga']) ?></td>
                            <td><?= $row['TanggalMasuk'] ?></td>
                            <td>
                                <a href="edit_barang.php?id=<?= $row['ID'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                <a href="hapus_barang.php?id=<?= $row['ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus barang ini?');"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>&kategori=<?= $selectedKategori ?>">&laquo;</a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&kategori=<?= $selectedKategori ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>&kategori=<?= $selectedKategori ?>">&raquo;</a>
            </li>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
