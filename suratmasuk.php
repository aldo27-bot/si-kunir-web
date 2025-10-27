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
    <title>Pengajuan Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" /> <!-- Tambahkan baris ini untuk ikon -->
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
                    <h1 class="" style="margin-top: 50px;">Pengajuan Surat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengajuan Surat</li>
                    </ol>

                    <div class="card mb-4 px-4">
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tipe Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tipe Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Detail</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include("koneksi.php");

                                    try {
                                        $sql = "SELECT pengajuan_surat.id as id, 
                                        pengajuan_surat.nik, 
                                        pengajuan_surat.nama, 
                                        pengajuan_surat.kode_surat, 
                                        pengajuan_surat.tanggal, 
                                        pengajuan_surat.no_pengajuan
                                        FROM pengajuan_surat
                                        JOIN laporan
                                        on pengajuan_surat.id = laporan.id
                                        where laporan.status = 'Masuk' or laporan.status ='Proses'
                                        GROUP by id  
                                        ORDER BY `pengajuan_surat`.`id` DESC";
                                        $query = $conn->prepare($sql);
                                        $query->execute();

                                        $query->store_result(); // This is necessary to use num_rows with prepared statements
                                        $rowCount = $query->num_rows;

                                        if ($rowCount > 0) {
                                            $query->bind_result($id, $nik, $nama, $kode_surat, $tanggal, $no_pengajuan);

                                            while ($query->fetch()) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo htmlentities($id); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlentities($nik); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($nama); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlentities($kode_surat); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlentities($tanggal); ?>
                                                    </td>
                                                    
                                                    <td class="">
                                                        <a class="btn btn-primary mt-lg-0" role="button"
                                                        href="suratmasuk_detail.php?no_pengajuan=<?php echo urlencode(trim($no_pengajuan)); ?>&kode_surat=<?php echo urlencode(trim(htmlentities($kode_surat))); ?>&id=<?php echo urlencode(trim(htmlentities($id))); ?>&user=<?php echo htmlentities($user) ?>">
                                                        Detail
                                                        </a>
                                                        <a class="btn btn-success mt-lg-0 mt-1" role="button"
                                                        href="utility/proses_selesai.php?no_pengajuan=<?php echo urlencode(trim($no_pengajuan)); ?>&kode_surat=<?php echo urlencode(trim(htmlentities($kode_surat))); ?>&no_pengajuan=<?php echo urlencode(trim(htmlentities($id))); ?>">
                                                        Selesai
                                                        </a>
                                                        <button type="button" class="btn btn-danger mt-lg-0 mt-1" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal"
                                                        data-bs-whatever="<?php echo urlencode(trim(htmlentities($id))); ?>">Tolak</button>
                                                        </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "No results found.";
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


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tolak Surat id :</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="utility/proses_tolak.php" method="POST">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Alasan:</label>
                            <textarea class="form-control" id="alasan" id="alasan" name="alasan"
                                placeholder="alasan"></textarea>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        var exampleModal = document.getElementById('exampleModal')
        exampleModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget
            // Extract info from data-bs-* attributes
            var recipient = button.getAttribute('data-bs-whatever')
            // If necessary, you could initiate an AJAX request here
            // and then do the updating in a callback.
            //
            // Update the modal's content.
            var modalTitle = exampleModal.querySelector('.modal-title')
            var modalBodyInput = exampleModal.querySelector('.modal-body input')

            modalTitle.textContent = 'Tolak Pesan Untuk Id Surat : ' + recipient
            modalBodyInput.value = recipient
        })
    </script>


    <!-- Modal
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Tolak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin akan menolak surat?</p>
                    <form action="utility/proses_tolak.php" method="post">
                        Tambahkan input tersembunyi untuk mengirimkan ID atau data lain yang diperlukan
                        <input type="hidden" id="id" name="" value="<?php echo htmlentities($id); ?>">

                        Input untuk alasan
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan:</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Tambahkan script Bootstrap JavaScript dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Tangkap event yang terjadi setelah modal ditampilkan
        $('#confirmationModal').on('show.bs.modal', function (event) {
            // Ambil tombol yang memunculkan modal
            var button = $(event.relatedTarget);

            // Ambil baris (parent) tombol yang diklik
            var row = button.closest('tr');

            // Ambil nilai dari kolom pertama pada baris yang sesuai
            var idToSend = row.find('td:first').text();

            // Setel nilai ID di dalam modal dan input tersembunyi
            $('#id').val(idToSend);
        });

        // Fungsi untuk mengirim data ke PHP
        function kirimData() {
            // Tidak perlu lagi mengambil nilai inputText karena kita menggunakan formulir
            // Formulir ini akan secara otomatis mengirim nilai ID dan alasan ke PHP
        }





        $(document).ready(function () {
            $('#datatablesSimple').DataTable({
                columnDefs: [
                    { targets: [2, 3, 4], searchable: true }, // Nama Lengkap, Tipe Surat, Tanggal Laporan
                    { targets: [0, 1, 5, 6], searchable: false } // Kolom lainnya tidak dapat dicari
                ]
            });
        });




    </script>






    <!-- modal -->
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