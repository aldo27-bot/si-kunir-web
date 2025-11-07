<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require('Koneksi.php');
require('helpers.php');

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$nama     = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$kode_otp = "00000";

$response = [];

// 🔹 Validasi input kosong
if ($username === '' || $password === '' || $email === '' || $nama === '') {
    $response = [
        "kode" => 3,
        "pesan" => "Data tidak lengkap"
    ];
    echo json_encode($response);
    exit;
}

// 🔹 Cek apakah username sudah terdaftar
$cek = mysqli_query($konek, "SELECT * FROM akun_user WHERE username = '$username'");
if (mysqli_num_rows($cek) > 0) {
    $response = [
        "kode" => 0,
        "pesan" => "Username sudah terdaftar"
    ];
    echo json_encode($response);
    exit;
}

// 🔹 Cek apakah email sudah digunakan
$cekEmail = mysqli_query($konek, "SELECT * FROM akun_user WHERE email = '$email'");
if (mysqli_num_rows($cekEmail) > 0) {
    $response = [
        "kode" => 4,
        "pesan" => "Email sudah terdaftar"
    ];
    echo json_encode($response);
    exit;
}

// 🔹 Insert data
$query = "INSERT INTO akun_user (username, password, email, nama, kode_otp)
          VALUES ('$username', '$password', '$email', '$nama', '$kode_otp')";
$eksekusi = mysqli_query($konek, $query);

if ($eksekusi) {
    $response = [
        "kode" => 1,
        "pesan" => "Registrasi berhasil"
    ];
} else {
    $response = [
        "kode" => 2,
        "pesan" => "Registrasi gagal"
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
mysqli_close($konek);
?>
