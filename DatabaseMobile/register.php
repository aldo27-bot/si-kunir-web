<?php
require('Koneksi.php');
require('helpers.php');

// Menerima data dari aplikasi Android
$em = $_POST['username']; // 'email' harus sesuai dengan key yang dikirim dari Android
$pas = $_POST['password']; // 'password' harus sesuai dengan key yang dikirim dari Android
$email = $_POST['email'];
$nama = $_POST['nama'];
$kode_otp = "0";

// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$em';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek > 0) {
    $response["kode"]=0;
    $response["peasn"] = "email sudah terdaftar";

    } else {
        // jika username belum terdaftar, lakukan proses registrasi
        $perintah = "INSERT INTO `akun_user`(`username`, `password`, `email`, `nama`, `kode_otp`) 
        VALUES ('$em','$pas','$email','$nama','00000')";
        $eksekusi = mysqli_query($konek, $perintah);

        if($eksekusi){
            $response["kode"] =1;
            $response["pesan"] = "registrasi berhasil";
        }else {
            $response["kode"]= 2;
            $response["pesan"] = "Registrasi gagal";
        }
    }


echo json_encode($response);
mysqli_close($konek);

?>