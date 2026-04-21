<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$filter_jenis = $_GET['jenis'] ?? '';
$filter_start = $_GET['start_date'] ?? '';
$filter_end = $_GET['end_date'] ?? '';

// nama file otomatis
$filename = "laporan_transaksi_" . date('Y-m-d') . ".csv";

// header download
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$filename");

$output = fopen('php://output', 'w');

// header kolom
fputcsv($output, ['Tanggal', 'Jenis', 'Jumlah', 'Keterangan']);

// query dasar
$sql = "SELECT * FROM transactions WHERE user_id = $user_id";

// filter jenis
if (!empty($filter_jenis)) {
    $sql .= " AND jenis = '$filter_jenis'";
}

// filter tanggal
if (!empty($filter_start)) {
    $sql .= " AND tanggal >= '$filter_start'";
}

if (!empty($filter_end)) {
    $sql .= " AND tanggal <= '$filter_end'";
}

$sql .= " ORDER BY tanggal DESC";

$query = mysqli_query($conn, $sql);

// isi data
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['tanggal'],
        ucfirst($row['jenis']),
        number_format($row['jumlah'], 0, ',', '.'),
        $row['keterangan']
    ]);
}

fclose($output);
exit;