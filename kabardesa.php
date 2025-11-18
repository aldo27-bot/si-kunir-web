<?php
include 'utility/sesionlogin.php';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Informasi Desa - Desa Kuncir</title>
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
            /* --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); */
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Global Styles */
        body {
            /* font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; */
            padding: 1rem 0;
            background-color: var(--light-bg);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        /* #layoutSidenav_content {
            background: var(--light-bg);
        } */

        /* Page Header */
        /* .page-header {
            /* background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%); */
            /* color: white; */
            /* padding: 40px 0;
            margin: -24px -24px 30px -24px;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 10px 30px rgba(54, 41, 183, 0.2);
        }  */

        /* .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        } */

        /* Breadcrumb Custom */
        /* .breadcrumb {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 20px;
            border-radius: 10px;
            margin-top: 15px;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .breadcrumb-item a:hover {
            color: #fff;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        } */

        /* .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.7);
        } */

        .page-header {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            padding: 1rem 0;
            margin: -1rem -1rem 2rem -1rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        .page-header h1 {
            /* color: white; */
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
            /* color: rgba(255, 255, 255, 0.9); */
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

        /* Alert Notifications */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            font-weight: 500;
            box-shadow: var(--card-shadow);
            animation: slideInDown 0.5s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .alert-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .alert .btn-close {
            filter: brightness(0) invert(1);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button Styles */
        .btn {
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 24px;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-cyan) 0%, #0891b2 100%);
            color: white;
            border: none;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-orange) 0%, #d97706 100%);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-red) 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(54, 41, 183, 0.4);
        }

        .btn-sm {
            padding: 6px 16px;
            font-size: 0.875rem;
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

        .table thead th {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 16px 12px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table tbody td {
            vertical-align: middle;
            padding: 14px 12px;
            border-color: #e2e8f0;
        }

        .table tfoot th {
            background-color: #f8fafc;
            font-weight: 600;
            border-top: 2px solid #e2e8f0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        /* Image in Table */
        .table img {
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .table img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border: none;
            padding: 16px 24px;
            background-color: #f8fafc;
        }

        /* Form Styles */
        .form-label {
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

        textarea.form-control {
            min-height: 100px;
        }

        /* Action Buttons Group */
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 30px 20px;
                margin: -16px -16px 20px -16px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 16px;
            }

            .table {
                font-size: 0.85rem;
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

        /* DataTables Custom */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%) !important;
            border-color: var(--primary-blue) !important;
            color: white !important;
        }


        /* Image Preview in Modal */
        #edit_gambar_preview, #detail_gambar img {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        /* Detail Modal Content */
        #detailKabarModal .modal-body p {
            margin-bottom: 16px;
            line-height: 1.6;
        }

        #detailKabarModal .modal-body strong {
            color: var(--primary-blue);
            font-weight: 600;
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
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="container-fluid px-4">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <h1>Informasi Desa</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item active">Informasi Desa</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="container-fluid px-3 mb-4">
                        <?php
                        // Tampilkan notifikasi jika ada status
                        if (isset($_GET['status'])) {
                            $status = $_GET['status'];
                            $message = '';
                            $alertType = '';

                            switch ($status) {
                                case 'success':
                                    $_SESSION['notification'] = [
                                        'message' => '<i class="fas fa-check-circle me-2"></i>Informasi desa berhasil ditambahkan.',
                                        'type' => 'success'
                                    ];
                                    break;
                                case 'updated':
                                    $_SESSION['notification'] = [
                                        'message' => '<i class="fas fa-edit me-2"></i>Informasi desa berhasil diperbarui.',
                                        'type' => 'primary'
                                    ];
                                    break;
                                case 'deleted':
                                    $_SESSION['notification'] = [
                                        'message' => '<i class="fas fa-trash-alt me-2"></i>Informasi desa berhasil dihapus.',
                                        'type' => 'danger'
                                    ];
                                    break;
                            }
                        }

                        if (isset($_SESSION['notification'])) {
                            $notification = $_SESSION['notification'];
                            echo "<div class='alert alert-{$notification['type']} alert-dismissible fade show' role='alert'>
                            {$notification['message']}
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";

                            // Hapus notifikasi dari session setelah ditampilkan
                            unset($_SESSION['notification']);
                        }
                        ?>
                    </div>

                    <!-- Add Button -->
                    <div class="mb-4">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahKabarModal">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Informasi Desa
                        </button>
                    </div>

                    <!-- Data Table Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        include("koneksi.php");

                                        try {
                                            $sql = "SELECT id_informasi_desa, judul, tanggal, deskripsi, gambar FROM informasi_desa ORDER BY id_informasi_desa DESC";
                                            $query = $conn->prepare($sql);
                                            $query->execute();

                                            $query->store_result();
                                            $rowCount = $query->num_rows;

                                            if ($rowCount > 0) {
                                                $query->bind_result($id, $judul, $tanggal, $deskripsi, $gambar);

                                                while ($query->fetch()) { ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlentities($id); ?></strong></td>
                                                        <td><?php echo htmlentities($judul ?? ''); ?></td>
                                                        <td>
                                                            <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                                            <?php echo htmlentities(!empty($tanggal) ? date('d M Y', strtotime($tanggal)) : ''); ?>
                                                        </td>
                                                        <td><?php $desc = $deskripsi ?? ''; echo htmlentities(substr($desc, 0, 80)) . (strlen($desc) > 80 ? '...' : ''); ?></td>
                                                        <td>
                                                            <?php if ($gambar): ?>
                                                                <img src="uploads/<?php echo htmlentities($gambar); ?>" width="60" height="60" alt="Gambar Kabar">
                                                            <?php else: ?>
                                                                <span class="text-muted"><i class="fas fa-image me-1"></i>Tidak Ada</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="detail_kabar.php?id=<?php echo urlencode($id); ?>" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye me-1"></i>Detail
                                                                </a>
                                                                <button class="btn btn-warning btn-sm" onclick="openEditModal('<?php echo $id; ?>', '<?php echo addslashes($judul ?? ''); ?>', '<?php echo $tanggal ?? ''; ?>', '<?php echo addslashes($deskripsi ?? ''); ?>', '<?php echo $gambar ?? ''; ?>')">
                                                                    <i class="fas fa-edit me-1"></i>Edit
                                                                </button>
                                                                <a href="hapus_kabar.php?id=<?php echo urlencode($id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus informasi ini?')">
                                                                    <i class="fas fa-trash-alt me-1"></i>Hapus
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                        <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>
                                                    <i class='fas fa-inbox fa-3x mb-3 d-block'></i>
                                                    <p>Belum ada informasi desa yang tersedia.</p>
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

    <!-- Tambah Kabar Modal -->
    <div class="modal fade" id="tambahKabarModal" tabindex="-1" aria-labelledby="tambahKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKabarModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Informasi Desa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="proses_tambah_kabar.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">
                                <i class="fas fa-heading me-1"></i>Judul
                            </label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul informasi" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tanggal
                            </label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi informasi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">
                                <i class="fas fa-image me-1"></i>Gambar
                            </label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Kabar Modal -->
    <div class="modal fade" id="detailKabarModal" tabindex="-1" aria-labelledby="detailKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailKabarModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Detail Informasi Desa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="detail_id"></span></p>
                    <p><strong>Judul:</strong> <span id="detail_judul"></span></p>
                    <p><strong>Tanggal:</strong> <span id="detail_tanggal"></span></p>
                    <p><strong>Deskripsi:</strong></p>
                    <p id="detail_deskripsi" style="white-space: pre-wrap;"></p>
                    <div><strong>Gambar:</strong></div>
                    <div id="detail_gambar" class="mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Kabar Modal -->
    <div class="modal fade" id="editKabarModal" tabindex="-1" aria-labelledby="editKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKabarModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Informasi Desa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="proses_edit_kabar.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">
                                <i class="fas fa-heading me-1"></i>Judul
                            </label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tanggal
                            </label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi
                            </label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gambar" class="form-label">
                                <i class="fas fa-image me-1"></i>Gambar Baru (Opsional)
                            </label>
                            <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
                            <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah gambar</small>
                            <img id="edit_gambar_preview" width="100" height="100" alt="Gambar Kabar" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript resources -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <script>
        // Script to open Detail Modal with existing data
        function openDetailModal(id_informasi_desa, judul, tanggal, deskripsi, gambar) {
            document.getElementById('detail_id').textContent = id_informasi_desa;
            document.getElementById('detail_judul').textContent = judul;
            document.getElementById('detail_tanggal').textContent = tanggal;
            document.getElementById('detail_deskripsi').textContent = deskripsi;
            const detailGambarContainer = document.getElementById('detail_gambar');
            if (gambar) {
                detailGambarContainer.innerHTML = `<img src="uploads/${gambar}" class="img-fluid" style="max-width: 100%; max-height: 400px;" alt="Gambar Kabar">`;
            } else {
                detailGambarContainer.innerHTML = '<p class="text-muted"><i class="fas fa-image me-2"></i>Tidak ada gambar</p>';
            }
            const detailModal = new bootstrap.Modal(document.getElementById('detailKabarModal'));
            detailModal.show();
        }

        function openEditModal(id_informasi_desa, judul, tanggal, deskripsi, gambar) {
            // Ambil elemen modal edit dan atur isian form dengan data yang diterima
            document.getElementById('edit_id').value = id_informasi_desa;
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_deskripsi').value = deskripsi;
            
            const previewImg = document.getElementById('edit_gambar_preview');
            if (gambar) {
                previewImg.src = 'uploads/' + gambar;
                previewImg.style.display = 'block';
            } else {
                previewImg.style.display = 'none';
            }
            
            const editModal = new bootstrap.Modal(document.getElementById('editKabarModal'));
            editModal.show();
        }

        // Preview image on file select
        document.getElementById('gambar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Could add preview here if needed
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('edit_gambar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('edit_gambar_preview');
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>

</html>