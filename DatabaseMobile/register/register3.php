
<?php
require('../Koneksi.php');

// Menerima data dari aplikasi Android
$username = $_POST['username']; // 'email' harus sesuai dengan key yang dikirim dari Android
$password = $_POST['password']; // 'password' harus sesuai dengan key yang dikirim dari Android
$kata_sandi = md5($password);


// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$username';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek = 0) {
    $response["kode"]=0;
    $response["pesan"] = "KESALAHAN INPUT";

    } else {
        // jika username belum terdaftar, lakukan proses registrasi
        $perintah = "UPDATE `akun_user` set `password` ='$kata_sandi'  WHERE `username` = '$username'";
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