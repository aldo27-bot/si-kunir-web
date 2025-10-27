<?php
include 'utility/sesionlogin.php';
// Menangani submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_surat = $_POST['jenis_surat']; // Tambahkan ini untuk mendapatkan jenis surat dari input
    $kode_surat = $_POST['kode_surat']; 
    $nama_pemohon = $_POST['nama_pemohon'];
    $tanggal = date("Y-m-d"); // Menyimpan tanggal hari ini

    // Menyimpan data ke database berdasarkan jenis surat
    switch ($jenis_surat) {
        case 'sktm':
            $sql = "INSERT INTO sktm (no_pengajuan, username, kode_surat, nama_bapak, tempat_tanggal_lahir_bapak, pekerjaan_bapak, alamat_bapak, nama_ibu, tempat_tanggal_lahir_ibu, pekerjaan_ibu, alamat_ibu, nama, nik, tempat_tanggal_lahir_anak, jenis_kelamin_anak, alamat, keperluan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssssssss", $no_pengajuan, $username, $kode_surat, $nama_bapak, $ttl_bapak, $pekerjaan_bapak, $alamat_bapak, $nama_ibu, $ttl_ibu, $pekerjaan_ibu, $alamat_ibu, $nama_anak, $nik_anak, $ttl_anak, $jk_anak, $alamat_anak, $keperluan);
            break;
        case 'skck':
            $sql = "INSERT INTO skck (no_pengajuan, kode_surat, nama, nik, tempat_tgl_lahir, kebangsaan, agama, jenis_kelamin, status_perkawinan, pekerjaan, alamat, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssss", $no_pengajuan, $kode_surat, $nama, $nik, $ttl, $kebangsaan, $agama, $jk, $status, $pekerjaan, $alamat, $username);
            break;
        case 'surat_kematian':
            $sql = "INSERT INTO surat_kematian (no_pengajuan, nik, kode_surat, username, nama, jenis_kelamin, tanggal_kematian, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $no_pengajuan, $nik, $kode_surat, $username, $nama, $jk, $tanggal_kematian, $alamat);
            break;
        case 'surat_ijin':
            $sql = "INSERT INTO surat_ijin (no_pengajuan, kode_surat, username, nama, nik, jenis_kelamin, tempat_tanggal_lahir, kewarganegaraan, agama, pekerjaan, alamat, tempat_kerja, bagian, tanggal, alasan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssssss", $no_pengajuan, $kode_surat, $username, $nama, $nik, $jk, $ttl, $kewarganegaraan, $agama, $pekerjaan, $alamat, $tempat_kerja, $bagian, $tanggal, $alasan);
            break;
        default:
            echo "<script>alert('Jenis surat tidak valid');</script>";
            return;
    }

    if ($stmt->execute()) {
        echo "<script>alert('Surat berhasil dibuat dan disimpan ke database!');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan surat: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pembuatan Surat</title>
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
        <div class="container mt-5">
            <h2>Pembuatan Surat</h2>
            <form method="POST" action="pembuatansurat.php">
                <div class="mb-3">
                    <label for="jenis_surat" class="form-label">Pilih Jenis Surat</label>
                    <select class="form-select" id="jenis_surat" name="jenis_surat" required onchange="showFields()">
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="sktm">Surat Keterangan Tidak Mampu (SKTM)</option>
                        <option value="skck">Surat Keterangan Catatan Kepolisian (SKCK)</option>
                        <option value="surat_kematian">Surat Kematian</option>
                        <option value="surat_ijin">Surat Ijin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                    <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" required>
                </div>

                <!-- Field untuk SKTM -->
                <div id="form-sktm" style="display: none;">
                    <div class="mb-3">
                        <label for="nama_bapak" class="form-label">Nama Bapak</label>
                        <input type="text" class="form-control" id="nama_bapak" name="nama_bapak">
                    </div>
                    <div class="mb-3">
                        <label for="alamat_bapak" class="form-label">Alamat Bapak</label>
                        <input type="text" class="form-control" id="alamat_bapak" name="alamat_bapak">
                    </div>
                    <div class="mb-3">
                        <label for="nama_ibu" class="form-label">Nama Ibu</label>
                        <input type="text" class="form-control" id="nama_ibu" name="nama_ibu">
                    </div>
                    <div class="mb-3">
                        <label for="alamat_ibu" class="form-label">Alamat Ibu</label>
                        <input type="text" class="form-control" id="alamat_ibu" name="alamat_ibu">
                    </div>
                    <div class="mb-3">
                        <label for="alamat_ibu" class="form-label">Alamat Ibu</label>
                        <input type="text" class="form-control" id="alamat_ibu" name="alamat_ibu">
                    </div><div class="mb-3">
                        <label for="alamat_ibu" class="form-label">Alamat Ibu</label>
                        <input type="text" class="form-control" id="alamat_ibu" name="alamat_ibu">
                    </div>
                </div>

                <!-- Field untuk SKCK -->
                <div id="form-skck" style="display: none;">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik">
                    </div>
                    <div class="mb-3">
                        <label for="kebangsaan" class="form-label">Kebangsaan</label>
                        <input type="text" class="form-control" id="kebangsaan" name="kebangsaan">
                    </div>
                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama</label>
                        <input type="text" class="form-control" id="agama" name="agama">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <!-- Tambahkan field tambahan untuk SKCK sesuai kebutuhan -->
                </div>

                <!-- Field untuk Surat Kematian -->
                <div id="form-surat-kematian" style="display: none;">
                    <div class="mb-3">
                        <label for="tanggal_kematian" class="form-label">Tanggal Kematian</label>
                        <input type="date" class="form-control" id="tanggal_kematian" name="tanggal_kematian">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat">
                    </div>
                    <!-- Tambahkan field tambahan untuk Surat Kematian sesuai kebutuhan -->
                </div>

                <!-- Field untuk Surat Ijin -->
                <div id="form-surat-ijin" style="display: none;">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Ijin</label>
                        <input type="text" class="form-control" id="alasan" name="alasan">
                    </div>
                    <div class="mb-3">
                        <label for="tempat_kerja" class="form-label">Tempat Kerja</label>
                        <input type="text" class="form-control" id="tempat_kerja" name="tempat_kerja">
                    </div>
                    <!-- Tambahkan field tambahan untuk Surat Ijin sesuai kebutuhan -->
                </div>

                <button type="submit" class="btn btn-primary">Simpan Surat</button>
            </form>
        </div>
    </div>

    <script>
        function showFields() {
            document.getElementById('form-sktm').style.display = 'none';
            document.getElementById('form-skck').style.display = 'none';
            document.getElementById('form-surat-kematian').style.display = 'none';
            document.getElementById('form-surat-ijin').style.display = 'none';

            var jenis_surat = document.getElementById('jenis_surat').value;
            if (jenis_surat == 'sktm') {
                document.getElementById('form-sktm').style.display = 'block';
            } else if (jenis_surat == 'skck') {
                document.getElementById('form-skck').style.display = 'block';
            } else if (jenis_surat == 'surat_kematian') {
                document.getElementById('form-surat-kematian').style.display = 'block';
            } else if (jenis_surat == 'surat_ijin') {
                document.getElementById('form-surat-ijin').style.display = 'block';
            }
        }
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
    <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <!-- <script src="js/datatables-simple-demo.js"></script> -->
     
    </script>
</body>
</html>
