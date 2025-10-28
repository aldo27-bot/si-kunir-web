<?php
include("../koneksi.php"); // pastikan path sesuai

header('Content-Type: application/json');

// Cek apakah parameter username dikirim
if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Parameter username tidak ditemukan."
    ]);
    exit;
}

$username = $_GET['username'];

// Ambil aspirasi terbaru milik user yang sudah ditanggapi admin
$query = "
    SELECT tanggal, jam, tanggapan
    FROM aspirasi
    WHERE username = '$username' AND tanggapan IS NOT NULL
    ORDER BY tanggal DESC, jam DESC
    LIMIT 1
";

$result = mysqli_query($conn, $query);

// Jika query gagal
if (!$result) {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Kesalahan query: " . mysqli_error($conn)
    ]);
    exit;
}

// Jika ada hasil
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    echo json_encode([
        "kode" => 1,
        "tanggapan" => $row['tanggapan'],
        "tanggal" => $row['tanggal'],
        "jam" => $row['jam']
    ]);
} else {
    echo json_encode([
        "kode" => 0,
        "pesan" => "Belum ada tanggapan untuk aspirasi Anda."
    ]);
}
?>
