<?php
include("../Koneksi.php");
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DATA dari masyarakat
$username   = $_POST['username'] ?? '';
$nama       = $_POST['nama'] ?? '';
$alamat     = $_POST['alamat'] ?? '';
$ttl        = $_POST['tempat_tanggal_lahir'] ?? '';
$kode_surat = $_POST['kode_surat'] ?? 'SKU';

// VALIDASI WAJIB
if (empty($username) || empty($nama) || empty($alamat) || empty($ttl)) {
    echo json_encode([
        "status" => false,
        "message" => "Lengkapi semua data!"
    ]);
    exit;
}

// Nomor pengajuan otomatis
$no_pengajuan = "SKU-" . time();

// PROCESS FILE OPSIONAL
$fileName = null;
if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] == 0) {
    $folder = "../surat/upload_surat/"; 
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Hindari nama file duplikat
    $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = $no_pengajuan . "." . $fileExt;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $folder . $fileName)) {
        echo json_encode([
            "status" => false,
            "message" => "Gagal meng-upload file!"
        ]);
        exit;
    }
}

// Insert ke TABEL SKU
$sql = "INSERT INTO surat_keterangan_usaha 
        (no_pengajuan, nama, alamat, tempat_tanggal_lahir, file, kode_surat, username)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $konek->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $no_pengajuan,
    $nama,
    $alamat,
    $ttl,
    $fileName, // Jika tidak ada file, tetap NULL
    $kode_surat,
    $username
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Pengajuan berhasil dikirim!",
        "no_pengajuan" => $no_pengajuan
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal mengirim pengajuan"
    ]);
}
?>
