<?php
include 'koneksi.php'; // koneksi ke DB
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warkop Doea Djaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .navbar a {
            text-decoration: none;
            margin: 0 10px;
            color: black;
        }

        .hero {
            padding: 20px;
        }

        .hero h3 {
            margin-bottom: 10px;
        }

        .btn-pesan {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .best-seller {
            background-color: #f3f3f3;
            padding: 20px;
        }

        .produk {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #e6e6e6;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 130px;
        }

        .card img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
            object-fit: cover;
            border-radius: 6px;
        }

        .produk-scroll {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
}

.produk-scroll::-webkit-scrollbar {
    height: 8px;
}

.produk-scroll::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.card {
    background-color: #e6e6e6;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    min-width: 160px;
    flex: 0 0 auto;
    scroll-snap-align: start;
}
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-left">
    <img src="image/logo.png" alt="Logo">
    <strong style="margin-right: 10px;">Warkop Doea Djaman</strong>
    <a href="https://www.instagram.com/warkop_doeadjaman/?utm_source=ig_web_button_share_sheet" target="_blank">
        <img src="image/icons8-instagram-32.png" alt="Instagram" style="width: 20px; height: 20px;">
    </a>
</div>
    <div>
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="admin/login.php">Admin</a>
    </div>
</div>

<!-- Hero Section -->
<div class="hero">
    <h3>EXCLUSIVE WARKOP<br>Koempoelan Orang Penoeh Inspirasi</h3>
    <p>Menikmati kopi dengan penuh inspirasi</p>
    <a href="menu.php" class="btn-pesan">Pesan Sekarang</a>
</div>

<!-- Best Seller -->
<div class="best-seller">
    <h2 style="text-align: center;">Best Seller</h2>
    <div class="produk-scroll">
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM produk WHERE is_best_seller = 1 LIMIT 10");
        while ($row = mysqli_fetch_assoc($result)) {
            $gambar = !empty($row['image']) ? $row['image'] : 'default.png'; // fallback jika tidak ada gambar
            ?>
            <div class="card">
    <img src="image/<?= htmlspecialchars($gambar); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
    <h4><?= htmlspecialchars($row['nama']); ?></h4>
    <p>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
<form action="keranjang.php" method="POST">
    <input type="hidden" name="id" value="<?= $row['id']; ?>">
    <input type="hidden" name="nama" value="<?= $row['nama']; ?>">
    <input type="hidden" name="harga" value="<?= $row['harga']; ?>">
    <input type="hidden" name="image" value="<?= $gambar; ?>">
    <button type="submit" class="btn-pesan">Pesan</button>
</form>
</div>
            <?php
        }
        ?>
    </div>
</div>


</body>
</html>