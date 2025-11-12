<?php
session_start();
// Pastikan file sesionlogin.php dan koneksi.php tersedia
// include 'utility/sesionlogin.php'; // Biarkan baris ini dikomen jika Anda tidak memerlukannya saat pengembangan
include 'koneksi.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// ----------------------------------------------------------------------
// VALIDASI DAN INISIALISASI PARAMETER
// ----------------------------------------------------------------------

// Catatan: Variabel detail di link daftar adalah 'id_detail', namun di sini Anda menggunakan 'no_pengajuan'. 
// Kita akan konsisten menggunakan 'no_pengajuan' sebagai kunci utama.
$no_pengajuan = $_GET['no_pengajuan'] ?? $_GET['id_detail'] ?? ''; // Ambil dari salah satu
$kode_surat = $_GET['kode_surat'] ?? '';
$table_name = $kode_surat; // Asumsi kode surat sama dengan nama tabel

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
    // Ambil no_pengajuan dari hidden input POST
    $no_pengajuan_post = $_POST['no_pengajuan'] ?? $no_pengajuan; 

    try {
        mysqli_begin_transaction($conn);
        $updateFields = [];
        $updateParams = [];
        $updateTypes = "";
        $excludedPostKeys = ['submit', 'no_pengajuan', 'kode_surat'];

        // 1. Update data utama (hanya kolom yang ada di POST, kecuali yang dikecualikan)
        foreach ($_POST as $key => $value) {
            if (!in_array($key, $excludedPostKeys)) {
                $updateFields[] = "`$key` = ?";
                $updateParams[] = $value;
                $updateTypes .= "s"; // Asumsi string
            }
        }

        if (!empty($updateFields)) {
            $query = "UPDATE `$table_name` SET " . implode(", ", $updateFields) . " WHERE no_pengajuan = ?";
            $stmt = mysqli_prepare($conn, $query);

            // Ikat semua parameter (menggunakan splat operator untuk PHP 8.1+)
            $updateTypes .= "s"; // Tipe untuk parameter WHERE
            $bind_params = array_merge([$updateTypes], $updateParams, [$no_pengajuan_post]); 
            
            // Menggunakan call_user_func_array untuk kompatibilitas yang lebih luas (jika splat operator gagal)
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

        // 2. Tangani unggahan file (Asumsi hanya satu file input bernama 'file_lampiran')
        $uploadPath = "../DatabaseMobile/surat/upload_surat/"; // Disesuaikan dengan path yang benar dari root web
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

            // Perbarui kolom 'file' di database (Asumsi nama kolom adalah 'file')
            $fileUpdateQuery = "UPDATE `$table_name` SET `file` = ? WHERE no_pengajuan = ?";
            $stmt_file = mysqli_prepare($conn, $fileUpdateQuery);
            mysqli_stmt_bind_param($stmt_file, "ss", $newFileName, $no_pengajuan_post);
            
            if (!mysqli_stmt_execute($stmt_file)) {
                 throw new Exception("Gagal update referensi file: " . mysqli_stmt_error($stmt_file));
            }
            mysqli_stmt_close($stmt_file);
        }

        mysqli_commit($conn);
        
        $_SESSION['success_message'] = "Data berhasil diperbarui!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan_post&kode_surat=$table_name");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan_post&kode_surat=$table_name");
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

// Dapatkan keterangan surat (Asumsi tabel keterangan surat bernama 'data_surat', bukan 'surat')
$query = "SELECT keterangan FROM data_surat WHERE kode_surat = ?"; 
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_surat);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$suratKeterangan = ($result->num_rows > 0) ? $result->fetch_assoc()['keterangan'] : "Surat Tidak Ada";
mysqli_stmt_close($stmt);

// Ambil data yang ada
$query_data = $conn->prepare("SELECT * FROM `$table_name` WHERE no_pengajuan = ?");
$query_data->bind_param("s", $no_pengajuan);
$query_data->execute();
$result_data = $query_data->get_result();
$data = $result_data->fetch_assoc();
$query_data->close();
// Jika $no_pengajuan dari GET kosong dan $data tidak ditemukan, ini akan menyebabkan error
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
</head>

<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php') ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("navbar/lefbar.php"); ?>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 class="mt-4">Detail Pengajuan Surat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="suratmasuk.php">Pengajuan Surat</a></li>
                        <li class="breadcrumb-item active">Detail Pengajuan Surat</li>
                    </ol>

                    <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row"> 
                        <div class="col-md-8">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="no_pengajuan" value="<?php echo htmlspecialchars($no_pengajuan); ?>">
                                <input type="hidden" name="kode_surat" value="<?php echo htmlspecialchars($kode_surat); ?>">
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4 class="mb-0"><?php echo htmlspecialchars($suratKeterangan); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($data): ?>
                                            <div class="mb-3">
                                                <label class="form-label">ID Pengajuan</label>
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['no_pengajuan']); ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIK</label>
                                                <input type="number" name="nik" class="form-control" value="<?php echo htmlspecialchars($data['nik'] ?? ''); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama'] ?? ''); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea name="alamat" class="form-control"><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                                            </div>
                                            
                                            <?php
                                            $excludedColumns = ['no_pengajuan', 'nik', 'nama', 'alamat', 'file', 'username', 'kode_surat', 'id_pejabat_desa'];
                                            foreach ($data as $key => $value) {
                                                if (!in_array($key, $excludedColumns)) {
                                                    // Jika kolom tersebut mungkin file (berakhir dengan '_file' atau 'file' tanpa di exclude), berikan input file
                                                    if (strpos($key, 'file') !== false || $key == 'file') {
                                                        $currentFileName = htmlspecialchars($value ?? 'Belum ada');
                                                        echo '<div class="mb-3">';
                                                        echo '<label for="file_lampiran" class="form-label">' . htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . ' (Ganti File)</label>';
                                                        echo '<input type="file" name="' . htmlspecialchars($key) . '" id="file_lampiran" class="form-control">';
                                                        echo '<small class="form-text text-muted">File saat ini: **' . $currentFileName . '**</small>';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="mb-3">';
                                                        echo '<label class="form-label">' . htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . '</label>';
                                                        echo '<input type="text" name="' . htmlspecialchars($key) . '" class="form-control" value="' . htmlspecialchars($value ?? '') . '">';
                                                        echo '</div>';
                                                    }
                                                }
                                            }
                                            ?>
                                            
                                            <button type="submit" name="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        <?php else: ?>
                                            <div class="alert alert-warning">Data tidak ditemukan</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">  
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Preview File Lampiran</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Hanya ambil file yang terkait dengan pengajuan saat ini
                                    $file_lampiran = $data['file'] ?? null;

                                    if (!empty($file_lampiran)) {
                                        // PASTIKAN PATH INI BENAR DARI detail_surat_masuk.php ke folder upload Anda
                                        $filePath = "../DatabaseMobile/surat/upload_surat/" . $file_lampiran; 
                                        ?>
                                        <div class="mb-3">
                                            <h6>File Utama dari Pemohon</h6>
                                            <div class="alert alert-secondary">
                                                File Tersedia - <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">Buka File</a>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                        echo "<div class='alert alert-warning'>Tidak ada file lampiran utama dari pemohon.</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Mengetahui (Preview/Cetak Surat)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <select id="selectOption" class="form-select">
                                                <option value="kepaladesa">Kepala Desa</option>
                                                <option value="sekretaris">Sekretaris Desa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <button type="button" class="btn btn-warning" onclick="previewDocument()">
                                                <i class="fas fa-search"></i> Preview
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="printAndClose()">
                                                <i class="fas fa-print"></i> Cetak
                                            </button>
                                        </div>
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
    
    <script>
        function previewDocument() {
            var selectedOption = document.getElementById("selectOption").value;
            var no_pengajuan = "<?php echo htmlspecialchars($no_pengajuan); ?>";
            var kode_surat = "<?php echo htmlspecialchars($kode_surat); ?>";

            if (no_pengajuan && kode_surat) {
                // Pastikan path ini benar relatif terhadap halaman detail
                var url = `template surat/cek.php?no_pengajuan=${encodeURIComponent(no_pengajuan)}&kode_surat=${encodeURIComponent(kode_surat)}&ttd=${encodeURIComponent(selectedOption)}`;
                window.open(url, '_blank');
            } else {
                alert("Error: Data pengajuan tidak lengkap");
            }
        }

        function printAndClose() {
            var selectedOption = document.getElementById("selectOption").value;
            var no_pengajuan = "<?php echo htmlspecialchars($no_pengajuan); ?>";
            var kode_surat = "<?php echo htmlspecialchars($kode_surat); ?>";
            
            if (no_pengajuan && kode_surat) {
                var url = `template surat/cek.php?no_pengajuan=${encodeURIComponent(no_pengajuan)}&kode_surat=${encodeURIComponent(kode_surat)}&ttd=${encodeURIComponent(selectedOption)}`;
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