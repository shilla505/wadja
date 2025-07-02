<?php
include '../koneksi.php';

$kategori_result = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk");
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;

    // Validasi dan upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($imageType, $allowedTypes)) {
            $uploadPath = '../image/' . basename($image);
            if (move_uploaded_file($tmp_image, $uploadPath)) {
                // Simpan data ke database
                $query = "INSERT INTO produk (nama, harga, kategori, is_best_seller, image) 
                          VALUES ('$nama', '$harga', '$kategori', '$is_best_seller', '$image')";
                if (mysqli_query($koneksi, $query)) {
                    $successMessage = "Menu berhasil disimpan!";
                } else {
                    $errorMessage = "Gagal menyimpan data: " . mysqli_error($koneksi);
                }
            } else {
                $errorMessage = "Gagal mengunggah gambar.";
            }
        } else {
            $errorMessage = "Jenis file tidak didukung. Gunakan JPEG, PNG, atau WEBP.";
        }
    } else {
        $errorMessage = "Silakan unggah gambar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Tambah Menu</title>
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
        <h2>Tambah Menu</h2>

        <?php if ($successMessage): ?>
            <div class="alert success"><?= $successMessage ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="alert error"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="nama">Nama Menu:</label>
            <input type="text" name="nama" id="nama" required>

            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" required>

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while ($row = mysqli_fetch_assoc($kategori_result)): ?>
            <option value="<?= $row['kategori'] ?>"><?= $row['kategori'] ?></option>
            <?php endwhile; ?>
            </select>

            <label><input type="checkbox" name="is_best_seller"> Best Seller</label>

            <label for="image">Gambar:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
