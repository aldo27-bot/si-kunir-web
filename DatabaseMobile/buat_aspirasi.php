<?php
include 'koneksi.php';

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

// Cek koneksi
if (!$konek) {
    echo json_encode(['error' => 'Koneksi database gagal']);
    exit;
}

// Cek metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Metode request harus POST']);
    exit;
}

// Ambil data dari POST
$judul    = $_POST['judul'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$deskripsi= $_POST['deskripsi'] ?? '';
$foto     = '';

// Validasi input
if (empty($judul) || empty($kategori) || empty($deskripsi)) {
    echo json_encode(['error' => 'Judul, kategori, dan deskripsi dibutuhkan']);
    exit;
}

// Upload file foto jika ada
if (!empty($_FILES['foto']['name']) && isset($_FILES['foto']['tmp_name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $foto = time() . '_' . basename($_FILES['foto']['name']);
    $target_file = $target_dir . $foto;
    move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
}

// Simpan ke database (tanpa username)
$stmt = $konek->prepare("INSERT INTO aspirasi (judul, kategori, deskripsi, foto) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['error' => 'Prepare statement gagal', 'info' => $konek->error]);
    exit;
}

$stmt->bind_param("ssss", $judul, $kategori, $deskripsi, $foto);

if ($stmt->execute()) {
    echo json_encode(['kode' => 1, 'pesan' => 'Aspirasi berhasil dikirim']);
} else {
    echo json_encode(['error' => 'Gagal menyimpan ke database', 'info' => $stmt->error]);
}

$stmt->close();
$konek->close();
?>
