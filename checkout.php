<?php
include 'koneksi.php';
session_start();

// Hitung total harga dari keranjang
$total_harga = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            background-color: #fff;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            background-color: #fff;
        }
        .back-icon {
            font-size: 20px;
            text-decoration: none;
            color: black;
            margin-right: auto;
        }
        .title {
            flex: 1;
            text-align: center;
            font-weight: bold;
        }
        .container {
            padding: 20px;
        }
        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        textarea {
            resize: none;
            height: 100px;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        .radio-group label {
            font-weight: normal;
        }
        .submit-btn {
            width: 100%;
            margin-top: 40px;
            padding: 14px;
            background-color: orange;
            color: white;
            border: none;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        .total-box {
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="javascript:history.back()" class="back-icon">&#8592;</a>
    <div class="title">Checkout</div>
    <div style="width: 24px;"></div>
</div>

<div class="container">
    <form action="proses_checkout.php" method="POST">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" required>

        <label>Metode Pembayaran</label>
        <div class="radio-group">
            <label><input type="radio" name="metode_pembayaran" value="kasir" required> Bayar di Kasir</label>
            <label><input type="radio" name="metode_pembayaran" value="qris" required> Qris</label>
        </div>

        <label for="catatan">Catatan</label>
        <textarea name="catatan" id="catatan" placeholder="Opsional..."></textarea>

        <div class="total-box">
            Total Harga: Rp <?= number_format($total_harga, 0, ',', '.') ?>
        </div>

        <input type="hidden" name="total_harga" value="<?= $total_harga ?>">

        <button type="submit" class="submit-btn">Pesan Sekarang</button>
    </form>
</div>

</body>
</html>
