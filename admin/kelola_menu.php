<?php
include '../koneksi.php'; // pastikan path-nya sesuai

// Ambil semua data produk
$result = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
        <!-- Tambahkan Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <title>Kelola Menu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f3f3f3;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            background-color: orange;
            color: white;
            border-radius: 4px;
            margin: 2px;
        }

        .btn-danger {
            background-color: red;
        }

        .btn-tambah {
            background-color: green;
            margin-bottom: 15px;
            display: inline-block;
        }

        .back-btn {
    font-size: 20px;
    color: #333;
    text-decoration: none;
        }
    </style>
</head>
<body>

   <!-- Pindahkan ikon back ke atas -->
<div style="margin-bottom: 20px;">
    <a href="admin_dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
</div>

<a href="tambah_menu.php" class="btn btn-tambah">+ Tambah Menu</a>


<table>
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Best Seller</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><img src="../image/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>"></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($row['kategori']); ?></td>
            <td><?= $row['is_best_seller'] ? 'Ya' : 'Tidak'; ?></td>
            <td>
                <a class="btn" href="edit_menu.php?id=<?= $row['id']; ?>">Edit</a>
                <a class="btn btn-danger" href="hapus_menu.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus menu ini?');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
