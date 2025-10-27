<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include("koneksi.php");

// Proses POST untuk update status & tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $tanggapan = $_POST['tanggapan'];

    try {
        $stmt = $conn->prepare("UPDATE aspirasi SET status=?, tanggapan=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $tanggapan, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: list_aspirasi.php');
        exit;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

// Ambil data aspirasi berdasarkan ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM aspirasi WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$r = $result->fetch_assoc();
$stmt->close();

if (!$r) {
    echo "Data aspirasi tidak ditemukan";
    exit;
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
    <title>Tanggapi Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<style>
    .detail-container {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .detail-title {
        font-size: 28px;
        font-weight: bold;
        color: #343a40;
    }
    .detail-date {
        color: #6c757d;
        font-size: 14px;
    }
    .detail-description {
        font-size: 16px;
        color: #495057;
        margin-top: 15px;
        line-height: 1.6;
    }
    .btn-back {
        background-color: #6c757d;
        color: #ffffff;
        border-radius: 8px;
        transition: background-color 0.3s;
    }
    .btn-back:hover {
        background-color: #5a6268;
        color: #ffffff;
    }
</style>
<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php'); ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include('navbar/lefbar.php'); ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 class="mt-4">Tanggapi Aspirasi</h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb mb-4">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="list_aspirasi.php">Daftar Pengajuan Aspirasi</a></li>
                            <li class="breadcrumb-item active">Tanggapi Aspirasi</li>
                        </ol>
                    </nav>

                    <!-- Detail Aspirasi -->
                    <div class="detail-container">
                        <h2 class="detail-title"><?= htmlspecialchars($r['judul']) ?></h2>
                        <p class="detail-date"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($r['tanggal'] ?? '-') ?></p>

                        <?php if (!empty($r['foto'])): ?>
                            <img src="uploads/<?= htmlspecialchars($r['foto']) ?>" class="detail-image" style="max-width: 400px; border-radius: 8px;">
                        <?php endif; ?>

                        <div class="detail-description">
                            <strong>Deskripsi:</strong>
                            <p><?= nl2br(htmlspecialchars($r['deskripsi'])) ?></p>
                        </div>

                        <?php if (!empty($r['tanggapan'])): ?>
                            <div class="mt-4 p-3 border rounded bg-light">
                                <strong>Tanggapan Sebelumnya:</strong>
                                <p><?= nl2br(htmlspecialchars($r['tanggapan'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Form Tanggapan -->
                    <form method="post" class="mt-4">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option <?= $r['status']=='Diajukan' ? 'selected':''?>>Diajukan</option>
                                <option <?= $r['status']=='Diproses' ? 'selected':''?>>Diproses</option>
                                <option <?= $r['status']=='Selesai' ? 'selected':''?>>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggapan</label>
                            <textarea name="tanggapan" class="form-control" rows="5" placeholder="Tuliskan tanggapan anda..." required><?= htmlspecialchars($r['tanggapan']) ?></textarea>
                        </div>
                        <button class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim</button>
                        <a href="list_aspirasi.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
