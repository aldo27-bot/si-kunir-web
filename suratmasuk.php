<?php
// Pastikan sesi dimulai paling awal
session_start();

// NOTE: pastikan nama file include benar: 'sesionlogin.php' atau 'sessionlogin.php'
// Jika file Anda bernama sessionlogin.php, ubah include di bawah.
include 'utility/sesionlogin.php';
include 'koneksi.php';

// Periksa koneksi (mengasumsikan koneksi dibuat di $conn)
if (!isset($conn)) {
    die("Koneksi database belum dibuat. Periksa file koneksi.php");
}

if ($conn->connect_error) {
    // Di lingkungan produksi, jangan tampilkan detail error ke user. Log saja.
    error_log("Koneksi DB gagal: " . $conn->connect_error);
    die("Koneksi ke database gagal.");
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
    <title>Pengajuan Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php include('navbar/upbar.php') ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("navbar/lefbar.php"); ?>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 class="" style="margin-top: 0px;">Pengajuan Surat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengajuan Surat</li>
                    </ol>

                    <div class="card mb-4 px-4">
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Pengajuan</th>
                                        <th>ID Surat</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tipe Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT 
                                                ps.id_pengajuan_surat,
                                                ps.no_pengajuan,
                                                ps.nama,
                                                ps.kode_surat,
                                                ps.tanggal,
                                                l.status
                                            FROM pengajuan_surat ps
                                            LEFT JOIN laporan l ON ps.id_laporan = l.id_laporan
                                            ORDER BY ps.id_pengajuan_surat DESC";

                                    $result = $conn->query($sql);

                                    if ($result === false) {
                                        // Log error dan tampilkan pesan sederhana
                                        error_log("Query gagal: " . $conn->error);
                                        echo '<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan saat mengambil data. Silakan coba lagi.</td></tr>';
                                    } elseif ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status = $row['status'] ?? 'N/A';
                                            $badge = '';

                                            if ($status === "Masuk") {
                                                $badge = '<span class="badge bg-warning">Menunggu</span>';
                                            } elseif ($status === "Proses") {
                                                $badge = '<span class="badge bg-primary">Diproses</span>';
                                            } elseif ($status === "Selesai") {
                                                $badge = '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($status === "Tolak") {
                                                $badge = '<span class="badge bg-danger">Ditolak</span>';
                                            } else {
                                                $badge = '<span class="badge bg-secondary">Tidak Diketahui</span>';
                                            }

                                            $id_for_detail = !empty($row['no_pengajuan']) ? $row['no_pengajuan'] : $row['id_pengajuan_surat'];

                                            // Escape output untuk mencegah XSS
                                            $id_pengajuan = htmlspecialchars($row['id_pengajuan_surat']);
                                            $no_pengajuan = htmlspecialchars($row['no_pengajuan']);
                                            $nama = htmlspecialchars($row['nama']);
                                            $kode_surat = strtoupper(htmlspecialchars($row['kode_surat']));
                                            $tanggal = htmlspecialchars($row['tanggal']);

                                            // Gunakan urlencode saat menaruh di query string
                                            $url_id_detail = urlencode($id_for_detail);
                                            $url_kode_surat = urlencode($row['kode_surat']);
                                    ?>
                                            <tr>
                                                <td><?= $id_pengajuan; ?></td>
                                                <td><?= $no_pengajuan; ?></td>
                                                <td><?= $nama; ?></td>
                                                <td><?= $kode_surat; ?></td>
                                                <td><?= $tanggal; ?></td>
                                                <td><?= $badge; ?></td>

                                                <td>
                                                    <!-- Perbaikan: hapus tanda ** dan gunakan urlencode pada parameter -->
                                                    <a class="btn btn-primary btn-sm"
                                                        href="suratmasuk_detail.php ?= $url_id_detail; ?>&kode_surat=<?= $url_kode_surat; ?>">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>

                                                    <?php if ($status === "Masuk" || $status === "Proses") { ?>
                                                        <button type="button" class="btn btn-success btn-sm mt-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalSelesai"
                                                            data-id-pengajuan="<?= $id_pengajuan; ?>">
                                                            <i class="fas fa-check"></i> Selesai
                                                        </button>

                                                        <button type="button" class="btn btn-danger btn-sm mt-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalTolak"
                                                            data-id-pengajuan="<?= $id_pengajuan; ?>">
                                                            <i class="fas fa-times"></i> Tolak
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center">Tidak ada data pengajuan surat.</td></tr>';
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

    <!-- Modal Tolak -->
    <div class="modal fade" id="modalTolak" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="utility/proses_tolak.php" method="POST">
                        <input type="hidden" id="id_tolak" name="id">
                        <label class="col-form-label">Alasan Penolakan:</label>
                        <textarea class="form-control" name="alasan" required></textarea>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Selesai (ubah ke POST) -->
    <div class="modal fade" id="modalSelesai" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyelesaikan pengajuan surat ini?</p>
                    <form action="utility/proses_selesai.php" method="POST" id="formSelesai">
                        <input type="hidden" id="id_selesai" name="id">
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Ya, Selesaikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap dan script lainnya -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <script>
        // Script untuk mengisi ID pada Modal Tolak
        var modalTolak = document.getElementById('modalTolak');
        if (modalTolak) {
            modalTolak.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idPengajuan = button.getAttribute('data-id-pengajuan');

                var inputId = modalTolak.querySelector('#id_tolak');
                if (inputId) inputId.value = idPengajuan;
            });
        }

        // Script untuk mengisi ID pada Modal Selesai
        var modalSelesai = document.getElementById('modalSelesai');
        if (modalSelesai) {
            modalSelesai.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idPengajuan = button.getAttribute('data-id-pengajuan');

                var inputId = modalSelesai.querySelector('#id_selesai');
                if (inputId) inputId.value = idPengajuan;
            });
        }
    </script>

</body>

</html>
