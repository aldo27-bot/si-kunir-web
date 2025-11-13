<?php
include 'utility/sesionlogin.php';

// PENTING: Anda perlu memastikan file 'koneksi.php' di-include di sini.
// Karena 'mysqli_query($conn, $query)' memerlukan variabel $conn.
// Diasumsikan 'koneksi.php' sudah di-include di 'utility/sesionlogin.php' 
// atau Anda akan mengaktifkan baris di bawah:
// include("koneksi.php");

// Ambil semua data aspirasi (tanpa nama pengaju)
$query = "
    SELECT id_pengajuan_aspirasi, judul, kategori, status, tanggal 
    FROM pengajuan_aspirasi 
    ORDER BY tanggal DESC
";

// Diasumsikan $conn tersedia dan query dieksekusi
// Jika koneksi.php belum di-include, uncomment include("koneksi.php") di atas
// dan pastikan $conn terdefinisi.
if (isset($conn)) {
    $result = mysqli_query($conn, $query);
} else {
    // Fallback jika $conn tidak terdefinisi (untuk menghindari error fatal)
    $result = false;
    // echo '<div class="alert alert-danger">Error: Variabel koneksi ($conn) tidak ditemukan.</div>';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pengajuan Aspirasi</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

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
        }

        #datatablesSimple tbody tr {
            transition: all 0.3s ease;
        }

        #datatablesSimple tbody tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        #datatablesSimple tbody td {
            vertical-align: middle;
            padding: 14px 12px;
            border-color: #e2e8f0;
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
        
       
        /* .badge.bg-secondary {
            background-color: #e2e8f0 !important;
            color: #475569 !important;
        }
        .badge.bg-warning {
            background-color: #fef3c7 !important; 
            color: #92400e !important;
        } 
        .badge.bg-success {
            background-color: #d1fae5 !important; 
            color: #065f46 !important;
        }  */
        .badge.bg-dark, .badge.bg-info-custom { 
            background-color: #0681f5ff !important; 
            color: #f0f2f5ff !important;
        }

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
    <?php include('navbar/upbar.php'); ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include('navbar/lefbar.php'); ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5" style="padding-top: 24px; padding-bottom: 40px;">
                    
                    <div class="page-header">
                        <div class="container-fluid px-4">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h1 class="">Pengajuan Aspirasi</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item active">Pengajuan Aspirasi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple"
                                    class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        if ($result && mysqli_num_rows($result) > 0):
                                            while ($r = mysqli_fetch_assoc($result)):
                                        ?>
                                        <tr>
                                            <td><strong><?= $no++ ?></strong></td>
                                            <td><?= htmlspecialchars($r['judul']) ?></td>
                                            <td><span class="badge bg-info-custom"><?= htmlspecialchars($r['kategori'] ?? '-') ?></span></td>
                                            <td>
                                                <?php
                                                        $statusDisplay = htmlspecialchars($r['status'] ?? 'Tidak Diketahui');
                                                        $statusColor = match (strtolower($statusDisplay)) {
                                                            'menunggu' => 'secondary',
                                                            'diproses' => 'warning',
                                                            'selesai' => 'success',
                                                            default => 'dark'
                                                        };
                                                        ?>
                                                <span class="badge bg-<?= $statusColor ?>">
                                                    <?= $statusDisplay ?>
                                                </span>
                                            </td>
                                            <td><i class="fas fa-calendar-alt me-1 text-muted"></i><?= date('d M Y', strtotime(htmlspecialchars($r['tanggal']))) ?></td>
                                            <td>
                                                <a href="respond.php?id_pengajuan_aspirasi=<?= $r['id_pengajuan_aspirasi'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-reply me-1"></i> Tanggapi
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                            endwhile;
                                        else:
                                            ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                <p>Belum ada data aspirasi.</p>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
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
    document.addEventListener("DOMContentLoaded", () => {
        const table = document.querySelector("#datatablesSimple");
        if (table) new simpleDatatables.DataTable(table);
    });
    </script>
</body>

</html>