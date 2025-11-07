<?php
include("koneksi.php");

// Validasi koneksi
if (!$conn) {
  die("Koneksi database gagal.");
}

$sql = "SELECT id, judul, deskripsi, tanggal, gambar FROM kabar_desa ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informasi Desa — Desa Kuncir</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <style>
    :root {
      --primary-blue: #3629B7;
      --secondary-blue: #B36CFF;
      --light-blue: #e3f2fd;
      --dark-blue: #0d47a1;
      --accent-blue: #03a9f4;
      --text-gray: #555;
      --card-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      --hover-shadow: 0 10px 30px rgba(24, 155, 191, 0.2);
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
    }

    /* Navbar */
    .navbar {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%) !important;
      box-shadow: 0 4px 20px rgba(24, 155, 191, 0.3);
      padding: 15px 30px;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 16px;
      font-weight: 700;
      color: white !important;
    }

    .navbar-brand img {
      height: 60px;
      width: auto;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .nav-link {
      color: white !important;
      font-weight: 500;
      padding: 8px 20px !important;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      color: #000000ff !important;
    }

    .btn-login-admin {
      background-color: white !important;
      color: var(--primary-blue) !important;
      border: 2px solid white !important;
      padding: 8px 22px !important;
      border-radius: 25px !important;
      font-weight: 600 !important;
      transition: all 0.3s !important;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2) !important;
    }

    .btn-login-admin:hover {
      background-color: transparent !important;
      color: white !important;
      transform: scale(1.05);
    }

    /* Berita */
    .news-card {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      margin-bottom: 30px;
      transition: all 0.3s ease;
      border: 1px solid #f0f0f0;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .news-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--hover-shadow);
    }

    .news-img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .card-body {
      padding: 25px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .news-title {
      font-weight: 600;
      color: var(--dark-blue);
      margin-bottom: 12px;
      font-size: 1.3rem;
      line-height: 1.4;
    }

    .news-date {
      color: var(--primary-blue);
      font-weight: 500;
      font-size: 0.95rem;
      margin-bottom: 15px;
    }

    .news-desc {
      color: var(--text-gray);
      line-height: 1.6;
      flex: 1;
    }

    /* Empty state */
    .empty-state {
      padding: 3rem 1rem;
      text-align: center;
      color: #78909c;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
      color: var(--primary-blue);
    }

    /* Footer */
    footer {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      color: white;
      padding: 30px 0;
      margin: 0;
      width: 100%;
    }

    footer .footer-text {
      font-size: 0.95rem;
    }


    /* Responsive */
    @media (max-width: 992px) {
      .navbar-brand img {
        height: 50px;
      }

      .news-img {
        height: 200px;
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 0;
      }

      .news-img {
        height: 180px;
      }

      .news-title {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 576px) {
      .navbar {
        padding: 10px 15px;
      }

      .navbar-brand img {
        height: 45px;
      }

      .news-img {
        height: 160px;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="assets/img/logonganjuk.png" alt="Logo Desa Kuncir" />
        <span>Desa Kuncir<br />Kecamatan Ngetos</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Halaman Awal</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-login-admin ms-2" href="login.php">
              <i class="fas fa-sign-in-alt me-2"></i>Login Admin
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Konten Utama -->
  <div class="container py-5">
    <div class="text-center mb-5">
      <h1 class="fw-bold" style="color: var(--dark-blue);">Berita & Informasi Desa</h1>
      <p class="text-muted">Update terbaru dari Desa Kuncir</p>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="news-card animate__animated animate__fadeInUp">
              <?php
              $gambar = !empty(trim($row['gambar'])) ? 'uploads/' . htmlspecialchars($row['gambar']) : 'assets/no-image.jpg';
              ?>
              <img src="<?php echo $gambar; ?>" class="news-img"
                alt="<?php echo htmlspecialchars($row['judul'] ?? 'Berita Desa'); ?>">
              <div class="card-body">
                <h5 class="news-title"><?php echo htmlspecialchars($row['judul'] ?? 'Tanpa Judul'); ?></h5>
                <p class="news-date">
                  <i class="fas fa-calendar-alt me-2"></i>
                  <?php echo date('d M Y', strtotime($row['tanggal'] ?? 'now')); ?>
                </p>
                <p class="news-desc">
                  <?php
                  $desc = strip_tags($row['deskripsi'] ?? '');
                  echo nl2br(htmlspecialchars(substr($desc, 0, 180))) . (strlen($desc) > 180 ? '...' : '');
                  ?>
                </p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h4 class="fw-semibold">Belum Ada Berita</h4>
        <p>Belum ada informasi terbaru dari Desa Kuncir saat ini.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Footer -->
  <footer class="text-center">
    <div class="container">
      <p class="footer-text mb-1">&copy; <?php echo date('Y'); ?> Desa Kuncir — Sistem Informasi Desa</p>
      <p class="footer-text mb-0 text-light">Melayani dengan transparansi dan kecepatan</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>