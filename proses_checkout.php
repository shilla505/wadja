<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($koneksi)) {
        die("Koneksi database tidak tersedia.");
    }

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $metode   = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
    $catatan  = mysqli_real_escape_string($koneksi, $_POST['catatan']);
    $total    = intval($_POST['total_harga']);
    $tanggal  = date("Y-m-d H:i:s");

    $query = "INSERT INTO pesanan (nama_pelanggan, metode_pembayaran, catatan, total_harga, tanggal)
              VALUES ('$nama', '$metode', '$catatan', $total, '$tanggal')";

    if (mysqli_query($koneksi, $query)) {
        $last_id = mysqli_insert_id($koneksi);

        // ✅ Tambahkan ini: simpan produk yang dipesan
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $id_produk = intval($item['id']);
        $jumlah    = intval($item['jumlah']);

        $stmt = $koneksi->prepare("INSERT INTO pesanan_produk (id_pesanan, id_produk, jumlah) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $last_id, $id_produk, $jumlah);
        $stmt->execute();
        $stmt->close();
    }
}


        // Kosongkan keranjang
        unset($_SESSION['keranjang']);

        // Tampilkan konfirmasi atau alihkan sesuai metode
        if ($metode === 'kasir') {
            echo "<div style='text-align: center; padding: 20px;'>
                    <h2>Terima Kasih!</h2>
                    <p>Pesanan Anda sedang diproses. Silakan lakukan pembayaran di kasir.</p>
                    <a href='index.php' class='btn' style='padding: 10px 20px; background-color: orange; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Beranda</a>
                  </div>";
            exit;
        } else if ($metode === 'qris') {
            header("Location: qris_pembayaran.php?id=$last_id");
            exit;
        }
    } else {
        echo "Gagal menyimpan pesanan: " . mysqli_error($koneksi);
    }
}
?>
