<?php
// Note: Assuming sesionlogin.php and koneksi.php are handled correctly.
include("koneksi.php");

// Pastikan $conn tersedia dari koneksi.php
if (!isset($conn)) {
    die("Error: Koneksi database tidak tersedia.");
}

$judul = $tanggal = $deskripsi = $gambar = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT judul, tanggal, deskripsi, gambar FROM informasi_desa WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($judul, $tanggal, $deskripsi, $gambar);
        
        if (!$stmt->fetch()) {
             // Jika data tidak ditemukan
             $judul = "Informasi Tidak Ditemukan";
             $tanggal = "-";
             $deskripsi = "Data dengan ID tersebut tidak ada.";
             $gambar = null;
        }
        $stmt->close();
    } catch (Exception $e) {
        die("Error fetching data: " . $e->getMessage());
    }
} else {
    $judul = "Error";
    $deskripsi = "ID informasi tidak diberikan.";
    $tanggal = "-";
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
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        font-family: 'Inter', sans-serif;
        padding: 1rem 0;
        background-color: var(--light-bg);
        color: #1e293b;
    }

    h1, h2, h3, h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    /* Page Header Structure */
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

    /* Detail Content Card */
    .detail-container {
        background-color: #ffffff;
        border-radius: 16px; /* Lebih besar */
        padding: 30px;
        margin-top: 20px;
        box-shadow: var(--card-shadow);
    }
    .detail-image {
        max-width: 100%;
        height: auto;
        border-radius: 12px; /* Lebih besar */
        margin-top: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }
    .detail-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 0.5rem;
    }
    .detail-date {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .detail-description {
        font-size: 1rem;
        color: #334155;
        margin-top: 20px;
        line-height: 1.7;
    }

    /* Button Back */
    .btn-back {
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 24px;
        transition: all 0.3s ease;
        border: none;
        font-family: 'Inter', sans-serif;
        color: white;
        background: #64748b;
    }
    .btn-back:hover {
        background-color: #475569;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4);
        color: white;
    }
    
    /* Responsive Padding */
    .container-fluid.px-5 {
        padding-left: 2rem !important;
        padding-right: 2rem !important;
    }
    @media (max-width: 768px) {
        .container-fluid.px-5 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        .detail-title {
            font-size: 1.5rem;
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
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <h1 class="">Detail Informasi Desa</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="kabardesa.php">Informasi Desa</a></li>
                                        <li class="breadcrumb-item active">Detail</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-container">
                        <h2 class="detail-title"><?php echo htmlentities($judul ?? 'N/A'); ?></h2>
                        <p class="detail-date">
                             <i class="fas fa-calendar-alt me-1"></i> 
                             <?php echo htmlentities(!empty($tanggal) ? date('d F Y', strtotime($tanggal)) : 'Tanggal Tidak Tersedia'); ?>
                        </p>
                        
                        <?php if ($gambar): ?>
                            <img src="uploads/<?php echo htmlentities($gambar); ?>" class="detail-image" alt="Gambar Kabar">
                        <?php endif; ?>
                        
                        <div class="detail-description">
                            <p><?php echo nl2br(htmlentities($deskripsi ?? '')); ?></p>
                        </div>
                    </div>
                    
                    <a href="kabardesa.php" class="btn btn-back mt-4"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>