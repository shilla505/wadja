<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "warkop_db";
$port = 3307; // tambahkan port di parameter ke-5

$koneksi = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
