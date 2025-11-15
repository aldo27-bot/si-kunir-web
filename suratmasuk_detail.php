<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Pastikan file sesionlogin.php dan koneksi.php tersedia
// include 'utility/sesionlogin.php'; // Dianjurkan untuk diaktifkan
include 'koneksi.php';


// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// ----------------------------------------------------------------------
// VALIDASI DAN INISIALISASI PARAMETER
// ----------------------------------------------------------------------

$no_pengajuan = $_GET['no_pengajuan'] ?? $_GET['id_detail'] ?? ''; // Ambil dari salah satu
$kode_surat = $_GET['kode_surat'] ?? '';

$table_name = $kode_surat;
$kodeMap = [
    'SKD'   => 'surat_domisili',
    'SKK'   => 'surat_kehilangan',
    'SKTM' => 'surat_sktm',
    'SKBB' => 'surat_berkelakuan_baik',
    'SKKM' => 'surat_keterangan_kematian',
    'SKBN' => 'surat_keterangan_beda_nama',
    'SKU'   => 'surat_keterangan_usaha'
];

$table_name = $kodeMap[$kode_surat] ?? $kode_surat;


// --- WHITLELIST TABEL UNTUK KEAMANAN KRITIS ---
$validTables = ['surat_kehilangan', 'surat_berkelakuan_baik', 'surat_domisili', 'surat_keterangan_kematian', 'surat_sktm', 'surat_keterangan_beda_nama', 'surat_keterangan_usaha'];
if (!in_array($table_name, $validTables)) {
    $_SESSION['error_message'] = "Tabel surat tidak valid.";
    header("Location: suratmasuk.php");
    exit;
}
// ---------------------------------------------


// ----------------------------------------------------------------------
// LOGIC POST (UPDATE DATA)
// ----------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $no_pengajuan_post = $_POST['no_pengajuan'] ?? $no_pengajuan;

    try {
        mysqli_begin_transaction($conn);
        $updateFields = [];
        $updateParams = [];
        $updateTypes = "";
        $excludedPostKeys = ['submit', 'no_pengajuan', 'kode_surat'];

        // 1. Update data utama
        foreach ($_POST as $key => $value) {
            if (!in_array($key, $excludedPostKeys)) {
                $updateFields[] = "`$key` = ?";
                $updateParams[] = $value;
                $updateTypes .= "s";
            }
        }

        if (!empty($updateFields)) {
            $query = "UPDATE `$table_name` SET " . implode(", ", $updateFields) . " WHERE no_pengajuan = ?";
            $stmt = mysqli_prepare($conn, $query);

            $updateTypes .= "s";
            $bind_params = array_merge([$updateTypes], $updateParams, [$no_pengajuan_post]);

            $params_ref = array();
            foreach ($bind_params as $key => $value) {
                $params_ref[$key] = &$bind_params[$key];
            }
            if (!call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt], $params_ref))) {
                throw new Exception("Gagal mengikat parameter: " . mysqli_stmt_error($stmt));
            }

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Gagal update data utama: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        }

        // 2. Tangani unggahan file
        $uploadPath = "../surat/upload_surat/";
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];

        if (isset($_FILES['file_lampiran']) && $_FILES['file_lampiran']['error'] === UPLOAD_ERR_OK) {

            $file = $_FILES['file_lampiran'];

            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception("Tipe file tidak valid. Tipe yang diizinkan: JPG, PNG, PDF");
            }
            if (!is_dir($uploadPath) && !mkdir($uploadPath, 0777, true)) {
                throw new Exception("Gagal membuat direktori upload.");
            }

            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = time() . '_' . $no_pengajuan_post . '.' . $fileExtension;
            $destination = $uploadPath . $newFileName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception("Gagal mengunggah file. Cek izin folder.");
            }

            // Update file pada tabel surat
            $fileUpdateQuery = "UPDATE `$table_name` SET `file` = ? WHERE no_pengajuan = ?";
            $stmt_file = mysqli_prepare($conn, $fileUpdateQuery);
            mysqli_stmt_bind_param($stmt_file, "ss", $newFileName, $no_pengajuan_post);
            mysqli_stmt_execute($stmt_file);
            mysqli_stmt_close($stmt_file);
        } else {
            // Jika tidak upload file baru → gunakan file lama
            $newFileName = $data['file'] ?? null;
        }


        // -------------------------------------------------------------
        // UPDATE ke pengajuan_surat → wajib dilakukan SETIAP UPDATE
        // -------------------------------------------------------------
        $updNama = $_POST['nama'] ?? null;
        $updNik  = $_POST['nik'] ?? null;
        $updFile = $newFileName;

        $queryUpdatePengajuan = "UPDATE pengajuan_surat 
                                SET nama = ?, nik = ?, file = ?
                                WHERE no_pengajuan = ?";

        $stmtPengajuan = mysqli_prepare($conn, $queryUpdatePengajuan);
        mysqli_stmt_bind_param($stmtPengajuan, "sssi", $updNama, $updNik, $updFile, $no_pengajuan_post);

        if (!mysqli_stmt_execute($stmtPengajuan)) {
            throw new Exception("Gagal update tabel pengajuan_surat: " . mysqli_stmt_error($stmtPengajuan));
        }

        mysqli_stmt_close($stmtPengajuan);



        mysqli_commit($conn);

        $_SESSION['success_message'] = "Data berhasil diperbarui!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan_post&kode_surat=$kode_surat");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan_post&kode_surat=$kode_surat");
        exit;
    }
}


// ----------------------------------------------------------------------
// LOGIC GET (AMBIL DATA & TAMPILAN)
// ----------------------------------------------------------------------

// Ambil pesan dari session dan hapus
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Dapatkan keterangan surat
$query = "SELECT keterangan FROM data_surat WHERE kode_surat = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_surat);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$suratKeterangan = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['keterangan'] : "Surat Tidak Ada";
mysqli_stmt_close($stmt);

// Ambil data yang ada
$query_data = $conn->prepare("SELECT * FROM `$table_name` WHERE no_pengajuan = ?");
$query_data->bind_param("s", $no_pengajuan);
$query_data->execute();
$result_data = $query_data->get_result();
$data = $result_data->fetch_assoc();
$query_data->close();
if (!$data && !empty($no_pengajuan)) {
    $error_message .= " Data pengajuan tidak ditemukan di tabel '$table_name'.";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Detail Pengajuan Surat</title>
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
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 1rem 0;
            background-color: var(--light-bg);
            color: #1e293b;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
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

        .card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            border: none;
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 16px 16px 0 0;
        }

        .card-body {
            padding: 24px;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            font-weight: 500;
            box-shadow: var(--card-shadow);
            animation: slideInDown 0.5s ease;
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%);
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-red) 0%, #dc2626 100%);
        }

        .alert-warning {
            background: linear-gradient(135deg, var(--warning-orange) 0%, #d97706 100%);
        }

        /* Badge */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 8px;
            text-transform: uppercase;
        }

        .badge.bg-secondary {
            background: #e2e8f0 !important;
            color: #475569 !important;
        }

        .alert .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(54, 41, 183, 0.1);
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

        .btn-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-orange) 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 4px 12px rgba(54, 41, 183, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(54, 41, 183, 0.4);
        }

        .file-link-box {
            background-color: #f1f5f9;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <h1 class="">Detail Pengajuan Surat</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="suratmasuk.php">Pengajuan Surat</a></li>
                                        <li class="breadcrumb-item active">Detail Pengajuan Surat</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-0 mb-4">
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="no_pengajuan" value="<?php echo htmlspecialchars($no_pengajuan); ?>">
                                <input type="hidden" name="kode_surat" value="<?php echo htmlspecialchars($kode_surat); ?>">

                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i><?php echo htmlspecialchars($suratKeterangan); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($data): ?>
                                            <div class="mb-4">
                                                <h5 class="text-primary"><i class="fas fa-info-circle me-1"></i> Data Pengajuan</h5>
                                                <p class="text-muted mb-0">No. Pengajuan: <strong>#<?php echo htmlspecialchars($data['no_pengajuan']); ?></strong> | Kode: <strong><span class="badge bg-secondary"><?php echo htmlspecialchars($kode_surat); ?></span></strong></p>
                                                <hr>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label"><i class="fas fa-user me-1"></i> Nama Lengkap</label>
                                                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i> Alamat</label>
                                                    <textarea name="alamat" class="form-control"><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                                                </div>

                                                <?php
                                                $excludedColumns = ['no_pengajuan', 'nik', 'nama', 'alamat', 'file', 'username', 'kode_surat', 'id_pejabat_desa'];
                                                foreach ($data as $key => $value) {
                                                    if (!in_array($key, $excludedColumns)) {
                                                        $label = htmlspecialchars(ucwords(str_replace('_', ' ', $key)));

                                                        if (strpos($key, 'file') !== false || $key == 'file') {
                                                            // Input file (diberi nama 'file_lampiran' untuk POST handler)
                                                            echo '<div class="col-md-6 mb-3">';
                                                            echo '<label for="file_lampiran" class="form-label"><i class="fas fa-upload me-1"></i> ' . $label . ' (Ganti File)</label>';
                                                            echo '<input type="file" name="file_lampiran" id="file_lampiran" class="form-control">';
                                                            echo '<small class="form-text text-muted">File saat ini: <strong>' . htmlspecialchars($value ?? 'Belum ada') . '</strong></small>';
                                                            echo '</div>';
                                                        } else {
                                                            // Input Teks
                                                            echo '<div class="col-md-6 mb-3">';
                                                            echo '<label class="form-label"><i class="fas fa-chevron-right me-1 text-muted"></i> ' . $label . '</label>';
                                                            echo '<input type="text" name="' . htmlspecialchars($key) . '" class="form-control" value="' . htmlspecialchars($value ?? '') . '">';
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" name="submit" class="btn btn-success">
                                                    <i class="fas fa-save me-1"></i> Simpan Perubahan Data
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle me-2"></i>Data tidak ditemukan untuk No. Pengajuan: <?php echo htmlspecialchars($no_pengajuan); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>File & Lampiran</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $file_lampiran = $data['file'] ?? null;
                                    // Base URL untuk file (asumsi path relatif benar dari file ini ke folder upload)
                                    $baseURL = "../si-kunir-web/DatabaseMobile/surat/upload_surat/";

                                    if (!empty($file_lampiran)) {
                                        $filePath = $baseURL . $file_lampiran;
                                        $fileExtension = strtolower(pathinfo($file_lampiran, PATHINFO_EXTENSION));

                                        $icon = 'fas fa-file-alt';
                                        $iconColorClass = 'text-primary';

                                        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $icon = 'fas fa-image';
                                            $iconColorClass = 'text-success';
                                        } elseif ($fileExtension == 'pdf') {
                                            $icon = 'fas fa-file-pdf';
                                            $iconColorClass = 'text-danger';
                                        }
                                    ?>
                                        <div class="mb-3">
                                            <h6><i class="<?= $icon ?> me-1 <?= $iconColorClass ?>"></i> File Utama Pemohon</h6>
                                            <div class="file-link-box">
                                                <span class="text-truncate" style="max-width: 65%;"><?php echo htmlspecialchars($file_lampiran); ?></span>
                                                <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-external-link-alt"></i> Buka
                                                </a>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                        echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle me-2'></i>Tidak ada file lampiran utama dari pemohon.</div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-print me-2"></i>Cetak & Tanda Tangan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="form-label">Pejabat Penandatangan</label>
                                            <select id="selectOption" class="form-select">
                                                <option value="kepaladesa">Kepala Desa</option>
                                                <option value="sekretaris">Sekretaris Desa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="previewDocument()">
                                            <i class="fas fa-search me-1"></i> Preview
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="printAndClose()">
                                            <i class="fas fa-print me-1"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <!-- <script>
        function previewDocument() {
            let ttd = document.getElementById("selectOption").value;
            let url = `cetak/cek_surat.php?no_pengajuan=<?= $no_pengajuan ?>&kode_surat=<?= $kode_surat ?>&ttd=${ttd}`;
            window.open(url, "_blank");
        }

        function printAndClose() {
            let ttd = document.getElementById("selectOption").value;
            let url = `cetak/cek_surat.php?no_pengajuan=<?= $no_pengajuan ?>&kode_surat=<?= $kode_surat ?>&ttd=${ttd}`;
            let win = window.open(url, "_blank");
            setTimeout(() => {
                win.print();
            }, 1000);
        }
    </script> -->

    <script>
        // Fungsi untuk Preview Dokumen
        function previewDocument() {
            var selectedOption = document.getElementById("selectOption").value;
            var no_pengajuan = "<?php echo htmlspecialchars($no_pengajuan); ?>";
            var kode_surat = "<?php echo htmlspecialchars($kode_surat); ?>";

            if (no_pengajuan && kode_surat) {
                var url = `cetak/cek_surat.php?no_pengajuan=${encodeURIComponent(no_pengajuan)}&kode_surat=${encodeURIComponent(kode_surat)}&ttd=${encodeURIComponent(selectedOption)}`;
                window.open(url, '_blank');
            } else {
                alert("Error: Data pengajuan tidak lengkap");
            }
        }

        // Fungsi untuk Cetak Dokumen dan Tutup Jendela
        function printAndClose() {
            var selectedOption = document.getElementById("selectOption").value;
            var no_pengajuan = "<?php echo htmlspecialchars($no_pengajuan); ?>";
            var kode_surat = "<?php echo htmlspecialchars($kode_surat); ?>";

            if (no_pengajuan && kode_surat) {
                var url = `cetak/cek_surat.php?no_pengajuan=${encodeURIComponent(no_pengajuan)}&kode_surat=${encodeURIComponent(kode_surat)}&ttd=${encodeURIComponent(selectedOption)}`;
                var printWindow = window.open(url, '_blank');

                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.print();
                        setTimeout(function() {
                            printWindow.close();
                        }, 1000);
                    }, 500);
                };
            } else {
                alert("Error: Data pengajuan tidak lengkap");
            }
        }
    </script>
</body>

</html>