<?php
session_start();
$jumlah = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $jumlah += $item['jumlah'];
    }
}
echo $jumlah;
