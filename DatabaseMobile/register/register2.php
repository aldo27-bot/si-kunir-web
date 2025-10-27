<?php
require("../Koneksi.php");

// Menerima data dari aplikasi Android
$username = $_POST['username']; // 'email' harus sesuai dengan key yang dikirim dari Android
$kode_otp= $_POST['kode_otp']; // 'password' harus sesuai dengan key yang dikirim dari Android



// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$username' and kode_otp ='$kode_otp';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek > 0) {
    $response["kode"]=0;
    $response["pesan"] = "Lanjut Registrasi";

    } else {
       

        if($eksekusi){
            $response["kode"] = 1;
            $response["pesan"] = "KODE OTP SALAH";
        }
        
    }


echo json_encode($response);
mysqli_close($konek);
?>