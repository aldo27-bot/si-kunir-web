<?php
require('Koneksi.php');

// $perintah = $_POST['sql'];
$perintah = "
";
$eksekusi = mysqli_query($konek, $perintah);

$response = array();

if ($eksekusi) {
    $response["kode"] = 1;
    $response["pesan"] = "Data berhasil diubah";
} else {
    $response["kode"] = 0;
    $response["pesan"] = "Gagal mengubah data: " . mysqli_error($konek);
}

echo json_encode($response);
mysqli_close($konek);
?>
