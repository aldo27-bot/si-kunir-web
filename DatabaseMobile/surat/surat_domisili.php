<?php
require("../Koneksi.php");
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil data dari POST
$nama = $_POST['nama'] ?? '';
$nik = $_POST['nik'] ?? '';
$tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$pekerjaan = $_POST['pekerjaan'] ?? '';
$agama = $_POST['agama'] ?? '';
$status_perkawinan = $_POST['status_perkawinan'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$username = $_POST['username'] ?? '';

// Tambahkan default kode surat untuk Surat Keterangan Domisili
$kode_surat = 'SKD';

// Validasi sederhana
if (empty($nama) || empty($nik)) {
    echo json_encode(["kode" => 0, "pesan" => "Data tidak lengkap"]);
    exit;
}

// Handle upload file jika ada
$fileName = '';
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
    $uploadDir = "../surat/upload_surat/"; 
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = "domisili_" . time() . "." . $ext;
    $targetFile = $uploadDir . $fileName;

    if(!move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)){
        echo json_encode([
            "kode" => 0,
            "pesan" => "Gagal mengunggah file"
        ]);
        exit;
    }
}

// Query insert ke tabel surat_domisili, tambahkan kolom file
$query = "INSERT INTO surat_domisili 
(kode_surat, nama, nik, tempat_tanggal_lahir, alamat, jenis_kelamin, pekerjaan, agama, status_perkawinan, keterangan, username, file)
VALUES 
('$kode_surat', '$nama', '$nik', '$tempat_tanggal_lahir', '$alamat', '$jenis_kelamin', '$pekerjaan', '$agama', '$status_perkawinan', '$keterangan', '$username', '$fileName')";

// Eksekusi
if (mysqli_query($konek, $query)) {
    echo json_encode(["kode" => 1, "pesan" => "Pengajuan Surat Domisili berhasil dikirim", "file" => $fileName]);
} else {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Gagal menyimpan data",
        "error" => mysqli_error($konek)
    ]);
}
?>
