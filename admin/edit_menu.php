<?php
include '../koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = '$id'");
// Ambil kategori unik dari tabel produk
$kategori_query = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk");

$data = mysqli_fetch_assoc($query);

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;

    $image = $data['image']; // default gambar lama

    // Jika user upload gambar baru
    if ($_FILES['image']['name']) {
        $tmp_image = $_FILES['image']['tmp_name'];
        $type = $_FILES['image']['type'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($type, $allowed)) {
            $newImageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($tmp_image, '../image/' . $newImageName);
            // Hapus gambar lama
            if (file_exists('../image/' . $image)) {
                unlink('../image/' . $image);
            }
            $image = $newImageName;
        } else {
            $errorMessage = "Format gambar tidak didukung!";
        }
    }

    if (!$errorMessage) {
        $update = "UPDATE produk SET nama='$nama', harga='$harga', kategori='$kategori', is_best_seller='$is_best_seller', image='$image' WHERE id='$id'";
        if (mysqli_query($koneksi, $update)) {
            $successMessage = "Menu berhasil diperbarui.";
        } else {
            $errorMessage = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Menu</title>
     <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #bbb;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-top: 10px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background: orange;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: darkorange;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<!-- Tombol Kembali -->
<div style="margin-bottom: 20px;">
    <a href="kelola_menu.php" style="text-decoration: none; font-size: 18px; color: #333;">
        <i class="fas fa-arrow-left"></i> 
    </a>
</div>

<div class="container">
    <h2>Edit Menu</h2>

    <?php if ($successMessage): ?>
        <div class="alert success"><?= $successMessage ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

        <label>Harga:</label>
        <input type="number" name="harga" value="<?= $data['harga'] ?>" required>

        <label>Kategori:</label>
<select name="kategori" required>
    <?php while ($row = mysqli_fetch_assoc($kategori_query)): ?>
        <option value="<?= $row['kategori'] ?>" <?= $row['kategori'] == $data['kategori'] ? 'selected' : '' ?>>
            <?= $row['kategori'] ?>
        </option>
    <?php endwhile; ?>
</select>


        <label><input type="checkbox" name="is_best_seller" <?= $data['is_best_seller'] ? 'checked' : '' ?>> Best Seller</label>

        <label>Gambar Saat Ini:</label>
        <img src="../image/<?= $data['image'] ?>" width="100" alt="">

        <label>Ganti Gambar (jika ingin):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Update</button>
  
</div>
</body>
</html>
