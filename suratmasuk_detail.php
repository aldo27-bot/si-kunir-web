<?php
session_start(); // Tambahkan session_start di awal

// include 'utility/sesionlogin.php';
include 'koneksi.php';

$no_pengajuan = $_GET['no_pengajuan'] ?? '';
$kode_surat = $_GET['kode_surat'] ?? '';
$id = $_GET['id'] ?? null;

// Tangani pengiriman formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        mysqli_begin_transaction($conn);
        // Update data utama
        $updateFields = [];
        $updateParams = [];
        $updateTypes = "";

        foreach ($_POST as $key => $value) {
            if ($key !== 'submit') {
                $updateFields[] = "`$key` = ?";
                $updateParams[] = $value;
                $updateTypes .= "s"; // Asumsikan semua kolom adalah string
            }
        }

        if (!empty($updateFields)) {
            $updateParams[] = $no_pengajuan; // Tambahkan no_pengajuan untuk klausa WHERE
            $query = "UPDATE `$kode_surat` SET " . implode(", ", $updateFields) . " WHERE no_pengajuan = ?";
            $stmt = mysqli_prepare($conn, $query);

            // Ikat semua parameter
            $updateTypes .= "s"; // Untuk parameter WHERE
            mysqli_stmt_bind_param($stmt, $updateTypes, ...$updateParams);
            mysqli_stmt_execute($stmt);
        }

        // Tangani unggahan file
        $uploadPath = "uploads/";
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        
        foreach ($_FILES as $fileKey => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                if (!in_array($file['type'], $allowedTypes)) {
                    throw new Exception("Tipe file tidak valid untuk $fileKey. Tipe yang diizinkan: JPG, PNG, PDF");
                }

                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = $no_pengajuan . '_' . $fileKey . '.' . $fileExtension;
                $destination = $uploadPath . $newFileName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    throw new Exception("Gagal mengunggah file: $fileKey");
                }

                // Perbarui referensi file di database jika diperlukan
                $fileUpdateQuery = "UPDATE `$kode_surat` SET `{$fileKey}_file` = ? WHERE no_pengajuan = ?";
                $stmt = mysqli_prepare($conn, $fileUpdateQuery);
                mysqli_stmt_bind_param($stmt, "ss", $newFileName, $no_pengajuan);
                mysqli_stmt_execute($stmt);
            }
        }

        mysqli_commit($conn);
        
        // Simpan pesan sukses di session
        $_SESSION['success_message'] = "Data berhasil diperbarui!";
        
        // Redirect tanpa parameter sukses
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan&kode_surat=$kode_surat");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        
        // Simpan pesan error di session
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        
        // Redirect tanpa parameter error
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_pengajuan=$no_pengajuan&kode_surat=$kode_surat");
        exit;
    }
}

// Ambil pesan dari session dan hapus
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Dapatkan keterangan surat
$query = "SELECT keterangan FROM surat WHERE kode_surat = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_surat);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$suratKeterangan = ($result->num_rows > 0) ? $result->fetch_assoc()['keterangan'] : "Surat Tidak Ada";

// Ambil data yang ada
$query = $conn->prepare("SELECT * FROM `$kode_surat` WHERE no_pengajuan = ?");
$query->bind_param("s", $no_pengajuan);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();
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

                    <!-- Tampilkan pesan sukses -->
                    <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Tampilkan pesan error -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row"> 
                        <!-- Kolom untuk Form Edit -->
                        <div class="col-md-8">
                            <form method="POST" enctype="multipart/form-data">
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
                                                <input type="number" name="nik" class="form-control" value="<?php echo htmlspecialchars($data['nik']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama'] ?? ''); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea name="alamat" class="form-control"><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                                            </div>
                                            <!-- Kolom dinamis -->
                                            <?php
                                            $excludedColumns = ['no_pengajuan', 'nik', 'nama', 'alamat', 'file', 'username', 'kode_surat'];
                                            foreach ($data as $key => $value) {
                                                if (!in_array($key, $excludedColumns)) {
                                                    echo '<div class="mb-3">';
                                                    echo '<label class="form-label">' . htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . '</label>';
                                                    echo '<input type="text" name="' . htmlspecialchars($key) . '" class="form-control" value="' . htmlspecialchars($value) . '">';
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        <?php else: ?>
                                            <div class="alert alert-warning">Data tidak ditemukan</div>
                                        <?php endif; ?>
                                        <button type="submit" name="submit" class="btn btn-success">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Kolom untuk Preview File -->
<div class="col-md-4">  
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Preview File</h5>
        </div>
        <div class="card-body">
            <?php
            include 'koneksi.php';

            // Ambil no_pengajuan dari GET atau POST
            $no_pengajuan = $_GET['no_pengajuan'] ?? '';

            if (!empty($no_pengajuan)) {
                // Query untuk mengambil file berdasarkan no_pengajuan
                $query = "
                    SELECT 'surat_ijin' AS sumber, file FROM surat_ijin WHERE no_pengajuan = ?
                    UNION ALL
                    SELECT 'skck' AS sumber, file FROM skck WHERE no_pengajuan = ?
                    UNION ALL
                    SELECT 'sktm' AS sumber, file FROM sktm WHERE no_pengajuan = ?
                ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $no_pengajuan, $no_pengajuan, $no_pengajuan);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $file = $row['file'];
                        $sumber = $row['sumber'];
                        $filePath = "DatabaseMobile/surat/upload_surat/" . $file;

                        if (!empty($file)) {
                            ?>
                            <div class="mb-3">
                                <h6>File Surat</h6>
                                <div class="alert alert-secondary">
                                    File Tersedia - <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">Buka File</a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "<div class='alert alert-warning'>Tidak ada file ditemukan untuk pengajuan ini.</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>No pengajuan tidak ditemukan.</div>";
            }
            ?>
        </div>
    </div>
</div>


                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Mengetahui</h5>
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
                                            Preview
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="printAndClose()">
                                            Cetak
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
    
    <script>
        function previewDocument() {
            var selectedOption = document.getElementById("selectOption").value;
            var no_pengajuan = "<?php echo htmlspecialchars($no_pengajuan); ?>";
            var kode_surat = "<?php echo htmlspecialchars($kode_surat); ?>";

            if (no_pengajuan && kode_surat) {
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
                    },
                    500);
                };
            } else {
                alert("Error: Data pengajuan tidak lengkap");
            }
            }
</script>
</body> 
</html>