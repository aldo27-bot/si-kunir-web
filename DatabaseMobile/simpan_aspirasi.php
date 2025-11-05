<?php
header("Content-Type: application/json");
include "Koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $foto = $_POST['foto'] ?? '';
    $username = $_POST['username'] ?? '';

    // Validasi data
    if (!$judul || !$deskripsi || !$username) {
        echo json_encode(["kode" => 0, "pesan" => "Data tidak lengkap"]);
        exit;
    }

    // Simpan aspirasi ke tabel aspirasi
    $stmt = $konek->prepare("INSERT INTO aspirasi (judul, kategori, deskripsi, foto, username, status, tanggal) VALUES (?, ?, ?, ?, ?, 'Menunggu', NOW())");
    $stmt->bind_param("sssss", $judul, $kategori, $deskripsi, $foto, $username);

    if ($stmt->execute()) {
        $id_aspirasi = $stmt->insert_id;

        // Buat notifikasi otomatis untuk admin
        $judul_notif = "Aspirasi baru dari $username";
        $pesan_notif = "Judul: $judul";

        $notif = $konek->prepare("INSERT INTO notifikasi (username, judul, pesan, tanggal) VALUES (?, ?, ?, NOW())");
        $notif->bind_param("sss", $username,   $judul_notif, $pesan_notif);
        $notif->execute();

        echo json_encode([
            "kode" => 1,
            "pesan" => "Aspirasi berhasil dikirim",
            "id_aspirasi" => $id_aspirasi
        ]);
    } else {
        echo json_encode([
            "kode" => 0,
            "pesan" => "Gagal menyimpan aspirasi",
            "error_mysql" => $stmt->error
        ]);
    }
}
?>
