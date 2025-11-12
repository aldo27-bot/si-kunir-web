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

// Query insert
$query = "INSERT INTO surat_domisili 
(kode_surat, nama, nik, tempat_tanggal_lahir, alamat, jenis_kelamin, pekerjaan, agama, status_perkawinan, keterangan, username)
VALUES 
('$kode_surat', '$nama', '$nik', '$tempat_tanggal_lahir', '$alamat', '$jenis_kelamin', '$pekerjaan', '$agama', '$status_perkawinan', '$keterangan', '$username')";

// Eksekusi
if (mysqli_query($konek, $query)) {
    echo json_encode(["kode" => 1, "pesan" => "Pengajuan Surat Domisili berhasil dikirim"]);
} else {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Gagal menyimpan data",
        "error" => mysqli_error($konek)
    ]);
}
?>
