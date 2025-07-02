<?php
include 'koneksi.php';

// Default bulan dan tahun saat ini
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : date('Y');

// Untuk dropdown tahun (dari tahun paling awal hingga sekarang)
$tahun_sekarang = date('Y');
$tahun_awal = 2020; // Atur sesuai awal data kamu

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan & Menu Terlaris</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fff; }
        h2 { text-align: center; }
        .filter-form { text-align: center; margin-bottom: 20px; }
        .filter-form select { padding: 8px; margin: 0 5px; }
        .chart-container { width: 100%; max-width: 800px; margin: 30px auto; }
    </style>
</head>
<body>

    <h2>Laporan Bulan <?= sprintf("%02d", $bulan) . '/' . $tahun ?></h2>

    <form class="filter-form" method="GET" action="">
        <label>Bulan:
            <select name="bulan">
                <?php for ($b = 1; $b <= 12; $b++): ?>
                    <option value="<?= $b ?>" <?= $b == $bulan ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $b, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </label>

        <label>Tahun:
            <select name="tahun">
                <?php for ($t = $tahun_awal; $t <= $tahun_sekarang; $t++): ?>
                    <option value="<?= $t ?>" <?= $t == $tahun ? 'selected' : '' ?>><?= $t ?></option>
                <?php endfor; ?>
            </select>
        </label>

        <button type="submit">Tampilkan</button>
    </form>

    <div class="chart-container">
        <canvas id="pieChart"></canvas>
    </div>

    <div class="chart-container">
        <canvas id="barChart"></canvas>
    </div>

    <script>
        async function fetchChartData() {
            const bulan = <?= $bulan ?>;
            const tahun = <?= $tahun ?>;

            const res = await fetch(`chart_data.php?bulan=${bulan}&tahun=${tahun}`);
            const data = await res.json();

            // PIE CHART (Menu Terlaris)
            const ctx1 = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: data.menu_terlaris.map(item => item.nama),
                    datasets: [{
                        data: data.menu_terlaris.map(item => item.jumlah),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8E44AD', '#2ECC71']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { title: { display: true, text: 'Menu Terlaris' } }
                }
            });

            // BAR CHART (Pendapatan Harian)
            const ctx2 = document.getElementById('barChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: data.pendapatan_harian.map(item => item.tanggal),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data.pendapatan_harian.map(item => item.total),
                        backgroundColor: '#3498db'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { title: { display: true, text: 'Pendapatan Harian' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                        }
                    }
                }
            });
        }

        fetchChartData();
    </script>

</body>
</html>
