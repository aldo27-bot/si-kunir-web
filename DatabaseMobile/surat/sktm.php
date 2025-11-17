<?php
header('Content-Type: application/json');

// Koneksi
include '../Koneksi.php';

$response = [];

// === Folder upload ===
$folder = "../surat/upload_surat/"; 
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$file_to_db = ""; // default kosong

// ====== UPLOAD FILE ======
if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != "") {

    $namaFile = "sktm_" . time() . ".jpg";
    $targetPath = $folder . $namaFile;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        $file_to_db = $namaFile;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal upload file"
        ]);
        exit;
    }
}

// ====== AMBIL DATA POST ======
$nama = $_POST['nama'];
$tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'];
$asal_sekolah = $_POST['asal_sekolah'];
$keperluan = $_POST['keperluan'];

$nama_orangtua = $_POST['nama_orangtua'];
$nik_orangtua = $_POST['nik_orangtua'];
$alamat_orangtua = $_POST['alamat_orangtua'];
$tempat_tanggal_lahir_orangtua = $_POST['tempat_tanggal_lahir_orangtua'];
$pekerjaan_orangtua = $_POST['pekerjaan_orangtua'];

$kode_surat = $_POST['kode_surat'];
$username = $_POST['username'];

// ====== QUERY INSERT TANPA id_pejabat_desa ======
$query = "INSERT INTO surat_sktm (
    nama,
    tempat_tanggal_lahir,
    asal_sekolah,
    keperluan,
    nama_orangtua,
    nik_orangtua,
    alamat_orangtua,
    tempat_tanggal_lahir_orangtua,
    pekerjaan_orangtua,
    file,
    kode_surat,
    username
) VALUES (
    '$nama',
    '$tempat_tanggal_lahir',
    '$asal_sekolah',
    '$keperluan',
    '$nama_orangtua',
    '$nik_orangtua',
    '$alamat_orangtua',
    '$tempat_tanggal_lahir_orangtua',
    '$pekerjaan_orangtua',
    '$file_to_db',
    '$kode_surat',
    '$username'
)";

if (mysqli_query($konek, $query)) {
    echo json_encode([
        "status" => "success",
        "message" => "Pengajuan SKTM berhasil",
        "file" => $file_to_db
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($konek)
    ]);
}
?>
