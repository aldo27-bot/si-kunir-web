<?php
require 'koneksi.php';
require '../helpers.php';

// Membuat koneksi mysqli
$konek = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($konek->connect_error) {
    die("Koneksi gagal: " . $konek->connect_error);
}

// Ambil semua aspirasi tanpa username
$sql = "
    SELECT id_pengajuan_aspirasi, judul, kategori, deskripsi, foto, status, tanggal, tanggapan
    FROM pengajuan_aspirasi
    ORDER BY tanggal DESC
";

$result = $konek->query($sql);

$list = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $list[] = $row;
    }
}

// Kirim response JSON
json_response(['pengajuan_aspirasi' => $list]);

// Tutup koneksi
$konek->close();
?>
