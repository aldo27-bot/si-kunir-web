<?php
include 'utility/sesionlogin.php';


// include("koneksi.php");

// Ambil semua data aspirasi (tanpa nama pengaju)
$query = "
    SELECT id_pengajuan_aspirasi, judul, kategori, status, tanggal 
    FROM pengajuan_aspirasi 
    ORDER BY tanggal DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pengajuan Aspirasi</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php'); ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include('navbar/lefbar.php'); ?>
        </div>
        <div id="layoutSidenav_content">
            <main class="container-fluid px-5">
                <h1 class="" style="margin-top: 50px;">Pengajuan Aspirasi</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengajuan Aspirasi</li>
                </ol>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <table id="datatablesSimple"
                            class="table table-striped table-bordered text-center align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($result) > 0):
                                    while ($r = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($r['judul']) ?></td>
                                    <td><?= htmlspecialchars($r['kategori'] ?? '-') ?></td>
                                    <td>
                                        <?php
                                                $statusColor = match (strtolower($r['status'])) {
                                                    'menunggu' => 'secondary',
                                                    'diproses' => 'warning',
                                                    'selesai' => 'success',
                                                    default => 'dark'
                                                };
                                                ?>
                                        <span class="badge bg-<?= $statusColor ?>">
                                            <?= htmlspecialchars($r['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($r['tanggal']) ?></td>
                                    <td>
                                        <a href="respond.php?id_pengajuan_aspirasi=<?= $r['id_pengajuan_aspirasi'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-reply"></i> Tanggapi
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                    endwhile;
                                else:
                                    ?>
                                <tr>
                                    <td colspan="6" class="text-muted">Belum ada data aspirasi.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const table = document.querySelector("#datatablesSimple");
        if (table) new simpleDatatables.DataTable(table);
    });
    </script>
</body>

</html>