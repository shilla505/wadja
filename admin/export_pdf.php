<?php
require_once '../vendor/autoload.php';
include '../koneksi.php';

use Dompdf\Dompdf;

$bulan = isset($_POST['bulan']) ? (int)$_POST['bulan'] : date('n');
$tahun = isset($_POST['tahun']) ? (int)$_POST['tahun'] : date('Y');

// Terima grafik dari form (base64 image)
$chart_pendapatan = $_POST['chart_pendapatan'] ?? '';
$chart_menu = $_POST['chart_menu'] ?? '';

// Fungsi sanitize base64 image agar aman di HTML
function sanitize_base64_img($img) {
    // Pastikan string dimulai dengan "data:image"
    if (strpos($img, 'data:image') === 0) {
        return $img;
    }
    return '';
}

// Sanitasi gambar
$chart_pendapatan = sanitize_base64_img($chart_pendapatan);
$chart_menu = sanitize_base64_img($chart_menu);

// Ambil data total pendapatan
$pendapatan_query = "
    SELECT SUM(total_harga) AS total
    FROM pesanan
    WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
";
$pendapatan_result = mysqli_query($koneksi, $pendapatan_query);
$pendapatan = mysqli_fetch_assoc($pendapatan_result);

// Ambil menu terlaris (top 10)
$menu_query = "
    SELECT pr.nama, SUM(pp.jumlah) AS jumlah
    FROM pesanan_produk pp
    JOIN produk pr ON pp.id_produk = pr.id
    JOIN pesanan p ON pp.id_pesanan = p.id
    WHERE MONTH(p.tanggal) = $bulan AND YEAR(p.tanggal) = $tahun
    GROUP BY pr.id, pr.nama
    ORDER BY jumlah DESC
    LIMIT 10
";
$menu_result = mysqli_query($koneksi, $menu_query);

// Buat konten HTML PDF
$html = "
    <h2 style='text-align:center;'>Laporan Pendapatan Bulan {$bulan}/{$tahun}</h2>
    <p>Total Pendapatan: <strong>Rp " . number_format($pendapatan['total'], 0, ',', '.') . "</strong></p>

    <h3>Grafik Pendapatan Harian</h3>";
if ($chart_pendapatan) {
    $html .= "<img src='{$chart_pendapatan}' style='width: 100%; max-width: 500px; margin-bottom:20px;'>";
} else {
    $html .= "<p><i>Grafik pendapatan tidak tersedia</i></p>";
}

$html .= "
    <h3>Grafik Menu Terlaris</h3>";
if ($chart_menu) {
    $html .= "<img src='{$chart_menu}' style='width: 100%; max-width: 500px; margin-bottom:20px;'>";
} else {
    $html .= "<p><i>Grafik menu tidak tersedia</i></p>";
}

$html .= "
    <h3>10 Menu Terlaris:</h3>
    <table border='1' cellpadding='5' cellspacing='0' width='100%' style='border-collapse: collapse;'>
        <thead style='background:#eee;'>
            <tr>
                <th style='width:5%;'>No</th>
                <th>Nama Menu</th>
                <th style='width:15%;'>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>";

$no = 1;
while ($row = mysqli_fetch_assoc($menu_result)) {
    $html .= "<tr>
                <td style='text-align:center;'>{$no}</td>
                <td>{$row['nama']}</td>
                <td style='text-align:center;'>{$row['jumlah']}</td>
              </tr>";
    $no++;
}

$html .= "</tbody></table>";

// Generate PDF
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_pendapatan_{$bulan}_{$tahun}.pdf", ["Attachment" => true]);
