<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "si_kunir";

$konek = new mysqli($host, $username, $password, $database);

// 🔹 Cek koneksi tapi tetap kembalikan JSON biar Retrofit bisa baca
if ($konek->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "kode" => 500,
        "pesan" => "Koneksi ke database gagal: " . $konek->connect_error
    ]);
    exit; // hentikan script dengan output JSON
}
?>
