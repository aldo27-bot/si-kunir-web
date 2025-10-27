<?php
header('Content-Type: application/json; charset=utf-8');

require("../Koneksi.php");
require("../sender/phpmailer.php");
require("../vendor/autoload.php");

// Ambil data dari POST
$username = $_POST['username'] ?? '';
$nama     = $_POST['nama'] ?? '';
$email    = $_POST['email'] ?? '';
$kode_otp = mt_rand(100000, 999999);
$response = array();

// Cek data kosong
if (empty($username) || empty($nama) || empty($email)) {
    $response["kode"] = 3;
    $response["pesan"] = "Data tidak lengkap";
    echo json_encode($response);
    exit;
}

// 🔹 Cek apakah EMAIL sudah terdaftar
$cekEmail = "SELECT * FROM akun_user WHERE email = '$email'";
$hasilEmail = mysqli_query($konek, $cekEmail);
$jmlEmail = mysqli_num_rows($hasilEmail);

if ($jmlEmail > 0) {
    $response["kode"] = 4;
    $response["pesan"] = "Email sudah terdaftar";
} else {
    // 🔹 Cek apakah USERNAME sudah terdaftar
    $cekUsername = "SELECT * FROM akun_user WHERE username = '$username'";
    $hasilUser = mysqli_query($konek, $cekUsername);
    $jmlUser = mysqli_num_rows($hasilUser);

    if ($jmlUser > 0) {
        $response["kode"] = 0;
        $response["pesan"] = "Username sudah terdaftar";
    } else {
        // 🔹 Kirim OTP ke email
        $type = "Register";
        $mail = new EmailSender();
        $mail->sendEmail($email, $type, $kode_otp);

        // 🔹 Simpan ke database
        $insert = "INSERT INTO akun_user (username, email, nama, kode_otp)
                   VALUES ('$username', '$email', '$nama', '$kode_otp')";
        $eksekusi = mysqli_query($konek, $insert);

        if ($eksekusi) {
            $response["kode"] = 1;
            $response["pesan"] = "Registrasi berhasil";
        } else {
            $response["kode"] = 2;
            $response["pesan"] = "Registrasi gagal";
        }
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
mysqli_close($konek);
?>
