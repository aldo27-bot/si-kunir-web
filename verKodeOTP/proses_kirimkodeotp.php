<?php
require("../koneksi.php");
require ("../DatabaseMobile/sender/phpmailer.php");
require ('../DatabaseMobile/vendor/autoload.php'); 

session_start();
// $username = $_SESSION['username'];
$username = 'admin';
$kode_otp = mt_rand(100000, 999999);



// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_admin` WHERE username = '$username';";
$eksekusi = mysqli_query($conn, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek = 0) {
   
    $response["kode"]=0;
    $response["pesan"] = "Username tidak tercantum";
    header("Location: ../");

    } else {
        $data = mysqli_fetch_assoc($eksekusi);
        $email = $data['email'];
        $type = "Register";
        $mail = new EmailSender();
        $mail->sendEmail($email, $type, $kode_otp);
        
        // jika username belum terdaftar, lakukan proses registrasi
        $perintah = "UPDATE `akun_admin` SET `kode_otp` = '$kode_otp' Where username = '$username'";
        $eksekusi = mysqli_query($conn, $perintah);

        

        if($eksekusi){
            $response["kode"] =1;
            $response["pesan"] = "kode otp berhasil diupdate";
            header("Location: ./");
        }else {
            $response["kode"]= 2;
            $response["pesan"] = "kode otp gagal diupdate";
            // header("Location: ../");
        }
    }


echo json_encode($response);
mysqli_close($konek);
?>