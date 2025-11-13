<?php
// Menyertakan file untuk memeriksa sesi login
include 'utility/sesionlogin.php';

// Query SQL untuk menghitung jumlah laporan berdasarkan status tertentu
$sql = "SELECT
    status,
    COUNT(*) as total
FROM
    laporan
WHERE
    status IN ('Proses', 'Selesai','Masuk','Tolak')
GROUP BY
    status;";

// Menjalankan query SQL
$result = $conn->query($sql);

// Inisialisasi variabel jumlah surat dengan nilai default 0
$surat_proses = 0;
$surat_selesai = 0;
$surat_masuk = 0;
$surat_tolak = 0;

// Periksa apakah query berhasil dieksekusi
if ($result) {
    // Iterasi hasil query
    while ($row = $result->fetch_assoc()) {
        // Mengisi variabel jumlah berdasarkan status surat
        if ($row['status'] == 'Proses') {
            $surat_proses = $row['total'];
        } elseif ($row['status'] == 'Selesai') {
            $surat_selesai = $row['total'];
        } elseif ($row['status'] == 'Masuk') {
            $surat_masuk = $row['total'];
        } elseif ($row['status'] == 'Tolak') {
            $surat_tolak = $row['total'];
        }
    }
} else {
    // Pesan error jika query gagal dijalankan
    echo "Error: " . $conn->error;
}

// Query untuk menghitung aspirasi masuk (tambahan)
$sql_aspirasi = "SELECT COUNT(*) as total FROM pengajuan_aspirasi WHERE status = 'Masuk'";
$result_aspirasi = $conn->query($sql_aspirasi);
$aspirasi_masuk = 0;

if ($result_aspirasi) {
    $row_aspirasi = $result_aspirasi->fetch_assoc();
    $aspirasi_masuk = $row_aspirasi['total'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <!-- Metadata dasar -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Desa Kuncir</title>

    <!-- Menyertakan file CSS eksternal -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        :root {
            --primary-blue: #3629B7;
            --secondary-blue: #B36CFF;
            --success-green: #10b981;
            --warning-orange: #f59e0b;
            --danger-red: #ef4444;
            --info-cyan: #06b6d4;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Global Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 1rem 0;
            background-color: var(--light-bg);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        /* Main Content Area */
        #layoutSidenav_content {
            background: var(--light-bg);
        }

        main {
            min-height: calc(100vh - 56px);
        }

        /* Dashboard Header */
        .dashboard-header {
            /* background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%); */
            /* color: white; */
            padding: 30px 0;
            margin: -24px -24px 30px -24px;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 10px 30px rgba(54, 41, 183, 0.2);
        }

        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        /* Stats Cards Container */
        .stats-container {
            margin-bottom: 40px;
        }

        /* Individual Stat Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            color: var(--card-color);
            line-height: 1;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: var(--card-bg);
            color: var(--card-color);
            flex-shrink: 0;
        }

        /* Color Variants */
        .stat-card.masuk {
            --card-color: #3b82f6;
            --card-color-light: #60a5fa;
            --card-bg: #dbeafe;
        }

        .stat-card.selesai {
            --card-color: var(--success-green);
            --card-color-light: #34d399;
            --card-bg: #d1fae5;
        }

        .stat-card.ditolak {
            --card-color: var(--danger-red);
            --card-color-light: #f87171;
            --card-bg: #fee2e2;
        }

        .stat-card.aspirasi {
            --card-color: var(--secondary-blue);
            --card-color-light: #c084fc;
            --card-bg: #f3e8ff;
        }

        /* Chart Card */
        .chart-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: none;
        }

        .chart-card .card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
        }

        .chart-card .card-body {
            padding: 30px 24px;
        }

        .chart-card .card-footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Responsive Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 30px 20px;
                margin: -16px -16px 20px -16px;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 2rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        /* Container Padding */
        .container-fluid.px-5 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        @media (max-width: 768px) {
            .container-fluid.px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Memasukkan navbar -->
    <?php include('navbar/upbar.php') ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- Memasukkan menu samping -->
            <?php include("navbar/lefbar.php"); ?>
        </div>

        <!-- Konten utama -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5" style="padding-top: 24px; padding-bottom: 40px;">
                    
                    <!-- Dashboard Header -->
                    <div class="dashboard-header">
                        <div class="container-fluid px-4">
                            <h1>
                                <i class="fas fa-chart-line me-3"></i>Dashboard
                            </h1>
                            <p>Selamat datang di Sistem Informasi Desa Kuncir</p>
                        </div>
                    </div>

                    <!-- Bagian informasi jumlah surat -->
                    <div class="stats-container">
                        <div class="stats-grid">
                            <!-- Surat Masuk -->
                            <div class="stat-card masuk">
                                <a href="suratmasuk.php?user=<?php echo htmlentities($user) ?>">
                                    <div class="stat-content">
                                        <div class="stat-label">Surat Masuk</div>
                                        <div class="stat-value"><?php echo htmlentities($surat_masuk); ?></div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </a>
                            </div>

                            <!-- Surat Selesai -->
                            <div class="stat-card selesai">
                                <a href="laporan.php?user=<?php echo htmlentities($user) ?>">
                                    <div class="stat-content">
                                        <div class="stat-label">Surat Selesai</div>
                                        <div class="stat-value"><?php echo htmlentities($surat_selesai); ?></div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </a>
                            </div>

                            <!-- Surat Ditolak -->
                            <div class="stat-card ditolak">
                                <a href="laporan.php?user=<?php echo htmlentities($user) ?>">
                                    <div class="stat-content">
                                        <div class="stat-label">Surat Ditolak</div>
                                        <div class="stat-value"><?php echo htmlentities($surat_tolak); ?></div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </a>
                            </div>

                            <!-- Aspirasi Masuk -->
                            <div class="stat-card aspirasi">
                                <a href="list_ aspirasi.php?user=<?php echo htmlentities($user) ?>">
                                    <div class="stat-content">
                                        <div class="stat-label">Aspirasi Masuk</div>
                                        <div class="stat-value"><?php echo htmlentities($aspirasi_masuk); ?></div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian grafik surat -->
                    <div class="chart-card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-2"></i>Grafik Surat Masuk
                        </div>
                        <div class="card-body">
                            <canvas id="myAreaChart" width="100%" height="30"></canvas>
                        </div>
                        <script>
                            // Memuat data untuk grafik dari server
                            fetch('get_data.php')
                                .then(response => response.json())
                                .then(data => {
                                    var dates = data.map(entry => entry.tanggal);
                                    var counts = data.map(entry => entry.jumlah);

                                    // Menginisialisasi grafik
                                    var ctx = document.getElementById('myAreaChart').getContext('2d');
                                    var areaChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: dates,
                                            datasets: [{
                                                label: 'Surat Masuk per Hari',
                                                data: counts,
                                                fill: true,
                                                backgroundColor: 'rgba(54, 41, 183, 0.1)',
                                                borderColor: '#3629B7',
                                                borderWidth: 3,
                                                tension: 0.4,
                                                pointBackgroundColor: '#3629B7',
                                                pointBorderColor: '#fff',
                                                pointBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHoverRadius: 7,
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: true,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top',
                                                    labels: {
                                                        font: {
                                                            family: 'Inter',
                                                            size: 13,
                                                            weight: '600'
                                                        },
                                                        padding: 15,
                                                        usePointStyle: true,
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    type: 'time',
                                                    time: {
                                                        unit: 'day'
                                                    },
                                                    grid: {
                                                        display: false
                                                    },
                                                    ticks: {
                                                        font: {
                                                            family: 'Inter',
                                                            size: 12
                                                        }
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        stepSize: 1,
                                                        font: {
                                                            family: 'Inter',
                                                            size: 12
                                                        }
                                                    },
                                                    grid: {
                                                        color: 'rgba(0, 0, 0, 0.05)'
                                                    }
                                                }
                                            }
                                        }
                                    });
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        </script>

                        <!-- Menampilkan tanggal terakhir surat masuk -->
                        <div class="card-footer">
                            <i class="fas fa-clock me-2"></i>Terakhir diperbarui pada tanggal
                            <?php
                            include("koneksi.php");

                            // Query untuk mendapatkan tanggal terbaru
                            $query = "SELECT DATE(tanggal) as tanggal FROM pengajuan_surat ORDER BY tanggal DESC LIMIT 1";
                            $result = $conn->query($query);

                            // Menampilkan tanggal jika data ditemukan
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $data = date('d F Y', strtotime($row['tanggal']));
                            } else {
                                $data = "Belum ada data"; // Nilai default jika tidak ada data
                            }

                            echo $data;
                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Menyertakan file JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>

</html>