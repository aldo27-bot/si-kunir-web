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
    <title>Informasi Desa</title>
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
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-5">
                    <h1 style="margin-top: 50px;">Informasi Desa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Informasi Desa</li>
                    </ol>
                    <div class="container-fluid px-5">
                        <?php
                        // Tampilkan notifikasi jika ada status
                        if (isset($_GET['status'])) {
                            $status = $_GET['status'];
                            $message = '';
                            $alertType = '';

                            switch ($status) {
                                case 'success':
                                    $_SESSION['notification'] = [
                                        'message' => 'Informasi desa berhasil ditambahkan.',
                                        'type' => 'success'
                                    ];
                                    break;
                                case 'updated':
                                    $_SESSION['notification'] = [
                                        'message' => 'Informasi desa berhasil diperbarui.',
                                        'type' => 'primary'
                                    ];
                                    break;
                                case 'deleted':
                                    $_SESSION['notification'] = [
                                        'message' => 'Informasi desa berhasil dihapus.',
                                        'type' => 'danger'
                                    ];
                                    break;
                            }
                        }

                        if (isset($_SESSION['notification'])) {
                            $notification = $_SESSION['notification'];
                            echo "<div class='alert alert-{$notification['type']} alert-dismissible fade show' role='alert'>
                            {$notification['message']}
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";

                            // Hapus notifikasi dari session setelah ditampilkan
                            unset($_SESSION['notification']);
                        }
                        ?>
                    </div>
                    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#tambahKabarModal">Tambah Informasi</button>
                    <div class="card mb-4 px-4">
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include("koneksi.php");

                                    try {
                                        $sql = "SELECT id_informasi_desa, judul, tanggal, deskripsi, gambar FROM informasi_desa ORDER BY id_informasi_desa DESC";
                                        $query = $conn->prepare($sql);
                                        $query->execute();

                                        $query->store_result();
                                        $rowCount = $query->num_rows;

                                        if ($rowCount > 0) {
                                            $query->bind_result($id, $judul, $tanggal, $deskripsi, $gambar);

                                            while ($query->fetch()) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($id); ?></td>
                                                    <td><?php echo htmlentities($judul); ?></td>
                                                    <td><?php echo htmlentities($tanggal); ?></td>
                                                    <td><?php echo htmlentities($deskripsi); ?></td>
                                                    <td>
                                                        <?php if ($gambar): ?>
                                                            <img src="uploads/<?php echo htmlentities($gambar); ?>" width="50" height="50" alt="Gambar Kabar">
                                                        <?php else: ?>
                                                            Tidak Ada Gambar
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="detail_kabar.php?id=<?php echo urlencode($id); ?>" class="btn btn-info btn-sm mt-lg-0">Detail</a>
                                                        <button class="btn btn-warning btn-sm mt-lg-0" onclick="openEditModal('<?php echo $id; ?>', '<?php echo $judul; ?>', '<?php echo $tanggal; ?>', '<?php echo $deskripsi; ?>', '<?php echo $gambar; ?>')">Edit</button>
                                                        <a href="hapus_kabar.php?id=<?php echo urlencode($id); ?>" class="btn btn-danger btn-sm mt-lg-0" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No results found.</td></tr>";
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

    <!-- Tambah Kabar Modal -->
    <div class="modal fade" id="tambahKabarModal" tabindex="-1" aria-labelledby="tambahKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKabarModalLabel">Tambah Informasi Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="proses_tambah_kabar.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Kabar Modal -->
    <div class="modal fade" id="detailKabarModal" tabindex="-1" aria-labelledby="detailKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailKabarModalLabel">Detail Informasi Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="detail_id"></span></p>
                    <p><strong>Judul:</strong> <span id="detail_judul"></span></p>
                    <p><strong>Tanggal:</strong> <span id="detail_tanggal"></span></p>
                    <p><strong>Deskripsi:</strong> <span id="detail_deskripsi"></span></p>
                    <div><strong>Gambar:</strong></div>
                    <div id="detail_gambar">
                        <!-- The image will be displayed here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Kabar Modal -->
    <div class="modal fade" id="editKabarModal" tabindex="-1" aria-labelledby="editKabarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKabarModalLabel">Edit Informasi Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="proses_edit_kabar.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
                            <img id="edit_gambar_preview" width="100" height="100" alt="Gambar Kabar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- JavaScript resources -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <script>
        // Script to open Detail Modal with existing data
        function openDetailModal(id_informasi_desa, judul, tanggal, deskripsi, gambar) {
            document.getElementById('detail_id').textContent = id_informasi_desa;
            document.getElementById('detail_judul').textContent = judul;
            document.getElementById('detail_tanggal').textContent = tanggal;
            document.getElementById('detail_deskripsi').textContent = deskripsi;
            const detailGambarContainer = document.getElementById('detail_gambar');
            if (gambar) {
                detailGambarContainer.innerHTML = `<img src="uploads/${gambar}" width="100" height="100" alt="Gambar Kabar">`;
            } else {
                detailGambarContainer.innerHTML = '<p>Tidak ada gambar</p>';
            }
            const detailModal = new bootstrap.Modal(document.getElementById('detailKabarModal'));
            detailModal.show();
        }

        function openEditModal(id_informasi_desa, judul, tanggal, deskripsi, gambar) {
            // Ambil elemen modal edit dan atur isian form dengan data yang diterima
            document.getElementById('edit_id').value = id_informasi_desa;
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_deskripsi').value = deskripsi;
            if (gambar) {
                document.getElementById('edit_gambar_preview').src = 'uploads/' + gambar;
            }
            const editModal = new bootstrap.Modal(document.getElementById('editKabarModal'));
            editModal.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>