<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require("../Koneksi.php"); 
header('Content-Type: application/json');

// Cek koneksi
if (!isset($konek) || $konek->connect_error) {
    $error_msg = isset($konek) ? $konek->connect_error : "Variabel koneksi \$konek tidak ada.";
    echo json_encode(["kode" => false, "pesan" => "Koneksi gagal: " . $error_msg]);
    exit();
}

/* ============================
   1. Ambil Data dari Android
===============================*/
$username       = $_POST['username'] ?? '';
$nama           = $_POST['nama'] ?? '';
$ttl            = $_POST['ttl'] ?? '';
$alamat         = $_POST['alamat'] ?? '';
$lokasi_usaha   = $_POST['lokasi_usaha'] ?? '';
$nama_usaha     = $_POST['nama_usaha'] ?? '';
$jenis_usaha    = $_POST['jenis_usaha'] ?? '';
$tahun_berdiri  = $_POST['tahun_berdiri'] ?? '';
$kode_surat     = $_POST['kode_surat'] ?? 'Surat Usaha';

/* ============================
    2. Validasi wajib
===============================*/
if (
    empty($username) || empty($nama) || empty($ttl) ||
    empty($alamat) || empty($lokasi_usaha) ||
    empty($nama_usaha) || empty($jenis_usaha) || empty($tahun_berdiri)
) {
    echo json_encode(["kode" => false, "pesan" => "Semua data wajib diisi!"]);
    exit();
}

/* ============================
   3. Upload File (Opsional)
===============================*/
$file_upload = null;
$target_dir = "../upload_surat/";

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

    // Buat folder jika belum ada
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    // Generate nama file
    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = round(microtime(true)) . '.' . end($temp);

    $target_file = $target_dir . $newfilename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $file_upload = $newfilename;
    } else {
        echo json_encode(["kode" => false, "pesan" => "Gagal upload file."]);
        exit();
    }
}

/* ============================
   4. Simpan ke Database
===============================*/
$sql = "INSERT INTO surat_usaha 
        (username, nama, ttl, alamat, lokasi_usaha, nama_usaha, jenis_usaha, tahun_berdiri, file, kode_surat) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $konek->prepare($sql);

if (!$stmt) {
    echo json_encode(["kode" => false, "pesan" => "Gagal menyiapkan query: " . $konek->error]);
    exit();
}

// Binding parameter
$stmt->bind_param(
    "ssssssssss",
    $username,
    $nama,
    $ttl,
    $alamat,
    $lokasi_usaha,
    $nama_usaha,
    $jenis_usaha,
    $tahun_berdiri,
    $file_upload,
    $kode_surat
);

// Eksekusi
if ($stmt->execute()) {
    echo json_encode(["kode" => true, "pesan" => "Surat Usaha berhasil diajukan!"]);
} else {
    echo json_encode(["kode" => false, "pesan" => "Gagal menyimpan: " . $stmt->error]);
}

$stmt->close();
$konek->close();
?>
