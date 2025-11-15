<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// include 'utility/sesionlogin.php'; // Dianjurkan untuk diaktifkan untuk otentikasi
include 'koneksi.php';

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
    <title>Pengajuan Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #3629B7; /* Warna utama dari kode sebelumnya */
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

        /* Header Icon */
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


        /* Stats Cards - Konsisten dengan warna baru */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.25rem;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }
        
        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning-orange);
        }

        .stat-card.primary .stat-icon {
            background: rgba(54, 41, 183, 0.15); 
            color: var(--primary-blue);
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.15); 
            color: var(--success-green);
        }

        .stat-card.danger .stat-icon {
            background: rgba(239, 68, 68, 0.15); 
            color: var(--danger-red);
        }

        .stat-card .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            font-size: 0.95rem;
            color: #475569;
            font-weight: 500;
        }

        /* Card and Table Styles */
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
        
        /* Badge Styles - Disesuaikan agar konsisten */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 8px;
            text-transform: uppercase;
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
        
        /* Tombol Detail/Primary - Diubah kembali ke gradien biru/ungu */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%); 
            box-shadow: 0 4px 12px rgba(54, 41, 183, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(54, 41, 183, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-red) 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .btn-secondary {
            background: #64748b;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            border: none;
            border-radius: 16px 16px 0 0;
            padding: 20px 24px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-footer {
            border: none;
            padding: 16px 24px;
            background-color: #f8fafc;
        }

        /* Form Styles */
        .col-form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(54, 41, 183, 0.1);
        }

        /* DataTables Custom */
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1rem;
            }

            .stat-card .stat-value {
                font-size: 1.75rem;
            }
            
            .btn-sm {
                padding: 4px 12px;
                font-size: 0.8rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }

            #datatablesSimple {
                font-size: 0.8rem;
            }

            #datatablesSimple thead th,
            #datatablesSimple tbody td {
                padding: 0.75rem 0.5rem;
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
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h1>Pengajuan Surat</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item active">Pengajuan Surat</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Hitung statistik
                    $sql_stats = "SELECT 
                                        COUNT(*) as total,
                                        SUM(CASE WHEN l.status = 'Masuk' THEN 1 ELSE 0 END) as menunggu,
                                        SUM(CASE WHEN l.status = 'Proses' THEN 1 ELSE 0 END) as proses,
                                        SUM(CASE WHEN l.status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                                        SUM(CASE WHEN l.status = 'Tolak' THEN 1 ELSE 0 END) as ditolak
                                    FROM pengajuan_surat ps
                                    LEFT JOIN laporan l ON ps.id_laporan = l.id_laporan";

                    $result_stats = $conn->query($sql_stats);
                    $stats = $result_stats->fetch_assoc();
                    ?>

                    <div class="stats-container">
                        <div class="stat-card warning">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value"><?= $stats['menunggu'] ?? 0 ?></div>
                            <div class="stat-label">Menunggu</div>
                        </div>
                        <div class="stat-card success">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value"><?= $stats['selesai'] ?? 0 ?></div>
                            <div class="stat-label">Selesai</div>
                        </div>
                        <div class="stat-card danger">
                            <div class="stat-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stat-value"><?= $stats['ditolak'] ?? 0 ?></div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-container">
                                <table id="datatablesSimple" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>No. Pengajuan</th>
                                            <th>Nama Lengkap</th>
                                            <th>Jenis Surat</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $sql = "SELECT 
                                                    ps.id_pengajuan_surat,
                                                    ps.no_pengajuan,
                                                    ps.nama,
                                                    ps.kode_surat,
                                                    ps.tanggal,
                                                    l.status
                                                FROM pengajuan_surat ps
                                                LEFT JOIN laporan l ON ps.id_laporan = l.id_laporan
                                                ORDER BY ps.id_pengajuan_surat DESC";

                                        $result = $conn->query($sql);

                                        if ($result === false) {
                                            error_log("Query gagal: " . $conn->error);
                                            echo '<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan saat mengambil data. Silakan coba lagi.</td></tr>';
                                        } elseif ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $status = $row['status'] ?? 'N/A';
                                                $badge = '';

                                                // Logika penentuan badge dan warna yang konsisten
                                                if ($status === "Masuk") {
                                                    $badge = '<span class="badge bg-warning">Menunggu</span>';
                                                } elseif ($status === "Proses") {
                                                    $badge = '<span class="badge bg-primary">Diproses</span>';
                                                } elseif ($status === "Selesai") {
                                                    $badge = '<span class="badge bg-success">Selesai</span>';
                                                } elseif ($status === "Tolak") {
                                                    $badge = '<span class="badge bg-danger">Ditolak</span>';
                                                } else {
                                                    $badge = '<span class="badge bg-secondary">Tidak Diketahui</span>';
                                                }

                                                $id_for_detail = !empty($row['no_pengajuan']) ? $row['no_pengajuan'] : $row['id_pengajuan_surat'];

                                                $id_pengajuan = htmlspecialchars($row['id_pengajuan_surat']);
                                                $no_pengajuan = htmlspecialchars($row['no_pengajuan']);
                                                $nama = htmlspecialchars($row['nama']);
                                                $kode_surat = strtoupper(htmlspecialchars($row['kode_surat']));
                                                $tanggal = date('d M Y', strtotime($row['tanggal']));

                                                $url_id_detail = urlencode($id_for_detail);
                                                $url_kode_surat = urlencode($row['kode_surat']);
                                        ?>
                                                <tr>
                                                    <td><strong>#<?= $id_pengajuan; ?></strong></td>
                                                    <td><?= $no_pengajuan; ?></td>
                                                    <td><?= $nama; ?></td>
                                                    <td><span class="badge bg-secondary"><?= $kode_surat; ?></span></td>
                                                    <td><?= $tanggal; ?></td>
                                                    <td><?= $badge; ?></td>

                                                    <td>
                                                        <div class="action-buttons">
                                                            <a class="btn btn-primary btn-sm"
                                                                href="suratmasuk_detail.php?no_pengajuan=<?= $url_id_detail; ?>&kode_surat=<?= $url_kode_surat; ?>">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>

                                                            <?php if ($status === "Masuk" || $status === "Proses") { ?>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalSelesai"
                                                                    data-id-pengajuan="<?= $id_pengajuan; ?>">
                                                                    <i class="fas fa-check"></i> Selesai
                                                                </button>

                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalTolak"
                                                                    data-id-pengajuan="<?= $id_pengajuan; ?>">
                                                                    <i class="fas fa-times"></i> Tolak
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox"></i><p>Tidak ada data pengajuan surat</p></div></td></tr>';
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

    <div class="modal fade" id="modalTolak" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Pengajuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="utility/proses_tolak.php" method="POST">
                        <input type="hidden" id="id_tolak" name="id">
                        <label class="col-form-label">Alasan Penolakan:</label>
                        <textarea class="form-control" name="alasan" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i>Ya, Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSelesai" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Konfirmasi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="fas fa-question-circle" style="font-size: 3rem; color: var(--success-green);"></i>
                        <p class="mt-3 mb-0" style="font-size: 1rem;">Apakah Anda yakin ingin menyelesaikan pengajuan surat ini?</p>
                    </div>
                    <form action="utility/proses_selesai.php" method="POST" id="formSelesai">
                        <input type="hidden" id="id_selesai" name="id">
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i>Ya, Selesaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Script untuk mengisi ID pada Modal Tolak
        var modalTolak = document.getElementById('modalTolak');
        if (modalTolak) {
            modalTolak.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idPengajuan = button.getAttribute('data-id-pengajuan');

                var inputId = modalTolak.querySelector('#id_tolak');
                if (inputId) inputId.value = idPengajuan;
            });
        }

        // Script untuk mengisi ID pada Modal Selesai
        var modalSelesai = document.getElementById('modalSelesai');
        if (modalSelesai) {
            modalSelesai.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idPengajuan = button.getAttribute('data-id-pengajuan');

                var inputId = modalSelesai.querySelector('#id_selesai');
                if (inputId) inputId.value = idPengajuan;
            });
        }

        // ==========================================================
        // SCRIPT SIDEBAR TOGGLE (Dipindahkan dari upbar.php)
        // ==========================================================
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebarToggle').on('click', function() {
                // Menambahkan kelas 'collapsed' pada sidebar dan konten
                $('.sidebar, .sb-sidenav-custom').toggleClass('collapsed');
                $('#layoutSidenav_content, .content').toggleClass('collapsed');

                // Menyimpan status ke localStorage
                const isCollapsed = $('.sidebar').hasClass('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Memulihkan status sidebar dari localStorage saat memuat halaman
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                $('.sidebar, .sb-sidenav-custom').addClass('collapsed');
                $('#layoutSidenav_content, .content').addClass('collapsed');
            }
        });
    </script>

</body>

</html>