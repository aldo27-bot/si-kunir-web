<?php
include("koneksi.php");

$sql = "SELECT * FROM kabar_desa ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kabar Desa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .news-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border: none;
      border-radius: 12px;
      overflow: hidden;
    }
    .news-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .news-img {
      height: 200px;
      object-fit: cover;
    }
    .news-title {
      font-size: 1.2rem;
      font-weight: 600;
    }
    .news-date {
      font-size: 0.9rem;
      color: #6c757d;
    }
    .news-desc {
      font-size: 0.95rem;
      color: #333;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="#">Desa Kuncir</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a href="index.php" class="nav-link">Halaman Awal</a></li>
        <li class="nav-item"><a href="login.php" class="btn btn-primary btn-sm ms-2">Login Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h2 class="fw-bold mb-4 text-center text-primary">Berita Desa</h2>

  <div class="row g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card news-card h-100">
            <?php if (!empty($row['gambar'])): ?>
              <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="card-img-top news-img" alt="Gambar Kabar">
            <?php else: ?>
              <img src="assets/no-image.jpg" class="card-img-top news-img" alt="No Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="news-title"><?php echo htmlspecialchars($row['judul']); ?></h5>
              <p class="news-date mb-1">📅 <?php echo date('d M Y', strtotime($row['tanggal'])); ?></p>
              <p class="news-desc"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted">
        <p>Belum ada kabar desa yang tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-white border-top">
  <small>© <?php echo date('Y'); ?> Desa Kuncir - Semua Hak Dilindungi</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
