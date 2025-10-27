<?php
include '../koneksi.php';

// Aktifkan error reporting untuk debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set header untuk JSON response
header('Content-Type: application/json');

// Baca input JSON
$data = json_decode(file_get_contents("php://input"), true);
$no_pengajuan = $data['no_pengajuan'];
$updates = $data['data']; // Data kolom dan nilai baru

// Validasi input
if (!empty($no_pengajuan) && !empty($updates) && is_array($updates)) {
    // Mulai query update
    $query = "UPDATE `kode_surat` SET ";
    $params = [];
    $types = '';

    foreach ($updates as $column => $value) {
        $query .= "`$column` = ?, ";
        $params[] = $value;
        $types .= 's';
    }

    $query = rtrim($query, ', ');
    $query .= " WHERE no_pengajuan = ?";
    $params[] = $no_pengajuan;
    $types .= 's';

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["success" => false, "error" => "Error pada query: " . $conn->error]);
        exit;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Gagal memperbarui data: " . $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Data tidak valid atau no_pengajuan kosong"]);
}
