<?php
require("../Koneksi.php");
require ("../sender/phpmailer.php");
require ('../vendor/autoload.php'); 

// Menerima data dari aplikasi Android
$username = $_POST['username']; // 'email' harus sesuai dengan key yang dikirim dari Android
$kode_otp = mt_rand(100000, 999999);



// periksa  apakah email sudah terdaftar 
$perintah = "SELECT * FROM `akun_user` WHERE username = '$username';";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek = 0) {
   
    $response["kode"]=0;
    $response["pesan"] = "Username tidak tercantum";

    } else {
        $data = mysqli_fetch_assoc($eksekusi);
        $email = $data['email'];
        $type = "Register";
        $mail = new EmailSender();
        $mail->sendEmail($email, $type, $kode_otp);
        
        // jika username belum terdaftar, lakukan proses registrasi
        $perintah = "UPDATE `akun_user` SET `kode_otp` = '$kode_otp' Where username = '$username'";
        $eksekusi = mysqli_query($konek, $perintah);

        


        // $F["username"] = $username;
        // array_push($response["data"], $F);

        if($eksekusi){
            $response["kode"] =1;
            $response["pesan"] = "kode otp berhasil diupdate";
        }else {
            $response["kode"]= 2;
            $response["pesan"] = "koed otp gagal diupdate";
        }
    }


echo json_encode($response);
mysqli_close($konek);
?>