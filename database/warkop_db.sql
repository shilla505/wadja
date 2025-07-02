-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 20, 2025 at 09:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warkop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama_pelanggan`, `metode_pembayaran`, `catatan`, `total_harga`, `tanggal`) VALUES
(14, 'kk', 'qris', '', 55000, '2025-05-18 14:19:00'),
(17, 'dey', 'qris', 'pancongnya setengah mateng', 41000, '2025-05-18 15:00:55'),
(18, 'putri sad', 'qris', 'tambah es batu', 30000, '2025-05-19 06:01:53'),
(19, 'Made', 'qris', 'nasi osengnya buat yang pedes banget', 53000, '2025-05-19 14:32:53'),
(20, 'bang lay', 'kasir', '', 57000, '2025-05-20 09:18:43'),
(21, 'tibo', 'kasir', '', 46000, '2025-05-20 09:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_produk`
--

CREATE TABLE `pesanan_produk` (
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan_produk`
--

INSERT INTO `pesanan_produk` (`id_pesanan`, `id_produk`, `jumlah`) VALUES
(14, 1, 1),
(14, 2, 1),
(17, 31, 1),
(17, 57, 1),
(17, 58, 1),
(18, 28, 1),
(18, 34, 1),
(18, 57, 1),
(19, 27, 1),
(19, 32, 1),
(19, 51, 1),
(20, 6, 1),
(20, 26, 1),
(20, 28, 1),
(21, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_best_seller` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `kategori`, `image`, `is_best_seller`) VALUES
(1, 'Espresso', 18000, 'BLACK COFFEE', 'Espresso.png', 0),
(2, 'Americano', 18000, 'BLACK COFFEE', 'Americano.png', 0),
(3, 'Kopi Tubruk', 18000, 'BLACK COFFEE', 'Kopi Tubruk.png', 0),
(4, 'V 60', 18000, 'BLACK COFFEE', 'V 60.png', 0),
(5, 'Saunders', 23000, 'MOCKTAILS', 'Saunders.png', 0),
(6, 'Virgin Mojito', 24000, 'MOCKTAILS', 'Virgin Mojito.png', 1),
(7, 'Pink Lady', 23000, 'MOCKTAILS', 'Pink Lady.png', 1),
(8, 'Long Black', 20000, 'BLACK COFFEE', 'Long Black.png', 0),
(9, 'V60 Jarai Wine', 25000, 'BLACK COFFEE', 'V60 Jarai Wine.png', 0),
(10, 'Japanese', 23000, 'BLACK COFFEE', 'Japanese.png', 0),
(11, 'Ice Coffee', 20000, 'BLACK COFFEE', 'Ice Coffee.png', 0),
(18, 'Aku Pengen Santay', 23000, 'SIGNATURE DOEA DJAMAN', 'Aku Pengen Santay.png', 0),
(19, 'Doea Djaman Latte', 23000, 'SIGNATURE DOEA DJAMAN', 'Doea Djaman Latte.png', 1),
(20, 'Frappe Oreo', 27000, 'SIGNATURE DOEA DJAMAN', 'Frappe Oreo.png', 0),
(21, 'Red Sweety', 20000, 'SIGNATURE DOEA DJAMAN', 'Red Sweety.png', 0),
(22, 'White Lemon', 19000, 'SIGNATURE DOEA DJAMAN', 'White Lemon.png', 0),
(23, 'Honey Lemon', 17000, 'SIGNATURE DOEA DJAMAN', 'Honey Lemon.png', 0),
(24, 'Passion Tea', 17000, 'SIGNATURE DOEA DJAMAN', 'Passion Tea.png', 0),
(25, 'Mie Instant', 9000, 'FOOD', 'Mie Instant.png', 0),
(26, 'Mie Instant + Telur', 13000, 'FOOD', 'Mie Instant + telur.png', 0),
(27, 'Prenges Prenges', 10000, 'FOOD', 'Prenges Prenges.png', 0),
(28, 'Cireng Bumbu Rujak', 10000, 'FOOD', 'Cireng Bumbu Rujak.png', 1),
(29, 'Roti Bakar', 10000, 'FOOD', 'Roti Bakar.png', 0),
(30, 'Burger Kill', 25000, 'FOOD', 'Burger Kill.png', 0),
(31, 'Kue Pancong', 16000, 'FOOD', 'Kue Pancong.png', 0),
(32, 'Nasi Oseng Daging Mercon', 28000, 'FOOD', 'Nasi Oseng.png', 1),
(33, 'Roti Maryam', 15000, 'FOOD', 'Roti Maryam.png', 0),
(34, 'Creeme Brulee', 15000, 'FOOD', 'Creeme Brulee.png', 0),
(35, 'Vanilla', 25000, 'FLAVOUR COFFEE', 'Coffee.png', 0),
(36, 'Caramel', 25000, 'FLAVOUR COFFEE', 'Coffee.png', 0),
(37, 'Mocca', 25000, 'FLAVOUR COFFEE', 'Coffee.png', 0),
(38, 'Hazelnut', 25000, 'FLAVOUR COFFEE', 'Coffee.png', 0),
(39, 'Irish', 25000, 'FLAVOUR COFFEE', 'Coffee.png', 0),
(40, 'Melon', 25000, 'FLAVOUR COFFEE', 'Roti Maryam.png', 0),
(41, 'Avocado', 25000, 'FLAVOUR COFFEE', 'Avocado.png', 1),
(42, 'Avogato', 24000, 'BLACK COFFEE PLUS', 'Avogato.png', 0),
(43, 'Caffee Latte', 24000, 'BLACK COFFEE PLUS', 'Caffee Latte.png', 0),
(44, 'Cappucino', 25000, 'BLACK COFFEE PLUS', 'Cappucino.png', 0),
(45, 'Vietnam Drip', 20000, 'BLACK COFFEE PLUS', 'Vietnam Drip.png', 0),
(46, 'Kopi Susu', 15000, 'BLACK COFFEE PLUS', 'Kopi Susu.png', 0),
(47, 'Iced Coffee Float', 25000, 'BLACK COFFEE PLUS', 'Iced Coffee Float.png', 0),
(48, 'Lychee Tea', 17000, 'TEA', 'Lychee Tea.png', 1),
(49, 'Lemon Tea', 13000, 'TEA', 'Lemon Tea.png', 0),
(50, 'Teh Tarik', 11000, 'TEA', 'Teh Tarik.png', 0),
(51, 'Teh Poci', 15000, 'TEA', 'Teh Poci.png', 1),
(52, 'Passion Tea', 17000, 'TEA', 'Roti Maryam.png', 0),
(53, 'Red Velvet', 20000, 'OTHER BEVERAGE', 'Red Velvet.png', 0),
(54, 'Matcha Latte', 20000, 'OTHER BEVERAGE', 'Matcha.png', 0),
(55, 'Fresh Milk', 15000, 'OTHER BEVERAGE', 'Fresh Milk.png', 0),
(56, 'Soda Susu', 17000, 'OTHER BEVERAGE', 'Soda Susu.png', 0),
(57, 'Air Mineral', 5000, 'OTHER BEVERAGE', 'air mineral.png', 0),
(58, 'Coffee Bear Float', 20000, 'OTHER BEVERAGE', 'Roti Maryam.png', 0),
(59, 'Chocolatte', 25000, 'OTHER BEVERAGE', 'Roti Maryam.png', 0),
(60, 'Jahe Geprek Susu', 10000, 'OTHER BEVERAGE', 'jahe.png', 0),
(61, 'Jahe Sereh Susu', 12000, 'OTHER BEVERAGE', 'jahe.png', 0),
(62, 'Harry Jhonson', 20000, 'MOCKTAILS', 'Harry Jhonson.png', 0),
(63, 'Sasakura', 20000, 'MOCKTAILS', 'Sasakura.png', 0),
(64, 'Frozen Kiwi', 20000, 'MOCKTAILS', 'Frozen Kiwi.png', 0),
(65, 'kopi susu gula aren', 23000, 'FLAVOUR COFFEE', '1747666189_kopi susu gula aren.jpg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan_produk`
--
ALTER TABLE `pesanan_produk`
  ADD PRIMARY KEY (`id_pesanan`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan_produk`
--
ALTER TABLE `pesanan_produk`
  ADD CONSTRAINT `pesanan_produk_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_produk_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
