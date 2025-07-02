<?php
session_start();

// Cek apakah permintaan valid (POST + ada ID + aksi)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || empty($_POST['aksi'])) {
    // Jika permintaan tidak valid, kembalikan ke halaman keranjang
    header("Location: keranjang.php");
    exit();
}

$id = $_POST['id']; // Ambil ID produk
$aksi = $_POST['aksi']; // Ambil aksi tambah atau kurangi

// Pastikan ID adalah angka dan aksi valid
$id = intval($id); // Pastikan ID adalah integer
if ($id <= 0 || !in_array($aksi, ['tambah', 'kurangi'])) {
    header("Location: keranjang.php");
    exit();
}

// Pastikan item ada di dalam keranjang
if (isset($_SESSION['keranjang'][$id])) {
    switch ($aksi) {
        case 'tambah':
            // Jika aksi tambah, tambahkan jumlah produk di keranjang
            $_SESSION['keranjang'][$id]['jumlah'] += 1;
            break;

        case 'kurangi':
            // Jika aksi kurangi, kurangi jumlah produk di keranjang, jika lebih dari 1
            if ($_SESSION['keranjang'][$id]['jumlah'] > 1) {
                $_SESSION['keranjang'][$id]['jumlah'] -= 1;
            } else {
                // Jika jumlah 1, hapus item dari keranjang
                unset($_SESSION['keranjang'][$id]);
            }
            break;
    }
}

// Setelah aksi selesai, kembalikan ke halaman keranjang
header("Location: keranjang.php");
exit();
?>
