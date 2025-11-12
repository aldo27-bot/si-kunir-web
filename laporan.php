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
    <title>Laporan</title>
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

        <!-- isi konten -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 class="" style="margin-top: 50px;">Laporan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                    <div class="card mb-4 px-4">
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Kode Surat</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Kode Surat</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include("koneksi.php");

                                    try {
                                        $sql = "SELECT laporan.id_laporan,
                                                    pengajuan_surat.username,
                                                    pengajuan_surat.nama,
                                                    pengajuan_surat.kode_surat,
                                                    DATE(laporan.tanggal) AS tanggal,
                                                    laporan.status,
                                                    pengajuan_surat.no_pengajuan
                                                FROM laporan
                                                JOIN pengajuan_surat 
                                                ON pengajuan_surat.id_laporan = laporan.id_laporan
                                                ORDER BY laporan.id_laporan DESC";

                                        $query = $conn->prepare($sql);
                                        $query->execute();
                                        $result = $query->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($r = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?= $r['id_laporan'] ?></td>
                                                    <td><?= $r['username'] ?></td>
                                                    <td><?= $r['nama'] ?></td>
                                                    <td><?= $r['kode_surat'] ?></td>
                                                    <td><?= $r['tanggal'] ?></td>
                                                    <td><?= $r['status'] ?></td>
                                                    <td style="text-align: center;">
                                                        <a class="btn btn-primary"
                                                            href="cetak/cek_surat.php?no_pengajuan=<?= urlencode($r['no_pengajuan']) ?>&kode_surat=<?= urlencode($r['kode_surat']) ?>">
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                    <?php }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data ditemukan.</td></tr>";
                                        }
                                    } catch (Exception $e) {
                                        die("Error: " . $e->getMessage());
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        $('#datatablesSimple').dataTable({
            "columns": [
                null,
                {
                    "searchable": true
                },
                {
                    "searchable": true
                },
                {
                    "searchable": false
                },
                {
                    "searchable": false
                },
                {
                    "searchable": false
                },
                {
                    "searchable": false
                }
            ]
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>