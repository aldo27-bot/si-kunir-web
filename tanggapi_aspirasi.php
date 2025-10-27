<?php
require 'koneksi.php'; // koneksi MySQLi

// Ambil data POST (JSON)
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['id'], $data['status'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$id = (int)$data['id'];
$status = $data['status'];
$tanggapan = isset($data['tanggapan']) ? trim($data['tanggapan']) : null;

// Validasi status
$allowed = ['menunggu','diproses','selesai']; // sesuai enum di tabel baru
if (!in_array(strtolower($status), $allowed)) {
    echo json_encode(['error' => 'Status invalid']);
    exit;
}

// Jika kolom tanggapan belum ada, bisa buat default null
$query = "UPDATE aspirasi SET status = ?, tanggapan = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $status, $tanggapan, $id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Aspirasi berhasil diperbarui']);
} else {
    echo json_encode(['error' => 'Gagal memperbarui aspirasi']);
}

$stmt->close();
$konek->close();
?>
