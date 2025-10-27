<?php  
include("koneksi.php");

try {
    $sql = "SELECT judul, tanggal, deskripsi, gambar FROM kabar_desa ORDER BY tanggal DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($judul, $tanggal, $deskripsi, $gambar);
    
    $berita = [];
    while ($stmt->fetch()) {
        $berita[] = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'deskripsi' => $deskripsi,
            'gambar' => $gambar
        ];
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kabar Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        html, body {
            overflow-x: hidden; /* Mencegah scroll horizontal di seluruh halaman */
        }

        .detail-container {
            display: flex;
            align-items: flex-start;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .detail-image {
            width: 200px; /* Ukuran gambar tetap */
            height: 200px; /* Ukuran gambar tetap */
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px; /* Jarak antara gambar dan deskripsi */
        }

        .detail-content {
            max-width: 600px;
        }

        .detail-title {
            font-size: 24px;
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
            margin-top: 10px;
        }

        img {
            max-width: 100%; /* Menghindari gambar melebihi lebar layar */
            height: auto;
        }

        /* Menyesuaikan tampilan agar responsif */
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Media query untuk perangkat mobile */
        @media (max-width: 768px) {
            .detail-container {
                flex-direction: column; /* Mengatur layout menjadi vertikal pada layar kecil */
                align-items: center; /* Menyusun gambar dan konten secara vertikal */
                padding: 15px;
            }

            .detail-image {
                width: 100%; /* Gambar akan mengisi lebar kontainer */
                height: auto; /* Menjaga rasio gambar */
                margin-right: 0; /* Menghilangkan jarak kanan pada gambar */
            }

            .detail-content {
                max-width: 100%; /* Menyesuaikan konten agar tidak melebihi layar */
            }

            .detail-title {
                font-size: 20px; /* Mengurangi ukuran font judul di layar kecil */
            }

            .detail-date {
                font-size: 12px; /* Menyesuaikan ukuran font tanggal di layar kecil */
            }

            .detail-description {
                font-size: 14px; /* Menyesuaikan ukuran font deskripsi */
            }

            .btn {
                width: 100%; /* Membuat tombol penuh pada layar kecil */
            }
        }
    </style>
</head>
<body class="overflow-x-hidden">
<nav class="navbar navbar-expand-lg sticky-top bg-white">
    <div class="container-fluid">
        <!-- Image and Text -->
        <a class="navbar-brand" href="index.html">
            <img
                src="assets/img/logonganjuk.png"
                alt="Logo"
                style="max-height: 70px; margin-right: 10px; margin-top: -30px"
            />
            <span class="d-inline-block" style="font-weight: 500">
                Desa Kauman <br />Kecamatan Nganjuk
            </span>
        </a>

        <!-- Navbar Toggler -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="#">Kabar Desa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Halaman Awal</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="login.php">Login Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container-fluid px-5">
        <h1 class="mt-4">Kabar Desa</h1>

        <?php foreach ($berita as $data): ?>
            <div class="detail-container">
                <?php if ($data['gambar']): ?>
                    <img src="uploads/<?php echo htmlentities($data['gambar']); ?>" class="detail-image" alt="Gambar Kabar">
                <?php endif; ?>
                <div class="detail-content">
                    <h2 class="detail-title"><?php echo htmlentities($data['judul']); ?></h2>
                    <p class="detail-date"><strong>Tanggal:</strong> <?php echo htmlentities($data['tanggal']); ?></p>
                    <div class="detail-description">
                        <strong>Deskripsi:</strong>
                        <p><?php echo nl2br(htmlentities($data['deskripsi'])); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="index.html" class="btn btn-secondary mt-3">Kembali ke Beranda</a>
    </div>
</main>

<!-- JavaScript resources -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

<footer class="text-center text-lg-start text-white" style="background-color: #189bbf">
    <style>
        footer {
        margin-top: 50px;
        padding-top: 20px;
    }

    footer .container {
        padding-top: 20px;
        padding-bottom: 20px;
    }
    </style>

    <!-- Grid container -->
    <div class="container p-4 pb-0">
        <!-- Section: Links -->
        <section>
            <div class="row">
                <!-- Logo -->
                <div class="col-12 text-center mb-4">
                    <img src="assets/img/logoelbg.png" alt="logoel" style="width: 400px;">
                </div>

                <!-- Kelurahan Info -->
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 font-weight-bold">
                        Kelurahan Kauman
                    </h6>
                    <p>Website Resmi Kelurahan Kauman, Kec/Kab. Nganjuk.</p>
                </div>

                <!-- Links -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 font-weight-bold">Link</h6>
                    <ul class="list-unstyled">
                        <li><a class="text-white" href="kabarindex.php">Kabar Desa</a></li>
                        <li><a class="text-white" href="index.html">Visi Misi</a></li>
                        <li><a class="text-white" href="index.html">Struktur Organisasi</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 font-weight-bold">Contact</h6>
                    <p><i class="fas fa-home mr-3"></i> Jl. Gatot Subroto 100, Kel. Kauman, Kec/Kab. Nganjuk, 64411</p>
                    <p><i class="fas fa-envelope mr-3"></i> kelurahankauman@gmail.com</p>
                    <p><i class="fas fa-phone mr-3"></i> 0358-321294</p>
                </div>
            </div>
        </section>
    </div>
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: #0b8d9e">
      © 2024 Copyright: B2
    </div>
</footer>
</body>
</html>
