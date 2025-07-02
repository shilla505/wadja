<?php
session_start();
include '../koneksi.php'; // koneksi ke DB

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit;
}

// Query untuk menampilkan pesanan masuk dan produk yang dipesan
$pesanan_query = "
    SELECT 
        p.id,
        p.nama_pelanggan,
        p.metode_pembayaran,
        p.catatan,
        p.total_harga,
        p.tanggal,
        p.status,
        GROUP_CONCAT(CONCAT(pr.nama, ' x', COALESCE(pp.jumlah, 1)) SEPARATOR ', ') AS produk_dipesan
    FROM pesanan p
    LEFT JOIN pesanan_produk pp ON p.id = pp.id_pesanan
    LEFT JOIN produk pr ON pp.id_produk = pr.id
    GROUP BY p.id
    ORDER BY p.tanggal DESC
    LIMIT 10";

$pesanan_result = mysqli_query($koneksi, $pesanan_query);


// Query untuk laporan pendapatan
$pendapatan_query = "SELECT MONTH(tanggal) AS bulan, YEAR(tanggal) AS tahun, SUM(total_harga) AS total_pendapatan
                     FROM pesanan GROUP BY YEAR(tanggal), MONTH(tanggal) ORDER BY tahun DESC, bulan DESC LIMIT 1"; // Pendapatan bulan terbaru
$pendapatan_result = mysqli_query($koneksi, $pendapatan_query);
$pendapatan = mysqli_fetch_assoc($pendapatan_result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Warkop Doea Djaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #f3f3f3;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: black;
            text-decoration: none;
            margin: 10px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #ddd;
        }
        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .pesanan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .pesanan-table th, .pesanan-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .pesanan-table th {
            background-color: #f2f2f2;
        }
        .laporan-pendapatan {
            background-color: #f9f9f9;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .laporan-pendapatan p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .btn {
            padding: 12px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #e67e00;
        }
        .logout-btn {
            padding: 12px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #4caf50;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    opacity: 0;
    animation: fadeInOut 4s forwards;
    z-index: 9999;
}

@keyframes fadeInOut {
    0%   { opacity: 0; transform: translateY(20px); }
    10%  { opacity: 1; transform: translateY(0); }
    90%  { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(20px); }
}
    </style>
</head>
<body>
<?php if (isset($_SESSION['notif'])): ?>
    <div class="toast">
        <?= $_SESSION['notif']; ?>
    </div>
    <?php unset($_SESSION['notif']); ?>
<?php endif; ?>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Dashboard Admin</h3>
    <a href="admin_dashboard.php">Pesanan Masuk</a>
    <a href="laporan_pendapatan.php">Laporan Pendapatan</a>
    <a href="kelola_menu.php">Kelola Menu</a>
    <form method="POST" action="logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Pesanan Masuk -->
    <div>
        <h2 class="section-title">Pesanan Masuk</h2>
        <table class="pesanan-table">
            <thead>
                <tr>
                    <th>Nama Pemesan</th>
                    <th>Pemesanan (Menu Dipilih)</th>
                    <th>Metode Pembayaran</th>
                    <th>Catatan</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pesanan = mysqli_fetch_assoc($pesanan_result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($pesanan['nama_pelanggan']); ?></td>
                        <td>
    <?php
    if (!empty($pesanan['produk_dipesan'])) {
        // Pisah nama produk berdasarkan koma dan tampilkan per baris
        $produkList = explode(',', $pesanan['produk_dipesan']);
        foreach ($produkList as $produk) {
            echo htmlspecialchars(trim($produk)) . '<br>';
        }
    } else {
        echo '-';
    }
    ?>
</td>
                        <td><?= htmlspecialchars($pesanan['metode_pembayaran']); ?></td>
                        <td><?= htmlspecialchars($pesanan['catatan']); ?></td>
                        <td>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></td>
                        <td><?= date("d/m/Y", strtotime($pesanan['tanggal'])); ?></td>
                        <td>
                            <?php if ($pesanan['status'] === 'selesai'): ?>
                                                                          ✅
                            <?php else: ?>
                            <form method="POST" action="selesai_pesanan.php" onsubmit="return confirm('Tandai pesanan atas nama <?= htmlspecialchars($pesanan['nama_pelanggan']); ?> sebagai selesai?');">
                            <input type="hidden" name="id_pesanan" value="<?= $pesanan['id']; ?>">
                             <input type="hidden" name="nama_pelanggan" value="<?= htmlspecialchars($pesanan['nama_pelanggan']); ?>">
                            <button type="submit" class="btn">Selesai</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    

   <!-- Laporan Pendapatan -->
<div class="laporan-pendapatan">
    <h3>Laporan Pendapatan</h3>
    <p>Bulan/Tahun: <?= $pendapatan['bulan'] . '/' . $pendapatan['tahun']; ?></p>
    <p>Total Pendapatan: Rp <?= number_format($pendapatan['total_pendapatan'], 0, ',', '.'); ?></p>
    <a href="laporan_pendapatan.php?bulan=<?= $pendapatan['bulan']; ?>&tahun=<?= $pendapatan['tahun']; ?>" class="btn">Lihat Detail Laporan</a>
</div>
</div>

</body>
</html>
