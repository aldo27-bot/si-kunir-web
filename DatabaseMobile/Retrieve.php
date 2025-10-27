<?php 
require('Koneksi.php');

$perintah = "SELECT * FROM `akun_user` ;";
$eksekusi = mysqli_query($konek,$perintah);
$cek = mysqli_affected_rows($konek);

$response = array();

if($cek > 0){
    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
    $response["data"] = array();
    while ($ambil = mysqli_fetch_object($eksekusi)) {
         $F["username"] = $ambil->username;
            $F["password"] = $ambil->password;
            $F["email"] = $ambil->email;
            $F["nama"] = $ambil->nama;
            $F["kode_otp"] = $ambil->kode_otp;
        array_push($response["data"],$F);
    }
}
else {
    $response["kode"] = 0;
    $response["pesan"] = "Data Tidak Tersedia";
}

echo json_encode($response);
mysqli_close($konek);
?>