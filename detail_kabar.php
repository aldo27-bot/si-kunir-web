<?php
include("koneksi.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT judul, tanggal, deskripsi, gambar FROM informasi_desa WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($judul, $tanggal, $deskripsi, $gambar);
        $stmt->fetch();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "No news selected!";
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
    <title>Detail Informasi Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" /> <!-- Tambahkan baris ini untuk ikon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<style>

    .detail-container {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .detail-image {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin-top: 25px;
        margin-bottom: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
    <?php include('navbar/upbar.php') ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("navbar/lefbar.php"); ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                <h1 class="mt-4">Detail Informasi Desa</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb mb-4">
                    <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="kabardesa.php">Informasi Desa</a></li>
                        <li class="breadcrumb-item active">Detail Informasi Desa</li>
                    </ol>
                    </nav>

                    <!-- Detail Kabar Desa Content -->
                    <div class="detail-container">
                        <h2 class="detail-title"><?php echo htmlentities($judul); ?></h2>
                        <p class="detail-date"><i class="fas fa-calendar-alt"></i> <?php echo htmlentities($tanggal); ?></p>
                        <?php if ($gambar): ?>
                            <img src="uploads/<?php echo htmlentities($gambar); ?>" class="detail-image" alt="Gambar Kabar">
                        <?php endif; ?>
                        <div class="detail-description">
                            <strong>Deskripsi:</strong>
                            <p><?php echo nl2br(htmlentities($deskripsi)); ?></p>
                        </div>
                    </div>
                    <a href="kabardesa.php" class="btn btn-back mt-4"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript resources -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
