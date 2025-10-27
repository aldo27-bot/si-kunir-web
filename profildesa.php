<?php  
include 'utility/sesionlogin.php';  
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Profil Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" /> <!-- Tambahkan baris ini untuk ikon -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php'); ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("navbar/lefbar.php"); ?>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 class="mt-5">Profil Desa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profil Desa</li>
                    </ol>

                    <style>
                        .profilp1, .profilp3 {
                            text-align: center;
                            font-weight: bold;
                            margin-top: 1rem; 
                        }
                        .profilh2, .perangkath3 {
                            text-align: center;
                            font-weight: bold;
                            margin-top: 2rem;
                        }
                    </style>

                    <h2 class="profilh2">Visi Misi</h2>
                    <p class="profilp1">Visi</p>
                    <p class="profilp2">Terwujudnya Tata Kelola Pemerintahan Desa yang Baik dan Transparan Guna Mewujudkan Desa yang Bermarwah.</p>
                    <p class="profilp3">Misi</p>
                    <ol class="profilp4">
                        <li>Meningkatkan Budaya Gotong Royong</li>
                        <li>Keamanan dan ketertiban di lingkungan desa Kauman</li>
                        <li>Meningkatkan program kesehatan, kebersihan desa serta mengusahakan KIS atau BPJS untuk seluruh masyarakat melalui program pemerintah</li>
                        <li>Mewujudkan dan meningkatkan serta meneruskan tata kelola pemerintahan desa yang baik</li>
                        <li>Meningkatkan pelayanan yang maksimal kepada masyarakat desa</li>
                    </ol>

                    <h3 class="perangkath3">Perangkat</h3>
                    <ol class="perangkat4">
                        <li>Kepala Desa: Lurah</li>
                        <li>Sekretaris: Carik</li>
                        <li>Bendahara: Uang</li>
                    </ol>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
