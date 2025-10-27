<?php
require('../Koneksi.php');


$username = $_POST['username'];
$kode_otp = $_POST['kode_otp'];
$password = $_POST['password'];

$kata_sandi = md5($password);

// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$username' and kode_otp ='$kode_otp';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek != 0) {
    // jika username belum terdaftar, lakukan proses registrasi
    $perintah = "UPDATE `akun_user` set `password` ='$kata_sandi'  WHERE `username` = '$username'";
    $eksekusi = mysqli_query($konek, $perintah);

    if($eksekusi){
        $response["kode"] = 1;
        $response["pesan"] = "Berhasil Ubah Password";
    }else {
        $response["kode"] = 2;
        $response["pesan"] = "Gagal Ubah Password";
    }
    

    } else {
        
        $response["kode"]= 0;
        $response["pesan"] = "Kode Otp salah";
    }


echo json_encode($response);
mysqli_close($konek);

?>