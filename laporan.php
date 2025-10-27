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
                                        <th>NIK</th>
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
                                        <th>NIK</th>
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
                                        $sql = "SELECT DISTINCT laporan.id, pengajuan_surat.nik, 
                                                pengajuan_surat.nama, pengajuan_surat.kode_surat,
                                                DATE (laporan.tanggal) AS tanggal, laporan.status, pengajuan_surat.no_pengajuan
                                                FROM laporan
                                                JOIN pengajuan_surat ON pengajuan_surat.id = laporan.id
                                                ORDER BY laporan.id DESC;";
                                        $query = $conn->prepare($sql);
                                        $query->execute();

                                        $query->store_result();
                                        $rowCount = $query->num_rows;

                                        if ($rowCount > 0) {
                                            $query->bind_result($id, $nik, $nama, $kode_surat, $tanggal, $status, $no_pengajuan);
                                            while ($query->fetch()) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($id); ?></td>
                                                    <td><?php echo htmlentities($nik); ?></td>
                                                    <td><?php echo htmlentities($nama); ?></td>
                                                    <td><?php echo htmlentities($kode_surat); ?></td>
                                                    <td><?php echo htmlentities($tanggal); ?></td>
                                                    <td><?php echo htmlentities($status); ?></td>
                                                    <td style="text-align: center;">
                                                        <a class="btn btn-primary mt-lg-0" role="button"
                                                           href="suratmasuk_detail.php?no_pengajuan=<?php echo urlencode(trim($no_pengajuan)); ?>&kode_surat=<?php echo urlencode(trim($kode_surat)); ?>&id=<?php echo urlencode(trim($id)); ?>&user=<?php echo htmlentities($_SESSION['username'] ?? 'guest'); ?>">
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
                { "searchable": true },
                { "searchable": true },
                { "searchable": false },
                { "searchable": false },
                { "searchable": false },
                { "searchable": false }
            ]
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
