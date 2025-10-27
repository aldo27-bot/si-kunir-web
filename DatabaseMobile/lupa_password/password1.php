<?php
require("../Koneksi.php");
require ("../sender/phpmailer.php");
require '../vendor/autoload.php'; 

// Menerima data dari aplikasi Android
$username = $_POST['username']; // 'email' harus sesuai dengan key yang dikirim dari Android
$kode_otp = mt_rand(100000, 999999);



// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$username';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek != 0) {
    $data = mysqli_fetch_assoc($eksekusi);
    $email = $data['email'];

    $perintah = "UPDATE `akun_user` 
    SET `kode_otp` = '$kode_otp' 
    WHERE `username` = '$username'";

    $type ="Lupa Password";

    $mail = new EmailSender();
    $mail->sendEmail($email, $type, $kode_otp);

    $eksekusi = mysqli_query($konek, $perintah);


    if ($eksekusi) {
        $response["kode"] = 1;
        $response["pesan"] = "Update Berhasil";
    } else {
        $response["kode"] = 2;
        $response["pesan"] = "Update Gagal";
    }


} else {
    $response["kode"] = 0;
    $response["pesan"] = "Username tidak ada";

}


echo json_encode($response);
mysqli_close($konek);
?>