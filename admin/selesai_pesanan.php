<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];

    // Update status menjadi selesai
    mysqli_query($koneksi, "UPDATE pesanan SET status='selesai' WHERE id = $id_pesanan");

    $_SESSION['notif'] = "Pesanan atas nama <strong>$nama_pelanggan</strong> telah diselesaikan.";
}

header("Location: admin_dashboard.php");
exit;
