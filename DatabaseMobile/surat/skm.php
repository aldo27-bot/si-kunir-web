<?php
header("Content-Type: application/json");
include "../Koneksi.php"; // koneksi: variabel $konek

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data dari Android
    $nama               = $_POST['nama'] ?? '';
    $ttl                = $_POST['ttl'] ?? '';
    $jenis_kelamin      = $_POST['jenis_kelamin'] ?? '';
    $agama              = $_POST['agama'] ?? '';
    $alamat             = $_POST['alamat'] ?? '';
    $kewarganegaraan    = $_POST['kewarganegaraan'] ?? '';
    $keterangan         = $_POST['keterangan'] ?? '';
    $username           = $_POST['username'] ?? '';
    $kode_surat         = "SKKM";

    // File default kosong
    $fileName = "";

    // Jika Android mengirim file
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

        $fileName = time() . "_" . basename($_FILES['file']['name']);
        $targetDir = __DIR__ . "/upload_surat/";    // pastikan folder ini ADA
        $targetFilePath = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            $response['status'] = false;
            $response['message'] = "Gagal mengupload file!";
            echo json_encode($response);
            exit();
        }
    }

    // Query insert ke database
    $query = "INSERT INTO surat_keterangan_kematian 
                (nama, alamat, jenis_kelamin, tempat_tanggal_lahir, agama, 
                 kewarganegaraan, keterangan, file, kode_surat, id_pejabat_desa, username)
              VALUES
                ('$nama', '$alamat', '$jenis_kelamin', '$ttl', '$agama',
                 '$kewarganegaraan', '$keterangan', '$fileName', '$kode_surat', NULL, '$username')";

    $result = mysqli_query($konek, $query);

    if ($result) {
        $response['status'] = true;
        $response['message'] = "Surat Keterangan Kematian berhasil disimpan.";
    } else {
        $response['status'] = false;
        $response['message'] = "Gagal menyimpan ke database!";
        $response['error'] = mysqli_error($konek);
    }

} else {
    $response['status'] = false;
    $response['message'] = "Request tidak valid!";
}

echo json_encode($response);
?>
