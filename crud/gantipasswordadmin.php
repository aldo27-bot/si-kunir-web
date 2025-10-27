<?php 
require("../koneksi.php");

// Menerima data dari aplikasi Android
$kode_otp = mt_rand(100000, 999999);

// Periksa apakah username terdaftar
$perintah = "SELECT * FROM `akun_admin` WHERE 1";
$eksekusi = mysqli_query($conn, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek == 0) {
    $response["kode"] = 0;
    $response["pesan"] = "Username tidak tercantum";
} else {
    $data = mysqli_fetch_assoc($eksekusi);
    $username = $data['username'];

    // Update kode OTP di database tanpa mengirim email
    $perintah = "UPDATE `akun_admin` SET `kode_otp` = '$kode_otp' WHERE username = '$username'";
    $eksekusi = mysqli_query($conn, $perintah);

    if ($eksekusi) {
        $response["kode"] = 1;
        $response["pesan"] = "Kode OTP berhasil diupdate";
        header("Location: ../ubahpassword/");
    } else {
        $response["kode"] = 2;
        $response["pesan"] = "Kode OTP gagal diupdate";
    }
}

echo json_encode($response);
mysqli_close($conn);
?>
