<?php
require('../Koneksi.php');
require('../helpers.php');


$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$nama = $_POST['nama'] ?? '';

$response = array();

// pastikan tidak kosong
if (empty($username) || empty($email) || empty($nama)) {
    $response["kode"] = 3;
    $response["pesan"] = "Data tidak lengkap";
    echo json_encode($response);
    exit;
}

// cek apakah username sudah terdaftar
$cek = mysqli_query($konek, "SELECT * FROM akun_user WHERE username = '$username'");
if (mysqli_num_rows($cek) > 0) {
    $response["kode"] = 0;
    $response["pesan"] = "Username sudah terdaftar";
} else {
    $kode_otp = rand(100000, 999999);
    $query = "INSERT INTO akun_user (username, password, nama, email, kode_otp)
              VALUES ('$username', 'TEMP', '$nama', '$email', '$kode_otp')";

    if (mysqli_query($konek, $query)) {
        $response["kode"] = 1;
        $response["pesan"] = "Registrasi tahap 1 berhasil, OTP dikirim";
        $response["otp"] = $kode_otp; // sementara tampilkan di response
    } else {
        $response["kode"] = 2;
        $response["pesan"] = "Gagal menyimpan data";
    }
}

echo json_encode($response);
mysqli_close($konek);
?>
