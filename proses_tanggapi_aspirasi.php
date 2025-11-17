<?php
header("Content-Type: application/json");
include 'utility/sesionlogin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_pengajuan_aspirasi'] ?? '';
    $tanggapan = $_POST['tanggapan'] ?? '';

    if (empty($id) || empty($tanggapan)) {
        echo json_encode(["kode" => 0, "pesan" => "Data tidak lengkap"]);
        exit;
    }

    // 🔹 Update tanggapan aspirasi
    $update = $conn->prepare("UPDATE pengajuan_aspirasi SET tanggapan=?, status='Ditanggapi' WHERE id_pengajuan_aspirasi=?");
    $update->bind_param("si", $tanggapan, $id);

    if ($update->execute()) {
        // 🔹 Ambil username dari aspirasi
        $result = mysqli_query($conn, "SELECT username FROM aspirasi WHERE id_pengajuan_aspirasi='$id'");
        $data = mysqli_fetch_assoc($result);
        $username = $data['username'] ?? '';

        if (empty($username)) {
            echo json_encode(["kode" => 0, "pesan" => "Username tidak ditemukan"]);
            exit;
        }

        // 🔹 Simpan notifikasi
        $pesan_notif = "Aspirasi Anda telah ditanggapi: $tanggapan";
        $notif = $conn->prepare("INSERT INTO notifikasi (username, pesan) VALUES (?, ?)");
        $notif->bind_param("ss", $username, $pesan_notif);

        if ($notif->execute()) {
            echo json_encode([
                "kode" => 1,
                "pesan" => "Tanggapan berhasil disimpan dan notifikasi dikirim"
            ]);
        } else {
            echo json_encode([
                "kode" => 0,
                "pesan" => "Gagal menyimpan notifikasi",
                "error_mysql" => $notif->error
            ]);
        }
    } else {
        echo json_encode([
            "kode" => 0,
            "pesan" => "Gagal menyimpan tanggapan",
            "error_mysql" => $update->error
        ]);
    }
}
?>
