<?php
require('Koneksi.php');

// Menerima data dari aplikasi Android
$kode_surat = $_POST['kode_surat']; // 'kode_surat' harus sesuai dengan key yang dikirim dari Android

$perintah = "SHOW COLUMNS FROM `$kode_surat`;";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek > 0) {
    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
    $response["data"] = array();

    // Mengambil nama kolom
    while ($row = mysqli_fetch_assoc($eksekusi)) {
        $response["data"][] = $row['Field'];
    }
} else {
    // Data tidak tersedia
    $response["kode"] = 0;
    $response["pesan"] = "Data Tidak Tersedia";
}

echo json_encode($response);
mysqli_close($konek);
?>
