<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// include 'utility/sesionlogin.php';

include("koneksi.php"); // koneksi pakai $conn

if (!isset($_SESSION['flash'])) $_SESSION['flash'] = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_pengajuan_aspirasi']) ? (int)$_POST['id_pengajuan_aspirasi'] : 0;
    $status = $_POST['status'] ?? '';
    $tanggapan = $_POST['tanggapan'] ?? '';

    if ($id <= 0 || trim($tanggapan) === '') {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data tidak lengkap (ID atau tanggapan kosong).'];
        header('Location: respond.php?id_pengajuan_aspirasi=' . $id);
        exit;
    }

    try {
        // 🔹 Update status & tanggapan aspirasi
        $stmt = $conn->prepare("UPDATE pengajuan_aspirasi SET status=?, tanggapan=? WHERE id_pengajuan_aspirasi=?");
        $stmt->bind_param("ssi", $status, $tanggapan, $id);
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan tanggapan: ' . $conn->error];
            header('Location: respond.php?id_pengajuan_aspirasi=' . $id);
            exit;
        }

        // 🔹 Ambil username dari tabel aspirasi
        $getUser = $conn->prepare("SELECT username FROM pengajuan_aspirasi WHERE id_pengajuan_aspirasi=?");
        $getUser->bind_param("i", $id);
        $getUser->execute();
        $resUser = $getUser->get_result();
        $userData = $resUser->fetch_assoc();
        $getUser->close();

        $username = $userData ? $userData['username'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'admin');

        // 🔹 Simpan notifikasi ke tabel notifikasi
        $pesan = "Aspirasi Anda telah ditanggapi. Status: $status. Tanggapan: " . substr($tanggapan, 0, 50) . (strlen($tanggapan) > 50 ? '...' : '');
        $notif = $conn->prepare("INSERT INTO notifikasi (username, pesan) VALUES (?, ?)");
        $notif->bind_param("ss", $username, $pesan);
        $notif->execute();
        $notif->close();

        // 🔹 Ambil FCM token user (untuk pengecekan)
        $getToken = $conn->prepare("SELECT fcm_token FROM akun_user WHERE username=?");
        $getToken->bind_param("s", $username);
        $getToken->execute();
        $resToken = $getToken->get_result();
        $tokenData = $resToken->fetch_assoc();
        $getToken->close();

        $token = $tokenData['fcm_token'] ?? '';

        // 🔹 Jika token kosong, tulis ke log dan lanjut tanpa error
        if (empty($token)) {
            file_put_contents('fcm_log.txt', "[" . date('Y-m-d H:i:s') . " - $username] Token kosong - tidak dikirim ke FCM\n", FILE_APPEND);
        } else {
            // 🔹 Kirim notifikasi FCM ke user pengaju
            $url = "http://localhost/si-kunir-web-1/DatabaseMobile/fcm_notification.php";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'username' => $username,
                'title' => 'Balasan Aspirasi',
                'message' => "Admin telah menanggapi aspirasi Anda.\nStatus: $status\nTanggapan: " . substr($tanggapan, 0, 100) . "..."
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                file_put_contents('fcm_log.txt', "[" . date('Y-m-d H:i:s') . " - $username] CURL Error: $error\n", FILE_APPEND);
            } else {
                file_put_contents('fcm_log.txt', "[" . date('Y-m-d H:i:s') . " - $username] => $response\n", FILE_APPEND);
            }

            curl_close($ch);
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tanggapan berhasil disimpan dan notifikasi dikirim.'];
        header('Location: list_aspirasi.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Error: ' . $e->getMessage()];
        header('Location: respond.php?id_pengajuan_aspirasi=' . $id);
        exit;
    }
}

// 🔹 GET: ambil data aspirasi
$id = isset($_GET['id_pengajuan_aspirasi']) ? (int)$_GET['id_pengajuan_aspirasi'] : 0;
if ($id <= 0) {
    echo "ID aspirasi tidak diberikan.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM pengajuan_aspirasi WHERE id_pengajuan_aspirasi=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$r = $result->fetch_assoc();
$stmt->close();

if (!$r) {
    echo "Data aspirasi tidak ditemukan.";
    exit;
}

$flash = $_SESSION['flash'];
$_SESSION['flash'] = null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tanggapi Aspirasi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="css/styles.css" rel="stylesheet" /> <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #3629B7;
            --secondary-blue: #B36CFF;
            --success-green: #10b981;
            --warning-orange: #f59e0b;
            --danger-red: #ef4444;
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
        
        /* Page Header - Disesuaikan untuk layout Dashboard */
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
        /* End Page Header */

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
        
        .card-title {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1.5rem;
        }

        /* Form Controls */
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

        /* Buttons */
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
        
        .btn-secondary {
            background: #64748b;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        /* Image Styling */
        .aspirasi-image {
            border-radius: 12px;
            max-width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }
        
        /* Tanggapan Sebelumnya */
        .prev-response {
            background-color: #f1f5f9 !important; 
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
        }
        .prev-response strong {
            color: #334155;
        }
        
        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            font-weight: 500;
            box-shadow: var(--card-shadow);
            animation: slideInDown 0.5s ease;
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-red) 0%, #dc2626 100%);
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
                                    <i class="fas fa-paper-plane me-1"></i>
                                </div>
                                <div>
                                    <h1 class="">Tanggapi Aspirasi</h1>
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="list_aspirasi.php">Pengajuan Aspirasi</a></li>
                                        <li class="breadcrumb-item active">Tanggapi Aspirasi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-0">
                        <?php if ($flash): ?>
                            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?= ($flash['type'] == 'success' ? 'check-circle' : 'exclamation-triangle') ?> me-2"></i>
                                <?= htmlspecialchars($flash['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card mb-5">
                            <div class="card-body">
                                <h4 class="card-title mb-1"><?= htmlspecialchars($r['judul']) ?></h4>
                                <span class="badge bg-secondary mb-3"><i class="fas fa-tag me-1"></i> <?= htmlspecialchars($r['kategori'] ?? 'N/A') ?></span>
                                <small class="text-muted d-block mb-3"><i class="fas fa-calendar-alt me-1"></i> Tanggal: <?= htmlspecialchars($r['tanggal'] ?? '-') ?></small>
                                
                                <p class="mt-3" style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($r['deskripsi'])) ?></p>

                                <?php if (!empty($r['foto'])): ?>
                                    <p class="mt-3">
                                        <strong><i class="fas fa-image me-1"></i> Bukti Foto:</strong><br>
                                        <img src="uploads/<?= htmlspecialchars($r['foto']) ?>" class="aspirasi-image" alt="Foto Aspirasi">
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($r['tanggapan'])): ?>
                                    <div class="prev-response">
                                        <strong><i class="fas fa-comment-dots me-1"></i> Tanggapan sebelumnya:</strong><br>
                                        <span class="text-secondary" style="font-size: 0.9rem;">Status terakhir: <?= strtoupper(htmlspecialchars($r['status'])) ?></span>
                                        <p class="mt-2 mb-0" style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($r['tanggapan'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <form method="post">
                            <input type="hidden" name="id_pengajuan_aspirasi" value="<?= htmlspecialchars($r['id_pengajuan_aspirasi']) ?>">
                            
                            <h3 class="mb-4 text-primary">Form Balasan</h3>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label"><i class="fas fa-sliders-h me-1"></i> Ubah Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="menunggu" <?= $r['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="diproses" <?= $r['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $r['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="tanggapan" class="form-label"><i class="fas fa-pen-alt me-1"></i> Tulis Tanggapan Resmi</label>
                                <textarea name="tanggapan" id="tanggapan" class="form-control" rows="6" required><?=($r['tanggapan']) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="list_aspirasi.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i> Kirim Tanggapan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>