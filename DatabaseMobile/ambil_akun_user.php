<?php
require('Koneksi.php');

$perintah = "SELECT * FROM `akun_admin` WHERE 1";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek > 0) {
    $response["data"] = array(); // Definisikan array data di sini

    while ($ambil = mysqli_fetch_object($eksekusi)) {
        $data = array();
        $data["username"] = $ambil->username;
        $data["email"] = $ambil->email;
        $data["password"] = $ambil->password;
        $data["nama"] = $ambil->nama;
        
        array_push($response["data"], $data);
    }

    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
} else {
    // Username tidak ditemukan di database
    $response["kode"] = 0;
    $response["pesan"] = "Data Tidak Tersedia";
}

echo json_encode($response);
mysqli_close($konek);
?>
