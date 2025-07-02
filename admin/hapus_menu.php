<?php
include '../koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT image FROM produk WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if ($data && file_exists('../image/' . $data['image'])) {
    unlink('../image/' . $data['image']); // hapus file gambar
}

mysqli_query($koneksi, "DELETE FROM produk WHERE id = '$id'");

header('Location: kelola_menu.php');
exit;
?>
