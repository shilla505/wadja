<?php
include '../koneksi.php';

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

$pendapatan_query = "
    SELECT SUM(total_harga) AS total
    FROM pesanan
    WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
";
$pendapatan_result = mysqli_query($koneksi, $pendapatan_query);
$pendapatan = mysqli_fetch_assoc($pendapatan_result);

$harian_query = "
    SELECT DAY(tanggal) AS hari, SUM(total_harga) AS total
    FROM pesanan
    WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
    GROUP BY DAY(tanggal)
    ORDER BY hari
";
$harian_result = mysqli_query($koneksi, $harian_query);
$harian_labels = [];
$harian_data = [];
while ($row = mysqli_fetch_assoc($harian_result)) {
    $harian_labels[] = 'Hari ' . $row['hari'];
    $harian_data[] = $row['total'];
}

$menu_query = "
    SELECT pr.kategori, pr.nama, SUM(pp.jumlah) AS jumlah
    FROM pesanan_produk pp
    JOIN produk pr ON pp.id_produk = pr.id
    JOIN pesanan p ON pp.id_pesanan = p.id
    WHERE MONTH(p.tanggal) = $bulan AND YEAR(p.tanggal) = $tahun
    GROUP BY pr.kategori, pr.id, pr.nama
    ORDER BY jumlah DESC
";
$menu_result = mysqli_query($koneksi, $menu_query);

$menu_by_kategori = [];
$kategori_summary = [];
while ($row = mysqli_fetch_assoc($menu_result)) {
    $kategori = $row['kategori'];
    if (!isset($menu_by_kategori[$kategori])) {
        $menu_by_kategori[$kategori] = ['labels' => [], 'data' => []];
        $kategori_summary[$kategori] = 0;
    }
    $menu_by_kategori[$kategori]['labels'][] = $row['nama'];
    $menu_by_kategori[$kategori]['data'][] = (int)$row['jumlah'];
    $kategori_summary[$kategori] += (int)$row['jumlah'];
}
$menu_by_kategori_json = json_encode($menu_by_kategori);
$kategori_labels = json_encode(array_keys($kategori_summary));
$kategori_data = json_encode(array_values($kategori_summary));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .charts { display: flex; gap: 30px; flex-wrap: wrap; }
        .chart-container { width: 45%; min-width: 300px; }
        .form-filter { margin-bottom: 20px; }
        #exportPdfForm { margin-bottom: 20px; }
    </style>
</head>
<body>
     <a href="admin_dashboard.php" style="text-decoration:none; font-weight:bold; display:inline-flex; align-items:center; margin-bottom:20px; color:#333;">
        <span style="font-size:24px; margin-right:8px;">←</span>
    </a>
    <h2>Laporan Pendapatan</h2>

    <form method="GET" class="form-filter">
        <label>Bulan:
            <select name="bulan">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $bulan ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </label>
        <label>Tahun:
            <select name="tahun">
                <?php for ($y = date('Y'); $y >= 2022; $y--): ?>
                    <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </label>
        <button type="submit">Tampilkan</button>
    </form>

    <!-- Form export PDF -->
    <form id="exportPdfForm" method="POST" action="export_pdf.php">
        <input type="hidden" name="bulan" value="<?= $bulan ?>">
        <input type="hidden" name="tahun" value="<?= $tahun ?>">
        <input type="hidden" id="chartPendapatanInput" name="chart_pendapatan" value="">
        <input type="hidden" id="chartMenuInput" name="chart_menu" value="">
        <button type="submit">Export PDF</button>
    </form>

    <p>Total Pendapatan Bulan <?= $bulan ?>/<?= $tahun ?>: 
        <strong>Rp <?= number_format($pendapatan['total'], 0, ',', '.') ?></strong>
    </p>

    <div class="charts">
        <div class="chart-container">
            <canvas id="pendapatanChart"></canvas>
        </div>
        <div class="chart-container" style="flex-direction: column;">
            <label for="kategoriSelect"><strong>Pilih Kategori:</strong></label>
            <select id="kategoriSelect" onchange="updateBarChart()"></select>
            <canvas id="menuChartCanvas" style="margin-top:10px;"></canvas>
        </div>
    </div>

    <script>
        const harianLabels = <?= json_encode($harian_labels) ?>;
        const harianData = <?= json_encode($harian_data) ?>;
        const menuByKategori = <?= $menu_by_kategori_json ?>;
        const kategoriLabels = <?= $kategori_labels ?>;
        const kategoriData = <?= $kategori_data ?>;

        const uniqueColors = [
            '#e6194b', '#3cb44b', '#ffe119', '#4363d8',
            '#f58231', '#911eb4', '#46f0f0', '#f032e6'
        ];

        function getColorSet(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(uniqueColors[i % uniqueColors.length]);
            }
            return colors;
        }

        // Line chart pendapatan harian
        const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(ctxPendapatan, {
            type: 'line',
            data: {
                labels: harianLabels,
                datasets: [{
                    label: 'Pendapatan Harian',
                    data: harianData,
                    fill: false,
                    borderColor: '#3cb44b',
                    backgroundColor: '#3cb44b',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { title: { display: true, text: 'Grafik Pendapatan Harian' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Dropdown kategori + opsi "All Menu"
        const select = document.getElementById('kategoriSelect');
        // Tambah opsi All Menu di depan
        const allOption = document.createElement('option');
        allOption.value = 'all';
        allOption.textContent = 'All Menu';
        select.appendChild(allOption);

        Object.keys(menuByKategori).forEach(kat => {
            const opt = document.createElement('option');
            opt.value = kat;
            opt.textContent = kat;
            select.appendChild(opt);
        });

        // Bar chart menu
        let menuChart;
        function updateBarChart() {
            let labels = [];
            let data = [];
            let selectedKategori = select.value;

            if(selectedKategori === 'all') {
                // Gabungkan semua menu dari semua kategori
                let allLabels = [];
                let allData = [];
                Object.values(menuByKategori).forEach(k => {
                    allLabels = allLabels.concat(k.labels);
                    allData = allData.concat(k.data);
                });
                // Gabungkan label yang sama
                const map = {};
                allLabels.forEach((label, idx) => {
                    map[label] = (map[label] || 0) + allData[idx];
                });
                labels = Object.keys(map);
                data = Object.values(map);
                // Sort descending by jumlah
                const sorted = labels.map((l,i) => [l, data[i]]).sort((a,b) => b[1] - a[1]);
                labels = sorted.map(v => v[0]);
                data = sorted.map(v => v[1]);
            } else {
                labels = menuByKategori[selectedKategori].labels;
                data = menuByKategori[selectedKategori].data;
            }

            const ctx = document.getElementById('menuChartCanvas').getContext('2d');
            if (menuChart) menuChart.destroy();

            menuChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: data,
                        backgroundColor: getColorSet(data.length)
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: selectedKategori === 'all' ? 'Semua Menu Terlaris' : 'Menu Terlaris - ' + selectedKategori
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision:0
                            }
                        }
                    }
                }
            });
        }

        updateBarChart();

        // Event export PDF
        const form = document.getElementById('exportPdfForm');
        form.addEventListener('submit', function(e) {
            const pendapatanCanvas = document.getElementById('pendapatanChart');
            const menuCanvas = document.getElementById('menuChartCanvas');

            document.getElementById('chartPendapatanInput').value = pendapatanCanvas.toDataURL('image/png');
            document.getElementById('chartMenuInput').value = menuCanvas.toDataURL('image/png');
        });
    </script>
</body>
</html>
