<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data pesanan dari database
$query = "SELECT * FROM pesanan WHERE id = $id";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Pesanan tidak ditemukan.");
}

$pesanan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran QRIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #fff;
        }
        .container {
            max-width: 500px;
            margin: auto;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .qr-image {
            width: 300px;
            height: 300px;
            margin: 20px 0;
            border: 1px solid #ccc;
        }
        .info {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .btn-kembali {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Silakan Scan QRIS untuk Pembayaran</h2>

    <div class="info">Nama Pelanggan: <strong><?= htmlspecialchars($pesanan['nama_pelanggan']) ?></strong></div>
    <div class="info">Total Bayar: <strong>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></strong></div>

    <!-- Ganti 'qris_sample.png' dengan QRIS asli -->
    <img src="image/qris.png" alt="WARKOP DOEADJAMAN " class="qr-image">

    <div class="info">Tanggal: <?= date("d M Y H:i", strtotime($pesanan['tanggal'])) ?></div>

    <a href="index.php" class="btn-kembali">Kembali ke Beranda</a>
</div>
</body>
</html>
