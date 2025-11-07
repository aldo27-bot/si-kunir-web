<?php
// --- CORS FIX ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Kalau preflight OPTIONS → hentikan di sini
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
// --- END CORS FIX ---

header("Content-Type: application/json");
include "Koneksi.php";

// Log metode request
error_log("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD']);

// Cek metode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["kode" => 0, "pesan" => "Metode tidak diizinkan (" . $_SERVER['REQUEST_METHOD'] . ")"]);
    exit;
}

// 🔍 Debug log semua data masuk
error_log("POST DATA: " . print_r($_POST, true));
error_log("FILES DATA: " . print_r($_FILES, true));

// Ambil data dari body
$judul     = $_POST['judul'] ?? '';
$kategori  = $_POST['kategori'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$username  = $_POST['username'] ?? '';

// Validasi input dasar
if (empty($judul) || empty($deskripsi) || empty($username)) {
    echo json_encode(["kode" => 0, "pesan" => "Data tidak lengkap"]);
    exit;
}

// Upload foto (opsional)
$fotoPath = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && !empty($_FILES['foto']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . '_' . basename($_FILES['foto']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
        $fotoPath = $targetFile;
        error_log("Foto berhasil diupload ke: " . $targetFile);
    } else {
        error_log("Gagal upload foto");
        echo json_encode(["kode" => 0, "pesan" => "Gagal mengupload foto"]);
        exit;
    }
} else {
    error_log("Tidak ada foto diunggah");
}

// Simpan ke database
$sql = "INSERT INTO aspirasi (judul, kategori, deskripsi, foto, username, status, tanggal) 
        VALUES (?, ?, ?, ?, ?, 'Menunggu', NOW())";
error_log("SQL yang dijalankan: " . $sql);

$stmt = $konek->prepare($sql);

if (!$stmt) {
    error_log(" Prepare gagal: " . $konek->error);
    echo json_encode([
        "kode" => 0,
        "pesan" => "Gagal menyiapkan query",
        "error_mysql" => $konek->error
    ]);
    exit;
}

// Bind parameter
$stmt->bind_param("sssss", $judul, $kategori, $deskripsi, $fotoPath, $username);

if ($stmt->execute()) {
    $id_aspirasi = $stmt->insert_id;

    // Tambahkan notifikasi untuk admin
    $judul_notif = "Aspirasi baru dari $username";
    $pesan_notif = "Judul: $judul";

    $notif = $konek->prepare("INSERT INTO notifikasi (username, judul, pesan, tanggal) VALUES (?, ?, ?, NOW())");
    if ($notif) {
        $notif->bind_param("sss", $username, $judul_notif, $pesan_notif);
        $notif->execute();
        $notif->close();
    } else {
        error_log("Gagal menambahkan notifikasi: " . $konek->error);
    }

    echo json_encode([
        "kode" => 1,
        "pesan" => "Aspirasi berhasil dikirim",
        "id_aspirasi" => $id_aspirasi
    ]);

    error_log("Aspirasi baru berhasil ditambahkan oleh $username");
} else {
    error_log("Eksekusi gagal: " . $stmt->error);
    echo json_encode([
        "kode" => 0,
        "pesan" => "Gagal menyimpan aspirasi",
        "error_mysql" => $stmt->error
    ]);
}

$stmt->close();
$konek->close();
?>
