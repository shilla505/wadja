<?php
session_start();

// Inisialisasi jumlah item
$jumlah_item = 0;

// Jika keranjang ada dan tidak kosong
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $jumlah_item += $item['jumlah'];
    }
}

// Output jumlah item sebagai teks biasa
echo $jumlah_item;
