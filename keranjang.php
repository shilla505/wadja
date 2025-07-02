<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $image = $_POST['image'];

    // Jika produk sudah ada di keranjang, tambahkan jumlahnya
    if (isset($_SESSION['keranjang'][$id])) {
        $_SESSION['keranjang'][$id]['jumlah'] += 1;
    } else {
        // Jika belum ada, tambahkan baru
        $_SESSION['keranjang'][$id] = [
            'id' => $id,
            'nama' => $nama,
            'harga' => $harga,
            'image' => $image,
            'jumlah' => 1
        ];
    }

    // Redirect supaya tidak mengulang POST saat refresh
    header('Location: keranjang.php');
    exit;
}

$total_harga = 0;
$keranjang_kosong = !isset($_SESSION['keranjang']) || empty($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        h2 {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .item-actions input[type='text'] {
            width: 35px;
            text-align: center;
            border: none;
            background: transparent;
        }

        .item-actions button {
            padding: 5px 10px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .item-actions button:hover {
            background-color: darkorange;
        }

        .remove-link {
            color: red;
            text-decoration: none;
            margin-left: 10px;
        }

        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .total-section h3 {
            margin: 0;
        }

        .checkout-btn {
            background-color: green;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .checkout-btn:hover {
            background-color: darkgreen;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            background-color: #333;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #555;
        }

        .empty-message {
            text-align: center;
            margin-top: 50px;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>

<h2>Keranjang Belanja</h2>

<?php if ($keranjang_kosong): ?>
    <p class="empty-message">Keranjang Anda kosong.</p>
<?php else: ?>
    <?php foreach ($_SESSION['keranjang'] as $key => $item): 
        $total_item = $item['harga'] * $item['jumlah'];
        $total_harga += $total_item;
    ?>
        <div class="cart-item">
            <img src="image/<?= $item['image']; ?>" alt="<?= $item['nama']; ?>">
            <div class="item-details">
                <strong><?= $item['nama']; ?></strong><br>
                Rp <?= number_format($item['harga'], 0, ',', '.'); ?>
            </div>
            <div class="item-actions">
                <form action="update_keranjang.php" method="POST">
                    <input type="hidden" name="id" value="<?= $item['id']; ?>">
                    <button type="submit" name="aksi" value="kurangi">-</button>
                    <input type="text" value="<?= $item['jumlah']; ?>" readonly>
                    <button type="submit" name="aksi" value="tambah">+</button>
                </form>
                <a class="remove-link" href="hapus_keranjang.php?id=<?= $item['id']; ?>">Hapus</a>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="total-section">
        <h3>Total: Rp <?= number_format($total_harga, 0, ',', '.'); ?></h3>
        <form action="checkout.php" method="POST">
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
    </div>
<?php endif; ?>

<!-- Tombol kembali ke menu tetap tampil -->
<a href="menu.php" class="back-btn">← Kembali ke Menu</a>

</body>
</html>
