<?php
session_start();

// Validasi data yang diterima dari request
if (isset($_POST['id'], $_POST['nama'], $_POST['harga'], $_POST['image'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_POST['image']; // path gambar produk

    // Cek apakah keranjang sudah ada
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Jika produk sudah ada di keranjang, tambahkan jumlah
    if (isset($_SESSION['keranjang'][$id])) {
        $_SESSION['keranjang'][$id]['jumlah'] += 1;
    } else {
        // Jika belum ada, tambahkan produk ke keranjang
        $_SESSION['keranjang'][$id] = [
            'id' => $id,
            'nama' => $nama,
            'harga' => $harga,
            'jumlah' => 1,
            'image' => $gambar
        ];
    }

    // Hitung jumlah total item di keranjang
    $jumlah_item = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $jumlah_item += $item['jumlah'];
    }

    // Kirim response JSON untuk digunakan oleh AJAX
    echo json_encode([
        'status' => 'success',
        'message' => 'Item berhasil ditambahkan ke keranjang',
        'cart_count' => $jumlah_item
    ]);
} else {
    // Jika data tidak lengkap
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak lengkap'
    ]);
}

exit();
