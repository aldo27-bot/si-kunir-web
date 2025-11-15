<?php
include("koneksi.php");

// Ambil 3 berita terbaru
$sql = "SELECT * FROM informasi_desa ORDER BY tanggal DESC LIMIT 3";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Website Resmi Desa Kuncir, Kecamatan Ngetos" />
  <title>Desa Kuncir</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <style>
    :root {
      --primary-blue: #3629B7;
      --secondary-blue: #B36CFF;
      --light-blue: #e3f2fd;
      --dark-blue: #0d47a1;
      --accent-blue: #03a9f4;
      --text-gray: #555;
      --text-dark: #2c3e50;
      --card-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      --hover-shadow: 0 10px 30px rgba(24, 155, 191, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      overflow-x: hidden;
      font-family: 'Inter', 'Segoe UI', sans-serif;
      scroll-behavior: smooth;
      color: var(--text-dark);
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-family: 'Poppins', sans-serif;
    }

    /* Styling tombol scroll to top yang baru */
    #scrollTopBtn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 55px;
      height: 55px;
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 99;
      border: none;
      padding: 0;
      border-radius: 50%;
      background: #e3f2fd;
      color: black;
      font-size: 25px;
      /* Ukuran ikon */
      box-shadow: 0 4px 15px rgba(54, 41, 183, 0.5);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    /* Hover effect yang lebih interaktif */
    #scrollTopBtn:hover {
      transform: scale(1.1) translateY(-3px);
      box-shadow: 0 8px 25px rgba(54, 41, 183, 0.7);
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
      gap: 15px;
      font-size: 15px;
      font-weight: 600;
      color: white !important;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand img {
      height: 70px;
      width: auto;
      filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.2));
    }

    .navbar-brand span {
      line-height: 1.4;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      font-weight: 700;
    }

    .nav-link {
      color: white !important;
      font-weight: 500;
      padding: 8px 20px !important;
      transition: all 0.3s;
      border-radius: 5px;
      margin: 0 5px;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      color: #000000ff !important;
    }

    .download-btn,
    .btn-login {
      background-color: white !important;
      color: var(--primary-blue) !important;
      border: 2px solid white !important;
      padding: 8px 25px !important;
      border-radius: 25px !important;
      font-weight: 600 !important;
      transition: all 0.3s !important;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2) !important;
    }

    .download-btn:hover,
    .btn-login:hover {
      background-color: transparent !important;
      color: white !important;
      transform: scale(1.05);
    }

    /* Carousel */
    .carousel {
      margin: 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .carousel-item img {
      height: 550px;
      object-fit: cover;
      filter: brightness(0.9);
    }

    .carousel-indicators button {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: var(--primary-blue);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: var(--primary-blue);
      border-radius: 50%;
      padding: 30px;
    }

    /* Sections */
    .section-divider {
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--primary-blue), var(--secondary-blue), transparent);
      margin: 60px auto;
      width: 60%;
      border-radius: 2px;
    }

    /* Welcome Section */
    .welcome-section {
      background: linear-gradient(135deg, var(--light-blue) 0%, white 100%);
      padding: 80px 20px;
      position: relative;
      overflow: hidden;
    }

    .welcome-section::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -10%;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(24, 155, 191, 0.1) 0%, transparent 70%);
      border-radius: 50%;
    }

    .welcome-section h1 {
      color: var(--dark-blue);
      font-weight: 700;
      font-size: 2.8rem;
      margin-bottom: 30px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      letter-spacing: -0.5px;
    }

    .welcome-section h5 {
      color: #555;
      line-height: 1.8;
      max-width: 900px;
      margin: 0 auto;
      font-weight: 400;
      font-size: 1.1rem;
    }

    .kepala-desa-name {
      color: var(--secondary-blue);
      font-weight: 600;
      font-size: 1.2rem;
      margin-top: 30px;
    }

    /* Visi Misi Section - IMPROVED */
    .visi-misi {
      padding: 80px 20px;
      background: linear-gradient(180deg, white 0%, #f8f9ff 100%);
    }

    .visi-misi h2 {
      color: var(--dark-blue);
      font-weight: 700;
      text-align: center;
      margin-bottom: 50px;
      font-size: 2.5rem;
      letter-spacing: -0.5px;
    }

    .tab-btn {
      border-radius: 30px;
      padding: 12px 40px;
      margin: 0 10px;
      font-weight: 600;
      transition: all 0.3s;
      font-size: 1.05rem;
      border: 2px solid var(--primary-blue);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%) !important;
      box-shadow: 0 4px 20px rgba(24, 155, 191, 0.3);
      border: none !important;
      color: white !important;
    }

    .btn-outline-primary {
      background: white !important;
      color: var(--primary-blue) !important;
      border: 2px solid var(--primary-blue) !important;
    }

    .btn-outline-primary:hover {
      background: var(--primary-blue) !important;
      color: white !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(24, 155, 191, 0.3);
    }

    /* Visi Content - IMPROVED */
    #visiContent {
      margin-top: 50px;
      padding: 0;
      background: transparent;
      border-radius: 0;
      box-shadow: none;
    }

    .visi-box {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      border-radius: 20px;
      padding: 50px 40px;
      box-shadow: 0 10px 40px rgba(54, 41, 183, 0.25);
      position: relative;
      overflow: hidden;
    }

    .visi-box::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }

    .visi-box::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: -30px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 50%;
    }

    .visi-icon {
      font-size: 3.5rem;
      color: white;
      margin-bottom: 25px;
      opacity: 0.9;
    }

    .visi-text {
      font-size: 1.3rem;
      color: white;
      line-height: 1.9;
      font-weight: 400;
      text-align: center;
      max-width: 900px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }

    /* Misi Content - IMPROVED */
    #misiContent {
      margin-top: 50px;
      padding: 0;
      background: transparent;
      border-radius: 0;
      box-shadow: none;
    }

    .misi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      max-width: 1400px;
      margin: 0 auto;
    }

    .misi-card {
      background: white;
      border-radius: 15px;
      padding: 30px 25px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border-left: 4px solid var(--primary-blue);
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }

    .misi-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(54, 41, 183, 0.2);
      border-left-color: var(--secondary-blue);
    }

    .misi-number {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      color: white;
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.2rem;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(54, 41, 183, 0.3);
    }

    .misi-text {
      color: var(--text-dark);
      font-size: 1rem;
      line-height: 1.7;
      font-weight: 400;
      flex: 1;
    }

    /* News Section */
    .news-section {
      padding: 80px 20px;
      background: white;
    }

    .news-section h2 {
      font-weight: 700;
      color: var(--dark-blue);
      margin-bottom: 50px;
      text-align: center;
      font-size: 2.5rem;
      letter-spacing: -0.5px;
    }

    .news-item {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      transition: all 0.3s ease;
      border: 1px solid #f0f0f0;
    }

    .news-item:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 30px rgba(24, 155, 191, 0.2);
    }

    .news-img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .news-content {
      padding: 25px;
    }

    .news-content h5 {
      font-weight: 600;
      color: var(--dark-blue);
      margin-bottom: 10px;
      font-size: 1.2rem;
    }

    .news-content .text-muted {
      color: var(--primary-blue) !important;
      font-weight: 500;
    }

    .news-content p {
      color: #666;
      line-height: 1.6;
    }

    /* Struktur Section */
    .struktur-section {
      padding: 80px 20px;
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      color: white;
    }

    .struktur-section h2 {
      color: white;
      font-weight: 700;
      text-align: center;
      margin-bottom: 60px;
      font-size: 2.5rem;
      letter-spacing: -0.5px;
    }

    .struktur-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px;
      margin: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      transition: all 0.3s;
      border-top: 4px solid var(--accent-blue);
    }

    .struktur-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
      background: white;
    }

    .struktur-card h5 {
      color: var(--dark-blue);
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 15px;
      border-bottom: 2px solid var(--light-blue);
      padding-bottom: 10px;
    }

    .struktur-card p {
      color: #555;
      font-size: 1rem;
      margin: 0;
      font-weight: 500;
    }

    .kepala-desa-card {
      background: linear-gradient(135deg, var(--accent-blue) 0%, var(--primary-blue) 100%) !important;
      color: white !important;
      border: none !important;
      border-top: 4px solid white !important;
    }

    .kepala-desa-card h5,
    .kepala-desa-card p {
      color: white !important;
      border-bottom-color: rgba(255, 255, 255, 0.3) !important;
    }

    /* Footer */
    .site-footer {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%) !important;
      color: white !important;
      padding: 40px 10px 10px !important;
      margin-top: 0 !important;
      border-top: none !important;
    }

    .site-footer h6 {
      font-weight: 700 !important;
      margin-bottom: 20px !important;
      font-size: 1.2rem !important;
      border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
      padding-bottom: 10px !important;
      color: white !important;
    }

    .site-footer a {
      color: white !important;
      text-decoration: none !important;
      transition: all 0.3s !important;
    }

    .site-footer a:hover {
      color: var(--light-blue) !important;
      padding-left: 5px !important;
    }

    .site-footer ul {
      list-style: none !important;
      padding: 0 !important;
    }

    .site-footer ul li {
      margin-bottom: 12px !important;
    }

    .site-footer .logo-footer {
      max-width: 300px !important;
      margin-bottom: 20px !important;
      filter: brightness(0) invert(1) !important;
    }

    .site-footer p {
      color: white !important;
    }

    .footer-bottom {
      background-color: rgba(0, 0, 0, 0.2) !important;
      text-align: center !important;
      padding: 20px !important;
      margin-top: 40px !important;
      border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
      color: white !important;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .navbar-brand img {
        height: 55px;
      }

      .nav-item {
        margin-bottom: 10px;
      }

      .navbar-brand {
        font-size: 13px;
      }

      .welcome-section h1 {
        font-size: 2rem;
      }

      .carousel-item img {
        height: 400px;
      }

      .struktur-card {
        margin: 10px 0;
      }

      .news-img {
        height: 200px;
      }

      .visi-text {
        font-size: 1.1rem;
      }

      .misi-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .carousel-item img {
        height: 300px;
      }

      .nav-item {
        margin-bottom: 10px;
      }

      .welcome-section h1 {
        font-size: 1.8rem;
      }

      .visi-misi h2,
      .struktur-section h2,
      .news-section h2 {
        font-size: 1.8rem;
      }

      .news-img {
        height: 180px;
      }

      .visi-text {
        font-size: 1rem;
      }

      .visi-box {
        padding: 40px 25px;
      }
      #scrollTopBtn {
                width: 40px;
                /* Sesuaikan ukuran pada layar kecil */
                height: 40px;
                bottom: 15px;
                /* Sesuaikan posisi */
                right: 15px;
            }
    }

    @media (max-width: 576px) {
      .navbar {
        padding: 10px 15px;
      }

      .nav-item {
        margin-bottom: 10px;
      }

      .navbar-brand img {
        height: 45px;
      }

      .welcome-section {
        padding: 50px 15px;
      }

      .struktur-card {
        padding: 20px;
      }

      .tab-btn {
        margin: 5px;
        padding: 10px 25px;
        font-size: 0.95rem;
      }

      .visi-box {
        padding: 30px 20px;
      }

      .visi-text {
        font-size: 0.95rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="assets/img/logonganjuk.png" alt="Logo" />
        <span>Desa Kuncir<br />Kecamatan Ngetos</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="kabarindex.php">Berita Desa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#visi-misi">Visi-Misi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#struktur">Struktur Organisasi</a>
          </li>
          <li class="nav-item">
            <a class="btn download-btn ms-2" href="https://play.google.com/store/apps">
              <i class="fab fa-google-play me-2"></i> unduh apk
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-login ms-2" href="login.php">
              <i class="fas fa-sign-in-alt me-2"></i>Login Admin
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Carousel -->
  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/img/foto_pura_bedugul_bali.jpg" class="d-block w-100" alt="Slide 1" />
      </div>
      <div class="carousel-item">
        <img src="assets/img/foto_raja_ampat.jpg" class="d-block w-100" alt="Slide 2" />
      </div>
      <div class="carousel-item">
        <img src="assets/img/foto_gunung_bromo.jpeg" class="d-block w-100" alt="Slide 3" />
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- Welcome Section -->
  <section class="welcome-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <h1 class="text-center animate__animated animate__fadeInDown">
            Selamat Datang di Website Desa Kami
          </h1>
          <h5 class="text-center animate__animated animate__fadeInUp">
            Kami senang Anda sudah berkunjung, semoga melalui situs web ini kami dapat memberikan segala kemudahan dalam pengajuan surat-surat kepada pemerintah desa kami. Situs web ini merupakan salah satu wujud dari komitmen pemerintah desa, pada pentingnya digitalisasi dan efisiensi zaman.
          </h5>
          <div class="kepala-desa-name text-center">
            Wiwik Sukartinem, Kepala Desa Kuncir
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="section-divider"></div>

  <!-- Visi & Misi -->
  <section id="visi-misi" class="visi-misi text-center">
    <div class="container">
      <h2>Visi & Misi Desa Kuncir</h2>
      <div class="mb-4">
        <button id="btnVisi" class="btn btn-primary tab-btn">Visi</button>
        <button id="btnMisi" class="btn btn-outline-primary tab-btn">Misi</button>
      </div>

      <!-- Konten Visi -->
      <div id="visiContent">
        <div class="visi-box">
          <div class="visi-icon">
            <i class="fas fa-eye"></i>
          </div>
          <p class="visi-text">
            "Terwujudnya Desa Kuncir Yang Rukun Dan Makmur Dan Tersejahteranya Manusia Yang Maju,
            Dengan Di Dukung Pengembangan Ekonomi Berbasis Sumber Daya Alam Yang Berwawasan
            Lingkungan Terdepan Dalam Bidang Pertanian Dan Perkebunan."
          </p>
        </div>
      </div>

      <!-- Konten Misi -->
      <div id="misiContent" style="display:none;">
        <div class="misi-grid">
          <div class="misi-card">
            <div class="misi-number">1</div>
            <div class="misi-text">Mewujudkan dan mengembangkan kegiatan keagamaan untuk menambah keimanan dan ketaqwaan kepada Tuhan Yang Maha Esa.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">2</div>
            <div class="misi-text">Mewujudkan dan mendorong terjadinya usaha-usaha kerukunan antar dan intern warga masyarakat yang disebabkan karena adanya perbedaan agama, keyakinan, organisasi, dan lainnya dalam suasana saling menghargai dan menghormati.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">3</div>
            <div class="misi-text">Membangun dan meningkatkan hasil pertanian dengan jalan penataan pengairan, perbaikan jalan sawah / jalan usaha tani, pemupukan, dan pola tanam yang baik.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">4</div>
            <div class="misi-text">Menata Pemerintahan Desa Kuncir yang kompak dan bertanggung jawab dalam mengemban amanat masyarakat.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">5</div>
            <div class="misi-text">Meningkatkan pelayanan masyarakat secara terpadu dan serius.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">6</div>
            <div class="misi-text">Mencari dan menambah debet air untuk mencukupi kebutuhan pertanian.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">7</div>
            <div class="misi-text">Menumbuh Kembangkan Kelompok Tani dan Gabungan Kelompok Tani serta bekerja sama dengan HIPPA untuk memfasilitasi kebutuhan Petani.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">8</div>
            <div class="misi-text">Menumbuhkembangkan usaha kecil dan menengah.</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">9</div>
            <div class="misi-text">Membangun dan mendorong majunya bidang pendidikan baik formal maupun informal yang mudah diakses dan dinikmati seluruh warga masyarakat tanpa terkecuali yang mampu menghasilkan insan intelektual, inovatif dan entrepreneur (wirausahawan).</div>
          </div>

          <div class="misi-card">
            <div class="misi-number">10</div>
            <div class="misi-text">Membangun dan mendorong usaha-usaha untuk pengembangan dan optimalisasi sektor pertanian, perkebunan, peternakan, dan perikanan, baik tahap produksi maupun tahap pengolahan hasilnya.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="section-divider"></div>

  <!-- Kabar Desa -->
  <section id="berita" class="news-section">
    <div class="container">
      <h2>Berita Desa Terbaru</h2>

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="news-item row g-0 align-items-center">
            <div class="col-md-4">
              <?php if (!empty($row['gambar'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="news-img" alt="Gambar Berita">
              <?php else: ?>
                <img src="assets/no-image.jpg" class="news-img" alt="No Image">
              <?php endif; ?>
            </div>
            <div class="col-md-8">
              <div class="news-content">
                <h5><?php echo htmlspecialchars($row['judul']); ?></h5>
                <p class="text-muted small mb-2">
                  <i class="fas fa-calendar-alt me-2"></i>
                  Tanggal: <?php echo date('d M Y', strtotime($row['tanggal'])); ?>
                </p>
                <p><?php echo nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 200))); ?>...</p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="text-center text-muted py-5">
          <i class="fas fa-inbox fa-3x mb-3"></i>
          <p>Belum ada kabar desa yang tersedia.</p>
        </div>
      <?php endif; ?>

      <div class="text-center mt-5">
        <a href="kabarindex.php" class="btn btn-primary btn-lg px-5 py-3" style="border-radius: 50px; font-weight: 600;">
          <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
        </a>
      </div>
    </div>
  </section>

  <div class="section-divider"></div>

  <!-- Struktur Organisasi Section -->
  <section id="struktur" class="struktur-section">
    <div class="container">
      <h2>Struktur Perangkat Desa</h2>

      <!-- Kepala Desa -->
      <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card kepala-desa-card text-center">
            <h5>KEPALA DESA</h5>
            <p>Hj. Wiwik Sukartinem, S.Pd, M.Si</p>
          </div>
        </div>
      </div>

      <!-- Baris Kedua -->
      <div class="row justify-content-center mt-4">
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>SEKRETARIS DESA</h5>
            <p>Andy Dwi Widya Hartono</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KASI PEMERINTAHAN</h5>
            <p>Suparti</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KASI KESEJAHTERAAN</h5>
            <p>Yaji</p>
          </div>
        </div>
      </div>

      <!-- Baris Ketiga -->
      <div class="row justify-content-center mt-4">
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KAUR UMUM & PERENCANAAN</h5>
            <p>Munirul Ikhwan</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>STAF KEUANGAN</h5>
            <p>Ismaul Farida</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>STAFF 1</h5>
            <p>Nurul Laila</p>
          </div>
        </div>
      </div>

      <!-- Baris Keempat -->
      <div class="row justify-content-center mt-4">
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>STAFF 2</h5>
            <p>Yulia Novitasari</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KASUN KUNCIR</h5>
            <p>Eko Purbayanti</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KASUN NGLAJER</h5>
            <p>Suyono</p>
          </div>
        </div>
      </div>

<!-- Baris Kelima -->
      <div class="row justify-content-center mt-4">
        <div class="col-lg-4 col-md-6">
          <div class="struktur-card text-center">
            <h5>KASUN SUMBER</h5>
            <p>Sukarno</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-2">
          <img src="assets/img/logo_sikunir_icon.png" alt="Logo" class="logo-footer">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <h6>Desa Kuncir</h6>
          <p>Website Resmi Desa Kuncir, Kec Ngetos, Kab. Nganjuk.</p>
        </div>

        <div class="col-md-4 mb-4">
          <h6>Petunjuk Navigasi</h6>
          <ul>
            <li><a href="kabarindex.php">Berita Desa</a></li>
            <li><a href="#visi-misi">Visi Misi</a></li>
            <li><a href="#struktur">Struktur Organisasi</a></li>
          </ul>
        </div>

        <div class="col-md-4 mb-4">
          <h6>Kontak</h6>
          <p><i class="fas fa-home me-2"></i> Dusun Nglajer, Desa Kuncir, Kec. Ngetos, Kabupaten Nganjuk, Jawa Timur 64474</p>
          <p><i class="fas fa-envelope me-2"></i> desa.kuncir@gmail.com</p>
          <p><i class="fas fa-phone me-2"></i> 0358-321294</p>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      © 2025 Sync Squad. All Rights Reserved.
    </div>
  </footer>
  <!-- Tombol Scroll to Top -->
  <button id="scrollTopBtn">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle Visi Misi
    document.getElementById('btnVisi').addEventListener('click', function() {
      document.getElementById('visiContent').style.display = 'block';
      document.getElementById('misiContent').style.display = 'none';
      this.classList.remove('btn-outline-primary');
      this.classList.add('btn-primary');
      document.getElementById('btnMisi').classList.remove('btn-primary');
      document.getElementById('btnMisi').classList.add('btn-outline-primary');
    });

    document.getElementById('btnMisi').addEventListener('click', function() {
      document.getElementById('visiContent').style.display = 'none';
      document.getElementById('misiContent').style.display = 'block';
      this.classList.remove('btn-outline-primary');
      this.classList.add('btn-primary');
      document.getElementById('btnVisi').classList.remove('btn-primary');
      document.getElementById('btnVisi').classList.add('btn-outline-primary');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
  <script>
    // Seleksi tombol Scroll to Top
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    // Fungsionalitas Tampilkan/Sembunyikan tombol
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) { // Tampilkan setelah scroll 300px
        scrollTopBtn.style.display = 'flex';
      } else {
        scrollTopBtn.style.display = 'none';
      }
    });

    // Fungsionalitas Scroll ke atas saat tombol diklik
    scrollTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  </script>
</body>

</html>