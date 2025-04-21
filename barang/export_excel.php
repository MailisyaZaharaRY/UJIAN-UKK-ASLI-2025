<?php
// Import library PhpSpreadsheet
require 'vendor/autoload.php';  // Pastikan path ke autoload.php sesuai dengan tempat file composer berada

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include 'db.php';

// Query untuk mengambil data barang
$sql = "SELECT Barang.*, Kategori.NamaKategori FROM Barang 
        LEFT JOIN Kategori ON Barang.KategoriID = Kategori.ID";
$result = $conn->query($sql);

// Membuat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menambahkan header kolom
$sheet->setCellValue('A1', 'Nama Barang');
$sheet->setCellValue('B1', 'Kategori');
$sheet->setCellValue('C1', 'Stok');
$sheet->setCellValue('D1', 'Harga');
$sheet->setCellValue('E1', 'Tanggal Masuk');

// Menambahkan data barang ke dalam spreadsheet
$row = 2;  // Mulai dari baris kedua
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['NamaBarang']);
    $sheet->setCellValue('B' . $row, $data['NamaKategori']);
    $sheet->setCellValue('C' . $row, $data['Stok']);
    $sheet->setCellValue('D' . $row, $data['Harga']);
    $sheet->setCellValue('E' . $row, $data['TanggalMasuk']);
    $row++;
}

// Membuat file Excel dan mengunduhnya
$writer = new Xlsx($spreadsheet);
$filename = 'Daftar_Barang_' . date('Y-m-d_H-i-s') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
