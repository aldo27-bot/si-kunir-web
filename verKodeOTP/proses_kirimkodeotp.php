<?php
require("../koneksi.php");
require ("../DatabaseMobile/sender/phpmailer.php");
require ('../DatabaseMobile/vendor/autoload.php'); 

session_start();
// $username = $_SESSION['username'];
$username = 'admin';
$kode_otp = mt_rand(100000, 999999);

// Atur header konten untuk memastikan respons adalah JSON
header('Content-Type: application/json');

// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_admin` WHERE username_admin = '$username';";
$eksekusi = mysqli_query($conn, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek == 0) {
    // Ubah dari $cek = 0 menjadi $cek == 0
    $response["kode"]=0;
    $response["pesan"] = "Username tidak tercantum";
    // Hapus header("Location: ../");
    } else {
        $data = mysqli_fetch_assoc($eksekusi);
        $email = $data['email'];
        $type = "Register";
        $mail = new EmailSender();
        $mail->sendEmail($email, $type, $kode_otp);
        
        // jika username belum terdaftar, lakukan proses registrasi
        $perintah = "UPDATE `akun_admin` SET `kode_otp` = '$kode_otp' Where username_admin = '$username'";
        $eksekusi = mysqli_query($conn, $perintah);

        

        if($eksekusi){
            $response["kode"] =1;
            $response["pesan"] = "kode otp berhasil diupdate";
            // Hapus header("Location: ./");
        }else {
            $response["kode"]= 2;
            $response["pesan"] = "kode otp gagal diupdate";
            // Hapus header("Location: ../");
        }
    }


echo json_encode($response);
// Pastikan variabel koneksi yang ditutup adalah $conn, bukan $konek
mysqli_close($conn); 
?>