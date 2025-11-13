<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// include 'utility/sesionlogin.php';
include 'koneksi.php'; // Pastikan koneksi.php di-include di sini

if (!isset($conn)) {
    die("Koneksi database belum dibuat. Periksa file koneksi.php");
}

if ($conn->connect_error) {
    error_log("Koneksi DB gagal: " . $conn->connect_error);
    die("Koneksi ke database gagal.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        }

        /* Global Styles */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            padding: 1rem 0;
            background: var(--light-bg);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        /* Page Header */
        .page-header {
            padding: 1rem 0;
            margin: -1rem -1rem 2rem -1rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        .page-header h1 {
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .page-header .breadcrumb {
            background: transparent;
            margin: 0.5rem 0 0 0;
            padding: 0;
        }

        .page-header .breadcrumb-item,
        .page-header .breadcrumb-item a {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(54, 41, 183, 0.1);
            color: var(--primary-blue);
            border-radius: 12px;
            margin-right: 15px;
            font-size: 1.5rem;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: white;
            overflow: hidden;
        }

        .card-body {
            padding: 24px;
        }

        /* Table Styles */
        .table {
            font-size: 0.95rem;
        }

        #datatablesSimple thead th {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 16px 12px;
            white-space: nowrap;
        }

        #datatablesSimple tbody td {
            padding: 14px 12px;
            vertical-align: middle;
            color: #334155;
            border-color: #e2e8f0;
            font-size: 0.9rem;
        }

        #datatablesSimple tbody tr {
            transition: all 0.3s ease;
        }

        #datatablesSimple tbody tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* .badge.bg-warning { 
            background: #fef3c7 !important;
            color: #92400e !important;
        }

        .badge.bg-primary { 
            background: #dbeafe !important;
            color: #1e40af !important;
        }

        .badge.bg-success { 
            background: #d1fae5 !important;
            color: #065f46 !important;
        }

        .badge.bg-danger { 
            background: #fee2e2 !important;
            color: #991b1b !important;
        }

        .badge.bg-secondary { 
            background: #e2e8f0 !important;
            color: #475569 !important;
        } */

        /* Button Styles */
        .btn {
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 24px;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Inter', sans-serif;
            color: white;
        }

        .btn-sm {
            padding: 6px 16px;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 4px 12px rgba(54, 41, 183, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(54, 41, 183, 0.4);
        }

        /* DataTables Custom */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 12px;
        }
        
        .dataTable-wrapper .dataTable-container {
            border: none !important;
        }
        .dataTable-wrapper .dataTable-top,
        .dataTable-wrapper .dataTable-bottom {
            padding: 1rem 0 0 0;
            border: none;
        }
        
        .dataTable-input, .dataTable-selector {
            border-radius: 10px;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid.px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php') ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("navbar/lefbar.php"); ?>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5" style="padding-top: 24px; padding-bottom: 40px;">
                    
                    <div class="page-header">
                        <div class="container-fluid px-4">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="fas fa-file-archive"></i>
                                </div>
                                <div>
                                    <h1 class="">Laporan</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item active">Laporan</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Nama</th>
                                            <th>Kode Surat</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // include("koneksi.php"); // Sudah di-include di atas

                                        try {
                                            $sql = "SELECT laporan.id_laporan,
                                                            pengajuan_surat.username,
                                                            pengajuan_surat.nama,
                                                            pengajuan_surat.kode_surat,
                                                            DATE(laporan.tanggal) AS tanggal,
                                                            laporan.status,
                                                            pengajuan_surat.no_pengajuan
                                                        FROM laporan
                                                        JOIN pengajuan_surat 
                                                        ON pengajuan_surat.id_laporan = laporan.id_laporan
                                                        ORDER BY laporan.id_laporan DESC";

                                            $query = $conn->prepare($sql);
                                            $query->execute();
                                            $result = $query->get_result();

                                            if ($result->num_rows > 0) {
                                                while ($r = $result->fetch_assoc()) { 
                                                    
                                                    // Logika Badge Status
                                                    $statusDisplay = htmlspecialchars($r['status'] ?? 'N/A');
                                                    $statusColor = match ($statusDisplay) {
                                                        'Masuk', 'Proses' => 'primary', // Menggunakan primary untuk proses
                                                        'Selesai' => 'success',
                                                        'Tolak' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                    ?>
                                                    <tr>
                                                        <td><strong><?= htmlspecialchars($r['id_laporan']) ?></strong></td>
                                                        <td><?= htmlspecialchars($r['username']) ?></td>
                                                        <td><?= htmlspecialchars($r['nama']) ?></td>
                                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($r['kode_surat']) ?></span></td>
                                                        <td><i class="fas fa-calendar-alt me-1 text-muted"></i><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                                        <td><span class="badge bg-<?= $statusColor ?>"><?= $statusDisplay ?></span></td>
                                                        <td class="text-center">
                                                            <a class="btn btn-primary btn-sm"
                                                                href="cetak/cek_surat.php?no_pengajuan=<?= urlencode($r['no_pengajuan']) ?>&kode_surat=<?= urlencode($r['kode_surat']) ?>">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                        <?php   }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center text-muted py-4'>
                                                <i class='fas fa-inbox fa-3x mb-3 d-block'></i>
                                                <p>Tidak ada data laporan ditemukan.</p>
                                                </td></tr>";
                                            }
                                        } catch (Exception $e) {
                                            die("Error: " . $e->getMessage());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    
    <script>
        // Mengganti inisialisasi DataTables yang salah (JQuery) dengan Simple DataTables
        document.addEventListener("DOMContentLoaded", () => {
            const table = document.querySelector("#datatablesSimple");
            if (table) {
                 // Cek apakah Simple DataTables sudah dimuat (disediakan oleh datatables-simple-demo.js)
                 if (typeof simpleDatatables !== 'undefined') {
                    // Coba inisialisasi jika belum diinisialisasi oleh datatables-simple-demo.js
                    if (!table.classList.contains('dataTable-table')) { 
                        new simpleDatatables.DataTable(table);
                    }
                } else {
                    console.error("Simple DataTables library not loaded.");
                }
            }
        });
    </script>
</body>

</html>