<?php
session_start();
include '../koneksi.php'; // koneksi ke DB

// Jika admin sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}

// Proses login admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Memeriksa username dan password tetap
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true; // Set session untuk login
        header("Location: admin_dashboard.php"); // Redirect ke dashboard admin
        exit;
    } else {
        $error_message = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Warkop Doea Djaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-container img {
            width: 100px; /* Ukuran logo */
            height: 100px; /* Pastikan logo berbentuk persegi */
            border-radius: 50%; /* Membuat logo bulat */
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box; /* Pastikan padding tidak nambah lebar */
        }

        .btn:hover {
            background-color: #e67e00;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="../image/logo.png" alt="Logo"> <!-- Ganti dengan path logo yang sesuai -->
    <h2>Login Admin</h2>
    
    <?php if (isset($error_message)) { ?>
        <div class="error-message"><?= $error_message ?></div>
    <?php } ?>

    <form method="POST">
        <input type="text" name="username" class="input-field" placeholder="Username" required>
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
