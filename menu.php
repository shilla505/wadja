<?php
include 'koneksi.php';
session_start();

// Hitung total item di keranjang
$jumlah_item = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $jumlah_item += $item['jumlah'];  // Jumlahkan total item dalam keranjang
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu - Warkop Doea Djaman</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        .menu-section {
            padding: 20px;
        }

        .kategori {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .produk {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .item {
            background-color: #f6f6f6;
            border-radius: 8px;
            padding: 10px;
            width: calc(50% - 10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item img {
            width: 40px;
            height: 40px;
        }

        .btn-tambah {
            padding: 4px 10px;
            background-color: orange;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-tambah:hover {
            background-color: darkorange;
        }

        /* Cart Icon */
        .cart-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            z-index: 999;
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            margin-right: 10px;
        }

        .cart-icon::after {
            content: attr(data-count);
            position: absolute;
            top: -8px;
            right: -12px;
            background: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .btn-view-cart {
            background-color: orange;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-view-cart:hover {
            background-color: darkorange;
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
    </div>
</div>

<!-- Menu Section -->
<div class="menu-section">
    <?php
    $kategori = [
        'BLACK COFFEE',
        'SIGNATURE DOEA DJAMAN',
        'FOOD',
        'BLACK COFFEE PLUS',
        'FLAVOUR COFFEE',
        'TEA',
        'OTHER BEVERAGE',
        'MOCKTAILS'
    ];

    foreach ($kategori as $kat) {
        echo "<div class='kategori'>$kat</div><div class='produk'>";
        $result = mysqli_query($koneksi, "SELECT * FROM produk WHERE kategori = '$kat'");
        while ($row = mysqli_fetch_assoc($result)) {
            $namaJS = htmlspecialchars($row["nama"], ENT_QUOTES);
            echo "
            <div class='item'>
                <div>
                    <img src='image/{$row['image']}' alt='{$row['nama']}'>
                    <div><strong>{$row['nama']}</strong><br>Rp " . number_format($row['harga'], 0, ',', '.') . "</div>
                </div>
                <button 
                    class='btn-tambah' 
                    onclick='tambahKeranjang({$row["id"]}, \"{$namaJS}\", {$row["harga"]}, \"{$row["image"]}\")'>+ 
                </button>
            </div>
            ";
        }
        echo "</div>";
    }
    ?>
</div>

<!-- Cart Icon -->
<div class="cart-container">
    <div class="cart-icon" id="cartCount" data-count="<?= $jumlah_item ?>">🛒</div>
    <a href="keranjang.php">
        <button class="btn-view-cart">View Cart</button>
    </a>
</div>

<!-- AJAX Script -->
<script>
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.text())
        .then(count => {
            $('#cartCount').attr('data-count', count);  // Update ikon dengan jumlah item
        });
}

function tambahKeranjang(id, nama, harga, image) {
    $.ajax({
        url: 'tambah_keranjang.php',
        method: 'POST',
        dataType: 'json',
        data: {
            id: id,
            nama: nama,
            harga: harga,
            image: image
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#cartCount').attr('data-count', response.cart_count);  // Update data-count setelah menambah
            } else {
                alert('Gagal menambahkan: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan sistem.');
        }
    });
}
</script>

</body>
</html>
