<?php
include '../koneksi.php';

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// 1. Query Menu Terlaris
$menu_query = "
    SELECT pr.nama AS nama_menu, COUNT(*) AS jumlah
    FROM pesanan_produk pp
    JOIN produk pr ON pp.id_produk = pr.id
    JOIN pesanan p ON pp.id_pesanan = p.id
    WHERE MONTH(p.tanggal) = ? AND YEAR(p.tanggal) = ?
    GROUP BY pr.nama
    ORDER BY jumlah DESC
    LIMIT 10
";

$stmt_menu = $koneksi->prepare($menu_query);
$stmt_menu->bind_param("ii", $bulan, $tahun);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();

$menu_data = [];
while ($row = $result_menu->fetch_assoc()) {
    $menu_data[] = [
        'menu' => $row['nama_menu'],
        'jumlah' => (int)$row['jumlah']
    ];
}

// 2. Query Pendapatan Harian
$pendapatan_query = "
    SELECT DATE(tanggal) AS tanggal, SUM(total_harga) AS total
    FROM pesanan
    WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    GROUP BY DATE(tanggal)
    ORDER BY tanggal ASC
";

$stmt_pendapatan = $koneksi->prepare($pendapatan_query);
$stmt_pendapatan->bind_param("ii", $bulan, $tahun);
$stmt_pendapatan->execute();
$result_pendapatan = $stmt_pendapatan->get_result();

$pendapatan_data = [];
while ($row = $result_pendapatan->fetch_assoc()) {
    $pendapatan_data[] = [
        'tanggal' => $row['tanggal'],
        'total' => (int)$row['total']
    ];
}

// Gabungkan semua data ke dalam satu array
$response = [
    'menu_terlaris' => $menu_data,
    'pendapatan_harian' => $pendapatan_data
];

header('Content-Type: application/json');
echo json_encode($response);
